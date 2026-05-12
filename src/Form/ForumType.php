<?php

namespace App\Form;

use App\Entity\Filiere;
use App\Entity\Forum;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ForumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('filiere', EntityType::class, [
                'class' => Filiere::class,
                'choice_label' => 'nom',
                'label' => 'Filière concernée',
                'attr' => ['class' => 'form-select'],
                'placeholder' => '-- Sélectionner une filière --',
            ])
            ->add('titre', TextType::class, [
                'label' => 'Titre du sujet',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Titre de votre sujet de discussion...',
                ],
            ])
            ->add('contenu', TextareaType::class, [
                'label' => 'Contenu du message',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 6,
                    'placeholder' => 'Détaillez votre sujet ici (minimum 10 caractères)...',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Forum::class]);
    }
}
