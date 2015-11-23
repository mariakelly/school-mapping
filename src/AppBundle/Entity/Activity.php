<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Activity
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ActivityRepository")
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
     * @ORM\Column(name="details", type="text", nullable=true)
     */
    private $details;

    /**
     * @var string
     *
     * @ORM\Column(name="shortDescription", type="text", nullable=true)
     */
    private $shortDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="partners", type="text", nullable=true)
     */
    private $partners;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isFeatured", type="boolean", options={"default"=0})
     */
    private $isFeatured;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isDistrictWide", type="boolean", options={"default"=0})
     */
    private $isDistrictWide;

    /**
     * @ORM\ManyToMany(targetEntity="Year", inversedBy="activities")
     */
    private $years;

    /**
     * @ORM\ManyToOne(targetEntity="School", inversedBy="activities")
     * @ORM\JoinColumn(name="school_id", referencedColumnName="id")
     */
    private $school;

    /**
     * @ORM\ManyToMany(targetEntity="ActivityCategory", inversedBy="activities")
     */
    private $activityCategories;

    /**
     * @ORM\ManyToMany(targetEntity="Topic", inversedBy="activities")
     */
    private $topics;

    /**
     * @ORM\ManyToMany(targetEntity="DivisionOrGroup", inversedBy="activities")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $groups;

    /**
     * @ORM\ManyToMany(targetEntity="Individual", inversedBy="activities")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $people;

    /**
     * @var string
     *
     * @ORM\Column(name="website", type="string", length=255, nullable=true)
     */
    private $website;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="activities")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    private $project;

    /**
     * toString
     */
    public function __toString()
    {
        return $this->name." - ".$this->school->getName();
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
     * Set partners
     *
     * @param string $partners
     * @return Activity
     */
    public function setPartners($partners)
    {
        $this->partners = $partners;

        return $this;
    }

    /**
     * Get partners
     *
     * @return string
     */
    public function getPartners()
    {
        return $this->partners;
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

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->years = new \Doctrine\Common\Collections\ArrayCollection();
        $this->topics = new \Doctrine\Common\Collections\ArrayCollection();
        $this->groups = new \Doctrine\Common\Collections\ArrayCollection();
        $this->people = new \Doctrine\Common\Collections\ArrayCollection();
        $this->activityCategories = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add category
     *
     * @param \AppBundle\Entity\ActivityCategory $category
     * @return Activity
     */
    public function addActivityCategory(\AppBundle\Entity\ActivityCategory $category)
    {
        $this->activityCategories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \AppBundle\Entity\ActivityCategory $category
     */
    public function removeActivityCategory(\AppBundle\Entity\ActivityCategory $category)
    {
        $this->activityCategories->removeElement($category);
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

    /**
     * Add years
     *
     * @param \AppBundle\Entity\Year $years
     * @return Activity
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
     * Set school
     *
     * @param \AppBundle\Entity\School $school
     * @return Activity
     */
    public function setSchool(\AppBundle\Entity\School $school = null)
    {
        $this->school = $school;

        return $this;
    }

    /**
     * Get school
     *
     * @return \AppBundle\Entity\School
     */
    public function getSchool()
    {
        return $this->school;
    }

    /**
     * Add topics
     *
     * @param \AppBundle\Entity\Topic $topics
     * @return Activity
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
     * Add groups
     *
     * @param \AppBundle\Entity\DivisionOrGroup $groups
     * @return Activity
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
     * @return Activity
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
     * @return Activity
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
     * Set project
     *
     * @param \AppBundle\Entity\Project $project
     * @return Activity
     */
    public function setProject(\AppBundle\Entity\Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \AppBundle\Entity\Project
     */
    public function getProject()
    {
        return $this->project;
    }
}
