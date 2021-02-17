<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Nom', 'constraints' => [
                    new NotBlank(['message' => 'Ce champs ne peut pas être vide !'])
                ],
                'attr' => [
                    'placeholder' => 'Enter votre nom'
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [
                    new NotBlank(['message' => 'Ce champs ne peut pas être vide !']),
                ],
                'attr' => ['placeholder' => 'Enter votre prénom']
            ])
            ->add('subject', TextType::class, [
                'label' => 'Sujet',
                'constraints' => [
                    new NotBlank(['message' => 'Ce champs ne peut pas être vide !'])
                ],
                'attr' => [
                    'placeholder' => 'Entrer le sujet du message'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [
                    new NotBlank(['message' => 'Ce champs ne peut pas être vide !']),
                ],
                'attr' => [
                    'placeholder' => 'Entrer votre adresse emai']
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Message',
                'constraints' => [
                    new NotBlank(['message' => 'Ce champs ne peut pas être vide !']),
                    new Length([
                        'min' => 6,
                        'max' => 255,
                        'minMessage' => 'Votre nouveau mot de passe doit avoir au moins 6 caractères',
                        'maxMessage' => 'Votre nouveau mot de passe ne doit pas dépasser 255 caractères',
                    ])
                ],
                'attr' => [
                    'placeholder' => 'Entrer votre message'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',

                'attr' => [
                    'class' => 'btn-block btn-success'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
