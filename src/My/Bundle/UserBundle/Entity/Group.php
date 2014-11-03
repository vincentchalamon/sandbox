<?php

/*
 * This file is part of the Sandbox package.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\UserBundle\Entity;

use Sonata\UserBundle\Entity\BaseGroup;

/**
 * Group
 */
class Group extends BaseGroup
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * {@inheritdoc}
     */
    public function __construct($name = null, $roles = array())
    {
        parent::__construct($name, $roles);
    }
}
