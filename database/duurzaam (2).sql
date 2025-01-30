-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 30 jan 2025 om 09:09
-- Serverversie: 10.4.32-MariaDB
-- PHP-versie: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `duurzaam`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `artikel`
--

CREATE TABLE `artikel` (
  `id` int(11) NOT NULL,
  `categorie_id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `merk` varchar(255) NOT NULL,
  `kleur` varchar(100) NOT NULL,
  `afmeting` varchar(100) NOT NULL,
  `aantal` int(11) NOT NULL,
  `ean_nummer` varchar(13) NOT NULL,
  `prijs_ex_btw` decimal(10,2) NOT NULL,
  `prijs` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `artikel`
--

INSERT INTO `artikel` (`id`, `categorie_id`, `naam`, `merk`, `kleur`, `afmeting`, `aantal`, `ean_nummer`, `prijs_ex_btw`, `prijs`) VALUES
(1, 1, 'Tafel', '', '', '', 0, '', 0.00, 0.00),
(2, 2, 'omer', 'merk', 'blauw', '12', 2, '12312313212', 12.00, 0.00),
(7, 7, 'dq', '12', '', '', 0, '2063879154', 23.00, 0.00),
(8, 6, 'Omer', '23', '', '', 0, '12323', 12.00, 0.00),
(9, 6, 'Omer', '23', '', '', 0, '6517894320', 234.00, 0.00),
(10, 6, 'dq', 'merk', '', '', 0, '131213', 0.00, 123.00);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `categorie`
--

CREATE TABLE `categorie` (
  `id` int(11) NOT NULL,
  `categorie` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `categorie`
--

INSERT INTO `categorie` (`id`, `categorie`) VALUES
(1, 'ded'),
(2, 'voorbeeld'),
(3, 'ded'),
(4, 'Kleding'),
(5, 'Meubels'),
(6, 'Bedden'),
(7, 'Kledingkasten'),
(8, 'Spiegels'),
(9, 'Kapstokken'),
(10, 'Garderobekasten'),
(11, 'Schoenenkasten'),
(12, 'Witgoed'),
(13, 'Bruingoed'),
(14, 'Grijsgoed'),
(15, 'Glazen, Borden en Bestek'),
(16, 'Boeken'),
(17, 'ew');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `gebruiker`
--

CREATE TABLE `gebruiker` (
  `id` int(11) NOT NULL,
  `gebruikersnaam` varchar(255) NOT NULL,
  `wachtwoord` varchar(255) NOT NULL,
  `rollen` text NOT NULL,
  `is_geverifieerd` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `gebruiker`
--

INSERT INTO `gebruiker` (`id`, `gebruikersnaam`, `wachtwoord`, `rollen`, `is_geverifieerd`) VALUES
(1, 'admin', '$2y$10$vUZqJkfM3YJFeIEiIUTSp.VRhZO6ktVl9/wFR.avp0oUyQMrLSSAW', 'directie', 1),
(2, 'omer', '$2y$10$h.gtdMTFNTECEZkM.1O12Ofs73X3ND0mlmk2eIXcPUez64QOFT2B6', 'directie', 1),
(38, 'directie1', '$2y$10$njCsUSahaD6sd3pgcFepneRtkR44aZmq3ctvTlJ6sDp8Cf0T6hMBm', 'directie', 1),
(39, 'directie2', '$2y$10$YOCb4DnD5btCUuZcwrqI7.3PwwTlaKnzqOH28ykPN1weneyqVoD3m', 'directie', 1),
(40, 'directie3', '$2y$10$MPt7sFUlbMWrG1SOviGGAuGJDmbqabX/NWvyQqkcEJ0qkW/jcahOG', 'directie', 1),
(41, 'magazijn1', '$2y$10$GMJTAoUDZzmTKq5kz/d29upEJa37CMXWx2zDdTpugC2IFJy.SM4sq', 'magazijnmedewerker', 1),
(42, 'magazijn2', '$2y$10$m08uEvDRgG6O.oxXtfGiSeGhobmuOIqIr0K0zAmc6h.6sCRdgAE06', 'magazijnmedewerker', 1),
(43, 'magazijn3', '$2y$10$QO/NGH67cpgbhIr.vNSQfusIUShNAMpfSrrYKWqn.O8H7f4wLKNgW', 'magazijnmedewerker', 1),
(44, 'magazijn4', '$2y$10$zfUw3B/86mc4/guXCRnqrOtJ6z4CXo70LRsxCuX82lZ3BCaP4r1ha', 'magazijnmedewerker', 1),
(45, 'magazijn5', '$2y$10$k.bTRK07sQUIEizFcDTNB.B2rTXyPPZYjA1KR4SN/qdzizf0Q5gCC', 'magazijnmedewerker', 1),
(46, 'winkel1', '$2y$10$O7usJZMMknBqnqZqrAoKtueOKV9ur5pQ0Nq2ROA5zXlF0SwIuSlf2', 'winkelpersoneel', 1),
(47, 'winkel2', '$2y$10$RFLFZLeMtLdeKreNHaktPe19lisXt8OG2n5XLUUsOrwllL1g4XbVm', 'winkelpersoneel', 1),
(48, 'winkel3', '$2y$10$dwT/uqwxIjUJMsXXZlfy8OdcIILKrXh424B4OYZU6Wpnan2zZ6A4G', 'winkelpersoneel', 1),
(49, 'chauffeur1', '$2y$10$jF6rbMb0S19IBY2BBvreIOiFEZP.MNVPDF8AhDbiJLhSgcRn4nk8C', 'chauffeur', 1),
(50, 'chauffeur2', '$2y$10$0MimDuSD32QIfsjKDkmI7.V.sHqjWLSrcwQm3FK0Tn6VMXHXebk1i', 'chauffeur', 1),
(51, 'chauffeur3', '$2y$10$mr9kh9DHaNrkTEsuxl34Z.h8HpcNgsb1FiqCyKRRifMtO/vd4ERNW', 'chauffeur', 1),
(52, 'chauffeur4', '$2y$10$EMi1CtCrDykaPU1KytMjqO3UnWivgEbbcH4SsPv3LiddOYLmpdvV2', 'chauffeur', 1),
(53, 'chauffeur5', '$2y$10$Nv8d5xPzcECBZxqbg7xhv.TdUxIQ4VnV4i56UicNoaY1rwTOcRUeW', 'chauffeur', 1),
(54, 'chauffeur6', '$2y$10$orLM4flCfdm2zbYwTxA2VeJj3jOnARIaST3eTimvYjWzktXuYTBb6', 'chauffeur', 1),
(55, 'w', '$2y$10$/oSs7ioT.U94Ft6xCTIcXOz.sfAwKHxPCIrexSPt2DIzw28oXvAri', 'magazijnmedewerker', 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `klant`
--

CREATE TABLE `klant` (
  `id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `adres` varchar(255) NOT NULL,
  `plaats` varchar(255) NOT NULL,
  `telefoon` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `klant`
--

INSERT INTO `klant` (`id`, `naam`, `adres`, `plaats`, `telefoon`, `email`) VALUES
(2, 'Omer', 'Rietzoom', 'gouda', '063422', 'ilikcay1@gmail.com');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `planning`
--

CREATE TABLE `planning` (
  `id` int(11) NOT NULL,
  `artikel_id` int(11) NOT NULL,
  `klant_id` int(11) NOT NULL,
  `kenteken` varchar(255) NOT NULL,
  `ophalen_of_bezorgen` enum('ophalen','bezorgen') NOT NULL,
  `afspraak_op` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `planning`
--

INSERT INTO `planning` (`id`, `artikel_id`, `klant_id`, `kenteken`, `ophalen_of_bezorgen`, `afspraak_op`) VALUES
(5, 1, 2, 'e', 'bezorgen', '2025-01-29 17:34:00'),
(6, 1, 2, 'e', 'bezorgen', '2025-01-29 17:34:00'),
(7, 2, 2, 'q', 'ophalen', '2025-01-29 15:47:00');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `status`
--

CREATE TABLE `status` (
  `id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `status`
--

INSERT INTO `status` (`id`, `status`) VALUES
(1, 'Op voorraad'),
(2, 'Gereserveerd'),
(3, 'Niet beschikbaar'),
(4, 'Beschikbaar'),
(5, 'Niet beschikbaar'),
(6, 'Ter reparatie'),
(7, 'Verkocht');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `verkopen`
--

CREATE TABLE `verkopen` (
  `id` int(11) NOT NULL,
  `klant_id` int(11) NOT NULL,
  `artikel_id` int(11) NOT NULL,
  `verkocht_op` datetime NOT NULL,
  `prijs` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `voorraad`
--

CREATE TABLE `voorraad` (
  `id` int(11) NOT NULL,
  `artikel_id` int(11) NOT NULL,
  `locatie` varchar(255) NOT NULL,
  `aantal` int(11) NOT NULL,
  `reparatie_nodig` tinyint(1) NOT NULL DEFAULT 0,
  `verkoop_gereed` tinyint(1) NOT NULL DEFAULT 0,
  `status_id` int(11) NOT NULL,
  `ingeboekt_op` datetime NOT NULL,
  `te_koop` tinyint(1) NOT NULL DEFAULT 1,
  `prijs` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `voorraad`
--

INSERT INTO `voorraad` (`id`, `artikel_id`, `locatie`, `aantal`, `reparatie_nodig`, `verkoop_gereed`, `status_id`, `ingeboekt_op`, `te_koop`, `prijs`) VALUES
(2, 1, 'hier', 1, 0, 1, 1, '2025-01-30 00:41:13', 1, 12.00),
(3, 1, 'hier', 2, 0, 0, 1, '2025-01-30 01:09:30', 1, 1312.00),
(4, 1, 'hier', 2, 0, 0, 1, '2025-01-30 01:09:52', 1, 12.00),
(13, 7, 'hier', 2, 0, 0, 1, '2025-01-30 02:46:40', 1, 123.00),
(14, 1, 'hier', 12, 0, 0, 1, '2025-01-30 02:49:34', 1, 12.00),
(15, 7, 'hier', 2, 0, 0, 1, '2025-01-30 09:09:09', 1, 23.00);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `artikel`
--
ALTER TABLE `artikel`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ean_nummer` (`ean_nummer`),
  ADD KEY `categorie_id` (`categorie_id`);

--
-- Indexen voor tabel `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `gebruiker`
--
ALTER TABLE `gebruiker`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `klant`
--
ALTER TABLE `klant`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `planning`
--
ALTER TABLE `planning`
  ADD PRIMARY KEY (`id`),
  ADD KEY `artikel_id_klant_id` (`artikel_id`,`klant_id`),
  ADD KEY `klant_id` (`klant_id`);

--
-- Indexen voor tabel `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `verkopen`
--
ALTER TABLE `verkopen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `klant_id_artikel_id` (`klant_id`,`artikel_id`),
  ADD KEY `artikel_id` (`artikel_id`);

--
-- Indexen voor tabel `voorraad`
--
ALTER TABLE `voorraad`
  ADD PRIMARY KEY (`id`),
  ADD KEY `artikel_id_status_id` (`artikel_id`,`status_id`),
  ADD KEY `status_id` (`status_id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `artikel`
--
ALTER TABLE `artikel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT voor een tabel `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT voor een tabel `gebruiker`
--
ALTER TABLE `gebruiker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT voor een tabel `klant`
--
ALTER TABLE `klant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT voor een tabel `planning`
--
ALTER TABLE `planning`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT voor een tabel `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT voor een tabel `verkopen`
--
ALTER TABLE `verkopen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `voorraad`
--
ALTER TABLE `voorraad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `artikel`
--
ALTER TABLE `artikel`
  ADD CONSTRAINT `artikel_ibfk_1` FOREIGN KEY (`categorie_id`) REFERENCES `categorie` (`id`);

--
-- Beperkingen voor tabel `planning`
--
ALTER TABLE `planning`
  ADD CONSTRAINT `planning_ibfk_1` FOREIGN KEY (`artikel_id`) REFERENCES `artikel` (`id`),
  ADD CONSTRAINT `planning_ibfk_2` FOREIGN KEY (`klant_id`) REFERENCES `klant` (`id`);

--
-- Beperkingen voor tabel `verkopen`
--
ALTER TABLE `verkopen`
  ADD CONSTRAINT `verkopen_ibfk_1` FOREIGN KEY (`klant_id`) REFERENCES `klant` (`id`),
  ADD CONSTRAINT `verkopen_ibfk_2` FOREIGN KEY (`artikel_id`) REFERENCES `artikel` (`id`);

--
-- Beperkingen voor tabel `voorraad`
--
ALTER TABLE `voorraad`
  ADD CONSTRAINT `voorraad_ibfk_1` FOREIGN KEY (`artikel_id`) REFERENCES `artikel` (`id`),
  ADD CONSTRAINT `voorraad_ibfk_2` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
