<?php

namespace Viking\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('VikingMainBundle:Default:index.html.twig');
    }
}
