<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/static", name="homepage")
     * @Template()
     */
    public function workingAction(Request $request)
    {
        $currentYear = $this->getDoctrine()->getManager()->getRepository('AppBundle:Year')->findOneByIsCurrentYear(1);
        $mapFile = $this->container->get('templating.helper.assets')->getUrl('bundles/app/js/boundaries/boundaries-'.$currentYear->getYear().'.js');

        return array(
            'mapFile' => $mapFile,
        );
    }

    /**
     * @Route("/", name="map_home")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $currentYear = $this->getDoctrine()->getManager()->getRepository('AppBundle:Year')->findOneByIsCurrentYear(1);
        $mapFile = $this->container->get('templating.helper.assets')->getUrl('bundles/app/js/boundaries/boundaries-'.$currentYear->getYear().'.js');

        return array(
            'mapFile' => $mapFile,
        );
    }
}
