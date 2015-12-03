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
     * List of all Filter Options
     *
     * @Route("/filters.json", name="map_filters", options={"expose"=true})
     * @Method("GET")
     */
    public function getFilterOptionsAction(Request $request)
    {
        $allFilters = array();

        $conn = $this->get('database_connection');

        // Categories
        $sql = "SELECT id, name FROM activity_category";
        $statement = $conn->prepare($sql);
        $statement->execute();
        $allFilters['Category'] = $statement->fetchAll();

        // Groups
        $sql = "SELECT id, name, type, website, abbreviation FROM division_or_group";
        $statement = $conn->prepare($sql);
        $statement->execute();
        $allFilters['Group'] = $statement->fetchAll();

        return new JsonResponse($allFilters);
    }

    /**
     * Listing of all current district wide projects
     *
     * @Route("/projects.json", name="get_district_projects", options={"expose"=true})
     * @Method("GET")
     */
    public function getDistrictWideProjects(Request $request)
    {
        $conn = $this->get('database_connection');
        $sql = "SELECT DISTINCT project.id, project.name, project.description, project.isFeatured,
          project.website, GROUP_CONCAT(activity_category.name) as categories,
          GROUP_CONCAT(division_or_group.id) as groups,
          GROUP_CONCAT(individual.id) as people
            FROM project
            JOIN project_activity_category ON project.id = project_activity_category.project_id
            LEFT JOIN activity_category ON project_activity_category.activity_category_id = activity_category.id
            LEFT JOIN project_division_or_group ON project.id =  project_division_or_group.project_id
            LEFT JOIN division_or_group ON project_division_or_group.division_or_group_id = division_or_group.id
            LEFT JOIN project_individual ON project.id =  project_individual.project_id
            LEFT JOIN individual ON project_individual.individual_id = individual.id
            WHERE project.isDistrictWide = 1
            GROUP BY project.id
            ORDER BY project.name;";

        $statement = $conn->prepare($sql);
        $statement->execute();
        $results = $statement->fetchAll();

        // $byCategory = array();
        $resultsCount = count($results);
        for ($i = 0; $i < $resultsCount; $i++) {
            // Convert to expected formats:
            // 1. Category Array
            $results[$i]['categories'] = array_values(array_unique(explode(",", $results[$i]['categories'])));

            if (!is_null($results[$i]['groups'])) {
                $results[$i]['groups'] = array_values(array_unique(explode(",", $results[$i]['groups'])));
                for($j = 0; $j < count($results[$i]['groups']); $j++) {
                    $sql = "SELECT name, abbreviation, website FROM division_or_group where id = ?";
                    $statement = $conn->prepare($sql);
                    $statement->bindValue(1, $results[$i]['groups'][$j]);
                    $statement->execute();
                    $groupData = $statement->fetch();
                    $results[$i]['groups'][$j] = $groupData;
                }
            }

            // 3. People JSON Object
            if (!is_null($results[$i]['people'])) {
                $results[$i]['people'] = array_values(array_unique(explode(",", $results[$i]['people'])));
                for($j = 0; $j < count($results[$i]['people']); $j++) {
                    $sql = "SELECT name, website FROM individual where id = ?";
                    $statement = $conn->prepare($sql);
                    $statement->bindValue(1, $results[$i]['people'][$j]);
                    $statement->execute();
                    $personData = $statement->fetch();
                    $results[$i]['people'][$j] = $personData;
                }
            }
        }

        $results = array_values($results); // Reset keys.

        return new JsonResponse($results);
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
        $em = $this->getDoctrine()->getManager();
        $schoolCode = $request->query->get('schoolCode') ? intval($request->query->get('schoolCode')) : null;

        // Possible Filters. Check for validity.
        // 1. Category Filter?
        $category = $request->query->get('category') ? intval($request->query->get('category')) : null;
        if ($category) {
            $categoryEntity = $em->getRepository('AppBundle:ActivityCategory')->find($category);
            if (!$categoryEntity) {
                return new JsonResponse(array('error' => "Category with id: $category does not exist."));
            }
        }
        // 2. Group Filter?
        $group = $request->query->get('group') ? intval($request->query->get('group')) : null;
        if ($group) {
            $groupEntity = $em->getRepository('AppBundle:DivisionOrGroup')->find($group);
            if (!$groupEntity) {
                return new JsonResponse(array('error' => "Group with id: $group does not exist."));
            }
        }

        if (!$schoolCode) {

            return new JsonResponse(array("error" => "Request must include a schoolCode"));

            $sql = "SELECT count(*) as count, school.code, school.name as school_name, activity_category.name
                FROM activity
                JOIN school ON school.id = activity.school_id
                JOIN activity_activity_category ON activity.id = activity_activity_category.activity_id
                LEFT JOIN activity_category ON activity_activity_category.activity_category_id = activity_category.id
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
            $sql = "SELECT DISTINCT activity.id, activity.name, activity.shortDescription as description, activity.isFeatured,
              activity.website, activity.project_id, GROUP_CONCAT(activity_category.name) as categories,
              GROUP_CONCAT(division_or_group.id) as groups,
              GROUP_CONCAT(individual.id) as people
                FROM activity
                JOIN school ON school.id = activity.school_id
                JOIN activity_activity_category ON activity.id = activity_activity_category.activity_id
                LEFT JOIN activity_category ON activity_activity_category.activity_category_id = activity_category.id
                LEFT JOIN activity_division_or_group ON activity.id =  activity_division_or_group.activity_id
                LEFT JOIN division_or_group ON activity_division_or_group.division_or_group_id = division_or_group.id
                LEFT JOIN activity_individual ON activity.id =  activity_individual.activity_id
                LEFT JOIN individual ON activity_individual.individual_id = individual.id
                WHERE school.code = ? GROUP BY activity.id ORDER BY activity.name;";

            $statement = $conn->prepare($sql);
            $statement->bindValue(1, $schoolCode);

            $statement->execute();
            $results = $statement->fetchAll();

            // $byCategory = array();
            $resultsCount = count($results);
            for ($i = 0; $i < $resultsCount; $i++) {
                // Description from Project if null
                if (is_null($results[$i]['description']) && $results[$i]['project_id']) {
                    $projectDetails = $this->getProjectDetails($results[$i]['project_id']);
                    $results[$i]['description'] = $projectDetails['description'];
                }

                if (is_null($results[$i]['website']) && $results[$i]['project_id']) {
                    if (!isset($projectDetails)) {
                        $projectDetails = $this->getProjectDetails($results[$i]['project_id']);
                    }
                    $results[$i]['website'] = $projectDetails['website'];
                }

                // Convert to expected formats:
                // 1. Category Array
                $results[$i]['categories'] = array_values(array_unique(explode(",", $results[$i]['categories'])));
                //    --- Filter for Category
                if (isset($categoryEntity) && !in_array($categoryEntity->getName(), $results[$i]['categories'])) {
                    // Remove from result set -- This does not include the category we're looking for
                    unset($results[$i]);
                    continue;
                }

                // 2. Groups JSON Object
                //    --- Filter for Group
                if (isset($groupEntity) && !in_array($groupEntity->getId(), explode(",", $results[$i]['groups']))) {
                    unset($results[$i]);
                    continue;
                }
                if (!is_null($results[$i]['groups'])) {
                    $results[$i]['groups'] = array_values(array_unique(explode(",", $results[$i]['groups'])));
                    for($j = 0; $j < count($results[$i]['groups']); $j++) {
                        $sql = "SELECT name, abbreviation, website FROM division_or_group where id = ?";
                        $statement = $conn->prepare($sql);
                        $statement->bindValue(1, $results[$i]['groups'][$j]);
                        $statement->execute();
                        $groupData = $statement->fetch();
                        $results[$i]['groups'][$j] = $groupData;
                    }
                }

                // 3. People JSON Object
                if (!is_null($results[$i]['people'])) {
                    $results[$i]['people'] = array_values(array_unique(explode(",", $results[$i]['people'])));
                    for($j = 0; $j < count($results[$i]['people']); $j++) {
                        $sql = "SELECT name, website FROM individual where id = ?";
                        $statement = $conn->prepare($sql);
                        $statement->bindValue(1, $results[$i]['people'][$j]);
                        $statement->execute();
                        $personData = $statement->fetch();
                        $results[$i]['people'][$j] = $personData;
                    }
                }
            }

            $results = array_values($results); // Reset keys.

            $response = new JsonResponse();
            $response->setData($results);
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
        $em = $this->getDoctrine()->getManager();

        // Query db for activity data by school
        $conn = $this->get('database_connection');

        // Filter parameters
        $category = $request->query->get('category') ? intval($request->query->get('category')) : null;
        $group = $request->query->get('group') ? intval($request->query->get('group')) : null;

        // Possible Filters. Check for validity.
        // 1. Category Filter?
        $category = $request->query->get('category') ? intval($request->query->get('category')) : null;
        if ($category) {
            $categoryEntity = $em->getRepository('AppBundle:ActivityCategory')->find($category);
            if (!$categoryEntity) {
                return new JsonResponse(array('error' => "Category with id: $category does not exist."));
            }
        }
        // 2. Group Filter?
        $group = $request->query->get('group') ? intval($request->query->get('group')) : null;
        if ($group) {
            $groupEntity = $em->getRepository('AppBundle:DivisionOrGroup')->find($group);
            if (!$groupEntity) {
                return new JsonResponse(array('error' => "Group with id: $group does not exist."));
            }
        }

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

        // Get categories for schools
        $sql = "SELECT school.code, GROUP_CONCAT(activity_category.name) as categories
                FROM activity
                JOIN school ON school.id = activity.school_id
                JOIN activity_activity_category ON activity.id = activity_activity_category.activity_id
                LEFT JOIN activity_category ON activity_activity_category.activity_category_id = activity_category.id
                GROUP BY school.id";
        $statement = $conn->prepare($sql);
        $statement->execute();
        $schoolCategories = $statement->fetchAll();
        $schoolCategories = array_reduce($schoolCategories, function ($result, $item) {
                $categories = array_unique(explode(",", $item['categories']));
                asort($categories);
                $categories = array_values($categories);
                $result[$item['code']] = $categories;
                return $result;
            }, array());

        $bySchool = array();

        // dump($schoolCategories); die;

        // First all schools with activities
        foreach ($results as $res) {
            $bySchool[$res['code']]['name'] = $res['school_name'];
            $bySchool[$res['code']]['type'] = $res['type'];
            $bySchool[$res['code']]['gradeLevel'] = $res['gradeLevel'];
            $bySchool[$res['code']]['latitude'] = $res['latitude'];
            $bySchool[$res['code']]['longitude'] = $res['longitude'];
            $bySchool[$res['code']]['website'] = $res['website'];
            $bySchool[$res['code']]['total'] = intval($res['count']);

            // Do Not Include if filtering by category
            if (!$category && !$group) {
                $bySchool[$res['code']]['categories'] = $schoolCategories[$res['code']];
            }
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

        $sqlStart = "SELECT count(distinct activity.id) as count, activity.id, school.code, school.name as school_name, school.type, school.gradeLevel, school.latitude, school.longitude, school.website, activity_division_or_group.division_or_group_id as group_id
                FROM activity
                JOIN school ON school.id = activity.school_id
                LEFT JOIN activity_activity_category ON activity.id = activity_activity_category.activity_id
                LEFT JOIN activity_division_or_group ON activity.id =  activity_division_or_group.activity_id";
        $sqlEnd = "GROUP BY school.id ORDER BY activity.name";

        if ($category && !$group) {
            $sql = $sqlStart . " WHERE activity_activity_category.activity_category_id = ? " .$sqlEnd;
            $statement = $conn->prepare($sql);
            $statement->bindValue(1, $category);
        } elseif ($group && !$category) {
            $sql = $sqlStart . " WHERE activity_division_or_group.division_or_group_id = ? " . $sqlEnd;
            $statement = $conn->prepare($sql);
            $statement->bindValue(1, $group);
            // dump($sql); die;
        } elseif ($category && $group) {
            $sql = $sqlStart .
                " WHERE activity_activity_category.activity_category_id = ?
                AND activity_division_or_group.division_or_group_id = ? "
                . $sqlEnd
            ;

            $statement = $conn->prepare($sql);
            $statement->bindValue(1, $category);
            $statement->bindValue(2, $group);
        } else {
            $sql = $sqlStart . " " . $sqlEnd;
            // dump($sql); die;
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

    private function getProjectDetails($projectId)
    {
        $conn = $this->get('database_connection');
        $sql = "SELECT description, website FROM project where id = ?";
        $statement = $conn->prepare($sql);
        $statement->bindValue(1, $projectId);
        $statement->execute();

        return $statement->fetch();
    }
}
