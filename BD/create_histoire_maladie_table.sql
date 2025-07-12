-- Script de création de la table histoire_maladie
-- Exécuter ce script dans la base de données uatg

USE uatg;

-- Supprimer la table si elle existe déjà
DROP TABLE IF EXISTS `histoire_maladie`;

-- Créer la table histoire_maladie
CREATE TABLE `histoire_maladie` (
    `id` int NOT NULL AUTO_INCREMENT,
    `numero_urap` varchar(15) NOT NULL,
    `sexe_patient` varchar(10) NOT NULL,
    `motif_homme` varchar(100) DEFAULT NULL,
    `motif_femme` varchar(100) DEFAULT NULL,
    `signes_fonctionnels` varchar(100) DEFAULT NULL,
    `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `numero_urap` (`numero_urap`),
    FOREIGN KEY (`numero_urap`) REFERENCES `patient`(`Numero_urap`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insérer quelques données de test (optionnel)
INSERT INTO `histoire_maladie` (`numero_urap`, `sexe_patient`, `motif_homme`, `motif_femme`, `signes_fonctionnels`) VALUES
('12345', 'masculin', 'paternite', NULL, 'leucorrhees'),
('67890', 'feminin', NULL, 'gynecologique', 'prurit');

-- Afficher la structure de la table créée
DESCRIBE histoire_maladie;

-- Afficher les données insérées
SELECT * FROM histoire_maladie; 