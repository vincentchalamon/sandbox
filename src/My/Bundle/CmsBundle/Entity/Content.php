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

use Vince\Bundle\CmsBundle\Entity\Area;
use Vince\Bundle\CmsBundle\Entity\Content as BaseContent;
use Doctrine\ORM\Mapping as ORM;

/**
 * This entity provides features to manage contents
 *
 * @ORM\Entity
 * @ORM\Table(name="content")
 */
class Content extends BaseContent
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
     * @ORM\ManyToOne(targetEntity="My\Bundle\CmsBundle\Entity\Article", inversedBy="contents", cascade={"persist"})
     * @ORM\JoinColumn(name="article_id", nullable=false, onDelete="CASCADE")
     */
    protected $article;

    /**
     * @var Area
     *
     * @ORM\ManyToOne(targetEntity="Vince\Bundle\CmsBundle\Entity\Area", cascade={"persist"})
     * @ORM\JoinColumn(name="area_id", nullable=false, onDelete="CASCADE")
     */
    protected $area;
}
