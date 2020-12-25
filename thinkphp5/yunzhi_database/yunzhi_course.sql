/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100113
 Source Host           : localhost:3306
 Source Schema         : tp5

 Target Server Type    : MySQL
 Target Server Version : 100113
 File Encoding         : 65001

 Date: 25/12/2020 10:53:19
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for yunzhi_course
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_course`;
CREATE TABLE `yunzhi_course`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `create_time` int(11) NOT NULL DEFAULT 0,
  `update_time` int(11) NOT NULL DEFAULT 0,
  `teacher_id` int(11) NOT NULL,
  `student_num` int(11) NULL DEFAULT NULL,
  `usmix` int(11) NOT NULL DEFAULT 50,
  `courseup` int(11) NOT NULL DEFAULT 100,
  `begincougrade` int(11) NOT NULL,
  `onceusgrade` int(11) NOT NULL DEFAULT 2,
  `resigternum` int(11) NOT NULL,
  `term_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 30 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of yunzhi_course
-- ----------------------------
INSERT INTO `yunzhi_course` VALUES (1, 'thinkphp5入门实例', 0, 1603111209, 4, 0, 40, 100, 50, 2, 1, 1);
INSERT INTO `yunzhi_course` VALUES (2, 'angularjs入门实例', 0, 1599894922, 4, 0, 50, 100, 49, 2, 50, 2);
INSERT INTO `yunzhi_course` VALUES (3, '高等数学', 1594643989, 1608598806, 1, 0, 80, 100, 30, 2, 1, 1);
INSERT INTO `yunzhi_course` VALUES (4, '计算机组成原理', 1599895144, 1599895144, 1, 0, 50, 100, 25, 2, 50, 2);
INSERT INTO `yunzhi_course` VALUES (29, '离散数学1', 1608599673, 1608599802, 1, 0, 50, 100, 0, 2, 0, 1);

SET FOREIGN_KEY_CHECKS = 1;
