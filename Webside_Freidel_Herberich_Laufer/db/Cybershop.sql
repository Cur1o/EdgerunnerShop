-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 20. Feb 2023 um 09:41
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
  `itemtype` enum('weapon','amor','item') NOT NULL,
  `itemValues` int(8) NOT NULL,
  `isConsumeable` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `productId` (`productId`)
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `products`
--

INSERT INTO `products` (`id`, `productId`, `name`, `description`, `image`, `price`, `itemtype`, `itemValues`, `isConsumeable`) VALUES
(91, 'pistol', 'Pistole', 'Piw Piw', 'ProductImages/Pistol.png', 500, 'weapon', 100, 0),
(92, 'gun', 'Gewehr', 'macht Loecher in Leute die weiter weg stehen', 'ProductImages/Gun.png', 1000, 'weapon', 250, 0),
(93, 'shotgun', 'Shotgun', 'macht viele Loecher in Leute', 'ProductImages/Shotgun.png', 1000, 'weapon', 500, 0),
(94, 'sniper', 'Sniper', 'macht ein fettes Loch', 'ProductImages/Sniper.png', 3000, 'weapon', 1000, 0),
(95, 'knife', 'Messer', 'wenns mal direkter wird', 'ProductImages/Knife.png', 500, 'weapon', 100, 0),
(96, 'head', 'Helm', 'schuetzt die Frisur auch im Kugelhagel', 'ProductImages/Head.png', 500, 'amor', 300, 0),
(97, 'body', 'Ruestung', 'nuetzlich um Loecher im Koerper zu verhindern', 'ProductImages/Body.png', 1000, 'amor', 500, 0),
(98, 'legs', 'Hose', 'nur echt mit drei Loechern drin', 'ProductImages/Legs.png', 600, 'amor', 400, 0),
(99, 'hand', 'Handschuhe', 'hier wohnen die Finger', 'ProductImages/Hand.png', 300, 'amor', 200, 0),
(100, 'health', 'Medipack', 'zum zusammenflicken der vielen Loecher', 'ProductImages/Health.png', 200, 'item', 5, 1),
(101, 'speed', 'Stimpack', 'wenns mal wieder laenger dauert', 'ProductImages/Speed.png', 200, 'item', 1, 1),
(102, 'shield', 'Schildblase', 'falls du Lust hast auf die eigene Bubble', 'ProductImages/Shield.png', 500, 'item', 5, 1),
(103, 'stealth', 'Ninja', '... und weg war er', 'ProductImages/Stealth.png', 500, 'item', 5, 0),
(104, 'rage', 'Wut', 'Wut als Snack', 'ProductImages/Rage.png', 150, 'item', 1, 1),
(105, 'boots', 'schuhe', 'damit kannst du besser laufen', 'ProductImages/Boots.png', 500, 'amor', 100, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `nick` varchar(32) NOT NULL,
  `email` varchar(64) NOT NULL,
  `userpassword` varchar(512) NOT NULL,
  `access` varchar(8) NOT NULL DEFAULT 'user',
  `lastchange` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `EdgeCoins` int(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nick` (`nick`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `nick`, `email`, `userpassword`, `access`, `lastchange`, `EdgeCoins`) VALUES
(0, 'admin', 'admin@shop.de', '$2y$10$M9ut8JoSGAB50T.WmQOhy.qqJzSpPJ2/lR.3Mx3F8Eb.PqGpPRa4G', 'admin', '2023-02-19 16:01:18', 34000),
(1, 'waffen', 'waffen@shop.de', '$2y$10$prdwUeN4t3HOM4wsbGM2keFzEbocgVgG3DUXcOV7nbBlKw9BPIc7y', 'user', '2023-02-15 17:51:39', 10000),
(2, 'kleidung', 'kleidung@shop.de', '$2y$10$IW6mHwni5hIL7fSVz0VAtO/l1BhdervO8xBIt63XFqupqjnVJ1H3G', 'user', '2023-02-15 17:52:16', 10000),
(3, 'produkte', 'produkte@shop.de', '$2y$10$UmhPn0FX0syFOzxdR4ctMOquRQvIkRBmtLCz4j.2Oj06nQsPNI9ha', 'user', '2023-02-15 17:52:24', 10000),
(7, 'Cur1o', 'cur1o@gmail.com', '$2y$10$kqpiJV.FnaZqPT.ifiBVvOfZiSTP2yrhnauxsGIpSGnUJDtGjONX6', 'user', '2023-02-20 08:33:48', 30675),
(19, 'NewUser1', 'new@shop.de', '$2y$10$7rbr0DddBDmD5X.WM7mbm.PNh9ZE8aEQiHZe8HGr9Ui1U5U1TDjKq', 'user', '2023-02-19 15:53:42', 14000),
(20, 'test', 'test@test.de', '$2y$10$gpoKr7e7l/t6rGA.epBx6uKnOrNwIx986jR7jlYObDvaG04AA0WBy', 'user', '2023-02-19 14:47:07', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_resources`
--

CREATE TABLE IF NOT EXISTS `user_resources` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `productId` int(8) NOT NULL,
  `userId` int(8) NOT NULL,
  `count` int(8) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`),
  KEY `productId` (`productId`),
  KEY `productId_2` (`productId`)
) ENGINE=InnoDB AUTO_INCREMENT=922 DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `user_resources`
--

INSERT INTO `user_resources` (`id`, `productId`, `userId`, `count`) VALUES
(672, 91, 19, 1),
(673, 92, 19, 1),
(674, 93, 19, 1),
(675, 94, 19, 1),
(676, 95, 19, 1),
(901, 91, 1, 1),
(902, 92, 1, 1),
(903, 93, 7, 1),
(904, 94, 7, 1),
(905, 95, 1, 1),
(908, 96, 2, 1),
(909, 97, 2, 1),
(910, 98, 2, 1),
(911, 99, 2, 1),
(912, 105, 2, 1),
(915, 100, 3, 1),
(916, 101, 3, 1),
(917, 102, 3, 1),
(918, 103, 3, 1),
(919, 104, 3, 1);

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
