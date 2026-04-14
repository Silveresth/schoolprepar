<?php

namespace App\Controller\Admin;

use App\Repository\EtablissementRepository;
use App\Repository\EvenementRepository;
use App\Repository\FiliereRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminDashboardController extends AbstractController
{
    #[Route('/', name: 'admin_dashboard')]
    public function dashboard(
        FiliereRepository $filiereRepo,
        EtablissementRepository $etablissementRepo,
        UtilisateurRepository $utilisateurRepo,
        EvenementRepository $evenementRepo
    ): Response {
        return $this->render('admin/dashboard.html.twig', [
            'stats' => [
                'total_filieres'       => $filiereRepo->count([]),
                'total_etablissements' => $etablissementRepo->count([]),
                'total_utilisateurs'   => $utilisateurRepo->count([]),
                'total_evenements'     => $evenementRepo->count([]),
            ],
            'dernieres_filieres' => $filiereRepo->findBy([], ['id' => 'DESC'], 5),
            'derniers_utilisateurs' => $utilisateurRepo->findBy([], ['id' => 'DESC'], 5),
        ]);
    }
}
