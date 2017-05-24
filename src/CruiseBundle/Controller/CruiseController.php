<?php

namespace CruiseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use CruiseBundle\Entity\OrderCruise;
use CruiseBundle\Entity\Ordering;


class CruiseController extends Controller
{
    /**
     * @Route("/feedback", name="feedback")
	 * @Template()		 
     */
    public function feedbackAction(Request $request)
	{

		$form = $this->createForm('CruiseBundle\Form\FeedbackType');
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) 
		{
			$name = $request->request->get('name');
			$email = $request->request->get('email');
			$body = $request->request->get('body');
			$subject = $request->request->get('subject');
			
			
			$mailer = $this->get('mailer');
			$message = \Swift_Message::newInstance()
				->setSubject($subject)
				//->setFrom(array('nobody@vodohod.com'=>'Интернет-магазин «ВодоходЪ СПб» Обратная связь'))
				->setFrom(array('nobody@vodohod.com'=>'Интернет-магазин «ВодоходЪ СПб» Обратная связь'))
				->setTo(array('dkochetkov@vodohod.ru'))
				->setCc(array('dkochetkov@vodohod.ru'))


				->setBody('email '.$email.'<br>Имя '.$name.'<br>Сообщение<br>'.$body, 'text/html')
				

				
			;
			$mailer->send($message);	

			//$this->redirectToRoute('feedback');
			
			$request->getSession()->getFlashBag()->add('success', 'Ваше сообшение отправлено.');
			
			return $this->redirectToRoute('feedback');
			
			//$form->remove();
			
			
		}


