<?php

namespace CruiseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RoundTrip
 *
 * @ORM\Table(name="round_trip")
 * @ORM\Entity(repositoryClass="CruiseBundle\Repository\RoundTripRepository")
 */
class RoundTrip
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
	
	/**
	 * @ORM\OneToMany(targetEntity="PriceDefault", mappedBy="roundTrip")
	 */
	private $priceDefault;		
	
	/**
	 * @ORM\OneToMany(targetEntity="Price", mappedBy="roundTrip")
	 */
	private $price;	
	

	public function __toString()
	{
		return $this->name;
	}

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
     * Set name
     *
     * @param string $name
     *
     * @return RoundTrip
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->priceDefault = new \Doctrine\Common\Collections\ArrayCollection();
        $this->price = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add priceDefault
     *
     * @param \CruiseBundle\Entity\PriceDefault $priceDefault
     *
     * @return RoundTrip
     */
    public function addPriceDefault(\CruiseBundle\Entity\PriceDefault $priceDefault)
    {
        $this->priceDefault[] = $priceDefault;

        return $this;
    }

    /**
     * Remove priceDefault
     *
     * @param \CruiseBundle\Entity\PriceDefault $priceDefault
     */
    public function removePriceDefault(\CruiseBundle\Entity\PriceDefault $priceDefault)
    {
        $this->priceDefault->removeElement($priceDefault);
    }

    /**
     * Get priceDefault
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPriceDefault()
    {
        return $this->priceDefault;
    }

    /**
     * Add price
     *
     * @param \CruiseBundle\Entity\Price $price
     *
     * @return RoundTrip
     */
    public function addPrice(\CruiseBundle\Entity\Price $price)
    {
        $this->price[] = $price;

        return $this;
    }

    /**
     * Remove price
     *
     * @param \CruiseBundle\Entity\Price $price
     */
    public function removePrice(\CruiseBundle\Entity\Price $price)
    {
        $this->price->removeElement($price);
    }

    /**
     * Get price
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPrice()
    {
        return $this->price;
    }
}
