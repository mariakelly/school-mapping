<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ActivityCategory
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class ActivityCategory
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
     * @ORM\ManyToMany(targetEntity="Activity", mappedBy="activityCategories")
     */
    private $activities;

    /**
     * @ORM\ManyToMany(targetEntity="Project", mappedBy="activityCategories")
     */
    private $projects;

    /**
     * @var boolean
     *
     * @ORM\Column(name="hideFromFilters", type="boolean", options={"default"=false})
     */
    private $hideFromFilters;

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
     * __toString Method.
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return ActivityCategory
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
     * @return ActivityCategory
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
     * Constructor
     */
    public function __construct()
    {
        $this->activities = new \Doctrine\Common\Collections\ArrayCollection();
        $this->projects = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add activities
     *
     * @param \AppBundle\Entity\Activity $activities
     * @return ActivityCategory
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
     * @param \AppBundle\Entity\Activity $projects
     * @return ActivityCategory
     */
    public function addProject(\AppBundle\Entity\Activity $projects)
    {
        $this->projects[] = $projects;

        return $this;
    }

    /**
     * Remove projects
     *
     * @param \AppBundle\Entity\Activity $projects
     */
    public function removeProject(\AppBundle\Entity\Activity $projects)
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
