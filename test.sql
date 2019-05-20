-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 04, 2019 at 10:46 PM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 5.6.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `logic_data`
--

CREATE TABLE `logic_data` (
  `DateTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `A1` int(11) NOT NULL,
  `A2` int(11) NOT NULL,
  `A3` int(11) NOT NULL,
  `A4` int(11) NOT NULL,
  `RA1` int(11) NOT NULL,
  `RA2` int(11) NOT NULL,
  `RA3` int(11) NOT NULL,
  `RA4` int(11) NOT NULL,
  `adminAccess` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `logic_data`
--

INSERT INTO `logic_data` (`DateTime`, `A1`, `A2`, `A3`, `A4`, `RA1`, `RA2`, `RA3`, `RA4`, `adminAccess`) VALUES
('2019-03-05 02:25:38', 528, 558, 471, 454, 158, 665, 420, 934, 1),
('2019-03-05 03:14:01', 78206, 797895, 9204, 10013, 821, 315, 231, 904, 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
