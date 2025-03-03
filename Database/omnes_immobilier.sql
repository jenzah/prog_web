-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `omnes_immobilier`
--
CREATE DATABASE IF NOT EXISTS `omnes_immobilier` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `omnes_immobilier`;

-- --------------------------------------------------------

--
-- Table structure for table `about`
-- 01.03.2025 Jennifer: J'ai supprimé l'utilisation de la table, donc on l'a plus besoin
--

DROP TABLE IF EXISTS `about`;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `aid` int(10) NOT NULL,
  `auser` varchar(50) NOT NULL,
  `aemail` varchar(50) NOT NULL,
  `apass` varchar(50) NOT NULL,
  `adob` date NOT NULL,
  `aphone` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`aid`, `auser`, `aemail`, `apass`, `adob`, `aphone`) VALUES
(2, 'disha', 'disha@gmail.com', 'disha', '1999-02-02', '9689689698'),
(6, 'final', 'final@gmail.com', 'final', '2020-04-29', '7979656578'),
(7, 'test', 'test@gmail.com', 'test', '2020-04-29', '8997979765'),
(8, 'check', 'check@gmail.com', 'check', '2020-04-29', '8979785688'),
(9, 'admin', 'admin@gmail.com', 'admin', '1999-12-06', '9878786545');

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

