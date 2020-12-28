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

 Date: 28/12/2020 21:36:43
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for yunzhi_classroom
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_classroom`;
CREATE TABLE `yunzhi_classroom`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `seat_map_id` int(11) NOT NULL,
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '教室编号',
  `create_time` date NOT NULL DEFAULT '0000-00-00' COMMENT '创建时间',
  `update_time` date NOT NULL DEFAULT '0000-00-00' COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 34 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of yunzhi_classroom
-- ----------------------------
INSERT INTO `yunzhi_classroom` VALUES (1, 91, 'A101', '0000-00-00', '0000-00-00');
INSERT INTO `yunzhi_classroom` VALUES (2, 92, 'A102', '0000-00-00', '0000-00-00');
INSERT INTO `yunzhi_classroom` VALUES (3, 91, NULL, '0000-00-00', '0000-00-00');
INSERT INTO `yunzhi_classroom` VALUES (4, 91, NULL, '0000-00-00', '0000-00-00');
INSERT INTO `yunzhi_classroom` VALUES (5, 91, NULL, '0000-00-00', '0000-00-00');
INSERT INTO `yunzhi_classroom` VALUES (6, 91, NULL, '0000-00-00', '0000-00-00');
INSERT INTO `yunzhi_classroom` VALUES (7, 91, NULL, '0000-00-00', '0000-00-00');
INSERT INTO `yunzhi_classroom` VALUES (8, 91, NULL, '0000-00-00', '0000-00-00');
INSERT INTO `yunzhi_classroom` VALUES (9, 91, NULL, '0000-00-00', '0000-00-00');
INSERT INTO `yunzhi_classroom` VALUES (10, 91, NULL, '0000-00-00', '0000-00-00');
INSERT INTO `yunzhi_classroom` VALUES (11, 91, NULL, '0000-00-00', '0000-00-00');
INSERT INTO `yunzhi_classroom` VALUES (12, 91, NULL, '0000-00-00', '0000-00-00');
INSERT INTO `yunzhi_classroom` VALUES (13, 91, NULL, '0000-00-00', '0000-00-00');
INSERT INTO `yunzhi_classroom` VALUES (14, 91, NULL, '0000-00-00', '0000-00-00');
INSERT INTO `yunzhi_classroom` VALUES (15, 91, NULL, '0000-00-00', '0000-00-00');
INSERT INTO `yunzhi_classroom` VALUES (16, 91, NULL, '0000-00-00', '0000-00-00');
INSERT INTO `yunzhi_classroom` VALUES (17, 91, '在路上', '0000-00-00', '0000-00-00');
INSERT INTO `yunzhi_classroom` VALUES (18, 91, '在路上', '0000-00-00', '0000-00-00');
INSERT INTO `yunzhi_classroom` VALUES (19, 93, '1231231', '0000-00-00', '0000-00-00');
INSERT INTO `yunzhi_classroom` VALUES (20, 93, '1231231', '0000-00-00', '0000-00-00');
INSERT INTO `yunzhi_classroom` VALUES (21, 93, '1231231', '0000-00-00', '0000-00-00');
INSERT INTO `yunzhi_classroom` VALUES (22, 93, '1231231', '0000-00-00', '0000-00-00');
INSERT INTO `yunzhi_classroom` VALUES (23, 93, '1231231', '0000-00-00', '0000-00-00');
INSERT INTO `yunzhi_classroom` VALUES (24, 93, '', '0000-00-00', '0000-00-00');
INSERT INTO `yunzhi_classroom` VALUES (25, 93, '', '0000-00-00', '0000-00-00');
INSERT INTO `yunzhi_classroom` VALUES (26, 93, '', '0000-00-00', '0000-00-00');
INSERT INTO `yunzhi_classroom` VALUES (27, 93, '', '0000-00-00', '0000-00-00');
INSERT INTO `yunzhi_classroom` VALUES (28, 93, '', '0000-00-00', '0000-00-00');
INSERT INTO `yunzhi_classroom` VALUES (29, 93, '', '0000-00-00', '0000-00-00');
INSERT INTO `yunzhi_classroom` VALUES (30, 93, '', '0000-00-00', '0000-00-00');
INSERT INTO `yunzhi_classroom` VALUES (31, 93, '', '0000-00-00', '0000-00-00');
INSERT INTO `yunzhi_classroom` VALUES (32, 93, '2019秋', '0000-00-00', '0000-00-00');
INSERT INTO `yunzhi_classroom` VALUES (33, 93, '2019秋', '0000-00-00', '0000-00-00');

SET FOREIGN_KEY_CHECKS = 1;
