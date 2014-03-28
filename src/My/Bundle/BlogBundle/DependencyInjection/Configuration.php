<?php

namespace My\Bundle\BlogBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('my_blog')->children()
                                ->arrayNode('urssaf')->isRequired()->children()
                                    ->scalarNode('siret')->isRequired()->end()
                                    ->scalarNode('ape')->isRequired()->end()
                                ->end();

        return $treeBuilder;
    }
}