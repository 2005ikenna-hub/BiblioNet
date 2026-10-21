<?php
namespace App\Controllers;

use App\Core\Controller;

class EmpruntController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $this->render('emprunts/index', [
            'pageTitle' => 'Emprunts',
        ]);
    }

    public function createForm(): void
    {
        $this->requireAuth();
        $this->redirect('emprunts');
    }

    public function create(): void
    {
        $this->requireAuth();
        $this->redirect('emprunts');
    }

    public function retour(int $id): void
    {
        $this->requireAuth();
        $this->redirect('emprunts');
    }
}
