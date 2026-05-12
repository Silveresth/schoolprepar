<?php

namespace App\Form;

use App\Entity\Test;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre du test',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex : Test d\'orientation — Baccalauréat 2025',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4,
                    'placeholder' => 'Décrivez l\'objectif de ce test...',
                ],
            ])
            ->add('date', DateTimeType::class, [
                'label' => 'Date de disponibilité',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('duree', IntegerType::class, [
                'label' => 'Durée (en minutes)',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex : 60',
                    'min' => 1,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Test::class]);
    }
}
