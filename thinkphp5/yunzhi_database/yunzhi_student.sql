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

 Date: 25/12/2020 10:53:56
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for yunzhi_student
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_student`;
CREATE TABLE `yunzhi_student`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '姓名',
  `num` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `sex` tinyint(2) NOT NULL DEFAULT 0,
  `email` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `create_time` int(11) NOT NULL DEFAULT 0,
  `update_time` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 95 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of yunzhi_student
-- ----------------------------
INSERT INTO `yunzhi_student` VALUES (89, '孟鑫燕', '195717', 0, '1580380768@qq.com', 1608599879, 1608599879);
INSERT INTO `yunzhi_student` VALUES (90, '孟鑫燕', '195717', 1, '1580380768@qq.com', 1608600206, 1608600206);
INSERT INTO `yunzhi_student` VALUES (91, '郝泽龙', '195717', 0, '1580380768@qq.com', 1608601602, 1608601602);
INSERT INTO `yunzhi_student` VALUES (92, '郝泽龙', '195717', 0, '1580380768@qq.com', 1608601653, 1608601653);
INSERT INTO `yunzhi_student` VALUES (93, '孟鑫燕', '195717', 0, '1580380768@qq.com', 1608601662, 1608601662);
INSERT INTO `yunzhi_student` VALUES (94, '赵雯丽', '195717', 1, 'zhaoliu@yunzhi.club', 1608601672, 1608601672);
INSERT INTO `yunzhi_student` VALUES (87, '郝泽龙', '195717', 0, '1580380768@qq.com', 1605946593, 1605946593);
INSERT INTO `yunzhi_student` VALUES (88, '赵雯丽', '195713', 0, '1580380768@qq.com', 1605946692, 1605946692);
INSERT INTO `yunzhi_student` VALUES (86, '段声望', '195717', 1, 'zhaoliu@yunzhi.club', 1605946069, 1608518168);
INSERT INTO `yunzhi_student` VALUES (84, '郝泽龙', '195717', 0, '1580380768@qq.com', 1605793317, 1605793317);
INSERT INTO `yunzhi_student` VALUES (85, '许一普', '195759', 0, '15383011312@qq.com', 1605940325, 1605940325);

SET FOREIGN_KEY_CHECKS = 1;
