<?php

namespace CruiseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="CruiseBundle\Repository\CategoryRepository")
 */
class Category
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
     * @var bool
     *
     * @ORM\Column( type="boolean")
     */
	private $isDefault;
	
	
	/**
	 * @ORM\OneToMany(targetEntity="PriceDefault", mappedBy="category")
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
     * @return Category
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
     * Set isDefault
     *
     * @param boolean $isDefault
     *
     * @return Category
     */
    public function setIsDefault($isDefault)
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    /**
     * Get isDefault
     *
     * @return boolean
     */
    public function getIsDefault()
    {
        return $this->isDefault;
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
     * @return Category
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
