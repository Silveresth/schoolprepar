# Résolution de l'erreur de migration

## Erreur rencontrée

```
SQLSTATE[42P07]: Duplicate table: ERREUR: la relation « etablissement » existe déjà
```

## Cause

Doctrine essaie d'exécuter une migration qui recrée des tables déjà existantes
(la migration `Version20260413140657` a déjà été exécutée, mais n'est pas
enregistrée dans la table `doctrine_migration_versions`).

## Solution — 3 étapes

### Étape 1 : Marquer les anciennes migrations comme déjà exécutées

```bash
php bin/console doctrine:migrations:version \
    --add "DoctrineMigrations\Version20260413140657"

php bin/console doctrine:migrations:version \
    --add "DoctrineMigrations\Version20260413142002"
```

### Étape 2 : Supprimer la migration générée automatiquement par make:migration

Si vous avez un fichier `VersionXXXXXX030219.php` (généré par `make:migration`),
supprimez-le car il recrée tout depuis zéro :

```bash
rm migrations/Version20260414030219.php
```

### Étape 3 : Exécuter seulement la migration corrective

```bash
php bin/console doctrine:migrations:migrate
```

Cette migration (`Version20260414_TP3_corrections.php`) utilise des
instructions **sécurisées** (`IF NOT EXISTS`, blocs `DO $$`) qui
ne plantent pas si les colonnes/tables existent déjà.

---

## Vérification finale

```bash
# Vérifie que toutes les migrations sont "migrated"
php bin/console doctrine:migrations:status

# Charge les données de test
php bin/console doctrine:fixtures:load

# Lance le serveur
symfony server:start
```

## Compte de test
- Email : `ama.koffi@schoolprepar.tg`
- Mot de passe : `password`
