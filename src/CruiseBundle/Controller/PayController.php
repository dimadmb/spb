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

		if(null == $order_id)
		{
			return $this->redirectToRoute('homepage');
		}
		
		
		$order = $this->getDoctrine()->getRepository('CruiseBundle:Ordering')->findOneById($order_id);
		
		if(null == $order->getStatus())
		{
			return $this->redirectToRoute('homepage');
		}		
		
		
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
			"&back_url_s=https://vodohod.spb.ru/smallfleet/success".
			"&back_url_f=https://vodohod.spb.ru/smallfleet/".
			"&o.order_id=$order_id"/*.
			/*"&o.amount=".$sum*100*/ ;		
		
		 return new RedirectResponse($url_redirect);
		
		//$this->redirect($url_redirect);
		
		//return [$request,$sum,$url_redirect];
	}
	
	
	
    /**
     * @Route("/admin/mail/{id}", name="mail")
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
		$order = $this->extOrder($order);
		
		$mailer = $this->get('mailer');
		$message = \Swift_Message::newInstance()
			->setSubject('Электронный билет на речную прогулку №'.$order_id)
			->setFrom(array('nobody@vodohod.com'=>'Интернет-магазин «ВодоходЪ СПб»'))
			->setTo(array($email))
			->setBcc(array('dkochetkov@vodohod.ru'))
			->setBody("Спасибо за покупку!", 'text/html')		
			;	
		
		//$body = $this->renderView('CruiseBundle:Pay:ticket.html.twig',['order'=>$order]);
		
		$cruises = $order;
		
		$bodyes = [];
		
		foreach($order->cruises as $cruise_id => $cruise)
		{
			 $bodyes[$cruise_id] = $body = $this->renderView('CruiseBundle:Pay:ticket.html.twig',['order'=>$order, 'cruiseId' => $cruise_id]);

			$file_pdf =  new Response(
							$this->get('knp_snappy.pdf')->getOutputFromHtml($body)
								);	

			$message->attach(\Swift_Attachment::newInstance()
					  ->setFilename('Билет '.$order_id.' '.$cruise->getDirection()->getName().'.pdf')
					  ->setContentType('application/pdf')
					  ->setBody($file_pdf))	
			;
					
		}
		
		$mailer->send($message);
		
		return ['cruises'=>$cruises,'bodyes'=>$bodyes];
	}
	

    private function extOrder($order = null)
	{
		//$order_id = $id;
		//
        //$em = $this->getDoctrine()->getManager();
        //$order = $em->getRepository('CruiseBundle:Ordering')->findOneById($order_id);			
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
