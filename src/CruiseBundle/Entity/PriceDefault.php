<?php

namespace CruiseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PriceDefault
 *
 * @ORM\Table(name="price_default")
 * @ORM\Entity(repositoryClass="CruiseBundle\Repository\PriceDefaultRepository")
 */
class PriceDefault
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
	 * @ORM\ManyToOne(targetEntity="Direction" , inversedBy="priceDefault", fetch="EAGER" )
	 */
	private $direction;
	
	
	/**
	 * @ORM\ManyToOne(targetEntity="Category" , inversedBy="priceDefault", fetch="EAGER" )
	 */
	private $category;	
	
	/**
	 * @ORM\ManyToOne(targetEntity="RoundTrip" , inversedBy="priceDefault", fetch="EAGER" )
	 */
	private $roundTrip;
	
    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=0, nullable=true)
     */
    private $price;


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
     * @return PriceDefault
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
     * Set direction
     *
     * @param \CruiseBundle\Entity\Direction $direction
     *
     * @return PriceDefault
     */
    public function setDirection(\CruiseBundle\Entity\Direction $direction = null)
    {
        $this->direction = $direction;

        return $this;
    }

    /**
     * Get direction
     *
     * @return \CruiseBundle\Entity\Direction
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * Set category
     *
     * @param \CruiseBundle\Entity\Category $category
     *
     * @return PriceDefault
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
     * @return PriceDefault
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
