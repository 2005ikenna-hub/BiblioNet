<?php
// ============================================================
// app/core/Controller.php — Contrôleur de base
// ============================================================

namespace App\Core;

abstract class Controller
{
    /**
     * Rend une vue en injectant des données et en l'encapsulant dans le layout.
     *
     * @param string $view   Chemin relatif à app/views/ (ex: 'livres/index')
     * @param array  $data   Tableau associatif de variables transmises à la vue
     * @param string $layout Layout à utiliser (par défaut 'layouts/main')
     */
    protected function render(string $view, array $data = [], string $layout = 'layouts/main'): void
    {
        // Rendre les clés du tableau disponibles comme variables
        extract($data, EXTR_SKIP);

        // Construire le chemin de la vue
        $viewPath = APP_ROOT . '/app/views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            die("Vue introuvable : $viewPath");
        }

        // Capturer le contenu de la vue
        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        // Inclure le layout (qui affichera $content)
        $layoutPath = APP_ROOT . '/app/views/' . $layout . '.php';

        if (file_exists($layoutPath)) {
            require $layoutPath;
        } else {
            // Fallback sans layout
            echo $content;
        }
    }

    /**
     * Redirection HTTP simple.
     */
    protected function redirect(string $path): void
    {
        header('Location: ' . APP_URL . '/' . ltrim($path, '/'));
        exit;
    }

    /**
     * Vérifie que l'utilisateur est connecté, redirige sinon.
     */
    protected function requireAuth(): void
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('auth/login');
        }
    }

    /**
     * Échapper une valeur pour l'affichage HTML (anti-XSS).
     */
    protected function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
