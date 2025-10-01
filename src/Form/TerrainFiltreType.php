<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class TerrainFiltreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prixMin', FormType\IntegerType::class, [
                'required' => false,
                'label' => 'Prix minimum',
                'attr' => ['placeholder' => 'Ex: 1000000'],
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => 0,
                        'message' => 'Le prix minimum doit être positif.'
                    ])
                ]
            ])
            ->add('prixMax', FormType\IntegerType::class, [
                'required' => false,
                'label' => 'Prix maximum',
                'attr' => ['placeholder' => 'Ex: 100000000'],
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => 0,
                        'message' => 'Le prix maximum doit être positif.'
                    ])
                ]
            ])
            ->add('ville', FormType\TextType::class, [
                'required' => false,
                'label' => 'Ville',
                'attr' => ['placeholder' => 'Ex: Abidjan']
            ])
            ->add('superficieMin', FormType\IntegerType::class, [
                'required' => false,
                'label' => 'Superficie minimum',
                'attr' => ['placeholder' => 'Ex: 200'],
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => 0,
                        'message' => 'La superficie minimum doit être positive.'
                    ])
                ]
            ])

            ->add('commune', TextType::class, [
                'required' => false,
                'label' => 'Commune',
                'attr' => [
                    'placeholder' => 'Ex : Cocody'
                ],
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Le nom de la commune doit faire au moins {{ limit }} caractères.',
                        'max' => 100,
                        'maxMessage' => 'Le nom de la commune ne peut pas dépasser {{ limit }} caractères.'
                    ]),
                ],
            ])


            ->add('superficieMax', FormType\IntegerType::class, [
                'required' => false,
                'label' => 'Superficie maximum',
                'attr' => ['placeholder' => 'Ex: 1000'],
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => 0,
                        'message' => 'La superficie maximum doit être positive.'
                    ])
                ]
            ])
            ->add('constructible', FormType\CheckboxType::class, [
                'required' => false,
                'label' => 'Constructible'
            ])
            ->add('viabilise', FormType\CheckboxType::class, [
                'required' => false,
                'label' => 'Viabilisé'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // pas d’entité associée - filtre libre
            'data_class' => null,
            'method' => 'GET',
            'csrf_protection' => false, 
        ]);
    }
}