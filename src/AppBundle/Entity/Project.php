<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Project
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectRepository")
 */
class Project
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
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="website", type="string", length=255, nullable=true)
     */
    private $website;

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
     * @ORM\ManyToMany(targetEntity="Year", inversedBy="projects")
     */
    private $years;

    /**
     * @ORM\ManyToMany(targetEntity="Topic", inversedBy="projects")
     */
    private $topics;

    /**
     * @ORM\OneToMany(targetEntity="Activity", mappedBy="project")
     */
    private $activities;

    /**
     * @ORM\ManyToMany(targetEntity="DivisionOrGroup", inversedBy="projects")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $groups;

    /**
     * @ORM\ManyToMany(targetEntity="Individual", inversedBy="projects")
     */
    private $people;

    /**
     * @ORM\ManyToMany(targetEntity="ActivityCategory", inversedBy="projects")
     */
    private $activityCategories;

    /**
     * toString
     */
    public function __toString()
    {
        return $this->name;
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
     * Set name
     *
     * @param string $name
     * @return Project
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
     * Set description
     *
     * @param string $description
     * @return Project
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set isFeatured
     *
     * @param boolean $isFeatured
     * @return Project
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
     * @return Project
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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->years = new \Doctrine\Common\Collections\ArrayCollection();
        $this->topics = new \Doctrine\Common\Collections\ArrayCollection();
        $this->activities = new \Doctrine\Common\Collections\ArrayCollection();
        $this->groups = new \Doctrine\Common\Collections\ArrayCollection();
        $this->people = new \Doctrine\Common\Collections\ArrayCollection();
        $this->activityCategories = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add years
     *
     * @param \AppBundle\Entity\Year $years
     * @return Project
     */
    public function addYear(\AppBundle\Entity\Year $years)
    {
        $this->years[] = $years;

        return $this;
    }

    /**
     * Remove years
     *
     * @param \AppBundle\Entity\Year $years
     */
    public function removeYear(\AppBundle\Entity\Year $years)
    {
        $this->years->removeElement($years);
    }

    /**
     * Get years
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getYears()
    {
        return $this->years;
    }

    /**
     * Add topics
     *
     * @param \AppBundle\Entity\Topic $topics
     * @return Project
     */
    public function addTopic(\AppBundle\Entity\Topic $topics)
    {
        $this->topics[] = $topics;

        return $this;
    }

    /**
     * Remove topics
     *
     * @param \AppBundle\Entity\Topic $topics
     */
    public function removeTopic(\AppBundle\Entity\Topic $topics)
    {
        $this->topics->removeElement($topics);
    }

    /**
     * Get topics
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTopics()
    {
        return $this->topics;
    }

    /**
     * Add activities
     *
     * @param \AppBundle\Entity\Activity $activities
     * @return Project
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
     * Add groups
     *
     * @param \AppBundle\Entity\DivisionOrGroup $groups
     * @return Project
     */
    public function addGroup(\AppBundle\Entity\DivisionOrGroup $groups)
    {
        $this->groups[] = $groups;

        return $this;
    }

    /**
     * Remove groups
     *
     * @param \AppBundle\Entity\DivisionOrGroup $groups
     */
    public function removeGroup(\AppBundle\Entity\DivisionOrGroup $groups)
    {
        $this->groups->removeElement($groups);
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Add people
     *
     * @param \AppBundle\Entity\Individual $people
     * @return Project
     */
    public function addPerson(\AppBundle\Entity\Individual $people)
    {
        $this->people[] = $people;

        return $this;
    }

    /**
     * Remove people
     *
     * @param \AppBundle\Entity\Individual $people
     */
    public function removePerson(\AppBundle\Entity\Individual $people)
    {
        $this->people->removeElement($people);
    }

    /**
     * Get people
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPeople()
    {
        return $this->people;
    }

    /**
     * Set website
     *
     * @param string $website
     * @return Project
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Add activityCategories
     *
     * @param \AppBundle\Entity\ActivityCategory $activityCategories
     * @return Project
     */
    public function addActivityCategory(\AppBundle\Entity\ActivityCategory $activityCategories)
    {
        $this->activityCategories[] = $activityCategories;

        return $this;
    }

    /**
     * Remove activityCategories
     *
     * @param \AppBundle\Entity\ActivityCategory $activityCategories
     */
    public function removeActivityCategory(\AppBundle\Entity\ActivityCategory $activityCategories)
    {
        $this->activityCategories->removeElement($activityCategories);
    }

    /**
     * Get activityCategories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActivityCategories()
    {
        return $this->activityCategories;
    }
}
