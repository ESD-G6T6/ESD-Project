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
-- Database: `booking`
--
CREATE DATABASE IF NOT EXISTS `booking` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `booking`;

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

DROP TABLE IF EXISTS `booking`;
CREATE TABLE IF NOT EXISTS `booking` (
  `bookingID` varchar(5) NOT NULL,
  `scooterID` varchar(5) NOT NULL,
  `parkingLotID` varchar(5) NOT NULL,
  `startTime` datetime NOT NULL,
  `endTime` datetime NOT NULL,
  PRIMARY KEY (`bookingID`) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`bookingID`, `scooterID`, `parkingLotID`, `startTime`, `endTime`) VALUES
('B0001', 'S0001', 'P0001', '2020-03-06 00:00:00', '2020-03-06 00:01:00'),
('B0002', 'S0002', 'P0001','2020-03-06 00:00:00', '2020-03-06 00:02:00'),
('B0003', 'S0003', 'P0001','2020-03-06 00:00:00', '2020-03-06 00:03:00'),
('B0004', 'S0004', 'P0001','2020-03-06 00:00:00', '2020-03-06 00:04:00'),
('B0005', 'S0005', 'P0001','2020-03-06 00:00:00', '2020-03-06 00:05:00'),
('B0006', 'S0006', 'P0001','2020-03-06 00:00:00', '2020-03-06 00:06:00'),
('B0007', 'S0007', 'P0001','2020-03-06 00:00:00', '2020-03-06 00:07:00'),
('B0008', 'S0008', 'P0001','2020-03-06 00:00:00', '2020-03-06 00:08:00');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
