<?php

/*
 * This file is part of the MyQuote bundle.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\QuoteBundle\DependencyInjection;

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
        $treeBuilder->root('my_quote')
            /*->children()
                ->scalarNode('siret')
                    ->isRequired()
                ->end()
                ->scalarNode('ape')
                    ->isRequired()
                ->end()
                ->arrayNode('google')
                    ->isRequired()
                    ->children()
                        ->scalarNode('applicationName')
                            ->isRequired()
                        ->end()
                        ->scalarNode('developerKey')
                            ->isRequired()
                        ->end()
                    ->end()
                ->end()
            ->end()*/;

        return $treeBuilder;
    }
}
