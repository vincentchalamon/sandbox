<?php

namespace Vince\Bundle\CmsSonataAdminBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class VinceCmsSonataAdminBundle extends Bundle
{

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'SonataUserBundle';
    }
}
