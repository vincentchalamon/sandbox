<?php

/*
 * This file is part of the MyCms bundle.
 *
 * (c) Vincent Chalamon <vincent@ylly.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\CmsBundle\Admin\Entity;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Category admin
 *
 * @author Vincent Chalamon <vincent@ylly.fr>
 */
class CategoryAdmin extends Admin
{
    /**
     * {@inheritdoc}
     */
    protected $baseRoutePattern = 'categories';

    /**
     * {@inheritdoc}
     */
    protected $datagridValues = array(
        '_page'       => 1,
        '_sort_order' => 'ASC',
        '_sort_by'    => 'name',
    );

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $mapper)
    {
        $mapper->addIdentifier('name', null, array('label' => 'Nom'))
               ->add('_action', 'actions', array(
                       'actions' => array(
                           'edit' => array(),
                           'delete' => array(),
                       ),
                   )
               );
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $mapper)
    {
        $mapper
            ->with('Général')
                ->add('name', null, array(
                        'label' => 'Nom',
                    )
                )
            ->end()
        ;
    }
}
