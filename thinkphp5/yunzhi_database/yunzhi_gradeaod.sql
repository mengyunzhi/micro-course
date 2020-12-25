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

 Date: 25/12/2020 10:53:39
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for yunzhi_gradeaod
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_gradeaod`;
CREATE TABLE `yunzhi_gradeaod`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `aodname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `aodnum` int(11) NOT NULL,
  `create_time` datetime(0) NOT NULL,
  `update_time` datetime(0) NOT NULL,
  `course_id` int(11) NOT NULL,
  `aodid` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of yunzhi_gradeaod
-- ----------------------------
INSERT INTO `yunzhi_gradeaod` VALUES (1, '上课睡觉', -2, '2020-10-10 10:27:16', '2020-10-10 10:27:20', 3, 0);
INSERT INTO `yunzhi_gradeaod` VALUES (2, '上课积极', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 2, 1);
INSERT INTO `yunzhi_gradeaod` VALUES (3, '上课玩手机', -2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 3, 0);
INSERT INTO `yunzhi_gradeaod` VALUES (4, '上课玩手机', -2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 3, 0);
INSERT INTO `yunzhi_gradeaod` VALUES (5, '上课回答问题', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 1);
INSERT INTO `yunzhi_gradeaod` VALUES (6, '上课玩手机', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 1);
INSERT INTO `yunzhi_gradeaod` VALUES (8, '上课积极', 15, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 3, 1);
INSERT INTO `yunzhi_gradeaod` VALUES (9, '搞事情', -2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 0);
INSERT INTO `yunzhi_gradeaod` VALUES (10, '说话', -3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 3, 0);
INSERT INTO `yunzhi_gradeaod` VALUES (11, '上课吃东西', -2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 4, 0);
INSERT INTO `yunzhi_gradeaod` VALUES (12, '上课打呼噜', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 4, 1);
INSERT INTO `yunzhi_gradeaod` VALUES (13, '说话', -2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 0);
INSERT INTO `yunzhi_gradeaod` VALUES (14, '说话', -2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 0);
INSERT INTO `yunzhi_gradeaod` VALUES (15, '说话', -2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 0);
INSERT INTO `yunzhi_gradeaod` VALUES (16, '上课玩手机', -3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 4, 0);
INSERT INTO `yunzhi_gradeaod` VALUES (17, '上课跟人说话', -3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 4, 0);

SET FOREIGN_KEY_CHECKS = 1;
