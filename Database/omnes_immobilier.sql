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

DROP TABLE IF EXISTS `chat_messages`;

DROP TABLE IF EXISTS `chat_participants`;

DROP TABLE IF EXISTS `chat_rooms`;

DROP TABLE IF EXISTS `rendez_vous`;
DROP TABLE IF EXISTS `rendez_vous2`;

DROP TABLE IF EXISTS `agent_disponibilite`;

DROP TABLE IF EXISTS `agent_disponibilite2`;
DROP TABLE IF EXISTS `agent_disponibilite3`;

DROP TABLE IF EXISTS `agent_schedules`;
DROP TABLE IF EXISTS `property`;

DROP TABLE IF EXISTS `chat_messages`;

DROP TABLE IF EXISTS `chat_participants`;

DROP TABLE IF EXISTS `chat_rooms`;

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

-- Agent 201
(501, 201, 'Appartement Parisien', 'Très lumineux avec vue sur Paris', 'résidentiel', 80, 3, 1, 750000, '12 Avenue Montaigne', 'Paris', 'Paris', '1.jpg', '2.jpg', NULL, 'A Vendre', '2025-02-28 10:30:00'),
(502, 201, 'Loft Industriel', 'Espace ouvert plein de charme', 'résidentiel', 120, 2, 2, 950000, '5 Rue des Entrepreneurs', 'Boulogne-Billancourt', 'Hauts-de-Seine', '3.jpg', '4.jpg', NULL, 'A Vendre', '2025-02-27 15:45:00'),

-- Agent 202
(503, 202, 'Local Commercial', 'Idéal pour restaurant chic', 'commercial', 150, 3, 2, 1200000, '28 Rue du Commerce', 'Versailles', 'Yvelines', '5.jpg', '6.jpg', NULL, 'A Vendre', '2025-03-01 09:15:00'),
(504, 202, 'Terrain Constructible', 'Vue dégagée zone résidentielle', 'terrain', 500, 0, 0, 320000, 'Chemin des Vignes', 'Saint-Germain-en-Laye', 'Yvelines', '7.jpg', '8.jpg', NULL, 'A Vendre', '2025-02-25 11:20:00'),

-- Agent 203
(505, 203, 'Studio Moderne', 'Proche transports et commerces', 'appartement A louer', 35, 1, 1, 950, '45 Rue Mouffetard', 'Paris', 'Paris', '9.jpg', NULL, NULL, 'A Louer', '2025-02-26 14:00:00'),
(506, 203, 'Maison de Ville', 'Jardin privatif bien exposé', 'résidentiel', 145, 5, 2, 890000, '17 Rue des Rosiers', 'Neuilly-sur-Seine', 'Hauts-de-Seine', '10.jpg', NULL, NULL, 'Vendu', '2025-02-22 16:30:00'),

-- Agent 204
(507, 204, 'Bureaux Modernes', 'Espace de travail lumineux', 'commercial', 200, 5, 2, 1500000, '8 Avenue des Champs-Élysées', 'Paris', 'Paris', '11.jpg', NULL, NULL, 'A Vendre', '2025-03-02 10:00:00'),
(508, 204, 'Appartement Familial', 'Proche écoles et parcs', 'résidentiel', 110, 4, 2, 720000, '25 Rue des Écoles', 'Créteil', 'Val-de-Marne', '12.jpg', NULL, NULL, 'A Louer', '2025-02-24 13:45:00'),

-- Agent 205
(509, 205, 'Terrain avec Vue', 'Constructible zone calme', 'terrain', 800, 0, 0, 450000, 'Route de la Forêt', 'Évry', 'Essonne', '13.jpg', NULL, NULL, 'A Vendre', '2025-02-23 11:15:00'),
(510, 205, 'Duplex Contemporain', 'Prestations haut de gamme', 'résidentiel', 130, 4, 3, 1050000, '3 Boulevard Haussmann', 'Paris', 'Paris', '14.jpg', NULL, NULL, 'Loué', '2025-02-21 09:30:00'),

-- Agent 206
(511, 206, 'Appartement Vue Seine', 'Très lumineux avec balcon', 'résidentiel', 90, 3, 1, 820000, '15 Quai d Orsay', 'Paris', 'Paris', '15.jpg', NULL, NULL, 'A Vendre', '2025-02-29 10:00:00'),
(512, 206, 'Maison de Campagne', 'Jardin privatif bien entretenu', 'résidentiel', 160, 5, 2, 980000, '20 Chemin des Fleurs', 'Fontainebleau', 'Seine-et-Marne', '16.jpg', NULL, NULL, 'Vendu', '2025-02-20 16:00:00'),

