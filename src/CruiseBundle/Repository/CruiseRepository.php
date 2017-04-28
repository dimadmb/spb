<?php

namespace CruiseBundle\Repository;

/**
 * CruiseRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CruiseRepository extends \Doctrine\ORM\EntityRepository
{
	//  получаем даты с активными круизами
	public function getDays() {
		$str = "SELECT c
			FROM CruiseBundle:Cruise c
			WHERE CONCAT(c.date , ' ' , c.time)  >= CURRENT_TIMESTAMP()			
			AND c.active = 1
			GROUP BY c.date
			ORDER BY c.date
			";
   		$q = $this->_em->createQuery($str);
   		return $q->getResult();
	}	
	
	
	//  получаем дату ближайшего круиза
	public function getNearestDate() {
		$str = "SELECT c
			FROM CruiseBundle:Cruise c
			WHERE CONCAT(c.date , ' ' , c.time)  >= CURRENT_TIMESTAMP()			
			AND c.active = 1
			GROUP BY c.date
			ORDER BY c.date
			";
   		$q = $this->_em->createQuery($str);
		$q->setMaxResults(1);
   		return $q->getOneOrNullResult();
	}	
	
	// получим все продажи по круизу
	public function findOneOrderById($id)
	{
		$str = "SELECT c,oc,oco
			FROM CruiseBundle:Cruise c
			LEFT JOIN c.orderCruise oc
			LEFT JOIN oc.ordering oco
			
			WHERE c.id = $id
			";	
   		$q = $this->_em->createQuery($str);
   		return $q->getOneOrNullResult();	/// осталось присоединить Ordering		
	}
	
	
}
