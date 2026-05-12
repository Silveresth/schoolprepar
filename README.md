# SchoolPrepar — TP4 Symfony

## Installation

```bash
# 1. Cloner / dézipper le projet
cd schoolprepar

# 2. Installer les dépendances
composer install

# 3. Configurer la base de données dans .env
# DATABASE_URL="postgresql://postgres:VOTRE_MDP@127.0.0.1:5432/scp?serverVersion=16&charset=utf8"

# 4. Créer la base de données
php bin/console doctrine:database:create

# 5. Exécuter les migrations
php bin/console doctrine:migrations:migrate

# 6. Charger les données de test
php bin/console doctrine:fixtures:load

# 7. Lancer le serveur
symfony server:start
# ou
php -S localhost:8000 -t public/
```

## Accès

| URL | Description |
|---|---|
| `http://localhost:8000/` | Site public (Material Kit) |
| `http://localhost:8000/filiere` | Liste des filières (front) |
| `http://localhost:8000/etablissement` | Liste des établissements (front) |
| `http://localhost:8000/admin/` | Back-office (AdminKit) |
| `http://localhost:8000/login` | Connexion admin |

## Compte admin de test
- Email : `ama.koffi@schoolprepar.tg`
- Mot de passe : `password`


