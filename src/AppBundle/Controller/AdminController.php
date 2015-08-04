<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Activity;

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

    /**
     * Import activities from file.
     *
     * @Route("/admin/import/activities", name="import_activities")
     * @Template()
     */
    public function importActivitiesAction(Request $request)
    {
        // Create Upload Form
        $form = $this->createFormBuilder()
            ->add('importFile', 'file')
            ->add('Upload & Import', 'submit', array(
                'attr' => array('class' => 'btn btn-primary'),
            ))
            ->getForm();

        $expectedColumns = array(
            'school_code',
            'activity_name',
            'category',
            'topic',
            'group',
            'person',
            'website',
        );

        $returnParams = array();
        if ("POST" == $request->getMethod()) {
            $form->handleRequest($request);
            $returnParams = $this->processImportRequest($form, $expectedColumns);
        }

        // Set Flash Messages regarding any errors encountered.
        if (count($returnParams) && count($returnParams['errors'])) {
            foreach ($returnParams['errors'] as $error) {
                $this->get('session')->getFlashBag()->add('error', $error);
            }
        } elseif (count($returnParams) && !count($returnParams['errors'])) {
            $this->get('session')->getFlashBag()->add('success', 
                "Success :: School activity data successfully added (".$returnParams['rows_imported']." entries added)");

            return $this->redirect($this->generateUrl('admin_activity'));
        }

        return array(
            'form' => $form->createView(),
            'expectedColumns' => $expectedColumns,
        );
    }


    /**
     * Private Helper Function to Process Results.
     */
    private function processImportRequest($form, $expectedColumns)
    {
        // Initialize Error Message Array.
        $errors = array();

        // Check file extension, move and open the File. 
        $file = $form['importFile']->getData();
        $extension = $file->getClientOriginalExtension();
        if (!$extension || $extension != "csv") {
            var_dump($extension); die;
            $errors[] = "File extension error: Please only upload .csv files.";
        } else {
            $dir = $this->get('kernel')->getRootDir() . "/../tmp/";
            $filename = date('YMd_gisa')."_activities.".$extension;
            $file->move($dir, $filename);

            // Process the File
            ini_set("auto_detect_line_endings", 1); 
            $fp = fopen($dir . $filename, "r");
            $header = fgetcsv($fp);

            // Check $header against $expectedColumns            
            if ($header != $expectedColumns) {
                $errorMsg = "Problem with header row of import file. Please see \"Expected Columns\" list below.\n";
                $errorMsg .= "(Received Columns: ";
                foreach ($header as $col) {
                    $errorMsg .= $col . " | ";
                }
                $errorMsg = substr($errorMsg, 0, -3);
                $errorMsg .= ")";

                $errors[] = $errorMsg;
            }
        }

        // If we still have no errors, continue processing.
        if (count($errors) == 0) {
            // Split up data
            $lineNum = 2;
            while (($data = fgetcsv($fp, 0, ",")) !== FALSE) {
                for ($i = 0; $i < count($data); $i++) {
                    $key = $header[$i];
                    $importData[$lineNum - 2][$key] = trim($data[$i]);

                    if (($key == "school_code" || $key == "category" || $key == "activity_name") && $data[$i] == "") {
                        $errors[] = "Required field - Line $lineNum: Column $key cannot be left blank.";
                    } elseif ($key == "website" && $data[$i] != "" && substr($data[$i], 0, 4) != "http") {
                        $errors[] = "Line $lineNum: Column $key must contain a valid url (".$data[$i].")";
                    }
                }
                $lineNum++;
            }

            // No point in continuing.
            if (count($errors) != 0) {
                break;
            }

            // Get all Repositories for Easy Access.
            $doctrine = $this->getDoctrine();
            $em = $doctrine->getManager();
            $yearRepo = $doctrine->getRepository('AppBundle:Year');
            $activityRepo = $doctrine->getRepository('AppBundle:Activity');
            $schoolRepo = $doctrine->getRepository('AppBundle:School');
            $divisionOrGroupRepo = $doctrine->getRepository('AppBundle:DivisionOrGroup');
            $individualRepo = $doctrine->getRepository('AppBundle:Individual');
            $topicRepo = $doctrine->getRepository('AppBundle:Topic');
            $activityCategoryRepo = $doctrine->getRepository('AppBundle:ActivityCategory');

            // Get current year, it applies to all imported.
            $currentYear = $yearRepo->findOneByIsCurrentYear(1);

            foreach ($importData as $i => $d) {
                // Required
                $school = $schoolRepo->findOneByCode($d['school_code']);
                $category = $activityCategoryRepo->findOneByName($d['category']);

                // Optional
                $topic = ($d['topic'] != "") ? $topicRepo->findOneByName($d['topic']) : null;
                $group = ($d['group'] != "") ? $divisionOrGroupRepo->findOneByAbbreviation($d['group']) : null;
                $person = ($d['person'] != "") ? $individualRepo->findOneByName($d['person']) : null;

                // Validation
                $validationErrors = $this->validateActivityData($data = $d, $lineNum = $i, $school, $category, $topic, $group, $person);
                $errors = array_merge($errors, $validationErrors);

                // Again, no point in continuing.
                if (count($validationErrors) != 0) {
                    break; 
                }

                // Set Data on Activity and Persist.
                $activity = new Activity();

                // 1 - required data
                $activity->setName($d['activity_name']);
                $activity->setSchool($school);
                $activity->setActivityCategory($category);
                $activity->setIsFeatured(0);
                $activity->setIsDistrictWide(0);

                // 2 - optional data
                if ($topic) {
                    $activity->addTopic($topic);
                }
                if ($group) {
                    $activity->addGroup($group);
                }
                if ($person) {
                    $activity->addPerson($person);
                }
                if ($d['website'] != "") {
                    $activity->setWebsite($d['website']);
                }

                // 3 - persist
                $em->persist($activity);
            }

            if (!count($errors)) {
                $em->flush();
            }
        }

        // TODO - Destroy File
        // $file;

        return array(
            'errors' => $errors,
            'rows_imported' => isset($importData) ? count($importData) : 0,
        );
    }

    /**
     * Helper function to validate activity data.
     */
    private function validateActivityData($data, $lineNum, $school, $category, $topic, $group, $person)
    {
        $errors = array();

        // School
        if (!$school) {
            $errors[] = "Line $lineNum: Could not find school with code: ".$data['school_code'];
        }

        // Category
        if (!$category) {
            $errors[] = "Line $lineNum: Could not find category: ".$data['category'];
        }

        // Topic
        if ($data['topic'] != "" && !$topic) {
            $errors[] = "Line $lineNum: Could not find topic: ".$data['topic'];
        }

        // Group
        if ($data['group'] != "" && !$group) {
            $errors[] = "Line $lineNum: Could not find group: ".$data['group'];
        }

        // Person
        if ($data['person'] != "" && !$person) {
            $errors[] = "Line $lineNum: Could not find person: ".$data['person'];
        }

        return $errors;
    }
}
