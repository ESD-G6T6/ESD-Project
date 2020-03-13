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
-- Database: `parkingLot`
--
CREATE DATABASE IF NOT EXISTS `parkingLot` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `parkingLot`;

-- --------------------------------------------------------

--
-- Table structure for table `parkingLot`
--

DROP TABLE IF EXISTS `parking_lot`;
CREATE TABLE IF NOT EXISTS `parkingLot` (
  `parkingLotID` varchar(5) NOT NULL,
  `numberOfAvailableScooters` int(3) NOT NULL,
  `latitude` decimal(10, 2) NOT NULL,
  `longitude` decimal(10, 2) NOT NULL,
  PRIMARY KEY (`parkingLotID`) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
-- Dumping data for table `parkingLot`
--

INSERT INTO `parkingLot` (`parkingLotID`, `numberOfAvailableScooters`, `latitude`, `longitude`) VALUES
('P0001', 5, 1.4360, 103.7860),
('P0002', 5, 1.2644, 103.8222),
('P0003', 5, 1.3346, 103.9624),
('P0004', 5, 1.3397, 103.7067);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
