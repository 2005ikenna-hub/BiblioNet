<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\ImageUpload;
use App\Models\Livre;
use RuntimeException;

class LivreController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $this->render('livres/index', [
            'pageTitle' => 'Gestion des livres',
            'livres' => Livre::all(),
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

        try {
            $data['image_path'] = ImageUpload::store('image', 'livres');
            Livre::create($data);
        } catch (RuntimeException $error) {
            if (!empty($data['image_path'])) {
                ImageUpload::delete($data['image_path'], 'livres');
            }
            http_response_code(422);
            $this->renderForm($data, [$error->getMessage()]);
            return;
        }

        $this->flash('success', 'Le livre a été ajouté.');
        $this->redirect('livres');
    }

    public function editForm(int $id): void
    {
        $this->requireAuth();
        $livre = Livre::find($id);
        if (!$livre) {
            $this->flash('warning', 'Livre introuvable.');
            $this->redirect('livres');
        }
        $this->renderForm($livre, []);
    }

    public function update(int $id): void
    {
        $this->requireAuth();
        $this->requirePost();
        $this->verifyCsrfToken();
        $livre = Livre::find($id);
        if (!$livre) {
            $this->flash('warning', 'Livre introuvable.');
            $this->redirect('livres');
        }

        $data = $this->formData();
        $data['image_path'] = $livre['image_path'] ?? null;
        $errors = $this->validate($data);
        if ($errors) {
            http_response_code(422);
            $data['id'] = $id;
            $this->renderForm($data, $errors);
            return;
        }

        try {
            $newImagePath = ImageUpload::store('image', 'livres');
        } catch (RuntimeException $error) {
            http_response_code(422);
            $data['id'] = $id;
            $this->renderForm($data, [$error->getMessage()]);
            return;
        }

        if ($newImagePath !== null) {
            $data['image_path'] = $newImagePath;
        }
        Livre::update($id, $data);
        if ($newImagePath !== null) {
            ImageUpload::delete($livre['image_path'] ?? null, 'livres');
        }
        $this->flash('success', 'Le livre a été modifié.');
        $this->redirect('livres');
    }

    public function delete(int $id): void
    {
        $this->requireAuth();
        $this->requirePost();
        $this->verifyCsrfToken();
        $livre = Livre::find($id);
        if ($livre && Livre::delete($id)) {
            ImageUpload::delete($livre['image_path'] ?? null, 'livres');
            $this->flash('success', 'Le livre a été supprimé.');
        } else {
            $this->flash('warning', 'Livre introuvable ou lié à un emprunt ; suppression refusée.');
        }
        $this->redirect('livres');
    }

    private function formData(): array
    {
        return [
            'titre' => trim($this->postString('titre', '')),
            'auteur' => trim($this->postString('auteur', '')),
            'categorie' => trim($this->postString('categorie', '')),
            'annee' => trim($this->postString('annee', '')),
            'nb_exemplaires' => trim($this->postString('nb_exemplaires', '1')),
        ];
    }

    private function validate(array $data): array
    {
        $errors = [];
        if ($data['titre'] === '' || strlen($data['titre']) > 200) {
            $errors[] = 'Le titre est obligatoire (200 caractères maximum).';
        }
        if ($data['auteur'] === '' || strlen($data['auteur']) > 150) {
            $errors[] = 'L\'auteur est obligatoire (150 caractères maximum).';
        }
        if (strlen($data['categorie']) > 100) {
            $errors[] = 'La catégorie ne peut pas dépasser 100 caractères.';
        }
        $year = filter_var($data['annee'], FILTER_VALIDATE_INT);
        if ($year === false || $year < 1 || $year > 2155) {
            $errors[] = 'L\'année doit être comprise entre 1 et 2155.';
        }
        $copies = filter_var($data['nb_exemplaires'], FILTER_VALIDATE_INT);
        if ($copies === false || $copies < 1 || $copies > 255) {
            $errors[] = 'Le nombre d\'exemplaires doit être compris entre 1 et 255.';
        }
        return $errors;
    }

    private function renderForm(array $livre, array $errors): void
    {
        $isNew = empty($livre['id']);
        $this->render('livres/form', [
            'pageTitle' => $isNew ? 'Ajouter un livre' : 'Modifier un livre',
            'livre' => $livre,
            'errors' => $errors,
            'isNew' => $isNew,
            'csrfToken' => $this->csrfToken(),
        ]);
    }
}
