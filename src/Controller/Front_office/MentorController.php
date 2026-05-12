<?php

namespace App\Controller\Front_office;

use App\Entity\Avis;
use App\Entity\RendezVous;
use App\Form\AvisType;
use App\Form\RendezVousType;
use App\Repository\MentorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/mentors', name: 'front_mentor_')]
class MentorController extends AbstractController
{
    /**
     * Liste publique des mentors — accessible à tous les utilisateurs connectés.
     * (Pas besoin d'être mentor pour voir la liste des mentors !)
     */
    #[Route('/', name: 'index')]
    #[IsGranted('ROLE_USER')]
    public function index(MentorRepository $repo): Response
    {
        return $this->render('front/mentor/index.html.twig', [
            'mentors' => $repo->findAll(),
        ]);
    }

    /**
     * Profil détaillé d'un mentor avec formulaire d'avis et de rendez-vous.
     * Accessible à tous les utilisateurs connectés.
     */
    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_USER')]
    public function show(int $id, MentorRepository $repo, Request $request, EntityManagerInterface $em): Response
    {
        $mentor = $repo->find($id);
        if (!$mentor) {
            throw $this->createNotFoundException('Mentor introuvable.');
        }

        $me = $this->getUser();
        $isOwner = ($mentor->getUtilisateur() === $me);

        // Formulaire avis (seulement pour les étudiants, pas le mentor lui-même)
        $formAvis = null;
        if (!$isOwner) {
            $avis = new Avis();
            $formAvis = $this->createForm(AvisType::class, $avis);
            $formAvis->handleRequest($request);
            if ($formAvis->isSubmitted() && $formAvis->isValid()) {
                $avis->setMentor($mentor);
                $avis->setAuteur($me);
                $em->persist($avis);
                $em->flush();
                $this->addFlash('success', 'Votre avis a été publié.');
                return $this->redirectToRoute('front_mentor_show', ['id' => $id]);
            }
        }

        // Formulaire rendez-vous (seulement pour les étudiants, pas le mentor lui-même)
        $formRdv = null;
        if (!$isOwner) {
            $rdv = new RendezVous();
            $formRdv = $this->createForm(RendezVousType::class, $rdv);
            $formRdv->handleRequest($request);
            if ($formRdv->isSubmitted() && $formRdv->isValid()) {
                $rdv->setIdMentor($mentor);
                $rdv->setIdEleve($me);
                $em->persist($rdv);
                $em->flush();
                $this->addFlash('success', 'Demande de rendez-vous envoyée !');
                return $this->redirectToRoute('front_mentor_show', ['id' => $id]);
            }
        }

        return $this->render('front/mentor/show.html.twig', [
            'mentor'   => $mentor,
            'isOwner'  => $isOwner,
            'formAvis' => $formAvis,
            'formRdv'  => $formRdv,
        ]);
    }
}
