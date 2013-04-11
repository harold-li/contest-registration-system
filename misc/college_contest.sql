/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50514
Source Host           : localhost:3306
Source Database       : JudgeOnline

Target Server Type    : MYSQL
Target Server Version : 50514
File Encoding         : 65001

Date: 2012-03-11 14:17:41
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `college_contest`
-- ----------------------------
DROP TABLE IF EXISTS `college_contest`;
CREATE TABLE `college_contest` (
  `tid` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(20) NOT NULL,
  `name1` varchar(30) NOT NULL,
  `stu_id1` varchar(8) NOT NULL,
  `college1` varchar(50) NOT NULL,
  `class1` varchar(20) NOT NULL,
  `gender1` varchar(10) NOT NULL,
  `contact1` varchar(100) NOT NULL,
  `name2` varchar(30) DEFAULT NULL,
  `stu_id2` varchar(8) DEFAULT NULL,
  `college2` varchar(50) DEFAULT NULL,
  `class2` varchar(20) DEFAULT NULL,
  `gender2` varchar(10) DEFAULT NULL,
  `contact2` varchar(100) DEFAULT NULL,
  `name3` varchar(30) DEFAULT NULL,
  `stu_id3` varchar(8) DEFAULT NULL,
  `college3` varchar(50) DEFAULT NULL,
  `class3` varchar(20) DEFAULT NULL,
  `gender3` varchar(10) DEFAULT NULL,
  `contact3` varchar(100) DEFAULT NULL,
  `notice` tinyint(1) NOT NULL DEFAULT '1',
  `comment` varchar(200) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created` int(11) NOT NULL,
  `changed` int(11) NOT NULL,
  `history` text,
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of college_contest
-- ----------------------------
