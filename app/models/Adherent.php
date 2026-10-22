<?php
// ============================================================
// app/models/Adherent.php — Modèle Adhérent
// ============================================================

namespace App\Models;

use App\Core\Database;

class Adherent
{
    public static function all(): array
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->query('SELECT * FROM adherents ORDER BY nom ASC, prenom ASC');
        return $stmt->fetchAll();
    }

    public static function find(int $id): array|false
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare('SELECT * FROM adherents WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public static function create(array $data): int
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare('
            INSERT INTO adherents (nom, prenom, classe, telephone, image_path)
            VALUES (:nom, :prenom, :classe, :telephone, :image_path)
        ');
        $stmt->execute([
            ':nom' => $data['nom'],
            ':prenom' => $data['prenom'],
            ':classe' => $data['classe'] ?? '',
            ':telephone' => $data['telephone'] ?? '',
            ':image_path' => $data['image_path'] ?? null,
        ]);
        return (int) $pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare('
            UPDATE adherents
            SET nom = :nom, prenom = :prenom, classe = :classe, telephone = :telephone, image_path = :image_path
            WHERE id = :id
        ');
        $stmt->execute([
            ':nom' => $data['nom'],
            ':prenom' => $data['prenom'],
            ':classe' => $data['classe'] ?? '',
            ':telephone' => $data['telephone'] ?? '',
            ':image_path' => $data['image_path'] ?? null,
            ':id' => $id,
        ]);
    }

    public static function delete(int $id): bool
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare('
            DELETE FROM adherents
            WHERE id = :id
              AND NOT EXISTS (
                  SELECT 1 FROM emprunts WHERE adherent_id = :loan_adherent_id
              )
        ');
        $stmt->execute([
            ':id' => $id,
            ':loan_adherent_id' => $id,
        ]);
        return $stmt->rowCount() === 1;
    }
}
