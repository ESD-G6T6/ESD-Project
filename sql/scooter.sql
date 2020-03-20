-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 14, 2019 at 06:42 AM
-- Server version: 5.7.19
-- PHP Version: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scooter`
--
CREATE DATABASE IF NOT EXISTS `scooter` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `scooter`;

-- --------------------------------------------------------

--
-- Table structure for table `scooter`
--

DROP TABLE IF EXISTS `scooter`;
CREATE TABLE IF NOT EXISTS `scooter` (
  `scooterID` varchar(5) NOT NULL,
  `parkingLotID` varchar(5),
  `availabilityStatus` boolean NOT NULL,
  PRIMARY KEY (`scooterID`) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `scooter`
--

INSERT INTO `scooter` (`scooterID`, `parkingLotID`, `availabilityStatus`) VALUES
('S0001', 'P0001', 1),
('S0002', 'P0001', 1),
('S0003', 'P0001', 1),
('S0004', 'P0001', 1),
('S0005', 'P0001', 1),
('S0006', 'P0002', 1),
('S0007', 'P0002', 1),
('S0008', 'P0002', 1),
('S0009', 'P0002', 1),
('S0010', 'P0002', 1),
('S0011', 'P0003', 1),
('S0012', 'P0003', 1),
('S0013', 'P0003', 1),
('S0014', 'P0003', 1),
('S0015', 'P0003', 1),
('S0016', 'P0004', 1),
('S0017', 'P0004', 1),
('S0018', 'P0004', 1),
('S0019', 'P0004', 1),
('S0020', 'P0004', 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
