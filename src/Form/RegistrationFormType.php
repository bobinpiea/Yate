<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

// Contraintes utilisées dans ce formulaire
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Champ email (lié à l'entité User)
            ->add('email') 

            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false, // Champ n'existe pas dans la BDD
                'constraints' => [
                    new IsTrue([
                        'message' => "Veuillez accepter les conditions d'utilisation.",
                    ]),
                ],
            ])
            
            
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez entrer un mot de passe.",
                    ]),
                    new Length([
                        'min' => 12,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères.',
                        'max' => 4096, // Limite de Symfony
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).+$/',
                        'message' => 'Le mot de passe doit contenir au moins une minuscule, une majuscule et un caractère spécial.',
                    ]),
                    new NotCompromisedPassword([
                        'message' => 'Ce mot de passe a été compromis dans une fuite de données. Veuillez en choisir un autre.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // formulaire est lié à l'entité User
            'data_class' => User::class,
        ]);
    }
}