<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MaisonFiltreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            /*  LOCALISATION  */
            ->add('ville', TextType::class, [
                'label' => 'Ville',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ex : Abidjan',
                ],
            ])
            ->add('commune', TextType::class, [
                'label' => 'Commune',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ex : Cocody',
                ],
            ])

            /*  BUDGET  */
            ->add('prixMin', IntegerType::class, [
                'label' => 'Prix min (FCFA)',
                'required' => false,
            ])
            ->add('prixMax', IntegerType::class, [
                'label' => 'Prix max (FCFA)',
                'required' => false,
            ])

            /*  SURFACE  */
            ->add('superficieMin', IntegerType::class, [
                'label' => 'Superficie min (m²)',
                'required' => false,
            ])
            ->add('superficieMax', IntegerType::class, [
                'label' => 'Superficie max (m²)',
                'required' => false,
            ])

            /*  ESPACE INTÉRIEUR  */
            ->add('nbPieces', IntegerType::class, [
                'label' => 'Nombre de pièces',
                'required' => false,
            ])
            ->add('nbChambres', IntegerType::class, [
                'label' => 'Nombre de chambres',
                'required' => false,
            ])

            /*  CARACTÉRISTIQUES  */
            ->add('garage', CheckboxType::class, [
                'label' => 'Garage',
                'required' => false,
            ])
            ->add('jardin', CheckboxType::class, [
                'label' => 'Jardin',
                'required' => false,
            ])
            ->add('piscine', CheckboxType::class, [
                'label' => 'Piscine',
                'required' => false,
            ])
            ->add('apatam', CheckboxType::class, [
                'label' => 'Apatam',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,     
            'method' => 'GET',       
            'csrf_protection' => false, 
        ]);
    }
}
