<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Individual;
use AppBundle\Form\IndividualType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Individual controller.
 *
 * @Route("/admin/people")
 */
class IndividualController extends Controller
{

    /**
     * Creates a new Individual entity.
     *
     * @Route("/", name="admin_people_create")
     * @Method("POST")
     * @Template("AppBundle:Individual:new.html.twig")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function createAction(Request $request)
    {
        $entity = new Individual();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->sendAdminNotification($entity, 'created');

            return $this->redirect($this->generateUrl('admin_people_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Individual entity.
     *
     * @param Individual $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Individual $entity)
    {
        $form = $this->createForm(new IndividualType(), $entity, array(
            'action' => $this->generateUrl('admin_people_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new Individual entity.
     *
     * @Route("/new", name="admin_people_new")
     * @Method("GET")
     * @Template()
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function newAction()
    {
        $entity = new Individual();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Individual entity.
     *
     * @Route("/{id}", name="admin_people_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Individual')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Individual entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Individual entity.
     *
     * @Route("/{id}/edit", name="admin_people_edit")
     * @Method("GET")
     * @Template()
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Individual')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Individual entity.');
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
    * Creates a form to edit a Individual entity.
    *
    * @param Individual $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Individual $entity)
    {
        $form = $this->createForm(new IndividualType(), $entity, array(
            'action' => $this->generateUrl('admin_people_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }
    /**
     * Edits an existing Individual entity.
     *
     * @Route("/{id}", name="admin_people_update")
     * @Method("PUT")
     * @Template("AppBundle:Individual:edit.html.twig")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Individual')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Individual entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->sendAdminNotification($entity, 'edited');

            return $this->redirect($this->generateUrl('admin_people_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Individual entity.
     *
     * @Route("/{id}", name="admin_people_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Individual')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Individual entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_people'));
    }

    /**
     * Creates a form to delete a Individual entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_people_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete', 'attr' => array('class' => 'btn btn-danger')))
            ->getForm()
        ;
    }

    private function sendAdminNotification($entity, $action)
    {
        $user = $this->get('security.context')->getToken()->getUser();

        $body = "Person $action by <strong>".$user->getUsername()."</strong> :: ". (string) $entity . " ID: ".$entity->getId();
        $message = \Swift_Message::newInstance()
            ->setSubject('Penn GSE in Philly - Person Added/Edited')
            ->setFrom('it-web@gse.upenn.edu')
            ->setTo('mariakel@gse.upenn.edu')
            ->setBody($body,'text/html');

        $this->get('mailer')->send($message);
    }
}
