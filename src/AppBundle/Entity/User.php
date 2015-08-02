<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="app_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * To String
     */
    public function __toString()
    {
        return $this->firstName . " " . $this->lastName;
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
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

    /**
     * Set skypeAccount
     *
     * @param string $skypeAccount
     * @return User
     */
    public function setSkypeAccount($skypeAccount)
    {
        $this->skypeAccount = $skypeAccount;

        return $this;
    }

    /**
     * Get skypeAccount
     *
     * @return string 
     */
    public function getSkypeAccount()
    {
        return $this->skypeAccount;
    }

    /**
     * Set cellNumber
     *
     * @param string $cellNumber
     * @return User
     */
    public function setCellNumber($cellNumber)
    {
        $this->cellNumber = $cellNumber;

        return $this;
    }

    /**
     * Get cellNumber
     *
     * @return string 
     */
    public function getCellNumber()
    {
        return $this->cellNumber;
    }

    /**
     * Set cohort
     *
     * @param \AppBundle\Entity\Cohort $cohort
     * @return User
     */
    public function setCohort(\AppBundle\Entity\Cohort $cohort = null)
    {
        $this->cohort = $cohort;

        return $this;
    }

    /**
     * Get cohort
     *
     * @return \AppBundle\Entity\Cohort 
     */
    public function getCohort()
    {
        return $this->cohort;
    }
}
