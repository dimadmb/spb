<?php

namespace CruiseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CalendarController extends Controller
{
    /**
     * Lists all Cruise entities.
     *
     * @Route("/calendar", name="calendar")
	 * @Template()		 
     */
    public function indexAction()  // устарела 
	{
		$cruiseDays = $this->getDoctrine()->getRepository('CruiseBundle:Cruise')->getDays();
		
		$months = [];
		
		foreach($cruiseDays as $cruiseDay)
		{
			$months[$cruiseDay->getDate()->format('Y-m')]['days'][] = $cruiseDay->getDate()->format('j');
		}
		
		foreach($months as $key => &$month)
		{
			$month['timestamp'] = strtotime($key);
			$month['firstDayOfWeek'] = date("w",strtotime($key)) == 0 ? 7 : date("w",strtotime($key)) ;
			$month['dayCount'] = date("t",strtotime($key)) ;
			$allCell = $month['firstDayOfWeek'] - 1 + $month['dayCount'] ;
			if($allCell%7 != 0)
			{
				$month['freeCellEnd'] = 7 - $allCell%7;
			}
			else
			{
				$month['freeCellEnd'] = 0;
			}
		}
		return ['months'=>$months,'d'=>$cruiseDays];
	}
	
	
	
    /**
     * Lists all Cruise entities.
     *
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
