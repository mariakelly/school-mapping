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
     * @Route("/schools.json", name="school_list")
     * @Method("GET")
     */
    public function schoolDataAction(Request $request)
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
            return array_map(function($school){
                return array(
                    'id' => $school->getId(),
                    'code' => $school->getCode(),
                    'name' => $school->getName(),
                    'latitude' => $school->getLatitude(),
                    'longitude' => $school->getLongitude(),
                );
            }, $schools);
        }
    }
}
