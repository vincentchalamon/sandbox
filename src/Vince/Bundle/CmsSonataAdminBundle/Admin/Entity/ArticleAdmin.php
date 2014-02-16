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
 * Article admin
 *
 * @author Vincent Chalamon <vincent@ylly.fr>
 */
class ArticleAdmin extends Admin
{

    /**
     * {@inheritdoc}
     */
    protected $baseRoutePattern = 'articles';

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
    public function getTemplate($name)
    {
        switch ($name) {
            case 'inner_list_row':
                return 'VinceCmsSonataAdminBundle:Article:list_inner_row.html.twig';
                break;
            case 'edit':
                return 'VinceCmsSonataAdminBundle:Article:edit.html.twig';
                break;
            default:
                return parent::getTemplate($name);
                break;
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->addIdentifier('title')
            ->add('url')
            ->add('publication')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $mapper)
    {
        $mapper
            ->add('title')
            ->add('publication', 'doctrine_orm_callback', array(
                    'callback' => function ($queryBuilder, $alias, $field, $value) {
                            if (!$value) {
                                return;
                            }
                            switch ($value['value']) {
                                case 'Never published':
                                    $queryBuilder->andWhere($queryBuilder->expr()->andX(
                                            $queryBuilder->expr()->isNull(sprintf('%s.startedAt', $alias)),
                                            $queryBuilder->expr()->isNull(sprintf('%s.endedAt', $alias))
                                        ));
                                    break;

                                case 'Published':
                                    $queryBuilder->andWhere($queryBuilder->expr()->andX(
                                            $queryBuilder->expr()->isNull(sprintf('%s.endedAt', $alias)),
                                            $queryBuilder->expr()->isNotNull(sprintf('%s.startedAt', $alias)),
                                            $queryBuilder->expr()->lte(sprintf('%s.startedAt', $alias), ':now')
                                        ))->setParameter('now', new \DateTime());
                                    break;

                                case 'Pre-published':
                                    $queryBuilder->andWhere($queryBuilder->expr()->andX(
                                            $queryBuilder->expr()->isNotNull(sprintf('%s.startedAt', $alias)),
                                            $queryBuilder->expr()->gt(sprintf('%s.startedAt', $alias), ':now')
                                        ))->setParameter('now', new \DateTime());
                                    break;

                                case 'Post-published':
                                    $queryBuilder->andWhere($queryBuilder->expr()->andX(
                                            $queryBuilder->expr()->isNotNull(sprintf('%s.startedAt', $alias)),
                                            $queryBuilder->expr()->lt(sprintf('%s.startedAt', $alias), ':now'),
                                            $queryBuilder->expr()->isNotNull(sprintf('%s.endedAt', $alias)),
                                            $queryBuilder->expr()->lt(sprintf('%s.endedAt', $alias), ':now')
                                        ))->setParameter('now', new \DateTime());
                                    break;

                                case 'Published temp':
                                    $queryBuilder->andWhere($queryBuilder->expr()->andX(
                                            $queryBuilder->expr()->isNotNull(sprintf('%s.startedAt', $alias)),
                                            $queryBuilder->expr()->lte(sprintf('%s.startedAt', $alias), ':now'),
                                            $queryBuilder->expr()->isNotNull(sprintf('%s.endedAt', $alias)),
                                            $queryBuilder->expr()->gte(sprintf('%s.endedAt', $alias), ':now')
                                        ))->setParameter('now', new \DateTime());
                                    break;
                            }
                        }
                ), 'choice', array(
                    'choices' => array(
                        'Never published' => $this->trans('Never published', array(), 'VinceCms'),
                        'Published' => $this->trans('Published', array(), 'VinceCms'),
                        'Pre-published' => $this->trans('Pre-published', array(), 'VinceCms'),
                        'Post-published' => $this->trans('Post-published', array(), 'VinceCms'),
                        'Published temp' => $this->trans('Published temp', array(), 'VinceCms')
                    )
                ));
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $mapper)
    {
        $mapper
            ->with('General')
                ->add('title')
                ->add('summary', null, array('required' => false))
                ->add('tags', null, array('required' => false))
                //->add('categories')
                //->add('metas')
            ;
        if ($this->getSubject()->getSlug() != 'homepage') {
            $mapper
                    ->add('url', null, array('required' => false))
                ->end()
                ->with('Publication')
                    ->add('startedAt', null, array('required' => false))
                    ->add('endedAt', null, array('required' => false))
            ;
        }
        $mapper
            ->end()
            ->with('Template')
                ->add('template', null, array('required' => false))
                //->add('contents')
            ->end()
        ;
    }
}