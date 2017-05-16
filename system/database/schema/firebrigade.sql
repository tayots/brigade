-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2017 at 08:58 PM
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

--
-- Dumping data for table `duty_attendance`
--

INSERT INTO `duty_attendance` (`unit`, `attendance_date`, `time_in`, `time_out`, `schedule`, `date_created`) VALUES
('45', '2017-04-30', '07:07 PM', '07:07 AM', 'Sunday', '2017-05-01 23:28:36'),
('88', '2017-04-28', '08:00 PM', '08:00 AM', 'Friday', '2017-05-02 22:36:57'),
('88', '2017-05-02', '08:00 PM', '08:00 AM', 'Tuesday', '2017-05-02 22:37:35'),
('94', '2017-05-01', '08:00 PM', '06:00 AM', 'Monday', '2017-05-02 23:37:48'),
('90', '2017-05-01', '08:59 PM', '08:08 AM', 'Monday', '2017-05-02 23:40:53'),
('92', '2017-05-02', '10:02 PM', '08:00 AM', 'Tuesday', '2017-05-02 23:41:07'),
('83', '2017-05-02', '09:00 PM', '08:00 AM', 'Tuesday', '2017-05-02 23:41:25'),
('94', '2017-05-14', '08:00 PM', '08:00 AM', 'Sunday', '2017-05-14 21:22:27'),
('90', '2017-05-14', '09:00 PM', '08:00 AM', 'Sunday', '2017-05-14 21:26:59'),
('90', '2017-05-15', '07:48 PM', '80:00 AM', 'Monday', '2017-05-16 00:53:49'),
('92', '2017-05-15', '10:30 PM', '08:08 AM', 'Monday', '2017-05-16 00:55:11'),
('94', '2017-05-15', '08:00 PM', '08:00 AM', 'Monday', '2017-05-16 01:31:54'),
('92', '2017-05-01', '10:02 PM', '08:08 AM', 'Monday', '2017-05-16 01:33:20'),
('83', '2017-05-01', '09:00 PM', '08:00 AM', 'Monday', '2017-05-16 01:34:11'),
('84', '2017-05-13', '03:30 PM', '10:45 AM', 'Saturday', '2017-05-16 01:36:17'),
('93', '2017-05-13', '05:37 PM', '03:00 PM', 'Saturday', '2017-05-16 01:37:40'),
('90', '2017-05-13', '08:00 PM', '01:00 PM', 'Saturday', '2017-05-16 01:38:21'),
('91', '2017-05-13', '09:04 PM', '10:45 AM', 'Saturday', '2017-05-16 01:38:55'),
('97', '2017-05-13', '09:15 PM', '12:10 PM', 'Saturday', '2017-05-16 01:39:10'),
('95', '2017-05-13', '09:25 PM', '10:12 AM', 'Saturday', '2017-05-16 01:39:27'),
('94', '2017-05-13', '09:46 PM', '10:10 AM', 'Saturday', '2017-05-16 01:39:48'),
('96', '2017-05-13', '11:46 PM', '09:30 AM', 'Saturday', '2017-05-16 01:40:11'),
('86', '2017-05-13', '07:00 PM', '12:00 PM', 'Saturday', '2017-05-16 01:40:28'),
('90', '2017-05-12', '07:52 PM', '08:00 AM', 'Friday', '2017-05-16 01:41:17'),
('84', '2017-05-12', '08:27 PM', '04:45 AM', 'Friday', '2017-05-16 01:41:36'),
('88', '2017-05-12', '09:27 PM', '07:04 AM', 'Friday', '2017-05-16 01:41:45'),
('92', '2017-05-12', '09:00 PM', '08:07 AM', 'Friday', '2017-05-16 01:41:56'),
('94', '2017-04-09', '08:00 PM', '08:00 AM', 'Sunday', '2017-05-16 17:56:07');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=44 ;

--
-- Dumping data for table `duty_schedule`
--

