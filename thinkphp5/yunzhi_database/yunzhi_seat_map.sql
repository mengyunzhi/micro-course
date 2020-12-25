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

 Date: 25/12/2020 10:53:51
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for yunzhi_seat_map
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_seat_map`;
CREATE TABLE `yunzhi_seat_map`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '名称',
  `create_time` date NOT NULL DEFAULT '0000-00-00',
  `update_time` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of yunzhi_seat_map
-- ----------------------------
INSERT INTO `yunzhi_seat_map` VALUES (5, '', '0000-00-00', '0000-00-00');

SET FOREIGN_KEY_CHECKS = 1;
