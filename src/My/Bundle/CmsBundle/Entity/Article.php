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

/**
 * This entity provides features to manage an Article.
 * An Article is a page with some Areas through a Template.
 * It also have some Metas and additional features (url, publication...).
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class Article extends BaseArticle
{
    /**
     * @var ArrayCollection
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
