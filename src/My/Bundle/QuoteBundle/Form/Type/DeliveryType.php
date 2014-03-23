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
 * DeliveryType manage a Delivery
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class DeliveryType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contents', null, array('attr' => array('placeholder' => 'Description')))
            ->add('price', null, array('attr' => array('placeholder' => 'Montant')))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'My\Bundle\QuoteBundle\Entity\Delivery',
                'intention'  => 'delivery'
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'delivery';
    }
}
 