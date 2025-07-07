-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 10 juin 2025 à 14:16
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
  `ID_antecedants` int NOT NULL,
  `Avez_vous_eu_des_pertes_vaginales_ces_deux_derniers_mois` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Avez_vous_eu_des_douleurs_au_bas_ventre_ces_deux_derniers_mois` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Avez_vous_eu_des_plaies_vaginales_ces_deux_derniers_mois` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Avez_vous_eu_mal_au_cours_des_derniers_rapport_sexuels` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Antecedant_ist_genicologique_gestité` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Antecedant_ist_genicologique_parité` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Date_des_derniers_regles` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Avez_vous_eu_des_ivgcette_annee__moins_d_un_an_` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Praiquez_vous_une_toillette_vaginale_avec_les_doigt_` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Si_oui_avec_quoi` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Quel_tampon_utilisez_vous_pandant_les_regles` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `avez_vous` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Prenez_vous_un_antibiotique_actullement` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`ID_antecedants`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `type_echantillon` varchar(30) NOT NULL,
  `date_prelevement` date NOT NULL,
  `technicien` varchar(25) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `echantillon_femelle`
--

INSERT INTO `echantillon_femelle` (`type_echantillon`, `date_prelevement`, `technicien`) VALUES
('RTTYUI', '2025-02-20', 'Dr Fanta');

-- --------------------------------------------------------

--
-- Structure de la table `echantillon_male`
--

DROP TABLE IF EXISTS `echantillon_male`;
CREATE TABLE IF NOT EXISTS `echantillon_male` (
  `type_echantillon1` varchar(30) NOT NULL,
  `date_prelevement1` date NOT NULL,
  `technicien1` varchar(25) NOT NULL,
  `type_echantillon2` varchar(30) NOT NULL,
  `date_prelevement2` date NOT NULL,
  `technicien2` varchar(25) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `echantillon_male`
--

INSERT INTO `echantillon_male` (`type_echantillon1`, `date_prelevement1`, `technicien1`, `type_echantillon2`, `date_prelevement2`, `technicien2`) VALUES
('xtfgjhfghjkl', '2025-06-06', 'fd', '', '0000-00-00', ''),
('xtfgjhfghjkl', '2025-06-06', 'fd', 'mrtyuiop', '2025-06-06', 'Dr Fanta'),
('xtfgjhfghjkl', '2025-06-06', 'fd', '', '0000-00-00', '');

-- --------------------------------------------------------

--
-- Structure de la table `ecs`
--

DROP TABLE IF EXISTS `ecs`;
CREATE TABLE IF NOT EXISTS `ecs` (
  `numero_identification` varchar(25) NOT NULL,
  `age` varchar(3) NOT NULL,
  `nom` varchar(30) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `medecin` varchar(30) NOT NULL,
  `couleur` varchar(30) NOT NULL,
  `nombre_leucocyte` varchar(10) NOT NULL,
  `spermatozoide` varchar(35) NOT NULL,
  `mobilite` varchar(35) NOT NULL,
  `parasite` varchar(35) NOT NULL,
  `cristaux` varchar(45) NOT NULL,
  `culture` varchar(45) NOT NULL,
  `especes_bacteriennes` varchar(45) NOT NULL,
  `titre` varchar(60) NOT NULL,
  `compte_rendu` varchar(300) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `ecs`
--

INSERT INTO `ecs` (`numero_identification`, `age`, `nom`, `prenom`, `medecin`, `couleur`, `nombre_leucocyte`, `spermatozoide`, `mobilite`, `parasite`, `cristaux`, `culture`, `especes_bacteriennes`, `titre`, `compte_rendu`) VALUES
('4555', '52', 'kouako', 'laurjnj', 'prkr,kr', 'blanchatre', '<5', 'moyen', 'ktkltk', 'absence', 'absence', 'negative', 'absence', 'hgigui', 'igjjoop('),
('4555', '52', 'kouako', 'laurjnj', 'prkr,kr', 'blanchatre', '<5', 'moyen', 'ktkltk', 'absence', 'absence', 'negative', 'absence', 'hgigui', 'igjjoop('),
('4555', '52', 'kouako', 'laurjnj', 'prkr,kr', 'blanchatre', '<5', 'moyen', 'ktkltk', 'absence', 'absence', 'negative', 'absence', 'hgigui', 'igjjoop(');

-- --------------------------------------------------------

--
-- Structure de la table `ecsu`
--

DROP TABLE IF EXISTS `ecsu`;
CREATE TABLE IF NOT EXISTS `ecsu` (
  `numero_identification` varchar(15) NOT NULL,
  `age` int NOT NULL,
  `nom` varchar(25) NOT NULL,
  `prenom` varchar(3) NOT NULL,
  `medecin` varchar(17) NOT NULL,
  `culture` varchar(28) NOT NULL,
  `ecoulement` varchar(25) NOT NULL,
  `frottis_polynu` varchar(25) NOT NULL,
  `cocci_gram_negatif` varchar(25) NOT NULL,
  `autres_flores` varchar(25) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `ecsu`
--

INSERT INTO `ecsu` (`numero_identification`, `age`, `nom`, `prenom`, `medecin`, `culture`, `ecoulement`, `frottis_polynu`, `cocci_gram_negatif`, `autres_flores`) VALUES
('155555', 25, 'YAO', 'PRI', 'ABOU', 'absence', '<5/champs', 'absence', 'absence', 'negatif');

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
  `Comp_Rend_Analyse` varchar(100) NOT NULL
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
  `Nom_techniciens` varchar(20) NOT NULL
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
  `Interprétation` varchar(35) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `exa_cyto_sec_vag`
--

DROP TABLE IF EXISTS `exa_cyto_sec_vag`;
CREATE TABLE IF NOT EXISTS `exa_cyto_sec_vag` (
  `numero_identification` varchar(10) NOT NULL,
  `nom` varchar(15) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `age` varchar(10) NOT NULL,
  `medecin` varchar(25) NOT NULL,
  `muqueuse_vaginale` varchar(25) NOT NULL,
  `ecoulement_vaginal` varchar(30) NOT NULL,
  `abondance` varchar(25) NOT NULL,
  `aspect` varchar(15) NOT NULL,
  `odeur` varchar(15) NOT NULL,
  `couleur` varchar(10) NOT NULL,
  `test_potasse` varchar(15) NOT NULL,
  `ph` varchar(10) NOT NULL,
  `exocol` varchar(15) NOT NULL,
  `ecoulement_cervical` varchar(20) NOT NULL,
  `cellules_epitheliales` varchar(25) NOT NULL,
  `trichomonas` varchar(15) NOT NULL,
  `leucocytes` varchar(10) NOT NULL,
  `levure` varchar(15) NOT NULL,
  `polynucleaires` varchar(15) NOT NULL,
  `flore_vaginale` varchar(10) NOT NULL,
  `clue_cells` varchar(15) NOT NULL,
  `mobiluncus` varchar(20) NOT NULL,
  `score` varchar(25) NOT NULL,
  `polynucleaires_endo` varchar(20) NOT NULL,
  `lymphocytes` varchar(15) NOT NULL,
  `secretions_vaginales` varchar(25) NOT NULL,
  `secretions_cervicales` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `exa_cyto_sec_vag`
--

INSERT INTO `exa_cyto_sec_vag` (`numero_identification`, `nom`, `prenom`, `age`, `medecin`, `muqueuse_vaginale`, `ecoulement_vaginal`, `abondance`, `aspect`, `odeur`, `couleur`, `test_potasse`, `ph`, `exocol`, `ecoulement_cervical`, `cellules_epitheliales`, `trichomonas`, `leucocytes`, `levure`, `polynucleaires`, `flore_vaginale`, `clue_cells`, `mobiluncus`, `score`, `polynucleaires_endo`, `lymphocytes`, `secretions_vaginales`, `secretions_cervicales`) VALUES
('4555', 'kouakou', 'john', '55', 'nrenojoojr', 'Normale', 'OUI', 'Minime', 'Crémeux', 'Inodore', 'Blanchtre', 'Positif', '', 'NORMALE', 'ABSENT', '', 'Abscence', '<05/champ', 'Abscence', '', 'Normal typ', 'Abscence', 'Abscence', '', '<05/champ', '', 'Absence de colonie de lev', 'Absence de colonie s');

-- --------------------------------------------------------

--
-- Structure de la table `habitude_sexuelles`
--

DROP TABLE IF EXISTS `habitude_sexuelles`;
CREATE TABLE IF NOT EXISTS `habitude_sexuelles` (
  `ID_habitude` int NOT NULL,
  `Quel_type_rapport_avez_vous_` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Pratiquez_vous__fellation` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Avez_vous_changé_partenais_ces_deux_dernier_mois` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Utilisez_vous_preservatif` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Pratqez_le_cunni` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`ID_habitude`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `histoire_maladie_recours_soins`
--

DROP TABLE IF EXISTS `histoire_maladie_recours_soins`;
CREATE TABLE IF NOT EXISTS `histoire_maladie_recours_soins` (
  `ID_histoire_maladie` int NOT NULL,
  `Motif_consultation` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Signe_fonctionnels` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Qui_avez_vous_consulté_pour_ces_signes` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Vous_a_t_il_prescrit_des_medicaments` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Si_oui_preciser` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Depuis_combien_de_temps_vous_vous_traitez` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`ID_histoire_maladie`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `patient`
