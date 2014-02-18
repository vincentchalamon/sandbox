<?php

/*
 * This file is part of the Sandbox package.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\CmsBundle\Admin\Entity;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\UserBundle\Admin\Entity\UserAdmin as BaseAdmin;

/**
 * User admin
 *
 * @author Vincent Chalamon <vincent@ylly.fr>
 */
class UserAdmin extends BaseAdmin
{

    /**
     * {@inheritdoc}
     */
    protected $baseRoutePattern = 'utilisateurs';

    /**
     * {@inheritdoc}
     */
    public function getTemplate($name)
    {
        switch ($name) {
            case 'base_show_field':
                return 'SonataAdminBundle:CRUD:base_show_field.html.twig';
            default:
                return parent::getTemplate($name);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);
        $listMapper->remove('createdAt')
                   ->add('createdAt', 'localizeddate')
                   ->reorder(array('username', 'email', 'groups', 'enabled', 'locked', 'createdAt'));
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $filterMapper)
    {
        parent::configureDatagridFilters($filterMapper);
        $filterMapper->remove('id');
    }

    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        parent::configureShowFields($showMapper);
        $showMapper->remove('dateOfBirth')
                   ->with('Profile')
                       ->add('dateOfBirth', 'localizeddate')
                   ->end();
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
        $formMapper
            ->with('General')
                ->add('plainPassword', 'repeated', array(
                        'type' => 'password',
                        'translation_domain' => 'SonataAdminBundle',
                        'required' => !$this->getSubject() || is_null($this->getSubject()->getId()),
                        'first_options' => array(
                            'label' => 'Password'
                        ),
                        'second_options' => array(
                            'label' => 'Confirmation'
                        )
                    )
                )
            ->end()
            ->with('Profile')
                ->add('dateOfBirth', 'datepicker')
                ->add('gender', 'sonata_user_gender', array(
                        'required' => true,
                        'expanded' => true,
                        'translation_domain' => $this->getTranslationDomain()
                    )
                )
                ->add('phone', 'masked', array(
                        'required' => false,
                        'mask' => '09 99 99 99 99'
                    )
                )
            ->end()
        ;
    }
}