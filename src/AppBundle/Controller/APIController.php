<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * API controller.
 *
 * @Route("/data")
 */
class APIController extends Controller
{
    /**
     * List of all schools
     *
     * @Route("/schools.json", name="school_list", options={"expose"=true})
     * @Method("GET")
     */
    public function schoolListAction(Request $request)
    {
        // Get School Data
        $em = $this->getDoctrine()->getManager();
        $schools = $em->getRepository('AppBundle:School')->findAll();

        // Map to get data
        $includeLocation = $request->query->get('includeLocation');
        $schoolData = $this->getSchoolData($schools, $includeLocation);

        $response = new JsonResponse();
        $response->setData($schoolData);

        return $response;
    }

    /**
     * List of all group options
     *
     * @Route("/groups.json", name="school_list", options={"expose"=true})
     * @Method("GET")
     */
    public function getGroupsAction(Request $request)
    {
        $conn = $this->get('database_connection');

        $sql = "SELECT id, name, type, website, abbreviation FROM division_or_group";

        $statement = $conn->prepare($sql);
        $statement->execute();
        $results = $statement->fetchAll();

        $response = new JsonResponse();
        $response->setData($results);

        return $response;
    }

    /**
     * List of all categories options
     *
     * @Route("/categories.json", name="school_list", options={"expose"=true})
     * @Method("GET")
     */
    public function getCategoriesAction(Request $request)
    {
        $conn = $this->get('database_connection');

        $sql = "SELECT id, name FROM activity_category";

        $statement = $conn->prepare($sql);
        $statement->execute();
        $results = $statement->fetchAll();

        $response = new JsonResponse();
        $response->setData($results);

        return $response;
    }

    /**
     * Category counts by school
     *
     * @Route("/activities.json", name="get_activity_counts", options={"expose"=true})
     * @Method("GET")
     */
    public function getActivityCountsAction(Request $request)
    {
        $conn = $this->get('database_connection');
        $schoolCode = $request->query->get('schoolCode');

        if (!$schoolCode) {
            $sql = "SELECT count(*) as count, school.code, school.name as school_name, activity_category.name
                FROM activity
                JOIN school ON school.id = activity.school_id
                JOIN activity_category ON activity.activity_category_id = activity_category.id
                GROUP BY school.id, activity_category_id";

            $statement = $conn->prepare($sql);
            $statement->execute();
            $results = $statement->fetchAll();

            $bySchool = array();
            foreach ($results as $res) {
                $bySchool[$res['code']]['name'] = $res['school_name'];
                $bySchool[$res['code']]['categories'][$res['name']] = $res['count'];
                $bySchool[$res['code']]['total'] = isset($bySchool[$res['code']]['total']) ? $bySchool[$res['code']]['total'] + intval($res['count']) : intval($res['count']);
            }

            $response = new JsonResponse();
            $response->setData($bySchool);
        } else {
            $sql = "SELECT activity.name, activity.details, activity.shortDescription, activity.isFeatured, activity.isDistrictWide, activity.website, activity_category.name as category
                FROM activity
                JOIN school ON school.id = activity.school_id
                JOIN activity_category ON activity.activity_category_id = activity_category.id
                WHERE school.code = ?";
            $statement = $conn->prepare($sql);
            $statement->bindValue(1, $schoolCode);
            $statement->execute();
            $results = $statement->fetchAll();

            $byCategory = array();
            foreach ($results as $res) {
                $byCategory[$res['category']][] = $res;
            }

            $response = new JsonResponse();
            $response->setData($byCategory);
        }

        return $response;
    }


