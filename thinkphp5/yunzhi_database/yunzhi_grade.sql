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

 Date: 25/12/2020 10:53:32
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for yunzhi_grade
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_grade`;
CREATE TABLE `yunzhi_grade`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NULL DEFAULT NULL,
  `course_id` int(11) NULL DEFAULT NULL,
  `coursegrade` int(11) NULL DEFAULT NULL,
  `usgrade` int(11) NULL DEFAULT NULL,
  `create_time` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `update_time` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `allgrade` int(11) NULL DEFAULT NULL,
  `resigternum` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 82 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of yunzhi_grade
-- ----------------------------
INSERT INTO `yunzhi_grade` VALUES (79, 92, 3, 61, 0, '2020-12-24 17:49:47', '0000-00-00 00:00:00', 30, 0);
INSERT INTO `yunzhi_grade` VALUES (80, 93, 3, 30, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 30, 0);
INSERT INTO `yunzhi_grade` VALUES (81, 94, 3, 30, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 30, 0);

SET FOREIGN_KEY_CHECKS = 1;
