/*
 Navicat Premium Data Transfer

 Source Server         : localhost_3306
 Source Server Type    : MySQL
 Source Server Version : 100113
 Source Host           : localhost:3306
 Source Schema         : tp5

 Target Server Type    : MySQL
 Target Server Version : 100113
 File Encoding         : 65001

 Date: 28/12/2020 21:36:52
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for yunzhi_seat
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_seat`;
CREATE TABLE `yunzhi_seat`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `x` int(11) NOT NULL DEFAULT 0,
  `y` int(11) NOT NULL DEFAULT 0,
  `is_seated` tinyint(1) NOT NULL DEFAULT 0,
  `create_time` date NOT NULL DEFAULT '0000-00-00',
  `update_time` date NOT NULL DEFAULT '0000-00-00',
  `is_seat` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `classroom_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 240847 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of yunzhi_seat
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
