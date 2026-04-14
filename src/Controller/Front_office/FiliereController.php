<?php

namespace App\Controller\Front_office;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FiliereController extends AbstractController
{
    #[Route('/filiere', name: 'filiere')]
    public function index(): Response
    {
        return $this->render('front/filiere/index.html.twig', [
            'filieres' => [
                ['id' => 1, 'nom' => 'Informatique', 'domaine' => 'Sciences',    'etablissement' => 'Lycée Technique',    'duree' => '3 ans'],
                ['id' => 2, 'nom' => 'Médecine',     'domaine' => 'Santé',       'etablissement' => 'Université de Lomé', 'duree' => '7 ans'],
                ['id' => 3, 'nom' => 'Droit',        'domaine' => 'Juridique',   'etablissement' => 'Université de Lomé', 'duree' => '5 ans'],
                ['id' => 4, 'nom' => 'Génie Civil',  'domaine' => 'BTP',         'etablissement' => 'ENSI',               'duree' => '5 ans'],
                ['id' => 5, 'nom' => 'Commerce',     'domaine' => 'Gestion',     'etablissement' => 'ESTAF',              'duree' => '3 ans'],
                ['id' => 6, 'nom' => 'Agronomie',    'domaine' => 'Agriculture', 'etablissement' => 'ESA',                'duree' => '5 ans'],
            ],
        ]);
    }
}