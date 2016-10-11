# Sequel Pro dump
# Version 2492
# http://code.google.com/p/sequel-pro
#
# Host: aa1n4djkebhleoj.c3fwzffy8wrd.ap-southeast-1.rds.amazonaws.com (MySQL 5.5.37-log)
# Database: ebdb
# Generation Time: 2014-08-25 20:09:10 +0000
# ************************************************************

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table admins
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admins`;

CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` (`id`,`username`,`fullname`,`password`,`status`,`created`,`modified`)
VALUES
	(1,'admin',NULL,'06bac9a5c873bb82d43a13dda7c83380b74d851f',1,'2014-02-06 04:09:01','2014-02-06 04:09:01');

/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table brand_types
# ------------------------------------------------------------

DROP TABLE IF EXISTS `brand_types`;

CREATE TABLE `brand_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

LOCK TABLES `brand_types` WRITE;
/*!40000 ALTER TABLE `brand_types` DISABLE KEYS */;
INSERT INTO `brand_types` (`id`,`name`)
VALUES
	(1,'Package'),
	(2,'Packet'),
	(3,'Can'),
	(4,'Bag'),
	(5,'Piece');

/*!40000 ALTER TABLE `brand_types` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table brands
# ------------------------------------------------------------

DROP TABLE IF EXISTS `brands`;

CREATE TABLE `brands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `thebrand_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `brand_type_id` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

LOCK TABLES `brands` WRITE;
/*!40000 ALTER TABLE `brands` DISABLE KEYS */;
INSERT INTO `brands` (`id`,`thebrand_id`,`name`,`brand_type_id`,`status`,`created`,`modified`)
VALUES
	(1,1,'Milo 3 in 1',3,1,'2014-02-09 13:55:39','2014-02-18 15:10:10'),
	(2,1,'Milo tin 100ml',5,1,'2014-02-13 00:00:00','2014-02-18 15:10:04'),
	(3,2,'Nescafe Rich',1,1,'2014-02-13 00:00:00','2014-02-18 15:09:53'),
	(4,2,'Nestle Branded Stuff',1,1,'2014-02-18 15:11:13','2014-03-13 12:09:05');

/*!40000 ALTER TABLE `brands` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table card_types
# ------------------------------------------------------------

DROP TABLE IF EXISTS `card_types`;

CREATE TABLE `card_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

LOCK TABLES `card_types` WRITE;
/*!40000 ALTER TABLE `card_types` DISABLE KEYS */;
INSERT INTO `card_types` (`id`,`name`,`status`,`created`,`modified`)
VALUES
	(1,'Platinum Visa',1,NULL,NULL),
	(2,'Gold Visa',1,'2014-08-25 18:13:36','2014-08-25 18:13:36'),
	(3,'Silver Visa',1,'2014-08-25 18:13:46','2014-08-25 18:13:46'),
	(4,'Platinum Mastercard',1,'2014-08-25 18:13:55','2014-08-25 18:13:55'),
	(5,'Gold Mastercard',1,'2014-08-25 18:14:05','2014-08-25 18:14:05'),
	(6,'Silver Mastercard',1,'2014-08-25 18:14:14','2014-08-25 18:14:14');

/*!40000 ALTER TABLE `card_types` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table checkin_logs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `checkin_logs`;

CREATE TABLE `checkin_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `supervisor_id` int(11) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `schedule_id` int(11) DEFAULT NULL,
  `venue_id` int(11) DEFAULT NULL,
  `checkin_time` datetime DEFAULT NULL,
  `checkout_time` datetime DEFAULT NULL,
  `meet_count` int(4) DEFAULT NULL,
  `stock_checkin` int(4) DEFAULT NULL,
  `stock_checkout` int(4) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table cms_menus
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cms_menus`;

CREATE TABLE `cms_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `sort` int(2) DEFAULT NULL,
  `superOnly` int(1) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

LOCK TABLES `cms_menus` WRITE;
/*!40000 ALTER TABLE `cms_menus` DISABLE KEYS */;
INSERT INTO `cms_menus` (`id`,`name`,`url`,`sort`,`superOnly`,`status`)
VALUES
	(1,'Region','Regions/Index',1,1,2),
	(2,'Supervisors','Supervisors/Index',8,1,2),
	(3,'Users','Users/index',10,1,2),
	(4,'Zone','Zones/Index',2,1,2),
	(5,'Venues','Venues/Index',3,1,2),
	(9,'Venue Type','VenueTypes/Index',4,1,2),
	(10,'Supervisor Types','SupervisorTypes/Index',10,1,2),
	(13,'Questioner','',20,1,2),
	(14,'Card Type','CardTypes/Index',21,0,2),
	(15,'Customers','Customers/Index',22,0,2);

