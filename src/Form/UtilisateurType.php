<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File as FileConstraint;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Prénom de l\'utilisateur',
                    'autocomplete' => 'given-name',
                ],
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom de famille',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom de famille',
                    'autocomplete' => 'family-name',
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'exemple@email.com',
                    'autocomplete' => 'email',
                ],
            ])
            ->add('role', ChoiceType::class, [
                'label' => 'Rôle & permissions',
                'attr' => ['class' => 'form-select'],
                'help' => 'Les admins ont accès au back-office complet.',
                'choices' => [
                    'Utilisateur (élève)' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                    'Conseiller' => 'ROLE_CONSEILLER',
                ],
            ])
            ->add('photoFile', FileType::class, [
                'label' => 'Photo de profil',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
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
            ->add('password', PasswordType::class, [

                'label' => $options['is_new'] ? 'Mot de passe' : 'Nouveau mot de passe',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $options['is_new'] ? 'Minimum 6 caractères' : 'Laisser vide pour ne pas changer',
                    'autocomplete' => 'new-password',
                ],
                'required' => $options['is_new'],
                'empty_data' => '',
                'constraints' => $options['is_new'] ? [
                    new NotBlank(message: 'Le mot de passe est obligatoire'),
                    new Length(min: 6, minMessage: 'Le mot de passe doit faire au moins {{ limit }} caractères'),
                ] : [
                    new Length(min: 6, minMessage: 'Le mot de passe doit faire au moins {{ limit }} caractères'),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
            'is_new' => true,
        ]);

        $resolver->setAllowedTypes('is_new', 'bool');
    }
}
