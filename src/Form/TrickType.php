<?php

namespace App\Form;

use App\Entity\Trick;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Validator\Constraints\NotBlank;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [new NotBlank()],
            ])
            ->add('description', TextareaType::class, [
                'constraints' => [new NotBlank()],
            ])
            ->add('groupName', ChoiceType::class, [
                'constraints' => [new NotBlank()],
                'choices' => [
                    'Butters' => 'butters',
                    'Grabs' => 'grabs',
                    'Spins' => 'spins',
                    'Flips' => 'flips',
                ],
            ])
            ->add('images', CollectionType::class, [
                'entry_type' => FileType::class,
                'allow_add' => true,
                'mapped' => false,
                'required' => false,
                'by_reference' => false,
                'prototype' => true,
                'prototype_data' => null,
                'attr' => ['class' => 'images'],
                'entry_options' => [
                    'label' => false,
                    'attr' => ['accept' => 'image/*'],
                    'constraints' => [
                        new File([
                            'maxSize' => '5M',
                            'mimeTypes' => ['image/png', 'image/jpeg', 'image/gif'],
                            'mimeTypesMessage' => 'Veuillez télécharger une image valide (PNG, JPEG, GIF)',
                        ]),
                    ],
                ],
            ])
            ->add('videos', CollectionType::class, [
                'entry_type' => VideoType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required' => false,
                'prototype' => true,
                'label' => false,
                'entry_options' => [
                    'label' => false,
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Créer le trick',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}

