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

use Doctrine\Common\Persistence\ObjectManager;
use My\Bundle\CmsBundle\Entity\ArticleMeta;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Security\Core\SecurityContext;
use Vince\Bundle\CmsBundle\Entity\Article;
use Vince\Bundle\TypeBundle\Listener\LocaleListener;
use Sonata\UserBundle\Entity\BaseUser;

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
     * Object manager
     *
     * @var ObjectManager
     */
    protected $em;

    /**
     * Locale
     *
     * @var string
     */
    protected $locale;

    /**
     * User
     *
     * @var BaseUser
     */
    protected $user;

    /**
     * Set entity manager
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param ObjectManager $em
     */
    public function setObjectManager(ObjectManager $em)
    {
        $this->em = $em;
    }

    /**
     * Set locale
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param LocaleListener $listener
     */
    public function setLocale(LocaleListener $listener)
    {
        $this->locale = $listener->getLocale();
    }

    /**
     * Set user
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param SecurityContext $context
     */
    public function setUser(SecurityContext $context)
    {
        $this->user = $context->getToken() ? $context->getToken()->getUser() : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormTheme()
    {
        return array_merge(parent::getFormTheme(), array('VinceCmsSonataAdminBundle:Form:form_theme.html.twig'));
    }

    /**
     * {@inheritdoc}
     */
    public function getNewInstance()
    {
        /** @var Article $article */
        $article = parent::getNewInstance();
        $meta    = new ArticleMeta();
        $meta->setMeta($this->em->getRepository('VinceCmsBundle:Meta')->findOneByName('language'));
        $meta->setContents($this->locale);
        $article->addMeta($meta);
        $meta = new ArticleMeta();
        $meta->setMeta($this->em->getRepository('VinceCmsBundle:Meta')->findOneByName('og:type'));
        $meta->setContents('article');
        $article->addMeta($meta);
        $meta = new ArticleMeta();
        $meta->setMeta($this->em->getRepository('VinceCmsBundle:Meta')->findOneByName('twitter:card'));
        $meta->setContents('summary');
        $article->addMeta($meta);
        if ($this->user) {
            $meta = new ArticleMeta();
            $meta->setMeta($this->em->getRepository('VinceCmsBundle:Meta')->findOneByName('author'));
            $meta->setContents(trim($this->user->getFirstname().' '.$this->user->getLastname()) ?: $this->user->getUsername());
            $article->addMeta($meta);
            if ($this->user->getGplusName()) {
                $meta = new ArticleMeta();
                $meta->setMeta($this->em->getRepository('VinceCmsBundle:Meta')->findOneByName('publisher'));
                $meta->setContents($this->user->getGplusName());
                $article->addMeta($meta);
            }
            if ($this->user->getTwitterName()) {
                $meta = new ArticleMeta();
                $meta->setMeta($this->em->getRepository('VinceCmsBundle:Meta')->findOneByName('twitter:creator'));
                $meta->setContents('@'.$this->user->getTwitterName());
                $article->addMeta($meta);
            }
        }

        return $article;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->addIdentifier('title', null, array(
                    'label' => 'article.field.title'
                )
            )
            ->add('url', 'url', array(
                    'label' => 'article.field.url'
                )
            )
            ->add('publication', 'trans', array(
                    'label' => 'article.field.publication',
                    'catalogue' => 'VinceCms'
                )
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $mapper)
    {
        $mapper
            ->add('title', null, array(
                    'label' => 'article.field.title'
                )
            )
            ->add('publication', 'doctrine_orm_callback', array(
                    'label' => 'article.field.publication',
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
                )
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $mapper)
    {
        $mapper
            ->with('article.group.general')
                ->add('title', null, array(
                        'label' => 'article.field.title'
                    )
                )
                ->add('summary', null, array(
                        'label' => 'article.field.summary',
                        'required' => false,
                        'help' => 'article.help.summary'
                    )
                )
                ->add('tags', 'list', array(
                        'label'    => 'article.field.tags',
                        'required' => false,
                        'help' => 'article.help.tags'
                    )
                )
            ;
        if ($this->getSubject()->getSlug() != 'homepage') {
            $mapper
                    ->add('url', null, array(
                            'label' => 'article.field.customUrl',
                            'required' => false,
                            'help' => 'article.help.customUrl',
                            'attr' => array(
                                'placeholder' => $this->getSubject()->getRoutePattern()
                            )
                        )
                    )
                ->end()
                ->with('article.group.publication')
                    ->add('startedAt', 'datepicker', array(
                            'label' => 'article.field.startedAt',
                            'required' => false
                        )
                    )
                    ->add('endedAt', 'datepicker', array(
                            'label' => 'article.field.endedAt',
                            'required' => false
                        )
                    )
            ;
        }
        $mapper
            ->end()
            ->with('article.group.metas')
                ->add('metas', 'metagroup', array(
                        'label' => false
                    )
                )
            ->end()
            ->with('article.group.template')
                ->add('template', null, array(
                        'label' => 'article.field.template',
                        'required' => false
                    )
                )
                ->add('contents', 'template', array(
                        'label' => false
                    )
                )
            ->end()
        ;
    }
}