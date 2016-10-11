

DROP TABLE IF EXISTS `029-dun-rawcircle`.`admins`;
DROP TABLE IF EXISTS `029-dun-rawcircle`.`card_types`;
DROP TABLE IF EXISTS `029-dun-rawcircle`.`cms_menus`;
DROP TABLE IF EXISTS `029-dun-rawcircle`.`cms_submenus`;
DROP TABLE IF EXISTS `029-dun-rawcircle`.`contents`;
DROP TABLE IF EXISTS `029-dun-rawcircle`.`customers`;
DROP TABLE IF EXISTS `029-dun-rawcircle`.`news`;
DROP TABLE IF EXISTS `029-dun-rawcircle`.`questioner_answers`;
DROP TABLE IF EXISTS `029-dun-rawcircle`.`questioner_campaigns`;
DROP TABLE IF EXISTS `029-dun-rawcircle`.`questioner_questions`;
DROP TABLE IF EXISTS `029-dun-rawcircle`.`questioner_result_logs`;
DROP TABLE IF EXISTS `029-dun-rawcircle`.`regions`;
DROP TABLE IF EXISTS `029-dun-rawcircle`.`settings`;
DROP TABLE IF EXISTS `029-dun-rawcircle`.`supervisor_types`;
DROP TABLE IF EXISTS `029-dun-rawcircle`.`supervisors`;
DROP TABLE IF EXISTS `029-dun-rawcircle`.`users`;
DROP TABLE IF EXISTS `029-dun-rawcircle`.`venue_types`;
DROP TABLE IF EXISTS `029-dun-rawcircle`.`venues`;
DROP TABLE IF EXISTS `029-dun-rawcircle`.`zones`;


CREATE TABLE `029-dun-rawcircle`.`admins` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`username` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`fullname` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`password` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`status` int(1) DEFAULT NULL,
	`created` datetime DEFAULT NULL,
	`modified` datetime DEFAULT NULL,	PRIMARY KEY  (`id`)) 	DEFAULT CHARSET=utf8,
	COLLATE=utf8_general_ci,
	ENGINE=MyISAM;

CREATE TABLE `029-dun-rawcircle`.`card_types` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`status` int(1) DEFAULT NULL,
	`created` datetime DEFAULT NULL,
	`modified` datetime DEFAULT NULL,	PRIMARY KEY  (`id`)) 	DEFAULT CHARSET=utf8,
	COLLATE=utf8_general_ci,
	ENGINE=MyISAM;

CREATE TABLE `029-dun-rawcircle`.`cms_menus` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`url` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`sort` int(2) DEFAULT NULL,
	`superOnly` int(1) NOT NULL,
	`status` int(1) DEFAULT 1 NOT NULL,	PRIMARY KEY  (`id`)) 	DEFAULT CHARSET=utf8,
	COLLATE=utf8_general_ci,
	ENGINE=InnoDB;

