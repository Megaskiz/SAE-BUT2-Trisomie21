-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 20 jan. 2023 à 12:08
-- Version du serveur : 10.4.27-MariaDB
-- Version de PHP : 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bddsae`
--

-- --------------------------------------------------------

--
-- Structure de la table `enfant`
--

CREATE TABLE `enfant` (
  `id_enfant` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `date_naissance` date NOT NULL,
  `lien_jeton` varchar(100) DEFAULT NULL,
  `adresse` varchar(100) DEFAULT NULL,
  `activite` varchar(100) DEFAULT NULL,
  `handicap` varchar(100) DEFAULT NULL,
  `info_sup` varchar(1000) DEFAULT NULL,
  `photo_enfant` varchar(100) DEFAULT NULL,
  `visibilite` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `enfant`
--

INSERT INTO `enfant` (`id_enfant`, `nom`, `prenom`, `date_naissance`, `lien_jeton`, `adresse`, `activite`, `handicap`, `info_sup`, `photo_enfant`, `visibilite`) VALUES
(27, 'Bérard', 'Melisande', '2012-07-09', 'images/63c8178bb90a55.40397647.png', '77, rue de l\"aigle', '', 'Trisomie 21', '', 'images/63b8293dd7abe3.51430517.jpg', 0),
(28, 'Martin', 'Victor', '2013-08-30', 'images/6390a3e21a4ce6.79858360.jpg', '47, rue saint Germain', 'football', '', 'doit aller aux toilettes très régulièrement', NULL, 1),
(33, 'Maheu', 'Pierre', '2014-03-30', 'images/63ca54fc00e238.90002496.png', '21, rue de Raymond Poincaré', 'Peinture', 'Trisomie 21', 'Essaie de dessiner tout le temps', 'images/63ca54fc00f919.48782317.png', 0),
(34, 'Labelle', 'Marion', '2015-05-15', 'images/63ca5594079f22.97471182.jpg', NULL, NULL, NULL, NULL, 'images/63ca559407be01.45084920.png', 0),
(35, 'Doyon', 'Charlie', '2014-01-12', 'images/63ca55d928b301.69387207.png', NULL, NULL, NULL, NULL, 'images/63ca55d928f072.11353862.png', 0);

-- --------------------------------------------------------

--
-- Structure de la table `lier`
--

CREATE TABLE `lier` (
  `id_objectif` int(11) NOT NULL,
  `id_recompense` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `lier`
--

INSERT INTO `lier` (`id_objectif`, `id_recompense`) VALUES
(28, 26),
(28, 27),
(28, 28),
(28, 29),
(29, 30),
(30, 31),
(31, 32);

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

CREATE TABLE `membre` (
  `id_membre` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `adresse` varchar(100) DEFAULT NULL,
  `code_postal` char(5) DEFAULT NULL,
  `ville` varchar(50) DEFAULT NULL,
  `courriel` varchar(50) DEFAULT NULL,
  `date_naissance` date NOT NULL,
  `mdp` varchar(500) DEFAULT NULL,
  `pro` tinyint(1) DEFAULT NULL,
  `compte_valide` tinyint(1) DEFAULT NULL,
  `role_user` int(1) NOT NULL,
  `visibilite` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `membre`
--

INSERT INTO `membre` (`id_membre`, `nom`, `prenom`, `adresse`, `code_postal`, `ville`, `courriel`, `date_naissance`, `mdp`, `pro`, `compte_valide`, `role_user`, `visibilite`) VALUES
(1, 'compte', 'administrateur', '', '', '', 'test@gmail.com', '0000-00-00', 'e712226da3facf4c458d431a4068ce0cb47df2cf30f44636255db54adb76caa7', 1, 1, 1, 0),
(2, 'Mailloux', 'Christine ', '64, Boulevard de Normandie', '38600', 'Nantes', 'christine@mail.com', '0000-00-00', '2983cae49631ea124afecf8183d827d54098175dedd78ff261d9c02b9d60186b', 0, 1, 0, 0),
(3, 'Lazure', 'Thomas', '97, Boulevard de Normandie', '97200', 'fort-de-france', 'thomas@mail.com', '1993-09-15', '1ebb92636717caa01d195ad0091f642d0a3d4f73a5cbe6ebb3502267d3c96d22', 0, 1, 0, 0),
(9, 'Bordeleau', 'Marie', '60, rue Grande Fusterie', '91800', 'BRUNOY', 'trochel.paul@gmail.com', '2003-06-30', 'a47fdb4f44865cd6e9d8f0c77733be25c29f6719e34a9cc67cabb405df8ff684', 0, 1, 0, 0),
(17, 'Orane', 'Blais', '15, rue Banaudon', '69008', 'Lyon', '1@mail.com', '1976-04-30', '6ca835f7f9e9689011cbda9ef8d56ab4f3b22cd3d6ca07810a8891e9957fe751', 0, 1, 2, 0),
(18, 'non valide', 'utilisateur', 'lksj', '33333', 'ville', 'mail@mail.mail', '2003-06-30', '6ca835f7f9e9689011cbda9ef8d56ab4f3b22cd3d6ca07810a8891e9957fe751', 0, 1, 0, 1),
(19, 'trochell', 'paul ', '77 chemin', '31000', 'toulousee', 'trochel@gmail.com', '2003-06-30', '64d0e6d2312c3d8fadb60fcce0c6151fec5962583d6fc08dbaa47c6b9245fb42', 0, 1, 0, 1),
(20, 'test', 'nouveau', 'compte', '11111', 'testville', 'mkjqsmlkj@makjfemlkj', '1002-02-10', '6ca835f7f9e9689011cbda9ef8d56ab4f3b22cd3d6ca07810a8891e9957fe751', 0, 1, 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

CREATE TABLE `message` (
  `id_message` int(11) NOT NULL,
  `sujet` varchar(50) DEFAULT NULL,
  `corps` text DEFAULT NULL,
  `date_heure` datetime DEFAULT NULL,
  `id_objectif` int(11) NOT NULL,
  `id_membre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `objectif`
