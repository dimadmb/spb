<?php

namespace CruiseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Cruise
 *
 * @ORM\Table(name="cruise", uniqueConstraints={@ORM\UniqueConstraint(name="direction_date_time", columns={"direction_id", "date", "time"})})
 * @ORM\Entity(repositoryClass="CruiseBundle\Repository\CruiseRepository")
 */
class Cruise
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
     * @var \Date
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var \Time
     *
     * @ORM\Column(name="time", type="time")
     */
    private $time;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;
	
	
    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;
	
	
	/**
	 * @ORM\ManyToOne(targetEntity="Direction", fetch="EAGER")
	 */
	private $direction;


    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean",options={"default" : true})
     */
    private $active  = true;	
	
	/**
	 * @ORM\OneToMany(targetEntity="Price", mappedBy="cruise", fetch="EAGER", cascade={"persist", "remove"}, orphanRemoval=true)
	 * @ORM\OrderBy({"price" = "DESC"})
	 */
	private $prices;
	

	/**
	 * @ORM\OneToMany(targetEntity="OrderCruise", mappedBy="cruise")
	 */
	private $orderCruise;

	
	
	
	public function __toString()
	{
		return date('Y-m-d H:i',$this->datetime->getTimestamp()).' => '.$this->direction->getName();
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
     * Set comment
     *
     * @param string $comment
     *
     * @return Cruise
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Cruise
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set direction
     *
     * @param \CruiseBundle\Entity\Direction $direction
     *
     * @return Cruise
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Cruise
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }



    /**
     * Set time
     *
     * @param \DateTime $time
     *
     * @return Cruise
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->prices = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add price
     *
     * @param \CruiseBundle\Entity\Price $price
     *
     * @return Cruise
     */
    public function addPrice(\CruiseBundle\Entity\Price $price)
    {
        $this->prices[] = $price;

        return $this;
    }

    /**
     * Remove price
     *
     * @param \CruiseBundle\Entity\Price $price
     */
    public function removePrice(\CruiseBundle\Entity\Price $price)
    {
        $this->prices->removeElement($price);
    }

    /**
     * Get prices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPrices()
    {
        return $this->prices;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Cruise
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Add orderCruise
     *
     * @param \CruiseBundle\Entity\OrderCruise $orderCruise
     *
     * @return Cruise
     */
    public function addOrderCruise(\CruiseBundle\Entity\OrderCruise $orderCruise)
    {
        $this->orderCruise[] = $orderCruise;

        return $this;
    }

    /**
     * Remove orderCruise
     *
     * @param \CruiseBundle\Entity\OrderCruise $orderCruise
     */
    public function removeOrderCruise(\CruiseBundle\Entity\OrderCruise $orderCruise)
    {
        $this->orderCruise->removeElement($orderCruise);
    }

    /**
     * Get orderCruise
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrderCruise()
    {
        return $this->orderCruise;
    }
}
