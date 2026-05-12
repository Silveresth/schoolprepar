<?php

namespace App\Controller\Front_office;

use App\Repository\EtablissementRepository;
use App\Repository\FiliereRepository;
use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/filiere-details', name: 'front_filiere_details_')]
class FiliereDetailsController extends AbstractController
{
    #[Route('/{id}', name: 'show', requirements: ['id' => '\\d+'])]
    public function show(
        int $id,
        FiliereRepository $filiereRepository,
        EvenementRepository $evenementRepository
    ): Response {
        $filiere = $filiereRepository->find($id);
        if (!$filiere) {
            throw $this->createNotFoundException('Filière introuvable.');
        }

        // Doctrine bug possible sur relation ManyToMany: en mode robuste, filtrage via le repo existe déjà.
        // On évite le findBy(['filieres' => $filiere]) qui peut déclencher un assert ($assoc !== null).
        $evenements = $evenementRepository->findAll();

        return $this->render('front/filiere/show.html.twig', [
            'filiere' => $filiere,
            'evenements' => array_values(array_filter($evenements, fn($e) => $e->getFilieres()->contains($filiere))),
        ]);

    }
}

