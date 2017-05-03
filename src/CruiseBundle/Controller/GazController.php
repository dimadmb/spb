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
//      * @Route("/check", name="gaz_check", defaults={"_format"="xml"})	 


	/**
     * @Route("/check", name="gaz_check")	 

     */
    public function checkAction(Request $request)
	{
		$order_id = $request->query->get('o_order_id');
	
		$amount = 100*$this->getDoctrine()->getRepository('CruiseBundle:OrderCruise')->getTotalCost($order_id);

		$response = new Response();
		$content = $this->renderView('CruiseBundle:Gaz:check.xml.twig',['order_id'=>$order_id,'amount'=>$amount]);
		$response->setContent($content);
		  
		$response->headers->set('Content-Type', 'text/xml');
		 
		return $response;		
	}
	
	
	
	/**
     * @Route("/pay", name="gaz_pay")
     */
    public function payAction(Request $request)
	{

		
		$em = $this->getDoctrine()->getManager();
		
		$result_code = $request->query->get('result_code');
		$order_id = $request->query->get('o_order_id');
		$amount = $request->query->get('amount');
		
		$amountReal = 100*$this->getDoctrine()->getRepository('CruiseBundle:OrderCruise')->getTotalCost($order_id);
		
		$order = $em->getRepository('CruiseBundle:Ordering')->findOneById($order_id);
		
		if(($amountReal == $amount) && ($result_code == 1))
		{
			$a = $this->forward('CruiseBundle:Pay:mail',['id'=>$order_id]);
			$order->setStatus(1);
			$order->setTrxAmount($amount/100);
			$order->setTrx(print_r($request->query->all(),1));
			
			
			// Находим сертификат эквайера
			
			
			$fp = "-----BEGIN CERTIFICATE-----
MIIF+jCCBOKgAwIBAgIQK21JV7ngx17fODERFj2JbzANBgkqhkiG9w0BAQsFADB+MQswCQYDVQQG
EwJVUzEdMBsGA1UEChMUU3ltYW50ZWMgQ29ycG9yYXRpb24xHzAdBgNVBAsTFlN5bWFudGVjIFRy
dXN0IE5ldHdvcmsxLzAtBgNVBAMTJlN5bWFudGVjIENsYXNzIDMgU2VjdXJlIFNlcnZlciBDQSAt
IEc0MB4XDTE2MDYwMzAwMDAwMFoXDTE3MDcwMzIzNTk1OVowfDELMAkGA1UEBhMCUlUxDzANBgNV
BAgMBk1vc2NvdzEPMA0GA1UEBwwGTW9zY293MSowKAYDVQQKDCFHYXpwcm9tYmFuayAoSm9pbnQt
c3RvY2sgY29tcGFueSkxHzAdBgNVBAMMFnd3dy5wcHMuZ2F6cHJvbWJhbmsucnUwggEiMA0GCSqG
SIb3DQEBAQUAA4IBDwAwggEKAoIBAQCupf8UMZyWS6gifohpoeNUSv1s28k1399RYDp7f5OO1Adw
r/5mudv0Cx4rZAMTb3TNbbfzLox5szTlCigGnQ5zP34YFOOiVHj42VI5iAcfVp3cAq6weizaE2/J
bIU+DU76ppFw1tHGQ/z77omUOKIPdQpu4dNVTaKdmwzJGaCF95VtI/7z/0UEHxk4R57U2PgcUPL3
dVPOmRxk3W683AOG7GWqI6ctp7nrAd5Ooe9CyQPAS6f81CqtFVyaIOsxDeE4tNI00kwmFne7BVRj
3lJAt7ryGxyaPsKzDQQaZu8qWk12z4HtW0iq0nlsKiAGEOIUfHQO+H+8fMJvXWWT+UD3AgMBAAGj
ggJ0MIICcDAhBgNVHREEGjAYghZ3d3cucHBzLmdhenByb21iYW5rLnJ1MAkGA1UdEwQCMAAwDgYD
VR0PAQH/BAQDAgWgMGEGA1UdIARaMFgwVgYGZ4EMAQICMEwwIwYIKwYBBQUHAgEWF2h0dHBzOi8v
ZC5zeW1jYi5jb20vY3BzMCUGCCsGAQUFBwICMBkMF2h0dHBzOi8vZC5zeW1jYi5jb20vcnBhMCsG
A1UdHwQkMCIwIKAeoByGGmh0dHA6Ly9zcy5zeW1jYi5jb20vc3MuY3JsMB0GA1UdJQQWMBQGCCsG
AQUFBwMBBggrBgEFBQcDAjAfBgNVHSMEGDAWgBRfYM9hkFXfhEMUimAqsvV69EMY7zBXBggrBgEF
BQcBAQRLMEkwHwYIKwYBBQUHMAGGE2h0dHA6Ly9zcy5zeW1jZC5jb20wJgYIKwYBBQUHMAKGGmh0
dHA6Ly9zcy5zeW1jYi5jb20vc3MuY3J0MIIBBQYKKwYBBAHWeQIEAgSB9gSB8wDxAHcA3esdK3oN
T6Ygi4GtgWhwfi6OnQHVXIiNPRHEzbbsvswAAAFVFhlYkgAABAMASDBGAiEA9RATTNtgcrbH49BS
iuRmmt+b77m33yvaFJBqAB6o+XECIQC2rPB5JwVsfJioHr5zehaccDvdeP/Yhz6ZecbdYSGflAB2
AKS5CZC0GFgUh7sTosxncAo8NZgE+RvfuON3zQ7IDdwQAAABVRYZWKwAAAQDAEcwRQIgD9M0O80t
XywbP8ErfzwQoSabMFXloEsGWjAzQ819To0CIQDfjZ1EaCtlUCqFmU3qCSWKSwxNPYcIxFBx+xU2
zqdG3jANBgkqhkiG9w0BAQsFAAOCAQEAUq+1NW297nbp2CNIerl6d4UEJ90OBD7QJ2Go3inePR5d
oFvPH8px8mCkTEo/ephJJYc26SCVfv0NFRMDsmhVg3VaID4WlCAO3/jl/qz4LeQN/Ugmg1fHlzxa
zzCEaTIbh3ysTvUY8wXR2R9dBDRvMseJoV7JNXOHXKp+6D+prFoykWhY0UOdw1o+q4fGdK0X4duI
0rDuThiJe0YJWWCcBo8yoM0ejsDA9fbyUH65LLVedMj4zesTX9YJXv2GZ9p2y6ZumPbaJ4TgLSFU
YvMULcRGXXqCkrf4pXFBu1wsFB2NneCYyTETK3f8Clc0sOZmE/8gLY5Q7fXsF9AsmYaLPw==
-----END CERTIFICATE-----";
			
			#$public_key = openssl_pkey_get_public ($cert);
			#$signature_check = openssl_verify($data, $signature, $public_key, OPENSSL_ALGO_SHA1);
			#openssl_free_key($public_key);
			#
			#
			#$r = print_r($_REQUEST,1);
			#file_put_contents('pay_r.txt',$r);			
		}
		else
		{
			$order->setDel(1);
			
		}
		$em->persist($order);
		$em->flush();
		
		$response = new Response();
		$response->setContent($this->renderView('CruiseBundle:Gaz:pay.xml.twig'));
		$response->headers->set('Content-Type', 'text/xml');

		return $response;	
		
	}
	
}
