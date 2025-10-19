<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Entity\NatureBien;
use App\Entity\Transaction;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;


class AnnonceAjaxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $options['entityManager'];

        $formBuilder
            ->add('transaction', EntityType::class, [
                'class' => Transaction::class,
                'choice_label' => 'typeTransaction',
                'placeholder' => 'Type de transaction',
                'required' => true,
                'attr' => ['data-ajax-trigger' => '1'],
            ])
            ->add('nature', EntityType::class, [
                'class' => NatureBien::class,
                'choice_label' => 'nom',
                'placeholder' => 'Nature du bien',
                'required' => true,
                'attr' => ['data-ajax-trigger' => '1'],
            ])

            ->add('photosUpload', FileType::class, [
                'label' => 'Photos du bien',
                'mapped' => false,
                'multiple' => true,
                'required' => true,
                'attr' => [
                    'accept' => 'image/jpeg, image/png, image/webp',
                    'placeholder' => 'Veuillez ajouter 1 à 3 photos',
                ],
                'constraints' => [
                    new Count([
                        'min' => 1,
                        'max' => 10,
                        'minMessage' => 'Veuillez ajouter au moins 1 photo',
                        'maxMessage' => 'Veuillez ne pas dépasser la limite de {{ limit }} photos.'
                    ]),
                    new All([
                        new File([
                            'maxSize' => '5M',
                            'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
                            'mimeTypesMessage' => 'Veuillez ajouter uniquement des formats valides (jpeg, png, webp).'
                        ])
                    ])
                ],
            ])

            ->add('details', FormType\FormType::class, [
                'mapped' => false,
            ]);

            

        $addAnnonceFields = function (FormInterface $form, string $natureLabel, string $transactionLabel): void {
            $details = $form->get('details');

            // Champs communs à toutes les annonces (obligatoires)
                $details
                    ->add('titre', FormType\TextType::class, [
                        'label' => 'Titre',
                        'constraints' => [
                            new NotBlank(['message' => 'Le titre est obligatoire.']),
                            new Length([
                                'min' => 5,
                                'minMessage' => 'Le titre doit faire au moins {{ limit }} caractères.',
                            ]),
                        ],
                        'attr' => [
                            'data-obligatoire' => true,
                            'placeholder' => 'Ex : Villa moderne avec piscine à Cocody',
                        ],
                    ])
                    ->add('description', FormType\TextareaType::class, [
                        'label' => 'Description',
                        'attr' => [
                            'rows' => 6,
                            'data-obligatoire' => true,
                            'placeholder' => 'Décrivez le bien, ses caractéristiques, son environnement...'
                        ],
                        'constraints' => [
                            new NotBlank(['message' => 'La description est obligatoire.']),
                            new Length([
                                'min' => 20,
                                'minMessage' => 'La description doit faire au moins {{ limit }} caractères.',
                            ]),
                        ],
                    ])
                    ->add('ville', FormType\TextType::class, [
                        'label' => 'Ville',
                        'constraints' => [
                            new NotBlank(['message' => 'La ville est obligatoire.']),
                            new Length([
                                'min' => 2,
                                'minMessage' => 'Le nom de la ville doit faire au moins {{ limit }} caractères.',
                            ]),
                        ],
                        'attr' => [
                            'data-obligatoire' => true,
                            'placeholder' => 'Ex: Abidjan',
                        ],
                    ])
                    ->add('commune', FormType\TextType::class, [
                        'label' => 'Commune',
                        'constraints' => [
                            new NotBlank(['message' => 'La commune est obligatoire.']),
                            new Length([
                                'min' => 2,
                                'minMessage' => 'La commune doit faire au moins {{ limit }} caractères.',
                            ]),
                        ],
                        'attr' => [
                            'data-obligatoire' => true,
                            'placeholder' => 'Ex: Cocody',
                        ],
                    ])
                    ->add('prix', FormType\MoneyType::class, [
                        'label' => 'Prix',
                        'currency' => 'XOF',
                        'constraints' => [
                            new NotBlank(['message' => 'Le prix est obligatoire.']),
                            new GreaterThan(['value' => 0, 'message' => 'Le prix doit être supérieur à 0 FCFA.']),
                        ],
                        'attr' => [
                            'data-obligatoire' => true,
                            'placeholder' => 'Ex: 12000000', 
                        ],
                    ])
                    ->add('tel', FormType\TextType::class, [
                        'label' => 'Téléphone',
                        'constraints' => [
                            new NotBlank(['message' => 'Le numéro de téléphone est obligatoire.']),
                            new Length([
                                'min' => 10, 
                                'max' => 10,
                                'exactMessage' => 'Le numéro doit faire exactement {{ limit }} caractères.'
                            ])
                        ],
                        'attr' => ['placeholder' => 'Ex: 0622232253']
                    ]);

                    

            // Champs spécifiques à location / colocation
            if (in_array(strtolower($transactionLabel), ['location', 'colocation'], true)) {
                $details
                    ->add('charge', FormType\MoneyType::class, [
                        'currency' => 'XOF',
                        'required' => false,
                        'attr' => [
                            'placeholder' => 'Ex: 15000'  
                        ],
                        'constraints' => [
                            new GreaterThan([
                                'value' => 0,
                                'message' => 'Les charges doivent être supérieures à 0 FCFA.'
                            ])
                        ]
                    ])
                    ->add('date_debut', FormType\DateType::class, [
                        'required' => false,
                        'widget' => 'single_text',
                        'html5' => true,
                        'constraints' => [
                            new GreaterThanOrEqual([
                                'value' => 'today',
                                'message' => 'La date de début ne peut pas être antérieure.',
                            ]),
                        ],
                    ])
                    ->add('date_fin', FormType\DateType::class, [
                        'required' => false,
                        'widget' => 'single_text',
                        'html5' => true,
                        'constraints' => [
                            new GreaterThanOrEqual([
                                'value' => 'today',
                                'message' => 'La date de fin ne peut pas être antérieure.',
                            ]),
                        ],
                    ]);
                
                // Si colocation uniquement : champ nb_colocataires
                if (strtolower($transactionLabel) === 'colocation') {
                    $details->add('nb_colocataires', FormType\IntegerType::class, [
                        'required' => false,
                                'attr' => [
                                'placeholder' => 'Ex : 3'
                            ],
                        'constraints' => [
                            new GreaterThan([
                                'value' => 0,
                                'message' => 'Le nombre de colocataires doit être supérieur à 0.'
                            ])
                        ]
                    ]);
                }
            }

            $nature = strtolower($natureLabel);

            if (str_contains($nature, 'maison')) {
                $details
                    ->add('superficie', FormType\IntegerType::class, [
                        'required' => false,
                        'constraints' => [
                            new GreaterThan(['value' => 0, 'message' => 'La superficie doit être supérieure à 0.'])
                        ],
                        'attr' => ['placeholder' => 'Ex : 600']
                    ])
                    ->add('surface_habitable', FormType\IntegerType::class, [
                        'required' => false,
                        'constraints' => [
                            new GreaterThan(['value' => 0, 'message' => 'La surface habitable doit être supérieur à 0.'])
                        ],
                        'attr' => ['placeholder' => 'Ex : 120']
                    ])
                    ->add('nb_pieces', FormType\IntegerType::class, [
                        'required' => false,
                        'constraints' => [
                            new GreaterThan(['value' => 0, 'message' => 'Le nombre de pièces doit être supérieur à 0.'])
                        ],
                        'attr' => ['placeholder' => 'Ex : 4']
                    ])
                    ->add('nb_chambres', FormType\IntegerType::class, [
                        'required' => false,
                        'constraints' => [
                            new GreaterThan(['value' => 0, 'message' => 'Le nombre de chambres doit être supérieur à 0.'])
                        ],
                        'attr' => ['placeholder' => 'Ex : 3']
                    ])
                    ->add('annee_construction', FormType\IntegerType::class, [
                        'required' => false,
                        'constraints' => [
                            new GreaterThan(['value' => 1800, 'message' => 'L’année doit être supérieure à 1800.'])
                        ],
                        'attr' => ['placeholder' => 'Ex : 2010']
                    ])
                    ->add('garage', FormType\CheckboxType::class, ['required' => false])
                    ->add('jardin', FormType\CheckboxType::class, ['required' => false])
                    ->add('piscine', FormType\CheckboxType::class, ['required' => false])
                    ->add('terrasse', FormType\CheckboxType::class, ['required' => false])
                    ->add('climatisateur', FormType\CheckboxType::class, ['required' => false])
                    ->add('cuisine', FormType\CheckboxType::class, ['required' => false])
                    ->add('wc', FormType\CheckboxType::class, ['required' => false])
                    ->add('salon', FormType\CheckboxType::class, ['required' => false])
                    ->add('apatam', FormType\CheckboxType::class, ['required' => false]);

            } elseif (str_contains($nature, 'appartement')) {
                $details
                        ->add('superficie', FormType\IntegerType::class, [
                            'required' => false,
                            'constraints' => [
                                new GreaterThan(['value' => 0, 'message' => 'La superficie doit être positive.'])
                            ],
                            'attr' => ['placeholder' => 'Ex: 60 (en m²)']
                        ])
                        ->add('surface_habitable', FormType\IntegerType::class, [
                            'required' => false,
                            'constraints' => [
                                new GreaterThan(['value' => 0, 'message' => 'La surface habitable doit être positive.'])
                            ],
                            'attr' => ['placeholder' => 'Ex: 45 (en m²)']
                        ])
                        ->add('nb_pieces', FormType\IntegerType::class, [
                            'required' => false,
                            'constraints' => [
                                new GreaterThan(['value' => 0, 'message' => 'Le nombre de pièces doit être supérieur à 0.'])
                            ],
                            'attr' => ['placeholder' => 'Ex: 3']
                        ])
                        ->add('nb_chambres', FormType\IntegerType::class, [
                            'required' => false,
                            'constraints' => [
                                new GreaterThan(['value' => 0, 'message' => 'Le nombre de chambres doit être supérieur à 0.'])
                            ],
                            'attr' => ['placeholder' => 'Ex: 2']
                        ])
                        ->add('annee_construction', FormType\IntegerType::class, [
                            'required' => false,
                            'constraints' => [
                                new GreaterThan(['value' => 1800, 'message' => 'L’année doit être supérieure à 1800.'])
                            ],
                            'attr' => ['placeholder' => 'Ex: 2015']
                        ])
                        ->add('etage', FormType\IntegerType::class, [
                            'required' => false,
                            'constraints' => [
                                new GreaterThan(['value' => 0, 'message' => 'L’étage doit être supérieur à 0.'])
                            ],
                            'attr' => ['placeholder' => 'Ex: 4']
                        ])
                        ->add('ascenseur', FormType\CheckboxType::class, ['required' => false])
                        ->add('balcon', FormType\CheckboxType::class, ['required' => false])
                        ->add('terrasse', FormType\CheckboxType::class, ['required' => false])
                        ->add('garage', FormType\CheckboxType::class, ['required' => false])
                        ->add('climatisateur', FormType\CheckboxType::class, ['required' => false])
                        ->add('cuisine', FormType\CheckboxType::class, ['required' => false])
                        ->add('wc', FormType\CheckboxType::class, ['required' => false])
                        ->add('salon', FormType\CheckboxType::class, ['required' => false]);

            } elseif (str_contains($nature, 'terrain')) {

                $details
                    ->add('superficie', FormType\IntegerType::class, [
                        'required' => false,
                        'attr' => [
                            'placeholder' => 'Ex: 400',
                            'data-obligatoire' => true
                        ],
                        'constraints' => [
                            new GreaterThan([
                                'value' => 0,
                                'message' => 'La superficie doit être supérieure à 0.'
                            ]),
                        ]
                    ])
                    ->add('constructible', FormType\CheckboxType::class, ['required' => false])
                    ->add('viabilise', FormType\CheckboxType::class, ['required' => false]);
            }
        };

        $formBuilder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $formEvent) use ($entityManager, $addAnnonceFields) {
            
            $formData = $formEvent->getData() ?? [];
            if (empty($formData['transaction']) || empty($formData['nature'])) return;

            $transaction = $entityManager->getRepository(Transaction::class)->find($formData['transaction']);
            $natureBien = $entityManager->getRepository(NatureBien::class)->find($formData['nature']);

            if ($transaction && $natureBien) {
                $addAnnonceFields($formEvent->getForm(), $natureBien->getNom(), $transaction->getTypeTransaction());
            }
        });
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(['data_class' => null]);
        $optionsResolver->setRequired('entityManager');
    }
}