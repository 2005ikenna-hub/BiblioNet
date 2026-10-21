-- Run once on an existing BiblioNet database created before this migration.
-- SMALLINT UNSIGNED preserves publication dates earlier than 1901.
ALTER TABLE livres MODIFY annee SMALLINT UNSIGNED NOT NULL;

-- Restore the seed value that MySQL YEAR stored as 0000.
UPDATE livres SET annee = 1759 WHERE titre = 'Candide' AND annee = 0;
