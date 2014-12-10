<?php

/*
 * This file is part of the Sandbox package.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
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
        $r       = new \ReflectionObject($this);
        $dirname = str_replace('\\', '/', dirname($r->getFileName())).'/../../Resources/config/validation';
        foreach (Finder::create()->files()->in($dirname) as $file) {
            $this->addValidationFile($file, $container);
        }
    }

    /**
     * Load validation file
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     * @param \SplFileInfo $file
     * @param ContainerBuilder $container
     */
    protected function addValidationFile(\SplFileInfo $file, ContainerBuilder $container)
    {
        if ($container->hasDefinition('validator.builder')) {
            // Symfony 2.5+
            $container->getDefinition('validator.builder')
                      ->addMethodCall('addYamlMapping', array($file->__toString()));

            return;
        }

        // Old method of loading validation
        if (!$container->hasParameter('validator.mapping.loader.yaml_files_loader.mapping_files')) {
            return;
        }
        $files = $container->getParameter('validator.mapping.loader.yaml_files_loader.mapping_files');
        $files[] = realpath($file->__toString());
        $container->addResource(new FileResource($file->__toString()));
        $container->setParameter('validator.mapping.loader.yaml_files_loader.mapping_files', $files);
    }
}
