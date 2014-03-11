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
            $group = $articleMeta->getMeta()->getGroup();
            $group = $group ? preg_replace('/[^\dA-z_\-]+/i', '-', strtolower($group)) : 'general';
            if (!isset($groups[$group])) {
                $groups[$group] = array();
            }
            $groups[$group][preg_replace('/[^\dA-z_\-]+/i', '-', strtolower($articleMeta->getMeta()->getName()))] = $articleMeta->getContents();
        }

        return $groups;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        die('<pre>'.print_r($value, true).'</pre>');
        $value = explode($this->separator, trim($value));
        foreach ($value as $key => $val) {
            if (!trim($val)) {
                unset($value[$key]);
            }
        }

        return $value;
    }
}