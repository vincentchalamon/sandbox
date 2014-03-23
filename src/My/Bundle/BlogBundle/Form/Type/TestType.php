<?php

namespace My\Bundle\BlogBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Build form
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class TestType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('summary', 'redactor')
                ->add('url', 'masked', array('mask' => '/a*'))
                ->add('categories', 'token', array('entity' => 'VinceCmsBundle:Category'))
                ->add('startedAt', 'datepicker')
                ;
    }

    /**
     * @inheritdoc
     */
    public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'Vince\Bundle\CmsBundle\Entity\Article');
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'test';
    }

}
