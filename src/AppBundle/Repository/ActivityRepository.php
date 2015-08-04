<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ActivityRepository extends EntityRepository
{
    public function findAllOrderedByName()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT a FROM AppBundle:Activity a ORDER BY a.name ASC'
            )
            ->getResult();
    }

    /**
     * Given one activity, see if there are any matches
     * with the same basic data.
     *  -- This is to avoid dupes in the upload/add process. --
     */
    public function findMatchingActivity($activityData)
    {
    	$matchingActivity = null;
    	return $matchingActivity;
    }
}