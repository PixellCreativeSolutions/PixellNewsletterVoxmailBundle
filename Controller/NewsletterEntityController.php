<?php

namespace Pixell\NewsletterBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pixell\NewsletterBundle\Entity\NewsletterEntity;
use Pixell\NewsletterBundle\Form\NewsletterEntityType;

/**
 * NewsletterEntity controller.
 *
 * @Route("/amministrazione/newsletterentity")
 */
class NewsletterEntityController extends Controller
{

	/**
	 * Lists all entities.
	 *
	 * @Route("/page/{pagenumber}", defaults={"pagenumber" = 1}, name="newsletterentity")
	 * @Method("GET")
	 * @Template()
	 */
	public function indexAction($pagenumber= 1)
	{
		$em = $this->getDoctrine()->getManager();

		$entitiesRepo = $em->getRepository('PixellNewsletterBundle:NewsletterEntity');
		
		$entitiesQuery = $entitiesRepo->createQueryBuilder('n')->getQuery();
		
		$paginator  = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
			$entitiesQuery,
			$this->get('request')->query->get('page', $pagenumber)
		);

		return array(
			'entities' => $pagination,
		);
	}
	/**
	 * Creates a new entity.
	 *
	 * @Route("/", name="newsletterentity_create")
	 * @Method("POST")
	 * @Template("PixellNewsletterBundle:NewsletterEntity:new.html.twig")
	 */
	public function createAction(Request $request)
	{
		$entity = new NewsletterEntity();
		$form = $this->createCreateForm($entity);
		$form->handleRequest($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			return $this->redirect($this->generateUrl('newsletterentity_show', array('id' => $entity->getId())));
		}

		return array(
			'entity' => $entity,
			'form'   => $form->createView(),
		);
	}

	/**
	* Creates a form to create an entity.
	*
	* @param NewsletterEntity $entity The entity
	*
	* @return \Symfony\Component\Form\Form The form
	*/
	private function createCreateForm(NewsletterEntity $entity)
	{
		$isSuperAdmin = $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN');

		$form = $this->createForm(new NewsletterEntityType($isSuperAdmin), $entity, array(
			'action' => $this->generateUrl('newsletterentity_create'),
			'method' => 'POST',
		));

		$form->add('submit', 'submit', array('label' => 'Salva'));

		return $form;
	}

	/**
	 * Displays a form to create a new entity.
	 *
	 * @Route("/new", name="newsletterentity_new")
	 * @Method("GET")
	 * @Template()
	 */
	public function newAction()
	{
		$entity = new NewsletterEntity();
		$form   = $this->createCreateForm($entity);

		return array(
			'entity' => $entity,
			'form'   => $form->createView(),
		);
	}

	/**
	 * Finds and displays an entity.
	 *
	 * @Route("/{id}", name="newsletterentity_show")
	 * @Method("GET")
	 * @Template()
	 */
	public function showAction($id)
	{
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('PixellNewsletterBundle:NewsletterEntity')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find NewsletterEntity entity.');
		}

		$deleteForm = $this->createDeleteForm($id);

		return array(
			'entity'	  => $entity,
			'delete_form' => $deleteForm->createView(),
		);
	}

	/**
	 * Displays a form to edit an existing entity.
	 *
	 * @Route("/{id}/edit", name="newsletterentity_edit")
	 * @Method("GET")
	 * @Template()
	 */
	public function editAction($id)
	{
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('PixellNewsletterBundle:NewsletterEntity')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find NewsletterEntity entity.');
		}

		$editForm = $this->createEditForm($entity);
		$deleteForm = $this->createDeleteForm($id);

		return array(
			'entity'	  => $entity,
			'edit_form'   => $editForm->createView(),
			'delete_form' => $deleteForm->createView(),
		);
	}

	/**
	* Creates a form to edit an entity.
	*
	* @param NewsletterEntity $entity The entity
	*
	* @return \Symfony\Component\Form\Form The form
	*/
	private function createEditForm(NewsletterEntity $entity)
	{
		$isSuperAdmin = $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN');

		$form = $this->createForm(new NewsletterEntityType($isSuperAdmin), $entity, array(
			'action' => $this->generateUrl('newsletterentity_update', array('id' => $entity->getId())),
			'method' => 'PUT',
		));

		$form->add('submit', 'submit', array('label' => 'Aggiorna'));

		return $form;
	}
	/**
	 * Edits an existing NewsletterEntity entity.
	 *
	 * @Route("/{id}", name="newsletterentity_update")
	 * @Method("PUT")
	 * @Template("PixellNewsletterBundle:NewsletterEntity:edit.html.twig")
	 */
	public function updateAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('PixellNewsletterBundle:NewsletterEntity')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find NewsletterEntity entity.');
		}

		$deleteForm = $this->createDeleteForm($id);
		$editForm = $this->createEditForm($entity);
		$editForm->handleRequest($request);

		if ($editForm->isValid()) {
			$em->flush();
			
			$this->get('session')->getFlashBag()->add(
				'notice',
				'Modifiche salvate con successo'
			);

			return $this->redirect($this->generateUrl('newsletterentity_edit', array('id' => $id)));
		}else{
			$this->get('session')->getFlashBag()->add(
				'alert',
				'Modifiche non salvate. Controlla gli errori'
			);
		}

		return array(
			'entity'	  => $entity,
			'edit_form'   => $editForm->createView(),
			'delete_form' => $deleteForm->createView(),
		);
	}
	/**
	 * Deletes an entity.
	 *
	 * @Route("/{id}", name="newsletterentity_delete")
	 * @Method("DELETE")
	 */
	public function deleteAction(Request $request, $id)
	{
		$form = $this->createDeleteForm($id);
		$form->handleRequest($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$entity = $em->getRepository('PixellNewsletterBundle:NewsletterEntity')->find($id);

			if (!$entity) {
				throw $this->createNotFoundException('Unable to find NewsletterEntity entity.');
			}

			$em->remove($entity);
			$em->flush();
		}

		return $this->redirect($this->generateUrl('newsletterentity'));
	}

	/**
	 * Creates a form to delete an entity by id.
	 *
	 * @param mixed $id The entity id
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createDeleteForm($id)
	{
		return $this->createFormBuilder()
			->setAction($this->generateUrl('newsletterentity_delete', array('id' => $id)))
			->setMethod('DELETE')
			->add('submit', 'submit', array('label' => 'Cancella'))
			->getForm()
		;
	}
}
