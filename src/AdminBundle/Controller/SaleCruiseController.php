<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use CruiseBundle\Entity\Cruise;

use Symfony\Component\HttpFoundation\Request;

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
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		
		$cruiseForForm = new Cruise();
		$form = $this->createFormBuilder($cruiseForForm)
				->add('date', DateType::class, ['label'=>'Дата','widget' => 'single_text','required'=> false])
				->add('direction',null,['required'=> false])
				->add('submit', SubmitType::class,array('label' => 'Фильтровать'))
				->getForm()
			;	
		$form->handleRequest($request);
		
		$search = [];
		if ($form->isSubmitted() && $form->isValid()) 
		{
			$cruiseForForm = $form->getData();
			if(null != $cruiseForForm->getDate())
			{
			$search['date'] = 	$cruiseForForm->getDate();
			}
			if(null != $cruiseForForm->getDirection())
			{
			$search['direction'] = 	$cruiseForForm->getDirection();
			}
		}

        $cruises = $em->getRepository('CruiseBundle:Cruise')->findBy($search,['id' => 'DESC']);
		
		foreach($cruises as $cruise)
		{
			$cruise->saleCount = $em->getRepository('CruiseBundle:OrderCruise')->getSaleCountPlace($cruise->getId());
		}
		
		return ['cruises'=>$cruises,'form'=>$form->createView()];
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
