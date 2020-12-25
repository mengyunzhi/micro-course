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

 Date: 25/12/2020 10:53:26
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for yunzhi_course_student
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_course_student`;
CREATE TABLE `yunzhi_course_student`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 301 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of yunzhi_course_student
-- ----------------------------
INSERT INTO `yunzhi_course_student` VALUES (298, 1, 92, 0, 0);
INSERT INTO `yunzhi_course_student` VALUES (299, 3, 93, 0, 0);
INSERT INTO `yunzhi_course_student` VALUES (300, 3, 94, 0, 0);

SET FOREIGN_KEY_CHECKS = 1;
