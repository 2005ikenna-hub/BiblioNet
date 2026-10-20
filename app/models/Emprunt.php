<?php namespace App\Models;
use App\Core\Database;

class Emprunt
{
    /** Tous les emprunts en cours (date_retour IS NULL) */
    public static function enCours(): array
    {
        return Database::getInstance()->query('
            SELECT e.*, l.titre AS livre_titre, a.nom AS adherent_nom, a.prenom AS adherent_prenom
            FROM emprunts e
            JOIN livres l    ON l.id = e.livre_id
            JOIN adherents a ON a.id = e.adherent_id
            WHERE e.date_retour IS NULL
            ORDER BY e.date_emprunt ASC
        ')->fetchAll();
    }

    /** Tous les emprunts (historique complet) */
    public static function all(): array
    {
        return Database::getInstance()->query('
            SELECT e.*, l.titre AS livre_titre, a.nom AS adherent_nom, a.prenom AS adherent_prenom
            FROM emprunts e
            JOIN livres l    ON l.id = e.livre_id
            JOIN adherents a ON a.id = e.adherent_id
            ORDER BY e.date_emprunt DESC
        ')->fetchAll();
    }

    /** Crée un emprunt */
    public static function create(int $livreId, int $adherentId): int
    {
        $pdo = Database::getInstance();
        $s = $pdo->prepare('INSERT INTO emprunts (livre_id, adherent_id, date_emprunt) VALUES (:l, :a, CURDATE())');
        $s->execute([':l' => $livreId, ':a' => $adherentId]);
        return (int) $pdo->lastInsertId();
    }

    /** Enregistre le retour d'un emprunt */
    public static function retour(int $id): void
    {
        $s = Database::getInstance()->prepare('UPDATE emprunts SET date_retour = CURDATE() WHERE id = :id');
        $s->execute([':id' => $id]);
    }
}
