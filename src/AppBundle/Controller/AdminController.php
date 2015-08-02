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
        $years = $em->getRepository('AppBundle:Year')->findAll();

        $boundaryFiles = array();
        foreach ($years as $year) {
            $expectedFilename = __DIR__."/../Resources/public/js/boundaries/boundaries-".$year->getYear().".js";
            $boundaryFiles[$year->getYear()] = array(
                "filename" => $expectedFilename,
                "status" => file_exists($expectedFilename),
            );
        }

        return array(
            'years' => $years,
            'boundaryFiles' => $boundaryFiles,
        );
    }
}
