-- Ajout de la table dossier_patient
CREATE TABLE IF NOT EXISTS dossier_patient (
    id_dossier INT AUTO_INCREMENT PRIMARY KEY,
    numero_urap VARCHAR(15) NOT NULL,
    date_ouverture DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_fermeture DATETIME DEFAULT NULL,
    statut VARCHAR(20) NOT NULL DEFAULT 'en_cours',
    titre VARCHAR(100) DEFAULT NULL,
    commentaire TEXT DEFAULT NULL,
    FOREIGN KEY (numero_urap) REFERENCES patient(Numero_urap)
);

-- Ajout de la table fiche_epidemiologique
CREATE TABLE IF NOT EXISTS fiche_epidemiologique (
    id_fiche INT AUTO_INCREMENT PRIMARY KEY,
    id_dossier INT NOT NULL,
    date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    type_fiche VARCHAR(50) NOT NULL,
    contenu TEXT NOT NULL,
    FOREIGN KEY (id_dossier) REFERENCES dossier_patient(id_dossier)
); 