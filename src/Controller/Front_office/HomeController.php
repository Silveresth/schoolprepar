<?php

namespace App\Controller\Front_office;

use App\Repository\EtablissementRepository;
use App\Repository\EvenementRepository;
use App\Repository\FiliereRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(
        FiliereRepository $filiereRepo,
        EtablissementRepository $etablissementRepo,
        UtilisateurRepository $utilisateurRepo,
        EvenementRepository $evenementRepo
    ): Response {
        return $this->render('front/home.html.twig', [
            'stats' => [
                'total_filieres'       => $filiereRepo->count([]),
                'total_etablissements' => $etablissementRepo->count([]),
                'total_utilisateurs'   => $utilisateurRepo->count([]),
                'total_evenements'     => $evenementRepo->count([]),
            ],
            'dernieres_filieres' => $filiereRepo->findBy([], ['id' => 'DESC'], 6),
        ]);
    }
}
