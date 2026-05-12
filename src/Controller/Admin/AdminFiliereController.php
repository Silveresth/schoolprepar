<?php

namespace App\Controller\Admin;

use App\Entity\Filiere;
use App\Form\FiliereType;
use App\Repository\FiliereRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin/filiere')]
class AdminFiliereController extends AbstractController
{
    #[Route('/', name: 'admin_filiere_index', methods: ['GET'])]
    public function index(FiliereRepository $filiereRepository): Response
    {
        return $this->render('admin/filiere/index.html.twig', [
            'filieres' => $filiereRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_filiere_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $filiere = new Filiere();
        $form = $this->createForm(FiliereType::class, $filiere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $uploadsDir = $this->getParameter('kernel.project_dir') . '/public/img/photos';
                if (!is_dir($uploadsDir)) {
                    mkdir($uploadsDir, 0775, true);
                }

                $originalExtension = $imageFile->guessExtension() ?: $imageFile->getClientOriginalExtension();
                $safeExtension = $originalExtension ? strtolower($originalExtension) : 'jpg';
                $newFilename = uniqid('fili_', true) . '.' . $safeExtension;

                $imageFile->move($uploadsDir, $newFilename);
                $filiere->setImageFilename($newFilename);
            }

            $em->persist($filiere);
            $em->flush();

            $this->addFlash('success', 'Filière créée avec succès.');
            return $this->redirectToRoute('admin_filiere_index');
        }

        return $this->render('admin/filiere/new.html.twig', [
            'form' => $form,
            'filiere' => $filiere,
        ]);
    }

    #[Route('/{id}', name: 'admin_filiere_show', methods: ['GET'])]
    public function show(Filiere $filiere): Response
    {
        return $this->render('admin/filiere/show.html.twig', ['filiere' => $filiere]);
    }

    #[Route('/{id}/edit', name: 'admin_filiere_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Filiere $filiere, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(FiliereType::class, $filiere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $uploadsDir = $this->getParameter('kernel.project_dir') . '/public/img/photos';
                if (!is_dir($uploadsDir)) {
                    mkdir($uploadsDir, 0775, true);
                }

                $originalExtension = $imageFile->guessExtension() ?: $imageFile->getClientOriginalExtension();
                $safeExtension = $originalExtension ? strtolower($originalExtension) : 'jpg';
                $newFilename = uniqid('fili_', true) . '.' . $safeExtension;

                $imageFile->move($uploadsDir, $newFilename);
                $filiere->setImageFilename($newFilename);
            }

            $em->flush();
            $this->addFlash('success', 'Filière modifiée avec succès.');

            return $this->redirectToRoute('admin_filiere_index');
        }

        return $this->render('admin/filiere/edit.html.twig', [
            'form' => $form,
            'filiere' => $filiere,
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_filiere_delete', methods: ['POST'])]
    public function delete(Request $request, Filiere $filiere, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $filiere->getId(), $request->request->get('_token'))) {
            $em->remove($filiere);
            $em->flush();
            $this->addFlash('success', 'Filière supprimée.');
        }
        return $this->redirectToRoute('admin_filiere_index');
    }
}
