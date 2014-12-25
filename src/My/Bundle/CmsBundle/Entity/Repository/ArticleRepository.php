<?php

namespace My\Bundle\CmsBundle\Entity\Repository;

use Doctrine\ORM\Query\Expr\Join;
use Vince\Bundle\CmsBundle\Entity\Repository\ArticleRepository as BaseRepository;

/**
 * This class provides features to find Articles.
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class ArticleRepository extends BaseRepository
{
    /**
     * Get all published Articles ordered by start publication date DESC and filtered by category
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     * @param  string $category Category name
     * @return array
     */
    public function findAllPublishedByCategory($category)
    {
        $builder = $this->createQueryBuilder('a')->orderBy('a.startedAt', 'DESC')
            ->leftJoin('a.categories', 'categories')->addSelect('categories');

        return $builder->where(
            $builder->expr()->andX(
                $builder->expr()->isNotNull('a.startedAt'),
                $builder->expr()->lte('a.startedAt', ':now'),
                $builder->expr()->orX(
                    $builder->expr()->isNull('a.endedAt'),
                    $builder->expr()->gte('a.endedAt', ':now')
                )
            )
        )->andWhere('categories.name = :category')->setParameters(array(
            'now' => new \DateTime(),
            'category' => $category,
        ))->getQuery()->getResult();
    }
}
