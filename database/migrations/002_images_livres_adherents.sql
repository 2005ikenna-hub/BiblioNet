-- Run once on an existing BiblioNet database to enable image uploads.
ALTER TABLE livres ADD COLUMN image_path VARCHAR(255) NULL AFTER nb_exemplaires;
ALTER TABLE adherents ADD COLUMN image_path VARCHAR(255) NULL AFTER telephone;