-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 15. Jul 2017 um 00:45
-- Server-Version: 10.1.21-MariaDB
-- PHP-Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `prospectingmanagerdb`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `grids`
--

CREATE TABLE `grids` (
  `planet` int(11) NOT NULL,
  `xcoord` int(11) NOT NULL,
  `ycoord` int(11) NOT NULL,
  `terraintype` int(11) NOT NULL,
  `deposittype` int(11) NOT NULL,
  `depositsize` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `planets`
--

CREATE TABLE `planets` (
  `id` int(11) NOT NULL,
  `name` varchar(200) COLLATE utf8_bin NOT NULL,
  `size` int(11) NOT NULL,
  `owner` int(11) UNSIGNED NOT NULL,
  `shareall` tinyint(1) NOT NULL,
  `editall` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `planets2users`
--

CREATE TABLE `planets2users` (
  `planet` int(11) NOT NULL,
  `user` int(10) UNSIGNED NOT NULL,
  `edit` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `grids`
--
ALTER TABLE `grids`
  ADD PRIMARY KEY (`planet`,`xcoord`,`ycoord`);

--
-- Indizes für die Tabelle `planets`
--
ALTER TABLE `planets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `planets_owner_fk` (`owner`);

--
-- Indizes für die Tabelle `planets2users`
--
ALTER TABLE `planets2users`
  ADD PRIMARY KEY (`planet`,`user`),
  ADD KEY `planets2users_user_fk` (`user`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `username_3` (`username`),
  ADD KEY `username_2` (`username`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `planets`
--
ALTER TABLE `planets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `grids`
--
ALTER TABLE `grids`
  ADD CONSTRAINT `grids_planet_fk` FOREIGN KEY (`planet`) REFERENCES `planets` (`id`);

--
-- Constraints der Tabelle `planets`
--
ALTER TABLE `planets`
  ADD CONSTRAINT `planets_owner_fk` FOREIGN KEY (`owner`) REFERENCES `users` (`id`);

--
-- Constraints der Tabelle `planets2users`
--
ALTER TABLE `planets2users`
  ADD CONSTRAINT `planets2users_planet_fk` FOREIGN KEY (`planet`) REFERENCES `planets` (`id`),
  ADD CONSTRAINT `planets2users_user_fk` FOREIGN KEY (`user`) REFERENCES `users` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
