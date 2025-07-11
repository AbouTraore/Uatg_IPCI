-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 10 juin 2025 à 08:14
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
CREATE DATABASE IF NOT EXISTS `uatg` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `uatg`;

-- --------------------------------------------------------

--
-- Structure de la table `antecedents_ist_genicologiques`
--

DROP TABLE IF EXISTS `antecedents_ist_genicologiques`;
CREATE TABLE IF NOT EXISTS `antecedents_ist_genicologiques` (
  `Numero_urap` int NOT NULL,
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
  PRIMARY KEY (`Numero_urap`)
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
-- Structure de la table `habitude_sexuelles`
--

DROP TABLE IF EXISTS `habitude_sexuelles`;
CREATE TABLE IF NOT EXISTS `habitude_sexuelles` (
  `Numero_urap` int NOT NULL,
  `Quel_type_rapport_avez_vous_` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Pratiquez_vous__fellation` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Avez_vous_changé_partenais_ces_deux_dernier_mois` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Utilisez_vous_preservatif` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Pratqez_le_cunni` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`Numero_urap`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `histoire_maladie_recours_soins`
--

DROP TABLE IF EXISTS `histoire_maladie_recours_soins`;
CREATE TABLE IF NOT EXISTS `histoire_maladie_recours_soins` (
  `Numero_urap` int NOT NULL,
  `Motif_consultation` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Signe_fonctionnels` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Qui_avez_vous_consulté_pour_ces_signes` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Vous_a_t_il_prescrit_des_medicaments` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Si_oui_preciser` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Depuis_combien_de_temps_vous_vous_traitez` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`Numero_urap`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `patient`
--

DROP TABLE IF EXISTS `patient`;
CREATE TABLE IF NOT EXISTS `patient` (
  `Numero_urap` varchar(15) NOT NULL,
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
  `Profession` varchar(25) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `patient`
--

INSERT INTO `patient` (`Numero_urap`, `Nom_patient`, `Prenom_patient`, `Age`, `Sexe_patient`, `Date_naissance`, `Contact_patient`, `Situation_matrimoniale`, `Lieu_résidence`, `Precise`, `Type_logement`, `Niveau_etude`, `Profession`) VALUES
('123', 'traore', 'VINCENT', 24, 'Masculin', '0001-10-02', '0799125799', 'Marié', 'Abidjan', '', 'Baraquement', 'Aucun', 'Sans profession'),
('1234567895', 'traore', 'Abou', 24, 'Masculin', '0001-10-02', '0799125799', 'Marié', 'Abidjan', '', 'Baraquement', 'Aucun', 'Aucun'),
('542698752213', 'BERTE', 'Fanta', 23, 'Féminin', '2002-03-12', '0584903726', 'Marié', 'Abidjan', '', 'Villa', 'Universitaire', 'Cadre superieur'),
('88888888888', 'AMANI', 'HUBERTINE', 24, 'Féminin', '2001-03-26', '0769269246', 'Marié', 'Abidjan', '', 'Baraquement', 'Aucun', 'Aucun'),
('1458546', 'kouakou', 'Laurince', 22, 'Masculin', '2002-09-02', '02415748931', 'Marié', 'Hors Abidjan', 'Bouake', 'Villa', 'Universitaire', 'Corps habillé'),
('123', 'abou', 'il est ou', 23, 'Masculin', '2001-10-03', '010230405', 'Marié', 'Abidjan', '', 'Baraquement', 'Aucun', 'Aucun');

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
