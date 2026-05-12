<?php

namespace App\Controller\Admin;

use App\Entity\RendezVous;
use App\Form\RendezVousType;
use App\Repository\RendezVousRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/rendez-vous')]
#[IsGranted('ROLE_ADMIN')]
class AdminRendezVousController extends AbstractController
{
    #[Route('/', name: 'admin_rendez_vous_index', methods: ['GET'])]
    public function index(RendezVousRepository $repo): Response
    {
        return $this->render('admin/rendez_vous/index.html.twig', [
            'items' => $repo->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_rendez_vous_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $item = new RendezVous();
        $form = $this->createForm(RendezVousType::class, $item, ['is_admin' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($item);
            $em->flush();
            $this->addFlash('success', 'RendezVous créé(e) avec succès.');
            return $this->redirectToRoute('admin_rendez_vous_index');
        }

        return $this->render('admin/rendez_vous/new.html.twig', ['form' => $form, 'item' => $item]);
    }

    #[Route('/{id}', name: 'admin_rendez_vous_show', methods: ['GET'])]
    public function show(RendezVous $item): Response
    {
        return $this->render('admin/rendez_vous/show.html.twig', ['item' => $item]);
    }

    #[Route('/{id}/edit', name: 'admin_rendez_vous_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RendezVous $item, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(RendezVousType::class, $item, ['is_admin' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'RendezVous modifié(e) avec succès.');
            return $this->redirectToRoute('admin_rendez_vous_index');
        }

        return $this->render('admin/rendez_vous/edit.html.twig', ['form' => $form, 'item' => $item]);
    }

    #[Route('/{id}/delete', name: 'admin_rendez_vous_delete', methods: ['POST'])]
    public function delete(Request $request, RendezVous $item, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $item->getId(), $request->request->get('_token'))) {
            $em->remove($item);
            $em->flush();
            $this->addFlash('success', 'RendezVous supprimé(e).');
        }
        return $this->redirectToRoute('admin_rendez_vous_index');
    }
}
