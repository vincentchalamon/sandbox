<?php

/*
 * This file is part of the MyCms bundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\CmsBundle\Tests\Entity;

use My\Bundle\CmsBundle\Entity\Article;
use Doctrine\Common\Collections\ArrayCollection;
use My\Bundle\CmsBundle\Entity\Category;
use My\Bundle\CmsBundle\Entity\Content;
use Vince\Bundle\CmsBundle\Entity\Area;
use Vince\Bundle\CmsBundle\Entity\Template;

/**
 * Test Article
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class ArticleTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test Article
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     */
    public function testContactValidation()
    {
        // Test categories
        $category = new Category();
        $article  = new Article();
        $this->assertEquals(new ArrayCollection(), $article->getCategories());
        $article->addCategory($category);
        $this->assertEquals(new ArrayCollection(array($category)), $article->getCategories());
        $article->removeCategory($category);
        $this->assertEquals(new ArrayCollection(), $article->getCategories());

        // Test screenshot
        $template = new Template();
        $template->setSlug('test');
        $article->setTemplate($template);
        $area = new Area();
        $area->setName('url');
        $area->setTemplate($template);
        $content = new Content();
        $content->setArea($area);
        $content->setContents('http://www.google.fr');
        $article->addContent($content);
        $this->assertEquals('/uploads/screenshots/http-www-google-fr.jpg', $article->getScreenshot());
        $content->setContents('http://www.google.fr/');
        $this->assertEquals('/uploads/screenshots/http-www-google-fr.jpg', $article->getScreenshot());
    }
}