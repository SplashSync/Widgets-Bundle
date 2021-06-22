<?php

/*
 *  Copyright (C) 2021 BadPixxel <www.badpixxel.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Splash\Widgets\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('splash_widgets');

        // @phpstan-ignore-next-line
        $rootNode
            ->children()
            ->arrayNode('cache')
            ->children()
            ->booleanNode('enable')->defaultValue(true)->end()
            ->end()
            ->end()
            ->arrayNode('templates')
            ->children()
            ->scalarNode('TextBlock')->defaultValue("SplashWidgetsBundle:Blocks:TextBlock.html.twig")->end()
            ->end()
            ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
