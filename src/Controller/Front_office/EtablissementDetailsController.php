<?php

namespace App\Controller\Front_office;

use App\Entity\Etablissement;
use App\Repository\EtablissementRepository;
use App\Repository\FiliereRepository;
use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/etablissement-details', name: 'front_etablissement_details_')]
class EtablissementDetailsController extends AbstractController
{
    #[Route('/{id}', name: 'show', requirements: ['id' => '\\d+'])]
    public function show(
        int $id,
        EtablissementRepository $etablissementRepository,
        FiliereRepository $filiereRepository,
        EvenementRepository $evenementRepository
    ): Response {
        $etablissement = $etablissementRepository->find($id);
        if (!$etablissement) {
            throw $this->createNotFoundException('Établissement introuvable.');
        }

        return $this->render('front/etablissement/show.html.twig', [
            'etablissement' => $etablissement,
            'filieres' => $filiereRepository->findBy(['etablissement' => $etablissement]),
            'evenements' => $evenementRepository->findBy(['etablissement' => $etablissement]),
        ]);
    }
}

