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

use Vince\Bundle\CmsBundle\Entity\Block as BaseBlock;
use Doctrine\ORM\Mapping as ORM;

/**
 * Block
 *
 * @ORM\Entity
 * @ORM\Table(name="block")
 */
class Block extends BaseBlock
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
}
