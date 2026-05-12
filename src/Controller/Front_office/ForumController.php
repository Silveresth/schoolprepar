<?php

namespace App\Controller\Front_office;

use App\Entity\Forum;
use App\Entity\ForumReponse;
use App\Form\ForumType;
use App\Form\ForumReponseType;
use App\Repository\FiliereRepository;
use App\Repository\ForumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/forum', name: 'front_forum_')]
class ForumController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ForumRepository $forumRepo, FiliereRepository $filiereRepo, Request $request): Response
    {
        $filiere = $request->query->get('filiere');
        $forums = $filiere
            ? $forumRepo->findByFiliereName($filiere)
            : $forumRepo->findBy([], ['createdAt' => 'DESC']);

        return $this->render('front/forum/index.html.twig', [
            'forums'   => $forums,
            'filieres' => $filiereRepo->findAll(),
            'filtreActif' => $filiere,
        ]);
    }

    #[Route('/nouveau', name: 'new')]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $forum = new Forum();
        $form = $this->createForm(ForumType::class, $forum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $forum->setAuteur($this->getUser());
            $em->persist($forum);
            $em->flush();
            $this->addFlash('success', 'Sujet publié avec succès !');
            return $this->redirectToRoute('front_forum_show', ['id' => $forum->getId()]);
        }

        return $this->render('front/forum/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(int $id, ForumRepository $forumRepo, Request $request, EntityManagerInterface $em): Response
    {
        $forum = $forumRepo->find($id);
        if (!$forum) {
            throw $this->createNotFoundException('Sujet introuvable.');
        }

        $reponse = new ForumReponse();
        $form = null;

        if ($this->getUser()) {
            $form = $this->createForm(ForumReponseType::class, $reponse);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $reponse->setForum($forum);
                $reponse->setAuteur($this->getUser());
                $em->persist($reponse);
                $em->flush();
                $this->addFlash('success', 'Réponse publiée !');
                return $this->redirectToRoute('front_forum_show', ['id' => $id, '_fragment' => 'reponses']);
            }
        }

        return $this->render('front/forum/show.html.twig', [
            'forum' => $forum,
            'form'  => $form?->createView(),
        ]);
    }
}