-- Agent 207
(513, 207, 'Studio Étudiant', 'Proche université et transports', 'appartement A louer', 30, 1, 1, 800, '25 Rue de la Sorbonne', 'Paris', 'Paris', '17.jpg', NULL, NULL, 'A Louer', '2025-03-01 14:30:00'),
(514, 207, 'Bureaux Partagés', 'Espace de coworking moderne', 'commercial', 180, 5, 2, 1600000, '10 Rue de Rivoli', 'Paris', 'Paris', '18.jpg', NULL, NULL, 'A Vendre', '2025-03-03 10:00:00'),

-- Agent 208
(515, 208, 'Appartement Familial', 'Proche écoles et parcs', 'résidentiel', 120, 4, 2, 780000, '30 Rue des Écoles', 'Créteil', 'Val-de-Marne', '19.jpg', NULL, NULL, 'A Vendre', '2025-02-25 13:45:00'),
(516, 208, 'Terrain Constructible', 'Vue dégagée zone résidentielle', 'terrain', 600, 0, 0, 380000, 'Chemin des Vignes', 'Saint-Germain-en-Laye', 'Yvelines', '20.jpg', NULL, NULL, 'A Vendre', '2025-02-24 11:20:00');


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
  `specialty` VARCHAR(100) DEFAULT 'Résidentiel', -- residentiel, terrain, appartement, commercial
  `uaddress1` varchar(255) DEFAULT NULL,
  `uaddress2` varchar(255) DEFAULT NULL,
  `ucity` varchar(100) DEFAULT NULL,
  `upostal_code` varchar(10) DEFAULT NULL,
  `ucountry` varchar(100) DEFAULT NULL,
  `formations` TEXT DEFAULT NULL,
  `experiences` TEXT DEFAULT NULL,
  `cv` VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`uid`, `uname`, `ufirstname`, `uemail`, `uphone`, `upass`, `utype`, `uimage`, `specialty`, `uaddress1`, `uaddress2`, `ucity`, `upostal_code`, `ucountry`, `formations`, `experiences`) VALUES

-- Agents
(201, 'Dupont', 'Pierre', 'agent1@example.com', '0600000001', 'agent1', 'agent', 'agent1.jpg', 'Résidentiel', '12 Rue de Paris', NULL, 'Paris', '75001', 'France', 'Diplômé de l École des Métiers de lImmobilier, Certificat en Gestion Immobilière', '5 ans d expérience dans la vente de biens résidentiels'),
(202, 'Durand', 'Julie', 'agent2@example.com', '0600000002', 'agent2', 'agent', 'agent2.jpg', 'Résidentiel', '23 Rue de Lyon', NULL, 'Lyon', '69001', 'France', 'Licence en Économie, Spécialisation en Immobilier', '3 ans d expérience dans la location de logements'),
(203, 'Martin', 'Sébastien', 'agent3@example.com', '0600000003', 'agent3', 'agent', 'agent3.jpg', 'Commercial', '45 Rue de Bordeaux', NULL, 'Bordeaux', '33000', 'France', 'Master en Gestion Immobilière, Certificat en Marketing', '7 ans d expérience dans la vente de locaux commerciaux'),
(204, 'Lefebvre', 'Aurélie', 'agent4@example.com', '0600000004', 'agent4', 'agent', 'agent4.jpg', 'Commercial', '56 Rue de Toulouse', NULL, 'Toulouse', '31000', 'France', 'Diplômée de l Institut des Études Immobilières, Certificat en Évaluation Immobilière', '4 ans d expérience dans la gestion de bureaux'),
(205, 'Garcia', 'Thomas', 'agent5@example.com', '0600000005', 'agent5', 'agent', 'agent5.jpg', 'Terrain', '78 Rue de Marseille', NULL, 'Marseille', '13001', 'France', 'BTS en Immobilier, Certificat en Urbanisme', '6 ans d expérience dans la vente de terrains constructibles'),
(206, 'Moreau', 'Laurent', 'agent6@example.com', '0600000006', 'agent6', 'agent', 'agent6.jpg', 'Terrain', '90 Rue de Nice', NULL, 'Nice', '06000', 'France', 'Diplômé de l École Supérieure des Métiers de l Immobilier, Certificat en Géomètre', '2 ans d expérience dans la promotion immobilière'),
(207, 'Roux', 'Christophe', 'agent7@example.com', '0600000007', 'agent7', 'agent', 'agent7.jpg', 'Appartement', '123 Rue de Strasbourg', NULL, 'Strasbourg', '67000', 'France', 'Licence en Droit, Spécialisation en Droit Immobilier', '8 ans d expérience dans la gestion de copropriétés'),
(208, 'Fournier', 'Catherine', 'agent8@example.com', '0600000008', 'agent8', 'agent', 'agent8.jpg', 'Appartement', '145 Rue de Lille', NULL, 'Lille', '59000', 'France', 'Master en Économie Immobilière, Certificat en Évaluation de Biens', '9 ans d expérience dans la vente d appartements de luxe'),

