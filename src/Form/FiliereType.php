<?php

namespace App\Form;

use App\Entity\Etablissement;
use App\Entity\Filiere;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File as FileConstraint;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class FiliereType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la filière',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex : Génie Logiciel, Médecine, Droit...',
                ],
                'constraints' => [
                    new NotBlank(message: 'Le nom est obligatoire'),
                    new Length(min: 3, max: 255, minMessage: 'Minimum {{ limit }} caractères'),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de la filière',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4,
                    'placeholder' => 'Décrivez les objectifs et le contenu de cette filière...',
                ],
                'constraints' => [
                    new NotBlank(message: 'La description est obligatoire'),
                    new Length(min: 20, minMessage: 'Minimum {{ limit }} caractères'),
                ],
            ])
            ->add('debouche', TextType::class, [
                'label' => 'Débouchés professionnels',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex : Développeur, Ingénieur, Médecin...',
                ],
                'constraints' => [
                    new NotBlank(message: 'Les débouchés sont obligatoires'),
                ],
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image de la filière',
                'required' => false,
                'mapped' => false,
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new FileConstraint([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Format d\'image invalide (JPEG/PNG/WEBP requis)',
                    ]),
                ],
            ])
            ->add('niveau_requis', TextType::class, [

                'label' => 'Niveau requis pour intégrer la filière',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex : Bac, Bac+2, Licence...',
                ],
                'constraints' => [
                    new NotBlank(message: 'Le niveau requis est obligatoire'),
                ],
            ])
            ->add('etablissement', EntityType::class, [
                'class' => Etablissement::class,
                'choice_label' => 'nom',
                'label' => 'Établissement proposant cette filière',
                'attr' => ['class' => 'form-select'],
                'placeholder' => '-- Choisir un établissement --',
                'constraints' => [
                    new NotBlank(message: 'L\'établissement est obligatoire'),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Filiere::class]);
    }
}
