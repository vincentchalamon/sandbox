<?php

/*
 * This file is part of the VinceCmsSonataAdmin bundle.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Vince\Bundle\CmsSonataAdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Vince\Bundle\CmsBundle\Entity\Meta;

/**
 * MetaType manage meta list for a specific group
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class MetaType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $metas = array();
        foreach ($options['metas'] as $meta) {
            /** @var Meta $meta */
            $name = preg_replace('/[^\dA-z_\-]+/i', '-', strtolower($meta->getName()));
            $builder->add($name, $meta->getType(), array(
                    'label'    => $meta->getTitle(),
                    'required' => false
                )
            );
            $metas[$name] = $meta->getTitle();
        }
        $builder->add('select', 'choice', array('choices' => $metas));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array('metas'));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'meta';
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'form';
    }
}