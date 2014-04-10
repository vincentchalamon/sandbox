<?php

/*
 * This file is part of the MyCms bundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\CmsBundle\Listener;

use Doctrine\ORM\EntityRepository;
use My\Bundle\CmsBundle\Entity\Repository\ArticleRepository;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Vince\Bundle\CmsBundle\Event\CmsEvent;
use My\Bundle\CmsBundle\Form\Type\ContactType;

/**
 * Listen CMS events
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class CmsListener
{

    /**
     * Article repository
     *
     * @var ArticleRepository
     */
    protected $articleRepository;

    /**
     * Skill repository
     *
     * @var EntityRepository
     */
    protected $skillRepository;

    /**
     * Factory
     *
     * @var FormFactory
     */
    protected $factory;

    /**
     * Set Article repository
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param ArticleRepository $repository
     */
    public function setArticleRepository(ArticleRepository $repository)
    {
        $this->articleRepository = $repository;
    }

    /**
     * Set Skill repository
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param EntityRepository $repository
     */
    public function setSkillRepository(EntityRepository $repository)
    {
        $this->skillRepository = $repository;
    }

    /**
     * Set FormFactory
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param FormFactory $formFactory
     */
    public function setFormFactory(FormFactory $formFactory)
    {
        $this->factory = $formFactory;
    }

    /**
     * Load homepage
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param CmsEvent $event
     */
    public function onLoadHomepage(CmsEvent $event)
    {
        $event->addOption('realisations', $this->articleRepository->findAllPublishedByCategory('Réalisation'));
        $event->addOption('billets', $this->articleRepository->findAllPublishedByCategory('Accueil', 0, 10));
    }

    /**
     * Load realisations
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param CmsEvent $event
     */
    public function onLoadRealisations(CmsEvent $event)
    {
        $event->addOption('realisations', $this->articleRepository->findAllPublishedByCategory('Réalisation'));
    }

    /**
     * Load cv
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param CmsEvent $event
     */
    public function onLoadCV(CmsEvent $event)
    {
        $event->addOption('skills', $this->skillRepository->findAll());
    }

    /**
     * Load page
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param CmsEvent $event
     */
    public function onLoad(CmsEvent $event)
    {
        $event->addOption('contactForm', $this->factory->create(new ContactType())->createView());
        //$event->addOption('siblings', $this->articleRepository->findSiblings($event->getArticle()));
        $event->addOption('siblings', array());
    }

    /**
     * Replace images by lazy
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param FilterResponseEvent $event
     */
    public function onLoadResponse(FilterResponseEvent $event)
    {
        if (preg_match_all('/<img([^>]+)src="(\/(?:media|app_dev\.php|uploads)\/[^"]+)"([^>]+)?>/i', $event->getResponse()->getContent(), $matches)) {
            $content = $event->getResponse()->getContent();
            foreach ($matches[0] as $key => $image) {
                $filename = '/var/www/blog/web'.str_ireplace('app_dev.php/', '', $matches[2][$key]);
                if (!is_file($filename)) {
                    continue;
                }
                list($width, $height) = getimagesize($filename);
                if ($width <= 100 || $height <= 100) {
                    continue;
                }
                $lazy = str_ireplace('<img', '<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="', str_ireplace('src=', 'data-original=', $image));
                if (strstr($image, ' class="')) {
                    $lazy = str_ireplace(' class="', ' class="lazy ', $lazy);
                } else {
                    $lazy = str_ireplace('<img', '<img class="lazy"', $lazy);
                }
                $content = str_replace($image, sprintf('<span class="loading" style="width:%spx;height:%spx;">%s</span>', $width, $height, $lazy), $content);
            }
            $content = str_ireplace('</body>', '<script type="text/javascript">$(function() {$(\'body\').trigger(\'lazy\');});</script></body>', $content);
            $event->getResponse()->setContent($content);
        }
    }
}