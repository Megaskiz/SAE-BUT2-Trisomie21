-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 13 jan. 2023 à 14:15
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
  `photo_enfant` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `enfant`
--

INSERT INTO `enfant` (`id_enfant`, `nom`, `prenom`, `date_naissance`, `lien_jeton`, `adresse`, `activite`, `handicap`, `info_sup`, `photo_enfant`) VALUES
(27, 'thouverey', 'Guillaume', '2023-09-22', 'images/pngegg.png', 'adresse', 'escalade', 'autisme', 'adore faire des sudoku', 'images/63b8293dd7abe3.51430517.jpg'),
(28, 'de trochel', 'paul jean', '2003-06-30', 'images/6390a3e21a4ce6.79858360.jpg', 'un deux', 'football', 'un deux', 'doit aller aux toilettes très régulièrement', NULL);

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
(17, 15),
(18, 16),
(20, 17),
(22, 21),
(24, 22);

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
  `role_user` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `membre`
--

INSERT INTO `membre` (`id_membre`, `nom`, `prenom`, `adresse`, `code_postal`, `ville`, `courriel`, `date_naissance`, `mdp`, `pro`, `compte_valide`, `role_user`) VALUES
(1, 'compte', 'administrateur', '77 chemin de la salade ponsan', '31000', 'toulouse', 'test@gmail.com', '0000-00-00', 'e712226da3facf4c458d431a4068ce0cb47df2cf30f44636255db54adb76caa7', 1, 1, 1),
(2, 'Huppé', 'Christine ', '64, Boulevard de Normandie', '38600', 'nantes', 'christine@mail.com', '0000-00-00', '9425e1d10c0b47f77b61c759365f6c0089e1aea1d5f3fa9632afa48136e41287', 0, 1, 0),
(3, 'Lazure', 'Thomas', '97, Boulevard de Normandie', '97200', 'fort-de-france', 'thomas@mail.com', '1993-09-15', '1ebb92636717caa01d195ad0091f642d0a3d4f73a5cbe6ebb3502267d3c96d22', 0, 1, 0),
(9, 'trochel', 'paul', '77 chemin ponsan', '31000', 'toulouse', 'trochel.paul@gmail.com', '2003-06-30', 'a47fdb4f44865cd6e9d8f0c77733be25c29f6719e34a9cc67cabb405df8ff684', 0, 1, 0),
(17, 'paul', 'trochel', 'ljm', '5555', 'laùj', '1@mail.com', '3030-03-30', '6ca835f7f9e9689011cbda9ef8d56ab4f3b22cd3d6ca07810a8891e9957fe751', 0, 1, 2),
(18, 'non valide', 'utilisateur', 'lksj', '33333', 'ville', 'mail@mail.mail', '2003-06-30', '6ca835f7f9e9689011cbda9ef8d56ab4f3b22cd3d6ca07810a8891e9957fe751', 0, 1, 0),
(19, 'trochell', 'paul ', '77 chemin', '31000', 'toulousee', 'trochel@gmail.com', '2003-06-30', '64d0e6d2312c3d8fadb60fcce0c6151fec5962583d6fc08dbaa47c6b9245fb42', 0, 0, 0);

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

--
-- Déchargement des données de la table `message`
--

INSERT INTO `message` (`id_message`, `sujet`, `corps`, `date_heure`, `id_objectif`, `id_membre`) VALUES
(25, '2', '17:24 ?', '2023-01-11 17:25:30', 15, 1),
(26, '2', '17:24 ?', '2023-01-11 17:25:54', 15, 1),
(27, '2', '17:24 ?', '2023-01-11 17:25:56', 15, 1),
(28, '2', '17:24 ?', '2023-01-11 17:25:59', 15, 1),
(29, '2', '17:24 ?', '2023-01-11 17:28:29', 15, 1),
(30, '2', '17:24 ?', '2023-01-11 17:28:32', 15, 1),
(31, 'sujet', 'message', '2023-01-11 17:28:41', 15, 1),
(32, 'sujet', 'message', '2023-01-11 17:31:20', 15, 1),
(33, 'sujett', 'messaggggge', '2023-01-11 17:31:35', 15, 1),
(34, 'sujett', 'messaggggge', '2023-01-11 17:31:39', 15, 1),
(35, 'sujett', 'messaggggge', '2023-01-11 17:31:42', 15, 1),
(36, 'sujett', 'messaggggge', '2023-01-11 17:31:54', 15, 1),
(37, 'sujett', 'messaggggge', '2023-01-11 17:31:58', 15, 1),
(38, 'sujett', 'messaggggge', '2023-01-11 17:32:03', 15, 1),
(39, 'sujett', 'messaggggge', '2023-01-11 17:34:50', 15, 1),
(40, 'sujet', 'message', '2023-01-11 17:34:54', 15, 1),
(41, 'sujet', 'message', '2023-01-11 17:38:41', 15, 1),
(42, '2', '17:24 ?', '2023-01-11 17:38:48', 15, 1);

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
  `type` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `objectif`
--