-- Clients
(301, 'Leroy', 'Marie', 'client1@example.com', '0600000009', 'client1', 'client', 'default.jpg', NULL, '10 Rue de Rouen', NULL, 'Rouen', '76000', 'France', NULL, NULL),
(302, 'Bertrand', 'Philippe', 'client2@example.com', '0600000010', 'client2', 'client', 'default.jpg', NULL, '20 Rue de Reims', NULL, 'Reims', '51000', 'France', NULL, NULL),
(303, 'Marchand', 'Isabelle', 'client3@example.com', '0600000011', 'client3', 'client', 'default.jpg', NULL, '30 Rue de Dijon', NULL, 'Dijon', '21000', 'France', NULL, NULL),
(304, 'Pierre', 'Sylvie', 'client4@example.com', '0600000012', 'client4', 'client', 'default.jpg', NULL, '40 Rue de Clermont', NULL, 'Clermont-Ferrand', '63000', 'France', NULL, NULL),
(305, 'Dubois', 'François', 'client5@example.com', '0600000013', 'client5', 'client', 'default.jpg', NULL, '50 Rue de Grenoble', NULL, 'Grenoble', '38000', 'France', NULL, NULL),
(306, 'Girard', 'Nathalie', 'client6@example.com', '0600000014', 'client6', 'client', 'default.jpg', NULL, '60 Rue de Montpellier', NULL, 'Montpellier', '34000', 'France', NULL, NULL),
(307, 'Lambert', 'Christine', 'client7@example.com', '0600000015', 'client7', 'client', 'default.jpg', NULL, '70 Rue de Rennes', NULL, 'Rennes', '35000', 'France', NULL, NULL),
(308, 'Mercier', 'Jean-Pierre', 'client8@example.com', '0600000016', 'client8', 'client', 'default.jpg', NULL, '80 Rue de Caen', NULL, 'Caen', '14000', 'France', NULL, NULL),
(309, 'Boucher', 'Sébastien', 'client9@example.com', '0600000017', 'client9', 'client', 'default.jpg', NULL, '90 Rue de Le Havre', NULL, 'Le Havre', '76600', 'France', NULL, NULL),
(310, 'Dupré', 'Valérie', 'client10@example.com', '0600000018', 'client10', 'client', 'default.jpg', NULL, '100 Rue de Nantes', NULL, 'Nantes', '44000', 'France', NULL, NULL),
(311, 'Fleury', 'Pascal', 'client11@example.com', '0600000019', 'client11', 'client', 'default.jpg', NULL, '110 Rue de Brest', NULL, 'Brest', '29200', 'France', NULL, NULL),
(312, 'Guérin', 'Cécile', 'client12@example.com', '0600000020', 'client12', 'client', 'default.jpg', NULL, '120 Rue de Quimper', NULL, 'Quimper', '29000', 'France', NULL, NULL),
(313, 'Huet', 'Laurent', 'client13@example.com', '0600000021', 'client13', 'client', 'default.jpg', NULL, '130 Rue de Vannes', NULL, 'Vannes', '56000', 'France', NULL, NULL),
(314, 'Joly', 'Sandrine', 'client14@example.com', '0600000022', 'client14', 'client', 'default.jpg', NULL, '140 Rue de Saint-Malo', NULL, 'Saint-Malo', '35400', 'France', NULL, NULL),
(315, 'Klein', 'Éric', 'client15@example.com', '0600000023', 'client15', 'client', 'default.jpg', NULL, '150 Rue de Bayonne', NULL, 'Bayonne', '64100', 'France', NULL, NULL),
(316, 'Lacroix', 'Caroline', 'client16@example.com', '0600000024', 'client16', 'default', 'client16.jpg', NULL, '160 Rue de Pau', NULL, 'Pau', '64000', 'France', NULL, NULL),
(317, 'Lefort', 'Olivier', 'client17@example.com', '0600000025', 'client17', 'default', 'client17.jpg', NULL, '170 Rue de Tarbes', NULL, 'Tarbes', '65000', 'France', NULL, NULL),
(318, 'Lemaitre', 'Sophie', 'client18@example.com', '0600000026', 'client18', 'default', 'client18.jpg', NULL, '180 Rue d Auch', NULL, 'Auch', '32000', 'France', NULL, NULL),
(319, 'Leroux', 'Gérard', 'client19@example.com', '0600000027', 'client19', 'default', 'client19.jpg', NULL, '190 Rue de Castres', NULL, 'Castres', '81100', 'France', NULL, NULL),
(320, 'Lévêque', 'Isabelle', 'client20@example.com', '0600000028', 'client20', 'default', 'client20.jpg', NULL, '200 Rue d Albi', NULL, 'Albi', '81000', 'France', NULL, NULL),

