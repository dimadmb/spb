<?php

namespace CruiseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderCruise
 *
 * @ORM\Table(name="order_cruise")
 * @ORM\Entity(repositoryClass="CruiseBundle\Repository\OrderCruiseRepository")
 */
class OrderCruise
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $price;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Ordering", inversedBy="orderCruise")
	 */
	private $ordering;
	
	
	/**
	 * @ORM\ManyToOne(targetEntity="Cruise", inversedBy="orderCruise")
	 */
	private $cruise;
	
	
	/**
	 * @ORM\ManyToOne(targetEntity="Category")
	 */
	private $category;	
	
	/**
	 * @ORM\ManyToOne(targetEntity="RoundTrip")
	 */
	private $roundTrip;
	
	
    /**
     * @var int
     *
     * @ORM\Column(name="count", type="integer")
     */
    private $count;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return OrderCruise
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set count
     *
     * @param integer $count
     *
     * @return OrderCruise
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set ordering
     *
     * @param \CruiseBundle\Entity\Ordering $ordering
     *
     * @return OrderCruise
     */
    public function setOrdering(\CruiseBundle\Entity\Ordering $ordering = null)
    {
        $this->ordering = $ordering;

        return $this;
    }

    /**
     * Get ordering
     *
     * @return \CruiseBundle\Entity\Ordering
     */
    public function getOrdering()
    {
        return $this->ordering;
    }

    /**
     * Set cruise
     *
     * @param \CruiseBundle\Entity\Cruise $cruise
     *
     * @return OrderCruise
     */
    public function setCruise(\CruiseBundle\Entity\Cruise $cruise = null)
    {
        $this->cruise = $cruise;

        return $this;
    }

    /**
     * Get cruise
     *
     * @return \CruiseBundle\Entity\Cruise
     */
    public function getCruise()
    {
        return $this->cruise;
    }

    /**
     * Set category
     *
     * @param \CruiseBundle\Entity\Category $category
     *
     * @return OrderCruise
     */
    public function setCategory(\CruiseBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \CruiseBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set roundTrip
     *
     * @param \CruiseBundle\Entity\RoundTrip $roundTrip
     *
     * @return OrderCruise
     */
    public function setRoundTrip(\CruiseBundle\Entity\RoundTrip $roundTrip = null)
    {
        $this->roundTrip = $roundTrip;

        return $this;
    }

    /**
     * Get roundTrip
     *
     * @return \CruiseBundle\Entity\RoundTrip
     */
    public function getRoundTrip()
    {
        return $this->roundTrip;
    }
}
