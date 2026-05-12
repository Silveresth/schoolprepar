<?php

namespace App\Controller\Front_office;

use App\Entity\ParticipantEvenement;
use App\Repository\EvenementRepository;
use App\Repository\ParticipantEvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/evenements', name: 'front_evenement_')]
class EvenementController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(EvenementRepository $repo): Response
    {
        return $this->render('front/evenement/index.html.twig', [
            'evenements' => $repo->findBy([], ['dateDebut' => 'ASC']),
        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(int $id, EvenementRepository $repo): Response
    {
        $evenement = $repo->find($id);
        if (!$evenement) {
            throw $this->createNotFoundException('Événement introuvable.');
        }

        return $this->render('front/evenement/show.html.twig', [
            'evenement' => $evenement,
            'dejainscrit' => $this->getUser()
                ? $evenement->getParticipants()->contains($this->getUser())
                : false,
        ]);
    }

    #[Route('/{id}/inscription', name: 'inscription', methods: ['POST'])]
    #[IsGranted('ROLE_USER', message: 'Vous devez être connecté pour vous inscrire.')]
    public function inscription(int $id, EvenementRepository $repo, EntityManagerInterface $em, Request $request): Response
    {
        $evenement = $repo->find($id);
        if (!$evenement) {
            throw $this->createNotFoundException('Événement introuvable.');
        }

        if (!$this->isCsrfTokenValid('inscription' . $id, $request->request->get('_token'))) {
            $this->addFlash('error', 'Token invalide.');
            return $this->redirectToRoute('front_evenement_show', ['id' => $id]);
        }

        $user = $this->getUser();

        if ($evenement->getParticipants()->contains($user)) {
            $this->addFlash('warning', 'Vous êtes déjà inscrit à cet événement.');
        } else {
            $participant = new ParticipantEvenement();
            $participant->setEvenement($evenement);
            $participant->setUser($user);
            $participant->setDateInscription(new \DateTime());
            $participant->setStatut('inscrit');
            $em->persist($participant);
            $em->flush();
            $this->addFlash('success', 'Inscription confirmée ! À bientôt.');
        }

        return $this->redirectToRoute('front_evenement_show', ['id' => $id]);
    }
}
