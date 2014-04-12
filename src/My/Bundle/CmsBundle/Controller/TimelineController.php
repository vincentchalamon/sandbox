<?php

/*
 * This file is part of the MyCms bundle.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\CmsBundle\Controller;

use Doctrine\Common\Inflector\Inflector;
use My\Bundle\SkillBundle\Entity\Skill;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Render timeline
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class TimelineController extends Controller
{

    /**
     * Render timeline
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     * @return Response
     */
    public function indexAction()
    {
        $skills   = $this->get('my.repository.skill')->findAllOrdered('startedAt', 'ASC');
        $elements = array();
        foreach ($skills as $skill) {
            /** @var Skill $skill */
            $element = array(
                'start' => $skill->getStartedAt()->format('Y-m-d'),
                'label' => $skill->getTitle()
            );
            if ($skill->getEndedAt()) {
                $element['end'] = $skill->getEndedAt()->format('Y-m-d');
            }
            if ($skill->isStudies()) {
                $element['css'] = 'jquery-timeline-item-formation';
            }
            $elements[] = $element;
        }

        return $this->render('MyCmsBundle:Timeline:index.js.twig', array('elements' => json_encode($elements)));
    }

    /**
     * Render CV as pdf
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     * @return Response
     */
    public function downloadAsPdfAction()
    {
        $html = $this->renderView('MyCmsBundle:Timeline:pdf.html.twig', array(
                'skills' => $this->get('my.repository.skill')->findAllOrdered('startedAt', 'DESC')
            )
        );

        return new Response($this->get('knp_snappy.pdf')->getOutputFromHtml($html), 200, array(
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'filename="Curriculum-Vitae-de-Vincent-Chalamon.pdf"'
            )
        );
    }
}
