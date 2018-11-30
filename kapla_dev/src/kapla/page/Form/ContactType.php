<?php

namespace kapla\page\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContactType extends AbstractType
{
    /**1
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prenom',TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Prénom*',
                    'class' => 'form-control py-2',
                )
            ))
            ->add('nom',TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Nom*',
                    'class' => 'form-control py-2',
                )
            ))
            ->add('email',EmailType::class, array(
                'attr' => array(
                    'placeholder' => 'Email*',
                    'class' => 'form-control py-2',
                )
            ))

            ->add('telephone',TelType::class, array(
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Téléphone',
                    'class' => 'form-control py-2',
                )
            ))

            ->add('societe',TextType::class, array(
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Société',
                    'class' => 'form-control py-2',
                )
            ))


            ->add('sujet',TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Sujet*',
                    'class' => 'form-control py-2',
                )
            ))

            ->add('message',TextareaType::class,array(
                'attr' => array(
                    'placeholder' => 'Message*',
                    'class' => 'form-control py-2',
                )
            ))

            ->add('save', SubmitType::class, array(
                'attr' => array(
                    'class' => 'btn btn-main w-100 mb-3 mb-md-0',
                ),
                'label' => 'Envoyer',
            ))
            ->getForm();
    }

}