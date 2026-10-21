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
        header('Content-Type: text/html; charset=UTF-8');
        extract($data, EXTR_SKIP);
        $viewPath = APP_ROOT . '/app/views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            die("Vue introuvable : $viewPath");
        }

        ob_start();
        require $viewPath;
        $content = ob_get_clean();
        $layoutPath = APP_ROOT . '/app/views/' . $layout . '.php';

        if (file_exists($layoutPath)) {
            require $layoutPath;
        } else {
            echo $content;
        }
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . APP_URL . '/' . ltrim($path, '/'));
        exit;
    }

    protected function requireAuth(): void
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('auth/login');
        }
    }

    protected function requirePost(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            http_response_code(405);
            header('Allow: POST');
            exit('Méthode non autorisée.');
        }
    }

    protected function postString(string $key, string $default = ''): string
    {
        $value = $_POST[$key] ?? $default;
        return is_string($value) ? $value : $default;
    }

    protected function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    protected function verifyCsrfToken(): void
    {
        $token = $_POST['csrf_token'] ?? '';
        if (!is_string($token) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            http_response_code(403);
            exit('Requête refusée : jeton de sécurité invalide.');
        }
    }

    protected function flash(string $type, string $message): void
    {
        $_SESSION['flash'][$type] = $message;
    }

    protected function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
