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

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\UserBundle\Admin\Entity\UserAdmin as BaseAdmin;

/**
 * User admin
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
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
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('show');
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate($name)
    {
        return $name == 'base_show_field' ? 'SonataAdminBundle:CRUD:base_show_field.html.twig' : parent::getTemplate($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);
        $listMapper->remove('username')->remove('createdAt')->remove('impersonating')->remove('locked')
                   ->addIdentifier('fullname', null, array('label' => 'Nom'))
                   ->reorder(array('fullname', 'email', 'groups'));
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
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General', array('class' => 'col-md-6'))
                ->add('email')
                ->add('plainPassword', 'repeated', array(
                        'type' => 'password',
                        'translation_domain' => 'SonataAdminBundle',
                        'required' => !$this->getSubject() || is_null($this->getSubject()->getId()),
                        'first_options' => array(
                            'label' => 'Mot de passe',
                        ),
                        'second_options' => array(
                            'label' => 'Confirmation du mot de passe',
                        ),
                    )
                )
                ->add('enabled', null, array('required' => false))
                ->add('roles', 'choice', array(
                        'label'    => 'Rôles',
                        'expanded' => true,
                        'multiple' => true,
                        'required' => false,
                        'choices'  => array('ROLE_MEMBER' => 'Membre', 'ROLE_ADMIN' => 'Administrateur'),
                    )
                )
            ->end()
            ->with('Profile', array('class' => 'col-md-6'))
                ->add('gender', 'choice', array(
                        'choices'  => array('m' => 'M.', 'f' => 'Mme'),
                        'required' => true,
                        'expanded' => true,
                    )
                )
                ->add('firstname')
                ->add('lastname')
                ->add('phone', 'masked', array('mask' => '+33 (0)9 99 99 99 99'))
                ->add('address')
                ->add('zipcode', 'masked', array('mask' => 99999))
                ->add('city')
            ->end()
        ;
    }
}
