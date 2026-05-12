<?php

namespace App\Controller\Security;

use App\Entity\Mentor;
use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use App\Repository\MentorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            if ($this->isGranted('ROLE_ADMIN')) return $this->redirectToRoute('admin_dashboard');
            if ($this->isGranted('ROLE_CONSEILLER') || $this->isGranted('ROLE_MENTOR')) return $this->redirectToRoute('mentor_dashboard');
            return $this->redirectToRoute('student_dashboard');
        }

        return $this->render('security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error'         => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher,
        MentorRepository $mentorRepo
    ): Response {
        if ($this->getUser()) return $this->redirectToRoute('home');

        $utilisateur = new Utilisateur();
        $form = $this->createForm(RegistrationFormType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateur->setPassword($hasher->hashPassword($utilisateur, $form->get('plainPassword')->getData()));

            $em->persist($utilisateur);

            // Si le rôle choisi est conseiller → créer automatiquement un profil Mentor
            if ($utilisateur->getRole() === 'ROLE_CONSEILLER') {
                $specialite = $form->get('specialite')->getData() ?? 'Non définie';

                $mentor = new Mentor();
                $mentor->setUtilisateur($utilisateur);
                $mentor->setSpecialite($specialite);
                $mentor->setBio('Biographie à compléter dans mon profil.');
                $mentor->setTarif(0);
                $em->persist($mentor);
            }

            $em->flush();

            $this->addFlash('success', 'Compte créé avec succès ! Vous pouvez vous connecter.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('Intercepted by firewall.');
    }
}
