<?php

/*
 * This file is part of the MyQuote bundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\QuoteBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use My\Bundle\QuoteBundle\Form\Transformer\CustomerTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * CustomerFormType manage a Customer
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class CustomerType extends AbstractType
{

    /**
     * Customer repository
     *
     * @var EntityRepository
     */
    protected $repository;

    /**
     * Set Customer repository
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param EntityRepository $repository
     */
    public function setCustomerRepository(EntityRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'hidden')
            ->add('name', null, array('attr' => array('placeholder' => 'Nom')))
            ->add('address', 'textarea', array('attr' => array('placeholder' => 'Adresse')))
            ->add('zipcode', 'masked', array('mask' => 99999, 'attr' => array('placeholder' => 'Code postal')))
            ->add('city', null, array('attr' => array('readonly' => true, 'placeholder' => 'Ville')))
            ->add('email', 'email', array('attr' => array('placeholder' => 'E-mail')))
            ->addModelTransformer(new CustomerTransformer($this->repository))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('intention' => 'customer'));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'customer';
    }
}
 