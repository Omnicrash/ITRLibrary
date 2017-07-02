<?php

namespace ITRLibraryBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('itr_library');

        $rootNode
            ->children()
                ->arrayNode('slack')
                    ->children()
                        ->scalarNode('bot_name')
                            ->defaultValue('LibraryBot')
                        ->end()

                        ->scalarNode('default_channel')
                            ->defaultValue('library')
                        ->end()

                        ->arrayNode('channel_tags')
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('channel')->end()
                                    ->arrayNode('tags')
                                        ->prototype('scalar')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()

                        ->arrayNode('cmd')
                            ->useAttributeAsKey('name')
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('token')
                                        ->isRequired()
                                        ->cannotBeEmpty()
                                    ->end()
                                    ->scalarNode('syntax')->end()
                                    ->scalarNode('example')->end()
                                ->end()
                            ->end()
                        ->end()

                    ->end()
                ->end() //slack

            ->end()
        ;

        return $treeBuilder;
    }
}
