<?php

/*
 * This file is part of the MyCms bundle.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\CmsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Build form
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class ContactType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('label' => 'Votre nom'))
            ->add('email', 'email', array('label' => 'Votre e-mail'))
            ->add('message', 'textarea', array('label' => 'Votre message'))
            ->add('captcha', 'ewz_recaptcha');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'My\Bundle\CmsBundle\Form\Data\Contact',
                'intention'  => 'contact',
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'contact';
    }
}
