<?php

namespace App\Form;

use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('texte', TextareaType::class, [
                'label' => 'Votre message',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le message ne peut pas être vide.',
                    ]),
                    new Assert\Length([
                        'min' => 3,
                        'minMessage' => 'Votre message doit contenir au moins {{ limit }} caractères.',
                        'max' => 5000,
                        'maxMessage' => 'Votre message ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'Écrivez votre réponse...',
                    'class' => 'input',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}