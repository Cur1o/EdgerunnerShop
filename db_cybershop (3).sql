-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 16. Feb 2023 um 10:33
-- Server-Version: 10.4.25-MariaDB
-- PHP-Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `db_cybershop`
--
CREATE DATABASE IF NOT EXISTS `db_cybershop` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `db_cybershop`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `productId` varchar(12) NOT NULL,
  `name` varchar(16) DEFAULT NULL,
  `description` varchar(64) NOT NULL,
  `image` varchar(128) NOT NULL DEFAULT 'ProductImages/noImage.png',
  `price` int(8) NOT NULL,
  `isConsumeable` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `productId` (`productId`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `products`
--

INSERT INTO `products` (`id`, `productId`, `name`, `description`, `image`, `price`, `isConsumeable`) VALUES
(91, 'pistol', 'Pistole', 'Piw Piw', 'ProductImages/Pistol.png', 500, 0),
(92, 'gun', 'Gewehr', 'macht Loecher in Leute die weiter weg stehen', 'ProductImages/Gun.png', 1000, 0),
(93, 'shotgun', 'Shotgun', 'macht viele Loecher in Leute', 'ProductImages/Shotgun.png', 1000, 0),
(94, 'sniper', 'Sniper', 'macht ein fettes Loch', 'ProductImages/Sniper.png', 3000, 0),
(95, 'knife', 'Messer', 'wenns mal direkter wird', 'ProductImages/Knife.png', 500, 0),
(96, 'head', 'Helm', 'schuetzt die Frisur auch im Kugelhagel', 'ProductImages/Head.png', 500, 0),
(97, 'body', 'Ruestung', 'nützlich um Loecher im Koerper zu verhindern', 'ProductImages/Body.png', 1000, 0),
(98, 'legs', 'Hose', 'nur echt mit drei Loechern drin', 'ProductImages/Legs.png', 600, 0),
(99, 'hand', 'Handschuhe', 'hier wohnen die Finger', 'ProductImages/Hand.png', 300, 0),
(100, 'health', 'Medipack', 'zum zusammenflicken der vielen Loecher', 'ProductImages/Health.png', 200, 1),
(101, 'speed', 'Stimpack', 'wenns mal wieder länger dauert', 'ProductImages/Speed.png', 200, 1),
(102, 'shield', 'Schildblase', 'falls du Lust hast auf die eigene Bubble', 'ProductImages/Shield.png', 500, 1),
(103, 'stealth', 'Tarnumhang', '... und weg war er', 'ProductImages/Stealth.png', 500, 0),
(104, 'rage', 'Rage', 'Wut als Snack', 'ProductImages/Rage.png', 150, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `nick` varchar(32) NOT NULL,
  `email` varchar(64) NOT NULL,
  `userpassword` varchar(512) NOT NULL,
  `image` varchar(64) NOT NULL DEFAULT 'UserImages/noimage.png',
  `access` varchar(8) NOT NULL DEFAULT 'user',
  `lastchange` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `EdgeCoins` int(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nick` (`nick`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `nick`, `email`, `userpassword`, `image`, `access`, `lastchange`, `EdgeCoins`) VALUES
(0, 'admin', 'admin@shop.de', '$2y$10$M9ut8JoSGAB50T.WmQOhy.qqJzSpPJ2/lR.3Mx3F8Eb.PqGpPRa4G', 'UserImages/noimage.png', 'admin', '2023-02-15 16:10:53', 30000),
(1, 'waffen', 'waffen@shop.de', '$2y$10$prdwUeN4t3HOM4wsbGM2keFzEbocgVgG3DUXcOV7nbBlKw9BPIc7y', 'UserImages/noimage.png', 'user', '2023-02-15 17:51:39', 10000),
(2, 'kleidung', 'kleidung@shop.de', '$2y$10$IW6mHwni5hIL7fSVz0VAtO/l1BhdervO8xBIt63XFqupqjnVJ1H3G', 'UserImages/noimage.png', 'user', '2023-02-15 17:52:16', 10000),
(3, 'produkte', 'produkte@shop.de', '$2y$10$UmhPn0FX0syFOzxdR4ctMOquRQvIkRBmtLCz4j.2Oj06nQsPNI9ha', 'UserImages/noimage.png', 'user', '2023-02-15 17:52:24', 10000),
(7, 'Cur1o', 'cur1o@gmail.com', '$2y$10$kqpiJV.FnaZqPT.ifiBVvOfZiSTP2yrhnauxsGIpSGnUJDtGjONX6', 'UserImages/Cur1o_userImage.png', 'user', '2023-02-15 16:16:29', 590000);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_resources`
--

CREATE TABLE IF NOT EXISTS `user_resources` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `productId` int(8) NOT NULL,
  `userId` int(8) NOT NULL,
  `count` int(8) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`),
  KEY `productId` (`productId`),
  KEY `productId_2` (`productId`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `user_resources`
--

INSERT INTO `user_resources` (`id`, `productId`, `userId`, `count`) VALUES
(20, 92, 7, 1),
(24, 95, 7, 1),
(25, 94, 7, 1),
(26, 104, 1, 1),
(27, 100, 1, 1),
(28, 95, 1, 1),
(29, 91, 7, 1),
(30, 95, 1, 1),
(31, 91, 1, 1);

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `user_resources`
--
ALTER TABLE `user_resources`
  ADD CONSTRAINT `user_resources_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `user_resources_ibfk_2` FOREIGN KEY (`productId`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
