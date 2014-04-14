<?php

/*
 * This file is part of the MyCms bundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\CmsBundle\DependencyInjection\Compiler;

use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Finder\Finder;

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
        // Override Article admin
        $definition = $container->getDefinition('vince.admin.article');
        $definition->addMethodCall('setWebDir', array($container->getParameter('kernel.web_dir')));
        $definition->addMethodCall('setSnappy', array($container->getDefinition('knp_snappy.image')));

        // Add validations
        if (!$container->hasParameter('validator.mapping.loader.yaml_files_loader.mapping_files')) {
            return;
        }
        $files = $container->getParameter('validator.mapping.loader.yaml_files_loader.mapping_files');
        foreach (Finder::create()->files()->in(__DIR__.'/../../Resources/config/validation') as $file) {
            /** @var \SplFileInfo $file */
            $files[] = $file->__toString();
            $container->addResource(new FileResource($file->__toString()));
        }
        $container->setParameter('validator.mapping.loader.yaml_files_loader.mapping_files', $files);
    }
}