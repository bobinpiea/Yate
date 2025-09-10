<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Sujet;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class SujetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
                    ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sujet::class,
        ]);
    }
}
