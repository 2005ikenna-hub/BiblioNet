# BiblioNet

Application de gestion de bibliothèque en PHP 8, MySQL et architecture MVC sans framework.

## Installation locale

### Prérequis

- PHP 8.x avec `PDO` et `pdo_mysql`
- MySQL 5.7+ ou MariaDB
- Apache avec `mod_rewrite` (XAMPP, Laragon ou Wamp)

### Étapes

1. Cloner le dépôt et ouvrir son dossier :

   ```bash
   git clone <url-du-depot>
   cd BiblioNet
   ```

2. Créer la base et charger le jeu de démonstration :

   ```bash
   mysql -u root -p < database/biblionet.sql
   ```

   Le script crée la base `biblionet`, 18 livres, 10 adhérents et des emprunts de démonstration. Il peut aussi être importé avec phpMyAdmin.

3. Copier `.env.example` vers `.env` (PowerShell : `Copy-Item .env.example .env`) puis ajuster `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` et `APP_URL`. Ne jamais publier `.env`.

4. Configurer Apache pour servir `public/`. Avec XAMPP, l’URL locale par défaut est `http://localhost/BiblioNet/public/`.

5. Pour une base créée avant la migration des années, appliquer une seule fois :

   ```bash
   mysql -u root -p biblionet < database/migrations/001_annee_publication_smallint.sql
   ```

6. Pour une base existante, activer les images avec la migration `database/migrations/002_images_livres_adherents.sql`. Le dossier `public/uploads/` doit être accessible en écriture par Apache; les images sont limitées à JPEG, PNG ou WebP, 5 Mo maximum. Les fichiers téléversés ne doivent pas être ajoutés au dépôt.

### Compte de démonstration

- Adresse : `admin@biblionet.com`
- Mot de passe : `Admin1234`

Changer ou supprimer ce compte avant toute mise en production.

## Fonctionnalités

- Catalogue public avec recherche par titre ou auteur et disponibilité calculée.
- Authentification bibliothécaire par session et mot de passe haché.
- CRUD des livres et des adhérents.
- Images facultatives pour les couvertures et photos, avec aperçu, validation du format et affichage dans les listes et le catalogue.
- Enregistrement des emprunts et des retours, historique et signalement des retards après 14 jours.
- Refus d’un emprunt lorsque le stock est épuisé; le contrôle est fait dans une transaction côté modèle.
- Actions de gestion protégées par POST et jeton CSRF; données affichées échappées.

## Vérifications locales

```bash
php tests/emprunt_model_test.php
```

Le test utilise des tables temporaires MySQL et ne modifie pas les enregistrements persistants. Il vérifie la création, le refus hors stock, le retour, le refus d’un double retour, le rétablissement du stock et l’historique.

## Architecture

- `public/index.php` : front controller et routes
- `app/controllers/` : orchestration des requêtes et validation
- `app/models/` : accès PDO et règles métier
- `app/views/` : rendu HTML sans requêtes SQL
- `app/core/` : routeur, contrôleur de base et connexion PDO
- `config/config.php` : configuration chargée depuis `.env`
- `database/` : schéma, données de démonstration et migration

La racine web doit pointer vers `public/`; le fichier `.env` reste à la racine du projet, hors du dossier exposé.

## Préparation du déploiement

1. Créer une base et un utilisateur MySQL sur l’hébergement, puis importer `database/biblionet.sql`.
2. Transférer le projet et configurer la racine du domaine vers `public/`.
3. Créer `.env` sur le serveur avec les paramètres de production et l’URL HTTPS; ne pas le committer.
4. Vérifier que `public/uploads/` est accessible en écriture par PHP, activer HTTPS et désactiver `display_errors` en production (`APP_ENV=production`).
5. Tester la connexion, les CRUD, la recherche, les emprunts et les retours sur l’URL publique; changer le compte de démonstration.

État au 22 octobre 2026 : l’application et les parcours J3 ont été vérifiés en local. Le déploiement sur un hébergeur et le test d’une URL publique restent à effectuer avec les accès de l’équipe.

## Déploiement automatique sur InfinityFree

Le workflow `.github/workflows/deploy-infinityfree.yml` envoie le site par FTPS après chaque push sur `main` et peut aussi être lancé manuellement depuis l’onglet Actions. Le dépôt GitHub ne contient jamais les identifiants FTP ni le fichier `.env`.

Dans **Settings > Secrets and variables > Actions**, ajouter les secrets `INFINITYFREE_FTP_USERNAME` et `INFINITYFREE_FTP_PASSWORD`, puis la variable `INFINITYFREE_FTP_DIRECTORY` avec le dossier web indiqué par les détails FTP InfinityFree (généralement `htdocs/`). Le workflow utilise `ftpupload.net`, FTPS explicite sur le port 21. Vérifier le nom d’hôte, le protocole et le dossier dans le panneau du compte avant le premier déploiement.

Créer la base MySQL et importer le script depuis phpMyAdmin. Créer séparément le fichier `.env` à la racine du site avec les paramètres de production, puis rendre `public/uploads/` accessible en écriture par PHP. Le `.htaccess` à la racine protège les dossiers privés et fait servir `public/` comme racine du site.

Les images téléversées sont exclues de Git et du déploiement FTP pour éviter leur suppression lors des synchronisations. Après le premier lancement, contrôler les journaux de l’action et tester le site; l’hébergeur peut temporairement bloquer les formulaires `multipart/form-data`, nécessaires aux uploads d’images.
