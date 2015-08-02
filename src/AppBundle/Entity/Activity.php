<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Activity
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Activity
{
    /**
     * @var integer
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
     * @ORM\Column(name="details", type="text")
     */
    private $details;

    /**
     * @var string
     *
     * @ORM\Column(name="shortDescription", type="text")
     */
    private $shortDescription;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isFeatured", type="boolean")
     */
    private $isFeatured;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isDistrictWide", type="boolean")
     */
    private $isDistrictWide;

    /**
     * @ORM\ManyToMany(targetEntity="Year", mappedBy="activities")
     */
    private $years;

    /**
     * @ORM\ManyToOne(targetEntity="School", inversedBy="activities")
     * @ORM\JoinColumn(name="school_id", referencedColumnName="id")
     */
    private $school;

    /**
     * @ORM\ManyToOne(targetEntity="ActivityCategory", inversedBy="activities")
     */
    private $activityCategory;

    /**
     * @ORM\ManyToMany(targetEntity="Topic", mappedBy="activities")
     */
    private $topics;

    /**
     * @ORM\ManyToMany(targetEntity="DivisionOrGroup", mappedBy="activities")
     */
    private $groups;

    /**
     * @ORM\ManyToMany(targetEntity="Individual", mappedBy="activities")
     */
    private $people;

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
     * Set name
     *
     * @param string $name
     * @return Activity
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
     * Set details
     *
     * @param string $details
     * @return Activity
     */
    public function setDetails($details)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get details
     *
     * @return string 
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set shortDescription
     *
     * @param string $shortDescription
     * @return Activity
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * Get shortDescription
     *
     * @return string 
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Set isFeatured
     *
     * @param boolean $isFeatured
     * @return Activity
     */
    public function setIsFeatured($isFeatured)
    {
        $this->isFeatured = $isFeatured;

        return $this;
    }

    /**
     * Get isFeatured
     *
     * @return boolean 
     */
    public function getIsFeatured()
    {
        return $this->isFeatured;
    }

    /**
     * Set isDistrictWide
     *
     * @param boolean $isDistrictWide
     * @return Activity
     */
    public function setIsDistrictWide($isDistrictWide)
    {
        $this->isDistrictWide = $isDistrictWide;

        return $this;
    }

    /**
     * Get isDistrictWide
     *
     * @return boolean 
     */
    public function getIsDistrictWide()
    {
        return $this->isDistrictWide;
    }
}
