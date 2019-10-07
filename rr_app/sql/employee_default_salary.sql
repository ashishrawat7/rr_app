-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 17, 2017 at 08:56 AM
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
-- Table structure for table `employee_default_salary`
--

CREATE TABLE IF NOT EXISTS `employee_default_salary` (
  `default_salary_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `basic` decimal(8,2) NOT NULL,
  `hra` decimal(8,2) NOT NULL,
  `spical_alw` decimal(8,2) NOT NULL,
  `conveyance_alw` decimal(8,2) NOT NULL,
  `education_alw` decimal(8,2) NOT NULL,
  `medical_alw` decimal(8,2) NOT NULL,
  `mobile_alw` decimal(8,2) NOT NULL,
  `internet` decimal(8,2) NOT NULL,
  `pf` decimal(8,2) NOT NULL,
  `esi` decimal(8,2) NOT NULL,
  `professional_tax` decimal(8,2) NOT NULL,
  `tds` decimal(8,2) NOT NULL,
  `employer_pf` decimal(8,2) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `date_added` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  PRIMARY KEY (`default_salary_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `employee_default_salary`
--

INSERT INTO `employee_default_salary` (`default_salary_id`, `employee_id`, `basic`, `hra`, `spical_alw`, `conveyance_alw`, `education_alw`, `medical_alw`, `mobile_alw`, `internet`, `pf`, `esi`, `professional_tax`, `tds`, `employer_pf`, `status`, `date_added`, `date_updated`) VALUES
(1, 7, 2000.00, 1000.00, 1000.00, 1000.00, 1000.00, 1000.00, 500.00, 500.00, 12.00, 1.75, 600.00, 5.00, 13.36, 1, '2016-08-12 17:27:55', '2016-08-13 12:58:07'),
(2, 7, 2000.00, 1000.00, 1000.00, 1000.00, 1000.00, 1000.00, 500.00, 500.00, 12.00, 1.75, 600.00, 5.00, 13.36, 1, '2016-08-13 12:05:49', '2016-08-13 12:58:07');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
