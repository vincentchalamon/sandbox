<?php

/*
 * This file is part of the MyCms bundle.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\CmsBundle\Entity\Repository;

use Doctrine\DBAL\Types\Type;
use My\Bundle\CmsBundle\Entity\Article;
use My\Bundle\CmsBundle\Entity\Category;
use Vince\Bundle\CmsBundle\Entity\Repository\ArticleRepository as BaseRepository;

/**
 * This class provides features to find Articles.
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class ArticleRepository extends BaseRepository
{

    /**
     * Find an Article from its identifier
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param string $category Category slug
     * @param int    $start    Query start
     * @param null   $limit    Query limit
     *
     * @return array
     */
    public function findAllPublishedByCategory($category, $start = null, $limit = null)
    {
        $builder = $this->createQueryBuilder('a')
                        ->leftJoin('a.metas', 'm')->addSelect('m')
                        ->leftJoin('a.menus', 'me')->addSelect('me')
                        ->leftJoin('a.contents', 'co')->addSelect('co')
                        ->leftJoin('co.area', 'ar')->addSelect('ar')
                        ->leftJoin('a.template', 't')->addSelect('t')
                        ->innerJoin('a.categories', 'c')->addSelect('c')
                        ->where('c.name = :category')
                        ->orderBy('a.startedAt', 'DESC');
        $builder->andWhere(
            $builder->expr()->andX(
                $builder->expr()->isNotNull('a.startedAt'),
                $builder->expr()->lte('a.startedAt', ':now'),
                $builder->expr()->orX(
                    $builder->expr()->isNull('a.endedAt'),
                    $builder->expr()->gte('a.endedAt', ':now')
                )
            )
        )->setParameter('now', new \DateTime(), Type::DATETIME)
         ->setParameter('category', $category)
         ->setFirstResult($start)
         ->setMaxResults($limit);

        return $builder->getQuery()->getResult();
    }

    /**
     * Get siblings articles by categories
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param Article $article
     *
     * @return array
     */
    public function findSiblings(Article $article)
    {
        // todo-vince Refactor this method: use tags instead of categories
        if (!$article->getCategories()->count()) {
            return array();
        }

        $builder = $this->createQueryBuilder('a')
                        ->leftJoin('a.metas', 'm')->addSelect('m')
                        ->leftJoin('a.menus', 'me')->addSelect('me')
                        ->leftJoin('a.contents', 'co')->addSelect('co')
                        ->leftJoin('a.template', 't')->addSelect('t')
                        ->innerJoin('a.categories', 'c')->addSelect('c')
                        ->orderBy('a.startedAt', 'DESC');
        $builder->where(
            $builder->expr()->andX(
                $builder->expr()->isNotNull('a.startedAt'),
                $builder->expr()->lte('a.startedAt', ':now'),
                $builder->expr()->orX(
                    $builder->expr()->isNull('a.endedAt'),
                    $builder->expr()->gte('a.endedAt', ':now')
                )
            )
        )->andWhere(
            $builder->expr()->in('c.name', $article->getCategories()->map(function (Category $category) {
                    return $category->getName();
                })->toArray())
        )->setParameter('now', new \DateTime());

        return $builder->getQuery()->getResult();
    }
}