--

CREATE TABLE `objectif` (
  `id_objectif` int(11) NOT NULL,
  `intitule` varchar(50) DEFAULT NULL,
  `nom` varchar(500) NOT NULL,
  `nb_jetons` tinyint(4) DEFAULT NULL,
  `duree` double DEFAULT NULL,
  `lien_image` varchar(100) DEFAULT NULL,
  `priorite` int(11) DEFAULT NULL,
  `travaille` tinyint(1) DEFAULT NULL,
  `id_membre` int(11) NOT NULL,
  `id_enfant` int(11) NOT NULL,
  `type` int(1) NOT NULL,
  `visibilite` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `objectif`
--

INSERT INTO `objectif` (`id_objectif`, `intitule`, `nom`, `nb_jetons`, `duree`, `lien_image`, `priorite`, `travaille`, `id_membre`, `id_enfant`, `type`, `visibilite`) VALUES
(9, 'Objectif à la maison', 'bien manger le matin_0000000: ne pas manger en dehors des repas_0000000:bien faire ses devoirs_0000000:', 21, 168, 'jeton', 0, 1, 1, 27, 3, 0),
(10, 'Revue objectif court', 'rester concentré dix minutes_000:', 3, 0.003, 'jeton', 1, 0, 1, 27, 1, 0),
(28, 'Objectif participer en classe', 'Il faut participer en classe_0000000000:', 10, 1, 'jeton', 3, 0, 1, 27, 1, 0),
(29, 'objectif tâche ménargère', 'faire une tâche ménarère sans se plaindre_00000:', 5, 2, 'jeton', 2, 0, 1, 35, 1, 0),
(30, 'faire ses devoirs', 'tu fera tes devoirs sans de plaindre_11111:', 5, 0.003, 'jeton', 1, 0, 1, 34, 1, 0),
(31, 'objectif journée type', 'Je passe la journée sans caprices_1111000:je range mes affaires_1111000:Je suis poli_1111000:', 21, 336, 'jeton', 1, 0, 1, 34, 3, 0);

-- --------------------------------------------------------

--
-- Structure de la table `placer_jeton`
--

CREATE TABLE `placer_jeton` (
  `id_objectif` int(11) NOT NULL,
  `date_heure` datetime NOT NULL,
  `id_membre` int(11) NOT NULL,
  `id_session` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `placer_jeton`
--

INSERT INTO `placer_jeton` (`id_objectif`, `date_heure`, `id_membre`, `id_session`) VALUES
(30, '2022-12-02 11:03:43', 1, 1),
(30, '2022-12-02 11:03:46', 1, 1),
(30, '2022-12-02 11:03:49', 1, 1),
(30, '2022-12-02 11:03:51', 1, 1),
(30, '2022-12-02 11:03:53', 1, 1),
(30, '2022-12-02 11:04:26', 1, 2),
(30, '2022-12-02 11:04:27', 1, 2),
(30, '2022-12-02 11:04:28', 1, 2),
(30, '2022-12-02 11:05:08', 1, 3),
(30, '2022-12-02 11:05:09', 1, 3),
(30, '2022-12-02 11:05:10', 1, 3),
(30, '2022-12-02 11:05:12', 1, 3),
(30, '2022-12-02 11:05:13', 1, 3),
(30, '2022-12-02 11:05:17', 1, 4),
(30, '2022-12-02 11:05:19', 1, 4),
(30, '2022-12-02 11:05:23', 1, 4),
(30, '2022-12-02 11:05:24', 1, 4),
(30, '2022-12-02 11:05:32', 1, 5),
(30, '2022-12-02 11:05:34', 1, 5),
(30, '2022-12-02 11:05:35', 1, 5),
(30, '2023-01-20 11:05:54', 1, 6),
(30, '2023-01-20 11:05:55', 1, 6),
(30, '2023-01-20 11:05:56', 1, 6),
(30, '2023-01-20 11:05:58', 1, 6),
(30, '2023-01-20 11:05:59', 1, 6),
(30, '2023-01-20 11:06:06', 1, 7),
(30, '2023-01-20 11:06:07', 1, 7),
(30, '2023-01-20 11:06:08', 1, 7),
(30, '2023-01-20 11:06:09', 1, 7),
(30, '2023-01-20 11:06:14', 1, 8),
(30, '2023-01-20 11:06:15', 1, 8),
(30, '2023-01-20 11:06:17', 1, 8),
(30, '2023-01-20 11:06:19', 1, 8),
(30, '2023-01-20 11:06:21', 1, 8),
(30, '2023-01-20 11:06:24', 1, 9),
(30, '2023-01-20 11:06:26', 1, 9),
(30, '2023-01-20 11:06:27', 1, 9),
(30, '2023-01-20 11:06:28', 1, 9),
(30, '2023-01-20 11:06:29', 1, 9),
(30, '2023-01-20 11:06:39', 1, 10),
(30, '2023-01-20 11:06:41', 1, 10),
(30, '2023-01-20 11:06:42', 1, 10),
(30, '2023-01-20 11:06:44', 1, 10),
(30, '2023-01-20 11:06:46', 1, 10),
(30, '2023-01-20 11:06:50', 1, 11),
(30, '2023-01-20 11:06:51', 1, 11),
(30, '2023-01-20 11:06:53', 1, 11),
(30, '2023-01-20 11:06:54', 1, 11),
(30, '2023-01-20 11:06:56', 1, 11),
(30, '2023-01-20 11:06:59', 1, 12),
(30, '2023-01-20 11:07:00', 1, 12),
(30, '2023-01-20 11:07:01', 1, 12),
(30, '2023-01-20 11:07:03', 1, 12),
(30, '2023-01-20 11:07:04', 1, 12),
(30, '2023-01-20 11:07:10', 1, 13),
(30, '2023-01-20 11:07:12', 1, 13),
(30, '2023-01-20 11:07:17', 1, 14),
(30, '2023-01-20 11:07:18', 1, 14),
(30, '2023-01-20 11:07:19', 1, 14),
(30, '2023-01-20 11:07:21', 1, 14),
(30, '2023-01-20 11:07:23', 1, 14),
(30, '2023-01-20 11:07:29', 1, 15),
(30, '2023-01-20 11:07:31', 1, 15),
(30, '2023-01-20 11:07:33', 1, 15),
(30, '2023-01-20 11:07:34', 1, 15),
(30, '2023-01-20 11:07:36', 1, 15),
(30, '2023-01-20 11:07:58', 1, 16),
(30, '2023-01-20 11:08:00', 1, 16),
(30, '2023-01-20 11:08:01', 1, 16),
(30, '2023-01-20 11:08:03', 1, 16),
(30, '2023-01-20 11:08:04', 1, 16),
(30, '2023-01-20 11:08:07', 1, 17),
(30, '2023-01-20 11:08:08', 1, 17),
(30, '2023-01-20 11:08:09', 1, 17),
(30, '2023-01-20 11:08:11', 1, 17),
(30, '2023-01-20 11:08:12', 1, 17),
(30, '2023-01-20 11:08:15', 1, 18),
(30, '2023-01-20 11:08:16', 1, 18),
(30, '2023-01-20 11:08:17', 1, 18),
(30, '2023-01-20 11:08:19', 1, 18),
(30, '2023-01-20 11:08:20', 1, 18),
(30, '2023-01-20 11:08:23', 1, 19),
(30, '2023-01-20 11:08:25', 1, 19),
(30, '2023-01-20 11:08:26', 1, 19),
(30, '2023-01-20 11:08:27', 1, 19),
(30, '2023-01-20 11:08:29', 1, 19),
(30, '2023-01-20 11:08:36', 1, 20),
(30, '2023-01-20 11:08:37', 1, 20),
(30, '2023-01-20 11:08:39', 1, 20),
(30, '2023-01-20 11:08:40', 1, 20),
(30, '2023-01-20 11:08:42', 1, 20),
(30, '2023-01-20 11:08:52', 1, 21),
(30, '2023-01-20 11:08:54', 1, 21),
(30, '2023-01-20 11:08:55', 1, 21),
(30, '2023-01-20 11:08:57', 1, 21),
(30, '2023-01-20 11:08:59', 1, 21),
(30, '2023-01-20 11:09:03', 1, 22),
(30, '2023-01-20 11:09:05', 1, 22),
(30, '2023-01-20 11:09:07', 1, 22),
(30, '2023-01-20 11:09:08', 1, 22),
(30, '2023-01-20 11:09:09', 1, 22),
(30, '2023-01-20 11:09:44', 1, 23),
(30, '2023-01-20 11:09:45', 1, 23),
(30, '2023-01-20 11:09:47', 1, 23),
(30, '2023-01-20 11:09:48', 1, 23),
(30, '2023-01-20 11:09:49', 1, 23),
(30, '2023-01-20 11:09:53', 1, 24),
(30, '2023-01-20 11:09:54', 1, 24),
(30, '2023-01-20 11:09:56', 1, 24),
(30, '2023-01-20 11:09:57', 1, 24),
(30, '2023-01-20 11:09:58', 1, 24),
(30, '2023-01-20 11:10:03', 1, 25),
(30, '2023-01-20 11:10:04', 1, 25),
(30, '2023-01-20 11:10:06', 1, 25),
(30, '2023-01-20 11:10:07', 1, 25),
(30, '2023-01-20 11:10:08', 1, 25),
(30, '2023-01-20 11:10:10', 1, 25),
(31, '2023-01-20 11:43:39', 1, 1),
(31, '2023-01-20 11:43:40', 1, 1),
(31, '2023-01-20 11:43:42', 1, 1),
(31, '2023-01-20 11:43:43', 1, 1),
(31, '2023-01-20 11:43:44', 1, 1),
(31, '2023-01-20 11:43:46', 1, 1),
(31, '2023-01-20 11:43:48', 1, 1),
(31, '2023-01-20 11:43:50', 1, 1),
(31, '2023-01-20 11:43:53', 1, 1),
(31, '2023-01-20 11:43:54', 1, 1),
(31, '2023-01-20 11:43:55', 1, 1),
(31, '2023-01-20 11:43:58', 1, 2),
(31, '2023-01-20 11:44:00', 1, 2),
(31, '2023-01-20 11:44:02', 1, 2),
(31, '2023-01-20 11:44:03', 1, 2),
(31, '2023-01-20 11:44:05', 1, 2),
(31, '2023-01-20 11:44:09', 1, 3),
(31, '2023-01-20 11:44:12', 1, 3),
(31, '2023-01-20 11:44:14', 1, 3),
(31, '2023-01-20 11:44:15', 1, 3),
(31, '2023-01-20 11:44:17', 1, 3),
(31, '2023-01-20 11:44:18', 1, 3),
(31, '2023-01-20 11:44:20', 1, 3),
(31, '2023-01-20 11:44:22', 1, 3),
(31, '2023-01-20 11:44:23', 1, 3),
(31, '2023-01-20 11:44:24', 1, 3),
(31, '2023-01-20 11:44:26', 1, 3),
(31, '2023-01-20 11:44:27', 1, 3),
(31, '2023-01-20 11:44:28', 1, 3),
(31, '2023-01-20 11:44:30', 1, 3),
(31, '2023-01-20 11:44:31', 1, 3),
(31, '2023-01-20 11:44:33', 1, 3),
(31, '2023-01-20 11:44:34', 1, 3),
(31, '2023-01-20 11:44:35', 1, 3),
(31, '2023-01-20 11:44:37', 1, 3),
(31, '2023-01-20 11:44:38', 1, 3),
(31, '2023-01-20 11:44:40', 1, 3),
(31, '2023-01-20 11:44:43', 1, 4),
(31, '2023-01-20 11:44:45', 1, 4),
(31, '2023-01-20 11:44:46', 1, 4),
(31, '2023-01-20 11:44:48', 1, 4),
(31, '2023-01-20 11:44:49', 1, 4),
(31, '2023-01-20 11:44:51', 1, 4),
(31, '2023-01-20 11:44:54', 1, 4),
(31, '2023-01-20 11:44:59', 1, 5),
(31, '2023-01-20 11:45:01', 1, 5),
(31, '2023-01-20 11:45:02', 1, 5),
(31, '2023-01-20 11:45:04', 1, 5),
(31, '2023-01-20 11:45:05', 1, 5),
(31, '2023-01-20 11:45:07', 1, 5),
(31, '2023-01-20 11:46:36', 1, 5),
(31, '2023-01-20 11:46:37', 1, 5),
(31, '2023-01-20 11:46:38', 1, 5),
(31, '2023-01-20 11:46:40', 1, 5),
(31, '2023-01-20 11:46:42', 1, 5),
(31, '2023-01-20 11:46:43', 1, 5),
(31, '2023-01-20 11:46:45', 1, 5),
(31, '2023-01-20 11:46:46', 1, 5),
(31, '2023-01-20 11:46:48', 1, 5),
(31, '2023-01-20 11:46:49', 1, 5),
(31, '2023-01-20 11:46:51', 1, 5),
(31, '2023-01-20 11:46:53', 1, 5),
(31, '2023-01-20 11:46:54', 1, 5),
(31, '2023-01-20 11:46:57', 1, 5),
(31, '2023-01-20 11:46:59', 1, 5),
(31, '2023-01-20 11:47:08', 1, 6),
(31, '2023-01-20 11:47:10', 1, 6),
(31, '2023-01-20 11:47:12', 1, 6),
(31, '2023-01-20 11:47:23', 1, 6),
(31, '2023-01-20 11:47:24', 1, 6),
(31, '2023-01-20 11:47:32', 1, 6),
(31, '2023-01-20 11:47:33', 1, 6),
(31, '2023-01-20 11:47:34', 1, 6),
(31, '2023-01-20 11:47:42', 1, 6),
(31, '2023-01-20 11:47:43', 1, 6),
(31, '2023-01-20 11:47:44', 1, 6),
(31, '2023-01-20 11:47:51', 1, 6);

-- --------------------------------------------------------

--
-- Structure de la table `recompense`
--

CREATE TABLE `recompense` (
  `id_recompense` int(11) NOT NULL,
  `intitule` varchar(50) DEFAULT NULL,
  `descriptif` text DEFAULT NULL,
  `lien_image` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `recompense`
--

INSERT INTO `recompense` (`id_recompense`, `intitule`, `descriptif`, `lien_image`) VALUES
(1, 'jeu de societe', 'une soirée jeu de société avec la famille', 'image'),
(2, 'aller faire une balade en foret', 'une balade dans la foret un apres-midi', 'image'),
(3, 'manger au kebab', NULL, NULL),
(14, 'soiree kebab', 'une soirée avec un kebab', 'image kebab'),
(15, 'un déssert en plus', 'tu aura un déssert au choix en plus', 'image'),
(16, 'aller en recrée 10minutes de plus', 'tu aura le droit de rester plus longtemps en récrée', 'image'),
(17, 'un bon savon', 'tu obiendra un bon savon', 'image savon'),
(23, '2 dessert', 'tu pourra prendre un deuxieme dessert', 'images/63c40296814415.27802436.png'),
(24, 'jouer 1 heure sur le téléphone', 'tu pourra jouer une heure sur ton téléphone', 'images/63c40296837170.89685802.png'),
(25, 'manger', 'tu pourra manger ce soir', 'images/63c4389f291d09.70597378.webp'),
(26, 'Balade forêt', 'On ira faire une balade en foret ce soir', 'images/63ca5e4c07f2c3.50478611.webp'),
(27, 'Dessert x2', 'Tu aura le droit d\'avoir un autre dessert au repas ce soir', 'images/63ca5e4c0974b8.94589287.png'),
(28, 'choix d\'un repas', 'tu pourra choisir le repas de ce soir', 'images/63ca5e4c0af3d8.00121578.jpg'),
(29, '30 min dodo', 'Tu pourra te coucher 30minutes plus tard', 'images/63ca5e4c0c4c46.84977536.webp'),
(30, 'une soirée cinéma', 'on ira ce week au cinéma', 'images/63ca60bfc035e8.69804863.png'),
(31, 'repousse dodo quinze minutes', 'tu obtiens un coupon pour repousser ton heure de dodo de quinze minutes', 'images/63ca669d069649.88133435.webp'),
(32, '2* dessert', 'tu aura un déssert au choix en plus', 'images/63ca7051653089.40304331.png');

-- --------------------------------------------------------

--
-- Structure de la table `suivre`
--

CREATE TABLE `suivre` (
  `id_enfant` int(11) NOT NULL,
  `id_membre` int(11) NOT NULL,
  `date_demande_equipe` date DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `suivre`
--

INSERT INTO `suivre` (`id_enfant`, `id_membre`, `date_demande_equipe`, `role`) VALUES
(27, 2, '2023-01-18', 'membre');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `enfant`
--
ALTER TABLE `enfant`
  ADD PRIMARY KEY (`id_enfant`);

--
-- Index pour la table `lier`
--
ALTER TABLE `lier`
  ADD PRIMARY KEY (`id_objectif`,`id_recompense`),
  ADD KEY `id_recompense` (`id_recompense`);

--
-- Index pour la table `membre`
--
ALTER TABLE `membre`
  ADD PRIMARY KEY (`id_membre`);

--
-- Index pour la table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id_message`),
  ADD KEY `id_objectif` (`id_objectif`),
  ADD KEY `id_membre` (`id_membre`);

--
-- Index pour la table `objectif`
--
ALTER TABLE `objectif`
  ADD PRIMARY KEY (`id_objectif`),
  ADD KEY `id_membre` (`id_membre`),
  ADD KEY `id_enfant` (`id_enfant`);

--
-- Index pour la table `placer_jeton`
--
ALTER TABLE `placer_jeton`
  ADD PRIMARY KEY (`id_objectif`,`date_heure`),
  ADD KEY `id_membre` (`id_membre`);

--
-- Index pour la table `recompense`
--
ALTER TABLE `recompense`
  ADD PRIMARY KEY (`id_recompense`);

--
-- Index pour la table `suivre`
--
ALTER TABLE `suivre`
  ADD PRIMARY KEY (`id_enfant`,`id_membre`),
  ADD KEY `id_membre` (`id_membre`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `enfant`
--
ALTER TABLE `enfant`
  MODIFY `id_enfant` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT pour la table `membre`
--
ALTER TABLE `membre`
  MODIFY `id_membre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `message`
--
ALTER TABLE `message`
  MODIFY `id_message` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT pour la table `objectif`
--
ALTER TABLE `objectif`
  MODIFY `id_objectif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT pour la table `recompense`
--
ALTER TABLE `recompense`
  MODIFY `id_recompense` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `lier`
--
ALTER TABLE `lier`
  ADD CONSTRAINT `lier_ibfk_1` FOREIGN KEY (`id_objectif`) REFERENCES `objectif` (`id_objectif`),
  ADD CONSTRAINT `lier_ibfk_2` FOREIGN KEY (`id_recompense`) REFERENCES `recompense` (`id_recompense`);

--
-- Contraintes pour la table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`id_objectif`) REFERENCES `objectif` (`id_objectif`),
  ADD CONSTRAINT `message_ibfk_2` FOREIGN KEY (`id_membre`) REFERENCES `membre` (`id_membre`);

--
-- Contraintes pour la table `objectif`
--
ALTER TABLE `objectif`
  ADD CONSTRAINT `objectif_ibfk_1` FOREIGN KEY (`id_membre`) REFERENCES `membre` (`id_membre`),
  ADD CONSTRAINT `objectif_ibfk_2` FOREIGN KEY (`id_enfant`) REFERENCES `enfant` (`id_enfant`);

--
-- Contraintes pour la table `placer_jeton`
--
ALTER TABLE `placer_jeton`
  ADD CONSTRAINT `placer_jeton_ibfk_1` FOREIGN KEY (`id_objectif`) REFERENCES `objectif` (`id_objectif`),
  ADD CONSTRAINT `placer_jeton_ibfk_2` FOREIGN KEY (`id_membre`) REFERENCES `membre` (`id_membre`);

--
-- Contraintes pour la table `suivre`
--
ALTER TABLE `suivre`
  ADD CONSTRAINT `suivre_ibfk_1` FOREIGN KEY (`id_enfant`) REFERENCES `enfant` (`id_enfant`),
  ADD CONSTRAINT `suivre_ibfk_2` FOREIGN KEY (`id_membre`) REFERENCES `membre` (`id_membre`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
