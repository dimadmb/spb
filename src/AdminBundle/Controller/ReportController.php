<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/sale/report")
 */
 

class ReportController extends Controller
{

	


	/**
     * @Route("/ticket/{id}", name="admin_get_ticket")
	 * @Template("dump.html.twig")		 
     */
    public function testAction(Request $request,$id)
	{
		$order = $this->getDoctrine()->getRepository('CruiseBundle:Ordering')->findOneById($id);
		$email = $order->getEmail();
	
		
		$body = $this->render('CruiseBundle:Pay:ticket.html.twig',['order'=>$this->extOrder($id)])->getContent();
		
		//$dump = get_class_methods($this->get('knp_snappy.pdf'));

		return new Response(
			$this->get('knp_snappy.pdf')->getOutputFromHtml($body),
			200,
			array(
				'Content-Type'          => 'application/pdf',
				'Content-Disposition'   => 'attachment; filename="Билет'.$id.'.pdf"'
			)
		);
							
	}

	/**
     * @Route("/orders", name="admin_sale_report_orders")
	 * @Template()		 
     */
    public function ordersAction(Request $request)
	{
		
		$id = '';
		$name = '';
		$date = '';
		$route = 0;
		
		
		$form = $this->createForm('AdminBundle\Form\OrdersType');
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) 
		{
			$id = null != $form->getData()['id'] ? $form->getData()['id'] : $id;
			$name = null != $form->getData()['name'] ? $form->getData()['name'] : $name;
			$date = null != $form->getData()['date'] ? $form->getData()['date']->format("Y-m-d") : $date;
			$route = null != $form->getData()['route'] ? $form->getData()['route'] : $route;
		}

		
		$em = $this->getDoctrine()->getManager();
		
		//$orders = $em->getRepository('CruiseBundle:Ordering')->findBy(['status'=>1],['date'=>'DESC']);	
		$orders = $em->getRepository('CruiseBundle:Ordering')->findByOrders($id,$name,$route,$date);

		$dump = get_class_methods($orders);


		foreach($orders  as $key => $order)
		{
			$cruises = [];
			foreach($order->getOrderCruise() as $orderCruise)
			{
				$cruises[$orderCruise->getCruise()->getId()]  = $orderCruise->getCruise();
			}
			$order->cruises = $cruises;
			if(($route == 1) && (count($cruises) != 2) )
			{
				unset($orders[$key]);
 			}
			if($route == 2)
			{
				if(count($cruises) == 2) 
				{
					unset($orders[$key]);
				}
				else
				{
					foreach($cruises as $cruise)
					{
						if($cruise->getDirection()->getId() == 2)
						{
							unset($orders[$key]);
						}
					}
				}
			}
			if($route == 3)
			{
				if(count($cruises) == 2) 
				{
					unset($orders[$key]);
				}
				else
				{
					foreach($cruises as $cruise)
					{
						if($cruise->getDirection()->getId() == 1)
						{
							unset($orders[$key]);
						}
					}
				}
			}


		}
	
		return ['orders'=>$orders,'form'=>$form->createView(),'dump'=>$dump];
	}
	
	
	
	/**
     * @Route("/days", name="admin_sale_report_days")
	 * @Template()		 
     */
    public function daysAction(Request $request)
	{
		//$dateStart = (null !== $request->query->get('dateStart') && '' != $request->query->get('dateStart')) ? $request->query->get('dateStart') : '2010-01-01';
		//$dateEnd   = (null !== $request->query->get('dateEnd')  && '' != $request->query->get('dateEnd')) ? $request->query->get('dateEnd') : '2040-12-01';
		
		$form = $this->createForm('AdminBundle\Form\DaysType');
		$form->handleRequest($request);
		
		$dateStart =  '2010-01-01';
		$dateEnd   =  '2040-12-01';
		

		
		if ($form->isSubmitted() && $form->isValid()) 
		{
			$dateStart = null != $form->getData()['dateStart'] ? $form->getData()['dateStart'] : $dateStart;
			$dateEnd   = null != $form->getData()['dateEnd'] ? $form->getData()['dateEnd'] : $dateEnd;
		}
		
		$em = $this->getDoctrine()->getManager();
		
		$orders = $em->getRepository('CruiseBundle:Ordering')->findByDates($dateStart,$dateEnd);
		
		$order_arr = [];
		$arr_dates = [];
		$arr_times = [];
		$arr_directions = [];
		
		foreach($orders as $order)
		{
			//$order_arr[$order->getDate()->format('Y-m-d H:i:s')] = '';
			foreach($order->getOrderCruise() as $orderCruise)
			{
				$order_arr[$orderCruise->getCruise()->getDirection()->getName()][$orderCruise->getCruise()->getTime()->format('H:i')][$orderCruise->getCruise()->getDate()->format('Y-m-d')] = 
				(isset($order_arr[$orderCruise->getCruise()->getDirection()->getName()][$orderCruise->getCruise()->getTime()->format('H:i')][$orderCruise->getCruise()->getDate()->format('Y-m-d')]) ? 
				$order_arr[$orderCruise->getCruise()->getDirection()->getName()][$orderCruise->getCruise()->getTime()->format('H:i')][$orderCruise->getCruise()->getDate()->format('Y-m-d')] + $orderCruise->getCount() :
				$orderCruise->getCount()
				);
				
				$arr_dates[$orderCruise->getCruise()->getDate()->format('Y-m-d')] = $orderCruise->getCruise()->getDate()->format('Y-m-d');
				$arr_times[$orderCruise->getCruise()->getTime()->format('H:i')] = $orderCruise->getCruise()->getTime()->format('H:i');
				$arr_directions[$orderCruise->getCruise()->getDirection()->getName()] = $orderCruise->getCruise()->getDirection()->getName();
			}
		}
		
		//$order_arr = get_class_methods($order->getDate());
		
		asort($arr_dates);
		asort($arr_times);
		rsort($arr_directions);
		
		
		return ['arr_orders'=>$order_arr,'arr_dates'=>$arr_dates,'arr_times'=>$arr_times,'arr_directions'=>$arr_directions,'form'=>$form->createView(),'request'=>$request];
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
