<?php

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/app/core/Database.php';
require_once dirname(__DIR__) . '/app/models/Livre.php';
require_once dirname(__DIR__) . '/app/models/Emprunt.php';

$pdo = new PDO(
    sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', DB_HOST, DB_NAME),
    DB_USER,
    DB_PASS,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
);

$databaseInstance = new ReflectionProperty(App\Core\Database::class, 'instance');
$databaseInstance->setAccessible(true);
$databaseInstance->setValue(null, $pdo);

$pdo->exec('CREATE TEMPORARY TABLE livres (id INT PRIMARY KEY, titre VARCHAR(100), nb_exemplaires INT NOT NULL) ENGINE=InnoDB');
$pdo->exec('CREATE TEMPORARY TABLE adherents (id INT PRIMARY KEY, nom VARCHAR(100), prenom VARCHAR(100)) ENGINE=InnoDB');
$pdo->exec('CREATE TEMPORARY TABLE emprunts (id INT AUTO_INCREMENT PRIMARY KEY, livre_id INT NOT NULL, adherent_id INT NOT NULL, date_emprunt DATE NOT NULL, date_retour DATE NULL) ENGINE=InnoDB');
$pdo->exec("INSERT INTO livres VALUES (1, 'Test book', 1)");
$pdo->exec("INSERT INTO adherents VALUES (1, 'Doe', 'Jane')");

$assert = static function (bool $condition, string $message): void {
    if (!$condition) {
        throw new RuntimeException($message);
    }
};

$createdLoanId = App\Models\Emprunt::create(1, 1);
$assert(is_int($createdLoanId), 'The first loan should be created.');
$assert(App\Models\Emprunt::create(1, 1) === false, 'A loan must be rejected when the only copy is already borrowed.');
$assert(App\Models\Livre::exemplairesDisponibles(1) === 0, 'Availability should be zero while the copy is borrowed.');
$assert(App\Models\Emprunt::retour($createdLoanId), 'The active loan should be returned.');
$assert(!App\Models\Emprunt::retour($createdLoanId), 'A returned loan must not be returned twice.');
$assert(App\Models\Livre::exemplairesDisponibles(1) === 1, 'Availability should be restored after a return.');
$assert(is_int(App\Models\Emprunt::create(1, 1)), 'A loan should be possible after the return.');
$assert(count(App\Models\Emprunt::all()) === 2, 'The history should include active and returned loans.');

echo "Emprunt model tests passed.\n";