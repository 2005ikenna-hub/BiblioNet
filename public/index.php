<?php
// ============================================================
// public/index.php — Front Controller (point d'entrée unique)
// ============================================================

// Racine absolue du projet (un niveau au-dessus de public/)
define('APP_ROOT', dirname(__DIR__));

// --- Chargement de la configuration ---
require_once APP_ROOT . '/config/config.php';

// --- Autoloader PSR-4 simple (sans Composer) ---
spl_autoload_register(function (string $class): void {
    // Convertir App\Core\Database → app/core/Database.php
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) return;

    $relative = substr($class, strlen($prefix));
    $file = APP_ROOT . '/app/' . str_replace('\\', '/', strtolower($relative)) . '.php';

    // Essai avec casse exacte du fichier (PHP est sensible à la casse sur Linux)
    if (!file_exists($file)) {
        // Reconstituer le chemin avec le bon case (majuscule sur le nom de fichier)
        $parts    = explode('\\', substr($class, strlen($prefix)));
        $fileName = array_pop($parts);
        $dir      = APP_ROOT . '/app/' . implode('/', array_map('strtolower', $parts));
        $file     = $dir . '/' . $fileName . '.php';
    }

    if (file_exists($file)) {
        require_once $file;
    }
});

// --- Démarrage de la session ---
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// --- Instanciation du routeur ---
$router = new App\Core\Router();

// ======================================================
// Définition des routes
// ======================================================

// --- Catalogue (page publique) ---
$router->add('',                          'App\Controllers\CatalogueController', 'index');
$router->add('catalogue',                 'App\Controllers\CatalogueController', 'index');
$router->add('catalogue/recherche',       'App\Controllers\CatalogueController', 'recherche');

// --- Authentification ---
$router->add('auth/login',                'App\Controllers\AuthController',      'loginForm');
$router->add('auth/login/submit',         'App\Controllers\AuthController',      'login');
$router->add('auth/logout',               'App\Controllers\AuthController',      'logout');

// --- Livres (admin) ---
$router->add('livres',                    'App\Controllers\LivreController',     'index');
$router->add('livres/creer',              'App\Controllers\LivreController',     'createForm');
$router->add('livres/creer/submit',       'App\Controllers\LivreController',     'create');
$router->add('livres/modifier/(\d+)',     'App\Controllers\LivreController',     'editForm');
$router->add('livres/modifier/(\d+)/submit', 'App\Controllers\LivreController', 'update');
$router->add('livres/supprimer/(\d+)',    'App\Controllers\LivreController',     'delete');

// --- Adhérents (admin) ---
$router->add('adherents',                 'App\Controllers\AdherentController',  'index');
$router->add('adherents/creer',           'App\Controllers\AdherentController',  'createForm');
$router->add('adherents/creer/submit',    'App\Controllers\AdherentController',  'create');
$router->add('adherents/modifier/(\d+)',  'App\Controllers\AdherentController',  'editForm');
$router->add('adherents/modifier/(\d+)/submit', 'App\Controllers\AdherentController', 'update');
$router->add('adherents/supprimer/(\d+)', 'App\Controllers\AdherentController', 'delete');

// --- Emprunts (admin) ---
$router->add('emprunts',                  'App\Controllers\EmpruntController',   'index');
$router->add('emprunts/creer',            'App\Controllers\EmpruntController',   'createForm');
$router->add('emprunts/creer/submit',     'App\Controllers\EmpruntController',   'create');
$router->add('emprunts/retour/(\d+)',     'App\Controllers\EmpruntController',   'retour');

// ======================================================
// Dispatch de la requête courante
// ======================================================
$url = $_GET['url'] ?? '';
$router->dispatch($url);
