-- ============================================================
-- BiblioNet — Script de création de la base de données
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Base de données
CREATE DATABASE IF NOT EXISTS `biblionet`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `biblionet`;

-- ------------------------------------------------------------
-- Table : users (bibliothécaires)
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id`       INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom`      VARCHAR(100) NOT NULL,
  `email`    VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Compte de démonstration : admin@biblionet.com / password : Admin1234
INSERT INTO `users` (`nom`, `email`, `password`) VALUES
  ('Administrateur', 'admin@biblionet.com', '$2y$12$8K1p/a0dR1lVX2tO6Y3ZLOQBwNHfPX7.Xt1ZJjNVJqxBzKm6UqH6i');

-- ------------------------------------------------------------
-- Table : livres
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `livres`;
CREATE TABLE `livres` (
  `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `titre`           VARCHAR(200) NOT NULL,
  `auteur`          VARCHAR(150) NOT NULL,
  `categorie`       VARCHAR(100) NOT NULL DEFAULT '',
  `annee`           YEAR         NOT NULL,
  `nb_exemplaires`  TINYINT UNSIGNED NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `livres` (`titre`, `auteur`, `categorie`, `annee`, `nb_exemplaires`) VALUES
  ('Le Petit Prince',                  'Antoine de Saint-Exupéry', 'Roman',            1943, 3),
  ('Harry Potter à l\'école des sorciers', 'J.K. Rowling',         'Fantasy',          1997, 4),
  ('L\'Alchimiste',                    'Paulo Coelho',             'Roman',            1988, 2),
  ('1984',                             'George Orwell',            'Science-fiction',  1949, 3),
  ('Le Seigneur des Anneaux',          'J.R.R. Tolkien',           'Fantasy',          1954, 2),
  ('Les Misérables',                   'Victor Hugo',              'Classique',        1862, 2),
  ('Fahrenheit 451',                   'Ray Bradbury',             'Science-fiction',  1953, 2),
  ('Le Comte de Monte-Cristo',         'Alexandre Dumas',          'Aventure',         1844, 1),
  ('Dune',                             'Frank Herbert',            'Science-fiction',  1965, 2),
  ('Le Meilleur des Mondes',           'Aldous Huxley',            'Science-fiction',  1932, 2),
  ('Notre-Dame de Paris',              'Victor Hugo',              'Classique',        1831, 1),
  ('L\'Étranger',                      'Albert Camus',             'Philosophie',      1942, 3),
  ('Germinal',                         'Émile Zola',               'Classique',        1885, 2),
  ('La Peste',                         'Albert Camus',             'Roman',            1947, 3),
  ('Candide',                          'Voltaire',                 'Philosophie',      1759, 2),
  ('Madame Bovary',                    'Gustave Flaubert',         'Classique',        1857, 1),
  ('Le Rouge et le Noir',              'Stendhal',                 'Classique',        1830, 1),
  ('Gatsby le Magnifique',             'F. Scott Fitzgerald',      'Roman',            1925, 2);

-- ------------------------------------------------------------
-- Table : adherents
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `adherents`;
CREATE TABLE `adherents` (
  `id`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom`       VARCHAR(100) NOT NULL,
  `prenom`    VARCHAR(100) NOT NULL,
  `classe`    VARCHAR(50)  NOT NULL DEFAULT '',
  `telephone` VARCHAR(20)  NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `adherents` (`nom`, `prenom`, `classe`, `telephone`) VALUES
  ('Mbemba',   'Jean-Pierre',  'Terminale A', '06 11 22 33 44'),
  ('Nkaya',    'Lucie',        'Première S',  '06 22 33 44 55'),
  ('Ossanga',  'Paul',         'Terminale D', '06 33 44 55 66'),
  ('Biyoudi',  'Marcelline',   'Seconde C',   '06 44 55 66 77'),
  ('Mouanda',  'Thierry',      'BTS 1',       '06 55 66 77 88'),
  ('Loemba',   'Christelle',   'Terminale A', '06 66 77 88 99'),
  ('Nganga',   'Fabrice',      'Première ES', '06 77 88 99 00'),
  ('Bouanga',  'Nadège',       'BTS 2',       '06 88 99 00 11'),
  ('Makosso',  'Emmanuel',     'Terminale D', '06 99 00 11 22'),
  ('Banzouzi', 'Aurélie',      'Seconde B',   '06 00 11 22 33');

-- ------------------------------------------------------------
-- Table : emprunts
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `emprunts`;
CREATE TABLE `emprunts` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `livre_id`      INT UNSIGNED NOT NULL,
  `adherent_id`   INT UNSIGNED NOT NULL,
  `date_emprunt`  DATE         NOT NULL,
  `date_retour`   DATE             NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_emprunt_livre`    FOREIGN KEY (`livre_id`)    REFERENCES `livres`   (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_emprunt_adherent` FOREIGN KEY (`adherent_id`) REFERENCES `adherents`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Quelques emprunts de test (certains rendus, d'autres en cours)
INSERT INTO `emprunts` (`livre_id`, `adherent_id`, `date_emprunt`, `date_retour`) VALUES
  (1,  1, DATE_SUB(CURDATE(), INTERVAL 20 DAY), DATE_SUB(CURDATE(), INTERVAL 6 DAY)),
  (2,  2, DATE_SUB(CURDATE(), INTERVAL 15 DAY), NULL),
  (3,  3, DATE_SUB(CURDATE(), INTERVAL 10 DAY), NULL),
  (4,  4, DATE_SUB(CURDATE(), INTERVAL 5  DAY), DATE_SUB(CURDATE(), INTERVAL 1 DAY)),
  (5,  5, DATE_SUB(CURDATE(), INTERVAL 18 DAY), NULL),
  (12, 6, DATE_SUB(CURDATE(), INTERVAL 3  DAY), NULL);

SET FOREIGN_KEY_CHECKS = 1;
