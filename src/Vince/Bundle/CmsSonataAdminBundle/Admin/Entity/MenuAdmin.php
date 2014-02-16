<?php

/*
 * This file is part of the VinceCmsSonataAdmin bundle.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Vince\Bundle\CmsSonataAdminBundle\Admin\Entity;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Menu admin
 *
 * @author Vincent Chalamon <vincent@ylly.fr>
 */
class MenuAdmin extends Admin
{

    /**
     * {@inheritdoc}
     */
    protected $baseRoutePattern = 'menus';

    /**
     * {@inheritdoc}
     */
    protected $datagridValues = array(
        '_page'       => 1,
        '_sort_order' => 'ASC',
        '_sort_by'    => 'title'
    );

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->addIdentifier('title')
            ->add('route');
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $mapper)
    {
        $mapper->add('title');
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $mapper)
    {
        $mapper
            ->with('General')
                ->add('parent')
                ->add('title')
                ->add('isImage')
                ->add('path')
            ->end()
            ->with('Publication')
                ->add('startedAt', 'datepicker', array('required' => false))
                ->add('endedAt', 'datepicker', array('required' => false))
            ->end();
        if (!$this->getSubject() || $this->getSubject()->getParent()) {
            $mapper
                ->with('Url')
                    ->add('url', null, array('required' => false))
                    ->add('article', null, array('required' => false))
                    ->add('target', 'choice', array('required' => false, 'choices' => array(
                        '_blank' => 'Nouvelle fenêtre',
                        '_self' => 'Même fenêtre'
                    )))
                ->end();
        }
    }
}