/*!40000 ALTER TABLE `cms_menus` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table cms_submenus
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cms_submenus`;

CREATE TABLE `cms_submenus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cms_menu_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

LOCK TABLES `cms_submenus` WRITE;
/*!40000 ALTER TABLE `cms_submenus` DISABLE KEYS */;
INSERT INTO `cms_submenus` (`id`,`cms_menu_id`,`name`,`url`,`status`)
VALUES
	(1,13,'Questioner Campaign','QuestionerCampaign/index',2),
	(2,13,'Quesioner Question','QuestionerQuestion/Index',2),
	(3,13,'Questioner Answer','QuestionerAnswer/Index',2),
	(4,13,'Questioner Result','QuestionerResultLog/Index',2);

/*!40000 ALTER TABLE `cms_submenus` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table contents
# ------------------------------------------------------------

DROP TABLE IF EXISTS `contents`;

CREATE TABLE `contents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(100) NOT NULL,
  `model_id` int(11) NOT NULL,
  `type` varchar(100) NOT NULL,
  `host` varchar(255) NOT NULL,
  `url` varchar(100) NOT NULL,
  `size` varchar(100) DEFAULT NULL,
  `cloud` int(1) NOT NULL DEFAULT '0' COMMENT '0=Masih di server loka,1=Sudah di server cloud',
  `mime_type` varchar(100) NOT NULL,
  `path` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cloud` (`cloud`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

LOCK TABLES `contents` WRITE;
/*!40000 ALTER TABLE `contents` DISABLE KEYS */;
INSERT INTO `contents` (`id`,`model`,`model_id`,`type`,`host`,`url`,`size`,`cloud`,`mime_type`,`path`,`created`,`modified`)
VALUES
	(1,'Venue',1,'original','http://192.168.0.34/nestle-promoter/cms/','contents/Venue/1/1_original.jpg',NULL,1,'image/jpeg','D:/abyfolder/xampp/htdocs/nestle-promoter/cms/app/webroot/contents/Venue/1/1_original.jpg',NULL,NULL),
	(2,'Venue',2,'original','http://192.168.0.34/nestle-promoter/cms/','contents/Venue/2/2_original.jpg',NULL,1,'image/jpeg','D:/abyfolder/xampp/htdocs/nestle-promoter/cms/app/webroot/contents/Venue/2/2_original.jpg',NULL,NULL),
	(3,'Venue',3,'original','http://192.168.0.34/nestle-promoter/cms/','contents/Venue/3/3_original.jpg',NULL,1,'image/jpeg','D:/abyfolder/xampp/htdocs/nestle-promoter/cms/app/webroot/contents/Venue/3/3_original.jpg',NULL,NULL),
	(4,'Venue',4,'original','http://192.168.0.34/nestle-promoter/cms/','contents/Venue/4/4_original.jpg',NULL,1,'image/jpeg','D:/abyfolder/xampp/htdocs/nestle-promoter/cms/app/webroot/contents/Venue/4/4_original.jpg',NULL,NULL),
	(5,'User',1,'original','http://192.168.0.34/nestle-promoter/cms/','contents/User/1/1_original.jpg',NULL,1,'image/jpeg','D:/abyfolder/xampp/htdocs/nestle-promoter/cms/app/webroot/contents/User/1/1_original.jpg',NULL,NULL),
	(29,'QuestionerAnswer',4,'','29','528ffd64e8e44ece5800022b_casuarinas-house-metropolis_portada-1000x658.jpg','135980',1,'image/jpeg',NULL,'2014-06-26 16:43:22','2014-06-27 12:10:41'),
	(30,'Customer',2,'ic_photo','https://s3-ap-southeast-1.amazonaws.com/coda-d/','contents/Customer/2/2_ic_photo.jpg',NULL,1,'application/octet-stream','contents/Customer/2/2_ic_photo.jpg','2014-08-25 17:41:45','2014-08-25 17:41:45'),
	(31,'Customer',2,'cust_photo','https://s3-ap-southeast-1.amazonaws.com/coda-d/','contents/Customer/2/2_cust_photo.jpg',NULL,1,'application/octet-stream','contents/Customer/2/2_cust_photo.jpg','2014-08-25 17:41:45','2014-08-25 17:41:45'),
	(32,'Customer',2,'signature','https://s3-ap-southeast-1.amazonaws.com/coda-d/','contents/Customer/2/2_signature.png',NULL,1,'application/octet-stream','contents/Customer/2/2_signature.png','2014-08-25 17:41:45','2014-08-25 17:41:45');

