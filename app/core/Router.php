<?php
// ============================================================
// app/core/Router.php — Routeur simple (front controller)
// ============================================================

namespace App\Core;

class Router
{
    /** @var array<string, array{controller: string, action: string}> */
    private array $routes = [];

    /**
     * Enregistre une route.
     *
     * @param string $path       Expression régulière de l'URL (sans les delimiteurs)
     * @param string $controller Nom complet du contrôleur (namespace inclus)
     * @param string $action     Méthode à appeler
     */
    public function add(string $path, string $controller, string $action): void
    {
        $this->routes[$path] = [
            'controller' => $controller,
            'action'     => $action,
        ];
    }

    /**
     * Résout l'URL et appelle le bon contrôleur/action.
     */
    public function dispatch(string $url): void
    {
        // Nettoyer l'URL
        $url = trim($url, '/');
        $url = strtok($url, '?'); // Enlever les query strings éventuels

        foreach ($this->routes as $pattern => $route) {
            if (preg_match('#^' . $pattern . '$#', $url, $matches)) {
                // Retirer le match complet pour ne garder que les groupes
                array_shift($matches);

                $controllerClass = $route['controller'];
                $action          = $route['action'];

                if (!class_exists($controllerClass)) {
                    $this->abort(500, "Contrôleur introuvable : $controllerClass");
                    return;
                }

                $controller = new $controllerClass();

                if (!method_exists($controller, $action)) {
                    $this->abort(500, "Action introuvable : $action");
                    return;
                }

                call_user_func_array([$controller, $action], $matches);
                return;
            }
        }

        // Aucune route trouvée → 404
        $this->abort(404);
    }

    /**
     * Affiche une page d'erreur HTTP.
     */
    private function abort(int $code, string $message = ''): void
    {
        http_response_code($code);
        if ($code === 404) {
            echo '<h1>404 — Page introuvable</h1>';
        } else {
            echo "<h1>Erreur $code</h1><p>" . htmlspecialchars($message) . '</p>';
        }
        exit;
    }
}
