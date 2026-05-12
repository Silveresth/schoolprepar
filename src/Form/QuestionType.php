<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\Test;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('test', EntityType::class, [
                'class' => Test::class,
                'choice_label' => 'titre',
                'label' => 'Test parent',
                'attr' => ['class' => 'form-select'],
                'placeholder' => '-- Sélectionner le test --',
            ])
            ->add('contenu', TextareaType::class, [
                'label' => 'Énoncé de la question',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4,
                    'placeholder' => 'Rédigez la question ici...',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Question::class]);
    }
}
