<?php

namespace CruiseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PayController extends Controller
{
    /**
     * @Route("/success", name="success")
	 * @Template()		 
     */
    public function successAction(Request $request)
	{
		$session = $request->getSession();
		$order_id = $session->get('order');
		
		$order = $this->getDoctrine()->getRepository('CruiseBundle:Ordering')->findOneById($order_id);
		
		return ['order'=>$order, 'order_id'=>$order_id];
	}
	




    /**
     * @Route("/pay", name="to_pay")
     * @Method({"POST"})
	 * @Template()		 
     */
    public function indexAction(Request $request)
	{
		$session = $request->getSession();
		$order_id = $session->get('order');
		
		
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository('CruiseBundle:Ordering')->findOneById($order_id);	
		
		
		$order
			->setName($request->request->get('name'))
			->setEmail($request->request->get('email'))
			->setPhone($request->request->get('phone'))
		;
		
		$em->persist($order);
		$em->flush();
		
		
		$sum = $em->getRepository('CruiseBundle:OrderCruise')->getTotalCost($order_id);
		
		/// проверить что всё хорошо, иначе отправить назад
		
		//  редиректим на оплату в газпромбанк 
		
		$url_redirect = "https://www.pps.gazprombank.ru:443/payment/start.wsm?lang=RU".
			"&merch_id=819879E11103E1BD879D651446DE601B".
			"&back_url_s=https://vodohod.spb.ru/smallfleet/order/success".
			"&back_url_f=https://vodohod.spb.ru/smallfleet/order/fail".
			"&o.order_id=$order_id"/*.
			/*"&o.amount=".$sum*100*/ ;		
		
		 return new RedirectResponse($url_redirect);
		
		//$this->redirect($url_redirect);
		
		//return [$request,$sum,$url_redirect];
	}
	
	
	
    /**
     * @Route("/mail/{id}", name="mail")
	 * @Template()		 
     */
    public function mailAction(Request $request, $id = null)
	{
		if(null == $id)
		{
			$session = $request->getSession();
			$order_id = $session->get('order');			
		}
		else
		{
			$order_id = $id;
		}
		
		$order = $this->getDoctrine()->getRepository('CruiseBundle:Ordering')->findOneById($order_id);
		$email = $order->getEmail();
	
		
		$body = $this->render('CruiseBundle:Pay:ticket.html.twig',['order'=>$this->extOrder($order_id)])->getContent();
		
		//$dump = get_class_methods($this->get('knp_snappy.pdf'));

		$file_pdf =  new Response(
						$this->get('knp_snappy.pdf')->getOutputFromHtml($body)
							);	
		$file_img =  new Response(
								$this->get('knp_snappy.image')->getOutputFromHtml($body),
								200,
								array(
									'Content-Type'          => 'image/jpg',
									'Content-Disposition'   => 'filename="image.jpg"'
								)
							);	
		
		
		$mailer = $this->get('mailer');
		$message = \Swift_Message::newInstance()
			->setSubject('Электронный билет на речную прогулку №'.$order_id)
			->setFrom(array('nobody@vodohod.com'=>'Интернет-магазин «ВодоходЪ СПб»'))
			->setTo(array($email))
			->setCc(array('dkochetkov@vodohod.ru'))

			->setBody($body, 'text/html')
			
			->attach(\Swift_Attachment::newInstance()
				  ->setFilename('Билет.pdf')
				  ->setContentType('application/pdf')
				  ->setBody($file_pdf))	
				  
			->attach(\Swift_Attachment::newInstance()
				  ->setFilename('Билет.jpg')
				  ->setContentType('image/jpg')
				  ->setBody($file_img->getContent()))
			
		;
		$mailer->send($message);		
		
		return ['body'=>$body];
	}
	

    private function extOrder($id = null)
	{
		$order_id = $id;
		
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository('CruiseBundle:Ordering')->findOneById($order_id);			
		$cruises = [];
		$category = [];
		foreach($order->getOrderCruise() as $orderCruise)
		{
			$cruises[$orderCruise->getCruise()->getId()]  = $orderCruise->getCruise();
			
			
			$category[$orderCruise->getCategory()->getId()] ['orderCruises'][] = $orderCruise;
			$category[$orderCruise->getCategory()->getId()] ['category'] =   $orderCruise->getCategory();
			$category[$orderCruise->getCategory()->getId()] ['count'] =   $orderCruise->getCount();
			//$category[$orderCruise->getCategory()->getId()] ['count'] =   $orderCruise->getCount();
		}
		$order->cruises = $cruises;
		$order->category = $category;

		
		return $order;		
	}
	
	
}
