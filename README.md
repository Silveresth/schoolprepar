# SchoolPrepar — TP3 Symfony

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

## Compte admin de test
- Email : `admin@gmail.com`
- Mot de passe : `password`

