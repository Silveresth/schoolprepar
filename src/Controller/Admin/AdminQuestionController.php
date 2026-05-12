<?php

namespace App\Controller\Admin;

use App\Entity\Question;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/question')]
#[IsGranted('ROLE_ADMIN')]
class AdminQuestionController extends AbstractController
{
    #[Route('/', name: 'admin_question_index', methods: ['GET'])]
    public function index(QuestionRepository $repo): Response
    {
        return $this->render('admin/question/index.html.twig', [
            'items' => $repo->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_question_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $item = new Question();
        $form = $this->createForm(QuestionType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($item);
            $em->flush();
            $this->addFlash('success', 'Question créé(e) avec succès.');
            return $this->redirectToRoute('admin_question_index');
        }

        return $this->render('admin/question/new.html.twig', ['form' => $form, 'item' => $item]);
    }

    #[Route('/{id}', name: 'admin_question_show', methods: ['GET'])]
    public function show(Question $item): Response
    {
        return $this->render('admin/question/show.html.twig', ['item' => $item]);
    }

    #[Route('/{id}/edit', name: 'admin_question_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Question $item, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(QuestionType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Question modifié(e) avec succès.');
            return $this->redirectToRoute('admin_question_index');
        }

        return $this->render('admin/question/edit.html.twig', ['form' => $form, 'item' => $item]);
    }

    #[Route('/{id}/delete', name: 'admin_question_delete', methods: ['POST'])]
    public function delete(Request $request, Question $item, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $item->getId(), $request->request->get('_token'))) {
            $em->remove($item);
            $em->flush();
            $this->addFlash('success', 'Question supprimé(e).');
        }
        return $this->redirectToRoute('admin_question_index');
    }
}
