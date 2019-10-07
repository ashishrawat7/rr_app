-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 27, 2017 at 10:41 AM
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
-- Table structure for table `admin_menu`
--

CREATE TABLE IF NOT EXISTS `admin_menu` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(64) NOT NULL,
  `menu_file_name` varchar(64) NOT NULL,
  `add_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL,
  PRIMARY KEY (`menu_id`),
  UNIQUE KEY `menu_name` (`menu_name`),
  UNIQUE KEY `menu_file_name` (`menu_file_name`),
  UNIQUE KEY `menu_name_2` (`menu_name`),
  UNIQUE KEY `menu_name_3` (`menu_name`,`menu_file_name`),
  UNIQUE KEY `menu_name_4` (`menu_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `admin_menu`
--

INSERT INTO `admin_menu` (`menu_id`, `menu_name`, `menu_file_name`, `add_date`, `updated_date`) VALUES
(1, 'ERP Team', 'ERP-TEAM', '2016-06-03 11:18:56', '2016-06-03 12:14:40'),
(2, 'Inventory', 'INVENTORY', '2016-06-08 16:35:20', '0000-00-00 00:00:00'),
(3, 'Sale', 'SALE', '2016-06-22 15:38:41', '0000-00-00 00:00:00'),
(4, 'Project', 'PROJECT', '2016-07-09 13:07:22', '0000-00-00 00:00:00'),
(5, 'Project Estimate Grant', 'PROJECT-ESTIMATE-GRANT', '2016-07-20 15:53:47', '0000-00-00 00:00:00'),
(6, 'Letter', 'LETTER', '2016-07-27 15:43:03', '0000-00-00 00:00:00'),
(7, 'HR Management', 'HR-MAN', '2016-08-02 12:32:48', '0000-00-00 00:00:00'),
(8, 'Menu Management', 'MENU-MANG', '2016-11-20 15:34:00', '0000-00-00 00:00:00');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
