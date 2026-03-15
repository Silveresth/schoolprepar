<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminDashboardController extends AbstractController
{
    #[Route('/', name: 'admin_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'stats' => [
                'total_filieres'       => 12,
                'total_etablissements' => 8,
                'total_eleves'         => 145,
                'total_conseillers'    => 5,
                'nouvelles_filieres'   => 2,
                'nouveaux_eleves'      => 10,
            ],
            'dernieres_filieres' => [
                ['id' => 1, 'nom' => 'Informatique', 'domaine' => 'Sciences',   'etablissement' => 'Lycée Technique'],
                ['id' => 2, 'nom' => 'Médecine',     'domaine' => 'Santé',      'etablissement' => 'Université de Lomé'],
                ['id' => 3, 'nom' => 'Droit',        'domaine' => 'Juridique',  'etablissement' => 'Université de Lomé'],
                ['id' => 4, 'nom' => 'Génie Civil',  'domaine' => 'BTP',        'etablissement' => 'ENSI'],
            ],
            'derniers_eleves' => [
                ['nom' => 'Kofi',   'prenom' => 'Ama',   'email' => 'ama.kofi@email.com'],
                ['nom' => 'Mensah', 'prenom' => 'Yao',   'email' => 'yao.mensah@email.com'],
                ['nom' => 'Agbe',   'prenom' => 'Kossi', 'email' => 'kossi.agbe@email.com'],
            ],
        ]);
    }
}