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
-- Database: `if0_42967232_irrigazione_db`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `cookies`
--

CREATE TABLE `cookies` (
  `username` varchar(20) NOT NULL,
  `valore` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dump dei dati per la tabella `cookies`
--

INSERT INTO `cookies` (`username`, `valore`) VALUES
('ambro', '0KtIkCeIV7KNT0S9EtLXE8ptUJUStjwG');

-- --------------------------------------------------------

--
-- Struttura della tabella `super_utenti`
--

CREATE TABLE `super_utenti` (
  `id_utente` int(11) NOT NULL,
  `id_impianto` int(11) NOT NULL,
  `nome_db_impianto` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `id_utente` int(11) NOT NULL COMMENT 'ID dell''utente, identifica l''utente anche nella tabella super user',
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `super_user` varchar(1) NOT NULL COMMENT 'Definisce gli utenti che hanno più impianti',
  `id_impianto` int(11) DEFAULT NULL COMMENT 'identifica un impianto quando un datalogger invia dati al beckend del sito. Se non è settato è super user',
  `nome_db_impianto` varchar(60) DEFAULT NULL COMMENT 'Nome db impianto per utenti che ne hanno solo uno. Se null è super user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`id_utente`, `username`, `password`, `super_user`, `id_impianto`, `nome_db_impianto`) VALUES
(1, 'ambro', 'password', 'f', 1, 'if0_42967232_irrigazione_db_impianto_generico');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`id_utente`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `id_utente` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID dell''utente, identifica l''utente anche nella tabella super user', AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
