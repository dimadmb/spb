<?php

namespace CruiseBundle\Repository;

/**
 * OrderCruiseRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OrderCruiseRepository extends \Doctrine\ORM\EntityRepository
{

	
	public function getTotalCost($order_id)
	{
		$str = "
		SELECT SUM(oc.price * oc.count ) summ
		FROM CruiseBundle:OrderCruise oc 
		WHERE oc.ordering = $order_id
		";
   		$q = $this->_em->createQuery($str);
   		return $q->getOneOrNullResult()['summ'] * 1;		
		
	}
	
	
	public function getFreeCountPlace($cruise_id) {
		$str = "SELECT (c.quantity - COALESCE(SUM(oc.count) ,0)) free_count
			FROM  CruiseBundle:Cruise c
			
			LEFT JOIN c.orderCruise oc
			
			LEFT JOIN oc.ordering oco
			
			WHERE 
			
			c.id = $cruise_id 
			
			AND 
			
			oco.del <> 1
			
			";
   		$q = $this->_em->createQuery($str);
   		return $q->getOneOrNullResult()['free_count'] * 1;
	}	// вычесть это из quantity 
	
	
	public function getSaleCountPlace($cruise_id) {
		$str = "SELECT  COALESCE(SUM(oc.count) ,0) sale_count
			FROM CruiseBundle:OrderCruise oc
			LEFT JOIN oc.ordering o
			WHERE oc.cruise = $cruise_id
			AND o.status = 1
			";
   		$q = $this->_em->createQuery($str);
   		return $q->getOneOrNullResult()['sale_count'] * 1;
	}	
	
}
