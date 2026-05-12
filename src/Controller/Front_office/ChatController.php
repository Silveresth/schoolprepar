<?php

namespace App\Controller\Front_office;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use App\Repository\MentorRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/chat', name: 'front_chat_')]
class ChatController extends AbstractController
{
    /**
     * Liste uniquement les mentors/conseillers disponibles pour la messagerie.
     */
    #[Route('/', name: 'index')]
    #[IsGranted('ROLE_USER')]
    public function index(MentorRepository $mentorRepo): Response
    {
        $me = $this->getUser();

        // Si c'est un conseiller/mentor qui accède au chat,
        // il voit les messages qu'il a reçus depuis son dashboard.
        // Ici on liste les mentors disponibles pour les étudiants.
        $mentors = $mentorRepo->findAll();

        return $this->render('front/chat/index.html.twig', [
            'mentors' => $mentors,
            'currentUser' => $me,
        ]);
    }

    /**
     * Conversation privée entre l'utilisateur connecté et un mentor/conseiller.
     */
    #[Route('/conversation/{id}', name: 'conversation', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_USER')]
    public function conversation(
        int $id,
        Request $request,
        UtilisateurRepository $userRepo,
        MentorRepository $mentorRepo,
        MessageRepository $messageRepo,
        EntityManagerInterface $em
    ): Response {
        $me = $this->getUser();
        $dest = $userRepo->find($id);

        if (!$dest) {
            throw $this->createNotFoundException('Utilisateur introuvable.');
        }

        // Vérifier que la conversation est entre un étudiant et un conseiller
        $destRoles = $dest->getRoles();
        $isMentorOrConseiller = in_array('ROLE_CONSEILLER', $destRoles) || in_array('ROLE_MENTOR', $destRoles);
        $meIsMentorOrConseiller = $this->isGranted('ROLE_CONSEILLER') || $this->isGranted('ROLE_MENTOR');

        if (!$isMentorOrConseiller && !$meIsMentorOrConseiller) {
            $this->addFlash('warning', 'Vous ne pouvez envoyer des messages qu\'à des conseillers ou mentors.');
            return $this->redirectToRoute('front_chat_index');
        }

        // Récupérer la conversation via le repository optimisé
        $messages = $messageRepo->findConversation($me, $dest);

        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setExpediteur($me);
            $message->setDestinataire($dest);
            $em->persist($message);
            $em->flush();

            return $this->redirectToRoute('front_chat_conversation', ['id' => $dest->getId()]);
        }

        // Trouver le profil mentor du destinataire (si applicable)
        $mentorProfile = $mentorRepo->findByUtilisateur($dest);

        return $this->render('front/chat/conversation.html.twig', [
            'dest'          => $dest,
            'mentorProfile' => $mentorProfile,
            'messages'      => $messages,
            'form'          => $form->createView(),
        ]);
    }
}
