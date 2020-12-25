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

 Date: 25/12/2020 10:54:01
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for yunzhi_teacher
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_teacher`;
CREATE TABLE `yunzhi_teacher`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '姓名',
  `password` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `username` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户名',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 20 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of yunzhi_teacher
-- ----------------------------
INSERT INTO `yunzhi_teacher` VALUES (2, '张老师', '5f383784a8ce262fa222357d503768412ee75518', 'lisi', 1594344872, 1594775272);
INSERT INTO `yunzhi_teacher` VALUES (1, '李老师', '5f383784a8ce262fa222357d503768412ee75518', 'zhangsan', 0, 0);
INSERT INTO `yunzhi_teacher` VALUES (4, '王老师', '5f383784a8ce262fa222357d503768412ee75518', 'wangwu', 1594428961, 1594690704);
INSERT INTO `yunzhi_teacher` VALUES (5, '赵老师', '5f383784a8ce262fa222357d503768412ee75518', 'haolaoshi', 1594632937, 1594687962);
INSERT INTO `yunzhi_teacher` VALUES (6, '何老师', '5f383784a8ce262fa222357d503768412ee75518', 'ligang', 1594642366, 1594643065);
INSERT INTO `yunzhi_teacher` VALUES (8, '陈老师', '5f383784a8ce262fa222357d503768412ee75518', '阿斯顿', 1594696474, 1594696474);
INSERT INTO `yunzhi_teacher` VALUES (19, '郝老师', '5f383784a8ce262fa222357d503768412ee75518', 'haozelong', 1594950661, 1594950661);

SET FOREIGN_KEY_CHECKS = 1;