/*!40000 ALTER TABLE `contents` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table customers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `customers`;

CREATE TABLE `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(200) DEFAULT NULL,
  `last_name` varchar(200) DEFAULT NULL,
  `ic_number` varchar(30) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `place_of_birth` varchar(255) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `card_type_id` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `venue_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ic_number` (`ic_number`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` (`id`,`first_name`,`last_name`,`ic_number`,`date_of_birth`,`place_of_birth`,`phone_number`,`email`,`card_type_id`,`status`,`created`,`modified`,`user_id`,`venue_id`)
VALUES
	(1,'Kossa','Prasena','',NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL),
	(2,'aby','fajar','181818991322','2014-08-25','perdana','0812543364','abyfajar@gmail.com',5,1,'2014-08-25 17:41:44','2014-08-25 17:41:44',1,NULL);

/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table news
# ------------------------------------------------------------

DROP TABLE IF EXISTS `news`;

CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `news_type_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` int(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
INSERT INTO `news` (`id`,`news_type_id`,`name`,`address`,`title`,`description`,`status`,`created`,`modified`)
VALUES
	(1,2,'Antologi tunggal','Jl. Taman ismail marzuki','','',1,'2014-06-26 14:23:20','2014-06-26 14:23:32');

/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table news_categories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `news_categories`;

CREATE TABLE `news_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

LOCK TABLES `news_categories` WRITE;
/*!40000 ALTER TABLE `news_categories` DISABLE KEYS */;
INSERT INTO `news_categories` (`id`,`name`,`status`)
VALUES
	(1,'Politik',1),
	(2,'Technology',1),
	(3,'Ekonomi',1),
	(4,'Budaya',1),
	(5,'Kewarganegaraan',0),
	(6,'Hukum',1),
	(7,'Pendidikan',1);

/*!40000 ALTER TABLE `news_categories` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table questioner_answers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `questioner_answers`;

CREATE TABLE `questioner_answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `questioner_question_id` int(11) DEFAULT NULL,
  `text_answer` int(1) NOT NULL DEFAULT '0',
  `have_child` int(1) NOT NULL DEFAULT '0',
  `parent_id` int(11) DEFAULT NULL,
  `answer` varchar(200) DEFAULT NULL,
  `point` smallint(2) DEFAULT NULL,
  `description` text,
  `sort` int(3) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

LOCK TABLES `questioner_answers` WRITE;
/*!40000 ALTER TABLE `questioner_answers` DISABLE KEYS */;
INSERT INTO `questioner_answers` (`id`,`questioner_question_id`,`text_answer`,`have_child`,`parent_id`,`answer`,`point`,`description`,`sort`,`status`,`created`,`modified`)
VALUES
	(1,1,0,1,NULL,'Seberapa Sering posting sehari',NULL,'',1,1,'2014-05-26 16:45:44','2014-05-26 16:46:46'),
	(2,1,0,0,1,'Lebih dari 10',NULL,'',1,1,'2014-05-26 16:46:04','2014-05-26 16:46:04'),
	(3,1,0,0,1,'5 - 10 Post',NULL,'',1,1,'2014-05-26 16:46:32','2014-05-26 16:46:32'),
	(4,1,1,0,NULL,'Hello this is it',3,'<p><strong>hello world</strong></p>\r\n<p>pevita peron pearce.&nbsp;</p>\r\n<p>cihuy bgt dah</p>',1,1,'2014-06-26 14:24:33','2014-06-27 12:30:19');

