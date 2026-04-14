<?php

namespace App\Controller\Front_office;

use App\Repository\FiliereRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FiliereController extends AbstractController
{
    #[Route('/filiere', name: 'filiere')]
    public function index(FiliereRepository $filiereRepository): Response
    {
        return $this->render('front/filiere/index.html.twig', [
            'filieres' => $filiereRepository->findAll(),
        ]);
    }
}
