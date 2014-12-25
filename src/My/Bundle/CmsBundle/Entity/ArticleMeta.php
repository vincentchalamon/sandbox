<?php

/*
 * This file is part of the Sandbox package.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\CmsBundle\Entity;

use Vince\Bundle\CmsBundle\Entity\ArticleMeta as BaseArticleMeta;
use Vince\Bundle\CmsBundle\Entity\Meta;
use Doctrine\ORM\Mapping as ORM;

/**
 * This entity provides features to manage Contents of an Article Meta.
 *
 * @ORM\Entity
 * @ORM\Table(name="article_meta")
 */
class ArticleMeta extends BaseArticleMeta
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Article
     *
     * @ORM\ManyToOne(targetEntity="My\Bundle\CmsBundle\Entity\Article", inversedBy="metas", cascade={"persist"})
     * @ORM\JoinColumn(name="article_id", nullable=false, onDelete="CASCADE")
     */
    protected $article;

    /**
     * @var Meta
     *
     * @ORM\ManyToOne(targetEntity="Vince\Bundle\CmsBundle\Entity\Meta", cascade={"persist"})
     * @ORM\JoinColumn(name="article_id", nullable=false, onDelete="CASCADE")
     */
    protected $meta;
}