-- Admins
(401, 'Bourgeois', 'Pierre', 'admin1@example.com', '0600000029', 'admin1', 'admin', 'admin1.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(402, 'Clerc', 'Marie', 'admin2@example.com', '0600000030', 'admin2', 'admin', 'admin2.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(403, 'Dumont', 'Jean', 'admin3@example.com', '0600000031', 'admin3', 'admin', 'admin3.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(404, 'Ferrand', 'Sophie', 'admin4@example.com', '0600000032', 'admin4', 'admin', 'admin4.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(405, 'Gauthier', 'Laurent', 'admin5@example.com', '0600000033', 'admin5', 'admin', 'admin5.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);


-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE IF NOT EXISTS `appointments` (
  `aid` int(50) NOT NULL AUTO_INCREMENT,
  `client_id` int(50) NOT NULL,
  `agent_id` int(50) NOT NULL,
  `property_id` int(50) DEFAULT NULL,
  `rdv_date` date NOT NULL,
  `rdv_time` time NOT NULL,
  `rdv_place` varchar(255) DEFAULT 'Sur place: 12 Rue de Paris, 75001 Paris',
  `rdv_status` enum('confirmé','annulé','terminé') NOT NULL DEFAULT 'confirmé', -- delete after managing it
  `rdv_motivation` varchar(255) DEFAULT NULL,
  `rdv_comments` text DEFAULT NULL,
  `rdv_created_at` datetime NOT NULL DEFAULT current_timestamp(),

  `rdv_price` decimal(10,2) DEFAULT NULL,
  `is_paid` tinyint(1) DEFAULT 0,
  `rdv_payment_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`aid`),
  KEY `client_id` (`client_id`),
  KEY `agent_id` (`agent_id`),
  KEY `property_id` (`property_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Current appointments (future dates)
-- Thursday is apparently presentation day, so we'll set some appointments for future dates after that
INSERT INTO `appointments` (`client_id`, `agent_id`, `property_id`, `rdv_date`, `rdv_time`, `rdv_place`, `is_paid`, `rdv_price`, `rdv_comments`, `rdv_created_at`) VALUES
-- Client 101 future appointments
(101, 203, 501, '2025-03-11', '10:00:00', 'Sur place: 12 Avenue Montaigne, Paris', 0, 150.00, 'Premier rendez-vous pour visiter cet appartement haussmannien', CURRENT_TIMESTAMP()),
(101, 203, 503, '2025-03-14', '14:00:00', 'Sur place: 28 Rue du Commerce, Versailles', 0, 200.00, 'Visite du local commercial pour possible restaurant', CURRENT_TIMESTAMP()),

-- Client 102 future appointments
(102, 201, 502, '2025-03-14', '11:00:00', 'Sur place: 5 Rue des Entrepreneurs, Boulogne-Billancourt', 0, 150.00, 'Visite du loft industriel', CURRENT_TIMESTAMP()),
(102, 202, 504, '2025-03-17', '16:00:00', 'Sur place: Chemin des Vignes, Saint-Germain-en-Laye', 0, 150.00, 'Visite du terrain constructible', CURRENT_TIMESTAMP());

-- Past appointments
INSERT INTO `appointments` (`client_id`, `agent_id`, `property_id`, `rdv_date`, `rdv_time`, `rdv_place`, `is_paid`, `rdv_price`, `rdv_comments`, `rdv_created_at`) VALUES
-- Client 101 past appointments
(101, 201, 506, '2025-03-05', '09:00:00', 'Sur place: 17 Rue des Rosiers, Neuilly-sur-Seine', 1, 150.00, 'Le client a beaucoup aimé cette maison de ville', '2025-03-05 08:30:00'),
(101, 202, 509, '2025-03-01', '15:00:00', 'Sur place: Route de la Forêt, Évry', 1, 150.00, 'Le client s\'est montré intéressé par le terrain avec vue', '2025-03-01 14:30:00'),

-- Client 102 past appointments
(102, 201, 510, '2025-03-03', '13:00:00', 'Sur place: 3 Boulevard Haussmann, Paris', 1, 200.00, 'Le client a apprécié les prestations haut de gamme du duplex', '2025-03-03 12:30:00'),
(102, 202, 507, '2025-02-28', '17:00:00', 'Sur place: 8 Avenue des Champs-Élysées, Paris', 1, 200.00, 'Le client a trouvé l\'espace de travail très lumineux', '2025-02-28 16:30:00');


-- --------------------------------------------------------

--
-- Table structure for tables for the messaging system
--

CREATE TABLE IF NOT EXISTS `chat_rooms` (
    room_id INT AUTO_INCREMENT PRIMARY KEY,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `chat_participants` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_id INT,
    user_id INT
);

CREATE TABLE IF NOT EXISTS `chat_messages` (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    room_id INT,
    user_id INT,
    message TEXT,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- --------------------------------------------------------

--
-- Table structure for tables for the messaging system
--

CREATE TABLE IF NOT EXISTS `agent_schedules` (
  `schedule_id` int(50) NOT NULL AUTO_INCREMENT,
  `agent_id` int(50) NOT NULL,
  `day_of_week` varchar(10) NOT NULL, -- 0=Monday through 6=Sunday
  `workday_start` time DEFAULT '09:00:00',
  `workday_end` time DEFAULT '18:00:00',
  `is_working_day` tinyint(1) NOT NULL DEFAULT 1,
  `morning_slots` int(11) NOT NULL DEFAULT 3, -- Number of available morning slots
  `afternoon_slots` int(11) NOT NULL DEFAULT 5, -- Number of available afternoon slots
  PRIMARY KEY (`schedule_id`),
  UNIQUE KEY `agent_day` (`agent_id`, `day_of_week`),
  KEY `agent_id` (`agent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `agent_schedules` (`agent_id`, `day_of_week`, `workday_start`, `workday_end`, `is_working_day`, `morning_slots`, `afternoon_slots`) VALUES

-- Agent 201 (travaille 5 jours, mercredi après-midi)
(201, 'Monday', '09:00:00', '17:00:00', 1, 3, 5),
(201, 'Tuesday', '09:00:00', '17:00:00', 1, 3, 5),
(201, 'Wednesday', '13:00:00', '17:00:00', 1, 0, 5),
(201, 'Thursday', '09:00:00', '17:00:00', 1, 3, 5),
(201, 'Friday', '09:00:00', '17:00:00', 1, 3, 5),
(201, 'Saturday', '10:00:00', '14:00:00', 1, 2, 2),

-- Agent 202 (travaille 5 jours, mercredi matin)
(202, 'Monday', '09:00:00', '17:00:00', 1, 3, 5),
(202, 'Tuesday', '09:00:00', '17:00:00', 1, 3, 5),
(202, 'Wednesday', '09:00:00', '12:00:00', 1, 3, 0),
(202, 'Thursday', '09:00:00', '17:00:00', 1, 3, 5),
(202, 'Friday', '09:00:00', '17:00:00', 1, 3, 5),
(202, 'Saturday', '10:00:00', '14:00:00', 1, 2, 2),

-- Agent 203 (travaille 5 jours, tout le mercredi)
(203, 'Monday', '09:00:00', '17:00:00', 1, 3, 5),
(203, 'Tuesday', '09:00:00', '17:00:00', 1, 3, 5),
(203, 'Wednesday', '00:00:00', '00:00:00', 0, 0, 0),
(203, 'Thursday', '09:00:00', '17:00:00', 1, 3, 5),
(203, 'Friday', '09:00:00', '17:00:00', 1, 3, 5),
(203, 'Saturday', '10:00:00', '14:00:00', 1, 2, 2),

-- Agent 204 (travaille 4 jours, mercredi après-midi)
(204, 'Monday', '09:00:00', '17:00:00', 1, 3, 5),
(204, 'Tuesday', '09:00:00', '17:00:00', 1, 3, 5),
(204, 'Wednesday', '13:00:00', '17:00:00', 1, 0, 5),
(204, 'Thursday', '09:00:00', '17:00:00', 1, 3, 5),
(204, 'Friday', '09:00:00', '17:00:00', 1, 3, 5),
(204, 'Saturday', '10:00:00', '14:00:00', 0, 0, 0),

-- Agent 205 (travaille 5 jours, mercredi matin)
(205, 'Monday', '09:00:00', '17:00:00', 1, 3, 5),
(205, 'Tuesday', '09:00:00', '17:00:00', 1, 3, 5),
(205, 'Wednesday', '09:00:00', '12:00:00', 1, 3, 0),
(205, 'Thursday', '09:00:00', '17:00:00', 1, 3, 5),
(205, 'Friday', '09:00:00', '17:00:00', 1, 3, 5),
(205, 'Saturday', '10:00:00', '14:00:00', 1, 2, 2),

-- Agent 206 (travaille 4 jours, tout le mercredi)
(206, 'Monday', '09:00:00', '17:00:00', 1, 3, 5),
(206, 'Tuesday', '09:00:00', '17:00:00', 1, 3, 5),
(206, 'Wednesday', '00:00:00', '00:00:00', 0, 0, 0),
(206, 'Thursday', '09:00:00', '17:00:00', 1, 3, 5),
(206, 'Friday', '09:00:00', '17:00:00', 1, 3, 5),
(206, 'Saturday', '10:00:00', '14:00:00', 0, 0, 0),

-- Agent 207 (travaille 5 jours, mercredi après-midi)
(207, 'Monday', '09:00:00', '17:00:00', 1, 3, 5),
(207, 'Tuesday', '09:00:00', '17:00:00', 1, 3, 5),
(207, 'Wednesday', '13:00:00', '17:00:00', 1, 0, 5),
(207, 'Thursday', '09:00:00', '17:00:00', 1, 3, 5),
(207, 'Friday', '09:00:00', '17:00:00', 1, 3, 5),
(207, 'Saturday', '10:00:00', '14:00:00', 1, 2, 2),

-- Agent 208 (travaille 5 jours, mercredi matin)
(208, 'Monday', '09:00:00', '17:00:00', 1, 3, 5),
(208, 'Tuesday', '09:00:00', '17:00:00', 1, 3, 5),
(208, 'Wednesday', '09:00:00', '12:00:00', 1, 3, 0),
(208, 'Thursday', '09:00:00', '17:00:00', 1, 3, 5),
(208, 'Friday', '09:00:00', '17:00:00', 1, 3, 5),
(208, 'Saturday', '10:00:00', '14:00:00', 1, 2, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `property`
--
ALTER TABLE `property`
  ADD PRIMARY KEY (`pid`),
  ADD KEY `fk_property_user` (`agentid`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `uemail` (`uemail`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `property`
--
ALTER TABLE `property`
  MODIFY `pid` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=505;


--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `uid` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;


--
-- Contraintes pour les tables déchargées
--

ALTER TABLE `chat_participants`
    ADD CONSTRAINT `fk_participant_rooms` FOREIGN KEY (room_id) REFERENCES chat_rooms(room_id) ON DELETE CASCADE,
    ADD CONSTRAINT `fk_chat_user` FOREIGN KEY (user_id) REFERENCES user(uid) ON DELETE CASCADE;

ALTER TABLE `chat_messages`
    ADD CONSTRAINT `fk_messages_rooms` FOREIGN KEY (room_id) REFERENCES chat_rooms(room_id) ON DELETE CASCADE,
    ADD CONSTRAINT `fk_messages_user` FOREIGN KEY (user_id) REFERENCES user(uid) ON DELETE CASCADE;