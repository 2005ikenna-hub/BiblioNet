# BiblioNet — Bilan du Jour 4

**Finalisation locale, contrôles et préparation de la soutenance**  
Mini-projet PHP / MySQL — Architecture MVC  
État constaté le 22 octobre 2026  
Équipe : noms des membres à compléter

## Finalisation

Le Jour 4 consolide les fonctionnalités, la sécurité, la documentation et les livrables. L’application locale comprend l’authentification, les CRUD des livres et adhérents, le catalogue, la recherche, les emprunts et les retours.

Une fonctionnalité complémentaire permet d’ajouter des images aux livres et aux adhérents. Les fichiers acceptés sont JPEG, PNG et WebP, avec une limite de 5 Mo. Les noms sont générés côté serveur, les formulaires affichent un aperçu, et l’image précédente est supprimée lors d’un remplacement ou de la suppression de la fiche.

La migration `002_images_livres_adherents.sql` ajoute les colonnes nécessaires aux bases existantes. Elle a été appliquée à la base locale. Les instructions d’installation, de migration et de déploiement figurent dans `README.md`.

## Sécurité et tests

- Authentification par session et mots de passe hachés/vérifiés.
- Actions de gestion protégées par POST et CSRF.
- Requêtes de données exécutées avec PDO et paramètres préparés.
- Échappement HTML des données affichées.
- Validation du contenu des images par type MIME et contrôle d’image; exécution de scripts bloquée dans `public/uploads/`.
- Images téléversées exclues de Git; le dossier devra être accessible en écriture par PHP sur l’hébergement.
- Lint PHP et test automatisé du modèle d’emprunt réussis.
- Tests navigateur réussis pour ajout, affichage, remplacement et suppression d’images.

La base de démonstration locale conserve 18 livres et 10 adhérents. Les fiches temporaires utilisées pour les tests ont été supprimées.

![Catalogue public](capture-catalogue.png)

## Déploiement et soutenance

Le déploiement public n’est pas encore effectué : l’hébergeur est en cours de recherche. Une fois le fournisseur choisi, il restera à créer la base MySQL, importer `database/biblionet.sql`, transférer le projet, faire pointer le site vers `public/`, configurer `.env` hors du dossier public, rendre `public/uploads/` accessible en écriture et tester toutes les fonctions sur l’URL HTTPS.

Avant la soutenance, remplacer le mot de passe de démonstration, renseigner les noms des membres et l’adresse publique, puis vérifier les parcours en ligne. Aucun mot de passe d’hébergement ou de base ne doit être ajouté au dépôt GitHub ni à ce rapport.