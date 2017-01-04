<?php
namespace Vertacoo\LinksDirectoryBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

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
    }

    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');
        if (isset($bundles['VichUploaderBundle'])) {
            $vichConfigs = $container->getExtensionConfig('vich_uploader')[0];
            $bundleConfig = $container->getExtensionConfig($this->getAlias())[0];
            
            $config = array(
                'db_driver' => 'orm',
                'mappings' => array(
                    'link_image' => array(
                        'uri_prefix' => $bundleConfig['link_upload_uri'],
                        'upload_destination' => $bundleConfig['link_upload_destination'],
                        'namer'=> 'vich_uploader.namer_origname'
                    )
                )
            );
            
            if (isset($configs['mappings'])) {
                $config['mappings'] = array_merge($vichConfigs['mappings'], $config['mappings']);
            }
            $container->prependExtensionConfig('vich_uploader', $config);

        }
    }
}
