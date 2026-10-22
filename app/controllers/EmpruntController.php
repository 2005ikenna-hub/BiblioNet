<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Adherent;
use App\Models\Emprunt;
use App\Models\Livre;

class EmpruntController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $this->render('emprunts/index', [
            'pageTitle' => 'Gestion des emprunts',
            'emprunts' => Emprunt::all(),
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

        $livreId = filter_var($this->postString('livre_id'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $adherentId = filter_var($this->postString('adherent_id'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $selection = [
            'livre_id' => $livreId ?: '',
            'adherent_id' => $adherentId ?: '',
        ];
        $errors = [];

        if (!$livreId || !$adherentId) {
            $errors[] = 'Veuillez sélectionner un livre et un adhérent.';
        } elseif (!Livre::find((int) $livreId)) {
            $errors[] = 'Le livre sélectionné n’existe plus.';
        } elseif (!Adherent::find((int) $adherentId)) {
            $errors[] = 'L’adhérent sélectionné n’existe plus.';
        } elseif (Livre::exemplairesDisponibles((int) $livreId) < 1) {
            $errors[] = 'Aucun exemplaire de ce livre n’est actuellement disponible.';
        }

        if ($errors) {
            http_response_code(422);
            $this->renderForm($selection, $errors);
            return;
        }

        if (!Emprunt::create((int) $livreId, (int) $adherentId)) {
            http_response_code(422);
            $this->renderForm($selection, ['Le stock vient de changer. Vérifiez la disponibilité puis réessayez.']);
            return;
        }

        $this->flash('success', 'L’emprunt a été enregistré.');
        $this->redirect('emprunts');
    }

    public function retour(int $id): void
    {
        $this->requireAuth();
        $this->requirePost();
        $this->verifyCsrfToken();

        if (Emprunt::retour($id)) {
            $this->flash('success', 'Le retour du livre a été enregistré.');
        } else {
            $this->flash('warning', 'Emprunt introuvable ou déjà clôturé.');
        }
        $this->redirect('emprunts');
    }

    private function renderForm(array $selection, array $errors): void
    {
        $this->render('emprunts/form', [
            'pageTitle' => 'Enregistrer un emprunt',
            'livres' => Livre::allAvecDisponibilite(),
            'adherents' => Adherent::all(),
            'selection' => $selection,
            'errors' => $errors,
            'csrfToken' => $this->csrfToken(),
        ]);
    }
}
