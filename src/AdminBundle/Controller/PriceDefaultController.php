<?php

namespace AdminBundle\Controller;

use CruiseBundle\Entity\PriceDefault;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Pricedefault controller.
 *
 * @Route("pricedefault")
 */
class PriceDefaultController extends Controller
{
    /**
     * Lists all priceDefault entities.
     *
     * @Route("/", name="pricedefault_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $priceDefaults = $em->getRepository('CruiseBundle:PriceDefault')->findAll();

        return $this->render('pricedefault/index.html.twig', array(
            'priceDefaults' => $priceDefaults,
        ));
    }

    /**
     * Creates a new priceDefault entity.
     *
     * @Route("/new", name="pricedefault_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $priceDefault = new Pricedefault();
        $form = $this->createForm('CruiseBundle\Form\PriceDefaultType', $priceDefault);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($priceDefault);
            $em->flush($priceDefault);

            return $this->redirectToRoute('pricedefault_show', array('id' => $priceDefault->getId()));
        }

        return $this->render('pricedefault/new.html.twig', array(
            'priceDefault' => $priceDefault,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a priceDefault entity.
     *
     * @Route("/{id}", name="pricedefault_show")
     * @Method("GET")
     */
    public function showAction(PriceDefault $priceDefault)
    {
        $deleteForm = $this->createDeleteForm($priceDefault);

        return $this->render('pricedefault/show.html.twig', array(
            'priceDefault' => $priceDefault,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing priceDefault entity.
     *
     * @Route("/{id}/edit", name="pricedefault_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, PriceDefault $priceDefault)
    {
        $deleteForm = $this->createDeleteForm($priceDefault);
        $editForm = $this->createForm('CruiseBundle\Form\PriceDefaultType', $priceDefault);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('pricedefault_edit', array('id' => $priceDefault->getId()));
        }

        return $this->render('pricedefault/edit.html.twig', array(
            'priceDefault' => $priceDefault,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a priceDefault entity.
     *
     * @Route("/{id}", name="pricedefault_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, PriceDefault $priceDefault)
    {
        $form = $this->createDeleteForm($priceDefault);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($priceDefault);
            $em->flush($priceDefault);
        }

        return $this->redirectToRoute('pricedefault_index');
    }

    /**
     * Creates a form to delete a priceDefault entity.
     *
     * @param PriceDefault $priceDefault The priceDefault entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(PriceDefault $priceDefault)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('pricedefault_delete', array('id' => $priceDefault->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
