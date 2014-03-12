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
use Vince\Bundle\CmsBundle\Entity\Content;
use Vince\Bundle\CmsBundle\Entity\Template;

/**
 * Contents transformer
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class ContentsTransformer implements DataTransformerInterface
{

    /**
     * Contents class name
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
     * @param string        $class Contents class name
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
        $contents = array();
        foreach ($value as $content) {
            /** @var Content $content */
            if (!isset($contents[$content->getArea()->getTemplate()->getSlug()])) {
                $contents[$content->getArea()->getTemplate()->getSlug()] = array();
            }
            $contents[$content->getArea()->getTemplate()->getSlug()][$content->getArea()->getName()] = $content->getContents();
        }

        return $contents;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        if (!is_array($value)) {
            return array();
        }

        // todo-vince Update Content instead of delete/create
        $results = array();
        foreach ($value as $template => $contents) {
            /** @var Template $template */
            if ($template = $this->em->getRepository('VinceCmsBundle:Template')->findOneBy(array('slug' => $template))) {
                foreach ($contents as $name => $content) {
                    if (trim($content) &&
                        $area = $this->em->getRepository('VinceCmsBundle:Area')->findOneBy(array(
                                'template' => $template->getId(),
                                'name' => $name
                            )
                        )
                    ) {
                        /** @var Content $articleContent */
                        $articleContent = new $this->class();
                        $articleContent->setArea($area);
                        $articleContent->setContents(trim($content));
                        $results[] = $articleContent;
                    }
                }
            }
        }

        return $results;
    }
}