-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.6.12-log - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             8.1.0.4615
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping database structure for code_igniter
CREATE DATABASE IF NOT EXISTS `code_igniter` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `code_igniter`;


-- Dumping structure for table code_igniter.health_aspect
CREATE TABLE IF NOT EXISTS `health_aspect` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(4) NOT NULL DEFAULT '1',
  `date_added` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK1_aspect_user` (`user_id`),
  CONSTRAINT `FK1_aspect_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table code_igniter.health_aspect: ~0 rows (approximately)
/*!40000 ALTER TABLE `health_aspect` DISABLE KEYS */;
REPLACE INTO `health_aspect` (`id`, `name`, `user_id`, `published`, `date_added`) VALUES
	(1, 'stomach', 1, 1, '2014-03-19 11:16:21');
/*!40000 ALTER TABLE `health_aspect` ENABLE KEYS */;


-- Dumping structure for table code_igniter.health_aspect_values
CREATE TABLE IF NOT EXISTS `health_aspect_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `health_aspect_id` int(11) NOT NULL DEFAULT '0',
  `date` date DEFAULT NULL,
  `value` int(11) NOT NULL DEFAULT '0',
  `comment` varchar(200) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK1_values_aspect` (`health_aspect_id`),
  CONSTRAINT `FK1_values_aspect` FOREIGN KEY (`health_aspect_id`) REFERENCES `health_aspect` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table code_igniter.health_aspect_values: ~0 rows (approximately)
/*!40000 ALTER TABLE `health_aspect_values` DISABLE KEYS */;
/*!40000 ALTER TABLE `health_aspect_values` ENABLE KEYS */;


-- Dumping structure for table code_igniter.hello
CREATE TABLE IF NOT EXISTS `hello` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table code_igniter.hello: ~0 rows (approximately)
/*!40000 ALTER TABLE `hello` DISABLE KEYS */;
REPLACE INTO `hello` (`id`, `message`) VALUES
	(1, 'How many roads');
/*!40000 ALTER TABLE `hello` ENABLE KEYS */;


-- Dumping structure for table code_igniter.meal
CREATE TABLE IF NOT EXISTS `meal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `display_order` int(11) NOT NULL DEFAULT '1',
  `published` tinyint(4) NOT NULL DEFAULT '1',
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK1_meals_user` (`user_id`),
  CONSTRAINT `FK1_meals_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Dumping data for table code_igniter.meal: ~4 rows (approximately)
/*!40000 ALTER TABLE `meal` DISABLE KEYS */;
REPLACE INTO `meal` (`id`, `name`, `user_id`, `display_order`, `published`, `date_added`) VALUES
	(1, 'breakfast', 1, 1, 1, '0000-00-00 00:00:00'),
	(2, 'lunch', 1, 1, 1, '0000-00-00 00:00:00'),
	(3, 'dinner', 1, 1, 1, '0000-00-00 00:00:00'),
	(4, 'other', 1, 1, 1, '0000-00-00 00:00:00');
/*!40000 ALTER TABLE `meal` ENABLE KEYS */;


-- Dumping structure for table code_igniter.meal_values
CREATE TABLE IF NOT EXISTS `meal_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meal_id` int(11) NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `value` int(11) NOT NULL DEFAULT '0',
  `comment` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table code_igniter.meal_values: ~0 rows (approximately)
/*!40000 ALTER TABLE `meal_values` DISABLE KEYS */;
/*!40000 ALTER TABLE `meal_values` ENABLE KEYS */;


-- Dumping structure for table code_igniter.user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `password` varchar(50) NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table code_igniter.user: ~0 rows (approximately)
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
REPLACE INTO `user` (`id`, `firstname`, `lastname`, `email`, `created`, `last_login`, `password`) VALUES
	(1, 'Joe', 'Cape', 'joe.sc.cape@gmail.com', '2014-03-19 00:00:00', '2014-03-19 00:00:00', 'aerfharwlg');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