    /**
     * Category counts and locations for school(s)
     *
     * @Route("/schoolActivities.json", name="school_activity_data", options={"expose"=true})
     * @Method("GET")
     */
    public function getSchoolAndActivityDataAction(Request $request)
    {
        // Query db for activity data by school
        $conn = $this->get('database_connection');

        // Default query = all activities
        $statement = $this->prepareActivityQueryStatement($request, $conn);

        $statement->execute();
        $results = $statement->fetchAll();

        // Get School Data
        $em = $this->getDoctrine()->getManager();
        $sql = "SELECT school.code, school.name, school.type, school.gradeLevel, school.latitude, school.longitude FROM school where 1";
        $statement = $conn->prepare($sql);
        $statement->execute();
        $schools = $statement->fetchAll();

        $bySchool = array();

        // First all schools with activities
        foreach ($results as $res) {
            $bySchool[$res['code']]['name'] = $res['school_name'];
            $bySchool[$res['code']]['type'] = $res['type'];
            $bySchool[$res['code']]['gradeLevel'] = $res['gradeLevel'];
            $bySchool[$res['code']]['latitude'] = $res['latitude'];
            $bySchool[$res['code']]['longitude'] = $res['longitude'];
            $bySchool[$res['code']]['categories'][$res['name']] = $res['count'];
            $bySchool[$res['code']]['total'] = isset($bySchool[$res['code']]['total']) ? $bySchool[$res['code']]['total'] + intval($res['count']) : intval($res['count']);
        }
        // Then all others
        foreach ($schools as $school) {
            if (!array_key_exists($school['code'], $bySchool)) {
                $bySchool[$school['code']] = $school;
            }
        }

        $response = new JsonResponse();
        $response->setData($bySchool);

        return $response;
    }


    /* =============================================
     * ====      PRIVATE HELPER FUNCTIONS       ====
     * ============================================= */

    /**
     * Helper - Given the original request, prepare the
     * appropriate query to execute for the data.
     */
    private function prepareActivityQueryStatement($request, $conn)
    {
        // Possible filter options
        $category = $request->get('category');
        $group = $request->get('group');

        $sqlStart = "SELECT count(*) as count, school.code, school.name as school_name, school.type, school.gradeLevel, school.latitude, school.longitude, activity_category.name, activity_division_or_group.division_or_group_id as group_id
                FROM activity
                JOIN school ON school.id = activity.school_id
                JOIN activity_category ON activity.activity_category_id = activity_category.id
                JOIN activity_division_or_group ON activity.id =  activity_division_or_group.activity_id";
        $sqlEnd = "GROUP BY school.id, activity_category_id";

        if ($category && !$group) {
            $sql = $sqlStart . " WHERE activity.activity_category_id = ? " .$sqlEnd;
            $statement = $conn->prepare($sql);
            $statement->bindValue(1, $category);
        } elseif ($group && !$category) {
            $sql = $sqlStart . " WHERE activity_division_or_group.division_or_group_id = ? " . $sqlEnd;
            $statement = $conn->prepare($sql);
            $statement->bindValue(1, $group);
        } elseif ($category && $group) {
            $sql = $sqlStart .
                " WHERE activity.activity_category_id = ?
                AND activity_division_or_group.division_or_group_id = ? "
                . $sqlEnd
            ;

            $statement = $conn->prepare($sql);
            $statement->bindValue(1, $category);
            $statement->bindValue(2, $group);
        } else {
            $sql = $sqlStart . $sqlEnd;

            $statement = $conn->prepare($sql);
        }

        return $statement;
    }

    /**
     * Helper - Determine which format to give data in.
     */
    private function getSchoolData($schools, $includeLocation = false)
    {
        if (!$includeLocation){
            return array_map(function($school){
                return array(
                    'id' => $school->getId(),
                    'code' => $school->getCode(),
                    'name' => $school->getName(),
                    'type' => $school->getType(),
                );
            }, $schools);
        } else {
            $values = array_map(function($school){
                return array(
                    'id' => $school->getId(),
                    'code' => $school->getCode(),
                    'name' => $school->getName(),
                    'type' => $school->getType(),
                    'latitude' => $school->getLatitude(),
                    'longitude' => $school->getLongitude(),
                );
            }, $schools);
            $schoolCodes = array_map(function($school){
                return $school->getCode();
            }, $schools);

            return array_combine($schoolCodes, $values);
        }
    }
}
