<?php

namespace App\Controller\Admin;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/message')]
#[IsGranted('ROLE_ADMIN')]
class AdminMessageController extends AbstractController
{
    #[Route('/', name: 'admin_message_index', methods: ['GET'])]
    public function index(MessageRepository $repo): Response
    {
        return $this->render('admin/message/index.html.twig', [
            'items' => $repo->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_message_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $item = new Message();
        $form = $this->createForm(MessageType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($item);
            $em->flush();
            $this->addFlash('success', 'Message créé(e) avec succès.');
            return $this->redirectToRoute('admin_message_index');
        }

        return $this->render('admin/message/new.html.twig', ['form' => $form, 'item' => $item]);
    }

    #[Route('/{id}', name: 'admin_message_show', methods: ['GET'])]
    public function show(Message $item): Response
    {
        return $this->render('admin/message/show.html.twig', ['item' => $item]);
    }

    #[Route('/{id}/edit', name: 'admin_message_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Message $item, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(MessageType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Message modifié(e) avec succès.');
            return $this->redirectToRoute('admin_message_index');
        }

        return $this->render('admin/message/edit.html.twig', ['form' => $form, 'item' => $item]);
    }

    #[Route('/{id}/delete', name: 'admin_message_delete', methods: ['POST'])]
    public function delete(Request $request, Message $item, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $item->getId(), $request->request->get('_token'))) {
            $em->remove($item);
            $em->flush();
            $this->addFlash('success', 'Message supprimé(e).');
        }
        return $this->redirectToRoute('admin_message_index');
    }
}
