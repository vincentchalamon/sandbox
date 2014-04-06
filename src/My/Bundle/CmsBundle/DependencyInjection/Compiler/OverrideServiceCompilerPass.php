<?php

/*
 * This file is part of the MyCms bundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\CmsBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Override VinceCmsSonataAdmin bundle configuration
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class OverrideServiceCompilerPass implements CompilerPassInterface
{

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('vince.admin.article');
        $definition->addMethodCall('setWebDir', array($container->getParameter('kernel.web_dir')));
        $definition->addMethodCall('setSnappy', array($container->getDefinition('knp_snappy.image')));
    }
}