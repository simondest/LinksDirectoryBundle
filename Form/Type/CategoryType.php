<?php
namespace Vertacoo\LinksDirectoryBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vertacoo\LinksDirectoryBundle\Form\Type\CategorySelectorType;


class CategoryType extends AbstractType
{

    protected $class;

    public function __construct($class)
    {
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', null, array(
            'label' => 'form.category.name'
        ))->add('parent', CategorySelectorType::class, array(
            'required' => false,
            'label' => 'form.category.parent',
        ));
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'translation_domain' => 'vertacoo_links_directory',
            'intention' => 'category',
            'validation_groups' => array('Category')
        ));
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function getBlockPrefix()
    {
        return 'vertacoo_linksdirectorybundle_category';
    }
}
