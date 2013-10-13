<?php

namespace Viking\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Viking\MainBundle\Form\DocumentForm;

class AdminController extends Controller
{
    public function indexAction()
    {
        $document = new DocumentForm();
        $form = $this->createFormBuilder($document)
            ->add('name', 'text')
            ->add('file', 'file')
            ->add('Envoyer', 'submit')
            ->getForm();

        if ($this->getRequest()->isMethod('POST')) {
            $form->bind($this->getRequest());
            if ($form->isValid()) {

                $document->upload();

                return $this->render('VikingMainBundle:Admin:index.html.twig', array(
                    'form' => $form->createView(),
                    'mess' => 'Le fichier est bien enregisté'
                ));
            }else{
                return $this->render('VikingMainBundle:Admin:index.html.twig', array(
                    'form' => $form->createView(),
                    'mess' => 'il y a eu une erreur OMG !!!!'
                ));
            }
        }
        return $this->render('VikingMainBundle:Admin:index.html.twig', array(
            'form' => $form->createView()
        ));
    }

}
