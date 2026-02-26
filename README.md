# Titre: SchoolPrepar


# Documentation Symfony : https://symfony.com

## Présentation du Projet

SchoolPrepar est une plateforme numérique innovante conçue pour accompagner les élèves dans leur orientation scolaire et professionnelle. Elle propose un accès centralisé à des informations fiables sur les filières d'études, les établissements scolaires, les débouchés professionnels, et met les utilisateurs en relation avec des conseillers d'orientation, des anciens élèves et des professionnels du métier.

### Vision

Créer une communauté éducative où chaque jeune peut trouver les ressources et le soutien nécessaires pour faire des choix éclairés concernant son avenir académique et professionnel.

## Objectifs du Projet

- Centraliser toutes les informations sur les filières, établissements et débouchés professionnels
- Accompagner les élèves grâce à des conseillers d'orientation, des anciens élèves et des professionnels
- Créer une communauté éducative dynamique autour de l'orientation scolaire

## Fonctionnalités Clés

### Informations sur les Filières et Établissements

- Moteur de recherche intelligent : Recherche par domaine, niveau, école, localisation
- Fiches détaillées par filière incluant :
  - Description complète
  - Écoles/universités correspondantes
  - Conditions d'admission
  - Débouchés professionnels
  - Localisation

### Accompagnement & Mentorat

- Répertoire de conseillers comprenant les anciens élèves et professionnels
- Profils personnalisés avec expertise, disponibilité et options de contact
- Chat: visioconférence et email
- Prise de rendez-vous automatique avec intégration calendrier
- Système de notation et feedback

### Recommandations Personnalisées

- Algorithme intelligent basé sur les interactions utilisateurs
- Recommandations de parcours, conseillers, écoles et événements

### Événements & Webinaires

- Calendrier dynamique des événements
- Notifications
- Replay des événements passés

### Système de Communication

- Chat interne entre élèves et conseillers
- Notifications temps réel
- Forum communautaire par filière

### Back-office d'Administration

- Gestion CRUD complète : filières, écoles, comptes utilisateurs, événements
- Statistiques de fréquentations
- Gestion des contenus

### Outils d'Auto-évaluation

- Tests de personnalité
- Quiz d'orientation
- Générateur de parcours personnalisé


## Structure du Projet

schoolprepar/
├── config/                
│   ├── packages/           
│   ├── routes/             
│   ├── bundles.php
│   ├── framework.yaml
│   ├── services.yaml
│   └── twig.yaml
├── public/                
│   └── index.php        
├── src/               
│   ├── Controller/      
│   │   ├── HomeController.php
│   │   └── OrientationController.php
│   └── Kernel.php
├── templates/             
│   ├── base.html.twig
│   ├── home/
│   │   └── index.html.twig
│   └── orientation/
│       └── quiz.html.twig
├── var/               
├── bin/                 
├── composer.json         
├── symfony.lock
└── README.md              

## Installation et Configuration

### Prérequis

- PHP 8.2 ou supérieur
- Composer
- Wamp server(Server Apache)

### Étapes d'installation

1 Cloner le projet

   git clone <url-du-projet>
   cd schoolprepar

2 Installer les dépendances

   composer install

3 Configurer les variables d'environnement
   
   cp .env .env.local

5 Installer les assets

   php bin/console assets:install

6 Lancer le serveur de développement

   php -S localhost:8000 -t public
   ou avec Symfony CLI
   symfony server:start
   
## Utilisation

### Page d'accueil

Accédez à la page d'accueil pour découvrir les statistiques du projet :

http://localhost:8000/

### Quiz d'orientation

Accédez au quiz d'orientation pour évaluer vos intérêts et compétences :

http://localhost:8000/orientation/quiz


## Roadmap

- [ ] Implémentation du moteur de recherche intelligent
- [ ] Création du système de gestion des filières et établissements
- [ ] Développement du module de mentorat
- [ ] Intégration du système de rendez-vous
- [ ] Implémentation des recommandations personnalisées
- [ ] Création du système de communication (chat, forum)
- [ ] Développement du back-office d'administration
- [ ] Intégration des outils d'auto-évaluation
- [ ] Système de notifications temps réel

