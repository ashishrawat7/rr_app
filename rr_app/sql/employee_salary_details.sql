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
-- Table structure for table `employee_salary_details`
--

CREATE TABLE IF NOT EXISTS `employee_salary_details` (
  `salary_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `month_of` date NOT NULL,
  `days_paid` tinyint(1) NOT NULL,
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
  `salary_adv` decimal(8,2) NOT NULL,
  `mobile_dedu` decimal(8,2) NOT NULL,
  `other_dedu` decimal(8,2) NOT NULL,
  `total_earning` decimal(8,2) NOT NULL,
  `total_deduction` decimal(8,2) NOT NULL,
  `net_pay` decimal(8,2) NOT NULL,
  PRIMARY KEY (`salary_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
