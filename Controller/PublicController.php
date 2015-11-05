<?php

namespace PixellHub\NewsletterBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PixellHub\NewsletterBundle\Entity\NewsletterEntity;
use PixellHub\NewsletterBundle\Form\NewsletterEntityType;

/**
 * @Route("/newsletter", defaults={"_locale"="it"}, requirements={"_locale" = "it|en"})
 */
class PublicController extends Controller
{
    /**
     * @Route("/form", name="frontend_form")
     * @Template()
     */
    public function newsletterAction(Request $request)
    {
        $entity = new NewsletterEntity();
        $form = $this->createForm(new NewsletterEntityType(), $entity, array(
                'method' => 'POST',
            ));
        
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setCreatedBy('frontend registration');
            $entity->setUpdatedBy('frontend registration');
            $em->persist($entity);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add(
                'notice',
                'Iscrizione avvenuta con successo'
            );
        }

        return array(
            'form'   => $form->createView(),
        );
    }
}
