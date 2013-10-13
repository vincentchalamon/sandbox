<?php

namespace Viking\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Viking\MainBundle\Entity\Article;
use Viking\MainBundle\Form\DocumentForm;

class ArticleController extends Controller
{
    public function indexAction()
    {
        $articles = $this->getDoctrine()->getRepository('VikingMainBundle:article')->findAll();
        return $this->render('VikingMainBundle:Article:index.html.twig', array('articles' => $articles));
    }

    public function viewAction($id)
    {
        $article = $this->getDoctrine()->getRepository('VikingMainBundle:article')->find($id);
        return $this->render('VikingMainBundle:Article:view.html.twig', array('article' => $article));
    }

    public function editAction($id = null)
    {
        $document = new DocumentForm();
        $document->getJson();
        $em = $this->getDoctrine()->getManager();
        if($id == null){
            $article = new Article();
        }else{
            $article = $em->getRepository("VikingMainBundle:Article")->find($id) ;
        }
        $form = $this->createFormBuilder($article)
                ->add('name', 'text')
                ->add('content', 'redactor')
                ->add('date', 'datetime',
                    array('data' => new \DateTime())
                    )
                ->add('author', 'text')
                ->add('ok', 'submit')
                ->getForm();
        $request = $this->getRequest();
        if($request->isMethod("post")){
            $form->bind($request);
            if($form->isValid()){
                $em->persist($article);
                $em->flush();
                return $this->redirect($this->generateUrl('viking_article_view', array('id' => $article->getId())));
            }
        }

        return $this->render('VikingMainBundle:Admin/Article:edit.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
