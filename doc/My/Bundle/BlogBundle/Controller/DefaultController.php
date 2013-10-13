<?php

namespace My\Bundle\BlogBundle\Controller;

use Vince\Bundle\CmsBundle\Controller\DefaultController as BaseController;

class DefaultController extends BaseController
{

    public function timelineAction()
    {
        return $this->render('MyBlogBundle:Component:timeline.js.twig', array(
            'skills' => array()
        ));
    }
}
