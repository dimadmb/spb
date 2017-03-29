<?php

namespace CruiseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Direction
 *
 * @ORM\Table(name="direction")
 * @ORM\Entity(repositoryClass="CruiseBundle\Repository\DirectionRepository")
 */
class Direction
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
	 * @ORM\OneToMany(targetEntity="PriceDefault", mappedBy="direction")
	 */
	private $priceDefault;	


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
     * @return Direction
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
    }

    /**
     * Add priceDefault
     *
     * @param \CruiseBundle\Entity\PriceDefault $priceDefault
     *
     * @return Direction
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
}
