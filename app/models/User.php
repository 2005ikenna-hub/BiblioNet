<?php namespace App\Models;
use App\Core\Database;

class User
{
    public static function findByEmail(string $email): array|false
    {
        $s = Database::getInstance()->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $s->execute([':email' => $email]);
        return $s->fetch();
    }

    public static function create(string $nom, string $email, string $password): int
    {
        $pdo = Database::getInstance();
        $s   = $pdo->prepare('INSERT INTO users (nom, email, password) VALUES (:nom, :email, :pw)');
        $s->execute([':nom' => $nom, ':email' => $email, ':pw' => password_hash($password, PASSWORD_BCRYPT)]);
        return (int) $pdo->lastInsertId();
    }
}
