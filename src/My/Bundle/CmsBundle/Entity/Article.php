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
use Vince\Bundle\CmsBundle\Entity\Article as BaseArticle;
use Doctrine\ORM\Mapping as ORM;

/**
 * This entity provides features to manage an Article.
 * An Article is a page with some Areas through a Template.
 * It also have some Metas and additional features (url, publication...).
 *
 * @ORM\Entity
 * @ORM\Table(name="article")
 */
class Article extends BaseArticle
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
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="My\Bundle\CmsBundle\Entity\ArticleMeta", mappedBy="article", cascade={"all"}, orphanRemoval=true)
     */
    protected $metas;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="My\Bundle\CmsBundle\Entity\Content", mappedBy="article", cascade={"all"})
     */
    protected $contents;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="My\Bundle\CmsBundle\Entity\Menu", mappedBy="article", cascade={"persist"})
     */
    protected $menus;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="My\Bundle\CmsBundle\Entity\Category", cascade={"persist"})
     * @ORM\JoinTable(name="article_category")
     */
    protected $categories;

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct();
        $this->categories = new ArrayCollection();
    }

    /**
     * Add category
     *
     * @param Category category
     * @return Article
     */
    public function addCategory(Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param Category $category
     */
    public function removeCategory(Category $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return ArrayCollection
     */
    public function getCategories()
    {
        return $this->categories;
    }
}
