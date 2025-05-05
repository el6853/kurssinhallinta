-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 05, 2025 at 10:03 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kurssinhallinta`
--

-- --------------------------------------------------------

--
-- Table structure for table `kurssikirjautumiset`
--

CREATE TABLE `kurssikirjautumiset` (
  `tunnus` int(11) NOT NULL,
  `opiskelija_id` int(11) DEFAULT NULL,
  `kurssi_id` int(11) DEFAULT NULL,
  `kirjautumisaika` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kurssikirjautumiset`
--

INSERT INTO `kurssikirjautumiset` (`tunnus`, `opiskelija_id`, `kurssi_id`, `kirjautumisaika`) VALUES
(1, 1001, 1, '2025-04-30 15:10:46'),
(2, 1002, 1, '2025-04-30 15:10:46'),
(3, 1003, 2, '2025-04-30 15:10:46'),
(4, 1004, 3, '2025-04-30 15:10:46');

-- --------------------------------------------------------

--
-- Table structure for table `kurssit`
--

CREATE TABLE `kurssit` (
  `tunnus` int(11) NOT NULL,
  `nimi` varchar(100) DEFAULT NULL,
  `kuvaus` text DEFAULT NULL,
  `alkupaiva` date DEFAULT NULL,
  `loppupaiva` date DEFAULT NULL,
  `opettaja_id` int(11) DEFAULT NULL,
  `tila_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kurssit`
--

INSERT INTO `kurssit` (`tunnus`, `nimi`, `kuvaus`, `alkupaiva`, `loppupaiva`, `opettaja_id`, `tila_id`) VALUES
(1, 'Matikan perusteet', 'Alkeistason matematiikkaa.', '2025-08-15', '2025-12-15', 1, 1),
(2, 'Fysiikan jatkokurssi', 'Syventävää fysiikkaa.', '2025-09-01', '2025-12-01', 2, 2),
(3, 'Biologian perusteet', 'Elämän alkeiden tarkastelua.', '2025-08-20', '2025-11-30', 3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `opettajat`
--

CREATE TABLE `opettajat` (
  `tunnus` int(11) NOT NULL,
  `etunimi` varchar(100) DEFAULT NULL,
  `sukunimi` varchar(100) DEFAULT NULL,
  `aine` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `opettajat`
--

INSERT INTO `opettajat` (`tunnus`, `etunimi`, `sukunimi`, `aine`) VALUES
(1, 'Laura', 'Virtanen', 'Matematiikka'),
(2, 'Mikko', 'Korhonen', 'Fysiikka'),
(3, 'Sanna', 'Mäkelä', 'Biologia'),
(4, 'Antti', 'Heikkinen', 'Historia'),
(5, 'Anna', 'Laine', 'Kemia');

-- --------------------------------------------------------

--
-- Table structure for table `opiskelijat`
--

CREATE TABLE `opiskelijat` (
  `opiskelijanumero` int(11) NOT NULL,
  `etunimi` varchar(100) DEFAULT NULL,
  `sukunimi` varchar(100) DEFAULT NULL,
  `syntymapaiva` date DEFAULT NULL,
  `vuosikurssi` int(11) DEFAULT NULL CHECK (`vuosikurssi` between 1 and 3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `opiskelijat`
--

INSERT INTO `opiskelijat` (`opiskelijanumero`, `etunimi`, `sukunimi`, `syntymapaiva`, `vuosikurssi`) VALUES
(1001, 'Matti', 'Meikäläine', '2004-05-12', 1),
(1002, 'Tiina', 'Testaaja', '2003-11-21', 2),
(1003, 'Sami', 'Esimerkki', '2005-03-08', 1),
(1004, 'Ella', 'Mallikas', '2002-07-15', 3);

-- --------------------------------------------------------

--
-- Table structure for table `tilat`
--

CREATE TABLE `tilat` (
  `tunnus` int(11) NOT NULL,
  `nimi` varchar(100) DEFAULT NULL,
  `kapasiteetti` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tilat`
--

INSERT INTO `tilat` (`tunnus`, `nimi`, `kapasiteetti`) VALUES
(1, 'Luokka 101', 30),
(2, 'Luokka 102', 25),
(3, 'Auditorio', 100);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kurssikirjautumiset`
--
ALTER TABLE `kurssikirjautumiset`
  ADD PRIMARY KEY (`tunnus`),
  ADD KEY `opiskelija_id` (`opiskelija_id`),
  ADD KEY `kurssi_id` (`kurssi_id`);

--
-- Indexes for table `kurssit`
--
ALTER TABLE `kurssit`
  ADD PRIMARY KEY (`tunnus`),
  ADD KEY `opettaja_id` (`opettaja_id`),
  ADD KEY `tila_id` (`tila_id`);

--
-- Indexes for table `opettajat`
--
ALTER TABLE `opettajat`
  ADD PRIMARY KEY (`tunnus`);

--
-- Indexes for table `opiskelijat`
--
ALTER TABLE `opiskelijat`
  ADD PRIMARY KEY (`opiskelijanumero`);

--
-- Indexes for table `tilat`
--
ALTER TABLE `tilat`
  ADD PRIMARY KEY (`tunnus`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kurssikirjautumiset`
--
ALTER TABLE `kurssikirjautumiset`
  MODIFY `tunnus` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kurssikirjautumiset`
--
ALTER TABLE `kurssikirjautumiset`
  ADD CONSTRAINT `kurssikirjautumiset_ibfk_1` FOREIGN KEY (`opiskelija_id`) REFERENCES `opiskelijat` (`opiskelijanumero`),
  ADD CONSTRAINT `kurssikirjautumiset_ibfk_2` FOREIGN KEY (`kurssi_id`) REFERENCES `kurssit` (`tunnus`);

--
-- Constraints for table `kurssit`
--
ALTER TABLE `kurssit`
  ADD CONSTRAINT `kurssit_ibfk_1` FOREIGN KEY (`opettaja_id`) REFERENCES `opettajat` (`tunnus`),
  ADD CONSTRAINT `kurssit_ibfk_2` FOREIGN KEY (`tila_id`) REFERENCES `tilat` (`tunnus`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
