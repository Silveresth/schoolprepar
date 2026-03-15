<?php

namespace App\Controller\Front_office;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EtablissementController extends AbstractController
{
    #[Route('/etablissement', name: 'etablissement')]
    public function index(): Response
    {
        return $this->render('front/etablissement/index.html.twig', [
            'etablissements' => [
                ['id' => 1, 'nom' => 'Lycée Technique',    'type' => 'Lycée',       'ville' => 'Lomé'],
                ['id' => 2, 'nom' => 'Université de Lomé', 'type' => 'Université',  'ville' => 'Lomé'],
                ['id' => 3, 'nom' => 'ENSI',               'type' => 'Grande école','ville' => 'Lomé'],
                ['id' => 4, 'nom' => 'ESTAF',              'type' => 'Institut',    'ville' => 'Lomé'],
                ['id' => 5, 'nom' => 'ESA',                'type' => 'Grande école','ville' => 'Kpalimé'],
            ],
        ]);
    }
}