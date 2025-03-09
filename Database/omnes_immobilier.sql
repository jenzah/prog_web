-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : sam. 08 mars 2025 à 11:29
-- Version du serveur : 5.7.39
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `omnes_immobilier`
--
CREATE DATABASE IF NOT EXISTS `omnes_immobilier` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `omnes_immobilier`;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
-- 08.03.2025 Jennifer: J'ai supprimé les table, car on n'en a pas besoin
--

DROP TABLE IF EXISTS `admin`;

DROP TABLE IF EXISTS `city`;

DROP TABLE IF EXISTS `contact`;

DROP TABLE IF EXISTS `state`;

DROP TABLE IF EXISTS `feedback`;

DROP TABLE IF EXISTS `appointments`;

DROP TABLE IF EXISTS `property`;

DROP TABLE IF EXISTS `user`;



-- --------------------------------------------------------

--
-- Table structure for table `property`
--

DROP TABLE IF EXISTS `property`;
CREATE TABLE `property` (
  `pid` int(50) NOT NULL,
  `agentid` int(50) NOT NULL,
  `title` varchar(200) NOT NULL,
  `propertyDescription` longtext NOT NULL,
  `propertyType` varchar(100) NOT NULL, -- résidentiel, commercial, terrain, appartement à louer
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
  `status` varchar(50) NOT NULL, -- a vendre, a louer, vendu, loué
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `property`
--

INSERT INTO `property` (`pid`, `agentid`, `title`, `propertyDescription`, `propertyType`, `area`, `nbRooms`, `nbBathrooms`, `price`, `location`, `city`, `department`, `pimage1`, `pimage2`, `pimage3`, `status`, `date`) VALUES
(501, 201, 'Appartement Haussmannien', 'Lumineux avec moulures anciennes', 'résidentiel', 85, 3, 1, 780000, '12 Avenue Montaigne', 'Paris', 'Paris', '1.jpg', '2.jpg', '3.jpg', 'A vendre', '2025-02-28 10:30:00'),
(502, 201, 'Loft industriel', 'Espace ouvert plein charme', 'résidentiel', 120, 2, 2, 950000, '5 Rue des Entrepreneurs', 'Boulogne-Billancourt', 'Hauts-de-Seine', '4.jpg', '5.jpg', '6.jpg', 'A vendre', '2025-02-27 15:45:00'),
(503, 202, 'Local Commercial', 'Idéal pour restaurant chic', 'commercial', 150, 3, 2, 1200000, '28 Rue du Commerce', 'Versailles', 'Yvelines', '7.jpg', '8.jpg', '9.jpg', 'A vendre', '2025-03-01 09:15:00'),
(504, 202, 'Terrain constructible', 'Vue dégagée zone résidentielle', 'terrain', 500, 0, 0, 320000, 'Chemin des Vignes', 'Saint-Germain-en-Laye', 'Yvelines', '10.jpg', '11.jpg', '12.jpg', 'A vendre', '2025-02-25 11:20:00'),
(505, 203, 'Studio moderne', 'Proche transports et commerces', 'appartement A louer', 35, 1, 1, 950, '45 Rue Mouffetard', 'Paris', 'Paris', '13.jpg', '14.jpg', '15.jpg', 'A louer', '2025-02-26 14:00:00'),
(506, 203, 'Maison de ville', 'Jardin privatif bien exposé', 'résidentiel', 145, 5, 2, 890000, '17 Rue des Rosiers', 'Neuilly-sur-Seine', 'Hauts-de-Seine', '16.jpg', '17.jpg', '18.jpg', 'vendu', '2025-02-22 16:30:00'),
(507, 204, 'Bureaux modernes', 'Espace de travail lumineux', 'commercial', 200, 5, 2, 1500000, '8 Avenue des Champs-Élysées', 'Paris', 'Paris', '19.jpg', '20.jpg', '21.jpg', 'A vendre', '2025-03-02 10:00:00'),
(508, 204, 'Appartement familial', 'Proche écoles et parcs', 'résidentiel', 110, 4, 2, 720000, '25 Rue des Écoles', 'Créteil', 'Val-de-Marne', '22.jpg', '23.jpg', '24.jpg', 'A louer', '2025-02-24 13:45:00'),
(509, 205, 'Terrain avec vue', 'Constructible zone calme', 'terrain', 800, 0, 0, 450000, 'Route de la Forêt', 'Évry', 'Essonne', '25.jpg', '26.jpg', '27.jpg', 'A vendre', '2025-02-23 11:15:00'),
(510, 205, 'Duplex contemporain', 'Prestations haut de gamme', 'résidentiel', 130, 4, 3, 1050000, '3 Boulevard Haussmann', 'Paris', 'Paris', '28.jpg', '29.jpg', '30.jpg', 'loué', '2025-02-21 09:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `uid` int(50) NOT NULL,
  `uname` varchar(100) NOT NULL,
  `ufirstname` varchar(100) NOT NULL,
  `uemail` varchar(100) NOT NULL,
  `uphone` varchar(20) NOT NULL,
  `upass` varchar(255) NOT NULL,
  `utype` varchar(50) NOT NULL,
  `uimage` varchar(300) NOT NULL,
  `specialty` VARCHAR(100) DEFAULT NULL -- residentiel,terrain,appartement,commercial
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`uid`, `uname`, `ufirstname`, `uemail`, `uphone`, `upass`, `utype`, `uimage`) VALUES
(201, 'Doe', 'John', 'agent1@example.com', '1234567890', 'agent1', 'agent', 'agent1.jpg'),
(202, 'Williams', 'Alice', 'agent2@example.com', '1234567891', 'agent2', 'agent', 'agent2.jpg'),
(203, 'Smith', 'Michael', 'agent3@example.com', '1234567892', 'agent3', 'agent', 'agent3.jpg'),
(204, 'Taylor', 'Sophia', 'agent4@example.com', '1234567893', 'agent4', 'agent', 'agent4.jpg'),
(205, 'Johnson', 'Daniel', 'agent5@example.com', '1234567894', 'agent5', 'agent', 'agent5.jpg'),
(101, 'Miller', 'Emily', 'user1@example.com', '1234567895', 'user1', 'user', 'user1.jpg'),
(102, 'Anderson', 'James', 'user2@example.com', '1234567896', 'user2', 'user', 'user2.jpg'),
(301, 'Thomas', 'Robert', 'admin1@example.com', '1234567897', 'admin1', 'admin', 'admin1.jpg');





-- Création de la table appointments en utilisant la table user existante
CREATE TABLE IF NOT EXISTS `appointments` (
  `aid` int(50) NOT NULL AUTO_INCREMENT,
  `client_id` int(50) NOT NULL,
  `agent_id` int(50) NOT NULL,
  `property_id` int(50) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `place` varchar(255) NOT NULL,
  `is_paid` tinyint(1) DEFAULT 0,
  `price` decimal(10,2) DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`aid`),
  KEY `client_id` (`client_id`),
  KEY `agent_id` (`agent_id`),
  KEY `property_id` (`property_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Current appointments (future dates)
-- Thursday is apparently presentation day, so we'll set some appointments for future dates after that
INSERT INTO `appointments` (`client_id`, `agent_id`, `property_id`, `appointment_date`, `appointment_time`, `place`, `is_paid`, `price`, `comments`, `created_at`) VALUES
-- Client 101 future appointments
(101, 201, 501, '2025-03-15', '10:00:00', 'Sur place: 12 Avenue Montaigne, Paris', 0, 150.00, 'Premier rendez-vous pour visiter cet appartement haussmannien', CURRENT_TIMESTAMP()),
(101, 202, 503, '2025-03-16', '14:00:00', 'Sur place: 28 Rue du Commerce, Versailles', 0, 200.00, 'Visite du local commercial pour possible restaurant', CURRENT_TIMESTAMP()),

-- Client 102 future appointments
(102, 201, 502, '2025-03-14', '11:00:00', 'Sur place: 5 Rue des Entrepreneurs, Boulogne-Billancourt', 0, 150.00, 'Visite du loft industriel', CURRENT_TIMESTAMP()),
(102, 202, 504, '2025-03-17', '16:00:00', 'Sur place: Chemin des Vignes, Saint-Germain-en-Laye', 0, 150.00, 'Visite du terrain constructible', CURRENT_TIMESTAMP());

-- Past appointments
INSERT INTO `appointments` (`client_id`, `agent_id`, `property_id`, `appointment_date`, `appointment_time`, `place`, `is_paid`, `price`, `comments`, `created_at`) VALUES
-- Client 101 past appointments
(101, 201, 506, '2025-03-05', '09:00:00', 'Sur place: 17 Rue des Rosiers, Neuilly-sur-Seine', 1, 150.00, 'Le client a beaucoup aimé cette maison de ville', '2025-03-05 08:30:00'),
(101, 202, 509, '2025-03-01', '15:00:00', 'Sur place: Route de la Forêt, Évry', 1, 150.00, 'Le client s\'est montré intéressé par le terrain avec vue', '2025-03-01 14:30:00'),

-- Client 102 past appointments
(102, 201, 510, '2025-03-03', '13:00:00', 'Sur place: 3 Boulevard Haussmann, Paris', 1, 200.00, 'Le client a apprécié les prestations haut de gamme du duplex', '2025-03-03 12:30:00'),
(102, 202, 507, '2025-02-28', '17:00:00', 'Sur place: 8 Avenue des Champs-Élysées, Paris', 1, 200.00, 'Le client a trouvé l\'espace de travail très lumineux', '2025-02-28 16:30:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `property`
--
ALTER TABLE `property`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `uemail` (`uemail`);


ALTER TABLE `appointments`
ADD CONSTRAINT `fk_appointments_client` FOREIGN KEY (`client_id`) REFERENCES `user` (`uid`),
ADD CONSTRAINT `fk_appointments_agent` FOREIGN KEY (`agent_id`) REFERENCES `user` (`uid`),
ADD CONSTRAINT `fk_appointments_property` FOREIGN KEY (`property_id`) REFERENCES `property` (`pid`);
--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `property`
--
ALTER TABLE `property`
  MODIFY `pid` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=500;


--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `uid` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;
COMMIT;
