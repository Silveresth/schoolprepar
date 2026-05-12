<?php

namespace App\Controller\Admin;

use App\Entity\Avis;
use App\Form\AvisType;
use App\Repository\AvisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/avis')]
#[IsGranted('ROLE_ADMIN')]
class AdminAvisController extends AbstractController
{
    #[Route('/', name: 'admin_avis_index', methods: ['GET'])]
    public function index(AvisRepository $repo): Response
    {
        return $this->render('admin/avis/index.html.twig', [
            'items' => $repo->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_avis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $item = new Avis();
        $form = $this->createForm(AvisType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($item);
            $em->flush();
            $this->addFlash('success', 'Avis créé(e) avec succès.');
            return $this->redirectToRoute('admin_avis_index');
        }

        return $this->render('admin/avis/new.html.twig', ['form' => $form, 'item' => $item]);
    }

    #[Route('/{id}', name: 'admin_avis_show', methods: ['GET'])]
    public function show(Avis $item): Response
    {
        return $this->render('admin/avis/show.html.twig', ['item' => $item]);
    }

    #[Route('/{id}/edit', name: 'admin_avis_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Avis $item, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AvisType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Avis modifié(e) avec succès.');
            return $this->redirectToRoute('admin_avis_index');
        }

        return $this->render('admin/avis/edit.html.twig', ['form' => $form, 'item' => $item]);
    }

    #[Route('/{id}/delete', name: 'admin_avis_delete', methods: ['POST'])]
    public function delete(Request $request, Avis $item, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $item->getId(), $request->request->get('_token'))) {
            $em->remove($item);
            $em->flush();
            $this->addFlash('success', 'Avis supprimé(e).');
        }
        return $this->redirectToRoute('admin_avis_index');
    }
}
