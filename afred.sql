-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 30, 2014 at 01:35 AM
-- Server version: 5.1.67-rel14.3-log
-- PHP Version: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sciencea_afred`
--

-- --------------------------------------------------------

--
-- Table structure for table `authentication`
--

CREATE TABLE IF NOT EXISTS `authentication` (
  `user` char(5) NOT NULL,
  `password` char(40) NOT NULL,
  PRIMARY KEY (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE IF NOT EXISTS `inventory` (
  `inventory_id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `research` varchar(100) NOT NULL,
  `institution` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `does` text NOT NULL,
  `add_info` text NOT NULL,
  `keywords` varchar(100) NOT NULL,
  `contact_name` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `department` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telephone` char(10) NOT NULL,
  `list` int(1) NOT NULL DEFAULT '0',
  `date_posted` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `valid` int(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`inventory_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=259 ;

-- --------------------------------------------------------

--
-- Table structure for table `lab_list`
--

CREATE TABLE IF NOT EXISTS `lab_list` (
  `lab_list_id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `inventory_id` int(255) unsigned NOT NULL,
  `lab` varchar(100) NOT NULL,
  `fee` int(1) NOT NULL,
  `guest` int(1) NOT NULL,
  `host` int(1) NOT NULL,
  `descr` text NOT NULL,
  PRIMARY KEY (`lab_list_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=244 ;

-- --------------------------------------------------------

--
-- Table structure for table `validation`
--

CREATE TABLE IF NOT EXISTS `validation` (
  `validation_id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `inventory_id` int(255) unsigned NOT NULL,
  `validation_key` char(100) NOT NULL,
  PRIMARY KEY (`validation_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=40 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
