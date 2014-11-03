<?php

/*
 * This file is part of the MyCms bundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\CmsBundle\Listener;

use Symfony\Component\Form\FormFactory;
use Vince\Bundle\CmsBundle\Event\CmsEvent;
use My\Bundle\CmsBundle\Form\Type\ContactType;

/**
 * Listen CMS events
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class CmsListener
{

    /**
     * Factory
     *
     * @var FormFactory
     */
    protected $factory;

    /**
     * Set FormFactory
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param FormFactory $formFactory
     */
    public function setFormFactory(FormFactory $formFactory)
    {
        $this->factory = $formFactory;
    }

    /**
     * Load contact page
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param CmsEvent $event
     */
    public function onLoadContact(CmsEvent $event)
    {
        $event->addOption('form', $this->factory->create(new ContactType())->createView());
    }
}
