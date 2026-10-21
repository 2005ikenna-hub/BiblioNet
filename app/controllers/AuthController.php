<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{
    public function loginForm(): void
    {
        if (!empty($_SESSION['user_id'])) {
            $this->redirect('livres');
        }

        $this->render('auth/login', [
            'pageTitle' => 'Connexion',
            'csrfToken' => $this->csrfToken(),
        ]);
    }

    public function login(): void
    {
        $this->requirePost();
        $this->verifyCsrfToken();

        $email = trim($this->postString('email'));
        $password = $_POST['password'] ?? '';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !is_string($password) || $password === '') {
            $this->renderLoginError('Veuillez saisir une adresse e-mail et un mot de passe valides.');
            return;
        }

        $user = User::findByEmail($email);
        if (!$user || !password_verify($password, $user['password'])) {
            $this->renderLoginError('Adresse e-mail ou mot de passe incorrect.');
            return;
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['user_nom'] = $user['nom'];
        $this->flash('success', 'Connexion réussie.');
        $this->redirect('livres');
    }

    public function logout(): void
    {
        $this->requireAuth();
        $this->requirePost();
        $this->verifyCsrfToken();

        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', [
                'expires' => time() - 42000,
                'path' => $params['path'],
                'domain' => $params['domain'],
                'secure' => $params['secure'],
                'httponly' => $params['httponly'],
                'samesite' => $params['samesite'] ?? 'Lax',
            ]);
        }
        session_destroy();
        $this->redirect('');
    }

    private function renderLoginError(string $message): void
    {
        http_response_code(422);
        $this->render('auth/login', [
            'pageTitle' => 'Connexion',
            'csrfToken' => $this->csrfToken(),
            'error' => $message,
            'email' => $this->postString('email'),
        ]);
    }
}
