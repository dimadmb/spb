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


use Liuggio\ExcelBundle;

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
     * @Route("/edit/{id}", name="admin_cruise_edit")
     * @Method({"GET", "POST"})
	 * @Template()		 
     */
	public function editAction(Request $request, $id)
	{
        $cruise = $this->getDoctrine()->getRepository("CruiseBundle:Cruise")->findOneById($id);
		
	
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
	 
    /**
     * Creates a new Cruise entity.
     *
     * @Route("/new_excel", name="admin_cruise_new_excel")
     * @Method({"GET", "POST"})
	 * @Template()		 
     */
    public function newExcelAction(Request $request)	
	{
		if(isset($request->request->all()["cruisebundle_cruise"]["date"]) 	&& (null !== $request->files->all()['file']->getRealPath()))
		{
			$em = $this->getDoctrine()->getManager();
			
			$date = $request->request->all()["cruisebundle_cruise"]["date"];
			$date = new \DateTime($date);
			
			$file =  $request->files->all()['file']->getRealPath();  // воткнуть проверку на существование файла
			
			$phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($file);
			
			$sheet = $phpExcelObject->getSheet(0);
			
			
			$directions = $em->getRepository("CruiseBundle:Direction")->findAll();
			foreach($directions as $direction)
			{
				$direction_arr[$direction->getId()] = $direction;
			}
			$priceDefaults = $em->getRepository("CruiseBundle:PriceDefault")->findAll();
			foreach($priceDefaults as $priceDefault)
			{
				$priceDefault_arr[$priceDefault->getDirection()->getId()][$priceDefault->getRoundTrip()->getId()][$priceDefault->getCategory()->getId()] = $priceDefault->getPrice();
			}
			$roundTrips = $em->getRepository("CruiseBundle:RoundTrip")->findAll();
			foreach($roundTrips as $roundTrip)
			{
				$roundTrip_arr[$roundTrip->getId()] = $roundTrip;
			}
			$categoryes = $em->getRepository("CruiseBundle:Category")->findAll();
			foreach($categoryes as $category)
			{
				$category_arr[$category->getId()] = $category;
			}
			
			for ($i = 2; $i <= $sheet->getHighestRow(); $i++) 
			{
				$quantity =   10;
				$time =   $sheet->getCellByColumnAndRow(0, $i)->getValue();
				// находим соответсвия с форматом времени
				if(preg_match("/\b\d{2}[:]\d{2}\b/",$time)) 
				{
					$cruise = new Cruise();
					$cruise->setTime(new \DateTime($time));
					$cruise->setDate($date);
					$cruise->setQuantity($quantity);
					$cruise->setDirection($direction_arr[1]);
					$em->persist($cruise);
					
					foreach($priceDefault_arr[1] as $RT_id => $priceDefaultRoundTrip)
					{
						foreach($priceDefaultRoundTrip as $cat_id => $priceDefaultCategory)
						{
							
							$price = new Price();
							$price 
								->setCruise($cruise)
								->setCategory($category_arr[$cat_id])
								->setRoundTrip($roundTrip_arr[$RT_id])
								->setPrice($priceDefaultCategory)
								;
							$em->persist($price);
							
							
						}
					}
					
				}

				
				$time =   $sheet->getCellByColumnAndRow(1, $i)->getValue();
				// находим соответсвия с форматом времени
				if(preg_match("/\b\d{2}[:]\d{2}\b/",$time)) 
				{
					$cruise = new Cruise();
					$cruise->setTime(new \DateTime($time));
					$cruise->setDate($date);
					$cruise->setQuantity($quantity);
					$cruise->setDirection($direction_arr[2]);
					$em->persist($cruise);
					
					foreach($priceDefault_arr[2] as $RT_id => $priceDefaultRoundTrip)
					{
						foreach($priceDefaultRoundTrip as $cat_id => $priceDefaultCategory)
						{
							
							$price = new Price();
							$price 
								->setCruise($cruise)
								->setCategory($category_arr[$cat_id])
								->setRoundTrip($roundTrip_arr[$RT_id])
								->setPrice($priceDefaultCategory)
								;
							$em->persist($price);
							
							
						}
					}				
					
				}
				

			}			
			$em->flush();
			
			return [
				'dump'=>[
				$sheet,
				$request,
				$date,
				$priceDefault_arr
				]
			];
		
		
		}
		else
		{
			return ['dump' => ''];
		}
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
		
		//$direction = $this->getDoctrine()->getRepository('CruiseBundle:Direction')->findOneById(1);

		$priceDefaults = $this->getDoctrine()->getRepository('CruiseBundle\Entity\PriceDefault')->findByDirection(1);

		foreach($priceDefaults as $priceDefault)
		{
			$price = new Price();
			$price->setCategory($priceDefault->getCategory());
			$price->setPrice($priceDefault->getPrice());
			$price->setRoundTrip($priceDefault->getRoundTrip());
			$cruise->getPrices()->add($price);
			//$this->getDoctrine()->getManager()->persist($price);
		}		
		
/*
		$categoryes = $this->getDoctrine()->getRepository('CruiseBundle\Entity\Category')->findByIsDefault(1);//ByDirection($cruise->getDirection());

		foreach($categoryes as $category)
		{
			$price = new Price();
			$price->setCategory($category);
			$cruise->getPrices()->add($price);
		}		
*/		
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
