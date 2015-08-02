<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Year
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Year
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
     * @ORM\Column(name="year", type="string", length=9)
     */
    private $year;

    /**
     * @ORM\ManyToMany(targetEntity="Activity", inversedBy="years")
     * @ORM\JoinTable(name="years_activities") 
     */
    private $activities;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isCurrentYear", type="boolean")
     */
    private $isCurrentYear;

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
     * Set year
     *
     * @param string $year
     * @return Year
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return string 
     */
    public function getYear()
    {
        return $this->year;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->activities = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set isCurrentYear
     *
     * @param boolean $isCurrentYear
     * @return Year
     */
    public function setIsCurrentYear($isCurrentYear)
    {
        $this->isCurrentYear = $isCurrentYear;

        return $this;
    }

    /**
     * Get isCurrentYear
     *
     * @return boolean 
     */
    public function getIsCurrentYear()
    {
        return $this->isCurrentYear;
    }

    /**
     * Add activities
     *
     * @param \AppBundle\Entity\Activity $activities
     * @return Year
     */
    public function addActivity(\AppBundle\Entity\Activity $activities)
    {
        $this->activities[] = $activities;

        return $this;
    }

    /**
     * Remove activities
     *
     * @param \AppBundle\Entity\Activity $activities
     */
    public function removeActivity(\AppBundle\Entity\Activity $activities)
    {
        $this->activities->removeElement($activities);
    }

    /**
     * Get activities
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getActivities()
    {
        return $this->activities;
    }
}
