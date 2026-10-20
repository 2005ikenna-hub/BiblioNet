<?php
// ============================================================
// app/controllers/CatalogueController.php
// Page publique : catalogue et recherche
// ============================================================

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Livre;

class CatalogueController extends Controller
{
    /** Affiche tous les livres avec disponibilité */
    public function index(): void
    {
        $livres = Livre::allAvecDisponibilite();

        $this->render('catalogue/index', [
            'pageTitle' => 'Catalogue',
            'livres'    => $livres,
            'query'     => '',
        ]);
    }

    /** Recherche par titre ou auteur */
    public function recherche(): void
    {
        $query  = trim($_GET['q'] ?? '');
        $livres = $query ? Livre::recherche($query) : Livre::allAvecDisponibilite();

        $this->render('catalogue/index', [
            'pageTitle' => 'Catalogue — Recherche',
            'livres'    => $livres,
            'query'     => $query,
        ]);
    }
}