/*!40000 ALTER TABLE `questioner_answers` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table questioner_campaigns
# ------------------------------------------------------------

DROP TABLE IF EXISTS `questioner_campaigns`;

CREATE TABLE `questioner_campaigns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `status` int(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

LOCK TABLES `questioner_campaigns` WRITE;
/*!40000 ALTER TABLE `questioner_campaigns` DISABLE KEYS */;
INSERT INTO `questioner_campaigns` (`id`,`name`,`description`,`status`,`created`,`modified`)
VALUES
	(1,'Gentlement','',1,'2014-05-26 16:44:19','2014-05-26 16:44:19');

/*!40000 ALTER TABLE `questioner_campaigns` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table questioner_questions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `questioner_questions`;

CREATE TABLE `questioner_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `questioner_campaign_id` int(11) NOT NULL,
  `question` varchar(200) DEFAULT NULL,
  `description` text,
  `multiple_choices` int(1) NOT NULL DEFAULT '0',
  `sort` int(3) NOT NULL DEFAULT '1',
  `status` int(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `questioner_campaign_id` (`questioner_campaign_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

LOCK TABLES `questioner_questions` WRITE;
/*!40000 ALTER TABLE `questioner_questions` DISABLE KEYS */;
INSERT INTO `questioner_questions` (`id`,`questioner_campaign_id`,`question`,`description`,`multiple_choices`,`sort`,`status`,`created`,`modified`)
VALUES
	(1,1,'Test Question','hello world',1,1,1,'2014-05-26 16:44:39','2014-05-26 16:44:39');

/*!40000 ALTER TABLE `questioner_questions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table questioner_result_logs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `questioner_result_logs`;

CREATE TABLE `questioner_result_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `questioner_campaign_id` int(11) DEFAULT NULL,
  `questioner_question_id` int(11) DEFAULT NULL,
  `questioner_answer_id` int(11) DEFAULT NULL,
  `questioner_answer_text` text,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table regions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `regions`;

CREATE TABLE `regions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

LOCK TABLES `regions` WRITE;
/*!40000 ALTER TABLE `regions` DISABLE KEYS */;
INSERT INTO `regions` (`id`,`name`,`status`)
VALUES
	(3,'Jakarta',1);

/*!40000 ALTER TABLE `regions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table shifts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `shifts`;

CREATE TABLE `shifts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

LOCK TABLES `shifts` WRITE;
/*!40000 ALTER TABLE `shifts` DISABLE KEYS */;
INSERT INTO `shifts` (`id`,`start_time`,`end_time`,`status`,`created`,`modified`)
VALUES
	(1,'11:00:00','20:00:00',1,'2014-02-06 12:19:19','2014-02-06 12:19:23');

/*!40000 ALTER TABLE `shifts` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table supervisor_types
# ------------------------------------------------------------

DROP TABLE IF EXISTS `supervisor_types`;

CREATE TABLE `supervisor_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

LOCK TABLES `supervisor_types` WRITE;
/*!40000 ALTER TABLE `supervisor_types` DISABLE KEYS */;
INSERT INTO `supervisor_types` (`id`,`name`,`status`)
VALUES
	(1,'Agency Administrator',1),
	(2,'Nestle Supervisor',1);

