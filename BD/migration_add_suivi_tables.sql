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

-- Migration pour centraliser les informations patient/visite

-- 1. Ajout des colonnes et clés étrangères sur la table visite
ALTER TABLE visite
  ADD COLUMN numero_urap VARCHAR(15) NOT NULL,
  ADD COLUMN medecin_prescripteur VARCHAR(100),
  ADD COLUMN structure_provenance VARCHAR(100),
  ADD CONSTRAINT fk_visite_patient FOREIGN KEY (numero_urap) REFERENCES patient(Numero_urap);

-- 2. Ajout des colonnes et clés étrangères sur la table habitude_sexuelles
ALTER TABLE habitude_sexuelles
  ADD COLUMN numero_urap VARCHAR(15) NOT NULL,
  ADD COLUMN id_visite INT,
  ADD CONSTRAINT fk_habitude_patient FOREIGN KEY (numero_urap) REFERENCES patient(Numero_urap),
  ADD CONSTRAINT fk_habitude_visite FOREIGN KEY (id_visite) REFERENCES visite(id_visite);

-- 3. Ajout du lien visite sur la table histoire_maladie
ALTER TABLE histoire_maladie
  ADD COLUMN id_visite INT,
  ADD CONSTRAINT fk_histoire_visite FOREIGN KEY (id_visite) REFERENCES visite(id_visite);

-- 4. Ajout des colonnes et clés étrangères sur la table antecedents_ist_genicologiques
ALTER TABLE antecedents_ist_genicologiques
  ADD COLUMN numero_urap VARCHAR(15) NOT NULL,
  ADD COLUMN id_visite INT,
  ADD CONSTRAINT fk_antecedent_patient FOREIGN KEY (numero_urap) REFERENCES patient(Numero_urap),
  ADD CONSTRAINT fk_antecedent_visite FOREIGN KEY (id_visite) REFERENCES visite(id_visite);

-- 5. Exemple pour les tables d'échantillons et examens (à adapter pour chaque table concernée)
-- Remplacez NOM_DE_LA_TABLE par le nom réel de la table
-- ALTER TABLE NOM_DE_LA_TABLE
--   ADD COLUMN numero_urap VARCHAR(15) NOT NULL,
--   ADD COLUMN id_visite INT,
--   ADD CONSTRAINT fk_NOM_patient FOREIGN KEY (numero_urap) REFERENCES patient(Numero_urap),
--   ADD CONSTRAINT fk_NOM_visite FOREIGN KEY (id_visite) REFERENCES visite(id_visite);

-- 6. Création d'une vue récapitulative
CREATE OR REPLACE VIEW patient_recapitulatif AS
SELECT
  p.Numero_urap,
  p.Nom_patient,
  p.Prenom_patient,
  p.Age,
  p.Sexe_patient,
  p.Date_naissance,
  p.Contact_patient,
  p.Situation_matrimoniale,
  p.Lieu_résidence,
  v.id_visite,
  v.`Date visite`,
  v.`Heure visite`,
  v.`Motif visite`,
  v.medecin_prescripteur,
  v.structure_provenance,
  hm.motif_homme,
  hm.motif_femme,
  hm.signes_fonctionnels,
  hs.Quel_type_rapport_avez_vous_,
  hs.Pratiquez_vous__fellation,
  hs.Avez_vous_changé_partenais_ces_deux_dernier_mois,
  hs.Utilisez_vous_preservatif,
  ai.Avez_vous_eu_des_pertes_vaginales_ces_deux_derniers_mois,
  ai.Avez_vous_eu_des_douleurs_au_bas_ventre_ces_deux_derniers_mois
  -- Ajoutez ici les autres champs nécessaires (examens, échantillons, etc.)
FROM patient p
LEFT JOIN visite v ON v.numero_urap = p.Numero_urap
LEFT JOIN histoire_maladie hm ON hm.numero_urap = p.Numero_urap AND hm.id_visite = v.id_visite
LEFT JOIN habitude_sexuelles hs ON hs.numero_urap = p.Numero_urap AND hs.id_visite = v.id_visite
LEFT JOIN antecedents_ist_genicologiques ai ON ai.numero_urap = p.Numero_urap AND ai.id_visite = v.id_visite
-- Ajoutez ici les autres jointures nécessaires
; 