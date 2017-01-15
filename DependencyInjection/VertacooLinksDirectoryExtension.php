<?php
namespace Vertacoo\LinksDirectoryBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class VertacooLinksDirectoryExtension extends Extension implements PrependExtensionInterface
{

    /**
     *
     * {@inheritdoc}
     *
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');
        
        $container->setParameter('vertacoo_links_directory.category.entity', $config['category_entity']);
        $container->setParameter('vertacoo_links_directory.link.entity', $config['link_entity']);
        $container->setParameter('vertacoo_links_directory.max_categories_per_page', $config['max_categories_per_page']);
        $container->setParameter('vertacoo_links_directory.max_links_per_page', $config['max_links_per_page']);
    }

    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');
        $bundleConfig = $container->getExtensionConfig($this->getAlias())[0];
        
        if (isset($bundles['VichUploaderBundle'])) {
            $vichConfigs = $container->getExtensionConfig('vich_uploader')[0];
            $config = array(
                'db_driver' => 'orm',
                'mappings' => array(
                    'link_image' => array(
                        'uri_prefix' => $bundleConfig['link_upload_uri'],
                        'upload_destination' => $bundleConfig['link_upload_destination'],
                        'namer' => 'vich_uploader.namer_origname'
                    )
                )
            );
            
            if (isset($vichConfigs['mappings'])) {
                $config['mappings'] = array_merge($vichConfigs['mappings'], $config['mappings']);
            }
            $container->prependExtensionConfig('vich_uploader', $config);
        } else {
            throw new Exception('VichUploaderBundle must be installed');
        }
        
        if (isset($bundles['StofDoctrineExtensionsBundle'])) {
            $stofDoctrineConfig = $container->getExtensionConfig('stof_doctrine_extensions')[0];
            $config = array(
                'orm' => array(
                    'default' => array(
                        'tree' => true,
                        'sluggable' => true,
                        'sortable' => true,
                        'timestampable' => true
                    )
                )
            );
            if (isset($stofDoctrineConfig['orm']['default'])) {
                $config['orm']['default'] = array_merge($stofDoctrineConfig['orm']['default'], $config['orm']['default']);
            }
            $container->prependExtensionConfig('stof_doctrine_extensions', $config);
        } else {
            
            throw new Exception('StofDoctrineExtensionsBundle must be installed');
        }
        
        $doctrineConfig = $container->getExtensionConfig('doctrine')[0];
        $config = array(
            'orm' => array(
                'resolve_target_entities' => array(
                    'Vertacoo\LinksDirectoryBundle\Model\CategoryInterface' => $bundleConfig['category_entity'],
                    'Vertacoo\LinksDirectoryBundle\Model\LinkInterface' => $bundleConfig['link_entity']
                )
            )
        );
        if(isset($doctrineConfig['orm']['resolve_target_entities'])){
            $config['orm']['resolve_target_entities'] = array_merge($doctrineConfig['orm']['resolve_target_entities'],$config['orm']['resolve_target_entities']);
        }
        $container->prependExtensionConfig('doctrine', $config);
    }
}
