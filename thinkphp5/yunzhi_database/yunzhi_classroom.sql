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

 Date: 25/12/2020 10:53:14
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for yunzhi_classroom
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_classroom`;
CREATE TABLE `yunzhi_classroom`  (
  `seat_map_id` int(11) NOT NULL,
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '教室编号',
  `create_time` date NOT NULL DEFAULT '0000-00-00' COMMENT '创建时间',
  `update_time` date NOT NULL DEFAULT '0000-00-00' COMMENT '更新时间',
  `course_id` int(11) NOT NULL DEFAULT 0,
  `begin_time` datetime(0) NULL DEFAULT NULL COMMENT '该教室签到起始时间',
  `out_time` datetime(0) NULL DEFAULT NULL COMMENT '该教室签到截止时间',
  PRIMARY KEY (`id`, `seat_map_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of yunzhi_classroom
-- ----------------------------
INSERT INTO `yunzhi_classroom` VALUES (1, 1, 'A101', '0000-00-00', '0000-00-00', 0, NULL, NULL);
INSERT INTO `yunzhi_classroom` VALUES (1, 2, 'A102', '0000-00-00', '0000-00-00', 0, NULL, NULL);

SET FOREIGN_KEY_CHECKS = 1;
