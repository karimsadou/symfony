<?php

namespace kapla\gallery\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GalleryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('theme', ChoiceType::class, array(
                'choices'        => $options['gallery_theme'],
                'multiple'       => false
            ))
            ->add('images', CollectionType::class, array(
                'label' => false,
                'entry_options' => array('label' => false),
                'entry_type'   => ImgGalleryType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ))
            ->add('edit', SubmitType::class, array(
                'label' => 'Valider',
                'attr' => array('class' => 'btn btn-success'),
            ) )
            ->add('editPreview', SubmitType::class, array(
                'label' => "Valider l'apercu",
                'attr' => array('class' => 'btn btn-success'),
            ))
            ->add('retour', SubmitType::class, array(
                'label' => "Retour au formulaire ",
                'attr' => array('class' => 'btn btn-success'),
            ))

            ->add('updatePreview', SubmitType::class, array(
                'label' => 'Mise à jour',
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
            'data_class' => 'kapla\gallery\Entity\Gallery',
            'gallery_theme' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gallerybundle_gallery';
    }
}
