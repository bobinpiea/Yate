<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Entity\NatureBien;
use App\Entity\Transaction;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use PHPUnit\Framework\Constraint\GreaterThan;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Form\Extension\Core\Type as F; // Utiliser tous les types de champs avec un As court 
use Symfony\Component\Form\FormEvent; // Utilisé pour capter les événements (comme PRE_SUBMIT, POST_SUBMIT...) dans un formulaire

class AnnonceAjaxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var EntityManagerInterface $em */
        $em = $options['em'];

        // Étape 1 : Sélection du type de transaction et du type de bien
        $builder
            ->add('transaction', EntityType::class, [
                'class' => Transaction::class,
                'choice_label' => 'typeTransaction',
                'placeholder' => '— Type de transaction —',
                'required' => true,
                'attr' => ['data-ajax-trigger' => '1'],
            ])
            ->add('nature', EntityType::class, [
                'class' => NatureBien::class,
                'choice_label' => 'nom',
                'placeholder' => '— Nature du bien —',
                'required' => true,
                'attr' => ['data-ajax-trigger' => '1'],
            ])

            // Ici que  Symfony va injecter les champs spécifiques selon les choix de l’utilisateur (maison, appartement, terrain).
            ->add('details', F\FormType::class, [ // Formulaire vide pour débuter
                'mapped' => false,
            ]);

        // Fonction pour injecter les champs dans le sous-formulaire "details"
        $addAnnonceFields = function (FormInterface $form, string $natureLabel, string $txLabel) {
            $details = $form->get('details');

            // Champs communs
            $details

                ->add('titre', F\TextType::class, [
                    'constraints' => [
                        new NotBlank(['message' => 'Le titre est obligatoire.']),
                        new Length([
                            'min' => 5,
                            'minMessage' => 'Le titre doit faire au moins {{ limit }} caractères.',
                        ]),
                    ],
                ])

                ->add('description', F\TextareaType::class, [
                    'attr' => ['rows' => 6],
                    'constraints' => [
                        new NotBlank(['message' => 'La description est obligatoire.']),
                        new Length([
                            'min' => 20,
                            'minMessage' => 'La description doit faire au moins {{ limit }} caractères.',
                        ]),
                    ],
                ])

                ->add('ville', F\TextType::class, [
                    'constraints' => [
                        new NotBlank(['message' => 'La ville est obligatoire.']),
                        new Length([
                            'min' => 2,
                            'minMessage' => 'Le nom de la ville doit faire au moins {{ limit }} caractères.',
                        ]),
                    ],
                ])    

                ->add('commune', F\TextType::class, [
                    'constraints' => [
                        new NotBlank(['message' => 'La commune est obligatoire.']),
                        new Length([
                            'min' => 2,
                            'minMessage' => 'La commune doit faire au moins {{ limit }} caractères.',
                        ]),
                    ],
                ])

                ->add('prix', F\MoneyType::class, [
                    'currency' => 'XOF',
                    'constraints' => [
                        new NotBlank(['message' => 'Le prix est obligatoire.']),
                        new GreaterThan([
                            'value' => 0,
                            'message' => 'Le prix doit être supérieur à 0 FCFA.',
                        ]),
                    ],
                ])

                ->add('charge', F\MoneyType::class, [
                    'currency' => 'XOF',
                    'required' => in_array(strtolower($txLabel), ['louer', 'colocation'], true),
                    'constraints' => [
                        new GreaterThan([
                            'value' => 0,
                            'message' => 'Les charges doivent être supérieures à 0 FCFA.'
                        ])
                    ]
                ])

            ->add('date_debut', F\DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'label' => 'Date de début',
                'html5' => true,
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => 'La date de début ne peut pas être antérieure.',
                    ]),
                ],
            ])

            ->add('date_fin', F\DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'label' => 'Date de fin',
                'html5' => true,
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => 'La date de fin ne peut pas être antérieure.',
                    ]),
                ],
            ]);

            $n = strtolower($natureLabel); // Ajout des champs selon la nature du bien choisie

            if (str_contains($n, 'maison')) {
                $details
                    ->add('superficie', F\IntegerType::class, [
                        'constraints' => [
                            new NotBlank(['message' => 'La superficie est obligatoire.']),
                            new GreaterThan(['value' => 0, 'message' => 'La superficie doit être positive.'])
                        ]
                    ])
                    ->add('surface_habitable', F\IntegerType::class, [
                        'required' => false,
                        'constraints' => [
                            new GreaterThan(['value' => 0, 'message' => 'La surface habitable doit être positive.'])
                        ]
                    ])
                    ->add('nb_pieces', F\IntegerType::class, [
                        'required' => false,
                        'constraints' => [
                            new GreaterThan(['value' => 0, 'message' => 'Le nombre de pièces doit être supérieur à 0.'])
                        ]
                    ])
                    ->add('nb_chambres', F\IntegerType::class, [
                        'required' => false,
                        'constraints' => [
                            new GreaterThan(['value' => 0, 'message' => 'Le nombre de chambres doit être supérieur à 0.'])
                        ]
                    ])
                    ->add('annee_construction', F\IntegerType::class, [
                        'required' => false,
                        'constraints' => [
                            new GreaterThan(['value' => 1800, 'message' => 'L’année doit être supérieure à 1800.'])
                        ]
                    ])
                    ->add('garage', F\CheckboxType::class, ['required' => false])
                    ->add('jardin', F\CheckboxType::class, ['required' => false])
                    ->add('piscine', F\CheckboxType::class, ['required' => false])
                    ->add('terrasse', F\CheckboxType::class, ['required' => false])
                    ->add('climatisateur', F\CheckboxType::class, ['required' => false])
                    ->add('cuisine', F\CheckboxType::class, ['required' => false])
                    ->add('wc', F\CheckboxType::class, ['required' => false])
                    ->add('salon', F\CheckboxType::class, ['required' => false])
                    ->add('apatam', F\CheckboxType::class, ['required' => false]);

            } elseif (str_contains($n, 'appartement')) {
                $details
                    ->add('superficie', F\IntegerType::class, [
                        'constraints' => [
                            new NotBlank(['message' => 'La superficie est obligatoire.']),
                            new GreaterThan(['value' => 0, 'message' => 'La superficie doit être positive.'])
                        ]
                    ])
                    ->add('nb_pieces', F\IntegerType::class, [
                        'required' => false,
                        'constraints' => [
                            new GreaterThan(['value' => 0, 'message' => 'Le nombre de pièces doit être supérieur à 0.'])
                        ]
                    ])
                    ->add('nb_chambres', F\IntegerType::class, [
                        'required' => false,
                        'constraints' => [
                            new GreaterThan(['value' => 0, 'message' => 'Le nombre de chambres doit être supérieur à 0.'])
                        ]
                    ])
                    ->add('etage', F\IntegerType::class, [
                        'required' => false,
                        'constraints' => [
                            new GreaterThan(['value' => 0, 'message' => 'L’étage doit être supérieur à 0.'])
                        ]
                    ])
                    ->add('ascenseur', F\CheckboxType::class, ['required' => false])
                    ->add('balcon', F\CheckboxType::class, ['required' => false])
                    ->add('terrasse', F\CheckboxType::class, ['required' => false])
                    ->add('cuisine', F\CheckboxType::class, ['required' => false]);


            } elseif (str_contains($n, 'terrain')) {
                $details
                    ->add('superficie', F\IntegerType::class, [
                        'constraints' => [
                            new NotBlank(['message' => 'La superficie est obligatoire.']),
                            new GreaterThan(['value' => 0, 'message' => 'La superficie doit être positive.']),
                        ]
                    ])
                    ->add('constructible', F\CheckboxType::class, ['required' => false])
                    ->add('viabilise', F\CheckboxType::class, ['required' => false]);
            }
        };

        // Événement qui déclenche l'ajout dynamique des champs selon les choix transaction/nature
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $e) use ($em, $addAnnonceFields) {
            $d = $e->getData() ?? [];
            if (empty($d['transaction']) || empty($d['nature'])) return;

            $tx = $em->getRepository(Transaction::class)->find($d['transaction']);
            $nb = $em->getRepository(NatureBien::class)->find($d['nature']);

            if ($tx && $nb) {
                $addAnnonceFields($e->getForm(), $nb->getNom(), $tx->getTypeTransaction());
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => null]);
        $resolver->setRequired('em');
    }
}
