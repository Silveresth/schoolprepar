<?php

namespace App\Form;

use App\Entity\Mentor;
use App\Entity\RendezVous;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RendezVousType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isAdmin = $options['is_admin'] ?? false;

        $builder->add('date', DateTimeType::class, [
            'label' => 'Date et heure du rendez-vous',
            'widget' => 'single_text',
            'attr' => ['class' => 'form-control'],
        ]);

        if ($isAdmin) {
            // En admin on peut choisir élève, mentor et statut
            $builder
                ->add('idEleve', EntityType::class, [
                    'class' => Utilisateur::class,
                    'choice_label' => fn(Utilisateur $u) => $u->getNomComplet() . ' (' . $u->getEmail() . ')',
                    'label' => 'Élève',
                    'attr' => ['class' => 'form-select'],
                    'placeholder' => '-- Sélectionner l\'élève --',
                ])
                ->add('idMentor', EntityType::class, [
                    'class' => Mentor::class,
                    'choice_label' => fn(Mentor $m) => $m->getUtilisateur()?->getNomComplet() . ' — ' . $m->getSpecialite(),
                    'label' => 'Mentor',
                    'attr' => ['class' => 'form-select'],
                    'placeholder' => '-- Sélectionner un mentor --',
                ])
                ->add('statut', ChoiceType::class, [
                    'label' => 'Statut',
                    'attr' => ['class' => 'form-select'],
                    'choices' => [
                        'En attente' => 'en_attente',
                        'Confirmé'   => 'confirme',
                        'Annulé'     => 'annule',
                    ],
                ]);
        }
        // En front: idEleve et idMentor sont définis dans le controller, pas dans le form
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RendezVous::class,
            'is_admin'   => false,
        ]);
    }
}
