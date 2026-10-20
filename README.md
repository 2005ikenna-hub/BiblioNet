# BiblioNet — Application de gestion de bibliothèque

Mini-projet MVC PHP/MySQL — Charly OBAME

## 🚀 Installation locale

### Prérequis
- PHP 8.x avec l'extension PDO et PDO_MySQL
- MySQL 5.7+ ou MariaDB
- Apache avec `mod_rewrite` activé (ou XAMPP/Laragon/Wamp)

### Étapes

1. **Cloner le dépôt**
   ```bash
   git clone https://github.com/<votre-groupe>/biblionet.git
   cd biblionet
   ```

2. **Créer la base de données**
   ```bash
   mysql -u root -p < database/biblionet.sql
   ```
   Ou importer via phpMyAdmin.

3. **Configurer l'environnement**
   ```bash
   cp .env.example .env
   ```
   Puis éditer `.env` avec vos identifiants MySQL.

4. **Pointer Apache vers `public/`**
   - Dans XAMPP, placer le projet dans `htdocs/BiblioNet/`
   - Accéder à `http://localhost/BiblioNet/public/`
   - Ou configurer un VirtualHost pointant vers `public/`

5. **Compte de démonstration**
   - Email : `admin@biblionet.com`
   - Mot de passe : `Admin1234`

## 🏗️ Architecture MVC

```
biblionet/
├── app/
│   ├── controllers/    — Logique de chaque module
│   ├── models/         — Accès PDO à la base de données
│   ├── views/          — Templates HTML (aucune requête SQL)
│   └── core/           — Router, Controller, Database (socle)
├── config/config.php   — Lit les variables du fichier .env
├── public/index.php    — Front controller (point d'entrée unique)
├── public/.htaccess    — Réécriture d'URL
├── database/           — Script SQL + données de test
└── .env                — Secrets (jamais committé)
```

## 👥 Répartition des tâches

| Membre | Domaine |
|--------|---------|
| Membre 1 | Socle MVC, Router, Database, Auth, déploiement |
| Membre 2 | CRUD Livres, catalogue public, recherche |
| Membre 3 | CRUD Adhérents, Emprunts/Retours, script SQL |

## 📋 Fonctionnalités

- ✅ Catalogue public avec recherche et disponibilité
- ✅ Authentification bibliothécaire (session + password_hash)
- ✅ CRUD Livres
- ✅ CRUD Adhérents
- ✅ Gestion des emprunts et retours
- ✅ Règle de disponibilité (refus si 0 exemplaire dispo)