INSERT INTO `duty_schedule` (`id`, `unit`, `schedule`, `date_created`) VALUES
(1, '94', 'Monday', '2017-01-17 16:31:42'),
(2, '94', 'Tuesday', '2017-01-17 16:32:11'),
(3, '88', 'Tuesday', '2017-01-17 16:32:32'),
(10, '91', 'Sunday', '2017-01-17 16:51:51'),
(11, '88', 'Wednesday', '2017-01-17 17:00:09'),
(12, '92', 'Monday', '2017-01-17 17:01:23'),
(13, '91', 'Saturday', '2017-01-17 17:01:50'),
(15, '92', 'Friday', '2017-01-17 17:02:03'),
(16, '90', 'Monday', '2017-01-17 17:02:54'),
(17, '85', 'Monday', '2017-01-17 17:02:59'),
(18, '86', 'Monday', '2017-01-17 17:03:03'),
(19, '83', 'Monday', '2017-01-17 17:03:07'),
(20, '85', 'Tuesday', '2017-01-17 17:03:15'),
(21, '95', 'Tuesday', '2017-01-17 17:03:24'),
(22, '96', 'Tuesday', '2017-01-17 17:03:29'),
(24, '98', 'Tuesday', '2017-01-17 17:03:43'),
(25, '86', 'Wednesday', '2017-01-17 17:03:56'),
(26, '85', 'Wednesday', '2017-01-17 17:04:00'),
(27, '92', 'Wednesday', '2017-01-17 17:04:05'),
(28, '97', 'Wednesday', '2017-01-17 17:04:19'),
(29, '90', 'Wednesday', '2017-01-17 17:04:27'),
(30, '89', 'Thursday', '2017-01-17 17:04:34'),
(31, '83', 'Thursday', '2017-01-17 17:04:40'),
(32, '90', 'Thursday', '2017-01-17 17:04:44'),
(33, '93', 'Thursday', '2017-01-17 17:04:50'),
(34, '98', 'Thursday', '2017-01-17 17:04:56'),
(35, '88', 'Friday', '2017-01-17 17:05:12'),
(36, '93', 'Friday', '2017-01-17 17:05:21'),
(37, '95', 'Friday', '2017-01-17 17:05:24'),
(38, '96', 'Friday', '2017-01-17 17:05:28'),
(39, '93', 'Saturday', '2017-01-17 17:05:39'),
(40, '89', 'Saturday', '2017-01-17 17:05:45'),
(41, '83', 'Sunday', '2017-01-17 17:05:54'),
(42, '94', 'Sunday', '2017-01-17 17:05:59'),
(43, '96', 'Sunday', '2017-01-17 17:06:04');

-- --------------------------------------------------------

--
-- Table structure for table `fire_apparata`
--

