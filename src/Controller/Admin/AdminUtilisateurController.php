<?php

namespace App\Controller\Admin;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/utilisateur')]
#[IsGranted('ROLE_ADMIN')]
class AdminUtilisateurController extends AbstractController
{
    #[Route('/', name: 'admin_utilisateur_index', methods: ['GET'])]
    public function index(UtilisateurRepository $repo): Response
    {
        return $this->render('admin/utilisateur/index.html.twig', [
            'utilisateurs' => $repo->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_utilisateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur, ['is_new' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('password')->getData();
            if ($plainPassword) {
                $utilisateur->setPassword($hasher->hashPassword($utilisateur, $plainPassword));
            }

            $photoFile = $form->get('photoFile')->getData();
            if ($photoFile) {
                $uploadsDir = $this->getParameter('kernel.project_dir') . '/public/img/avatars';
                if (!is_dir($uploadsDir)) {
                    mkdir($uploadsDir, 0775, true);
                }

                $originalExtension = $photoFile->guessExtension() ?: $photoFile->getClientOriginalExtension();
                $safeExtension = $originalExtension ? strtolower($originalExtension) : 'jpg';
                $newFilename = uniqid('avatar_', true) . '.' . $safeExtension;

                $photoFile->move($uploadsDir, $newFilename);
                $utilisateur->setPhotoFilename($newFilename);
            }

            $em->persist($utilisateur);

            $em->flush();
            $this->addFlash('success', 'Utilisateur créé avec succès.');
            return $this->redirectToRoute('admin_utilisateur_index');
        }

        return $this->render('admin/utilisateur/new.html.twig', [
            'form' => $form,
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route('/{id}', name: 'admin_utilisateur_show', methods: ['GET'])]
    public function show(Utilisateur $utilisateur): Response
    {
        return $this->render('admin/utilisateur/show.html.twig', ['utilisateur' => $utilisateur]);
    }

    #[Route('/{id}/edit', name: 'admin_utilisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateur $utilisateur, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        $form = $this->createForm(UtilisateurType::class, $utilisateur, ['is_new' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('password')->getData();
            if ($plainPassword) {
                $utilisateur->setPassword($hasher->hashPassword($utilisateur, $plainPassword));
            }

            $photoFile = $form->get('photoFile')->getData();
            if ($photoFile) {
                $uploadsDir = $this->getParameter('kernel.project_dir') . '/public/img/avatars';
                if (!is_dir($uploadsDir)) {
                    mkdir($uploadsDir, 0775, true);
                }

                $originalExtension = $photoFile->guessExtension() ?: $photoFile->getClientOriginalExtension();
                $safeExtension = $originalExtension ? strtolower($originalExtension) : 'jpg';
                $newFilename = uniqid('avatar_', true) . '.' . $safeExtension;

                $photoFile->move($uploadsDir, $newFilename);
                $utilisateur->setPhotoFilename($newFilename);
            }

            $em->flush();

            $this->addFlash('success', 'Utilisateur modifié.');
            return $this->redirectToRoute('admin_utilisateur_index');
        }

        return $this->render('admin/utilisateur/edit.html.twig', [
            'form' => $form,
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_utilisateur_delete', methods: ['POST'])]
    public function delete(Request $request, Utilisateur $utilisateur, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $utilisateur->getId(), $request->request->get('_token'))) {
            $em->remove($utilisateur);
            $em->flush();
            $this->addFlash('success', 'Utilisateur supprimé.');
        }
        return $this->redirectToRoute('admin_utilisateur_index');
    }
}
