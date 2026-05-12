<?php

namespace App\Controller\Front_office;

use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Les webinaires sont les mêmes que les événements dans ce projet.
 * Ce controller redirige vers la section Événements pour éviter la duplication.
 */
#[Route('/webinaires', name: 'front_webinaires_')]
class WebinaireController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        // Redirection vers la liste des événements (même contenu)
        return $this->redirectToRoute('front_evenement_index');
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(int $id): Response
    {
        // Redirection vers le détail de l'événement correspondant
        return $this->redirectToRoute('front_evenement_show', ['id' => $id]);
    }
}