INSERT INTO `objectif` (`id_objectif`, `intitule`, `nom`, `nb_jetons`, `duree`, `lien_image`, `priorite`, `travaille`, `id_membre`, `id_enfant`, `type`) VALUES
(9, 'systeme maison', 'bien manger le matin_1111111:ne pas manger en dehors des repas_1111111:bien faire ses devoirs_1111111:', 21, 216, 'jeton', 0, 0, 1, 27, 3),
(10, 'nom', 'rester concentré 5minutes_100:', 3, 0.007, 'jeton', 1, 0, 1, 27, 1),
(11, 'maison', 's"habiller seul_1111100:faire du sport_0000000:', 14, 0, 'jeton', 0, 0, 1, 27, 3),
(15, 'ecole français', 'rester concentrer 5minutes_1000:', 4, 3, 'jeton', 1, 0, 1, 28, 1),
(16, 'nom_sys', 'ne pas crier pendant dix min_000:', 3, 4, 'jeton', 4, 0, 1, 28, 1),
(17, 'manger', 'finir son assiete_000:', 3, 1, 'jeton', 1, 0, 1, 28, 1),
(18, 'ecoel', 'rester concentrer 5minutes_0:', 1, 1, 'jeton', 1, 0, 1, 28, 1),
(20, 'douche maison', 'il faut se doucher sans pleurer_00000:', 5, 5, 'jeton', 2, 0, 1, 27, 1),
(21, 'douche tous les jours', 'se laver tous les jours de la semaine_1111000:', 7, 192, 'jeton', 3, 0, 1, 27, 1),
(22, 'maison', 'ne pas faire de caprices_0100000:', 7, 1, 'jeton', 2, 0, 1, 27, 1),
(23, 'mlkj', 'mlkj_0000:', 4, 4, 'jeton', 1, 0, 1, 27, 1),
(24, '', '_:', 0, 168, 'jeton', 0, 0, 1, 28, 1);

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
(10, '2023-01-12 09:36:02', 1, 1),
(10, '2023-01-12 09:36:05', 1, 1),
(10, '2023-01-12 09:36:06', 1, 1),
(10, '2023-01-12 09:36:13', 1, 1),
(10, '2023-01-12 09:36:17', 1, 1),
(10, '2023-01-12 09:36:20', 1, 1),
(10, '2023-01-12 09:40:17', 1, 2),
(10, '2023-01-12 09:40:34', 1, 2),
(10, '2023-01-12 09:40:42', 1, 3),
(10, '2023-01-12 09:40:52', 1, 3),
(10, '2023-01-12 09:41:10', 1, 4),
(10, '2023-01-12 09:41:53', 1, 5),
(10, '2023-01-12 09:41:56', 1, 5),
(10, '2023-01-12 09:42:16', 1, 6),
(10, '2023-01-12 09:42:18', 1, 6),
(10, '2023-01-12 09:47:29', 1, 8),
(10, '2023-01-12 09:47:32', 1, 8),
(10, '2023-01-12 09:47:54', 1, 9),
(10, '2023-01-12 09:47:56', 1, 9),
(10, '2023-01-12 09:47:57', 1, 9),
(10, '2023-01-12 09:48:13', 1, 10),
(10, '2023-01-12 09:49:25', 1, 12),
(10, '2023-01-12 09:49:27', 1, 12),
(11, '2023-01-11 17:43:14', 1, 1),
(11, '2023-01-11 17:48:30', 1, 1),
(11, '2023-01-11 17:48:31', 1, 1),
(11, '2023-01-11 17:48:32', 1, 1),
(11, '2023-01-11 18:01:02', 1, 1);

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
(6, 'oui', NULL, NULL),
(7, 'oui', NULL, NULL),
(8, 'oui', NULL, NULL),
(9, 'oui', 'non', 'image'),
(10, 'oui', 'non', 'image'),
(11, 'oui', 'non', 'image'),
(12, 'oui', 'non', 'image'),
(13, 'oui', 'non', 'image'),
(14, 'soiree kebab', 'une soirée avec un kebab', 'image kebab'),
(15, 'un déssert en plus', 'tu aura un déssert au choix en plus', 'image'),
(16, 'aller en recrée 10minutes de plus', 'tu aura le droit de rester plus longtemps en récrée', 'image'),
(17, 'un bon savon', 'tu obiendra un bon savon', 'image savon'),
(18, 'kjh', 'kjh', NULL),
(19, 'une rc', 'la rec', 'images/63b95d9bdc2992.44240692.jpg'),
(20, 'une rc', 'la rec', 'images/63b95d9bdc2992.44240692.jpg'),
(21, 'une rc', 'la rec', 'images/63b95dc6610fd0.45669887.jpg'),
(22, '', '', NULL);

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
(27, 1, '2023-01-06', 'membre'),
(28, 18, '2022-12-06', 'user');

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
  MODIFY `id_enfant` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `membre`
--
ALTER TABLE `membre`
  MODIFY `id_membre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `message`
--
ALTER TABLE `message`
  MODIFY `id_message` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT pour la table `objectif`
--
ALTER TABLE `objectif`
  MODIFY `id_objectif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `recompense`
--
ALTER TABLE `recompense`
  MODIFY `id_recompense` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

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
