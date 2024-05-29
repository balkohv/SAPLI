-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 03, 2024 at 07:17 AM
-- Server version: 5.7.24
-- PHP Version: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projetapi`
--

-- --------------------------------------------------------

--
-- Table structure for table `medished_db`
--

CREATE TABLE `medished_db` (
  `login` varchar(50) NOT NULL,
  `mdp` varchar(200) NOT NULL,
  `id_auth` varchar(50) NOT NULL,
  `role` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `medished_db`
--

INSERT INTO `medished_db` (`login`, `mdp`, `id_auth`, `role`) VALUES
('secretaire1', '870266392ce3ae61c88f44857828f2dca6095f2ebe9c12b7bf1af4a21b6fa260', 'auht02', 'scretaire'),
('test', '9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08', 'auht01', 'usagee');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `medished_db`
--
ALTER TABLE `medished_db`
  ADD PRIMARY KEY (`login`),
  ADD UNIQUE KEY `id_auth` (`id_auth`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
