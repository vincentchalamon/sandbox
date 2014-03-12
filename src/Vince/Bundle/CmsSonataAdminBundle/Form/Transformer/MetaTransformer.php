<?php

/*
 * This file is part of the VinceCmsSonataAdmin bundle.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Vince\Bundle\CmsSonataAdminBundle\Form\Transformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Vince\Bundle\CmsBundle\Entity\ArticleMeta;

/**
 * Meta transformer
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class MetaTransformer implements DataTransformerInterface
{

    /**
     * ArticleMeta class name
     *
     * @var string
     */
    protected $class;

    /**
     * Object manager
     *
     * @var ObjectManager
     */
    protected $em;

    /**
     * @param ObjectManager $em    ObjectManager
     * @param string        $class ArticleMeta class name
     */
    public function __construct(ObjectManager $em, $class)
    {
        $this->em    = $em;
        $this->class = $class;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        // Fix for Symfony 2.4
        if (null === $value) {
            return null;
        }

        /** @var \Traversable $values */
        if (!is_array($value) && !$value instanceof \Traversable) {
            throw new \InvalidArgumentException(sprintf('Meta form type only accept array or \Traversable objects, %s sent.', is_object($value) ? 'instance of '.get_class($value) : gettype($value)));
        }

        // Build array values for view
        $groups = array();
        foreach ($value as $articleMeta) {
            /** @var ArticleMeta $articleMeta */
            $group = $articleMeta->getMeta()->getGroup() ?: 'general';
            if (!isset($groups[$group])) {
                $groups[$group] = array();
            }
            $groups[$group][$articleMeta->getMeta()->getName()] = $articleMeta->getContents();
        }

        return $groups;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        if (!is_array($value)) {
            return array();
        }

        // todo-vince Update ArticleMeta instead of delete/create
        $results = array();
        foreach ($value as $group => $metas) {
            foreach ($metas as $name => $contents) {
                if (trim($contents) &&
                    $meta = $this->em->getRepository('VinceCmsBundle:Meta')->findOneBy(array(
                            'name' => $name,
                            'group' => $group == 'general' ? null : $group
                        )
                    )
                ) {
                    /** @var ArticleMeta $articleMeta */
                    $articleMeta = new $this->class();
                    $articleMeta->setMeta($meta);
                    $articleMeta->setContents(trim($contents));
                    $results[] = $articleMeta;
                }
            }
        }

        return $results;
    }
}