/*!40000 ALTER TABLE `supervisor_types` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table supervisors
# ------------------------------------------------------------

DROP TABLE IF EXISTS `supervisors`;

CREATE TABLE `supervisors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `supervisor_type_id` int(11) NOT NULL,
  `agency_name` varchar(100) NOT NULL,
  `agency_address` text NOT NULL,
  `agency_contact_no` varchar(15) NOT NULL,
  `status` int(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

LOCK TABLES `supervisors` WRITE;
/*!40000 ALTER TABLE `supervisors` DISABLE KEYS */;
INSERT INTO `supervisors` (`id`,`name`,`email`,`username`,`password`,`supervisor_type_id`,`agency_name`,`agency_address`,`agency_contact_no`,`status`,`created`,`modified`)
VALUES
	(1,'Coda Agency',NULL,'coda_agency','5801b8b4d1fb28ff797639a93a1e7684cefbd505',1,'Coda Technology','Coda','123123123123',1,'2014-02-09 13:55:18','2014-02-09 13:55:18');

/*!40000 ALTER TABLE `supervisors` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `nip` varchar(11) NOT NULL,
  `type` int(1) NOT NULL DEFAULT '2',
  `supervisor_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `phone_2` varchar(15) DEFAULT NULL,
  `address` text,
  `email` varchar(100) DEFAULT NULL,
  `im` varchar(100) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='jenis type : 1 = supervisor 2 = spg';

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`,`username`,`password`,`nip`,`type`,`supervisor_id`,`name`,`phone`,`phone_2`,`address`,`email`,`im`,`status`,`created`,`modified`)
VALUES
	(1,'spg001','28121883d0bf7c410c714db7a9953e8d1ccac6b6','1234567',2,1,'Spg 001','123123','123123','13123','','123',1,'2014-02-06 05:33:49','2014-02-10 13:06:57'),
	(3,'yuska','382334323a380ceff3620d214f3fa5a82cb7e32a','1234',2,1,'yuska yudistira','12345678','','','yuska@aniseasia.com','',1,'2014-02-10 19:04:19','2014-02-10 19:04:19'),
	(4,'kossa','a3f66b9db3db616ad41085f3ab9a17523615bb65','123',2,1,'Kossa Audi Prasena','123','123','123','kossa@coda-technology.com','123',1,'2014-02-10 22:33:33','2014-02-12 17:45:29');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table venue_types
# ------------------------------------------------------------

DROP TABLE IF EXISTS `venue_types`;

CREATE TABLE `venue_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

LOCK TABLES `venue_types` WRITE;
/*!40000 ALTER TABLE `venue_types` DISABLE KEYS */;
INSERT INTO `venue_types` (`id`,`name`,`status`)
VALUES
	(1,'Supermarket',1),
	(2,'Restaurants',1);

/*!40000 ALTER TABLE `venue_types` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table venues
# ------------------------------------------------------------

DROP TABLE IF EXISTS `venues`;

CREATE TABLE `venues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zone_id` int(11) DEFAULT NULL,
  `venue_type_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `address` text,
  `lat` varchar(100) NOT NULL DEFAULT '0',
  `lng` varchar(100) NOT NULL DEFAULT '0',
  `status` int(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

LOCK TABLES `venues` WRITE;
/*!40000 ALTER TABLE `venues` DISABLE KEYS */;
INSERT INTO `venues` (`id`,`zone_id`,`venue_type_id`,`name`,`address`,`lat`,`lng`,`status`,`created`,`modified`)
VALUES
	(2,7,2,'Plaza Indonesia','plaza indonesia, kav 1','-6.193376521585256','106.82232141494751',1,'2014-02-12 17:43:40','2014-02-12 17:43:40');

/*!40000 ALTER TABLE `venues` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table zones
# ------------------------------------------------------------

DROP TABLE IF EXISTS `zones`;

CREATE TABLE `zones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `region_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

LOCK TABLES `zones` WRITE;
/*!40000 ALTER TABLE `zones` DISABLE KEYS */;
INSERT INTO `zones` (`id`,`region_id`,`name`,`status`)
VALUES
	(7,3,'Jakarta Pusat',1);

/*!40000 ALTER TABLE `zones` ENABLE KEYS */;
UNLOCK TABLES;





/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
