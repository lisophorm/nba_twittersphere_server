/*
Navicat MySQL Data Transfer

Source Server         : 221 root
Source Server Version : 50533
Source Host           : localhost:3306
Source Database       : twittersphere

Target Server Type    : MYSQL
Target Server Version : 50533
File Encoding         : 65001

Date: 2014-01-13 17:34:33
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for access
-- ----------------------------
DROP TABLE IF EXISTS `access`;
CREATE TABLE `access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(16) COLLATE utf8_unicode_ci DEFAULT '1',
  `password` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mysqldate` timestamp NULL DEFAULT NULL,
  `from_user` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `from_user_id` int(11) DEFAULT NULL,
  `real_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` varchar(512) COLLATE utf8_unicode_ci DEFAULT NULL,
  `geolat` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `geolong` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `iso_language_code` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `profile_image_url` text COLLATE utf8_unicode_ci,
  `to_user_id` int(24) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `twitter_id` (`password`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=32448 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for clients
-- ----------------------------
DROP TABLE IF EXISTS `clients`;
CREATE TABLE `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ipaddress` varchar(64) COLLATE utf8_unicode_ci DEFAULT '1',
  `lasttweet` int(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=32447 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for currenttweet
-- ----------------------------
DROP TABLE IF EXISTS `currenttweet`;
CREATE TABLE `currenttweet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `current_tweet` int(32) DEFAULT NULL,
  `session_id` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `session_id` (`session_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for instagram
-- ----------------------------
DROP TABLE IF EXISTS `instagram`;
CREATE TABLE `instagram` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ignore` int(2) DEFAULT '0',
  `tag` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `instagram_id` varchar(64) COLLATE utf8_unicode_ci DEFAULT '1',
  `likes` int(4) DEFAULT NULL,
  `src` text COLLATE utf8_unicode_ci,
  `thumb` text COLLATE utf8_unicode_ci,
  `url` text COLLATE utf8_unicode_ci,
  `date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `username` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `userpic` text COLLATE utf8_unicode_ci,
  `userfullname` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `caption` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `main` (`instagram_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=147461 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for instagram_limits
-- ----------------------------
DROP TABLE IF EXISTS `instagram_limits`;
CREATE TABLE `instagram_limits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `apikey` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `limit` int(11) DEFAULT NULL,
  `when` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=58103 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for instagram_messages
-- ----------------------------
DROP TABLE IF EXISTS `instagram_messages`;
CREATE TABLE `instagram_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `error_type` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `code` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `error_message` text COLLATE utf8_unicode_ci,
  `when` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=34249 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for published_tweets
-- ----------------------------
DROP TABLE IF EXISTS `published_tweets`;
CREATE TABLE `published_tweets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sourceId` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `session_id` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `twitter_id` (`sourceId`,`session_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=654891 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for tweets
-- ----------------------------
DROP TABLE IF EXISTS `tweets`;
CREATE TABLE `tweets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `flagged` varchar(5) COLLATE utf8_unicode_ci DEFAULT 'OK',
  `approved` varchar(5) COLLATE utf8_unicode_ci DEFAULT '1',
  `panic` int(3) DEFAULT NULL,
  `published` int(5) DEFAULT '0',
  `sourceId` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mysqldate` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sourceUserId` int(11) DEFAULT NULL,
  `real_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` varchar(512) COLLATE utf8_unicode_ci DEFAULT '0',
  `geolat` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `geolong` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `iso_language_code` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `profilePicture` text COLLATE utf8_unicode_ci,
  `hasPicture` int(2) DEFAULT NULL,
  `picture` text COLLATE utf8_unicode_ci,
  `to_user_id` int(24) unsigned DEFAULT NULL,
  `hasText` int(3) DEFAULT '1',
  `sweardebug` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `twitter_id` (`sourceId`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=654895 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
