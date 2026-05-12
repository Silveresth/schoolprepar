<?php

namespace App\Controller\Admin;

use App\Entity\Forum;
use App\Form\ForumType;
use App\Repository\ForumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/forum')]
#[IsGranted('ROLE_ADMIN')]
class AdminForumController extends AbstractController
{
    #[Route('/', name: 'admin_forum_index', methods: ['GET'])]
    public function index(ForumRepository $repo): Response
    {
        return $this->render('admin/forum/index.html.twig', [
            'items' => $repo->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_forum_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $item = new Forum();
        $form = $this->createForm(ForumType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($item);
            $em->flush();
            $this->addFlash('success', 'Forum créé(e) avec succès.');
            return $this->redirectToRoute('admin_forum_index');
        }

        return $this->render('admin/forum/new.html.twig', ['form' => $form, 'item' => $item]);
    }

    #[Route('/{id}', name: 'admin_forum_show', methods: ['GET'])]
    public function show(Forum $item): Response
    {
        return $this->render('admin/forum/show.html.twig', ['item' => $item]);
    }

    #[Route('/{id}/edit', name: 'admin_forum_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Forum $item, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ForumType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Forum modifié(e) avec succès.');
            return $this->redirectToRoute('admin_forum_index');
        }

        return $this->render('admin/forum/edit.html.twig', ['form' => $form, 'item' => $item]);
    }

    #[Route('/{id}/delete', name: 'admin_forum_delete', methods: ['POST'])]
    public function delete(Request $request, Forum $item, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $item->getId(), $request->request->get('_token'))) {
            $em->remove($item);
            $em->flush();
            $this->addFlash('success', 'Forum supprimé(e).');
        }
        return $this->redirectToRoute('admin_forum_index');
    }
}
