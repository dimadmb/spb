<?php

namespace CruiseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ordering
 *
 * @ORM\Table(name="ordering")
 * @ORM\Entity(repositoryClass="CruiseBundle\Repository\OrderingRepository")
 */
class Ordering
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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="pasport", type="string", length=12, nullable=true)
     */
    private $pasport;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status;

    /**
     * @var bool
     *
     * @ORM\Column(name="del", type="boolean", nullable=false)
     */
    private $del = 0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="trx", type="text", nullable=true)
     */
    private $trx;

    /**
     * @var string
     *
     * @ORM\Column(name="trxAmount", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $trxAmount;
	
	/**
	 * @ORM\OneToMany(targetEntity="OrderCruise", mappedBy="ordering", fetch="EAGER")
	 */
	private $orderCruise;


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
     * @return Ordering
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
     * Set email
     *
     * @param string $email
     *
     * @return Ordering
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Ordering
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set pasport
     *
     * @param string $pasport
     *
     * @return Ordering
     */
    public function setPasport($pasport)
    {
        $this->pasport = $pasport;

        return $this;
    }

    /**
     * Get pasport
     *
     * @return string
     */
    public function getPasport()
    {
        return $this->pasport;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Ordering
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set del
     *
     * @param boolean $del
     *
     * @return Ordering
     */
    public function setDel($del)
    {
        $this->del = $del;

        return $this;
    }

    /**
     * Get del
     *
     * @return bool
     */
    public function getDel()
    {
        return $this->del;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Ordering
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
     * Set trx
     *
     * @param string $trx
     *
     * @return Ordering
     */
    public function setTrx($trx)
    {
        $this->trx = $trx;

        return $this;
    }

    /**
     * Get trx
     *
     * @return string
     */
    public function getTrx()
    {
        return $this->trx;
    }

    /**
     * Set trxAmount
     *
     * @param string $trxAmount
     *
     * @return Ordering
     */
    public function setTrxAmount($trxAmount)
    {
        $this->trxAmount = $trxAmount;

        return $this;
    }

    /**
     * Get trxAmount
     *
     * @return string
     */
    public function getTrxAmount()
    {
        return $this->trxAmount;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->orderCruise = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add orderCruise
     *
     * @param \CruiseBundle\Entity\OrderCruise $orderCruise
     *
     * @return Ordering
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
