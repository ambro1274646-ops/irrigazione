-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql300.infinityfree.com
-- Creato il: Set 23, 2026 alle 09:26
-- Versione del server: 11.4.13-MariaDB
-- Versione PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_42967232_irrigazione_db_impianto_generico`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `dati_aria`
--

CREATE TABLE `dati_aria` (
  `data_ora` datetime NOT NULL,
  `valore_umidita_aria` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `dati_aria`
--

INSERT INTO `dati_aria` (`data_ora`, `valore_umidita_aria`) VALUES
('2026-09-18 15:00:27', 55.2),
('2026-09-19 15:00:27', 58.9),
('2026-09-20 15:00:27', 61.4),
('2026-09-21 15:00:27', 59);

-- --------------------------------------------------------

--
-- Struttura della tabella `dati_pioggia`
--

CREATE TABLE `dati_pioggia` (
  `data_ora` datetime NOT NULL,
  `mm_pioggia` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `dati_pioggia`
--

INSERT INTO `dati_pioggia` (`data_ora`, `mm_pioggia`) VALUES
('2026-09-16 15:00:50', 0),
('2026-09-17 15:00:50', 3.2),
('2026-09-19 15:00:50', 0),
('2026-09-20 15:00:50', 6.5);

-- --------------------------------------------------------

--
-- Struttura della tabella `dati_terreno`
--

CREATE TABLE `dati_terreno` (
  `data_ora` datetime NOT NULL,
  `valore_umidita_terreno` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `schedulazione`
--

CREATE TABLE `schedulazione` (
  `id_giorno` int(11) NOT NULL,
  `ora_inizio` varchar(6) DEFAULT NULL,
  `durata` double(10,2) DEFAULT NULL COMMENT 'Durata in ore dell''irrigazione.',
  `dispositivi` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `schedulazione`
--

INSERT INTO `schedulazione` (`id_giorno`, `ora_inizio`, `durata`, `dispositivi`) VALUES
(0, '19:45', 1.30, '1|2|3'),
(1, NULL, NULL, ''),
(2, NULL, NULL, ''),
(3, NULL, NULL, ''),
(4, NULL, NULL, '1|2|3'),
(5, '20:00', 1.50, ''),
(6, NULL, NULL, '');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `dati_aria`
--
ALTER TABLE `dati_aria`
  ADD PRIMARY KEY (`data_ora`);

--
-- Indici per le tabelle `dati_pioggia`
--
ALTER TABLE `dati_pioggia`
  ADD PRIMARY KEY (`data_ora`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
