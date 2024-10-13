-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 13 oct. 2024 à 15:23
-- Version du serveur : 8.3.0
-- Version de PHP : 8.1.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gifl_init_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `collections`
--

DROP TABLE IF EXISTS `collections`;
CREATE TABLE IF NOT EXISTS `collections` (
  `collection_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `private` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`collection_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `collections_gifs`
--

DROP TABLE IF EXISTS `collections_gifs`;
CREATE TABLE IF NOT EXISTS `collections_gifs` (
  `collection_id` int NOT NULL,
  `gif_id` int NOT NULL,
  KEY `collections_gifs_ibfk_1` (`collection_id`),
  KEY `collections_gifs_ibfk_2` (`gif_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `collections_hashtags`
--

DROP TABLE IF EXISTS `collections_hashtags`;
CREATE TABLE IF NOT EXISTS `collections_hashtags` (
  `collection_id` int NOT NULL,
  `hashtag_id` int NOT NULL,
  KEY `hashtag_id` (`hashtag_id`),
  KEY `collections_hashtags_ibfk_1` (`collection_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `gifs`
--

DROP TABLE IF EXISTS `gifs`;
CREATE TABLE IF NOT EXISTS `gifs` (
  `gif_id` int NOT NULL AUTO_INCREMENT,
  `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` int NOT NULL,
  `created_at` datetime NOT NULL,
  `reported` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`gif_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `gifs_hashtags`
--

DROP TABLE IF EXISTS `gifs_hashtags`;
CREATE TABLE IF NOT EXISTS `gifs_hashtags` (
  `gif_id` int NOT NULL,
  `hashtag_id` int NOT NULL,
  KEY `hashtag_id` (`hashtag_id`),
  KEY `gifs_hashtags_ibfk_1` (`gif_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `hashtags`
--

DROP TABLE IF EXISTS `hashtags`;
CREATE TABLE IF NOT EXISTS `hashtags` (
  `hashtag_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`hashtag_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`user_id`, `email`, `name`, `password`, `admin`, `avatar`) VALUES
(15, 'public@gifl.bzh', 'GIFL public domain', '$2y$10$55fxHsk6kU9rJiCOaud12uaJkZAizVQhiAZYkfMraJhAdm15JVG8a', 1, 'http://gifl/assets/images/logoGIFL.png');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `collections`
--
ALTER TABLE `collections`
  ADD CONSTRAINT `collections_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Contraintes pour la table `collections_gifs`
--
ALTER TABLE `collections_gifs`
  ADD CONSTRAINT `collections_gifs_ibfk_1` FOREIGN KEY (`collection_id`) REFERENCES `collections` (`collection_id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `collections_gifs_ibfk_2` FOREIGN KEY (`gif_id`) REFERENCES `gifs` (`gif_id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Contraintes pour la table `collections_hashtags`
--
ALTER TABLE `collections_hashtags`
  ADD CONSTRAINT `collections_hashtags_ibfk_1` FOREIGN KEY (`collection_id`) REFERENCES `collections` (`collection_id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `collections_hashtags_ibfk_2` FOREIGN KEY (`hashtag_id`) REFERENCES `hashtags` (`hashtag_id`);

--
-- Contraintes pour la table `gifs`
--
ALTER TABLE `gifs`
  ADD CONSTRAINT `gifs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Contraintes pour la table `gifs_hashtags`
--
ALTER TABLE `gifs_hashtags`
  ADD CONSTRAINT `gifs_hashtags_ibfk_1` FOREIGN KEY (`gif_id`) REFERENCES `gifs` (`gif_id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `gifs_hashtags_ibfk_2` FOREIGN KEY (`hashtag_id`) REFERENCES `hashtags` (`hashtag_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
