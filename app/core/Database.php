<?php
// ============================================================
// app/core/Database.php — Singleton PDO
// ============================================================

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    /**
     * Retourne l'instance PDO unique (patron Singleton).
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=utf8mb4',
                DB_HOST,
                DB_NAME
            );

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                // En production ne jamais exposer le message d'erreur
                if (APP_ENV !== 'production') {
                    die('Erreur de connexion à la base de données : ' . $e->getMessage());
                }
                die('Erreur de connexion à la base de données.');
            }
        }

        return self::$instance;
    }

    // Empêcher l'instanciation directe
    private function __construct() {}
    private function __clone() {}
}
