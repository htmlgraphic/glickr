/*
 Navicat MySQL Data Transfer

 Source Server         : LOCAL
 Source Server Version : 50531
 Source Host           : localhost
 Source Database       : glickr

 Target Server Version : 50531
 File Encoding         : utf-8

 Date: 11/17/2013 21:23:58 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `flickr_cache`
-- ----------------------------
DROP TABLE IF EXISTS `flickr_cache`;
CREATE TABLE `flickr_cache` (
  `request` char(35) NOT NULL,
  `response` mediumtext NOT NULL,
  `expiration` datetime NOT NULL,
  KEY `request` (`request`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
