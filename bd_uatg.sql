-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 24 juil. 2025 à 11:02
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
  `autre` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `etes_vous_enceinte` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `Avez_vous_eu_des_ivgcette_annee_moins_d_un_an` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `qui_avez_vous_consulte` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `medicaments_prescrits` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `preciser_medicaments` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `duree_traitement` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `date_creation` date NOT NULL,
  PRIMARY KEY (`Numero_urap`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `antecedents_ist_genicologiques`
--

INSERT INTO `antecedents_ist_genicologiques` (`Numero_urap`, `Avez_vous_eu_des_pertes_vaginales_ces_deux_derniers_mois`, `Avez_vous_eu_des_douleurs_au_bas_ventre_ces_deux_derniers_mois`, `Avez_vous_eu_des_plaies_vaginales_ces_deux_derniers_mois`, `Avez_vous_eu_mal_au_cours_des_derniers_rapport_sexuels`, `Antecedant_ist_genicologique_gestité`, `Antecedant_ist_genicologique_parité`, `Date_des_derniers_regles`, `Avez_vous_eu_des_ivgcette_annee__moins_d_un_an_`, `Praiquez_vous_une_toillette_vaginale_avec_les_doigt_`, `Si_oui_avec_quoi`, `Quel_tampon_utilisez_vous_pandant_les_regles`, `avez_vous`, `Prenez_vous_un_antibiotique_actullement`, `autre`, `etes_vous_enceinte`, `Avez_vous_eu_des_ivgcette_annee_moins_d_un_an`, `qui_avez_vous_consulte`, `medicaments_prescrits`, `preciser_medicaments`, `duree_traitement`, `date_creation`) VALUES
(206, 'non', 'oui', 'non', 'non', '4', '4', '2025-07-02', '', 'non', '', 'Serviettes hygieniques', NULL, NULL, '', 'Femme non ', 'non', 'Medecin', 'non', '', '', '2025-07-12');

-- --------------------------------------------------------

--
-- Structure de la table `antecedents_ist_hommes`
--

DROP TABLE IF EXISTS `antecedents_ist_hommes`;
CREATE TABLE IF NOT EXISTS `antecedents_ist_hommes` (
  `Numero_urap` int NOT NULL,
  `antecedent` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `antibiotique_actuel` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `preciser_antibiotique` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Numero_urap`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `antecedents_ist_hommes`
--

INSERT INTO `antecedents_ist_hommes` (`Numero_urap`, `antecedent`, `antibiotique_actuel`, `preciser_antibiotique`, `date_creation`) VALUES
(197, 'deja ete atteint d\'une MST', 'non', '', '2025-07-10 09:12:41'),
(198, 'brulure au niveau des organes genitaux', 'oui', 'kiki brûlantium', '2025-07-10 19:08:36'),
(200, 'eu des traumatismes testiculaires', 'oui', 'kiki brûlantium', '2025-07-12 12:08:17'),
(203, 'deja ete atteint d\'une MST', 'oui', 'kiki brûlantium', '2025-07-15 12:55:52'),
(205, 'brulure au niveau des organes genitaux', 'oui', 'kiki brûlantium', '2025-07-12 12:36:45'),
(207, 'brulure au niveau des organes genitaux', 'oui', 'qsdf', '2025-07-12 12:50:07'),
(789, 'deja ete atteint d\'une MST', 'oui', 'kiki brûlantium', '2025-07-21 20:58:09'),
(800, 'deja ete atteint d\'une MST', 'oui', 'kiki brûlantium', '2025-07-24 10:44:36');

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
('RTTYUI', '2025-02-20', 'Dr Fanta'),
('vaginale', '2025-07-22', 'Dr Fanta'),
('vaginale', '2025-07-22', 'Dr Fanta'),
('vaginale', '2025-07-22', 'Dr Fanta');

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
  `technicien2` varchar(25) NOT NULL,
  `numero_urap` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `echantillon_male`
--

INSERT INTO `echantillon_male` (`type_echantillon1`, `date_prelevement1`, `technicien1`, `type_echantillon2`, `date_prelevement2`, `technicien2`, `numero_urap`) VALUES
('xtfgjhfghjkl', '2025-06-06', 'fd', '', '0000-00-00', '', ''),
('xtfgjhfghjkl', '2025-06-06', 'fd', 'mrtyuiop', '2025-06-06', 'Dr Fanta', ''),
('xtfgjhfghjkl', '2025-06-06', 'fd', '', '0000-00-00', '', ''),
('xtfgjhfghjkl', '2025-07-08', 'fd', 'mrtyuiop', '2025-07-17', 'Dr Fanta', ''),
('xtfgjhfghjkl', '2025-07-08', 'fd', 'mrtyuiop', '2025-07-17', 'Dr Fanta', ''),
('xtfgjhfghjkl', '2025-07-08', 'fd', 'mrtyuiop', '2025-07-17', 'Dr Fanta', ''),
('xtfgjhfghjkl', '2025-07-08', 'fd', 'mrtyuiop', '2025-07-17', 'Dr Fanta', ''),
('xtfgjhfghjkl', '2025-07-08', 'fd', 'mrtyuiop', '2025-07-17', 'Dr Fanta', '790'),
('xtfgjhfghjkl', '2025-07-08', 'fd', 'mrtyuiop', '2025-07-17', 'Dr Fanta', '790'),
('xtfgjhfghjkl', '2025-07-08', 'fd', 'mrtyuiop', '2025-07-17', 'Dr Fanta', '790'),
('xtfgjhfghjkl', '2025-07-08', 'fd', 'mrtyuiop', '2025-07-17', 'Dr Fanta', '789'),
('xtfgjhfghjkl', '2025-07-08', 'fd', 'mrtyuiop', '2025-07-17', 'Dr Fanta', '790'),
('xtfgjhfghjkl', '2025-07-08', 'fd', 'mrtyuiop', '2025-07-17', 'Dr Fanta', '790'),
('xtfgjhfghjkl', '2025-07-24', 'fd', 'mrtyuiop', '2025-07-24', 'Dr Fanta', '800'),
('xtfgjhfghjkl', '2025-07-24', 'fd', 'mrtyuiop', '2025-07-24', 'Dr Fanta', '789');

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
('4555', '52', 'kouako', 'laurjnj', 'prkr,kr', 'blanchatre', '<5', 'moyen', 'ktkltk', 'absence', 'absence', 'negative', 'absence', 'hgigui', 'igjjoop('),
('4555', '52', 'yves', 'LAURINCE HERMANN STYVE', 'nrenojoojr', 'blanchatre', '<5', 'moyen', 'ktkltk', 'absence', 'absence', 'negative', 'absence', 'n, ,', 'n,n,n,'),
('4555', '52', 'yves', 'LAURINCE HERMANN STYVE', 'nrenojoojr', 'blanchatre', '<5', 'moyen', 'ktkltk', 'absence', 'absence', 'negative', 'absence', 'n, ,', 'n,n,n,'),
('4555', '25', 'YAO', 'laurjnj', 'ABOU', 'blanchatre', '<5', 'moyen', 'kvk', 'absence', 'absence', 'negative', 'absence', 'giuuiui', 'iviugigigiogui'),
('4555', '25', 'YAO', 'laurjnj', 'ABOU', 'blanchatre', '<5', 'moyen', 'kvk', 'absence', 'absence', 'negative', 'absence', 'giuuiui', 'iviugigigiogui'),
('4555', '25', 'YAO', 'laurjnj', 'ABOU', 'blanchatre', '<5', 'moyen', 'kvk', 'absence', 'absence', 'negative', 'absence', 'giuuiui', 'iviugigigiogui'),
('4555', '25', 'YAO', 'laurjnj', 'ABOU', 'blanchatre', '<5', 'moyen', 'kvk', 'absence', 'absence', 'negative', 'absence', 'giuuiui', 'iviugigigiogui'),
('4555', '25', 'YAO', 'laurjnj', 'ABOU', 'blanchatre', '<5', 'moyen', 'kvk', 'absence', 'absence', 'negative', 'absence', 'giuuiui', 'iviugigigiogui'),
('4555', '25', 'YAO', 'laurjnj', 'ABOU', 'blanchatre', '<5', 'moyen', 'kvk', 'absence', 'absence', 'negative', 'absence', 'giuuiui', 'iviugigigiogui'),
('197', '25', 'KONE', 'laurjnj', 'ABOU', 'blanchatre', '<5', 'moyen', 'kvk', 'absence', 'absence', 'negative', 'absence', 'giuuiui', 'iviugigigiogui'),
('197', '24', 'KONE', 'IBRAHIM', 'kilo', 'blanchatre', '<5', 'moyen', 'gtkhio', 'absence', 'absence', 'negative', 'absence', 'ouihio', 'hiohiohio'),
('203', '42', 'yao', 'yao paulin', 'kobenan', 'blanchatre', '<5', 'moyen', 'gtkhio', 'absence', 'absence', 'negative', 'absence', 'ouihio', 'iviugigigiogui'),
('203', '42', 'yao', 'yao paulin', 'kobenan', 'blanchatre', '<5', 'moyen', 'gtkhio', 'absence', 'absence', 'negative', 'absence', 'ouihio', 'iviugigigiogui'),
('203', '42', 'yao', 'yao paulin', 'kobenan', 'blanchatre', '<5', 'moyen', 'gtkhio', 'absence', 'absence', 'negative', 'absence', 'ouihio', 'iviugigigiogui'),
('203', '42', 'yao', 'yao paulin', 'kobenan', 'blanchatre', '<5', 'moyen', 'gtkhio', 'absence', 'absence', 'negative', 'absence', 'ouihio', 'iviugigigiogui'),
('203', '42', 'yao', 'yao paulin', 'kobenan', 'blanchatre', '<5', 'moyen', 'gtkhio', 'absence', 'absence', 'negative', 'absence', 'ouihio', 'iviugigigiogui'),
('203', '42', 'yao', 'yao paulin', 'kobenan', 'blanchatre', '<5', 'moyen', 'gtkhio', 'absence', 'absence', 'negative', 'absence', 'ouihio', 'iviugigigiogui');

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
('155555', 25, 'YAO', 'PRI', 'ABOU', 'absence', '<5/champs', 'absence', 'absence', 'negatif'),
('155555', 25, 'YAO', 'PRI', 'ABOU', 'absence', '<5/champs', 'absence', 'absence', 'negatif'),
('155555', 25, 'YAO', 'PRI', 'ABOU', 'negatif', 'absence', '<5/champs', 'absence', 'absence'),
('4555', 52, 'YAO', 'joh', 'nrenojoojr', 'negatif', 'absence', '<5/champs', 'absence', 'absence'),
('789', 22, 'kouakou', 'lau', 'yao', 'negatif', 'absence', '<5/champs', 'absence', 'absence'),
('789', 22, 'kouakou', 'lau', 'yao', 'negatif', 'absence', '<5/champs', 'absence', 'absence'),
('789', 22, 'kouakou', 'lau', 'fanta', 'negatif', 'absence', '<5/champs', 'absence', 'absence'),
('790', 27, 'Ouedraogo', 'Las', 'Fanta', 'negatif', 'absence', '<5/champs', 'absence', 'absence'),
('790', 27, 'Ouedraogo', 'Las', 'fanta', 'negatif', 'absence', '<5/champs', 'absence', 'absence'),
('790', 27, 'Ouedraogo', 'Las', 'fanta', 'negatif', 'absence', '<5/champs', 'absence', 'absence'),
('790', 27, 'Ouedraogo', 'Las', 'fanta', 'negatif', 'absence', '<5/champs', 'absence', 'absence'),
('789', 22, 'kouakou', 'lau', 'coulibaly', 'negatif', 'absence', '<5/champs', 'absence', 'absence'),
('790', 27, 'Ouedraogo', 'Las', 'YAO', 'negatif', 'absence', '<5/champs', 'absence', 'absence'),
('790', 27, 'Ouedraogo', 'Las', 'YAO', 'negatif', 'absence', '<5/champs', 'absence', 'absence'),
('800', 20, 'konan', 'yao', 'loua', 'negatif', 'absence', '<5/champs', 'absence', 'absence'),
('789', 22, 'kouakou', 'lau', 'yao', 'negatif', 'absence', '<5/champs', 'absence', 'absence');

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
('4555', 'kouakou', 'john', '55', 'nrenojoojr', 'Normale', 'OUI', 'Minime', 'Crémeux', 'Inodore', 'Blanchtre', 'Positif', '', 'NORMALE', 'ABSENT', '', 'Abscence', '<05/champ', 'Abscence', '', 'Normal typ', 'Abscence', 'Abscence', '', '<05/champ', '', 'Absence de colonie de lev', 'Absence de colonie s'),
('4555', 'kouakou', 'john', '55', 'nrenojoojr', 'Normale', 'OUI', 'Minime', 'Crémeux', 'Inodore', 'Blanchtre', 'Positif', '', 'NORMALE', 'ABSENT', '', 'Abscence', '<05/champ', 'Abscence', '', 'Normal typ', 'Abscence', 'Abscence', '', '<05/champ', '', 'Absence de colonie de lev', 'Absence de colonie s'),
('4555', 'kouakou', 'john', '55', 'nrenojoojr', 'Normale', 'OUI', 'Minime', 'Crémeux', 'Inodore', 'Blanchtre', 'Positif', '', 'NORMALE', 'ABSENT', '', 'Abscence', '<05/champ', 'Abscence', '', 'Normal typ', 'Abscence', 'Abscence', '', '<05/champ', '', 'Absence de colonie de lev', 'Absence de colonie s'),
('4555', 'kouakou', 'john', '55', 'nrenojoojr', 'Normale', 'OUI', 'Minime', 'Crémeux', 'Inodore', 'Blanchtre', 'Positif', '', 'NORMALE', 'ABSENT', '', 'Abscence', '<05/champ', 'Abscence', '', 'Normal typ', 'Abscence', 'Abscence', '', '<05/champ', '', 'Absence de colonie de lev', 'Absence de colonie s'),
('4555', 'kouakou', 'john', '55', 'nrenojoojr', 'Normale', 'OUI', 'Minime', 'Crémeux', 'Inodore', 'Blanchtre', 'Positif', '', 'NORMALE', 'ABSENT', '', 'Abscence', '<05/champ', 'Abscence', '', 'Normal typ', 'Abscence', 'Abscence', '', '<05/champ', '', 'Absence de colonie de lev', 'Absence de colonie s'),
('4555', 'kouakou', 'john', '55', 'nrenojoojr', 'Normale', 'OUI', 'Minime', 'Crémeux', 'Inodore', 'Blanchtre', 'Positif', '', 'NORMALE', 'ABSENT', '', 'Abscence', '<05/champ', 'Abscence', '', 'Normal typ', 'Abscence', 'Abscence', '', '<05/champ', '', 'Absence de colonie de lev', 'Absence de colonie s'),
('4555', 'YAO', 'vhjhjh', '52', 'uiogui', 'Absence de lésion', 'Absent', 'Faible', 'Homogène', 'Normale', '', 'Négatif', '', 'Normal', 'Normal', '', 'Absence', '', 'Absence', '', 'Flore de D', 'Absence', 'Absence', '', 'iuguigiu', 'guiguigui', 'Absence', 'Absence'),
('4555', 'YAO', 'vhjhjh', '52', 'uiogui', 'Absence de lésion', 'Absent', 'Faible', 'Homogène', 'Normale', '', 'Négatif', '', 'Normal', 'Normal', '', 'Absence', '', 'Absence', '', 'Flore de D', 'Absence', 'Absence', '', 'iuguigiu', 'guiguigui', 'Absence', 'Absence'),
('4555', 'YAO', 'vhjhjh', '52', 'uiogui', 'Absence de lésion', 'Absent', 'Faible', 'Homogène', 'Normale', '', 'Négatif', '', 'Normal', 'Normal', '', 'Absence', '', 'Absence', '', 'Flore de D', 'Absence', 'Absence', '', 'iuguigiu', 'guiguigui', 'Absence', 'Absence'),
('4555', 'YAO', 'vhjhjh', '52', 'uiogui', 'Absence de lésion', 'Absent', 'Faible', 'Homogène', 'Normale', '', 'Négatif', '', 'Normal', 'Normal', '', 'Absence', '', 'Absence', '', 'Flore de D', 'Absence', 'Absence', '', 'iuguigiu', 'guiguigui', 'Absence', 'Absence'),
('4555', 'YAO', 'vhjhjh', '52', 'uiogui', 'Absence de lésion', 'Absent', 'Faible', 'Homogène', 'Normale', '', 'Négatif', '', 'Normal', 'Normal', '', 'Absence', '', 'Absence', '', 'Flore de D', 'Absence', 'Absence', '', 'iuguigiu', 'guiguigui', 'Absence', 'Absence'),
('4555', 'YAO', 'vhjhjh', '52', 'uiogui', 'Absence de lésion', 'Absent', 'Faible', 'Homogène', 'Normale', '', 'Négatif', '', 'Normal', 'Normal', '', 'Absence', '', 'Absence', '', 'Flore de D', 'Absence', 'Absence', '', 'iuguigiu', 'guiguigui', 'Absence', 'Absence'),
('4555', 'yves', 'laurjnj', '52', 'nrenojoojr', 'Présence de lésion', 'Présent', 'Fort', 'Hétérogène', 'Fétide', 'blanche', 'Positif', '6', 'Normal', 'Normal', 'oiohiohio', 'Absence', 'hhhiohiohi', 'Absence', '', 'Flore de D', 'Absence', 'Absence', '', 'iuguigiu', 'guiguigui', 'Absence', 'Absence'),
('198', 'coulibaly', 'fatou', '24', 'bakayoko', 'Absence de lésion', 'Absent', 'Faible', 'Homogène', 'Normale', 'blanche', 'Négatif', '6', 'Normal', 'Normal', 'oiohiohio', 'Absence', '', 'Absence', 'gghghgh', 'Flore de D', 'Absence', 'Absence', '', 'guigui', 'guiguigui', 'Absence', 'Absence'),
('790', 'Ouedraogo', 'Lassinata', '27', 'Fanta', 'Présence de lésion', 'Absent', 'Faible', 'Homogène', 'Normale', 'blanche', 'Négatif', '2', 'Normal', 'Normal', 'poiuloi', 'Absence', 'polio', 'Absence', 'corona', 'Flore de D', 'Absence', 'Absence', 'dingue', 'virus', 'ebola', 'Absence', 'Absence'),
('789', 'kouakou', 'laurince hermann styves', '22', 'coulibaly', 'Absence de lésion', 'Absent', 'Faible', 'Homogène', 'Normale', 'blanche', 'Négatif', 'kjkkljklkl', 'Normal', 'Normal', 'poiuloi', 'Absence', 'polio', 'Absence', 'corona', 'Flore de D', 'Absence', 'Absence', 'dingue', 'virus', 'ebola', 'Absence', 'Absence'),
('789', 'kouakou', 'laurince hermann styves', '22', 'KOUA', 'Absence de lésion', 'Absent', 'Faible', 'Homogène', 'Normale', 'blanche', 'Négatif', 'kjkkljklkl', 'Normal', 'Normal', 'poiuloi', 'Absence', 'polio', 'Absence', 'corona', 'Flore de D', 'Absence', 'Absence', 'dingue', 'virus', 'ebola', 'Absence', 'Absence');

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

--
-- Déchargement des données de la table `habitude_sexuelles`
--

INSERT INTO `habitude_sexuelles` (`Numero_urap`, `Quel_type_rapport_avez_vous_`, `Pratiquez_vous__fellation`, `Avez_vous_changé_partenais_ces_deux_dernier_mois`, `Utilisez_vous_preservatif`, `Pratqez_le_cunni`) VALUES
(197, 'Hétérosexuel', 'Rarement', 'Non', 'Toujours', NULL),
(198, 'Hétérosexuel', 'quelque fois', 'Non', 'Toujours', NULL),
(200, 'Hétérosexuel', 'Rarement', 'oui', 'Rarement', NULL),
(201, 'quelque fois', 'Rarement', 'Non', 'quelque fois', NULL),
(202, 'quelque fois', 'Rarement', 'Non', 'quelque fois', NULL),
(203, 'Hétérosexuel', 'Jamais', 'Non', 'quelque fois', NULL),
(471, 'Hétérosexuel', 'Toujours', 'oui', 'Jamais', NULL),
(789, 'Hétérosexuel', 'quelque fois', 'oui', 'Toujours', NULL),
(800, 'Hétérosexuel', 'Jamais', 'Non', 'Toujours', NULL),
(194587, 'Hétérosexuel', 'quelque fois', 'Non', 'Toujours', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `histoire_maladie`
--

DROP TABLE IF EXISTS `histoire_maladie`;
CREATE TABLE IF NOT EXISTS `histoire_maladie` (
  `Numero_urap` int NOT NULL,
  `sexe_patient` varchar(10) NOT NULL,
  `motif_homme` varchar(50) DEFAULT NULL,
  `motif_femme` varchar(50) DEFAULT NULL,
  `signes_fonctionnels` varchar(50) DEFAULT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Numero_urap`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `histoire_maladie`
--

INSERT INTO `histoire_maladie` (`Numero_urap`, `sexe_patient`, `motif_homme`, `motif_femme`, `signes_fonctionnels`, `date_creation`) VALUES
(145, 'masculin', 'paternite', '', 'mal_odeur', '2025-07-12 11:41:14'),
(123, 'masculin', 'paternite', '', 'prurit', '2025-07-12 11:54:38'),
(1254000, 'masculin', 'dysurie', '', 'mal_odeur', '2025-07-12 12:51:58'),
(1478, 'feminin', '', 'agent_contaminateur', 'prurit', '2025-07-12 12:52:21'),
(203, 'masculin', 'dysurie', '', 'douleurs_pelviennes', '2025-07-15 12:54:49'),
(789, 'masculin', 'dysurie', '', 'prurit', '2025-07-21 20:59:04'),
(800, 'masculin', 'dysurie', '', 'prurit', '2025-07-24 10:47:07');

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
  `Profession` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `patient`
--

INSERT INTO `patient` (`Numero_urap`, `Nom_patient`, `Prenom_patient`, `Age`, `Sexe_patient`, `Date_naissance`, `Contact_patient`, `Situation_matrimoniale`, `Lieu_résidence`, `Precise`, `Type_logement`, `Niveau_etude`, `Profession`) VALUES
('123', 'Traoré', 'Abou', 24, 'Masculin', '2001-01-02', '0102030405', 'Marié', 'Abidjan', '', 'Baraquement', 'Aucun', 'Aucun'),
('123', 'Traoré', 'Abou', 24, 'Masculin', '2001-01-02', '0102030405', 'Marié', 'Abidjan', '', 'Baraquement', 'Aucun', 'Aucun'),
('123', 'kpouadio', 'fanta', 24, 'Féminin', '2001-01-02', '0102030405', 'Marié', 'Abidjan', '', 'Baraquement', 'Aucun', 'Aucun'),
('180', 'yao', 'kouame fernand', 22, 'Masculin', '2002-07-12', '0102030405', 'Célibataire', 'Abidjan', '', 'Studio', 'Aucun', 'Aucun'),
('0123', 'kouakou', 'john', 23, 'Masculin', '2002-02-02', '0708522526', 'Célibataire', 'Abidjan', '', 'Autre', 'Universitaire', 'Secteur informel'),
('0123', 'kouakou', 'ahou', 23, 'Masculin', '2002-02-02', '0708522526', 'Marié', 'Abidjan', '', 'Baraquement', 'Aucun', 'Aucun'),
('178', 'yao', 'kouame', 22, 'Masculin', '2002-07-12', '0102030405', 'Célibataire', 'Abidjan', '', 'Studio', 'Aucun', 'Aucun'),
('001', 'kouakou', 'laurince hermann styves', 22, 'Masculin', '2002-09-02', '0779156688', 'Célibataire', 'Abidjan', 'yamoussoukro', 'Studio', 'Aucun', 'Aucun'),
('001', 'kouakou', 'wilfried', 22, 'Masculin', '2003-05-04', '0779156688', 'Célibataire', 'Abidjan', 'cocody', 'Studio', 'Secondaire', 'Aucun'),
('001', 'kouakou', 'laurince hermann styves', 22, 'Masculin', '2002-09-02', '0779156688', 'Célibataire', 'Abidjan', '', 'Studio', 'Aucun', 'Aucun'),
('00152', 'coulibaly', 'mariam', 23, 'Féminin', '2001-10-05', '0102030405', 'Divorcé', 'Hors Abidjan', 'korhogo', 'Cour commune', 'Primaire', 'Secteur informel'),
('00152', 'coulibaly', 'mariam', 23, 'Masculin', '2001-10-05', '0102030405', 'Célibataire', 'Abidjan', '', 'Studio', 'Aucun', 'Aucun'),
('00152', 'coulibaly', 'mariam', 23, 'Masculin', '2001-10-05', '0102030405', 'Célibataire', 'Abidjan', '', 'Studio', 'Aucun', 'Aucun'),
('00157', 'coulibaly', 'mariam', 23, 'Masculin', '2001-10-05', '0102030405', 'Célibataire', 'Abidjan', '', 'Studio', 'Aucun', 'Aucun'),
('00158', 'coulibaly', 'ouattara', 23, 'Masculin', '2001-10-05', '0102030405', 'Célibataire', 'Abidjan', '', 'Studio', 'Aucun', 'Aucun'),
('00159', 'coulibaly', 'hamed', 20, 'Masculin', '2005-02-01', '0102030405', 'Célibataire', 'Abidjan', '', 'Studio', 'Aucun', 'Aucun'),
('00160', 'kouakou', 'yao', 20, 'Masculin', '2005-02-01', '0102030405', 'Célibataire', 'Abidjan', '', 'Studio', 'Aucun', 'Aucun'),
('00161', 'kouakou', 'kouadio', 20, 'Masculin', '2005-02-01', '0102030405', 'Célibataire', 'Abidjan', '', 'Studio', 'Aucun', 'Aucun'),
('00162', 'kouakou', 'kouakou', 20, 'Masculin', '2005-02-01', '0102030405', 'Célibataire', 'Abidjan', '', 'Studio', 'Aucun', 'Aucun'),
('00163', 'kouakou', 'kouakou kan', 20, 'Masculin', '2005-02-01', '0102030405', 'Célibataire', 'Abidjan', '', 'Studio', 'Aucun', 'Aucun'),
('170', 'fanta', 'maminin', 22, 'Féminin', '2002-07-12', '0102030405', 'Célibataire', 'Abidjan', '', 'Studio', 'Aucun', 'Aucun'),
('177', 'yao', 'maminin', 22, 'Masculin', '2002-07-12', '0102030405', 'Célibataire', 'Abidjan', '', 'Studio', 'Aucun', 'Aucun'),
('179', 'ouattara', 'ibrahim', 23, 'Masculin', '2001-11-08', '01478520', 'Célibataire', 'Abidjan', '', 'Studio', 'Aucun', 'Aucun'),
('197', 'KONE', 'IBRAHIM', 24, 'Masculin', '2001-05-14', '0779156688', 'Célibataire', 'Abidjan', '', 'Studio', 'Aucun', 'Aucun'),
('198', 'coulibaly', 'fatou', 24, 'Féminin', '2001-07-02', '012358452245', 'Marié', 'Abidjan', '', 'Villa', 'Primaire', 'Cadre moyen'),
('203', 'yao', 'yao paulin', 42, 'Masculin', '1983-07-03', '0101020304', 'Célibataire', 'Abidjan', '', 'Villa', 'Universitaire', 'Cadre superieur'),
('789', 'kouakou', 'laurince hermann styves', 22, 'Masculin', '2002-09-02', '0779156688', 'Célibataire', 'Abidjan', '', 'Villa', 'Universitaire', 'Etudiant'),
('20', 'guehi', 'justin', 27, 'Masculin', '1998-07-10', '1236547890', 'Célibataire', 'Abidjan', '', 'Studio', 'Aucun', 'Aucun'),
('790', 'Ouedraogo', 'Lassinata', 27, 'Féminin', '1998-07-17', '12547896', 'Célibataire', 'Abidjan', '', 'Studio', 'Secondaire', 'Secteur informel'),
('800', 'konan', 'yao jule', 20, 'Masculin', '2005-07-02', '0779156688', 'Marié', 'Abidjan', '', 'Villa', 'Universitaire', 'Cadre superieur');

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
(1, 'sidick', 'nico', 'abou@gmail.com', '123', 'ADMIN', 1, '12323265', 'Uatg'),
(2, 'LOBA', 'VINCENT', 'amanihubertine403@gmail.com', '1234', 'TECHNICIEN', 0, '0769269246', 'titi'),
(3, 'Berte', 'Maminin ', 'bertefanta1203@gmail.com', 'Fanta', 'ADMIN', 1, '0584903726', 'Fanta12');

-- --------------------------------------------------------

--
-- Structure de la table `visite`
--

DROP TABLE IF EXISTS `visite`;
CREATE TABLE IF NOT EXISTS `visite` (
  `id_visite` int NOT NULL,
  `Date visite` date NOT NULL,
  `Heure visite` varchar(10) NOT NULL,
  `Motif visite` varchar(20) NOT NULL,
  `Numero_urap` varchar(15) NOT NULL,
  `ID_prescripteur` int DEFAULT NULL,
  `Structure_provenance` varchar(50) DEFAULT NULL,
  `ID_antecedants` int DEFAULT NULL,
  `ID_histoire_maladie` int DEFAULT NULL,
  `ID_habitude` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
