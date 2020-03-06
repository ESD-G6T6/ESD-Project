-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 06, 2020 at 05:29 AM
-- Server version: 5.7.26
-- PHP Version: 7.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scooter_rental`
--

-- drop database if exists and recreate it
drop database if exists scooter_rental;
create database scooter_rental;

-- use this database to create and populate the tables
use scooter_rental;

-- --------------------------------------------------------

--
-- Table structure for table `scooter`
--

DROP TABLE IF EXISTS `scooter`;
CREATE TABLE `scooter` (
  `ScooterID` varchar(5) NOT NULL,
  `ParkingLotID` varchar(5) NOT NULL,
  `AvailabilityStatus` boolean NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `parking_lot`
--

DROP TABLE IF EXISTS `parking_lot`;
CREATE TABLE `parking_lot` (
  `ParkingLotID` varchar(5) NOT NULL,
  `NumberOfAvailableScooters` int(3) NOT NULL,
  `Longitude` decimal(9, 6) NOT NULL,
  `Latitude` decimal(9, 6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

DROP TABLE IF EXISTS `booking`;
CREATE TABLE `booking` (
  `BookingID` varchar(5) NOT NULL,
  `ParkingLotID` varchar(5) NOT NULL,
  `StartTime` datetime NOT NULL,
  `EndTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------
