<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\Reponse;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('question', EntityType::class, [
                'class' => Question::class,
                'choice_label' => fn(Question $q) => '#' . $q->getId() . ' — ' . mb_substr($q->getContenu(), 0, 60) . '...',
                'label' => 'Question associée',
                'attr' => ['class' => 'form-select'],
                'placeholder' => '-- Sélectionner la question --',
            ])
            ->add('contenu', TextareaType::class, [
                'label' => 'Contenu de la réponse',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 3,
                    'placeholder' => 'Rédigez la réponse ici...',
                ],
            ])
            ->add('points', null, [
                'label' => 'Points (0 = incorrecte)',
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Reponse::class]);
    }
}
