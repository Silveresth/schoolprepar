<?php

namespace App\Controller\Admin;

use App\Entity\Etablissement;
use App\Form\EtablissementType;
use App\Repository\EtablissementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/etablissement')]
class AdminEtablissementController extends AbstractController
{
    #[Route('/', name: 'admin_etablissement_index', methods: ['GET'])]
    public function index(EtablissementRepository $repo): Response
    {
        return $this->render('admin/etablissement/index.html.twig', [
            'etablissements' => $repo->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_etablissement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $etablissement = new Etablissement();
        $form = $this->createForm(EtablissementType::class, $etablissement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($etablissement);
            $em->flush();
            $this->addFlash('success', 'Établissement créé avec succès.');
            return $this->redirectToRoute('admin_etablissement_index');
        }

        return $this->render('admin/etablissement/new.html.twig', [
            'form' => $form,
            'etablissement' => $etablissement,
        ]);
    }

    #[Route('/{id}', name: 'admin_etablissement_show', methods: ['GET'])]
    public function show(Etablissement $etablissement): Response
    {
        return $this->render('admin/etablissement/show.html.twig', ['etablissement' => $etablissement]);
    }

    #[Route('/{id}/edit', name: 'admin_etablissement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Etablissement $etablissement, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(EtablissementType::class, $etablissement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Établissement modifié.');
            return $this->redirectToRoute('admin_etablissement_index');
        }

        return $this->render('admin/etablissement/edit.html.twig', [
            'form' => $form,
            'etablissement' => $etablissement,
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_etablissement_delete', methods: ['POST'])]
    public function delete(Request $request, Etablissement $etablissement, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $etablissement->getId(), $request->request->get('_token'))) {
            $em->remove($etablissement);
            $em->flush();
            $this->addFlash('success', 'Établissement supprimé.');
        }
        return $this->redirectToRoute('admin_etablissement_index');
    }
}
