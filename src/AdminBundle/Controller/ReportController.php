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
     * @Route("/ticket/{id}/{cruiseId}", name="admin_get_ticket")
	 * @Template("dump.html.twig")		 
     */
    public function ticketAction(Request $request,$id,$cruiseId)
	{
		$order = $this->getDoctrine()->getRepository('CruiseBundle:Ordering')->findOneById($id);
		
		$order = $this->extOrder($order);
		
		$cruise = $this->getDoctrine()->getRepository('CruiseBundle:Cruise')->findOneById($cruiseId);
	
		
		$body = $this->renderView('CruiseBundle:Pay:ticket.html.twig',['order'=>$order,'cruiseId'=>$cruiseId]);
		
		//$dump = get_class_methods($this->get('knp_snappy.pdf'));

		return new Response(
			$this->get('knp_snappy.pdf')->getOutputFromHtml($body),
			200,
			array(
				'Content-Type'          => 'application/pdf',
				'Content-Disposition'   => 'attachment; filename="Билет '.$id.' '.$cruise->getDirection()->getName().'.pdf"'
			)
		);
							
	}	


	/**
     * @Route("/category", name="admin_sale_report_category")
	 * @Template()		 
     */
    public function categoryAction(Request $request)
	{
		
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
		
		$arr_orders = [];
		$arr_dates = [];
		$arr_category = [];
		$arr_directions = [];
		
		foreach($orders as $order)
		{
			//определим направление круиза(ов) в заказе
			$directions = [];
			$categoryes = [];
			$date = "";
			foreach($order->getOrderCruise() as $orderCruise)
			{
				$directions[$orderCruise->getCruise()->getDirection()->getId()] = $orderCruise->getCruise()->getDirection()->getId();
				$categoryes[$orderCruise->getCategory()->getName()] = $orderCruise->getCount();
				$date = $orderCruise->getCruise()->getDate()->format('Y-m-d');
				$arr_dates[$date] = $date;
				
				$arr_category[$orderCruise->getCategory()->getId()] = $orderCruise->getCategory()->getName();
				
			}
			if(count($directions) == 2)
			{
				$direction = "СПб - Петергоф - СПб";
			}
			else
			{
				if(array_shift($directions) == 1) $direction = "СПб - Петергоф";
				if(array_shift($directions) == 2) $direction = "Петергоф - СПб";
			}
			foreach($categoryes as $categoryName => $count)
			{
				$arr_orders[$direction][$categoryName][$date] = 
				isset($arr_orders[$direction][$categoryName][$date]) ?
				$arr_orders[$direction][$categoryName][$date] + $count :
				$count;
				
			}
			
			$arr_directions[$direction] = $direction;
		}
		
		asort($arr_directions);
		asort($arr_dates);
		rsort($arr_category);
		
		return ['orders'=>$orders,'arr_orders'=>$arr_orders, 'arr_dates'=>$arr_dates, 'arr_category' => $arr_category, 'arr_directions' => $arr_directions, 'form'=>$form->createView() ];		
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
