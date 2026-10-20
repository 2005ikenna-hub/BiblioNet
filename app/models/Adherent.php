<?php namespace App\Models;
use App\Core\Database;

class Adherent
{
    public static function all(): array
    {
        return Database::getInstance()->query('SELECT * FROM adherents ORDER BY nom, prenom')->fetchAll();
    }

    public static function find(int $id): array|false
    {
        $s = Database::getInstance()->prepare('SELECT * FROM adherents WHERE id = :id LIMIT 1');
        $s->execute([':id' => $id]);
        return $s->fetch();
    }

    public static function create(array $d): int
    {
        $pdo = Database::getInstance();
        $s = $pdo->prepare('INSERT INTO adherents (nom, prenom, classe, telephone) VALUES (:nom,:prenom,:classe,:tel)');
        $s->execute([':nom'=>$d['nom'],':prenom'=>$d['prenom'],':classe'=>$d['classe']??'',':tel'=>$d['telephone']??'']);
        return (int)$pdo->lastInsertId();
    }

    public static function update(int $id, array $d): void
    {
        $s = Database::getInstance()->prepare('UPDATE adherents SET nom=:nom,prenom=:prenom,classe=:classe,telephone=:tel WHERE id=:id');
        $s->execute([':nom'=>$d['nom'],':prenom'=>$d['prenom'],':classe'=>$d['classe']??'',':tel'=>$d['telephone']??'',':id'=>$id]);
    }

    public static function delete(int $id): void
    {
        $s = Database::getInstance()->prepare('DELETE FROM adherents WHERE id = :id');
        $s->execute([':id' => $id]);
    }
}
