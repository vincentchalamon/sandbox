<?php

/*
 * This file is part of the MyQuote bundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\QuoteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * EmailType manage email content
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class EmailType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('contents', 'redactor', array('attr' => array('placeholder' => 'Contenu')));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('intention' => 'email'));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'mail';
    }
}
 