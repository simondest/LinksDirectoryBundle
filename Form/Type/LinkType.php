<?php
namespace Vertacoo\LinksDirectoryBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Vertacoo\LinksDirectoryBundle\Form\Type\CategorySelectorType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class LinkType extends AbstractType
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
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name',null, array(
            'label' => 'form.link.name'
        ))
            ->add('description',null, array(
            'label' => 'form.link.description',
            'required' => false
        ))
            ->add('url', UrlType::class, array(
            'label' => 'form.link.url'
        ))
            ->add('imageFile', VichImageType::class, array(
            'label' => 'form.link.image',
            'required' => false
        ))
            ->add('categorie', CategorySelectorType::class, array(
            'label' => 'form.link.category'
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
            'attr'=>array('novalidate'=>'novalidate'),
            'translation_domain' => 'vertacoo_links_directory',
            'validation_groups' => array(
                'Link'
            )
        ));
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function getBlockPrefix()
    {
        return 'vertacoo_linksdirectorybundle_link';
    }
}
