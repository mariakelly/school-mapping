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
     * @ORM\ManyToMany(targetEntity="Activity", mappedBy="years")
     */
    private $activities;

    /**
     * @ORM\ManyToMany(targetEntity="Project", mappedBy="years")
     */
    private $projects;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isCurrentYear", type="boolean")
     */
    private $isCurrentYear;

    /**
     * __toString Method.
     */
    public function __toString()
    {
        return $this->year;
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

    /**
     * Add projects
     *
     * @param \AppBundle\Entity\Project $projects
     * @return Year
     */
    public function addProject(\AppBundle\Entity\Project $projects)
    {
        $this->projects[] = $projects;

        return $this;
    }

    /**
     * Remove projects
     *
     * @param \AppBundle\Entity\Project $projects
     */
    public function removeProject(\AppBundle\Entity\Project $projects)
    {
        $this->projects->removeElement($projects);
    }

    /**
     * Get projects
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProjects()
    {
        return $this->projects;
    }
}
