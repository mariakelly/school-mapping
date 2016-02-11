<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Activity;
use AppBundle\Form\ActivityType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Activity controller.
 *
 * @Route("/admin/activity")
 */
class ActivityController extends Controller
{

    /**
     * Lists all Activity entities.
     *
     * @Route("/", name="admin_activity")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Activity')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Lists all Activity for a given category.
     *
     * @Route("/by-category/{id}", name="admin_activity_by_category")
     * @Method("GET")
     * @Template("AppBundle:Activity:index.html.twig")
     */
    public function byCategoryAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:ActivityCategory')->find($id);

        if (!$category) {
            throw $this->createNotFoundException("Unable to find Category with ID: $id.");
        }

        $entities = $category->getActivities();

        return array(
            'title'    => "by Category - ".$category->getName(),
            'entities' => $entities,
        );
    }

    /**
     * Lists all Activity for a given group.
     *
     * @Route("/by-group/{id}", name="admin_activity_by_group")
     * @Method("GET")
     * @Template("AppBundle:Activity:index.html.twig")
     */
    public function byGroupAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $group = $em->getRepository('AppBundle:DivisionOrGroup')->find($id);

        if (!$group) {
            throw $this->createNotFoundException("Unable to find Group with ID: $id.");
        }

        $entities = $group->getActivities();

        return array(
            'title'    => "by Group - ".$group->getName(),
            'entities' => $entities,
        );
    }


    /**
     * Lists all Activity for a given school.
     *
     * @Route("/by-school/{id}", name="admin_activity_by_school")
     * @Method("GET")
     * @Template("AppBundle:Activity:index.html.twig")
     */
    public function bySchoolAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $school = $em->getRepository('AppBundle:School')->find($id);

        if (!$school) {
            throw $this->createNotFoundException("Unable to find School with ID: $id.");
        }

        $entities = $school->getActivities();

        return array(
            'title'    => "by School - ".$school->getName(),
            'entities' => $entities,
        );
    }


    /**
     * Lists all Activity for a given person.
     *
     * @Route("/by-person/{id}", name="admin_activity_by_person")
     * @Method("GET")
     * @Template("AppBundle:Activity:index.html.twig")
     */
    public function byPersonAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $person = $em->getRepository('AppBundle:Individual')->find($id);

        if (!$person) {
            throw $this->createNotFoundException("Unable to find Person with ID: $id.");
        }

        $entities = $person->getActivities();

        return array(
            'title'    => "by Person - ".$person->getName(),
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Activity entity.
     *
     * @Route("/", name="admin_activity_create")
     * @Method("POST")
     * @Template("AppBundle:Activity:new.html.twig")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function createAction(Request $request)
    {
        $entity = new Activity();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->sendAdminNotification($entity, "created");

            return $this->redirect($this->generateUrl('admin_activity_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Activity entity.
     *
     * @param Activity $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Activity $entity)
    {
        $form = $this->createForm(new ActivityType(), $entity, array(
            'action' => $this->generateUrl('admin_activity_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array(
            'label' => 'Create',
            'attr'  => array('class' => 'btn btn-primary'),
        ));

        return $form;
    }

    /**
     * Displays a form to create a new Activity entity.
     *
     * @Route("/new", name="admin_activity_new")
     * @Method("GET")
     * @Template()
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function newAction()
    {
        $entity = new Activity();
        $year = $this->getDoctrine()->getManager()->getRepository('AppBundle:Year')->findOneByIsCurrentYear(1);
        $entity->addYear($year);

        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Activity entity.
     *
     * @Route("/{id}", name="admin_activity_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Activity')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Activity entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Activity entity.
     *
     * @Route("/{id}/edit", name="admin_activity_edit")
     * @Method("GET")
     * @Template()
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Activity')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Activity entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Activity entity.
    *
    * @param Activity $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Activity $entity)
    {
        $form = $this->createForm(new ActivityType(), $entity, array(
            'action' => $this->generateUrl('admin_activity_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array(
            'label' => 'Save',
            'attr'  => array('class' => 'btn btn-primary'),
        ));

        return $form;
    }
    /**
     * Edits an existing Activity entity.
     *
     * @Route("/{id}", name="admin_activity_update")
     * @Method("PUT")
     * @Template("AppBundle:Activity:edit.html.twig")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Activity')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Activity entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->sendAdminNotification($entity, "edited");

            return $this->redirect($this->generateUrl('admin_activity_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Activity entity.
     *
     * @Route("/{id}", name="admin_activity_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Activity')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Activity entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_activity'));
    }

    /**
     * Creates a form to delete a Activity entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_activity_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete', 'attr' => array('class' => 'btn btn-danger')))
            ->getForm()
        ;
    }

    private function sendAdminNotification($entity, $action)
    {
        $user = $this->get('security.context')->getToken()->getUser();

        $body = "Activity $action by <strong>".$user->getUsername()."</strong> :: ". (string) $entity . " ID: ".$entity->getId();
        $message = \Swift_Message::newInstance()
            ->setSubject('Penn GSE in Philly - Activity Added/Edited')
            // ->setFrom('send@example.com')
            ->setTo('mariakel@gse.upenn.edu')
            ->setBody($body,'text/html');

        $this->get('mailer')->send($message);
    }
}