DROP TABLE IF EXISTS `fire_apparata`;
CREATE TABLE IF NOT EXISTS `fire_apparata` (
  `fire_data_id` bigint(10) NOT NULL,
  `engine` varchar(10) DEFAULT NULL,
  `time_out` varchar(11) DEFAULT NULL,
  `fto_out` int(11) DEFAULT NULL,
  `time_in` varchar(11) DEFAULT NULL,
  `fto_in` int(11) DEFAULT NULL,
  `onboard` text,
  KEY `fire_data_id` (`fire_data_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fire_apparata`
--

INSERT INTO `fire_apparata` (`fire_data_id`, `engine`, `time_out`, `fto_out`, `time_in`, `fto_in`, `onboard`) VALUES
(27, 'E20', '9:12', 87, '10:30', 87, '94,90,86'),
(27, '', '', 0, '', 0, ''),
(27, '', '', 0, '', 0, ''),
(27, '', '', 0, '', 0, ''),
(27, '', '', 0, '', 0, ''),
(27, '', '', 0, '', 0, ''),
(27, '', '', 0, '', 0, ''),
(27, '', '', 0, '', 0, '');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `fire_data`
--

INSERT INTO `fire_data` (`id`, `date_of_fire`, `location`, `classification`, `caller`, `contact_number`, `time_received`, `time_controlled`, `unit`, `oic`, `water_used`, `dispatch`, `proceeding`, `at_base`) VALUES
(27, '2017-05-15', 'Golden Valley', 'Working Fire', 'direct', '', '09:03 AM', '10:12 AM', 'michelle', 94, 0, 'Yes', '09,45', '92,93,');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `meeting`
--

INSERT INTO `meeting` (`id`, `date_of_meeting`, `activity`, `venue`, `oic`, `remarks`, `recorder`, `approved_by`, `sent`, `replied`) VALUES
(6, '2017-04-27', 'GM Meeting', 'bravo', '09', 'dafasdf', '30', NULL, 0, 0);

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

--
-- Dumping data for table `meeting_attendance`
--

INSERT INTO `meeting_attendance` (`meeting_id`, `unit`, `attendance_date`) VALUES
(6, '50', '2017-04-27'),
(6, '40', '2017-04-27'),
(6, '09', '2017-04-27');

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

--
-- Dumping data for table `personnel`
--

INSERT INTO `personnel` (`id`, `first_name`, `last_name`, `unit`, `status`) VALUES
(4, 'Cesar', 'Tiu', '13', 'active'),
(5, 'Johnny', 'Chik', '18', 'active'),
(6, 'Tirso', 'Aberia', '22', 'active'),
(7, 'Alexander', 'Sia', '27', 'active'),
(8, 'Mark Anthony', 'Rañola', '29', 'active'),
(9, 'Ronnie', 'Gocuan', '30', 'active'),
(10, 'Maxwell', 'Ahyong', '35', 'inactive'),
(11, 'Jason', 'Dy', '38', 'active'),
(12, 'Micheal', 'Dy', '40', 'active'),
(13, 'Joubert', 'Lim', '42', 'active'),
(14, 'Alfred', 'Chu', '43', 'active'),
(15, 'Philip', 'Po', '45', 'active'),
(16, 'Wilton', 'Uykingtian', '48', 'active'),
(17, 'Leonardo', 'Angeles Jr.', '50', 'active'),
(18, 'Dick Nathan', 'Co', '55', 'active'),
(19, 'Roberto', 'Labadan', '56', 'active'),
(20, 'Benjamin', 'Go', '62', 'active'),
(21, 'Bronson', 'Peña', '63', 'active'),
(22, 'Jepher', 'Racuya', '68', 'active'),
(23, 'Dennis', 'Cuenco', '72', 'active'),
(24, 'Micheal', 'Tan', '75', 'active'),
(25, 'Kimhe', 'Chan', '77', 'active'),
(26, 'Jeffrey', 'So', '79', 'active'),
(27, 'Alexander', 'Tanchan', '80', 'active'),
(28, 'Mark', 'Uy', '82', 'active'),
(29, 'Neil', 'Tan', '83', 'active'),
(30, 'Russel', 'Gocuan', '84', 'active'),
(31, 'Frey Angelo', 'Blanco', '85', 'active'),
(32, 'Joseph', 'Baluyos', '86', 'active'),
(33, 'Micheal', 'Lim', '87', 'active'),
(34, 'Louie', 'Aberia', '88', 'active'),
(35, 'Alex', 'Acusar', '89', 'active'),
(36, 'Erwin', 'Batucan', '90', 'active'),
(37, 'Phoenix Mckenzie', 'Gocuan', '91', 'active'),
(38, 'Ace Mark', 'Lao', '92', 'active'),
(39, 'Charles Kevin', 'Lim', '93', 'active'),
(40, 'Jeremy', 'Ling', '94', 'active'),
(41, 'Jonathan', 'Suarez', '95', 'active'),
(42, 'Jacob', 'Tanco', '96', 'active'),
(43, 'Rey', 'Tacder', '97', 'active'),
(44, 'Justin', 'Tieng', '98', 'active'),
(46, 'Leonardo', 'Sylianco Jr.', '09', 'active'),
(47, 'Rey', 'R1', 'R1', 'active'),
(48, 'Bebot', 'R2', 'R2', 'active'),
(49, 'Rodel', 'R3', 'R3', 'active'),
(50, 'Rensy', 'D1', 'D1', 'active'),
(51, 'Casiano', 'D2', 'D2', 'active');

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

--
-- Dumping data for table `rank`
--

INSERT INTO `rank` (`id`, `name`, `status`) VALUES
(1, 'FTO', 'active'),
(2, 'Firefighter', 'active');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `special_activity`
--

INSERT INTO `special_activity` (`id`, `date_of_special`, `activity`, `venue`, `oic`, `remarks`, `recorder`, `approved_by`, `sent`, `replied`) VALUES
(3, '2017-05-11', 'IHAS', 'test', '30', 'teassdf', '30', NULL, 0, 0);

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

--
-- Dumping data for table `special_activity_attendance`
--

INSERT INTO `special_activity_attendance` (`special_id`, `unit`, `attendance_date`) VALUES
(3, '95', '2017-05-11'),
(3, '96', '2017-05-11'),
(3, '83', '2017-05-11'),
(3, '30', '2017-05-11');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `training`
--

INSERT INTO `training` (`id`, `date_of_training`, `activity`, `venue`, `oic`, `remarks`, `recorder`, `approved_by`, `sent`, `replied`) VALUES
(6, '2017-05-14', 'Ground Ladder', 'Braco', '30', 'test', '40', NULL, 0, 0),
(7, '2017-05-07', 'test', 'test', '22', '22', '22', NULL, 0, 0),
(8, '2017-04-23', 'test', 'test', '22', '22', '22', NULL, 0, 0),
(9, '2017-02-12', 'test', 'test', '22', '22', '22', NULL, 0, 0),
(10, '2017-01-15', 'test', 'test', '22', '22', '22', NULL, 0, 0);

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
-- Dumping data for table `training_attendance`
--

INSERT INTO `training_attendance` (`training_id`, `unit`, `attendance_date`) VALUES
(6, '90', '2017-05-14'),
(6, '92', '2017-05-14'),
(6, '94', '2017-05-14'),
(6, '95', '2017-05-14'),
(6, '96', '2017-05-14'),
(6, '30', '2017-05-14'),
(7, '50', '2017-05-07'),
(7, '13', '2017-05-07'),
(7, '45', '2017-05-07'),
(8, '50', '2017-04-23'),
(8, '13', '2017-04-23'),
(8, '45', '2017-04-23'),
(9, '50', '2017-02-12'),
(9, '13', '2017-02-12'),
(9, '45', '2017-02-12'),
(10, '50', '2017-01-15'),
(10, '13', '2017-01-15'),
(10, '45', '2017-01-15'),
(10, '55', '2017-01-15'),
(10, '72', '2017-01-15');

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
