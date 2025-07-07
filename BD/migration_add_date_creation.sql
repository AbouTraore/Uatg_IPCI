-- Migration pour ajouter le champ date_creation à la table patient
-- Exécutez ce script dans votre base de données pour mettre à jour la structure

USE uatg;

-- Ajouter le champ date_creation à la table patient
ALTER TABLE patient ADD COLUMN date_creation TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;

-- Mettre à jour les enregistrements existants avec la date d'aujourd'hui
UPDATE patient SET date_creation = CURRENT_TIMESTAMP WHERE date_creation IS NULL;

-- Vérifier que la modification a été appliquée
DESCRIBE patient; 