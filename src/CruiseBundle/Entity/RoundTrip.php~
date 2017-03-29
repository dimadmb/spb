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
}

