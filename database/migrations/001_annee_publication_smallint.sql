-- Run once on an existing BiblioNet database created before this migration.
-- SMALLINT UNSIGNED preserves publication dates earlier than 1901.
ALTER TABLE livres MODIFY annee SMALLINT UNSIGNED NOT NULL;

-- Restore seed values that MySQL YEAR stored as 0000.
UPDATE livres
SET annee = CASE titre
		WHEN 'Candide' THEN 1759
		WHEN 'Germinal' THEN 1885
		WHEN 'Le Comte de Monte-Cristo' THEN 1844
		WHEN 'Le Rouge et le Noir' THEN 1830
		WHEN 'Les Misérables' THEN 1862
		WHEN 'Madame Bovary' THEN 1857
		WHEN 'Notre-Dame de Paris' THEN 1831
END
WHERE annee = 0
	AND titre IN (
			'Candide',
			'Germinal',
			'Le Comte de Monte-Cristo',
			'Le Rouge et le Noir',
			'Les Misérables',
			'Madame Bovary',
			'Notre-Dame de Paris'
	);
