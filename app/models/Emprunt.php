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
            SELECT e.*, l.titre AS livre_titre, a.nom AS adherent_nom, a.prenom AS adherent_prenom,
                   DATEDIFF(CURDATE(), e.date_emprunt) AS jours_ecoules
            FROM emprunts e
            JOIN livres l    ON l.id = e.livre_id
            JOIN adherents a ON a.id = e.adherent_id
            ORDER BY e.date_emprunt DESC
        ')->fetchAll();
    }

    /** Crée un emprunt */
    public static function create(int $livreId, int $adherentId): int|false
    {
        $pdo = Database::getInstance();
        $pdo->beginTransaction();

        try {
            $book = $pdo->prepare('SELECT nb_exemplaires FROM livres WHERE id = :id FOR UPDATE');
            $book->execute([':id' => $livreId]);
            $bookRow = $book->fetch();

            if (!$bookRow) {
                $pdo->rollBack();
                return false;
            }

            $member = $pdo->prepare('SELECT id FROM adherents WHERE id = :id');
            $member->execute([':id' => $adherentId]);
            if (!$member->fetch()) {
                $pdo->rollBack();
                return false;
            }

            $activeLoans = $pdo->prepare('SELECT COUNT(*) FROM emprunts WHERE livre_id = :id AND date_retour IS NULL');
            $activeLoans->execute([':id' => $livreId]);
            if ((int) $activeLoans->fetchColumn() >= (int) $bookRow['nb_exemplaires']) {
                $pdo->rollBack();
                return false;
            }

            $insert = $pdo->prepare('INSERT INTO emprunts (livre_id, adherent_id, date_emprunt) VALUES (:livre_id, :adherent_id, CURDATE())');
            $insert->execute([':livre_id' => $livreId, ':adherent_id' => $adherentId]);
            $id = (int) $pdo->lastInsertId();
            $pdo->commit();

            return $id;
        } catch (\Throwable $error) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $error;
        }
    }

    /** Enregistre le retour d'un emprunt */
    public static function retour(int $id): bool
    {
        $s = Database::getInstance()->prepare('UPDATE emprunts SET date_retour = CURDATE() WHERE id = :id AND date_retour IS NULL');
        $s->execute([':id' => $id]);
        return $s->rowCount() === 1;
    }
}
