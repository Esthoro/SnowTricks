<?php

namespace App\Form;

use App\Entity\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('embedCode', TextareaType::class, [
                'label' => false,
            ]);

        $builder->get('embedCode')
            ->addModelTransformer(new CallbackTransformer(
                function ($originalEmbedCode) {
                    return Video::cleanEmbedCode($originalEmbedCode);
                },
                function ($submittedEmbedCode) {
                    return $submittedEmbedCode; // Pas de transformation inverse nécessaire ici
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Video::class,
        ]);
    }
}
