# BiblioNet — Rapport de réalisation

**Mini-projet PHP / MySQL — Architecture MVC**  
État du projet au 22 octobre 2026  
Équipe de trois membres — noms à compléter

## 1. Présentation

BiblioNet est une application web de gestion d’une bibliothèque scolaire. Elle remplace le suivi manuel des livres et des prêts par un catalogue consultable publiquement et un espace de gestion réservé au bibliothécaire.

Le projet a été réalisé en PHP 8 orienté objet, avec MySQL, PDO et une architecture MVC sans framework. Le périmètre comprend l’authentification, les livres, les adhérents, les emprunts et les retours. Le script de démonstration contient 18 livres, 10 adhérents et plusieurs emprunts.

L’application locale est servie par Apache via `public/`. Les informations de connexion à MySQL sont lues depuis `.env`, conservé hors du dossier web.

<!-- PAGEBREAK -->

## 2. Architecture et données

Le fichier `public/index.php` constitue le point d’entrée unique. Il charge la configuration, démarre la session, enregistre les routes et délègue la requête au contrôleur concerné. Les contrôleurs valident les entrées, appellent les modèles puis transmettent les résultats aux vues. Les modèles utilisent PDO et les requêtes préparées; les vues n’exécutent pas de SQL.

La base comporte quatre tables : `users` pour les bibliothécaires, `livres`, `adherents` et `emprunts`. Chaque emprunt référence un livre et un adhérent. `date_retour` reste à `NULL` tant que le livre n’est pas rendu.

La disponibilité n’est pas stockée : elle est égale au nombre d’exemplaires moins les emprunts actifs. La création d’un emprunt verrouille la ligne du livre dans une transaction, recompte les emprunts non rendus puis insère l’emprunt uniquement si un exemplaire reste disponible.

**Répartition prévue**

- Membre 1 : socle MVC, accès aux données, authentification, intégration et déploiement.
- Membre 2 : livres, catalogue public, recherche et disponibilité.
- Membre 3 : adhérents, emprunts, retours et données SQL.

<!-- PAGEBREAK -->

## 3. Fonctionnalités réalisées

Le visiteur peut consulter le catalogue, rechercher un titre ou un auteur et voir la disponibilité actuelle. Le bibliothécaire connecté peut gérer les livres et les adhérents, enregistrer un emprunt et saisir un retour. L’historique affiche le livre, l’adhérent, les dates et l’état du prêt; les emprunts de plus de 14 jours sont signalés comme retards.

La création est refusée lorsqu’aucun exemplaire n’est disponible. Le contrôle du modèle complète le contrôle d’interface et protège aussi le stock si plusieurs demandes arrivent presque simultanément. Un emprunt déjà clôturé ne peut pas être retourné une seconde fois.

![Écran de gestion des emprunts](capture-emprunts.png)

La capture montre les prêts de démonstration, les actions de retour et les retards calculés sur les données locales.

<!-- PAGEBREAK -->

## 4. Sécurité et vérifications

Les mots de passe sont vérifiés avec `password_verify`; l’accès aux écrans de gestion dépend de la session. Les créations et retours utilisent POST, les actions d’écriture vérifient le jeton CSRF et les valeurs affichées sont échappées avec `htmlspecialchars`. PDO est configuré en mode exceptions avec les préparations natives.

Les vérifications réalisées en local au 22 octobre 2026 :

- Connexion à la base MySQL et présence des 18 livres de démonstration.
- Redirection d’un visiteur non connecté vers la page de connexion.
- Ouverture du formulaire d’emprunt avec les livres et adhérents disponibles.
- Recherche de « Dune » : un résultat et deux exemplaires disponibles.
- Test automatisé du modèle : création, refus hors stock, retour, refus du double retour, disponibilité restaurée et historique.
- Vérification de syntaxe PHP des fichiers modifiés.

![Catalogue public filtré sur Dune](capture-catalogue.png)

Une anomalie de la base locale provenait de l’ancien type MySQL `YEAR`, qui avait ramené plusieurs années antérieures à 1901 à zéro. La migration `001_annee_publication_smallint.sql` passe la colonne en `SMALLINT UNSIGNED` et restaure les années du jeu de démonstration.

<!-- PAGEBREAK -->

## 5. Déploiement et suite

Le déploiement public n’a pas été effectué dans le cadre de ces vérifications : aucun accès d’hébergement ni domaine de production n’est configuré dans le workspace. La procédure prévue est de créer la base et l’utilisateur MySQL sur l’hébergement, importer `database/biblionet.sql`, transférer les fichiers, faire pointer le domaine vers `public/`, puis créer un `.env` de production avec `APP_ENV=production` et une URL HTTPS.

Avant la soutenance, l’équipe doit tester les parcours sur l’URL publique, vérifier que les erreurs ne sont pas affichées, remplacer le mot de passe du compte de démonstration et partager les identifiants de jury par un canal approprié. Les noms des membres, l’établissement et l’URL finale sont à compléter.

La soutenance prévue dure 15 minutes : démonstration des parcours principaux, puis questions à chacun des membres. Chaque membre doit pouvoir expliquer l’architecture et les règles métier, y compris les parties développées par les autres.