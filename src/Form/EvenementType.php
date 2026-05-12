<?php

namespace App\Form;

use App\Entity\Etablissement;
use App\Entity\Evenement;
use App\Entity\Filiere;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre de l\'événement',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex : Journée portes ouvertes 2025',
                ],
                'constraints' => [
                    new NotBlank(message: 'Le titre est obligatoire'),
                    new Length(min: 5, minMessage: 'Minimum {{ limit }} caractères'),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de l\'événement',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4,
                    'placeholder' => 'Décrivez cet événement...',
                ],
                'constraints' => [
                    new NotBlank(message: 'La description est obligatoire'),
                ],
            ])
            ->add('dateDebut', DateTimeType::class, [
                'label' => 'Date et heure de début',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dateFin', DateTimeType::class, [
                'label' => 'Date et heure de fin',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('lieu', TextType::class, [
                'label' => 'Lieu de l\'événement',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Adresse ou nom du lieu',
                ],
                'constraints' => [
                    new NotBlank(message: 'Le lieu est obligatoire'),
                ],
            ])
            ->add('etablissement', EntityType::class, [
                'class' => Etablissement::class,
                'choice_label' => 'nom',
                'label' => 'Établissement organisateur',
                'attr' => ['class' => 'form-select'],
                'placeholder' => '-- Choisir (optionnel) --',
                'required' => false,
            ])
            ->add('filieres', EntityType::class, [
                'class' => Filiere::class,
                'choice_label' => 'nom',
                'label' => 'Filières concernées',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ])
            ->add('participants', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => fn(Utilisateur $u) => $u->getNomComplet(),
                'label' => 'Participants inscrits',
                'multiple' => true,
                'expanded' => false,
                'attr' => ['class' => 'form-select', 'size' => 6],
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Evenement::class]);
    }
}
