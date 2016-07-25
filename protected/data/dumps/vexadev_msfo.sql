-- phpMyAdmin SQL Dump
-- version 4.0.5
-- http://www.phpmyadmin.net
--
-- Host: vexadev.mysql.ukraine.com.ua
-- Generation Time: Jul 28, 2014 at 07:22 PM
-- Server version: 5.1.72-cll-lve
-- PHP Version: 5.2.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `vexadev_msfo`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_chapter`
--

CREATE TABLE IF NOT EXISTS `tbl_chapter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `first_page` int(4) NOT NULL,
  `paid` int(1) NOT NULL,
  `file` varchar(255) NOT NULL,
  `upload_date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tbl_chapter`
--

INSERT INTO `tbl_chapter` (`id`, `title`, `first_page`, `paid`, `file`, `upload_date`) VALUES
(2, 'Глава 1', 1, 0, 'demo.pdf', 0),
(3, 'Глава 2', 22, 1, 'phpunit-book.pdf', 0),
(4, 'Глава 3', 33, 0, 'progit.ru.pdf', 0),
(5, 'МСФО 1 "Представление отчетности"', 5, 1, 'пример шрифта.pdf', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE IF NOT EXISTS `tbl_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(128) NOT NULL,
  `firstname` varchar(128) DEFAULT NULL,
  `lastname` varchar(128) DEFAULT NULL,
  `pass` varchar(255) NOT NULL,
  `role` enum('admin') NOT NULL DEFAULT 'admin',
  `registred` int(11) NOT NULL DEFAULT '0',
  `confirmed` tinyint(1) DEFAULT '0' COMMENT 'Displaying confirmed user own email or not',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `email`, `firstname`, `lastname`, `pass`, `role`, `registred`, `confirmed`) VALUES
(1, 'admin@admin.com', 'admin', NULL, '$2a$13$pM.ncJIYIQ213HsA.H1Jq.gU/ps6Bv7wi5MMW9JATCZrHuJvJpR6u', 'admin', 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
