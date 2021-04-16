-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Lun 27 Avril 2020 à 11:57
-- Version du serveur :  5.7.11
-- Version de PHP :  5.6.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `authentificationtpa3bs`
--

-- --------------------------------------------------------

--
-- Structure de la table `typeutilisateur`
--

CREATE TABLE `typeutilisateur` (
  `idtype` int(11) NOT NULL,
  `descriptiontype` varchar(20) NOT NULL,
  `admintype` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `typeutilisateur`
--

INSERT INTO `typeutilisateur` (`idtype`, `descriptiontype`, `admintype`) VALUES
(1, 'admin', 1),
(2, 'utilisateur', 0);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(32) NOT NULL,
  `mdp` varchar(256) NOT NULL,
  `tokenPassword` varchar(32) NOT NULL,
  `tokenInscription` varchar(32) DEFAULT NULL,
  `idtype` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `mdp`, `tokenPassword`, `tokenInscription`, `idtype`) VALUES
(26, 'admin', 'admin@gmail.com', '$2y$10$XEfRGRQwyobS7Jf2C7n9wOYfks9JK7FBclADGj2ogMBsWx2yfq6LG', '961806f5ed1e5ab005ea37d4665e8b89', NULL, 1),
(27, 'baptiste', 'bapt@gmail.com', '$2y$10$9UMmOBI0YLTmi0Mlzfah1.vqvMjJQ82XbGomIsGsTIcoZS8GCsmTC', '0021efdc30d2eccea5cd73b3f448e161', NULL, 2);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `typeutilisateur`
--
ALTER TABLE `typeutilisateur`
  ADD PRIMARY KEY (`idtype`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idtype` (`idtype`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `typeutilisateur`
--
ALTER TABLE `typeutilisateur`
  MODIFY `idtype` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`idtype`) REFERENCES `typeutilisateur` (`idtype`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
