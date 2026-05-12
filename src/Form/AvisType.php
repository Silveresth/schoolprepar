<?php

namespace App\Form;

use App\Entity\Avis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AvisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note', ChoiceType::class, [
                'label' => 'Votre note',
                'attr' => ['class' => 'form-select'],
                'choices' => [
                    '⭐ 1 — Très insuffisant' => 1,
                    '⭐⭐ 2 — Insuffisant'    => 2,
                    '⭐⭐⭐ 3 — Passable'     => 3,
                    '⭐⭐⭐⭐ 4 — Bien'        => 4,
                    '⭐⭐⭐⭐⭐ 5 — Excellent' => 5,
                ],
                'placeholder' => '-- Sélectionner une note --',
            ])
            ->add('commentaire', TextareaType::class, [
                'label' => 'Commentaire',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4,
                    'placeholder' => 'Votre avis détaillé sur ce mentor (minimum 10 caractères)',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Avis::class]);
    }
}