--

DROP TABLE IF EXISTS `patient`;
CREATE TABLE IF NOT EXISTS `patient` (
  `Numero_urap` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `Nom_patient` varchar(20) NOT NULL,
  `Prenom_patient` varchar(30) NOT NULL,
  `Age` int NOT NULL,
  `Sexe_patient` varchar(10) NOT NULL,
  `Date_naissance` date NOT NULL,
  `Contact_patient` varchar(15) NOT NULL,
  `Situation_matrimoniale` varchar(25) NOT NULL,
  `Lieu_résidence` varchar(30) NOT NULL,
  `Precise` varchar(35) NOT NULL,
  `Type_logement` varchar(25) NOT NULL,
  `Niveau_etude` varchar(15) NOT NULL,
  `Profession` varchar(25) NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `patient`
--

INSERT INTO `patient` (`Numero_urap`, `Nom_patient`, `Prenom_patient`, `Age`, `Sexe_patient`, `Date_naissance`, `Contact_patient`, `Situation_matrimoniale`, `Lieu_résidence`, `Precise`, `Type_logement`, `Niveau_etude`, `Profession`, `date_creation`) VALUES
('123', 'Traoré', 'Abou', 24, 'Masculin', '2001-01-02', '0102030405', 'Marié', 'Abidjan', '', 'Baraquement', 'Aucun', 'Aucun', '2025-06-10 14:16:00'),
('123', 'Traoré', 'Abou', 24, 'Masculin', '2001-01-02', '0102030405', 'Marié', 'Abidjan', '', 'Baraquement', 'Aucun', 'Aucun', '2025-06-10 14:16:00'),
('123', 'kpouadio', 'fanta', 24, 'Féminin', '2001-01-02', '0102030405', 'Marié', 'Abidjan', '', 'Baraquement', 'Aucun', 'Aucun', '2025-06-10 14:16:00'),
('joiutj', 'euguigi', 'erpuigeuigz', 23, 'Masculin', '2020-05-20', '020000', 'Marié', 'Abidjan', '', 'Baraquement', 'Aucun', 'Aucun', '2025-06-10 14:16:00');

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

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `Id_user` int NOT NULL AUTO_INCREMENT,
  `Nom_user` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Prenom_user` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Email_user` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Mdp_user` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Type_user` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Etat_user` int DEFAULT NULL,
  `Contact_user` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Login_user` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`Id_user`),
  UNIQUE KEY `Email_user` (`Email_user`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`Id_user`, `Nom_user`, `Prenom_user`, `Email_user`, `Mdp_user`, `Type_user`, `Etat_user`, `Contact_user`, `Login_user`) VALUES
(1, 'sidick', 'nico', 'abou@gmail.com', '123', 'TECHNICIEN', 1, '12323265', 'Uatg'),
(2, 'LOBA', 'VINCENT', 'amanihubertine403@gmail.com', '1234', 'TECHNICIEN', 0, '0769269246', 'titi'),
(3, 'Berte', 'Maminin ', 'bertefanta1203@gmail.com', 'Fanta', 'ADMIN', 0, '0584903726', 'Fanta12');

-- --------------------------------------------------------

--
-- Structure de la table `visite`
--

DROP TABLE IF EXISTS `visite`;
CREATE TABLE IF NOT EXISTS `visite` (
  `id_visite` int NOT NULL,
  `Date visite` date NOT NULL,
  `Heure visite` varchar(10) NOT NULL,
  `Motif visite` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
