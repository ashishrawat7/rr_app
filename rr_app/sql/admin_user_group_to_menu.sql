-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 27, 2017 at 10:42 AM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `abc_erp`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_user_group_to_menu`
--

CREATE TABLE IF NOT EXISTS `admin_user_group_to_menu` (
  `user_group_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `permission` varchar(20) NOT NULL,
  PRIMARY KEY (`user_group_id`,`menu_id`,`permission`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin_user_group_to_menu`
--

INSERT INTO `admin_user_group_to_menu` (`user_group_id`, `menu_id`, `permission`) VALUES
(1, 1, 'R,W'),
(1, 2, 'R,W'),
(1, 3, 'R,W'),
(1, 4, 'R,W'),
(1, 5, 'R,W'),
(1, 6, 'R,W'),
(1, 7, 'R,W'),
(1, 8, 'R,W'),
(2, 1, 'R'),
(2, 2, 'R,W'),
(2, 3, 'R,W'),
(2, 4, 'R'),
(2, 5, 'R'),
(2, 6, 'R'),
(2, 7, 'R,W'),
(2, 8, 'R,W');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
