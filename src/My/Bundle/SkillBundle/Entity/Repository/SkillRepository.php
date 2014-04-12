<?php

/*
 * This file is part of the MySkill bundle.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\SkillBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * This class provides features to find Skills.
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class SkillRepository extends EntityRepository
{

    /**
     * {@inheritdoc}
     */
    public function findAllOrdered($order, $sort)
    {
        return $this->findBy(array(), array($order => $sort));
    }
}