<?php

namespace CruiseBundle\Entity;

use FOS\UserBundle\Model\User as CruiseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends CruiseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
	
    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     */
    private $firstName;		
	
    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     */
    private $lastName;	

    public function __construct()
    {
        parent::__construct();
        // your own logic
		
		$this->salt = "";
    }

#    /**
#     * {@inheritdoc}
#     */
#    public function getSalt()
#    {
#        return null;
#    }	
	

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }
}
