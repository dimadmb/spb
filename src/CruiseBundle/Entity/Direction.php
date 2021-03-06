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
     * @var string
     *
     * @ORM\Column(name="place_start", type="string", length=255)
     */
    private $placeStart;
	


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
     * Set placeStart
     *
     * @param string $placeStart
     *
     * @return Direction
     */
    public function setPlaceStart($placeStart)
    {
        $this->placeStart = $placeStart;

        return $this;
    }

    /**
     * Get placeStart
     *
     * @return string
     */
    public function getPlaceStart()
    {
        return $this->placeStart;
    }
}
