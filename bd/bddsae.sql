-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 16 jan. 2023 à 14:00
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
(9, '2023-01-14 20:05:01', 1, 1),
(10, '2022-12-22 23:14:23', 1, 1),
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
(10, '2023-01-12 09:47:57', 1, 9),
(10, '2023-01-12 09:48:13', 1, 10),
(10, '2023-01-12 09:49:27', 1, 12),
(10, '2023-01-14 17:41:30', 1, 13),
(10, '2023-01-14 17:41:31', 1, 13),
(10, '2023-01-14 17:41:33', 1, 13),
(10, '2023-01-14 17:41:56', 1, 14),
(10, '2023-01-14 17:41:58', 1, 14),
(10, '2023-01-14 17:41:59', 1, 14),
(10, '2023-01-15 13:50:11', 1, 15),
(10, '2023-01-15 13:53:22', 1, 16),
(10, '2023-01-15 13:53:23', 1, 16),
(10, '2023-01-15 13:53:25', 1, 16),
(11, '2023-01-14 16:58:17', 1, 1),
(11, '2023-01-14 16:58:30', 1, 1),
(11, '2023-01-14 16:58:32', 1, 1),
(11, '2023-01-14 16:58:34', 1, 1),
(11, '2023-01-14 16:58:35', 1, 1),
(11, '2023-01-14 16:58:37', 1, 1),
(11, '2023-01-14 16:58:38', 1, 1),
(11, '2023-01-14 16:58:39', 1, 1),
(11, '2023-01-14 16:58:41', 1, 1),
(11, '2023-01-14 16:58:42', 1, 1),
(11, '2023-01-14 16:58:43', 1, 1),
(11, '2023-01-14 16:58:45', 1, 1),
(11, '2023-01-14 16:58:46', 1, 1),
(11, '2023-01-14 16:58:48', 1, 1),
(11, '2023-01-14 16:58:50', 1, 1),
(17, '2023-01-14 17:40:04', 1, 1),
(17, '2023-01-14 17:40:06', 1, 1),
(17, '2023-01-14 17:40:07', 1, 1),
(22, '2023-01-15 14:30:59', 1, 1),
(22, '2023-01-15 14:31:05', 1, 1),
(26, '2023-01-15 16:45:05', 1, 1),
(26, '2023-01-15 16:45:16', 1, 2),
(26, '2023-01-15 16:45:18', 1, 2),
(26, '2023-01-15 16:45:19', 1, 2);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `placer_jeton`
--
ALTER TABLE `placer_jeton`
  ADD PRIMARY KEY (`id_objectif`,`date_heure`),
  ADD KEY `id_membre` (`id_membre`);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `placer_jeton`
--
ALTER TABLE `placer_jeton`
  ADD CONSTRAINT `placer_jeton_ibfk_1` FOREIGN KEY (`id_objectif`) REFERENCES `objectif` (`id_objectif`),
  ADD CONSTRAINT `placer_jeton_ibfk_2` FOREIGN KEY (`id_membre`) REFERENCES `membre` (`id_membre`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
