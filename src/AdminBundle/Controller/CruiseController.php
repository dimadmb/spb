<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CruiseBundle\Entity\Cruise;
use CruiseBundle\Entity\Price;


use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * Cruise controller.
 *
 * @Route("/cruise")
 */

class CruiseController extends Controller
{
    /**
     * Lists all Cruise entities.
     *
     * @Route("/", name="admin_cruise_index")
     * @Method("GET")
	 * @Template()		 
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $cruises = $em->getRepository('CruiseBundle:Cruise')->findBy([],['id' => 'DESC']);
		
		return ['cruises'=>$cruises];
    }
	
    /**
     * Creates a new Cruise entity.
     *
     * @Route("/new", name="admin_cruise_new")
     * @Method({"GET", "POST"})
	 * @Template()		 
     */
    public function newAction(Request $request)
	{
        $cruise = new Cruise();
		
		$prices = [];
/*
		$priceDefaults = $this->getDoctrine()->getRepository('CruiseBundle\Entity\PriceDefault')->findAll();//ByDirection($cruise->getDirection());

		foreach($priceDefaults as $priceDefault)
		{
			$price = new Price();
			$price->setCategory($priceDefault->getCategory());
			$price->setPrice('5');
			$cruise->getPrices()->add($price);
			//$this->getDoctrine()->getManager()->persist($price);
		}		
*/		

		$categoryes = $this->getDoctrine()->getRepository('CruiseBundle\Entity\Category')->findByIsDefault(1);//ByDirection($cruise->getDirection());

		foreach($categoryes as $category)
		{
			$price = new Price();
			$price->setCategory($category);
			$cruise->getPrices()->add($price);
		}		
		
		$form = $this->createForm('CruiseBundle\Form\CruiseType', $cruise);

		
        $form->handleRequest($request);	

		
		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			
			foreach($cruise->getPrices() as $price)
			{
				
				$price->setCruise($cruise);
				$em->persist($price);
			}
			
			$em->persist($cruise);
			$em->flush();
			
			return $this->redirectToRoute('admin_cruise_index');
			
		}
		
		
		return ['form'=>$form->createView() , $cruise];
	}
	
}
