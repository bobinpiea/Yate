<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Sujet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class SujetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Champ Titre du sujet
            ->add('titre', null, [
                'label' => "Titre du sujet",
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => "Le titre est obligatoire.",
                    ]),
                    new Assert\Length([
                        'min' => 3,
                        'minMessage' => "Le titre doit contenir au moins {{ limit }} caractères.",
                        'max' => 150,
                        'maxMessage' => "Le titre ne peut pas dépasser {{ limit }} caractères.",
                    ]),
                ],
                'attr' => [
                    'placeholder' => "Ex: Recherche colocation à Abidjan",
                    'class' => 'input',
                ],
            ])

            // Champ Catégorie (lié à l'entité Categorie)
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class, 
                'choice_label' => 'nom',
                'label' => 'Choisir une catégorie',
                'placeholder' => 'Sélectionnez une catégorie',
                'required' => true,
            ])

            // Champ du Premier Message
            ->add('premierMessage', TextareaType::class, [
                'label' => "Votre message",
                'mapped' => false, 
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => "Le message est obligatoire.",
                    ]),
                    new Assert\Length([
                        'min' => 5,
                        'minMessage' => "Le message doit contenir au moins {{ limit }} caractères.",
                    ]),
                ],
                'attr' => [
                    'placeholder' => "Écrivez ici le contenu de votre message...",
                    'rows' => 6,
                    'class' => 'textarea',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sujet::class, 
        ]);
    }
}