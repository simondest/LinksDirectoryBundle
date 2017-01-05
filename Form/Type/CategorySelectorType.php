<?php
namespace Vertacoo\LinksDirectoryBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class CategorySelectorType extends AbstractType
{

    protected $class;

    public function __construct($class)
    {
        $this->class = $class;
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'class' => $this->class,
            'translation_domain' => 'vertacoo_links_directory',
            'choice_label' => 'indentedName',
            'query_builder' => function (NestedTreeRepository $er) {
                return $er->getChildrenQueryBuilder();
            }
        ));
    }

    public function getParent()
    {
        return EntityType::class;
    }

   
}