CREATE TABLE `029-dun-rawcircle`.`cms_submenus` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`cms_menu_id` int(11) NOT NULL,
	`name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`url` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`status` int(1) DEFAULT 1 NOT NULL,	PRIMARY KEY  (`id`)) 	DEFAULT CHARSET=utf8,
	COLLATE=utf8_general_ci,
	ENGINE=InnoDB;

CREATE TABLE `029-dun-rawcircle`.`contents` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`model` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`model_id` int(11) NOT NULL,
	`type` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`host` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`url` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`size` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`cloud` int(1) DEFAULT 0 NOT NULL COMMENT '0=Masih di server loka,1=Sudah di server cloud',
	`mime_type` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`created` datetime DEFAULT NULL,
	`modified` datetime DEFAULT NULL,	PRIMARY KEY  (`id`),
	KEY `cloud` (`cloud`)) 	DEFAULT CHARSET=utf8,
	COLLATE=utf8_general_ci,
	ENGINE=InnoDB;

CREATE TABLE `029-dun-rawcircle`.`customers` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`first_name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`last_name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`ic_number` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`date_of_birth` date DEFAULT NULL,
	`phone_number` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`email` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`card_type_id` int(11) DEFAULT NULL,
	`status` int(1) DEFAULT NULL,
	`created` datetime DEFAULT NULL,
	`modified` datetime DEFAULT NULL,
	`user_id` int(11) DEFAULT NULL,
	`venue_id` int(11) DEFAULT NULL,	PRIMARY KEY  (`id`),
	UNIQUE KEY `ic_number` (`ic_number`)) 	DEFAULT CHARSET=utf8,
	COLLATE=utf8_general_ci,
	ENGINE=MyISAM;

CREATE TABLE `029-dun-rawcircle`.`news` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`news_type_id` int(11) NOT NULL,
	`name` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`address` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`title` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`description` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`status` int(1) NOT NULL,
	`created` datetime NOT NULL,
	`modified` datetime NOT NULL,	PRIMARY KEY  (`id`)) 	DEFAULT CHARSET=latin1,
	COLLATE=latin1_swedish_ci,
	ENGINE=InnoDB;

CREATE TABLE `029-dun-rawcircle`.`questioner_answers` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`questioner_question_id` int(11) DEFAULT NULL,
	`text_answer` int(1) DEFAULT 0 NOT NULL,
	`have_child` int(1) DEFAULT 0 NOT NULL,
	`parent_id` int(11) DEFAULT NULL,
	`answer` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`point` int(2) DEFAULT NULL,
	`description` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`sort` int(3) DEFAULT NULL,
	`status` int(1) DEFAULT NULL,
	`created` datetime DEFAULT NULL,
	`modified` datetime DEFAULT NULL,	PRIMARY KEY  (`id`)) 	DEFAULT CHARSET=utf8,
	COLLATE=utf8_general_ci,
	ENGINE=MyISAM;

CREATE TABLE `029-dun-rawcircle`.`questioner_campaigns` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`description` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`status` int(1) DEFAULT 1 NOT NULL,
	`created` datetime DEFAULT NULL,
	`modified` datetime DEFAULT NULL,	PRIMARY KEY  (`id`)) 	DEFAULT CHARSET=utf8,
	COLLATE=utf8_general_ci,
	ENGINE=MyISAM;

CREATE TABLE `029-dun-rawcircle`.`questioner_questions` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`questioner_campaign_id` int(11) NOT NULL,
	`question` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`description` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`multiple_choices` int(1) DEFAULT 0 NOT NULL,
	`sort` int(3) DEFAULT 1 NOT NULL,
	`status` int(1) DEFAULT NULL,
	`created` datetime DEFAULT NULL,
	`modified` datetime DEFAULT NULL,	PRIMARY KEY  (`id`),
	KEY `questioner_campaign_id` (`questioner_campaign_id`)) 	DEFAULT CHARSET=utf8,
	COLLATE=utf8_general_ci,
	ENGINE=MyISAM;

CREATE TABLE `029-dun-rawcircle`.`questioner_result_logs` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`user_id` int(11) DEFAULT NULL,
	`questioner_campaign_id` int(11) DEFAULT NULL,
	`questioner_question_id` int(11) DEFAULT NULL,
	`questioner_answer_id` int(11) DEFAULT NULL,
	`questioner_answer_text` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`created` datetime DEFAULT NULL,	PRIMARY KEY  (`id`)) 	DEFAULT CHARSET=utf8,
	COLLATE=utf8_general_ci,
	ENGINE=MyISAM;

CREATE TABLE `029-dun-rawcircle`.`regions` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`status` int(1) DEFAULT NULL,	PRIMARY KEY  (`id`)) 	DEFAULT CHARSET=utf8,
	COLLATE=utf8_general_ci,
	ENGINE=MyISAM;

CREATE TABLE `029-dun-rawcircle`.`settings` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`site_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`site_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'djarum super mild' NOT NULL,
	`site_description` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`site_keywords` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`site_domain` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'coda-technology.dev' NOT NULL,
	`web_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'http://webmld.coda-technology.dev/' NOT NULL,
	`wap_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'http://mld.coda-technology.dev/' NOT NULL,
	`cms_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`path_content` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'D:/xampp/htdocs/mld-web/contents/' NOT NULL,
	`path_webroot` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`facebook_app_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`facebook_app_secret` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`bucket_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`aws_host` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`aws_host_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`aws_access_key` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`aws_secret_key` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,	PRIMARY KEY  (`id`)) 	DEFAULT CHARSET=utf8,
	COLLATE=utf8_general_ci,
	ENGINE=MyISAM;

CREATE TABLE `029-dun-rawcircle`.`supervisor_types` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`status` int(1) DEFAULT NULL,	PRIMARY KEY  (`id`)) 	DEFAULT CHARSET=utf8,
	COLLATE=utf8_general_ci,
	ENGINE=MyISAM;

CREATE TABLE `029-dun-rawcircle`.`supervisors` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`email` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`username` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`password` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`supervisor_type_id` int(11) NOT NULL,
	`agency_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`agency_address` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`agency_contact_no` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`status` int(1) NOT NULL,
	`created` datetime NOT NULL,
	`modified` datetime NOT NULL,	PRIMARY KEY  (`id`),
	UNIQUE KEY `username` (`username`)) 	DEFAULT CHARSET=utf8,
	COLLATE=utf8_general_ci,
	ENGINE=MyISAM;

CREATE TABLE `029-dun-rawcircle`.`users` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`username` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`nip` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`type` int(1) DEFAULT 2 NOT NULL,
	`supervisor_id` int(11) DEFAULT NULL,
	`name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`phone` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`phone_2` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`address` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`email` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`im` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`status` int(1) DEFAULT NULL,
	`created` datetime DEFAULT NULL,
	`modified` datetime DEFAULT NULL,	PRIMARY KEY  (`id`),
	UNIQUE KEY `username` (`username`)) 	DEFAULT CHARSET=utf8,
	COLLATE=utf8_general_ci,
	ENGINE=MyISAM;

CREATE TABLE `029-dun-rawcircle`.`venue_types` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`status` int(1) NOT NULL,	PRIMARY KEY  (`id`)) 	DEFAULT CHARSET=utf8,
	COLLATE=utf8_general_ci,
	ENGINE=MyISAM;

CREATE TABLE `029-dun-rawcircle`.`venues` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`zone_id` int(11) DEFAULT NULL,
	`venue_type_id` int(11) NOT NULL,
	`name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`address` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`lat` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '0' NOT NULL,
	`lng` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '0' NOT NULL,
	`status` int(1) DEFAULT NULL,
	`created` datetime DEFAULT NULL,
	`modified` datetime DEFAULT NULL,	PRIMARY KEY  (`id`)) 	DEFAULT CHARSET=utf8,
	COLLATE=utf8_general_ci,
	ENGINE=MyISAM;

CREATE TABLE `029-dun-rawcircle`.`zones` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`region_id` int(11) DEFAULT NULL,
	`name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`status` int(1) DEFAULT NULL,	PRIMARY KEY  (`id`)) 	DEFAULT CHARSET=utf8,
	COLLATE=utf8_general_ci,
	ENGINE=MyISAM;

