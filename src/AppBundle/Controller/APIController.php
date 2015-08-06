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
     * Category counts by school
     *
     * @Route("/activities.json", name="get_activity_counts", options={"expose"=true})
     * @Method("GET")
     */
    public function getActivityCountsAction(Request $request)
    {
        $conn = $this->get('database_connection');
        $sql = "SELECT count(*) as count, school.code, school.name, activity_category.name 
            FROM activity 
            JOIN school ON school.id = activity.school_id 
            JOIN activity_category ON activity.activity_category_id = activity_category.id 
            GROUP BY school.id, activity_category_id";

        $statement = $conn->prepare($sql);
        $statement->execute();
        $results = $statement->fetchAll();

        $bySchool = array();
        foreach ($results as $res) {
            $bySchool[$res['code']]['categories'][$res['name']] = $res['count'];
            $bySchool[$res['code']]['total'] = isset($bySchool[$res['code']]['total']) ? $bySchool[$res['code']]['total'] + intval($res['count']) : intval($res['count']);
        }

        $response = new JsonResponse();
        $response->setData($bySchool);

        return $response;

    }


    /* =============================================
     * ====      PRIVATE HELPER FUNCTIONS       ====
     * ============================================= */

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
                );
            }, $schools);
        } else {
            $values = array_map(function($school){
                return array(
                    'id' => $school->getId(),
                    'code' => $school->getCode(),
                    'name' => $school->getName(),
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
