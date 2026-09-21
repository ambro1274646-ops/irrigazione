-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql300.infinityfree.com
-- Creato il: Set 21, 2026 alle 15:00
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
  `durata` double(10,2) DEFAULT NULL COMMENT 'Durata in ore dell''irrigazione.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `schedulazione`
--

INSERT INTO `schedulazione` (`id_giorno`, `ora_inizio`, `durata`) VALUES
(0, '19:45', 1.30),
(1, NULL, NULL),
(2, NULL, NULL),
(3, NULL, NULL),
(4, NULL, NULL),
(5, '20:00', 1.50),
(6, NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
