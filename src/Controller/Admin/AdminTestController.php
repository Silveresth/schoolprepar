<?php

namespace App\Controller\Admin;

use App\Entity\Test;
use App\Form\TestType;
use App\Repository\TestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/test')]
#[IsGranted('ROLE_ADMIN')]
class AdminTestController extends AbstractController
{
    #[Route('/', name: 'admin_test_index', methods: ['GET'])]
    public function index(TestRepository $repo): Response
    {
        return $this->render('admin/test/index.html.twig', [
            'items' => $repo->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_test_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $item = new Test();
        $form = $this->createForm(TestType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($item);
            $em->flush();
            $this->addFlash('success', 'Test créé(e) avec succès.');
            return $this->redirectToRoute('admin_test_index');
        }

        return $this->render('admin/test/new.html.twig', ['form' => $form, 'item' => $item]);
    }

    #[Route('/{id}', name: 'admin_test_show', methods: ['GET'])]
    public function show(Test $item): Response
    {
        return $this->render('admin/test/show.html.twig', ['item' => $item]);
    }

    #[Route('/{id}/edit', name: 'admin_test_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Test $item, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TestType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Test modifié(e) avec succès.');
            return $this->redirectToRoute('admin_test_index');
        }

        return $this->render('admin/test/edit.html.twig', ['form' => $form, 'item' => $item]);
    }

    #[Route('/{id}/delete', name: 'admin_test_delete', methods: ['POST'])]
    public function delete(Request $request, Test $item, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $item->getId(), $request->request->get('_token'))) {
            $em->remove($item);
            $em->flush();
            $this->addFlash('success', 'Test supprimé(e).');
        }
        return $this->redirectToRoute('admin_test_index');
    }
}
