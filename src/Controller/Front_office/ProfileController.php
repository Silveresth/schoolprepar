<?php

namespace App\Controller\Front_office;

use App\Entity\Mentor;
use App\Form\ProfileType;
use App\Form\MentorType;
use App\Repository\MentorRepository;
use App\Repository\RendezVousRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/profile', name: 'front_profile_')]
#[IsGranted('ROLE_USER')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(MentorRepository $mentorRepo, RendezVousRepository $rdvRepo, MessageRepository $messageRepo): Response
    {
        $user = $this->getUser();
        return $this->render('front/profile/index.html.twig', [
            'user'          => $user,
            'mentorProfile' => $mentorRepo->findByUtilisateur($user),
            'mesRdv'        => $rdvRepo->findByEleve($user),
            'messagesRecus' => $messageRepo->findByDestinataire($user, 5),
        ]);
    }

    #[Route('/modifier', name: 'edit')]
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Votre profil a été mis à jour.');
            return $this->redirectToRoute('front_profile_index');
        }

        return $this->render('front/profile/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/conseiller/modifier', name: 'mentor_edit')]
    #[IsGranted('ROLE_CONSEILLER')]
    public function mentorEdit(Request $request, EntityManagerInterface $em, MentorRepository $mentorRepo): Response
    {
        $user = $this->getUser();
        $mentor = $mentorRepo->findByUtilisateur($user);

        if (!$mentor) {
            // Créer le profil si inexistant
            $mentor = new Mentor();
            $mentor->setUtilisateur($user);
            $mentor->setBio('À compléter.');
            $mentor->setSpecialite('Non définie');
            $mentor->setTarif(0);
            $em->persist($mentor);
            $em->flush();
        }

        $form = $this->createForm(MentorType::class, $mentor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Votre profil conseiller a été mis à jour.');
            return $this->redirectToRoute('front_profile_index');
        }

        return $this->render('front/profile/mentor_edit.html.twig', [
            'form'   => $form->createView(),
            'mentor' => $mentor,
        ]);
    }
}
