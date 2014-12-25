<?php

/*
 * This file is part of the MyCms bundle.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\CmsBundle\Admin\Entity;

use Vince\Bundle\CmsSonataAdminBundle\Admin\Entity\ArticleAdmin as Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Article admin
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class ArticleAdmin extends Admin
{

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $mapper)
    {
        parent::configureListFields($mapper);
        $mapper->add('categories', null, array(
                'label' => 'Catégories',
            )
        )->reorder(array('title', 'publication', 'categories', '_action'));
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $mapper)
    {
        parent::configureDatagridFilters($mapper);
        $mapper->add('categories', null, array(
                'label' => 'Catégorie',
            )
        )->add('title', null, array(
                'label' => 'Titre',
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $mapper)
    {
        parent::configureFormFields($mapper);
        $mapper
            ->with('article.group.general')
                ->add('categories', null, array(
                        'label' => 'Catégories',
                        'required' => false,
                    )
                )
            ->end();
    }
}
