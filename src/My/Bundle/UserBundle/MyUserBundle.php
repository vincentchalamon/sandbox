<?php

namespace My\Bundle\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MyUserBundle extends Bundle
{

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
