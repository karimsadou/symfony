<?php

namespace kapla\page\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use kapla\page\Form\CategoryType;
use kapla\page\Entity;


class CategoryListType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categories', CollectionType::class, array(
                'label' => false,
                'entry_options' => array('label' => false),
                'entry_type'   => CategoryType::class,
                'allow_add' => false,
                'allow_delete' => true,
                'by_reference' => false
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Valider',
                'attr' => array('class' => 'btn btn-success'),
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'kapla\page\Entity\CategoryList'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'pagebundle_category';
    }


}
