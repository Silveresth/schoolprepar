<?php

namespace App\DataFixtures;

use App\Entity\Etablissement;
use App\Entity\Filiere;
use App\Entity\Evenement;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $manager): void
    {
        // 1. Établissements (5 minimum requis)
        $etablissements = [];
        $data = [
            ['IPNET Institute of Technology', 'Avenue des Nations Unies, Lomé', 'Lomé'],
            ['Université de Lomé', 'Boulevard du 13 Janvier, Lomé', 'Lomé'],
            ['IAEC Togo', 'Rue du Commerce, Lomé', 'Lomé'],
            ['ESA Togo', 'Quartier Agoe, Lomé', 'Lomé'],
            ['EUREKA Togo', 'Agbalepedogan, Lomé', 'Lomé'],
        ];
        foreach ($data as [$nom, $adresse, $ville]) {
            $e = new Etablissement();
            $e->setNom($nom)->setAdresse($adresse)->setVille($ville);
            $manager->persist($e);
            $etablissements[] = $e;
        }

        // 2. Filières (5 minimum, reliées à des établissements)
        $filieres = [];
        $fData = [
            ['Génie Logiciel', 'Formation en développement logiciel et génie informatique.', 'Développeur, Ingénieur logiciel', 'Baccalauréat série C ou D', 0],
            ['Réseaux & Télécommunications', 'Formation en infrastructure réseau et télécoms.', 'Ingénieur réseau, Technicien télécoms', 'Baccalauréat série C', 0],
            ['Web & Intégration Multimédia', 'Design web, développement front/back.', 'Développeur web, UX Designer', 'Baccalauréat toutes séries', 0],
            ['Management des Systèmes d\'Information', 'Gestion des SI et management.', 'Chef de projet IT, Consultant', 'Baccalauréat série A ou G', 1],
            ['CyberSécurité', 'Protection des systèmes et des données.', 'Expert sécurité, Analyste SOC', 'Baccalauréat série C', 1],
        ];
        foreach ($fData as [$nom, $desc, $debouche, $niveau, $etabIdx]) {
            $f = new Filiere();
            $f->setNom($nom)->setDescription($desc)->setDebouche($debouche)
              ->setNiveauRequis($niveau)->setEtablissement($etablissements[$etabIdx]);
            $manager->persist($f);
            $filieres[] = $f;
        }

        // 3. Événements (5 minimum, avec relation N:N filières et établissement)
        $evData = [
            ['Journée Portes Ouvertes IPNET', 'Découvrez nos formations et rencontrez les formateurs.', '+3 days', '+3 days +6 hours', 'Campus IPNET, Lomé', 0],
            ['Conférence Orientation Bac', 'Comment choisir sa filière après le bac ?', '+7 days', '+7 days +3 hours', 'Université de Lomé, Amphi A', 1],
            ['Salon des Métiers du Numérique', 'Rencontrez les professionnels du secteur IT.', '+14 days', '+14 days +8 hours', 'Palais des Congrès, Lomé', 2],
            ['Atelier CV et Insertion Pro', 'Préparez votre insertion professionnelle.', '+21 days', '+21 days +2 hours', 'IAEC Togo, Salle B', 2],
            ['Hackathon SchoolPrepar 2026', 'Compétition de programmation 24h.', '+30 days', '+31 days', 'Campus IPNET, Lab Info', 0],
        ];
        foreach ($evData as [$titre, $desc, $debut, $fin, $lieu, $etabIdx]) {
            $ev = new Evenement();
            $ev->setTitre($titre)->setDescription($desc)
               ->setDateDebut(new \DateTime($debut))->setDateFin(new \DateTime($fin))
               ->setLieu($lieu)->setEtablissement($etablissements[$etabIdx]);
            // Ajoute 2 filières à chaque événement (relation N:N)
            $ev->addFiliere($filieres[0]);
            $ev->addFiliere($filieres[array_rand($filieres)]);
            $manager->persist($ev);
        }

        // 4. Utilisateurs (5 minimum avec hash de mot de passe)
        $users = [
            ['Koffi', 'Ama', 'ama.koffi@schoolprepar.tg', 'ROLE_ADMIN'],
            ['Mensah', 'Yao', 'yao.mensah@schoolprepar.tg', 'ROLE_USER'],
            ['Agbe', 'Kossi', 'kossi.agbe@schoolprepar.tg', 'ROLE_USER'],
            ['Dogbe', 'Efua', 'efua.dogbe@schoolprepar.tg', 'ROLE_CONSEILLER'],
            ['Attivor', 'Mawuli', 'mawuli.attivor@schoolprepar.tg', 'ROLE_USER'],
        ];
        foreach ($users as [$nom, $prenom, $email, $role]) {
            $u = new Utilisateur();
            $u->setNom($nom)->setPrenom($prenom)->setEmail($email)->setRole($role);
            $u->setPassword($this->hasher->hashPassword($u, 'password'));
            $manager->persist($u);
        }

        $manager->flush();
    }
}
