<?php

/*
 * This file is part of the MySkill bundle.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\SkillBundle\Admin\Entity;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Skill admin
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class SkillAdmin extends Admin
{

    /**
     * {@inheritdoc}
     */
    protected $baseRoutePattern = 'competences';

    /**
     * {@inheritdoc}
     */
    public function getFormTheme()
    {
        return array_merge(array('MySkillBundle:Form:form_theme.html.twig'), parent::getFormTheme());
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title', null, array('label' => 'Société'))
            ->add('location', 'string', array('label' => 'Lieu'))
            ->add('startedAt', 'localizeddate', array('label' => 'Du'))
            ->add('endedAt', 'localizeddate', array('label' => 'Au'))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', null, array('label' => 'Société', 'attr' => array('placeholder' => 'Société')))
            ->add('location', null, array('label' => 'Lieu', 'attr' => array('placeholder' => 'Lieu')))
            ->add('startedAt', 'datepicker', array('label' => 'Du', 'attr' => array('placeholder' => 'Du')))
            ->add('endedAt', 'datepicker', array('required' => false, 'label' => 'Au', 'attr' => array('placeholder' => 'Au')))
            ->add('website', null, array('label' => 'Site Internet', 'attr' => array('placeholder' => 'Site Internet')))
            ->add('description', 'redactor', array('label' => 'Description', 'attr' => array('placeholder' => 'Description')))
            ->add('studies', null, array('required' => false, 'label' => 'S\'agit-il d\'une formation ?'))
        ;
    }
}