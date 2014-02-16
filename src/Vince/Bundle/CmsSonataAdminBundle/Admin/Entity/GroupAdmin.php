<?php

/*
 * This file is part of the VinceCmsSonataAdmin bundle.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Vince\Bundle\CmsSonataAdminBundle\Admin\Entity;

use Sonata\UserBundle\Admin\Entity\GroupAdmin as BaseAdmin;

/**
 * Group admin
 *
 * @author Vincent Chalamon <vincent@ylly.fr>
 */
class GroupAdmin extends BaseAdmin
{

    /**
     * {@inheritdoc}
     */
    protected $baseRoutePattern = 'groupes';
}