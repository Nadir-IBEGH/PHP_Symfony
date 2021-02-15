<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'disabled' => true,
                'label' => 'Mon adresse email'
            ])
            ->add('fullName', TextType::class, [
                'disabled' => true,
                'label' => 'Mon prénom'
            ])
            ->add('old_password', PasswordType::class, [
                'label' => 'Mon mot de passe actuel',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Veuillez saisir votre mot de passe actuel'
                ]
            ])
            ->add('new_password', RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'mapped' => false,
                    'constraints' => [
                        new NotBlank(['message'=>'Le mot de passe ne peut pas être vide !']),
                        new Length([
                            'min' => 6,
                            'max' => 30,
                            'minMessage' => 'Votre nouveau mot de passe doit avoir au moins 6 caractères',
                            'maxMessage' => 'Votre nouveau mot de passe ne doit pas dépasser 30 caractères',
                        ])
                    ],
                    'invalid_message' => 'Le mot de passe et la confirmation doivet etre identique',
                    'label' => 'Mon nouveau de passe',
                    'required' => true,
                    'first_options' =>
                        ['label' => 'Mon nouveau de passe',
                            'attr' => [
                                'placeholder' => 'Merci de saisir votre nouveau mot de passe'
                            ]
                        ],
                    'second_options' =>
                        ['label' => 'Confirmer votre nouveau mot de passe',
                            'attr' => [
                                'placeholder' => 'Merci de confirmer votre nouveau mot de passe'
                            ]
                        ],
                ])
            ->add('submit', SubmitType::class, [
                'label' => 'Mettre à jour'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
