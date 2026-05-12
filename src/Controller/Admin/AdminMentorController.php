<?php

namespace App\Controller\Admin;

use App\Entity\Mentor;
use App\Form\MentorType;
use App\Repository\MentorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/mentor')]
#[IsGranted('ROLE_ADMIN')]
class AdminMentorController extends AbstractController
{
    #[Route('/', name: 'admin_mentor_index', methods: ['GET'])]
    public function index(MentorRepository $repo): Response
    {
        return $this->render('admin/mentor/index.html.twig', [
            'mentors' => $repo->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_mentor_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $mentor = new Mentor();
        $form = $this->createForm(MentorType::class, $mentor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($mentor);
            $em->flush();
            $this->addFlash('success', 'Mentor créé avec succès.');
            return $this->redirectToRoute('admin_mentor_index');
        }

        return $this->render('admin/mentor/new.html.twig', [
            'form' => $form,
            'mentor' => $mentor,
        ]);
    }

    #[Route('/{id}', name: 'admin_mentor_show', methods: ['GET'])]
    public function show(Mentor $mentor): Response
    {
        return $this->render('admin/mentor/show.html.twig', ['mentor' => $mentor]);
    }

    #[Route('/{id}/edit', name: 'admin_mentor_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mentor $mentor, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(MentorType::class, $mentor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Mentor modifié avec succès.');
            return $this->redirectToRoute('admin_mentor_index');
        }

        return $this->render('admin/mentor/edit.html.twig', [
            'form' => $form,
            'mentor' => $mentor,
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_mentor_delete', methods: ['POST'])]
    public function delete(Request $request, Mentor $mentor, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $mentor->getId(), $request->request->get('_token'))) {
            $em->remove($mentor);
            $em->flush();
            $this->addFlash('success', 'Mentor supprimé.');
        }
        return $this->redirectToRoute('admin_mentor_index');
    }
}
