<?php
namespace Vertacoo\LinksDirectoryBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Vertacoo\LinksDirectoryBundle\Model as Model;
class VertacooLinksDirectoryBundle extends Bundle
{

    /**
     *
     * {@inheritdoc}
     *
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        
        $this->addRegisterMappingsPass($container);
    }

    private function addRegisterMappingsPass(ContainerBuilder $container)
    {
        $modelDir = realpath(__DIR__.'/Resources/config/doctrine/model');
        $mappings = array(
            $modelDir => Model::class,
        );
        
        
        $container->addCompilerPass(DoctrineOrmMappingsPass::createXmlMappingDriver($mappings, array(
            'Vertacoo\LinksDirectoryBundle\Doctrine\CategoryManager'
        ),null,array('VertacooLinksDirectoryBundle'=>Model::class)));
        
    }
}
