-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 24, 2017 at 03:47 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `firebrigade`
--
CREATE DATABASE IF NOT EXISTS `firebrigade` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `firebrigade`;

-- --------------------------------------------------------

--
-- Table structure for table `duty_attendance`
--

DROP TABLE IF EXISTS `duty_attendance`;
CREATE TABLE IF NOT EXISTS `duty_attendance` (
  `unit` varchar(10) NOT NULL,
  `attendance_date` date NOT NULL,
  `time_in` varchar(10) NOT NULL,
  `time_out` varchar(10) NOT NULL,
  `schedule` varchar(10) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `unit` (`unit`),
  KEY `schedule` (`schedule`),
  KEY `unit_2` (`unit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `duty_schedule`
--

DROP TABLE IF EXISTS `duty_schedule`;
CREATE TABLE IF NOT EXISTS `duty_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unit` varchar(10) NOT NULL,
  `schedule` varchar(10) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `unit` (`unit`),
  KEY `schedule` (`schedule`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=68 ;

-- --------------------------------------------------------

--
-- Table structure for table `fire_apparata`
--

DROP TABLE IF EXISTS `fire_apparata`;
CREATE TABLE IF NOT EXISTS `fire_apparata` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `fire_data_id` bigint(10) NOT NULL,
  `engine` varchar(10) DEFAULT NULL,
  `time_out` varchar(11) DEFAULT NULL,
  `fto_out` varchar(11) DEFAULT NULL,
  `time_in` varchar(11) DEFAULT NULL,
  `fto_in` varchar(11) DEFAULT NULL,
  `onboard` text,
  PRIMARY KEY (`id`),
  KEY `fire_data_id` (`fire_data_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=237 ;

-- --------------------------------------------------------

--
-- Table structure for table `fire_attendance`
--

DROP TABLE IF EXISTS `fire_attendance`;
CREATE TABLE IF NOT EXISTS `fire_attendance` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `unit` varchar(10) NOT NULL,
  `fire_data_id` bigint(11) NOT NULL,
  `attendance_date` date NOT NULL,
  `remarks` text,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  KEY `unit` (`unit`),
  KEY `attendance_date` (`attendance_date`),
  KEY `unit_2` (`unit`),
  KEY `unit_3` (`unit`,`fire_data_id`,`attendance_date`),
  KEY `fire_data_id` (`fire_data_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=147 ;

-- --------------------------------------------------------

--
-- Table structure for table `fire_data`
--

DROP TABLE IF EXISTS `fire_data`;
CREATE TABLE IF NOT EXISTS `fire_data` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `date_of_fire` date NOT NULL,
  `location` text NOT NULL,
  `classification` text NOT NULL,
  `caller` varchar(100) DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `time_received` varchar(10) NOT NULL,
  `time_controlled` varchar(10) NOT NULL,
  `unit` varchar(10) NOT NULL,
  `oic` int(11) NOT NULL,
  `water_used` int(11) NOT NULL,
  `dispatch` varchar(15) NOT NULL,
  `proceeding` text NOT NULL,
  `at_base` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `unit` (`unit`),
  KEY `date_of_fire` (`date_of_fire`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=57 ;

-- --------------------------------------------------------

--
-- Table structure for table `meeting`
--

DROP TABLE IF EXISTS `meeting`;
CREATE TABLE IF NOT EXISTS `meeting` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date_of_meeting` date NOT NULL,
  `activity` text NOT NULL,
  `venue` text NOT NULL,
  `oic` varchar(10) NOT NULL,
  `remarks` text NOT NULL,
  `recorder` varchar(10) NOT NULL,
  `approved_by` varchar(10) DEFAULT NULL,
  `sent` tinyint(1) DEFAULT '0',
  `replied` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `meeting_attendance`
--

DROP TABLE IF EXISTS `meeting_attendance`;
CREATE TABLE IF NOT EXISTS `meeting_attendance` (
  `meeting_id` bigint(20) NOT NULL,
  `unit` varchar(10) NOT NULL,
  `attendance_date` date NOT NULL,
  KEY `training` (`meeting_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `personnel`
--

DROP TABLE IF EXISTS `personnel`;
CREATE TABLE IF NOT EXISTS `personnel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `unit` varchar(10) NOT NULL,
  `status` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_unit` (`unit`),
  KEY `unit` (`unit`),
  KEY `unit_2` (`unit`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=52 ;

-- --------------------------------------------------------

--
-- Table structure for table `personnel_class`
--

DROP TABLE IF EXISTS `personnel_class`;
CREATE TABLE IF NOT EXISTS `personnel_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(10) DEFAULT NULL,
  `description` text NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rank`
--

DROP TABLE IF EXISTS `rank`;
CREATE TABLE IF NOT EXISTS `rank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(11) DEFAULT NULL,
  `status` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `special_activity`
--

DROP TABLE IF EXISTS `special_activity`;
CREATE TABLE IF NOT EXISTS `special_activity` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date_of_special` date NOT NULL,
  `activity` text NOT NULL,
  `venue` text NOT NULL,
  `oic` varchar(10) NOT NULL,
  `remarks` text NOT NULL,
  `recorder` varchar(10) NOT NULL,
  `approved_by` varchar(10) DEFAULT NULL,
  `sent` tinyint(1) DEFAULT '0',
  `replied` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

--
-- Table structure for table `special_activity_attendance`
--

DROP TABLE IF EXISTS `special_activity_attendance`;
CREATE TABLE IF NOT EXISTS `special_activity_attendance` (
  `special_id` bigint(20) NOT NULL,
  `unit` varchar(10) NOT NULL,
  `attendance_date` date NOT NULL,
  KEY `training` (`special_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `training`
--

DROP TABLE IF EXISTS `training`;
CREATE TABLE IF NOT EXISTS `training` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date_of_training` date NOT NULL,
  `activity` text NOT NULL,
  `venue` text NOT NULL,
  `oic` varchar(10) NOT NULL,
  `remarks` text NOT NULL,
  `recorder` varchar(10) NOT NULL,
  `approved_by` varchar(10) DEFAULT NULL,
  `sent` tinyint(1) DEFAULT '0',
  `replied` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `training_attendance`
--

DROP TABLE IF EXISTS `training_attendance`;
CREATE TABLE IF NOT EXISTS `training_attendance` (
  `training_id` bigint(20) NOT NULL,
  `unit` varchar(10) NOT NULL,
  `attendance_date` date NOT NULL,
  KEY `training` (`training_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `duty_attendance`
--
ALTER TABLE `duty_attendance`
  ADD CONSTRAINT `attendance_unit_number_update` FOREIGN KEY (`unit`) REFERENCES `personnel` (`unit`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `duty_schedule`
--
ALTER TABLE `duty_schedule`
  ADD CONSTRAINT `unit_number_` FOREIGN KEY (`unit`) REFERENCES `personnel` (`unit`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fire_apparata`
--
ALTER TABLE `fire_apparata`
  ADD CONSTRAINT `fire_apparata_ibfk_1` FOREIGN KEY (`fire_data_id`) REFERENCES `fire_data` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `fire_attendance`
--
ALTER TABLE `fire_attendance`
  ADD CONSTRAINT `fire_attendance_ibfk_1` FOREIGN KEY (`fire_data_id`) REFERENCES `fire_data` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `meeting_attendance`
--
ALTER TABLE `meeting_attendance`
  ADD CONSTRAINT `meeting_attendance_ibfk_1` FOREIGN KEY (`meeting_id`) REFERENCES `meeting` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `special_activity_attendance`
--
ALTER TABLE `special_activity_attendance`
  ADD CONSTRAINT `special_activity_attendance_ibfk_1` FOREIGN KEY (`special_id`) REFERENCES `special_activity` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `training_attendance`
--
ALTER TABLE `training_attendance`
  ADD CONSTRAINT `training_attendance_ibfk_1` FOREIGN KEY (`training_id`) REFERENCES `training` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
