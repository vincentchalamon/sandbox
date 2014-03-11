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

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vince\Bundle\CmsBundle\Entity\Meta;
use Vince\Bundle\CmsSonataAdminBundle\Form\Transformer\MetaTransformer;

/**
 * MetaGroupType manage grouped meta list
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class MetaGroupType extends AbstractType
{

    /**
     * Object manager
     *
     * @var ObjectManager
     */
    protected $em;

    /**
     * Set object manager
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param ObjectManager $objectManager
     *
     * @return MetaGroupType
     */
    public function setObjectManager(ObjectManager $objectManager)
    {
        $this->em = $objectManager;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer(new MetaTransformer());
        $list   = $this->em->getRepository('VinceCmsBundle:Meta')->findAll();
        $groups = array();
        foreach ($list as $meta) {
            /** @var Meta $meta */
            if (!isset($groups[$meta->getGroup()])) {
                $groups[$meta->getGroup()] = array();
            }
            $groups[$meta->getGroup()][] = $meta;
        }
        foreach ($groups as $name => $metas) {
            $builder->add($name ? preg_replace('/[^\dA-z_\-]+/i', '-', strtolower($name)) : 'general', 'meta', array(
                    'label' => $name,
                    'metas' => $metas
                )
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'metagroup';
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'form';
    }
}