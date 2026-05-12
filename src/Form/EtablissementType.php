<?php

namespace App\Form;

use App\Entity\Etablissement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File as FileConstraint;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class EtablissementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de l\'établissement',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex : Université de Lomé',
                ],
                'constraints' => [
                    new NotBlank(message: 'Le nom est obligatoire'),
                    new Length(min: 3, max: 255, minMessage: 'Minimum {{ limit }} caractères'),
                ],
            ])
            ->add('adresse', TextareaType::class, [
                'label' => 'Adresse postale',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 3,
                    'placeholder' => 'Adresse complète de l\'établissement',
                ],
                'constraints' => [
                    new NotBlank(message: 'L\'adresse est obligatoire'),
                ],
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image de l\'établissement',
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
            ->add('ville', TextType::class, [
                'label' => 'Ville',

                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex : Lomé, Kara, Sokodé...',
                ],
                'constraints' => [
                    new NotBlank(message: 'La ville est obligatoire'),
                    new Length(min: 2, minMessage: 'Minimum {{ limit }} caractères'),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Etablissement::class]);
    }
}
