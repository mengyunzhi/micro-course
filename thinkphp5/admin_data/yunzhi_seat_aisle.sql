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

 Date: 28/12/2020 21:36:59
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for yunzhi_seat_aisle
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_seat_aisle`;
CREATE TABLE `yunzhi_seat_aisle`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `seat_map_id` int(11) NOT NULL DEFAULT 0,
  `x` int(11) NOT NULL DEFAULT 0,
  `y` int(11) NOT NULL DEFAULT 0,
  `create_time` date NOT NULL DEFAULT '0000-00-00',
  `update_time` date NOT NULL DEFAULT '0000-00-00',
  `state` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 241342 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of yunzhi_seat_aisle
-- ----------------------------
INSERT INTO `yunzhi_seat_aisle` VALUES (241341, 97, 4, 4, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241340, 97, 4, 3, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241339, 97, 4, 2, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241338, 97, 4, 1, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241337, 97, 4, 0, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241336, 97, 3, 4, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241335, 97, 3, 3, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241334, 97, 3, 2, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241333, 97, 3, 1, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241332, 97, 3, 0, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241331, 97, 2, 4, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241330, 97, 2, 3, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241329, 97, 2, 2, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241328, 97, 2, 1, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241327, 97, 2, 0, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241326, 97, 1, 4, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241325, 97, 1, 3, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241324, 97, 1, 2, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241323, 97, 1, 1, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241322, 97, 1, 0, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241321, 97, 0, 4, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241320, 97, 0, 3, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241319, 97, 0, 2, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241318, 97, 0, 1, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241317, 97, 0, 0, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241316, 96, 4, 4, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241315, 96, 4, 3, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241314, 96, 4, 2, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241313, 96, 4, 1, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241312, 96, 4, 0, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241311, 96, 3, 4, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241310, 96, 3, 3, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241309, 96, 3, 2, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241308, 96, 3, 1, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241307, 96, 3, 0, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241306, 96, 2, 4, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241305, 96, 2, 3, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241304, 96, 2, 2, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241303, 96, 2, 1, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241302, 96, 2, 0, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241301, 96, 1, 4, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241300, 96, 1, 3, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241299, 96, 1, 2, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241298, 96, 1, 1, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241297, 96, 1, 0, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241296, 96, 0, 4, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241295, 96, 0, 3, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241294, 96, 0, 2, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241293, 96, 0, 1, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241292, 96, 0, 0, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241201, 91, 8, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241200, 91, 8, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241199, 91, 8, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241198, 91, 8, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241197, 91, 8, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241196, 91, 8, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241195, 91, 8, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241194, 91, 8, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241193, 91, 8, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241192, 91, 8, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241191, 91, 7, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241190, 91, 7, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241189, 91, 7, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241188, 91, 7, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241187, 91, 7, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241186, 91, 7, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241185, 91, 7, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241184, 91, 7, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241183, 91, 7, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241182, 91, 7, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241181, 91, 6, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241180, 91, 6, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241179, 91, 6, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241178, 91, 6, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241177, 91, 6, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241176, 91, 6, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241175, 91, 6, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241174, 91, 6, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241173, 91, 6, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241172, 91, 6, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241171, 91, 5, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241170, 91, 5, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241169, 91, 5, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241168, 91, 5, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241167, 91, 5, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241166, 91, 5, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241165, 91, 5, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241164, 91, 5, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241163, 91, 5, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241162, 91, 5, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241161, 91, 4, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241160, 91, 4, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241159, 91, 4, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241158, 91, 4, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241157, 91, 4, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241156, 91, 4, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241155, 91, 4, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241154, 91, 4, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241153, 91, 4, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241152, 91, 4, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241151, 91, 3, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241150, 91, 3, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241149, 91, 3, 7, '0000-00-00', '0000-00-00', 1);
INSERT INTO `yunzhi_seat_aisle` VALUES (241148, 91, 3, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241147, 91, 3, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241146, 91, 3, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241145, 91, 3, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241144, 91, 3, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241143, 91, 3, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241142, 91, 3, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241141, 91, 2, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241140, 91, 2, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241139, 91, 2, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241138, 91, 2, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241137, 91, 2, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241136, 91, 2, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241135, 91, 2, 3, '0000-00-00', '0000-00-00', 1);
INSERT INTO `yunzhi_seat_aisle` VALUES (241134, 91, 2, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241133, 91, 2, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241132, 91, 2, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241131, 91, 1, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241130, 91, 1, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241129, 91, 1, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241128, 91, 1, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241127, 91, 1, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241126, 91, 1, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241125, 91, 1, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241124, 91, 1, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241123, 91, 1, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241122, 91, 1, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241121, 91, 0, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241120, 91, 0, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241119, 91, 0, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241118, 91, 0, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241117, 91, 0, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241116, 91, 0, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241115, 91, 0, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241114, 91, 0, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241113, 91, 0, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241112, 91, 0, 0, '0000-00-00', '0000-00-00', 0);

SET FOREIGN_KEY_CHECKS = 1;