		return ['form'=> $form->createView(),get_class_methods($form)];
		
	}
    /**
     * @Route("/rules", name="rules")
	 * @Template()		 
     */
    public function rulesAction(Request $request)
	{
		return [];
	}
	
	
    /**
     * @Route("/", name="homepage")
	 * @Template()		 
     */
    public function indexAction(Request $request)
	{
		$error = [];
		$dump = '';
		
		$em = $this->getDoctrine()->getManager();
		
		
		
		
		if($request->getMethod() == 'POST')
		{
			$cruise1 = $this->getDoctrine()->getRepository('CruiseBundle:Cruise')->findOneById($request->request->get('cruise1'));
			$cruise2 = $this->getDoctrine()->getRepository('CruiseBundle:Cruise')->findOneById($request->request->get('cruise2'));
			
			

			
			$order = new Ordering();
			$order->setDate(new \DateTime());
			$em->persist($order);


			
			
			$rt = $request->request->get('roundTrip');
			if($rt == 2) 
			{ 
				$arr_cruises = [1,2];
			};
			if($rt == 1) 
			{
				$arr_cruises = [$request->request->get('roundTrip1')];
			};
			
			$dump[] = $arr_cruises;
				

			foreach($arr_cruises as $i)
			{
				$cruise = 'cruise'.$i;
				
				$summPlace = 0;
				
				foreach($request->request->get('category') as $categoryId => $value)
				{

					if($value > 0)
					{
						
						$summPlace += $value;

						$category = $em->getRepository('CruiseBundle:Category')->findOneById($categoryId);
						$roundTrip = $em->getRepository('CruiseBundle:RoundTrip')->findOneById($rt);
						
						$price = $em->getRepository('CruiseBundle:Price')->findOneBy([
							'cruise'=>$$cruise->getId(),
							'category'=>$categoryId,
							'roundTrip'=>$rt
						])->getPrice();
						
						$orderCruise = new OrderCruise();
						
						
						
						//$dump = $$cruise;
						
						$orderCruise
							->setOrdering($order)
							->setCruise($$cruise)
							->setCategory($category)
							->setRoundTrip($roundTrip)
							->setPrice($price)
							->setCount($value)
						;
						
						$em->persist($orderCruise);
					}
				}
				// сделать проверку на доступность мест
				if($summPlace > $this->getDoctrine()->getRepository('CruiseBundle:OrderCruise')->getFreeCountPlace($$cruise->getId()))
				{
					$error[] = "Количество запрошеных мест (". $summPlace .") превышает количество свободных (" . $this->getDoctrine()->getRepository('CruiseBundle:OrderCruise')->getFreeCountPlace($$cruise->getId()).") в круизе на ".$$cruise->getTime()->format("H:i");
				}
				
			}
			

			if(count($error) == 0) 
			{
				$em->flush();
				$session = $request->getSession();
				$session->set('order',$order->getId() );
				return $this->redirectToRoute('form_filling');
			}
		
		}
		
		
		
		$firstCruise = $this->getDoctrine()->getRepository('CruiseBundle:Cruise')->getNearestDate();
		$date = (null == $firstCruise) ?
		new \DateTime() :  
		$firstCruise->getDate() ;	
		
		return ['error'=>$error, 'nearestDate' => $date, 'request'=>$request,'dump'=>$dump];
	}
	
	private function eachCruises($cruises)
	{
		foreach($cruises as $cruise)
		{
			$priceArr = [];
			foreach($cruise->getPrices() as $price)
			{
				//$priceArr[$price->getRoundTrip()->getId()][$price->getCategory()->getName()]= $price->getPrice();
				
				$priceArr[$price->getRoundTrip()->getId()][$price->getCategory()->getId()]=  ['name'=>$price->getCategory()->getName(), 'price'=> $price->getPrice() ]  ;
			}
			$cruise->priceArr = $priceArr;
			
			$cruise->freeCountPlace = 
			$this->getDoctrine()->getRepository('CruiseBundle:OrderCruise')->getFreeCountPlace($cruise->getId());
		}
	}
	

	
    /**
     * @Route("/ajax/cruises/{date}", name="ajax_cruises")
	 * @Template()		 
     */
    public function cruiseAction($date = null)
	{
		$firstCruise  = $this->getDoctrine()->getRepository('CruiseBundle:Cruise')->getNearestDate();
		
		if(null != $firstCruise)
		{
			$date = (null == $date) ? $firstCruise->getDate() : new \DateTime($date);  
		}
		else
		{
			$date = new \DateTime();
		}
		
# 		$cruises1 =  $this->getDoctrine()->getRepository('CruiseBundle:Cruise')->findBy(['date'=>$date, 'direction'=>1],['time'=>'ASC']);
# 		$this->eachCruises($cruises1);
# 		
# 		$cruises2 =  $this->getDoctrine()->getRepository('CruiseBundle:Cruise')->findBy(['date'=>$date, 'direction'=>2],['time'=>'ASC']);
# 		$this->eachCruises($cruises2);
		
		$cruises1 =  $this->getDoctrine()->getRepository('CruiseBundle:Cruise')->findByDateTime($date, 1);
		$this->eachCruises($cruises1);
		
		$cruises2 =  $this->getDoctrine()->getRepository('CruiseBundle:Cruise')->findByDateTime($date, 2);
		$this->eachCruises($cruises2);				
	
		
		
		return ['cruises'=>['cruises1'=>$cruises1,'cruises2'=>$cruises2]];
	}
	
    /**
     * @Route("/form_filling", name="form_filling")
	 * @Template()		 
     */	
	public function formFillingAction(Request $request)
	{
		$session = $request->getSession();
		$order_id = $session->get('order');
		
		if(null == $order_id)
		{
			return $this->redirectToRoute('homepage');
		}
		
		
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

		
		return [$request,'order'=>$order];
	}

	
	
    /**
     * @Route("/date-arr", name="date_arr")
	 * @Template()		 
     */
    public function dateArrayAction()  
	{
		$cruiseDays = $this->getDoctrine()->getRepository('CruiseBundle:Cruise')->getDays();
		
		$arr = [];
		
		//dump($cruiseDays);
		
		foreach($cruiseDays as $cruiseDay)
		{
			$arr[] = $cruiseDay->getDate()->format('"Y-m-d"');
		}
		
		$string = 'var arrayGlobalActiveDate = ['.implode(',',$arr).']';
		
		return ['string'=>$string];
	}	
	
	
}
