<?php

/*
 * This file is part of the Sandbox package.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\CmsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * This bundle provides a blog.
 * This bundle extends VinceCmsBundle.
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class MyCmsBundle extends Bundle
{

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'VinceCmsBundle';
    }
}
