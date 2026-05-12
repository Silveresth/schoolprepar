<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('role', ChoiceType::class, [
                'label'    => 'Je suis…',
                'mapped'   => true,
                'choices'  => [
                    '📚 Étudiant(e)' => 'ROLE_USER',
                    '🧑‍🏫 Conseiller / Mentor' => 'ROLE_CONSEILLER',
                ],
                'expanded' => true,
                'multiple' => false,
                'attr'     => ['class' => 'role-choice'],
                'data'     => 'ROLE_USER',
            ])
            ->add('specialite', TextType::class, [
                'label'    => 'Votre spécialité',
                'mapped'   => false,
                'required' => false,
                'attr'     => [
                    'class'       => 'form-control',
                    'placeholder' => 'Ex : Informatique, Médecine, Droit…',
                ],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr'  => ['class' => 'form-control', 'placeholder' => 'Votre prénom', 'autocomplete' => 'given-name'],
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr'  => ['class' => 'form-control', 'placeholder' => 'Votre nom', 'autocomplete' => 'family-name'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr'  => ['class' => 'form-control', 'placeholder' => 'exemple@email.com', 'autocomplete' => 'email'],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type'         => PasswordType::class,
                'mapped'       => false,
                'first_options'  => ['label' => 'Mot de passe', 'attr' => ['class' => 'form-control', 'placeholder' => 'Min. 6 caractères']],
                'second_options' => ['label' => 'Confirmer le mot de passe', 'attr' => ['class' => 'form-control', 'placeholder' => 'Répétez…']],
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
                'constraints' => [
                    new NotBlank(message: 'Le mot de passe est obligatoire'),
                    new Length(min: 6, minMessage: 'Le mot de passe doit faire au moins {{ limit }} caractères', max: 4096),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Utilisateur::class]);
    }
}
