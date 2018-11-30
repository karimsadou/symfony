<?php

namespace kapla\page\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use kapla\page\Form\ContentType;
use kapla\page\Entity\Content;


class BlocType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add($builder->create('content', ContentType::class, array('by_reference' => $options)))
            ->add('typeBloc', HiddenType::class, array(
            'attr' => array('class' => 'typeBlocClass')))
            ->add('num_order', HiddenType::class)
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
            'data_class' => 'kapla\page\Entity\Bloc',
            'bloc_types' => null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'pagebundle_bloc';
    }


}
