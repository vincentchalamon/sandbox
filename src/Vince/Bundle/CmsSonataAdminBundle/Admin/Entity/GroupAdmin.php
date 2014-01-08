<?php

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
     * Route pattern
     *
     * @var string
     */
    protected $baseRoutePattern = 'groupes';
}