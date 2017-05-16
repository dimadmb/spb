<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\HttpFoundation\Request;

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
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

		$form = $this->createFormBuilder()
				->add('date', DateType::class, ['label'=>'Дата','widget' => 'single_text','required'=> false])
				->add('buyer',TextType::class,['required'=> false])
				->add('submit', SubmitType::class,array('label' => 'Фильтровать'))
				->getForm()
			;	
		$form->handleRequest($request);		
		
		$search = [];
		if ($form->isSubmitted() && $form->isValid()) 
		{
			$search = $form->getData();
		}		
		
        $orders = $em->getRepository('CruiseBundle:Ordering')->findBySaleOrder($search);
		
		foreach($orders as $order)
		{
			$cruises = [];
			foreach($order->getOrderCruise() as $orderCruise)
			{
				$cruises[$orderCruise->getCruise()->getId()] = $orderCruise->getCruise();
			}
			$order->cruises = $cruises;
		}

		
		return ['orders'=>$orders,'form'=>$form->createView()];
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
