<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullName', TextType::class,
                [
                    'label' => 'Nom complet (nom, prénom)',
                    'attr' => ['placeholder' => 'Tapez votre nom, prénom']
                ])
            ->add('email', RepeatedType::class,
                [
                    'type' => EmailType::class,
                    'invalid_message' => 'L\'email et la confirmation doivet etre identique',
                    'label' => 'Adresse mail',
                    'required' => true,
                    'first_options' => [
                        'label' => 'Adresse mail',
                        'attr' =>
                            ['placeholder' => 'Enrez votre adresse email']
                    ],

                    'second_options' => [
                        'label' => 'Confirmer votre adresse mail',
                        'attr' =>
                            ['placeholder' => 'Confirmez votre adresse email']
                    ],
                ])
            ->add('password', RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Le mot de passe et la confirmation doivet etre identique',
                    'label' => 'Mot de passe',
                    'required' => true,
                    'first_options' => [
                        'label' => 'Mot de passe',
                        'attr' => [
                            'placeholder' => 'Entrez votre mot de passe'
                        ]
                    ],

                    'second_options' => [
                        'label' => 'Confirmer votre mot de passe',
                        'attr' => [
                            'placeholder' => 'Confirmez votre mot de passe'
                        ]
                    ]
                ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
