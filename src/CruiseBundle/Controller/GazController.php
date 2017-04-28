<?php

namespace CruiseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;


/**
 * Gazprom controller.
 *
 * @Route("/gazprom")
 */


class GazController extends Controller
{
	/**
     * @Route("/check", name="gaz_check")	 
     */
    public function checkAction(Request $request)
	{
		
		//$order_id = $request->request->get('o_order_id');
		//$amount = $request->request->get('o_amount');
		return new Response('OoK');
	}
	
	
	
	/**
     * @Route("/pay/{id}", name="gaz_pay")
     */
    public function payAction(Request $request, $id  = null )
	{
		
		//$order_id = $request->request->get('o_order_id');
		//$amount = $request->request->get('o_amount');
		if(null != $id)
		{
			$a = $this->forward('CruiseBundle:Pay:mail',['id'=>$id]);
		}
		
		
		
		return new Response('OK');
		
	}
	
}
