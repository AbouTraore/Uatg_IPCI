-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 06 août 2025 à 07:47
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `uatg`
--

-- --------------------------------------------------------

--
-- Structure de la table `antecedents_ist_genicologiques`
--

DROP TABLE IF EXISTS `antecedents_ist_genicologiques`;
CREATE TABLE IF NOT EXISTS `antecedents_ist_genicologiques` (
  `ID_antecedents` int NOT NULL AUTO_INCREMENT,
  `Numero_urap` int NOT NULL,
  `Avez_vous_eu_des_pertes_vaginales_ces_deux_derniers_mois` varchar(50) NOT NULL,
  `Avez_vous_eu_des_douleurs_au_bas_ventre_ces_deux_derniers_mois` varchar(50) NOT NULL,
  `Avez_vous_eu_des_plaies_vaginales_ces_deux_derniers_mois` varchar(50) NOT NULL,
  `Avez_vous_eu_mal_au_cours_des_derniers_rapport_sexuels` varchar(50) NOT NULL,
  `Antecedant_ist_genicologique_gestité` varchar(50) NOT NULL,
  `Antecedant_ist_genicologique_parité` varchar(50) NOT NULL,
  `Date_des_derniers_regles` varchar(50) NOT NULL,
  `Avez_vous_eu_des_ivgcette_annee_moins_d_un_an` varchar(50) NOT NULL,
  `Pratiquez_vous_une_toillette_vaginale_avec_les_doigts_` varchar(50) NOT NULL,
  `Si_oui_avec_quoi` varchar(50) NOT NULL,
  `Quel_tampon_utilisez_vous_pendant_les_regles` varchar(50) NOT NULL,
  `avez_vous` varchar(50) NOT NULL,
  `Prenez_vous_un_antibiotique_actuellement` varchar(50) NOT NULL,
  `autre` varchar(50) NOT NULL,
  `etes_vous_enceinte` varchar(10) NOT NULL,
  `qui_avez_vous_consulte` varchar(10) NOT NULL,
  `medicaments_prescrits` varchar(10) NOT NULL,
  `preciser_medicaments` varchar(10) NOT NULL,
  `duree_traitement` varchar(15) NOT NULL,
  `date_creation` date NOT NULL,
  `Praiquez_vous_une_toillette_vaginale_avec_les_doigt_` varchar(15) NOT NULL,
  `Quel_tampon_utilisez_vous_pandant_les_regles` varchar(15) NOT NULL,
  PRIMARY KEY (`ID_antecedents`),
  KEY `Numero_urap` (`Numero_urap`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `antecedents_ist_genicologiques`
--

INSERT INTO `antecedents_ist_genicologiques` (`ID_antecedents`, `Numero_urap`, `Avez_vous_eu_des_pertes_vaginales_ces_deux_derniers_mois`, `Avez_vous_eu_des_douleurs_au_bas_ventre_ces_deux_derniers_mois`, `Avez_vous_eu_des_plaies_vaginales_ces_deux_derniers_mois`, `Avez_vous_eu_mal_au_cours_des_derniers_rapport_sexuels`, `Antecedant_ist_genicologique_gestité`, `Antecedant_ist_genicologique_parité`, `Date_des_derniers_regles`, `Avez_vous_eu_des_ivgcette_annee_moins_d_un_an`, `Pratiquez_vous_une_toillette_vaginale_avec_les_doigts_`, `Si_oui_avec_quoi`, `Quel_tampon_utilisez_vous_pendant_les_regles`, `avez_vous`, `Prenez_vous_un_antibiotique_actuellement`, `autre`, `etes_vous_enceinte`, `qui_avez_vous_consulte`, `medicaments_prescrits`, `preciser_medicaments`, `duree_traitement`, `date_creation`, `Praiquez_vous_une_toillette_vaginale_avec_les_doigt_`, `Quel_tampon_utilisez_vous_pandant_les_regles`) VALUES
(1, 324, 'non', 'non', 'oui', 'non', '1', '1', '2025-08-01', 'non', '', '', '', '', '', '', 'Femme non ', 'Medecin', 'non', '', '', '2025-08-05', 'non', 'Serviettes hygi'),
(2, 125, 'oui', 'non', 'oui', 'oui', '1', '1', '2025-08-01', 'non', '', 'Autre', '', '', '', 'nataa', 'Femme non ', 'Pharmacien', 'oui', 'jhjhjh', '07jours', '2025-08-05', 'oui', 'Tampons(tampax)');

-- --------------------------------------------------------

--
-- Structure de la table `antecedents_ist_hommes`
--

DROP TABLE IF EXISTS `antecedents_ist_hommes`;
CREATE TABLE IF NOT EXISTS `antecedents_ist_hommes` (
  `ID_antecedent` int NOT NULL AUTO_INCREMENT,
  `Numero_urap` int NOT NULL,
  `antibiotique_actuel` varchar(100) NOT NULL,
  `preciser_antibiotique` varchar(100) NOT NULL,
  `date_creation` varchar(100) NOT NULL,
  `antecedent` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`ID_antecedent`),
  KEY `Numero_urap` (`Numero_urap`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `antecedents_ist_hommes`
--

INSERT INTO `antecedents_ist_hommes` (`ID_antecedent`, `Numero_urap`, `antibiotique_actuel`, `preciser_antibiotique`, `date_creation`, `antecedent`) VALUES
(1, 321, 'oui', 'qsdfghjk', '2025-08-05 12:35:25', 'deja ete atteint d\'u'),
(2, 323, 'oui', 'dfg', '2025-08-05 12:39:30', 'brulure au niveau de'),
(3, 123, 'oui', 'kiki brûlantium', '2025-08-05 13:26:50', 'deja ete atteint d\'u'),
(4, 123, 'non', '', '2025-08-05 13:28:27', 'deja ete atteint d\'une MST'),
(5, 789, 'oui', 'kiki brûlantium', '2025-08-05 13:57:27', 'deja ete atteint d\'une MST');

-- --------------------------------------------------------

--
-- Structure de la table `echantillon`
--

DROP TABLE IF EXISTS `echantillon`;
CREATE TABLE IF NOT EXISTS `echantillon` (
  `id_echantillon` int NOT NULL,
  `type_echantillon` varchar(25) NOT NULL,
  `date_prelevement` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `echantillon_femelle`
--

DROP TABLE IF EXISTS `echantillon_femelle`;
CREATE TABLE IF NOT EXISTS `echantillon_femelle` (
  `ID_echantillon_femelle` int NOT NULL,
  `Numero_urap` int NOT NULL,
  `type_echantillon` varchar(20) NOT NULL,
  `date_prelevement` date NOT NULL,
  `technicien` varchar(20) NOT NULL,
  PRIMARY KEY (`ID_echantillon_femelle`),
  KEY `Numero_urap` (`Numero_urap`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `echantillon_male`
--

DROP TABLE IF EXISTS `echantillon_male`;
CREATE TABLE IF NOT EXISTS `echantillon_male` (
  `ID_echantillon_male` int NOT NULL,
  `Numero_urap` int NOT NULL,
  `type_echantillon1` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `date_prelevement1` date NOT NULL,
  `technicien1` varchar(25) NOT NULL,
  `type_echantillon2` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `date_prelevement2` date NOT NULL,
  `technicien2` varchar(20) NOT NULL,
  PRIMARY KEY (`ID_echantillon_male`),
  KEY `Numero_urap` (`Numero_urap`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `echantillon_male`
--

INSERT INTO `echantillon_male` (`ID_echantillon_male`, `Numero_urap`, `type_echantillon1`, `date_prelevement1`, `technicien1`, `type_echantillon2`, `date_prelevement2`, `technicien2`) VALUES
(0, 789, 'xtfgjhfghjkl', '2025-08-01', 'fd', 'mrtyuiop', '2025-04-02', 'Dr Fanta');

-- --------------------------------------------------------

--
-- Structure de la table `ecs`
--

DROP TABLE IF EXISTS `ecs`;
CREATE TABLE IF NOT EXISTS `ecs` (
  `numero_identification` int NOT NULL,
  `age` varchar(3) NOT NULL,
  `nom` varchar(30) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `medecin` varchar(30) NOT NULL,
  `couleur` varchar(25) NOT NULL,
  `nombre_leucocyte` varchar(50) NOT NULL,
  `spermatozoide` varchar(10) NOT NULL,
  `mobilite` varchar(35) NOT NULL,
  `parasite` varchar(35) NOT NULL,
  `cristaux` varchar(35) NOT NULL,
  `culture` varchar(45) NOT NULL,
  `espece_bacteriennes` varchar(45) NOT NULL,
  `titre` varchar(45) NOT NULL,
  `compte_rendu` varchar(50) NOT NULL,
  PRIMARY KEY (`numero_identification`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ecsu`
--

DROP TABLE IF EXISTS `ecsu`;
CREATE TABLE IF NOT EXISTS `ecsu` (
  `numero_identification` int NOT NULL,
  `age` varchar(3) NOT NULL,
  `nom` varchar(15) NOT NULL,
  `prenom` varchar(30) NOT NULL,
  `medecin` varchar(20) NOT NULL,
  `culture` varchar(50) NOT NULL,
  `ecoulement` varchar(25) NOT NULL,
  `frottis_polynu` varchar(50) NOT NULL,
  `cocci_gram_negatif` varchar(20) NOT NULL,
  `autres_flores` varchar(15) NOT NULL,
  PRIMARY KEY (`numero_identification`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `ecsu`
--

INSERT INTO `ecsu` (`numero_identification`, `age`, `nom`, `prenom`, `medecin`, `culture`, `ecoulement`, `frottis_polynu`, `cocci_gram_negatif`, `autres_flores`) VALUES
(789, '22', 'kouakou', 'laurince hermann styves', 'jolo', 'negatif', 'absence', '<5/champs', 'absence', 'absence');

-- --------------------------------------------------------

--
-- Structure de la table `examen sperme`
--

DROP TABLE IF EXISTS `examen sperme`;
CREATE TABLE IF NOT EXISTS `examen sperme` (
  `id_examen_sperme` int NOT NULL,
  `Examen_microscopique_examen_état_frais_nombre_leucocyte` varchar(20) NOT NULL,
  `Examen_microscopique_examen_état_frais_spermatozoïdes` varchar(25) NOT NULL,
  `Examen_macroscopique_couleur_1` varchar(30) NOT NULL,
  `Examen_microscopique_examen_état_frais_monilite` varchar(20) NOT NULL,
  `Examen_microscopique_examen_état_frais_parasite` varchar(25) NOT NULL,
  `Examen_microscopique_examen_état_frais_cristaux` varchar(20) NOT NULL,
  `Examen_microscopique_examen_état_frais_espèce_bactérienne_isolée` varchar(30) NOT NULL,
  `Examen_microscopique_examen_état_frais_titre` varchar(35) NOT NULL,
  `Culture` varchar(25) NOT NULL,
  `Nom_techniciens` varchar(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `examens_spermes`
--

DROP TABLE IF EXISTS `examens_spermes`;
CREATE TABLE IF NOT EXISTS `examens_spermes` (
  `id_examens` varchar(15) NOT NULL,
  `Âge` int NOT NULL,
  `Nom` varchar(25) NOT NULL,
  `Prénom` varchar(35) NOT NULL,
  `Médecin_presc` varchar(20) NOT NULL,
  `Exa_Macro_Couleur` varchar(10) NOT NULL,
  `Exa_Micro_Nbre_leuco` varchar(5) NOT NULL,
  `Exa_Micro_Sperma` varchar(15) NOT NULL,
  `Exa_Micro_Mobilité` varchar(10) NOT NULL,
  `Exa_Micro_Parasite` varchar(10) NOT NULL,
  `Exa_Micro_Cristaux` varchar(10) NOT NULL,
  `Exa_Micro_Culture` varchar(10) NOT NULL,
  `Exa_Micro_Espèces bactériennes isolées` varchar(10) NOT NULL,
  `Exa_Micro_Titre` varchar(25) NOT NULL,
  `Comp_Rend_Analyse` varchar(100) NOT NULL,
  PRIMARY KEY (`id_examens`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `examen_cytobacterologique_secretion_cervico_vaginale`
--

DROP TABLE IF EXISTS `examen_cytobacterologique_secretion_cervico_vaginale`;
CREATE TABLE IF NOT EXISTS `examen_cytobacterologique_secretion_cervico_vaginale` (
  `id_examen` int NOT NULL,
  `Examen_macroscopique_muqueuse_vaginale` varchar(25) NOT NULL,
  `Examen_macroscopique_écoulement_vaginal_abondance` varchar(20) NOT NULL,
  `Examen_macroscopique_écoulement_vaginal_aspect` varchar(30) NOT NULL,
  `Examen_macroscopique_écoulement_vaginal_couleur` varchar(50) NOT NULL,
  `Examen_macroscopique_écoulement_vaginal_odeur` varchar(20) NOT NULL,
  `Examen_macroscopique_ph` varchar(25) NOT NULL,
  `Examen_macroscopique_test_potasse` varchar(15) NOT NULL,
  `Examen_macroscopique_exocol` varchar(20) NOT NULL,
  `Examen_microscopique_écoulement_cervical` varchar(25) NOT NULL,
  `Examen_microscopique_secrétions_vaginal_examens_état_frais_cellu` varchar(50) NOT NULL,
  `Examen_microscopique_secrétions_vaginal_examens_état_frais_trich` varchar(30) NOT NULL,
  `Examen_macroscopique_secrétions_vaginal_examens_état_frais_leuco` varchar(40) NOT NULL,
  `Examen_macroscopique_secrétions_vaginal_examens_etat_frais_levur` varchar(20) NOT NULL,
  `Examen_macroscopique_secrétions_vaginal_frottis_colore_gram_flor` varchar(50) NOT NULL,
  `Examen_macroscopique_secrétions_vaginal_frottis_colore_gram_poly` varchar(20) NOT NULL,
  `Examen_macroscopique_secrétions_vaginal_frottis_colore_gram_mob` varchar(20) NOT NULL,
  `Examen_macroscopique_secrétions_vaginal_frottis_colore_gram_sco` varchar(50) NOT NULL,
  `Examen_macroscopique_secrétions_endocervicale_polynucléaire` varchar(30) NOT NULL,
  `Examen_macroscopique_secrétions _endocervicale_lymphocyte` varchar(20) NOT NULL,
  `Interprétation` varchar(30) NOT NULL,
  `Culture_sécrétion_vaginale` varchar(20) NOT NULL,
  `Culture_sécrétion_cervicale` varchar(50) NOT NULL,
  `Nom_techniciens` varchar(20) NOT NULL,
  PRIMARY KEY (`id_examen`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `examen_secretion_uretrale`
--

DROP TABLE IF EXISTS `examen_secretion_uretrale`;
CREATE TABLE IF NOT EXISTS `examen_secretion_uretrale` (
  `id_examen_secretion_uretrale` int NOT NULL,
  `Examen_macroscopique_écoulement` varchar(25) NOT NULL,
  `Examen_macroscopique_sécrétion_urétrale_frottis_coloré_gram_poly` varchar(30) NOT NULL,
  `Examen_macroscopique_sécrétion_urétrale_frottis_coloré_gram_cocc` varchar(25) NOT NULL,
  `Examen_macroscopique_sécrétion_urétrale_frottis_coloré_gram_autr` varchar(25) NOT NULL,
  `Culture_sécrétion_urétrale` varchar(30) NOT NULL,
  `Nom_techniciens` varchar(15) NOT NULL,
  `Interprétation` varchar(35) NOT NULL,
  PRIMARY KEY (`id_examen_secretion_uretrale`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `exa_cyto_sec_vag`
--

DROP TABLE IF EXISTS `exa_cyto_sec_vag`;
CREATE TABLE IF NOT EXISTS `exa_cyto_sec_vag` (
  `numero_identification` int NOT NULL,
  `nom` varchar(15) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `age` varchar(25) NOT NULL,
  `medecin` varchar(30) NOT NULL,
  `muqueuse_vaginale` varchar(25) NOT NULL,
  `ecoulement_vaginal` varchar(30) NOT NULL,
  `abondance` varchar(25) NOT NULL,
  `aspect` varchar(10) NOT NULL,
  `odeur` varchar(20) NOT NULL,
  `couleur` varchar(15) NOT NULL,
  `test_potasse` varchar(15) NOT NULL,
  `ph` varchar(20) NOT NULL,
  `exocol` varchar(20) NOT NULL,
  `ecoulement_cervical` varchar(15) NOT NULL,
  `cellules_epitheliales` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `trichomonas` varchar(30) NOT NULL,
  `leucocytes` varchar(35) NOT NULL,
  `levure` varchar(25) NOT NULL,
  `polynucleaires` varchar(20) NOT NULL,
  `flore_vaginale` varchar(15) NOT NULL,
  `clue_cells` varchar(25) NOT NULL,
  `mobiluncus` varchar(30) NOT NULL,
  `score` varchar(15) NOT NULL,
  `polynucleaires_endo` varchar(20) NOT NULL,
  `lymphocytes` varchar(15) NOT NULL,
  `secretions_vaginales` varchar(20) NOT NULL,
  `secretions_cervicales` varchar(15) NOT NULL,
  PRIMARY KEY (`numero_identification`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `exa_cyto_sec_vag`
--

INSERT INTO `exa_cyto_sec_vag` (`numero_identification`, `nom`, `prenom`, `age`, `medecin`, `muqueuse_vaginale`, `ecoulement_vaginal`, `abondance`, `aspect`, `odeur`, `couleur`, `test_potasse`, `ph`, `exocol`, `ecoulement_cervical`, `cellules_epitheliales`, `trichomonas`, `leucocytes`, `levure`, `polynucleaires`, `flore_vaginale`, `clue_cells`, `mobiluncus`, `score`, `polynucleaires_endo`, `lymphocytes`, `secretions_vaginales`, `secretions_cervicales`) VALUES
(789, 'kouakou', 'laurince hermann sty', '22', 'jolo', 'Absence de lésion', 'Absent', 'Faible', 'Homogène', 'Normale', 'blanche', 'Négatif', '6', 'Normal', 'Normal', 'oiohiohio', 'Absence', 'polio', 'Absence', '', 'Flore de Doderl', 'Absence', 'Absence', '', 'virus', 'ebola', 'Absence', 'Absence');

-- --------------------------------------------------------

--
-- Structure de la table `habitude_sexuelles`
--

DROP TABLE IF EXISTS `habitude_sexuelles`;
CREATE TABLE IF NOT EXISTS `habitude_sexuelles` (
  `ID_habitude_sexuelles` int NOT NULL AUTO_INCREMENT,
  `Numero_urap` int NOT NULL,
  `Quel_type_rapport_avez_vous` varchar(50) NOT NULL,
  `Pratiquez_vous__fellation` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Avez_vous_changé_partenais_ces_deux_dernier_mois` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Utilisez_vous_preservatif` varchar(50) NOT NULL,
  `Pratiquez_vous_cunilingus` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`ID_habitude_sexuelles`),
  KEY `Numero_urap` (`Numero_urap`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `habitude_sexuelles`
--

INSERT INTO `habitude_sexuelles` (`ID_habitude_sexuelles`, `Numero_urap`, `Quel_type_rapport_avez_vous`, `Pratiquez_vous__fellation`, `Avez_vous_changé_partenais_ces_deux_dernier_mois`, `Utilisez_vous_preservatif`, `Pratiquez_vous_cunilingus`) VALUES
(1, 197, 'Hétérosexuel', 'Rarement', 'Non', 'Rarement', ''),
(2, 198, 'Hétérosexuel', 'Rarement', 'Non', 'Rarement', ''),
(7, 789, 'Hétérosexuel', 'Jamais', 'Non', 'Toujours', '');

-- --------------------------------------------------------

--
-- Structure de la table `histoire_maladie`
--

DROP TABLE IF EXISTS `histoire_maladie`;
CREATE TABLE IF NOT EXISTS `histoire_maladie` (
  `ID_histoire_maladie` int NOT NULL AUTO_INCREMENT,
  `Numero_urap` int NOT NULL,
  `sexe_patient` varchar(10) NOT NULL,
  `motif_homme` varchar(50) NOT NULL,
  `motif_femme` varchar(50) NOT NULL,
  `signes_fonctionnels` varchar(50) NOT NULL,
  `date_creation` date NOT NULL,
  PRIMARY KEY (`ID_histoire_maladie`),
  KEY `Numero_urap` (`Numero_urap`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `histoire_maladie`
--

INSERT INTO `histoire_maladie` (`ID_histoire_maladie`, `Numero_urap`, `sexe_patient`, `motif_homme`, `motif_femme`, `signes_fonctionnels`, `date_creation`) VALUES
(1, 123, 'masculin', 'gene_uretral', '', 'prurit', '2025-08-05'),
(2, 141, 'feminin', '', 'agent_contaminateur', 'mal_odeur', '2025-08-05'),
(3, 789, 'masculin', 'amp', '', 'prurit', '2025-08-05');

-- --------------------------------------------------------

--
-- Structure de la table `patient`
--

DROP TABLE IF EXISTS `patient`;
CREATE TABLE IF NOT EXISTS `patient` (
  `Numero_urap` int NOT NULL,
  `Nom_patient` varchar(15) NOT NULL,
  `Prenom_patient` varchar(30) NOT NULL,
  `Age` int NOT NULL,
  `Sexe_patient` varchar(10) NOT NULL,
  `Date_naissance` date NOT NULL,
  `Contact_patient` varchar(10) NOT NULL,
  `Situation_matrimoniale` varchar(20) NOT NULL,
  `Lieu_résidence` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Precise` varchar(30) NOT NULL,
  `Type_logement` varchar(25) NOT NULL,
  `Niveau_etude` varchar(30) NOT NULL,
  `Profession` varchar(25) NOT NULL,
  PRIMARY KEY (`Numero_urap`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `patient`
--

INSERT INTO `patient` (`Numero_urap`, `Nom_patient`, `Prenom_patient`, `Age`, `Sexe_patient`, `Date_naissance`, `Contact_patient`, `Situation_matrimoniale`, `Lieu_résidence`, `Precise`, `Type_logement`, `Niveau_etude`, `Profession`) VALUES
(789, 'kouakou', 'laurince hermann styves', 22, 'Masculin', '2002-09-22', '0779156688', 'Célibataire', 'Abidjan', '', 'Studio', 'Aucun', 'Aucun');

-- --------------------------------------------------------

--
-- Structure de la table `prescriteur`
--

DROP TABLE IF EXISTS `prescriteur`;
CREATE TABLE IF NOT EXISTS `prescriteur` (
  `ID_prescripteur` int NOT NULL,
  `Nom` varchar(150) NOT NULL,
  `Prenom` varchar(150) NOT NULL,
  `Contact` varchar(50) DEFAULT NULL,
  `Structure_provenance` varchar(50) NOT NULL,
  PRIMARY KEY (`ID_prescripteur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `prescriteur`
--

INSERT INTO `prescriteur` (`ID_prescripteur`, `Nom`, `Prenom`, `Contact`, `Structure_provenance`) VALUES
(0, 'jolo', '', NULL, 'ipci');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `Id_user` int NOT NULL AUTO_INCREMENT,
  `Nom_user` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Prenom_user` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Email_user` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Mdp_user` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Type_user` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Etat_user` int DEFAULT NULL,
  `Contact_user` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Login_user` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`Id_user`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`Id_user`, `Nom_user`, `Prenom_user`, `Email_user`, `Mdp_user`, `Type_user`, `Etat_user`, `Contact_user`, `Login_user`) VALUES
(1, 'sidick', 'nico', '', '123', 'ADMIN', 1, '12323265', 'Uatg'),
(2, 'LOBA', 'VINCENT', '', '1234', 'TECHNICIEN', 0, '0769269246', 'titi'),
(3, 'Berte', 'Maminin ', '', 'Fanta', 'ADMIN', 1, '0584903726', 'Fanta12');

-- --------------------------------------------------------

--
-- Structure de la table `visite`
--

DROP TABLE IF EXISTS `visite`;
CREATE TABLE IF NOT EXISTS `visite` (
  `id_visite` int NOT NULL,
  `date_visite` date NOT NULL,
  `Heure visite` varchar(10) NOT NULL,
  `Motif visite` varchar(20) NOT NULL,
  `Numero_urap` varchar(15) NOT NULL,
  `ID_prescripteur` int DEFAULT NULL,
  `Structure_provenance` varchar(50) DEFAULT NULL,
  `ID_antecedents` int DEFAULT NULL,
  `ID_antecedent` int NOT NULL,
  `ID_histoire_maladie` int DEFAULT NULL,
  `ID_habitude_sexuelles` int DEFAULT NULL,
  PRIMARY KEY (`id_visite`),
  KEY `ID_prescripteur` (`ID_prescripteur`),
  KEY `ID_antecedants` (`ID_antecedents`,`ID_histoire_maladie`,`ID_habitude_sexuelles`),
  KEY `ID_antecedents` (`ID_antecedents`),
  KEY `ID_antecedent` (`ID_antecedent`),
  KEY `Numero_urap` (`Numero_urap`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `visite`
--

INSERT INTO `visite` (`id_visite`, `date_visite`, `Heure visite`, `Motif visite`, `Numero_urap`, `ID_prescripteur`, `Structure_provenance`, `ID_antecedents`, `ID_antecedent`, `ID_histoire_maladie`, `ID_habitude_sexuelles`) VALUES
(1, '2025-08-05', '13:36', 'controle', '789', 0, 'ipci', NULL, 0, NULL, NULL),
(2, '2025-08-05', '13:36', 'controle', '789', 0, 'ipci', NULL, 0, NULL, NULL),
(3, '2025-08-05', '13:36', 'controle', '789', 0, 'ipci', NULL, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `visite_echantillon`
--

DROP TABLE IF EXISTS `visite_echantillon`;
CREATE TABLE IF NOT EXISTS `visite_echantillon` (
  `id_visite` int NOT NULL,
  `id_echantillon` int NOT NULL,
  PRIMARY KEY (`id_visite`,`id_echantillon`),
  KEY `id_echantillon` (`id_echantillon`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `visite_examen`
--

DROP TABLE IF EXISTS `visite_examen`;
CREATE TABLE IF NOT EXISTS `visite_examen` (
  `id_visite` int NOT NULL,
  `id_examen` int NOT NULL,
  PRIMARY KEY (`id_visite`,`id_examen`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
