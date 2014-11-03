<?php

/*
 * This file is part of the Sandbox package.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\UserBundle\Admin\Entity;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\UserBundle\Admin\Entity\GroupAdmin as BaseAdmin;

/**
 * Group admin
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class GroupAdmin extends BaseAdmin
{

    /**
     * {@inheritdoc}
     */
    protected $baseRoutePattern = 'groupes';

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $mapper)
    {
        parent::configureListFields($mapper);
        $mapper->get('roles')->setType('array');
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General', array('class' => 'col-md-6'))
                ->add('name')
            ->end()
            ->with('Rôles', array('class' => 'col-md-6'))
                ->add('roles', 'sonata_security_roles', array(
                        'expanded' => true,
                        'multiple' => true,
                        'required' => false,
                        'label' => false
                    )
                )
            ->end()
        ;
    }
}