DROP TABLE IF EXISTS `city`;
CREATE TABLE `city` (
  `cid` int(50) NOT NULL,
  `cname` varchar(100) NOT NULL,
  `sid` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `city`
--

INSERT INTO `city` (`cid`, `cname`, `sid`) VALUES
(9, 'navi mumbai', 3),
(10, 'vapi', 2),
(11, 'valsad', 2);

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

DROP TABLE IF EXISTS `contact`;
CREATE TABLE `contact` (
  `cid` int(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `message` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`cid`, `name`, `email`, `phone`, `subject`, `message`) VALUES
(2, 'demo', 'demo@gmail.com', '9765989689', 'demo', 'demo'),
(4, 'test', 'test@gmail.com', '7976976979', 'test', 'test'),
(5, 'final', 'final@gmail.com', '7697967967', 'final', 'final'),
(6, 'disha', 'disha@gmail.com', '7898797696', 'demo', 'demo');

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
  `status` varchar(50) NOT NULL, -- à vendre, à louer, vendu, loué
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `property`
--

INSERT INTO `property` (`pid`, `agentid`, `title`, `propertyDescription`, `propertyType`, `area`, `nbRooms`, `nbBathrooms`, `price`, `location`, `city`, `department`, `pimage1`, `pimage2`, `pimage3`, `status`, `date`) VALUES
(1, 1, 'Appartement Haussmannien', 'Lumineux avec moulures anciennes', 'résidentiel', 85, 3, 1, 780000, '12 Avenue Montaigne', 'Paris', 'Paris', '1.jpg', '2.jpg', '3.jpg', 'À vendre', '2025-02-28 10:30:00'),
(2, 1, 'Loft industriel', 'Espace ouvert plein charme', 'résidentiel', 120, 2, 2, 950000, '5 Rue des Entrepreneurs', 'Boulogne-Billancourt', 'Hauts-de-Seine', '4.jpg', '5.jpg', '6.jpg', 'À vendre', '2025-02-27 15:45:00'),
(3, 2, 'Local Commercial', 'Idéal pour restaurant chic', 'commercial', 150, 3, 2, 1200000, '28 Rue du Commerce', 'Versailles', 'Yvelines', '7.jpg', '8.jpg', '9.jpg', 'À vendre', '2025-03-01 09:15:00'),
(4, 2, 'Terrain constructible', 'Vue dégagée zone résidentielle', 'terrain', 500, 0, 0, 320000, 'Chemin des Vignes', 'Saint-Germain-en-Laye', 'Yvelines', '10.jpg', '11.jpg', '12.jpg', 'À vendre', '2025-02-25 11:20:00'),
(5, 3, 'Studio moderne', 'Proche transports et commerces', 'appartement à louer', 35, 1, 1, 950, '45 Rue Mouffetard', 'Paris', 'Paris', '13.jpg', '14.jpg', '15.jpg', 'À louer', '2025-02-26 14:00:00'),
(6, 3, 'Maison de ville', 'Jardin privatif bien exposé', 'résidentiel', 145, 5, 2, 890000, '17 Rue des Rosiers', 'Neuilly-sur-Seine', 'Hauts-de-Seine', '16.jpg', '17.jpg', '18.jpg', 'vendu', '2025-02-22 16:30:00'),
(7, 4, 'Bureaux modernes', 'Espace de travail lumineux', 'commercial', 200, 5, 2, 1500000, '8 Avenue des Champs-Élysées', 'Paris', 'Paris', '19.jpg', '20.jpg', '21.jpg', 'À vendre', '2025-03-02 10:00:00'),
(8, 4, 'Appartement familial', 'Proche écoles et parcs', 'résidentiel', 110, 4, 2, 720000, '25 Rue des Écoles', 'Créteil', 'Val-de-Marne', '22.jpg', '23.jpg', '24.jpg', 'À louer', '2025-02-24 13:45:00'),
(9, 5, 'Terrain avec vue', 'Constructible zone calme', 'terrain', 800, 0, 0, 450000, 'Route de la Forêt', 'Évry', 'Essonne', '25.jpg', '26.jpg', '27.jpg', 'À vendre', '2025-02-23 11:15:00'),
(10, 5, 'Duplex contemporain', 'Prestations haut de gamme', 'résidentiel', 130, 4, 3, 1050000, '3 Boulevard Haussmann', 'Paris', 'Paris', '28.jpg', '29.jpg', '30.jpg', 'loué', '2025-02-21 09:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `state`
--

DROP TABLE IF EXISTS `state`;
CREATE TABLE `state` (
  `sid` int(50) NOT NULL,
  `sname` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `state`
--

INSERT INTO `state` (`sid`, `sname`) VALUES
(2, 'gujarat'),
(3, 'goa'),
(4, 'maharashtra'),
(7, 'bihar'),
(9, 'chhattisgarh'),
(10, 'uttar pardesh'),
(15, 'rajasthan');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `uid` int(50) NOT NULL,
  `uname` varchar(100) NOT NULL,
  `uemail` varchar(100) NOT NULL,
  `uphone` varchar(20) NOT NULL,
  `upass` varchar(50) NOT NULL,
  `utype` varchar(50) NOT NULL,
  `uimage` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`uid`, `uname`, `uemail`, `uphone`, `upass`, `utype`, `uimage`) VALUES
(1, 'admin', 'admin@gmail.com', '9876543210', 'admin', 'user', '3.jpg'),
(2, 'aryan', 'aryan@gmail.com', '9878678678', 'aryan', 'agent', '2.jpg'),
(3, 'demo', 'demo@gmail.com', '7976976979', 'demo', 'user', '1.jpg'),
(4, 'disha', 'disha@gmail.com', '7976956956', 'disha', 'agent', '2.jpg'),
(5, 'disha', 'disha1@gmail.com', '8997897869', 'disha', 'agent', '1.jpg'),
(6, 'testing', 'testing@gmail.com', '9869596597', 'testing', 'builder', '1.jpg'),
(7, 'some', 'some@gmail.com', '9689698697', 'some', 'builder', '3.jpg'),
(8, 'test', 'test12@gmail.com', '9798678969', 'test', 'builder', 'avatar-3.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`aid`);

--
-- Indexes for table `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `property`
--
ALTER TABLE `property`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `state`
--
ALTER TABLE `state`
  ADD PRIMARY KEY (`sid`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`uid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `aid` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
  MODIFY `cid` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `cid` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `property`
--
ALTER TABLE `property`
  MODIFY `pid` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `state`
--
ALTER TABLE `state`
  MODIFY `sid` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `uid` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;
