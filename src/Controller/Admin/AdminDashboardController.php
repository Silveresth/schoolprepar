<?php

namespace App\Controller\Admin;

use App\Repository\UtilisateurRepository;
use App\Repository\EtablissementRepository;
use App\Repository\FiliereRepository;
use App\Repository\EvenementRepository;
use App\Repository\ForumRepository;
use App\Repository\RendezVousRepository;
use App\Repository\MentorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminDashboardController extends AbstractController
{
    #[Route('/', name: 'admin_dashboard')]
    public function dashboard(
        UtilisateurRepository $utilisateurRepo,
        EtablissementRepository $etablissementRepo,
        FiliereRepository $filiereRepo,
        EvenementRepository $evenementRepo,
        ForumRepository $forumRepo,
        RendezVousRepository $rendezVousRepo,
        MentorRepository $mentorRepo,
    ): Response {
        return $this->render('admin/dashboard.html.twig', [
            'stats' => [
                'utilisateurs' => $utilisateurRepo->count([]),
                'etablissements' => $etablissementRepo->count([]),
                'filieres' => $filiereRepo->count([]),
                'evenements' => $evenementRepo->count([]),
                'forums' => $forumRepo->count([]),
                'rendezVous' => $rendezVousRepo->count([]),
                'mentors' => $mentorRepo->count([]),
            ],
            'recentUsers' => $utilisateurRepo->findBy([], ['id' => 'DESC'], 5),
        ]);
    }
}
