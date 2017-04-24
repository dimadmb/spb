<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Cruise controller.
 *
 * @Route("/sale/order")
 */
 
 
class SaleOrderController extends Controller
{
    /**
     * @Route("/", name="admin_sale_order_index")
	 * @Template()		 
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $orders = $em->getRepository('CruiseBundle:Ordering')->findBy([],['id' => 'DESC']);
		
		foreach($orders as $order)
		{
			$cruises = [];
			foreach($order->getOrderCruise() as $orderCruise)
			{
				$cruises[$orderCruise->getCruise()->getId()] = $orderCruise->getCruise();
			}
			$order->cruises = $cruises;
		}

		
		return ['orders'=>$orders];
    }
	
    /**
     * @Route("/{id}", name="admin_sale_order_item")
	 * @Template()		 
     */
    public function orderAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $order = $em->getRepository('CruiseBundle:Ordering')->findOneById($id);

		
		return ['order'=>$order];
    }	
}
