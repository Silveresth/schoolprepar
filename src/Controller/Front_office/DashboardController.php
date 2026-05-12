<?php

namespace App\Controller\Front_office;

use App\Repository\EvenementRepository;
use App\Repository\ForumRepository;
use App\Repository\MentorRepository;
use App\Repository\MessageRepository;
use App\Repository\RendezVousRepository;
use App\Repository\EtablissementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractController
{
    /**
     * Dashboard étudiant — redirige vers le bon panel selon le rôle.
     */
    #[Route('/dashboard', name: 'dashboard')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin_dashboard');
        }
        if ($this->isGranted('ROLE_CONSEILLER') || $this->isGranted('ROLE_MENTOR')) {
            return $this->redirectToRoute('mentor_dashboard');
        }
        return $this->redirectToRoute('student_dashboard');
    }

    #[Route('/dashboard/etudiant', name: 'student_dashboard')]
    #[IsGranted('ROLE_USER')]
    public function studentDashboard(
        EvenementRepository $evenementRepo,
        ForumRepository $forumRepo,
        MentorRepository $mentorRepo,
        RendezVousRepository $rendezVousRepo,
        EtablissementRepository $etablissementRepo,
    ): Response {
        $user = $this->getUser();

        // Rediriger les conseillers/mentors vers leur dashboard
        if ($this->isGranted('ROLE_CONSEILLER') || $this->isGranted('ROLE_MENTOR')) {
            return $this->redirectToRoute('mentor_dashboard');
        }
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin_dashboard');
        }

        $prochainsEvenements = $evenementRepo->findBy([], ['dateDebut' => 'ASC'], 3);
        $derniersForums      = $forumRepo->findBy([], ['id' => 'DESC'], 5);
        $mentors             = $mentorRepo->findBy([], ['id' => 'DESC'], 4);
        $etablissements      = $etablissementRepo->findBy([], ['id' => 'DESC'], 4);

        return $this->render('front/dashboard/student.html.twig', [
            'user'               => $user,
            'prochainsEvenements'=> $prochainsEvenements,
            'derniersForums'     => $derniersForums,
            'mentors'            => $mentors,
            'etablissements'     => $etablissements,
        ]);
    }

    #[Route('/dashboard/conseiller', name: 'mentor_dashboard')]
    #[IsGranted('ROLE_USER')]
    public function mentorDashboard(
        RendezVousRepository $rendezVousRepo,
        MentorRepository $mentorRepo,
        MessageRepository $messageRepo,
        EvenementRepository $evenementRepo,
        ForumRepository $forumRepo,
    ): Response {
        $user = $this->getUser();

        // Rediriger les simples étudiants vers leur dashboard
        if (!$this->isGranted('ROLE_CONSEILLER') && !$this->isGranted('ROLE_MENTOR') && !$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('student_dashboard');
        }
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin_dashboard');
        }

        // Trouver le profil mentor lié à cet utilisateur
        $mentorProfile = $mentorRepo->findByUtilisateur($user);
        $rendezVous = [];

        // Rendez-vous uniquement pour ce mentor (pas tous les RDV de la plateforme)
        if ($mentorProfile) {
            $rendezVous = $rendezVousRepo->findBy(
                ['idMentor' => $mentorProfile],
                ['date' => 'ASC'],
                10
            );
        }

        // Messages reçus par ce conseiller
        $allMessages = $messageRepo->findByDestinataire($user, 5);

        $prochainsEvenements = $evenementRepo->findBy([], ['dateDebut' => 'ASC'], 3);
        $derniersForums      = $forumRepo->findBy([], ['id' => 'DESC'], 5);

        return $this->render('front/dashboard/mentor.html.twig', [
            'user'               => $user,
            'mentorProfile'      => $mentorProfile,
            'rendezVous'         => $rendezVous,
            'messagesRecus'      => $allMessages,
            'prochainsEvenements'=> $prochainsEvenements,
            'derniersForums'     => $derniersForums,
        ]);
    }
}
