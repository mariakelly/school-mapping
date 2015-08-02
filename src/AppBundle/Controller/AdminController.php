<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin_home")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        // Get Years data
        $years = $em->getRepository('AppBundle:Year')->findAll();

        // Detect whether we have the required boundary files for mapping
        $boundaryFiles = array();
        foreach ($years as $year) {
            $expectedFilename = __DIR__."/../Resources/public/js/boundaries/boundaries-".$year->getYear().".js";
            $boundaryFiles[$year->getYear()] = array(
                "filename" => $expectedFilename,
                "status" => file_exists($expectedFilename),
            );
        }

        // Get School Data
        $schools = $em->getRepository('AppBundle:School')->findAll();

        // Get Topic Data
        $topics = $em->getRepository('AppBundle:Topic')->findAll();

        // Get Category Data
        $categories = $em->getRepository('AppBundle:ActivityCategory')->findAll();

        // Get People Data
        $people = $em->getRepository('AppBundle:Individual')->findAll();

        // Get Group Data
        $groups = $em->getRepository('AppBundle:DivisionOrGroup')->findAll();

        return array(
            'years' => $years,
            'boundaryFiles' => $boundaryFiles,
            'schools' => $schools,
            'topics' => $topics,
            'categories' => $categories,
            'people' => $people,
            'groups' => $groups,
        );
    }
}
