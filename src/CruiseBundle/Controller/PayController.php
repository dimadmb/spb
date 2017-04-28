<?php

namespace CruiseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

class PayController extends Controller
{
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
		/*
		$url_redirect = "https://www.pps.gazprombank.ru:443/payment/start.wsm?lang=RU".
			"&merch_id=5DA408E6A74D8365E20AB20DB6789729".
			"&back_url_s=http://volga-vodohod.ru/river-walks/order/success.htm".
			"&back_url_f=http://volga-vodohod.ru/river-walks/order/fail.htm".
			"&o.order_id=$order_id".
			"&o.amount=".$out_summ*100 ;		
		*/
		
		return [$request,$sum];
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
			->setTo(array('dkochetkov@vodohod.ru'))

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
