<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\BlogBundle\Listener;

use Doctrine\ORM\EntityManager;
use My\Bundle\BlogBundle\Form\Type\TestType;
use Symfony\Component\Form\FormFactory;
use Vince\Bundle\CmsBundle\Event\CmsEvent;

/**
 * Description of SearchListener
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class CmsListener
{

    protected $em;
    protected $formFactory;

    public function onHomepageLoad(CmsEvent $event)
    {
        $event->addOption('realisations', $this->em->getRepository('VinceCmsBundle:Article')->findPublishedByCategory('Réalisation'));
        $event->addOption('results', array());
        $form = $this->formFactory->create(new TestType(), $event->getArticle());

        $event->addOption('plop', $form->createView());

    }

    public function onCvLoad(CmsEvent $event)
    {
        $event->addOption('skills', array());
    }

    public function onRealisationsLoad(CmsEvent $event)
    {
        $event->addOption('realisations', $this->em->getRepository('VinceCmsBundle:Article')->findPublishedByCategory('Réalisation'));
    }

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }

    public function setFormFactory(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }
}