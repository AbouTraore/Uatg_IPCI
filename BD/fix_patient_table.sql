-- Script pour corriger la table patient
-- Ajout de la clé primaire et du champ Adresse manquant

-- 1. Ajouter le champ Adresse manquant
ALTER TABLE `patient` ADD COLUMN `Adresse` varchar(100) NOT NULL DEFAULT '' AFTER `Contact_patient`;

-- 2. Ajouter une clé primaire sur Numero_urap
ALTER TABLE `patient` ADD PRIMARY KEY (`Numero_urap`);

-- 3. Vérifier que la table est correcte
DESCRIBE `patient`; 