<?php

/*
 * This file is part of the VinceCmsBundle.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\BlogBundle;

use My\Bundle\BlogBundle\Form\Type\TestType;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Vince\Bundle\CmsBundle\Component\Processor\Processor;

/**
 * Description of ContactProcessor.php
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class TestProcessor extends Processor
{

    protected $mailer, $templating, $configuration, $translator, $session;

    public function process(Request $request)
    {
        $form = $this->createForm(new TestType(), $this->options['article']);
        $form->submit($request);
        
        return $form->isValid() ? true : $form;
    }

    public function setSession($session)
    {
        $this->session = $session;
    }

    public function setMailer($mailer)
    {
        $this->mailer = $mailer;
    }

    public function setTemplating($templating)
    {
        $this->templating = $templating;
    }

    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;
    }

    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
}