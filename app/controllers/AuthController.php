<?php
namespace App\Controllers;

use App\Core\Controller;

class AuthController extends Controller
{
    public function loginForm(): void
    {
        $this->render('auth/login', ['pageTitle' => 'Connexion']);
    }

    public function login(): void
    {
        // TODO J2 — implémenter l'authentification
        $this->redirect('auth/login');
    }

    public function logout(): void
    {
        session_destroy();
        $this->redirect('');
    }
}
