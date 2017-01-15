<?php
namespace Vertacoo\LinksDirectoryBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vertacoo\LinksDirectoryBundle\Form\Type\CategorySelectorType;

class LinkFilterType extends AbstractType
{

    /**
     *
     * {@inheritdoc}
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', null, array(
            'label' => 'form.link.name'
        ))->add('categorie', CategorySelectorType::class, array(
            'label' => 'form.link.category',
            'placeholder' => ''
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
            'data_class' => null,
            'attr' => array(
                'novalidate' => 'novalidate'
            ),
            'translation_domain' => 'vertacoo_links_directory',
        ));
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function getBlockPrefix()
    {
        return 'vertacoo_links_linkfilter';
    }
}
