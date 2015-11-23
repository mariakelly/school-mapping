<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Activity;
use AppBundle\Entity\Project;

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
     * @Route("/admin/import/activities/{project}", name="import_activities")
     * @Template()
     */
    public function importActivitiesAction(Request $request, $project = null)
    {
        // Create Upload Form
        $form = $this->createFormBuilder()
            ->add('importFile', 'file')
            ->add('Upload & Import', 'submit', array(
                'attr' => array('class' => 'btn btn-primary'),
            ))
            ->getForm();

        if (!is_null($project) && $project == "project") {
            $expectedColumns = array(
                'activity_name',
                'category',
                'group',
                'person',
                'partners',
                'school_codes',
                'description',
                'website',
            );
        } else {
            $expectedColumns = array(
                'school_code',
                'activity_name',
                'category',
                'group',
                'person',
                'partners',
                'description',
                'website',
            );
        }

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
            'project' => $project,
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
                echo implode("<br>", $errors); die;
            }

            $doctrine = $this->getDoctrine();
            $em = $doctrine->getManager();
            // $errors will be populated with any errors that arise.
            $this->saveData($expectedColumns, $em, $importData, $errors);

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
     * Helper function to save data based on expected
     */
    private function saveData($expectedColumns, $em, $importData, &$errors)
    {
        // Get all Repositories for Easy Access.
        $yearRepo = $em->getRepository('AppBundle:Year');
        $activityRepo = $em->getRepository('AppBundle:Activity');
        $schoolRepo = $em->getRepository('AppBundle:School');
        $divisionOrGroupRepo = $em->getRepository('AppBundle:DivisionOrGroup');
        $individualRepo = $em->getRepository('AppBundle:Individual');
        $topicRepo = $em->getRepository('AppBundle:Topic');
        $activityCategoryRepo = $em->getRepository('AppBundle:ActivityCategory');

        // Get current year, it applies to all imported.
        $currentYear = $yearRepo->findOneByIsCurrentYear(1);

        // Which process are we following?
        $processMultipleSchools = true;
        if ($expectedColumns[0] == "school_code") {
            $processMultipleSchools = false;
        }

        foreach ($importData as $i => $d) {
            $lineNum = $i + 1;
            $schools = array();
            if (isset($d['school_codes'])) {
                $schools = explode(",", $d['school_codes']);
                $d['school_code'] = $d['school_codes']; // In case there is only one, set this for ease of access.
            }
            if (!$processMultipleSchools || count($schools) === 1) { // have a single school
                // Create New Activity
                $activity = new Activity();

                // Required
                $school = $schoolRepo->findOneByCode($d['school_code']);
                $this->processCSVColumn($name = "category", $lineNum, $activityCategoryRepo, $activity, $d['category'], 'addActivityCategory', $errors);

                // Optional
                $this->processCSVColumn($name = "group", $lineNum, $divisionOrGroupRepo, $activity, $d['group'], 'addGroup', $errors);
                $this->processCSVColumn($name = "person", $lineNum, $individualRepo, $activity, $d['person'], 'addPerson', $errors);

                // Additional Validation
                $validationErrors = $this->validateActivityData($data = $d, $lineNum, $school, $d['school_code']);
                $errors = array_merge($errors, $validationErrors);

                // Again, no point in continuing.
                if (count($validationErrors) != 0 || count($errors) != 0) {
                    break; 
                }

                // Set data and persist.
                // 1 - required data
                $activity->setName($d['activity_name']);
                $activity->setSchool($school);
                $activity->setIsFeatured(0);
                $activity->setIsDistrictWide(0);
                $activity->addYear($currentYear);

                // 2 - optional data
                if ($d['website'] != "") {
                    $activity->setWebsite($d['website']);
                }
                if ($d['description'] != "") {
                    $activity->setShortDescription($d['description']);
                }
                if ($d['partners'] != "") {
                    $activity->setPartners($d['partners']);
                }

                // 3 - persist
                $em->persist($activity);
            } else {
                // Process this as a project.
                // Create a project
                $project = new Project();
                $project->setName($d['activity_name']);
                $project->setDescription($d['description']);
                $project->addYear($currentYear);
                $project->setIsFeatured(0);
                $project->setIsDistrictWide(0);

                if ($d['website'] != "") {
                    $project->setWebsite($d['website']);
                }

                // Optional
                $this->processCSVColumn($name = "group", $lineNum, $divisionOrGroupRepo, $project, $d['group'], 'addGroup', $errors);
                $this->processCSVColumn($name = "person", $lineNum, $individualRepo, $project, $d['person'], 'addPerson', $errors);

                foreach ($schools as $schoolCode) {
                    $activity = new Activity();
                    $schoolCode = trim($schoolCode);

                    if ($schoolCode == "") {
                        continue;
                    }

                    $school = $schoolRepo->findOneByCode($schoolCode);

                    $this->processCSVColumn($name = "category", $lineNum, $activityCategoryRepo, $activity, $d['category'], 'addActivityCategory', $errors);
                    $this->processCSVColumn($name = "group", $lineNum, $divisionOrGroupRepo, $activity, $d['group'], 'addGroup', $errors);
                    $this->processCSVColumn($name = "person", $lineNum, $individualRepo, $activity, $d['person'], 'addPerson', $errors);

                    // Additional Validation
                    $validationErrors = $this->validateActivityData($data = $d, $lineNum, $school, $schoolCode);
                    $errors = array_merge($errors, $validationErrors);

                    // Again, no point in continuing.
                    if (count($validationErrors) != 0 || count($errors) != 0) {
                        break; 
                    }

                    // 1 - required data
                    $activity->setName($d['activity_name']);
                    $activity->setSchool($school);
                    $activity->setIsFeatured(0);
                    $activity->setIsDistrictWide(0);
                    $activity->addYear($currentYear);
                    $activity->setProject($project);

                    // 2 - optional data
                    if ($d['website'] != "") {
                        $activity->setWebsite($d['website']);
                    }
                    if ($d['description'] != "") {
                        $activity->setShortDescription($d['description']);
                    }
                    if ($d['partners'] != "") {
                        $activity->setPartners($d['partners']);
                    }

                    $project->addActivity($activity);

                    $em->persist($activity);
                    $em->persist($project);
                }
            }
        }
    }

    /**
     * Helper function to process a CSV column and lookup data by name
     * then add to the given activity.
     */
    private function processCSVColumn($name, $lineNum, $repo, $activity, $importData, $addDataFunction, &$errors)
    {
        if ($importData != "") {
            if (count($data = explode(",", $importData)) == 1) {
                $entity = $repo->findOneByName($importData);
                if (!$entity) {
                    // if (is_callable($repo, 'findOneByName')) {
                    //     echo "got here single"; die;
                        $entity = $repo->findOneByAbbreviation($importData);
                    // }
                    if (!$entity) { // still no entity
                        $errors[] = "Could not find $name: ".$importData." on line: ".$lineNum;
                        return;
                    }
                }
                call_user_func(array($activity, $addDataFunction), $entity);
            } else {
                foreach ($data as $d) {
                    $entity = $repo->findOneByName(trim($d));
                    if (!$entity) {
                        // if (method_exists($repo, 'findOneByAbbreviation')) {
                        //     echo "got here multiple"; die;
                            $entity = $repo->findOneByAbbreviation(trim($d));
                        // }
                        if (!$entity) { // still no entity
                            // echo "got here multiple"; die;
                            $errors[] = "Could not find $name: ".trim($d)." on line: ".$lineNums;
                            return;
                        }
                    }
                    call_user_func(array($activity, $addDataFunction), $entity);
                }
            }
        }
    }

    /**
     * Helper function to validate activity data.
     */
    private function validateActivityData($data, $lineNum, $school, $code)
    {
        $errors = array();

        // School
        if (!$school) {
            $errors[] = "Line $lineNum: Could not find school with code: ".$code;
        }

        return $errors;
    }
}
