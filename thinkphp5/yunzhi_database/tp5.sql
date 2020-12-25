/*
 Navicat MySQL Data Transfer

 Source Server         : 3307
 Source Server Type    : MySQL
 Source Server Version : 50732
 Source Host           : localhost:3307
 Source Schema         : tp5

 Target Server Type    : MySQL
 Target Server Version : 50732
 File Encoding         : 65001

 Date: 25/12/2020 13:14:39
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for yunzhi_classroom
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_classroom`;
CREATE TABLE `yunzhi_classroom` (
  `seat_map_id` int(11) NOT NULL,
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT '' COMMENT '教室编号',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `course_id` int(11) NOT NULL DEFAULT '0',
  `begin_time` datetime DEFAULT NULL COMMENT '该教室签到起始时间',
  `out_time` datetime DEFAULT NULL COMMENT '该教室签到截止时间',
  PRIMARY KEY (`id`,`seat_map_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of yunzhi_classroom
-- ----------------------------
BEGIN;
INSERT INTO `yunzhi_classroom` VALUES (1, 1, 'A101', 0, 0, 0, NULL, NULL);
INSERT INTO `yunzhi_classroom` VALUES (1, 2, 'A102', 0, 0, 0, NULL, NULL);
COMMIT;

-- ----------------------------
-- Table structure for yunzhi_course
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_course`;
CREATE TABLE `yunzhi_course` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  `teacher_id` int(11) NOT NULL,
  `student_num` int(11) DEFAULT NULL,
  `usmix` int(11) NOT NULL DEFAULT '50',
  `courseup` int(11) NOT NULL DEFAULT '100',
  `begincougrade` int(11) NOT NULL,
  `onceusgrade` int(11) NOT NULL DEFAULT '2',
  `resigternum` int(11) NOT NULL,
  `term_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of yunzhi_course
-- ----------------------------
BEGIN;
INSERT INTO `yunzhi_course` VALUES (1, 'thinkphp5入门实例', 0, 1603111209, 4, 0, 40, 100, 50, 2, 1, 1);
INSERT INTO `yunzhi_course` VALUES (2, 'angularjs入门实例', 0, 1599894922, 4, 0, 50, 100, 49, 2, 50, 2);
INSERT INTO `yunzhi_course` VALUES (3, '高等数学', 1594643989, 1608598806, 1, 0, 80, 100, 30, 2, 1, 1);
INSERT INTO `yunzhi_course` VALUES (4, '计算机组成原理', 1599895144, 1599895144, 1, 0, 50, 100, 25, 2, 50, 2);
INSERT INTO `yunzhi_course` VALUES (29, '离散数学1', 1608599673, 1608599802, 1, 0, 50, 100, 0, 2, 0, 1);
COMMIT;

-- ----------------------------
-- Table structure for yunzhi_course_student
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_course_student`;
CREATE TABLE `yunzhi_course_student` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=301 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of yunzhi_course_student
-- ----------------------------
BEGIN;
INSERT INTO `yunzhi_course_student` VALUES (298, 1, 92, 0, 0);
INSERT INTO `yunzhi_course_student` VALUES (299, 3, 93, 0, 0);
INSERT INTO `yunzhi_course_student` VALUES (300, 3, 94, 0, 0);
COMMIT;

-- ----------------------------
-- Table structure for yunzhi_grade
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_grade`;
CREATE TABLE `yunzhi_grade` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `coursegrade` int(11) DEFAULT NULL,
  `usgrade` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `allgrade` int(11) DEFAULT NULL,
  `resigternum` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for yunzhi_gradeaod
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_gradeaod`;
CREATE TABLE `yunzhi_gradeaod` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `aodname` varchar(255) NOT NULL,
  `aodnum` int(11) NOT NULL,
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `course_id` int(11) NOT NULL,
  `aodid` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of yunzhi_gradeaod
-- ----------------------------
BEGIN;
INSERT INTO `yunzhi_gradeaod` VALUES (1, '上课睡觉', -2, '2020-10-10 10:27:16', '2020-10-10 10:27:20', 3, 0);
COMMIT;

-- ----------------------------
-- Table structure for yunzhi_seat
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_seat`;
CREATE TABLE `yunzhi_seat` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `classroom_id` int(11) NOT NULL DEFAULT '0',
  `x` int(11) NOT NULL DEFAULT '0',
  `y` int(11) NOT NULL DEFAULT '0',
  `isseat` tinyint(2) NOT NULL DEFAULT '0',
  `isseated` tinyint(2) NOT NULL DEFAULT '0',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `student_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

-- ----------------------------
-- Records of yunzhi_seat
-- ----------------------------
BEGIN;
INSERT INTO `yunzhi_seat` VALUES (1, 1, 1, 1, 1, 1, 0, 0, 30);
INSERT INTO `yunzhi_seat` VALUES (8, 0, 0, 0, 0, 0, 0, 0, 1);
COMMIT;

-- ----------------------------
-- Table structure for yunzhi_seat_map
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_seat_map`;
CREATE TABLE `yunzhi_seat_map` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '' COMMENT '名称',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of yunzhi_seat_map
-- ----------------------------
BEGIN;
INSERT INTO `yunzhi_seat_map` VALUES (5, '', 0, 0);
COMMIT;

-- ----------------------------
-- Table structure for yunzhi_student
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_student`;
CREATE TABLE `yunzhi_student` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '' COMMENT '姓名',
  `num` varchar(40) NOT NULL DEFAULT '',
  `sex` tinyint(2) NOT NULL DEFAULT '0',
  `email` varchar(40) NOT NULL DEFAULT '',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=95 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of yunzhi_student
-- ----------------------------
BEGIN;
INSERT INTO `yunzhi_student` VALUES (89, '孟鑫燕', '195717', 0, '1580380768@qq.com', 1608599879, 1608599879);
INSERT INTO `yunzhi_student` VALUES (90, '孟鑫燕', '195717', 1, '1580380768@qq.com', 1608600206, 1608600206);
INSERT INTO `yunzhi_student` VALUES (91, '郝泽龙', '195717', 0, '1580380768@qq.com', 1608601602, 1608601602);
INSERT INTO `yunzhi_student` VALUES (92, '郝泽龙', '195717', 0, '1580380768@qq.com', 1608601653, 1608601653);
INSERT INTO `yunzhi_student` VALUES (93, '孟鑫燕', '195717', 0, '1580380768@qq.com', 1608601662, 1608601662);
INSERT INTO `yunzhi_student` VALUES (94, '赵雯丽', '195717', 1, 'zhaoliu@yunzhi.club', 1608601672, 1608601672);
INSERT INTO `yunzhi_student` VALUES (87, '郝泽龙', '195717', 0, '1580380768@qq.com', 1605946593, 1605946593);
INSERT INTO `yunzhi_student` VALUES (88, '赵雯丽', '195713', 0, '1580380768@qq.com', 1605946692, 1605946692);
INSERT INTO `yunzhi_student` VALUES (86, '段声望', '195717', 1, 'zhaoliu@yunzhi.club', 1605946069, 1608518168);
INSERT INTO `yunzhi_student` VALUES (84, '郝泽龙', '195717', 0, '1580380768@qq.com', 1605793317, 1605793317);
INSERT INTO `yunzhi_student` VALUES (85, '许一普', '195759', 0, '15383011312@qq.com', 1605940325, 1605940325);
COMMIT;

-- ----------------------------
-- Table structure for yunzhi_teacher
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_teacher`;
CREATE TABLE `yunzhi_teacher` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '姓名',
  `password` varchar(40) NOT NULL DEFAULT '',
  `username` varchar(16) NOT NULL COMMENT '用户名',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of yunzhi_teacher
-- ----------------------------
BEGIN;
INSERT INTO `yunzhi_teacher` VALUES (2, '张老师', '5f383784a8ce262fa222357d503768412ee75518', 'lisi', 1594344872, 1594775272);
INSERT INTO `yunzhi_teacher` VALUES (1, '李老师', '5f383784a8ce262fa222357d503768412ee75518', 'zhangsan', 0, 0);
INSERT INTO `yunzhi_teacher` VALUES (4, '王老师', '5f383784a8ce262fa222357d503768412ee75518', 'wangwu', 1594428961, 1594690704);
INSERT INTO `yunzhi_teacher` VALUES (5, '赵老师', '5f383784a8ce262fa222357d503768412ee75518', 'haolaoshi', 1594632937, 1594687962);
INSERT INTO `yunzhi_teacher` VALUES (6, '何老师', '5f383784a8ce262fa222357d503768412ee75518', 'ligang', 1594642366, 1594643065);
INSERT INTO `yunzhi_teacher` VALUES (8, '陈老师', '5f383784a8ce262fa222357d503768412ee75518', '阿斯顿', 1594696474, 1594696474);
INSERT INTO `yunzhi_teacher` VALUES (19, '郝老师', '5f383784a8ce262fa222357d503768412ee75518', 'haozelong', 1594950661, 1594950661);
COMMIT;

-- ----------------------------
-- Table structure for yunzhi_term
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_term`;
CREATE TABLE `yunzhi_term` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `app` int(1) NOT NULL,
  `create_time` time(6) DEFAULT NULL,
  `update_time` time(6) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of yunzhi_term
-- ----------------------------
BEGIN;
INSERT INTO `yunzhi_term` VALUES (1, '2020春', 1, '11:18:20.000000', '11:18:24.000000');
INSERT INTO `yunzhi_term` VALUES (2, '2019秋', 0, '11:18:44.000000', '11:18:46.000000');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
