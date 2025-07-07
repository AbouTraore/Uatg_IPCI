-- Script pour restaurer la table patient à son état d'origine
-- Avant les modifications d'hier (ajout des champs Adresse et date_creation)

USE uatg;

-- 1. Supprimer le champ date_creation s'il existe
ALTER TABLE `patient` DROP COLUMN IF EXISTS `date_creation`;

-- 2. Supprimer le champ Adresse s'il existe
ALTER TABLE `patient` DROP COLUMN IF EXISTS `Adresse`;

-- 3. Supprimer la clé primaire si elle existe
ALTER TABLE `patient` DROP PRIMARY KEY IF EXISTS;

-- 4. Vérifier la structure de la table
DESCRIBE `patient`;

-- 5. Afficher un message de confirmation
SELECT 'Table patient restaurée à son état d\'origine' AS message;