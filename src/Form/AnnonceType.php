<?php

namespace App\Form;

use App\Entity\Annonce;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\GreaterThan;

class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    { // Champs Commun à toutes les annonces 
        $builder

            ->add('titre', TextType::class, [
                'label' => 'Titre de l’annonce',
                'constraints' => [
                    new NotBlank(['message' => 'Le titre est obligatoire.']),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Le titre doit faire au moins {{ limit }} caractères.',
                    ]),
                ],
            ])

        
            ->add('description', TextareaType::class, [
                'label' => 'Description détaillée',
                'constraints' => [
                    new NotBlank(['message' => 'La description est obligatoire.']),
                    new Length([
                        'min' => 20,
                        'minMessage' => 'La description doit faire au moins {{ limit }} caractères.',
                    ]),
                ],
            ])


            ->add('ville', TextType::class, [
                'label' => 'Ville',
                'constraints' => [
                    new NotBlank(['message' => 'La ville est obligatoire.']),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Le nom de la ville doit faire au moins {{ limit }} caractères.',
                    ]),
                ],
            ])

      
            ->add('commune', TextType::class, [
                'label' => 'Commune ou quartier',
                'constraints' => [
                    new NotBlank(['message' => 'La commune est obligatoire.']),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Le nom de la commune doit faire au moins {{ limit }} caractères.',
                    ]),
                ],
            ])

      
            ->add('prix', MoneyType::class, [
                'label' => 'Prix ou loyer mensuel',
                'currency' => 'XOF',
                'constraints' => [
                    new NotBlank(['message' => 'Le prix est obligatoire.']),
                    new GreaterThan([
                        'value' => 0,
                        'message' => 'Le prix doit être supérieur à 0.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}