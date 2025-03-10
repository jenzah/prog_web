-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : lun. 10 mars 2025 à 11:06
-- Version du serveur : 5.7.39
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `omnes_immobilier`
--

-- --------------------------------------------------------

--
-- Structure de la table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_status` enum('pending','completed','failed','refunded') NOT NULL DEFAULT 'pending',
  `payment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `service_fee` decimal(10,2) NOT NULL DEFAULT '500.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `payment`
--

INSERT INTO `payment` (`payment_id`, `user_id`, `property_id`, `amount`, `payment_status`, `payment_date`, `service_fee`) VALUES
(7, 3, 501, '150000.00', 'pending', '2025-03-10 10:56:50', '500.00'),
(10, 3, 501, '500.00', 'completed', '2025-03-10 11:00:23', '500.00');

-- --------------------------------------------------------

--
-- Structure de la table `property`
--

CREATE TABLE `property` (
  `pid` int(50) NOT NULL,
  `agentid` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `propertyDescription` longtext NOT NULL,
  `propertyType` varchar(100) NOT NULL,
  `area` int(50) NOT NULL,
  `nbRooms` int(50) NOT NULL,
  `nbBathrooms` int(50) NOT NULL,
  `price` int(50) NOT NULL,
  `location` varchar(200) NOT NULL,
  `city` varchar(100) NOT NULL,
  `department` varchar(100) NOT NULL,
  `pimage1` varchar(300) NOT NULL,
  `pimage2` varchar(300) NOT NULL,
  `pimage3` varchar(300) NOT NULL,
  `status` varchar(50) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `property`
--

INSERT INTO `property` (`pid`, `agentid`, `title`, `propertyDescription`, `propertyType`, `area`, `nbRooms`, `nbBathrooms`, `price`, `location`, `city`, `department`, `pimage1`, `pimage2`, `pimage3`, `status`, `date`) VALUES
(501, 4, 'Appartement Haussmannien', 'Lumineux avec moulures anciennes', 'résidentiel', 85, 3, 1, 780000, '12 Avenue Montaigne', 'Paris', 'Paris', '1.jpg', '2.jpg', '3.jpg', 'A vendre', '2025-02-28 10:30:00'),
(502, 4, 'Loft industriel', 'Espace ouvert plein charme', 'résidentiel', 120, 2, 2, 950000, '5 Rue des Entrepreneurs', 'Boulogne-Billancourt', 'Hauts-de-Seine', '4.jpg', '5.jpg', '6.jpg', 'A vendre', '2025-02-27 15:45:00'),
(503, 5, 'Local Commercial', 'Idéal pour restaurant chic', 'commercial', 150, 3, 2, 1200000, '28 Rue du Commerce', 'Versailles', 'Yvelines', '7.jpg', '8.jpg', '9.jpg', 'A vendre', '2025-03-01 09:15:00'),
(504, 5, 'Terrain constructible', 'Vue dégagée zone résidentielle', 'terrain', 500, 0, 0, 320000, 'Chemin des Vignes', 'Saint-Germain-en-Laye', 'Yvelines', '10.jpg', '11.jpg', '12.jpg', 'A vendre', '2025-02-25 11:20:00');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `uid` int(11) NOT NULL,
  `uname` varchar(100) NOT NULL,
  `uemail` varchar(100) NOT NULL,
  `uphone` varchar(20) NOT NULL,
  `upass` varchar(255) NOT NULL,
  `utype` enum('client','agent') NOT NULL DEFAULT 'client',
  `uimage` varchar(300) DEFAULT NULL,
  `address1` varchar(255) NOT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `postal_code` varchar(10) NOT NULL,
  `country` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`uid`, `uname`, `uemail`, `uphone`, `upass`, `utype`, `uimage`, `address1`, `address2`, `city`, `postal_code`, `country`) VALUES
(3, 'kawtar', 'kawtar@gmail.com', '9077756576', 'kawtar', 'client', NULL, '12 Rue de Paris', NULL, 'Paris', '75001', 'France'),
(4, 'Agent1', 'agent1@email.com', '0600000001', 'password_hash', 'agent', NULL, '123 Rue des Agents', NULL, 'Paris', '75001', 'France'),
(5, 'Agent2', 'agent2@email.com', '0600000002', 'password_hash', 'agent', NULL, '456 Boulevard des Experts', NULL, 'Lyon', '69001', 'France');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `property_id` (`property_id`);

--
-- Index pour la table `property`
--
ALTER TABLE `property`
  ADD PRIMARY KEY (`pid`),
  ADD KEY `fk_property_user` (`agentid`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`uid`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `property`
--
ALTER TABLE `property`
  MODIFY `pid` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=505;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`uid`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`property_id`) REFERENCES `property` (`pid`) ON DELETE CASCADE;

--
-- Contraintes pour la table `property`
--
ALTER TABLE `property`
  ADD CONSTRAINT `fk_property_user` FOREIGN KEY (`agentid`) REFERENCES `user` (`uid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
