<?php

namespace kapla\page\Form;

use Doctrine\ORM\Mapping\Entity;
use kapla\page\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use kapla\page\Form\BlocType;
use Test\Fixture\Document\Image;
use kapla\upload\Form\FilesType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

class PageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('category', EntityType::class, array(
                'class' => 'PageBundle:Category',
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true
            ))
            ->add('Blocs', CollectionType::class, array(
                'label' => false,
                'entry_type'   => BlocType::class,
                'entry_options' => array('bloc_types' => $options["bloc_types"]),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ))
            ->add('upfile', FilesType::class, array(
                'label' => false,
                'attr' => array('class' => 'typeBlocClass')))
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
            'data_class' => 'kapla\page\Entity\Page',
            'bloc_types' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
//    public function getBlockPrefix()
//    {
//        return 'pagebundle_page';
//    }
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('upfile', new Assert\Image(array(
            'mimeTypes' => 'application/pdf'
        )));
    }
}
