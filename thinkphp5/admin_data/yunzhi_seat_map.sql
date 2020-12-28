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

 Date: 28/12/2020 21:37:05
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for yunzhi_seat_map
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_seat_map`;
CREATE TABLE `yunzhi_seat_map`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_time` date NOT NULL DEFAULT '0000-00-00',
  `update_time` date NOT NULL DEFAULT '0000-00-00',
  `x_map` int(2) UNSIGNED NOT NULL,
  `y_map` int(2) UNSIGNED NOT NULL,
  `is_last` tinyint(1) UNSIGNED NULL DEFAULT 1,
  `is_first` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 98 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of yunzhi_seat_map
-- ----------------------------
INSERT INTO `yunzhi_seat_map` VALUES (97, '2016-09-16', '2016-09-16', 5, 5, 1, 0);
INSERT INTO `yunzhi_seat_map` VALUES (91, '0000-00-00', '2016-09-16', 9, 10, 0, 1);
INSERT INTO `yunzhi_seat_map` VALUES (96, '2016-09-16', '2016-09-16', 5, 5, 0, 0);

SET FOREIGN_KEY_CHECKS = 1;
