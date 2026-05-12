<?php

namespace App\Controller\Front_office;

use App\Repository\EvenementRepository;
use App\Repository\FiliereRepository;
use App\Repository\ResultatTestRepository;
use App\Repository\TestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/recommandations', name: 'front_recommandations_')]
class RecommendationsController extends AbstractController
{
    #[Route('/', name: 'index')]
    #[IsGranted('ROLE_USER')]
    public function index(
        FiliereRepository $filiereRepository,
        TestRepository $testRepository,
        ResultatTestRepository $resultatRepo,
        EvenementRepository $evenementRepository
    ): Response {
        $user = $this->getUser();

        // Heuristique simple basée sur l'existence de résultats de tests.
        $lastResult = $resultatRepo->findOneBy(['user' => $user], ['date' => 'DESC']);

        $tests = $testRepository->findAll();
        $filieres = $filiereRepository->findAll();
        $evenements = $evenementRepository->findAll();

        // Score -> choix arbitraire pour une démo :
        // - >= 80 : viser filières au niveau le plus élevé (tri string->number via fallback)
        // - 50-79 : niveau moyen
        // - < 50 : niveau découverte
        $niveauCible = 'Tous niveaux';
        if ($lastResult) {
            $score = $lastResult->getScore();
            if ($score >= 80) {
                $niveauCible = 'Avancé';
            } elseif ($score >= 50) {
                $niveauCible = 'Intermédiaire';
            } else {
                $niveauCible = 'Découverte';
            }
        }

        // On ne connaît pas la granularité exacte de "niveauRequis".
        // Donc on filtre partiellement sur mots-clés.
        $filieresFiltres = array_values(array_filter($filieres, function ($f) use ($niveauCible) {
            if ($niveauCible === 'Tous niveaux') {
                return true;
            }
            $n = strtolower((string) $f->getNiveauRequis());
            if ($niveauCible === 'Avancé') {
                return str_contains($n, 'bac') || str_contains($n, 'licence') || str_contains($n, 'master');
            }
            if ($niveauCible === 'Intermédiaire') {
                return str_contains($n, 'niveau') || str_contains($n, 'tech') || str_contains($n, 'bts');
            }
            // Découverte
            return true;
        }));

        return $this->render('front/recommandations/index.html.twig', [
            'niveauCible' => $niveauCible,
            'lastResult' => $lastResult,
            'filieres' => $filieresFiltres,
            'evenements' => $evenements,
            'tests' => $tests,
        ]);
    }
}

