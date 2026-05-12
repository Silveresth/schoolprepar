<?php

namespace App\Form;

use App\Entity\Mentor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MentorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('specialite', TextType::class, [
                'label' => 'Spécialité',
                'attr'  => ['class' => 'form-control', 'placeholder' => 'Ex : Informatique, Médecine, Droit...'],
            ])
            ->add('bio', TextareaType::class, [
                'label' => 'Biographie',
                'attr'  => ['class' => 'form-control', 'rows' => 5, 'placeholder' => 'Décrivez votre parcours...'],
            ])
            ->add('experience', TextareaType::class, [
                'label'    => 'Expérience professionnelle',
                'required' => false,
                'attr'     => ['class' => 'form-control', 'rows' => 3, 'placeholder' => 'Vos expériences...'],
            ])
            ->add('tarif', NumberType::class, [
                'label' => 'Tarif par heure (FCFA)',
                'attr'  => ['class' => 'form-control', 'placeholder' => 'Ex : 5000', 'min' => 0],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Mentor::class]);
    }
}
