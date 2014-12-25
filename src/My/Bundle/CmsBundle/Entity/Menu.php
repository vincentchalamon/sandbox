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

use Doctrine\Common\Collections\ArrayCollection;
use Vince\Bundle\CmsBundle\Entity\Menu as BaseMenu;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * This entity provides features to manage menu
 *
 * @ORM\Entity
 * @ORM\Table(name="menu")
 */
class Menu extends BaseMenu
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
     * @ORM\ManyToOne(targetEntity="My\Bundle\CmsBundle\Entity\Article", inversedBy="menus", cascade={"persist"})
     * @ORM\JoinColumn(name="article_id", nullable=true, onDelete="SET NULL")
     */
    protected $article;

    /**
     * @var Menu
     *
     * @ORM\ManyToOne(targetEntity="My\Bundle\CmsBundle\Entity\Menu", inversedBy="children", cascade={"persist"})
     * @ORM\JoinColumn(name="parent_id", nullable=true, onDelete="CASCADE")
     * @Gedmo\TreeParent
     */
    protected $parent;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="My\Bundle\CmsBundle\Entity\Menu", mappedBy="parent", cascade={"persist"})
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    protected $children;
}
