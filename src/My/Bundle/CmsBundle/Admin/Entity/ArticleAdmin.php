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

use My\Bundle\CmsBundle\Entity\Article;
use Vince\Bundle\CmsSonataAdminBundle\Admin\Entity\ArticleAdmin as Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Knp\Bundle\SnappyBundle\Snappy\LoggableGenerator;

/**
 * Article admin
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class ArticleAdmin extends Admin
{

    /**
     * Web dir
     *
     * @var string
     */
    protected $webDir;

    /**
     * Set Snappy image
     *
     * @var LoggableGenerator
     */
    protected $snappy;

    /**
     * Set web dir
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param string $webDir
     */
    public function setWebDir($webDir)
    {
        $this->webDir = $webDir;
    }

    /**
     * Set Snappy image
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param LoggableGenerator $snappy
     */
    public function setSnappy(LoggableGenerator $snappy)
    {
        $this->snappy = $snappy;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormTheme()
    {
        return array_merge(array('MyCmsBundle:Admin:form_theme.html.twig'), parent::getFormTheme());
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $mapper)
    {
        parent::configureListFields($mapper);
        $mapper->add('categories', null, array(
                'label' => 'Catégories'
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $mapper)
    {
        parent::configureDatagridFilters($mapper);
        $mapper->add('categories', null, array(
                'label' => 'Catégorie'
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
                        'required' => false
                    )
                )
            ->end();
    }

    /**
     * {@inheritdoc}
     */
    public function postPersist($object)
    {
        parent::postPersist($object);
        /** @var Article $object */
        // Generate screenshot if Article is a realisation
        if ($object->getTemplate()->getSlug() == 'realisation') {
            if (!is_file($this->webDir.$object->getScreenshot())) {
                $this->snappy->generate($object->getContent('url'), $this->webDir.$object->getScreenshot());
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function postUpdate($object)
    {
        parent::postUpdate($object);
        /** @var Article $object */
        // Generate screenshot if Article is a realisation
        if ($object->getTemplate()->getSlug() == 'realisation') {
            if (!is_file($this->webDir.$object->getScreenshot())) {
                $this->snappy->generate($object->getContent('url'), $this->webDir.$object->getScreenshot());
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function postRemove($object)
    {
        parent::postRemove($object);
        /** @var Article $object */
        // Generate screenshot if Article is a realisation
        if ($object->getTemplate()->getSlug() == 'realisation') {
            if (is_file($this->webDir.$object->getScreenshot())) {
                unlink($this->webDir.$object->getScreenshot());
            }
        }
    }
}