<?php

namespace CruiseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

class PayController extends Controller
{
    /**
     * @Route("/pay", name="to_pay")
     * @Method({"POST"})
	 * @Template()		 
     */
    public function indexAction(Request $request)
	{
		$session = $request->getSession();
		$order_id = $session->get('order');
		
		
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository('CruiseBundle:Ordering')->findOneById($order_id);	
		
		
		$order
			->setName($request->request->get('name'))
			->setEmail($request->request->get('email'))
			->setPhone($request->request->get('phone'))
		;
		
		$em->persist($order);
		$em->flush();
		
		return [$request];
	}
}
