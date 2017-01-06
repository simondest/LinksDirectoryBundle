<?php
namespace Vertacoo\LinksDirectoryBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{

    /**
     *
     * {@inheritdoc}
     *
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('vertacoo_links_directory');
        
        $rootNode->children()
            
            ->scalarNode('category_entity')
            ->isRequired()
            ->cannotBeEmpty()
            ->end()
            ->scalarNode('link_entity')
            ->isRequired()
            ->cannotBeEmpty()
            ->end()
            ->scalarNode('link_upload_uri')
            ->isRequired()
            ->cannotBeEmpty()
            ->end()
            ->scalarNode('link_upload_destination')
            ->isRequired()
            ->cannotBeEmpty()
            ->end()
            ->scalarNode('max_categories_per_page')
            ->cannotBeEmpty()
            ->defaultValue(10)
            ->end()
            ->scalarNode('max_links_per_page')
            ->cannotBeEmpty()
            ->defaultValue(10)
            ->end()
            
            ->end();
        
        return $treeBuilder;
    }
}
