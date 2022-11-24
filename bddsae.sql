-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 24 nov. 2022 à 20:55
-- Version du serveur : 10.4.24-MariaDB
-- Version de PHP : 7.4.29

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
  `lien_jeton` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `enfant`
--

INSERT INTO `enfant` (`id_enfant`, `nom`, `prenom`, `date_naissance`, `lien_jeton`) VALUES
(1, 'trochel', 'paul', '2003-06-30', 'lien_jeton'),
(2, 'Patus', 'Guillaume', '2004-10-18', 'lien image'),
(3, 'Laliberté', 'Auda', '2005-07-22', 'lien_jeton'),
(4, 'Senapus', 'Marcil', '2009-04-17', 'lien_jeton'),
(5, 'Chatigny', 'Gilles', '2010-05-06', 'lien_jeton');

-- --------------------------------------------------------

--
-- Structure de la table `lier`
--

CREATE TABLE `lier` (
  `id_objectif` int(11) NOT NULL,
  `id_recompense` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `mdp` varchar(50) DEFAULT NULL,
  `pro` tinyint(1) DEFAULT NULL,
  `compte_valide` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `membre`
--

INSERT INTO `membre` (`id_membre`, `nom`, `prenom`, `adresse`, `code_postal`, `ville`, `courriel`, `date_naissance`, `mdp`, `pro`, `compte_valide`) VALUES
(1, 'compte', 'administrateur', '24 rue du taur ', '31000', 'toulouse', 'test@gmail.com', '1996-12-27', 'admin', 1, 1),
(2, 'Huppé', 'Christine ', '64, Boulevard de Normandie\r\n', '38600', 'fontaine', 'christine@mail.com', '1993-09-15', 'huppe', 0, 1),
(3, 'Lazure', 'Thomas', '97, Boulevard de Normandie', '97200', 'fort-de-france', 'thomas@mail.com', '1993-09-15', 'thomas', 0, 1);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `objectif`
--

CREATE TABLE `objectif` (
  `id_objectif` int(11) NOT NULL,
  `intitule` varchar(50) DEFAULT NULL,
  `nb_jetons` tinyint(4) DEFAULT NULL,
  `duree` double DEFAULT NULL,
  `lien_image` varchar(100) DEFAULT NULL,
  `priorite` int(11) DEFAULT NULL,
  `travaille` tinyint(1) DEFAULT NULL,
  `id_membre` int(11) NOT NULL,
  `id_enfant` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `placer_jeton`
--

CREATE TABLE `placer_jeton` (
  `id_objectif` int(11) NOT NULL,
  `date_heure` datetime NOT NULL,
  `id_membre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `recompense`
--

CREATE TABLE `recompense` (
  `id_recompense` int(11) NOT NULL,
  `intitule` varchar(50) DEFAULT NULL,
  `descriptif` text DEFAULT NULL,
  `lien_image` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `suivre`
--

CREATE TABLE `suivre` (
  `id_enfant` int(11) NOT NULL,
  `id_membre` int(11) NOT NULL,
  `date_demande_equipe` date DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `suivre`
--

INSERT INTO `suivre` (`id_enfant`, `id_membre`, `date_demande_equipe`, `role`) VALUES
(2, 1, NULL, 'Parent'),
(3, 1, NULL, 'Parent');

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
  MODIFY `id_enfant` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `membre`
--
ALTER TABLE `membre`
  MODIFY `id_membre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `message`
--
ALTER TABLE `message`
  MODIFY `id_message` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `objectif`
--
ALTER TABLE `objectif`
  MODIFY `id_objectif` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `recompense`
--
ALTER TABLE `recompense`
  MODIFY `id_recompense` int(11) NOT NULL AUTO_INCREMENT;

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
