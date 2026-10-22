<?php
// ============================================================
// app/models/Livre.php — Modèle Livre
// ============================================================

namespace App\Models;

use App\Core\Database;
use PDO;

class Livre
{
    // --------------------------------------------------------
    // Lecture
    // --------------------------------------------------------

    /** Retourne tous les livres */
    public static function all(): array
    {
        $pdo  = Database::getInstance();
        $stmt = $pdo->query('SELECT * FROM livres ORDER BY titre ASC');
        return $stmt->fetchAll();
    }

    /** Retourne un livre par son ID */
    public static function find(int $id): array|false
    {
        $pdo  = Database::getInstance();
        $stmt = $pdo->prepare('SELECT * FROM livres WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Recherche par titre ou auteur.
     * Retourne aussi les exemplaires disponibles pour chaque livre.
     */
    public static function recherche(string $query): array
    {
        $pdo  = Database::getInstance();
        $like = '%' . $query . '%';

        $sql = '
            SELECT l.*,
                   (l.nb_exemplaires - COALESCE(e.nb_en_cours, 0)) AS disponibles
            FROM livres l
            LEFT JOIN (
                SELECT livre_id, COUNT(*) AS nb_en_cours
                FROM emprunts
                WHERE date_retour IS NULL
                GROUP BY livre_id
            ) e ON e.livre_id = l.id
            WHERE l.titre LIKE :titre OR l.auteur LIKE :auteur
            ORDER BY l.titre ASC
        ';

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':titre' => $like, ':auteur' => $like]);
        return $stmt->fetchAll();
    }

    /**
     * Retourne tous les livres avec le nombre d'exemplaires disponibles.
     */
    public static function allAvecDisponibilite(): array
    {
        $pdo = Database::getInstance();

        $sql = '
            SELECT l.*,
                   (l.nb_exemplaires - COALESCE(e.nb_en_cours, 0)) AS disponibles
            FROM livres l
            LEFT JOIN (
                SELECT livre_id, COUNT(*) AS nb_en_cours
                FROM emprunts
                WHERE date_retour IS NULL
                GROUP BY livre_id
            ) e ON e.livre_id = l.id
            ORDER BY l.titre ASC
        ';

        return $pdo->query($sql)->fetchAll();
    }

    // --------------------------------------------------------
    // Écriture
    // --------------------------------------------------------

    /** Crée un nouveau livre */
    public static function create(array $data): int
    {
        $pdo  = Database::getInstance();
        $stmt = $pdo->prepare('
            INSERT INTO livres (titre, auteur, categorie, annee, nb_exemplaires, image_path)
            VALUES (:titre, :auteur, :categorie, :annee, :nb_exemplaires, :image_path)
        ');
        $stmt->execute([
            ':titre'          => $data['titre'],
            ':auteur'         => $data['auteur'],
            ':categorie'      => $data['categorie'] ?? '',
            ':annee'          => (int) $data['annee'],
            ':nb_exemplaires' => (int) ($data['nb_exemplaires'] ?? 1),
            ':image_path'     => $data['image_path'] ?? null,
        ]);
        return (int) $pdo->lastInsertId();
    }

    /** Met à jour un livre */
    public static function update(int $id, array $data): void
    {
        $pdo  = Database::getInstance();
        $stmt = $pdo->prepare('
            UPDATE livres
            SET titre = :titre, auteur = :auteur, categorie = :categorie,
                annee = :annee, nb_exemplaires = :nb_exemplaires, image_path = :image_path
            WHERE id = :id
        ');
        $stmt->execute([
            ':titre'          => $data['titre'],
            ':auteur'         => $data['auteur'],
            ':categorie'      => $data['categorie'] ?? '',
            ':annee'          => (int) $data['annee'],
            ':nb_exemplaires' => (int) ($data['nb_exemplaires'] ?? 1),
            ':image_path'     => $data['image_path'] ?? null,
            ':id'             => $id,
        ]);
    }

    /** Supprime un livre */
    public static function delete(int $id): bool
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare('DELETE FROM livres WHERE id = :id AND NOT EXISTS (SELECT 1 FROM emprunts WHERE livre_id = :loan_livre_id)');
        $stmt->execute([':id' => $id, ':loan_livre_id' => $id]);
        return $stmt->rowCount() === 1;
    }

    // --------------------------------------------------------
    // Règle métier
    // --------------------------------------------------------

    /** Nombre d'exemplaires disponibles pour un livre */
    public static function exemplairesDisponibles(int $livreId): int
    {
        $pdo  = Database::getInstance();
        $stmt = $pdo->prepare('
            SELECT (l.nb_exemplaires - COALESCE(COUNT(e.id), 0)) AS dispo
            FROM livres l
            LEFT JOIN emprunts e ON e.livre_id = l.id AND e.date_retour IS NULL
            WHERE l.id = :id
            GROUP BY l.id
        ');
        $stmt->execute([':id' => $livreId]);
        $row = $stmt->fetch();
        return $row ? max(0, (int) $row['dispo']) : 0;
    }
}
