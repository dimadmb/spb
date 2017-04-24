<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Cruise controller.
 *
 * @Route("/sale/cruise")
 */

class SaleCruiseController extends Controller
{
    /**
     * @Route("/", name="admin_sale_cruise_index")
	 * @Template()		 
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $cruises = $em->getRepository('CruiseBundle:Cruise')->findBy([],['id' => 'DESC']);
		
		foreach($cruises as $cruise)
		{
			$cruise->saleCount = $em->getRepository('CruiseBundle:OrderCruise')->getSaleCountPlace($cruise->getId());
		}
		
		return ['cruises'=>$cruises];
    }


    /**
     * @Route("/{id}", name="admin_sale_cruise_item")
	 * @Template()		 
     */
	public function cruiseAction($id)
	{
		
		$cruise = $this->getDoctrine()->getRepository('CruiseBundle:Cruise')->findOneOrderById($id);
		
		return ['cruise'=>$cruise];
	}
}
