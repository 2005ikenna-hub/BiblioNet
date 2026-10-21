<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Adherent;

class AdherentController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $this->render('adherents/index', [
            'pageTitle' => 'Gestion des adhérents',
            'adherents' => Adherent::all(),
            'csrfToken' => $this->csrfToken(),
        ]);
    }

    public function createForm(): void
    {
        $this->requireAuth();
        $this->renderForm([], []);
    }

    public function create(): void
    {
        $this->requireAuth();
        $this->requirePost();
        $this->verifyCsrfToken();
        $data = $this->formData();
        $errors = $this->validate($data);
        if ($errors) {
            http_response_code(422);
            $this->renderForm($data, $errors);
            return;
        }

        Adherent::create($data);
        $this->flash('success', 'L\'adhérent a été ajouté.');
        $this->redirect('adherents');
    }

    public function editForm(int $id): void
    {
        $this->requireAuth();
        $adherent = Adherent::find($id);
        if (!$adherent) {
            $this->flash('warning', 'Adhérent introuvable.');
            $this->redirect('adherents');
        }
        $this->renderForm($adherent, []);
    }

    public function update(int $id): void
    {
        $this->requireAuth();
        $this->requirePost();
        $this->verifyCsrfToken();
        $data = $this->formData();
        $errors = $this->validate($data);
        if ($errors) {
            http_response_code(422);
            $data['id'] = $id;
            $this->renderForm($data, $errors);
            return;
        }
        if (!Adherent::find($id)) {
            $this->flash('warning', 'Adhérent introuvable.');
            $this->redirect('adherents');
        }

        Adherent::update($id, $data);
        $this->flash('success', 'L\'adhérent a été modifié.');
        $this->redirect('adherents');
    }

    public function delete(int $id): void
    {
        $this->requireAuth();
        $this->requirePost();
        $this->verifyCsrfToken();
        if (Adherent::delete($id)) {
            $this->flash('success', 'L\'adhérent a été supprimé.');
        } else {
            $this->flash('warning', 'Adhérent introuvable ou lié à un emprunt ; suppression refusée.');
        }
        $this->redirect('adherents');
    }

    private function formData(): array
    {
        return [
            'nom' => trim($this->postString('nom', '')),
            'prenom' => trim($this->postString('prenom', '')),
            'classe' => trim($this->postString('classe', '')),
            'telephone' => trim($this->postString('telephone', '')),
        ];
    }

    private function validate(array $data): array
    {
        $errors = [];
        if ($data['nom'] === '' || strlen($data['nom']) > 100) {
            $errors[] = 'Le nom est obligatoire (100 caractères maximum).';
        }
        if ($data['prenom'] === '' || strlen($data['prenom']) > 100) {
            $errors[] = 'Le prénom est obligatoire (100 caractères maximum).';
        }
        if (strlen($data['classe']) > 50) {
            $errors[] = 'La classe ne peut pas dépasser 50 caractères.';
        }
        if (strlen($data['telephone']) > 20) {
            $errors[] = 'Le téléphone ne peut pas dépasser 20 caractères.';
        }
        return $errors;
    }

    private function renderForm(array $adherent, array $errors): void
    {
        $isNew = empty($adherent['id']);
        $this->render('adherents/form', [
            'pageTitle' => $isNew ? 'Ajouter un adhérent' : 'Modifier un adhérent',
            'adherent' => $adherent,
            'errors' => $errors,
            'isNew' => $isNew,
            'csrfToken' => $this->csrfToken(),
        ]);
    }
}
