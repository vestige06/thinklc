/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50141
Source Host           : localhost:3306
Source Database       : thinklc21

Target Server Type    : MYSQL
Target Server Version : 50141
File Encoding         : 65001

Date: 2013-06-02 23:39:08
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `thinklc_admin`
-- ----------------------------
DROP TABLE IF EXISTS `thinklc_admin`;
CREATE TABLE `thinklc_admin` (
  `id` smallint(4) NOT NULL AUTO_INCREMENT,
  `rid` smallint(4) unsigned NOT NULL DEFAULT '0',
  `founder` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `aname` varchar(10) NOT NULL,
  `apwd` varchar(32) NOT NULL,
  `loginip` varchar(15) NOT NULL,
  `logintime` int(10) NOT NULL,
  `count` smallint(6) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of thinklc_admin
-- ----------------------------
INSERT INTO `thinklc_admin` VALUES ('1', '0', '1', 'admin', '7fef6171469e80d32c0559f88b377245', '127.0.0.1', '1370171072', '0', '1');

-- ----------------------------
-- Table structure for `thinklc_area`
-- ----------------------------
DROP TABLE IF EXISTS `thinklc_area`;
CREATE TABLE `thinklc_area` (
  `id` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `aname` varchar(20) NOT NULL,
  `alias` varchar(20) NOT NULL,
  `listorder` tinyint(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of thinklc_area
-- ----------------------------
INSERT INTO `thinklc_area` VALUES ('1', '默认地区', 'diqu', '1');

-- ----------------------------
-- Table structure for `thinklc_article`
-- ----------------------------
DROP TABLE IF EXISTS `thinklc_article`;
CREATE TABLE `thinklc_article` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL COMMENT '文章标题',
  `uid` smallint(5) NOT NULL DEFAULT '0' COMMENT '用户id',
  `catid` tinyint(2) NOT NULL DEFAULT '0' COMMENT '文章分类',
  `summary` varchar(300) NOT NULL COMMENT '文章摘要',
  `content` text NOT NULL COMMENT '文章内容',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `tags` varchar(50) NOT NULL COMMENT '标签',
  `readcount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '阅读次数',
  `commentcount` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '评论次数',
  `addtime` int(10) NOT NULL,
  `edittime` int(10) NOT NULL,
  `toptitle` varchar(10) DEFAULT NULL COMMENT '标题颜色',
  `topnum` tinyint(1) unsigned DEFAULT '1',
  `extend` tinyint(1) DEFAULT NULL,
  `topstatus` tinyint(1) unsigned DEFAULT '0',
  `picurl` varchar(100) DEFAULT NULL,
  `ispic` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `cate` (`catid`),
  KEY `uid` (`uid`),
  KEY `title` (`title`),
  KEY `toptotime` (`addtime`),
  KEY `topstatus` (`topstatus`),
  KEY `topnum` (`topnum`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='文章表';

-- ----------------------------
-- Records of thinklc_article
-- ----------------------------
INSERT INTO `thinklc_article` VALUES ('8', 'zhengzs', '0', '2', 'zhengzs', 'dsaasdads', '0', 'zhengzs', '0', '0', '0', '1370139246', '', '1', null, '0', '', '0');
INSERT INTO `thinklc_article` VALUES ('11', '人人人人人人人人人人人人人人人人', '0', '2', '花丛人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人人从花丛人人人人人人人人人人人人人人人人人人人', '', '1', '恭恭敬敬', '0', '0', '0', '1354424287', '#FF6600', '1', null, '0', '', '0');
INSERT INTO `thinklc_article` VALUES ('9', 'hhhhh', '0', '2', 'hhhhh', '', '0', 'hhhhh', '0', '0', '0', '1353254625', '', '1', null, '0', '', '0');
INSERT INTO `thinklc_article` VALUES ('10', '有朝一日，我定会乐山再起', '0', '2', '有朝一日，我定会乐山再起', '', '0', '​有朝一日', '0', '0', '0', '1354420516', '', '1', null, '0', '', '0');
INSERT INTO `thinklc_article` VALUES ('12', 'rrrrrrrrrrrrrrrrr', '0', '3', 'rrrrrrrrrrrrrrrrrrrrrrrrr', 'rrrrrrrrrrrrrrrrrrrrrrrrr', '1', 'rrr', '0', '0', '0', '1370139311', '', '1', null, '0', '', '0');
INSERT INTO `thinklc_article` VALUES ('13', 'dasdasd', '0', '3', 'asdasdas', '', '1', '', '0', '0', '0', '1367392521', '', '1', null, '0', '', '0');
INSERT INTO `thinklc_article` VALUES ('14', 'fds', '0', '2', 'fdsfds', '', '1', '', '0', '0', '0', '1367395673', '', '1', null, '0', '', '0');
INSERT INTO `thinklc_article` VALUES ('15', 'dasdasdasd', '0', '3', 'sadasdasd', 'dsasaads', '1', 'sadsd', '0', '0', '1370135728', '0', '', '1', null, '0', '', '0');
INSERT INTO `thinklc_article` VALUES ('16', 'saadsasd', '0', '3', 'asdasdasdasd', 'asdasdasdasdsa', '1', 'asdasd', '0', '0', '1370135866', '0', '', '1', null, '0', '', '0');
INSERT INTO `thinklc_article` VALUES ('17', 'sdafsdfdsfa', '0', '2', '', 'fdsdafsdfsfds', '1', '', '0', '0', '1370140269', '0', '', '1', null, '0', '', '0');
INSERT INTO `thinklc_article` VALUES ('18', '佐枯基材基本基本面基本面基本面基本面基本', '0', '3', '', '佐枯基材基本基本面基本面基本面基本面基本<br />\r\n佐枯基材基本基本面基本面基本面基本面基本<br />\r\n佐枯基材基本基本面基本面基本面基本面基本<br />\r\n佐枯基材基本基本面基本面基本面基本面基本<br />\r\n佐枯基材基本基本面基本面基本面基本面基本<br />\r\n佐枯基材基本基本面基本面基本面基本面基本<br />\r\n佐枯基材基本基本面基本面基本面基本面基本<br />\r\n佐枯基材基本基本面基本面基本面基本面基本<br />\r\n佐枯基材基本基本面基本面基本面基本面基本<br />\r\n佐枯基材基本基本面基本面基本面基本面基本<br />\r\n佐枯基材基本基本面基本面基本面基本面基本<br />\r\n佐枯基材基本基本面基本面基本面基本面基本<br />\r\n佐枯基材基本基本面基本面基本面基本面基本<br />\r\n佐枯基材基本基本面基本面基本面基本面基本<br />\r\n佐枯基材基本基本面基本面基本面基本面基本<br />\r\n佐枯基材基本基本面基本面基本面基本面基本<br />\r\n佐枯基材基本基本面基本面基本面基本面基本<br />\r\n佐枯基材基本基本面基本面基本面基本面基本<br />\r\n佐枯基材基本基本面基本面基本面基本面基本<br />\r\n佐枯基材基本基本面基本面基本面基本面基本<br />\r\n佐枯基材基本基本面基本面基本面基本面基本<br />\r\n佐枯基材基本基本面基本面基本面基本面基本', '1', '文章 啦啊', '0', '0', '1370155997', '0', '', '1', null, '0', '', '0');
INSERT INTO `thinklc_article` VALUES ('19', '这个错误通常会在你使用HEADER的时候出', '0', '3', '', '这个错误通常会在你使用HEADER的时候出这个错误通常会<br />\r\n在你使用HEADER的时候出这个错误通常会在你使用HEADER的时候出这个错误通常会在你使用HEADER的时候出这个错误通常会在你使用HEADER的时候出这个错误通常会在你使用HEADER的时候出<br />\r\n<br />\r\n这个错误通常会在你使用HEADER的时候出这个错误通常会在你使用HEADER的时候出<br />\r\n<br />\r\n这个错误通常会在你使用HEADER的时候出这个错误通常会在你使用HEADER的时候出这个错误通常会在你使用HEADER的时候出', '1', 'lamp linux', '0', '0', '1370182342', '0', '#33CCCC', '1', null, '0', '', '0');

-- ----------------------------
-- Table structure for `thinklc_blog`
-- ----------------------------
DROP TABLE IF EXISTS `thinklc_blog`;
CREATE TABLE `thinklc_blog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(15) NOT NULL DEFAULT '',
  `user_id` mediumint(5) unsigned NOT NULL DEFAULT '0',
  `category_id` smallint(5) unsigned NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` longtext,
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `mod_time` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `read_count` mediumint(5) unsigned NOT NULL DEFAULT '0',
  `comment_count` mediumint(5) unsigned NOT NULL DEFAULT '0',
  `tags` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='文章表';

-- ----------------------------
-- Records of thinklc_blog
-- ----------------------------
INSERT INTO `thinklc_blog` VALUES ('1', '', '41', '12', '这是本博客第一条数据……', '<p>\r\n	这是本博客第一条数据……\r\n</p>\r\n<p>\r\n	bug有木有？\r\n</p>', '1362121398', '0', '1', '10', '1', '博客 bug');
INSERT INTO `thinklc_blog` VALUES ('2', '', '41', '10', '这是第二篇文章', '<p>\r\n	第二篇文章\r\n</p>\r\n<p>\r\n	哈哈\r\n</p>', '1362121522', '0', '1', '12', '1', 'php');
INSERT INTO `thinklc_blog` VALUES ('3', '', '41', '11', 'dasasasa&quot;sdfsdf', 'dasasdasas<p>	http://leible.com/</p><p>	&lt;div class=&quot;content&quot;&gt;</p><p>	&quot;fsdfdsfdssdf\'</p><p>	&lt;/div&gt;</p><p>	<br></p><p>	&lt;script&gt;window.location.href=\'http://www.baidu.com\'&lt;/script&gt;</p><p>	<br></p><p>	<span></span><span></span> </p>', '1363617026', '1365948053', '1', '10', '0', 'js javascript');
INSERT INTO `thinklc_blog` VALUES ('4', '', '41', '12', 'dasasdasas http://leible.com/', 'dasasdasas<p>	http://leible.com/</p><p>	&lt;div class=&quot;content&quot;&gt;</p><p>	&quot;fsdfdsfdssdf\'</p><p>	&lt;/div&gt;</p><p>	<br></p><p>	&lt;script&gt;window.location.href=\'http://www.baidu.com\'&lt;/script&gt;</p><p>	<br></p>', '1365433469', '1365433772', '1', '2', '0', 'html css');

-- ----------------------------
-- Table structure for `thinklc_category`
-- ----------------------------
DROP TABLE IF EXISTS `thinklc_category`;
CREATE TABLE `thinklc_category` (
  `id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `moduleid` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `catname` varchar(50) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `alias` varchar(20) NOT NULL DEFAULT '',
  `items` smallint(6) unsigned NOT NULL DEFAULT '0',
  `pid` smallint(4) unsigned NOT NULL DEFAULT '0',
  `listorder` smallint(4) unsigned NOT NULL DEFAULT '0',
  `seo_title` varchar(255) NOT NULL DEFAULT '',
  `seo_keywords` varchar(255) NOT NULL DEFAULT '',
  `seo_description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of thinklc_category
-- ----------------------------
INSERT INTO `thinklc_category` VALUES ('1', '5', '默认分类', '', 'fenlei', '0', '0', '1', '', '', '');
INSERT INTO `thinklc_category` VALUES ('2', '7', '系统', '', 'OS', '0', '0', '0', '', '', '');
INSERT INTO `thinklc_category` VALUES ('3', '7', 'fdsa', '', 'fads', '0', '0', '0', '', '', '');
INSERT INTO `thinklc_category` VALUES ('4', '7', 'fdsafads', '', 'fasdfasd', '0', '0', '0', '', '', '');

-- ----------------------------
-- Table structure for `thinklc_charge`
-- ----------------------------
DROP TABLE IF EXISTS `thinklc_charge`;
CREATE TABLE `thinklc_charge` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uname` varchar(30) NOT NULL DEFAULT '',
  `bank` varchar(20) NOT NULL DEFAULT '',
  `amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `sendtime` int(10) unsigned NOT NULL DEFAULT '0',
  `receivetime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '4' COMMENT '订单处理状态',
  `note` varchar(255) NOT NULL DEFAULT '',
  `ordernum` varchar(20) NOT NULL,
  `paystyle` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ordernum` (`ordernum`),
  KEY `uname` (`uname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of thinklc_charge
-- ----------------------------

-- ----------------------------
-- Table structure for `thinklc_credit`
-- ----------------------------
DROP TABLE IF EXISTS `thinklc_credit`;
CREATE TABLE `thinklc_credit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uname` varchar(30) NOT NULL DEFAULT '',
  `amount` smallint(5) NOT NULL DEFAULT '0',
  `balance` smallint(5) NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `reason` varchar(255) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  `editor` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `uname` (`uname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of thinklc_credit
-- ----------------------------

-- ----------------------------
-- Table structure for `thinklc_etype`
-- ----------------------------
DROP TABLE IF EXISTS `thinklc_etype`;
CREATE TABLE `thinklc_etype` (
  `id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `listorder` smallint(4) NOT NULL DEFAULT '0',
  `typename` varchar(30) NOT NULL DEFAULT '',
  `item` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `item` (`item`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of thinklc_etype
-- ----------------------------

-- ----------------------------
-- Table structure for `thinklc_group`
-- ----------------------------
DROP TABLE IF EXISTS `thinklc_group`;
CREATE TABLE `thinklc_group` (
  `id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `gname` varchar(10) NOT NULL,
  `upgrade` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `price` smallint(4) unsigned NOT NULL DEFAULT '0',
  `price2` smallint(4) unsigned NOT NULL DEFAULT '0',
  `listorder` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `system` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `usescore` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of thinklc_group
-- ----------------------------
INSERT INTO `thinklc_group` VALUES ('1', '禁止访问', '0', '0', '0', '1', '1', '0');
INSERT INTO `thinklc_group` VALUES ('2', '游客', '0', '0', '0', '2', '1', '0');
INSERT INTO `thinklc_group` VALUES ('3', '待审核会员', '0', '0', '0', '3', '1', '0');
INSERT INTO `thinklc_group` VALUES ('4', '普通会员', '1', '0', '0', '4', '1', '0');
INSERT INTO `thinklc_group` VALUES ('5', 'VIP会员', '1', '200', '120', '5', '1', '0');

-- ----------------------------
-- Table structure for `thinklc_help`
-- ----------------------------
DROP TABLE IF EXISTS `thinklc_help`;
CREATE TABLE `thinklc_help` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `tid` smallint(4) unsigned NOT NULL DEFAULT '0',
  `uid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `display` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(200) NOT NULL,
  `content` varchar(1800) NOT NULL,
  `answer` text NOT NULL,
  `asktime` int(10) unsigned NOT NULL DEFAULT '0',
  `answertime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `status` (`status`),
  KEY `isshow` (`display`),
  KEY `title` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of thinklc_help
-- ----------------------------

-- ----------------------------
-- Table structure for `thinklc_info`
-- ----------------------------
DROP TABLE IF EXISTS `thinklc_info`;
CREATE TABLE `thinklc_info` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `content` varchar(2000) NOT NULL,
  `edittime` int(10) NOT NULL,
  `expired` int(10) unsigned NOT NULL DEFAULT '0',
  `areaid` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `catid` tinyint(2) NOT NULL DEFAULT '0',
  `uid` smallint(5) NOT NULL DEFAULT '0',
  `count` smallint(5) NOT NULL DEFAULT '0',
  `keyword` varchar(50) NOT NULL,
  `linkurl` varchar(150) NOT NULL,
  `ispic` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `picurl` varchar(100) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `detail` text NOT NULL,
  `extend` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `contact` varchar(200) NOT NULL,
  `toptotime` int(10) NOT NULL,
  `topstatus` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `topnum` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `toptitle` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cate` (`catid`),
  KEY `uid` (`uid`),
  KEY `area` (`areaid`),
  KEY `title` (`title`),
  KEY `toptotime` (`toptotime`),
  KEY `topstatus` (`topstatus`),
  KEY `topnum` (`topnum`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of thinklc_info
-- ----------------------------
INSERT INTO `thinklc_info` VALUES ('1', '分类地区哈哈', '无可奈何花落去', '1365869389', '0', '1', '1', '0', '0', '基本面', '', '0', '', '1', '老大哥魂牵梦绕', '0', 'a:4:{s:9:\"contacter\";s:0:\"\";s:3:\"tel\";s:0:\"\";s:2:\"qq\";s:0:\"\";s:3:\"msn\";s:0:\"\";}', '0', '0', '1', '#808000');

-- ----------------------------
-- Table structure for `thinklc_limit`
-- ----------------------------
DROP TABLE IF EXISTS `thinklc_limit`;
CREATE TABLE `thinklc_limit` (
  `uid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `moduleid` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `lasttime` int(10) unsigned NOT NULL DEFAULT '0',
  `item` varchar(10) NOT NULL,
  `val` smallint(4) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `uid` (`uid`,`moduleid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of thinklc_limit
-- ----------------------------

-- ----------------------------
-- Table structure for `thinklc_link`
-- ----------------------------
DROP TABLE IF EXISTS `thinklc_link`;
CREATE TABLE `thinklc_link` (
  `id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `tid` smallint(4) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `listorder` smallint(4) NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `linkurl` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of thinklc_link
-- ----------------------------

-- ----------------------------
-- Table structure for `thinklc_member`
-- ----------------------------
DROP TABLE IF EXISTS `thinklc_member`;
CREATE TABLE `thinklc_member` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `uname` varchar(20) NOT NULL,
  `upwd` varchar(32) NOT NULL,
  `gid` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `company` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `contacter` varchar(30) NOT NULL,
  `tel` varchar(30) NOT NULL,
  `email` varchar(20) NOT NULL,
  `regip` varchar(30) NOT NULL,
  `regtime` int(10) unsigned NOT NULL DEFAULT '0',
  `loginip` varchar(30) NOT NULL,
  `logintime` int(10) unsigned NOT NULL DEFAULT '0',
  `credit` smallint(5) NOT NULL DEFAULT '0',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `viptotime` int(10) unsigned NOT NULL DEFAULT '0',
  `ucverify` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uname` (`uname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of thinklc_member
-- ----------------------------

-- ----------------------------
-- Table structure for `thinklc_menu`
-- ----------------------------
DROP TABLE IF EXISTS `thinklc_menu`;
CREATE TABLE `thinklc_menu` (
  `id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `mname` varchar(20) NOT NULL,
  `aname` varchar(20) NOT NULL,
  `parameter` varchar(30) NOT NULL,
  `mtitle` varchar(50) NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `pid` smallint(4) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `action` varchar(20) NOT NULL,
  `system` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `display` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `listorder` smallint(4) unsigned NOT NULL DEFAULT '0',
  `dialog` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `mname` (`mname`)
) ENGINE=MyISAM AUTO_INCREMENT=64 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of thinklc_menu
-- ----------------------------
INSERT INTO `thinklc_menu` VALUES ('1', '', '', '', '系统维护', '1', '0', '1', '', '1', '1', '2', '0');
INSERT INTO `thinklc_menu` VALUES ('2', '', '', '', '角色权限', '1', '1', '2', '', '1', '1', '2', '0');
INSERT INTO `thinklc_menu` VALUES ('3', 'Menu', 'index', '', '菜单管理', '1', '2', '3', '', '1', '1', '0', '0');
INSERT INTO `thinklc_menu` VALUES ('4', '', '', '', '我的面板', '1', '0', '1', '', '1', '1', '1', '0');
INSERT INTO `thinklc_menu` VALUES ('5', '', '', '', '个人信息', '1', '4', '2', '', '1', '1', '0', '0');
INSERT INTO `thinklc_menu` VALUES ('6', 'Person', 'password', '', '修改密码', '1', '5', '3', '', '1', '1', '0', '0');
INSERT INTO `thinklc_menu` VALUES ('7', '', '', '', '常用操作', '1', '4', '2', '', '1', '1', '0', '0');
INSERT INTO `thinklc_menu` VALUES ('8', 'Role', 'index', '', '角色管理', '1', '2', '3', '', '0', '1', '0', '0');
INSERT INTO `thinklc_menu` VALUES ('9', 'Admin', 'index', '', '管理员管理', '1', '2', '3', '', '0', '1', '0', '0');
INSERT INTO `thinklc_menu` VALUES ('10', '', '', '', '系统设置', '1', '1', '2', '', '0', '1', '1', '0');
INSERT INTO `thinklc_menu` VALUES ('11', 'Setting', 'config', '', '系统配置', '1', '10', '3', '', '0', '1', '0', '0');
INSERT INTO `thinklc_menu` VALUES ('12', 'Setting', 'index', '', '网站设置', '1', '10', '3', '', '0', '1', '0', '0');
INSERT INTO `thinklc_menu` VALUES ('13', 'Module', 'index', '', '模块管理', '1', '10', '3', '', '0', '1', '0', '0');
INSERT INTO `thinklc_menu` VALUES ('14', 'Area', 'index', '', '地区管理', '1', '10', '3', '', '0', '1', '0', '0');
INSERT INTO `thinklc_menu` VALUES ('15', '', '', '', '功能模块', '1', '0', '1', '', '0', '1', '3', '0');
INSERT INTO `thinklc_menu` VALUES ('16', '', '', '', '{5}管理', '1', '15', '2', '', '0', '1', '0', '0');
INSERT INTO `thinklc_menu` VALUES ('17', 'Info', 'index', '', '{5}列表', '1', '16', '3', '', '0', '1', '2', '0');
INSERT INTO `thinklc_menu` VALUES ('18', 'Category', 'index', 'moduleid=5', '分类管理', '1', '16', '3', '', '0', '1', '5', '0');
INSERT INTO `thinklc_menu` VALUES ('19', 'Setting', 'index', 'item=5', '模块设置', '1', '16', '3', '', '0', '1', '6', '0');
INSERT INTO `thinklc_menu` VALUES ('20', '', '', '', '扩展模块', '1', '15', '2', '', '0', '1', '0', '0');
INSERT INTO `thinklc_menu` VALUES ('21', 'Help', 'index', '', '{2}管理', '1', '20', '3', '', '0', '1', '0', '0');
INSERT INTO `thinklc_menu` VALUES ('22', 'Webpage', 'index', '', '{3}管理', '1', '20', '3', '', '0', '1', '0', '0');
INSERT INTO `thinklc_menu` VALUES ('23', 'Link', 'index', '', '{4}管理', '1', '20', '3', '', '0', '1', '0', '0');
INSERT INTO `thinklc_menu` VALUES ('24', '', '', '', '会员管理', '1', '0', '1', '', '0', '1', '4', '0');
INSERT INTO `thinklc_menu` VALUES ('25', '', '', '', '会员管理', '1', '24', '2', '', '0', '1', '0', '0');
INSERT INTO `thinklc_menu` VALUES ('26', '', '', '', '财务管理', '1', '24', '2', '', '0', '1', '0', '0');
INSERT INTO `thinklc_menu` VALUES ('27', 'Member', 'index', '', '会员列表', '1', '25', '3', '', '0', '1', '1', '0');
INSERT INTO `thinklc_menu` VALUES ('28', 'Member', 'check', '', '审核会员', '1', '25', '3', '', '0', '1', '2', '0');
INSERT INTO `thinklc_menu` VALUES ('29', 'Group', 'index', '', '会员组管理', '1', '25', '3', '', '0', '1', '4', '0');
INSERT INTO `thinklc_menu` VALUES ('30', 'Setting', 'member', 'item=1', '模块设置', '1', '25', '3', '', '0', '1', '6', '0');
INSERT INTO `thinklc_menu` VALUES ('31', 'Money', 'index', '', '资金管理', '1', '26', '3', '', '0', '1', '0', '0');
INSERT INTO `thinklc_menu` VALUES ('32', 'Credit', 'index', '', '积分管理', '1', '26', '3', '', '0', '1', '0', '0');
INSERT INTO `thinklc_menu` VALUES ('33', 'Charge', 'index', '', '充值记录', '1', '26', '3', '', '0', '1', '0', '0');
INSERT INTO `thinklc_menu` VALUES ('34', 'Member', 'vip', '', 'VIP管理', '1', '25', '3', '', '0', '1', '3', '0');
INSERT INTO `thinklc_menu` VALUES ('35', 'Info', 'add', '', '添加{5}', '1', '16', '3', '', '0', '1', '1', '0');
INSERT INTO `thinklc_menu` VALUES ('36', 'Info', 'check', '', '审核{5}', '1', '16', '3', '', '0', '1', '3', '0');
INSERT INTO `thinklc_menu` VALUES ('37', 'Info', 'top', '', '置顶管理', '1', '16', '3', '', '0', '1', '4', '0');
INSERT INTO `thinklc_menu` VALUES ('38', '', '', '', '更新数据', '1', '1', '2', '', '0', '1', '0', '0');
INSERT INTO `thinklc_menu` VALUES ('39', 'Cache', 'config', '', '更新配置缓存', '1', '38', '3', '', '0', '1', '2', '0');
INSERT INTO `thinklc_menu` VALUES ('40', 'Cache', 'compiled', '', '清理编译缓存', '1', '38', '3', '', '0', '1', '5', '0');
INSERT INTO `thinklc_menu` VALUES ('41', 'Cache', 'templates', '', '清理模版缓存', '1', '38', '3', '', '0', '1', '3', '0');
INSERT INTO `thinklc_menu` VALUES ('42', 'Cache', 'html', '', '清理网页缓存', '1', '38', '3', '', '0', '1', '4', '0');
INSERT INTO `thinklc_menu` VALUES ('43', 'Cache', 'index', '', '刷新静态首页', '1', '38', '3', '', '0', '1', '1', '0');
INSERT INTO `thinklc_menu` VALUES ('44', 'Cache', 'onekey', '', '一键更新', '1', '38', '3', '', '0', '1', '6', '0');
INSERT INTO `thinklc_menu` VALUES ('45', 'Oauth', 'index', '', '一键登录', '1', '25', '3', '', '0', '1', '5', '0');
INSERT INTO `thinklc_menu` VALUES ('46', '', '', '', '{6}管理', '1', '15', '2', '', '0', '1', '2', '0');
INSERT INTO `thinklc_menu` VALUES ('47', 'Phone', 'add', '', '添加{6}', '1', '46', '3', '', '0', '1', '0', '0');
INSERT INTO `thinklc_menu` VALUES ('48', 'Phone', 'index', '', '{6}列表', '1', '46', '3', '', '0', '1', '0', '0');
INSERT INTO `thinklc_menu` VALUES ('49', 'Phone', 'check', '', '审核{6}', '1', '46', '3', '', '0', '1', '0', '0');
INSERT INTO `thinklc_menu` VALUES ('50', 'Phone', 'spread', '', '推广管理', '1', '46', '3', '', '0', '1', '0', '0');
INSERT INTO `thinklc_menu` VALUES ('51', 'Category', 'index', 'moduleid=6', '分类管理', '1', '46', '3', '', '0', '1', '0', '0');
INSERT INTO `thinklc_menu` VALUES ('52', 'Setting', 'index', 'item=6', '模块设置', '1', '46', '3', '', '0', '1', '0', '0');
INSERT INTO `thinklc_menu` VALUES ('53', '', '', '', '文章模块', '1', '0', '1', '', '0', '1', '5', '0');
INSERT INTO `thinklc_menu` VALUES ('54', '', '', '', '文章模块', '1', '53', '2', '', '0', '1', '0', '0');
INSERT INTO `thinklc_menu` VALUES ('55', 'Article', 'Add', '', '添加{7}', '1', '54', '3', '', '0', '1', '0', '0');
INSERT INTO `thinklc_menu` VALUES ('56', 'Article', 'index', '', '{7}列表', '1', '54', '3', '', '0', '1', '0', '0');
INSERT INTO `thinklc_menu` VALUES ('57', 'Article', 'check', '', '{7}审核列表', '1', '54', '3', '', '0', '1', '0', '0');
INSERT INTO `thinklc_menu` VALUES ('58', 'Article', 'top', '', '置顶管理', '1', '54', '3', '', '0', '1', '0', '0');
INSERT INTO `thinklc_menu` VALUES ('59', 'Category', 'index', 'moduleid=7', '分类管理', '1', '54', '3', '', '0', '1', '0', '0');
INSERT INTO `thinklc_menu` VALUES ('60', 'Setting', 'index', 'item=7', '设置管理', '1', '54', '3', '', '0', '1', '0', '0');
INSERT INTO `thinklc_menu` VALUES ('61', '', '', '', '标签模块', '1', '53', '2', '', '0', '1', '0', '0');
INSERT INTO `thinklc_menu` VALUES ('62', 'Tag', 'add', '', '添加{8}', '1', '61', '3', '', '0', '1', '0', '0');
INSERT INTO `thinklc_menu` VALUES ('63', 'Tag', 'index', '', '{8}列表', '1', '61', '3', '', '0', '1', '0', '0');

-- ----------------------------
-- Table structure for `thinklc_module`
-- ----------------------------
DROP TABLE IF EXISTS `thinklc_module`;
CREATE TABLE `thinklc_module` (
  `id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `mname` varchar(20) NOT NULL DEFAULT '',
  `mtitle` varchar(20) NOT NULL DEFAULT '',
  `listorder` smallint(4) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `display` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `installtime` int(10) unsigned NOT NULL DEFAULT '0',
  `system` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `islink` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `linkurl` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of thinklc_module
-- ----------------------------
INSERT INTO `thinklc_module` VALUES ('1', 'Member', '会员中心', '4', '1', '1', '1365862404', '1', '0', '');
INSERT INTO `thinklc_module` VALUES ('2', 'Help', '帮助中心', '3', '1', '1', '1365862404', '1', '0', '');
INSERT INTO `thinklc_module` VALUES ('3', 'Webpage', '关于我们', '5', '1', '0', '1365862404', '1', '0', '');
INSERT INTO `thinklc_module` VALUES ('4', 'Link', '友情链接', '6', '1', '0', '1365862404', '1', '0', '');
INSERT INTO `thinklc_module` VALUES ('5', 'Info', '综合信息', '1', '1', '1', '1365862404', '1', '0', '');
INSERT INTO `thinklc_module` VALUES ('6', 'Phone', '电话黄页', '2', '1', '1', '1365862404', '1', '0', '');
INSERT INTO `thinklc_module` VALUES ('7', 'Article', '文章', '7', '1', '1', '1365862404', '1', '0', '');

-- ----------------------------
-- Table structure for `thinklc_money`
-- ----------------------------
DROP TABLE IF EXISTS `thinklc_money`;
CREATE TABLE `thinklc_money` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uname` varchar(30) NOT NULL DEFAULT '',
  `bank` varchar(30) NOT NULL DEFAULT '',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `reason` varchar(255) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  `editor` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `uname` (`uname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of thinklc_money
-- ----------------------------

-- ----------------------------
-- Table structure for `thinklc_oauth`
-- ----------------------------
DROP TABLE IF EXISTS `thinklc_oauth`;
CREATE TABLE `thinklc_oauth` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uname` varchar(30) NOT NULL DEFAULT '',
  `site` varchar(30) NOT NULL DEFAULT '',
  `openid` varchar(255) NOT NULL DEFAULT '',
  `nickname` varchar(255) NOT NULL DEFAULT '',
  `avatar` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `logintimes` int(10) unsigned NOT NULL DEFAULT '0',
  `logintime` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `site` (`site`,`openid`),
  KEY `uname` (`uname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of thinklc_oauth
-- ----------------------------

-- ----------------------------
-- Table structure for `thinklc_panel`
-- ----------------------------
DROP TABLE IF EXISTS `thinklc_panel`;
CREATE TABLE `thinklc_panel` (
  `mid` smallint(4) unsigned NOT NULL DEFAULT '0',
  `aid` smallint(4) unsigned NOT NULL DEFAULT '0',
  `mtitle` char(32) NOT NULL,
  `url` char(255) NOT NULL,
  `datetime` int(10) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `aid` (`mid`,`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of thinklc_panel
-- ----------------------------
INSERT INTO `thinklc_panel` VALUES ('44', '1', '一键更新', '/admin.php/cache/onekey', '1370100960');
INSERT INTO `thinklc_panel` VALUES ('55', '1', '添加文章', '/admin.php/article/Add', '1370101862');

-- ----------------------------
-- Table structure for `thinklc_phone`
-- ----------------------------
DROP TABLE IF EXISTS `thinklc_phone`;
CREATE TABLE `thinklc_phone` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(20) NOT NULL,
  `business` char(50) NOT NULL,
  `tel` char(18) NOT NULL,
  `mobile` char(11) NOT NULL,
  `address` char(30) NOT NULL,
  `map` char(32) NOT NULL,
  `qq` char(12) NOT NULL,
  `areaid` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `catid` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `uid` int(4) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `toptotime` int(10) unsigned NOT NULL DEFAULT '0',
  `toptitle` char(10) NOT NULL,
  `spreadtotime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `catid` (`catid`),
  KEY `uid` (`uid`),
  KEY `areaid` (`areaid`),
  KEY `title` (`title`),
  KEY `toptotime` (`toptotime`),
  KEY `status` (`status`),
  KEY `address` (`address`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of thinklc_phone
-- ----------------------------

-- ----------------------------
-- Table structure for `thinklc_role`
-- ----------------------------
DROP TABLE IF EXISTS `thinklc_role`;
CREATE TABLE `thinklc_role` (
  `id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `rname` varchar(20) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `access` varchar(300) NOT NULL,
  `remark` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of thinklc_role
-- ----------------------------

-- ----------------------------
-- Table structure for `thinklc_setting`
-- ----------------------------
DROP TABLE IF EXISTS `thinklc_setting`;
CREATE TABLE `thinklc_setting` (
  `item` varchar(30) NOT NULL DEFAULT '0',
  `item_key` varchar(20) NOT NULL DEFAULT '',
  `item_value` varchar(200) NOT NULL DEFAULT '',
  KEY `item` (`item`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of thinklc_setting
-- ----------------------------
INSERT INTO `thinklc_setting` VALUES ('0', 'site_name', 'ThinkLC地方分类信息系统');
INSERT INTO `thinklc_setting` VALUES ('0', 'ftp_enable', '0');
INSERT INTO `thinklc_setting` VALUES ('0', 'search_type', '1');
INSERT INTO `thinklc_setting` VALUES ('1', 'reg_enable', '1');
INSERT INTO `thinklc_setting` VALUES ('1', 'reg_status', '1');
INSERT INTO `thinklc_setting` VALUES ('1', 'reg_area', '0');
INSERT INTO `thinklc_setting` VALUES ('1', 'reg_ip', '0');
INSERT INTO `thinklc_setting` VALUES ('1', 'credit_reg', '10');
INSERT INTO `thinklc_setting` VALUES ('1', 'credit_login', '2');
INSERT INTO `thinklc_setting` VALUES ('1', 'credit_post', '3');
INSERT INTO `thinklc_setting` VALUES ('1', 'credit_pay', '5');
INSERT INTO `thinklc_setting` VALUES ('1', 'credit_delete', '5');
INSERT INTO `thinklc_setting` VALUES ('1', 'credit_money', '10');
INSERT INTO `thinklc_setting` VALUES ('1', 'paytype', '站内|支付宝|财付通|中国工商银行|中国农业银行');
INSERT INTO `thinklc_setting` VALUES ('5', 'area_alias', '1');
INSERT INTO `thinklc_setting` VALUES ('5', 'cate_alias', '1');
INSERT INTO `thinklc_setting` VALUES ('5', 'listtemp', 'info');
INSERT INTO `thinklc_setting` VALUES ('5', 'cell_width', '178|370|562|946');
INSERT INTO `thinklc_setting` VALUES ('5', 'cell_contentlen', '100');
INSERT INTO `thinklc_setting` VALUES ('5', 'cell_titlelen', '10');
INSERT INTO `thinklc_setting` VALUES ('5', 'water', '0');
INSERT INTO `thinklc_setting` VALUES ('5', 'cell_height', '140');
INSERT INTO `thinklc_setting` VALUES ('5', 'loadtype', '0');
INSERT INTO `thinklc_setting` VALUES ('5', 'pagenum', '10');
INSERT INTO `thinklc_setting` VALUES ('5', 'wappagenum', '10');
INSERT INTO `thinklc_setting` VALUES ('5', 'top_link', '1');
INSERT INTO `thinklc_setting` VALUES ('5', 'top_pic', '1');
INSERT INTO `thinklc_setting` VALUES ('5', 'top_detail', '1');
INSERT INTO `thinklc_setting` VALUES ('5', 'top_title', '1');
INSERT INTO `thinklc_setting` VALUES ('5', 'top_credit', '0');
INSERT INTO `thinklc_setting` VALUES ('5', 'top_off_5', '2|95,3|90,6|85,12|80');
INSERT INTO `thinklc_setting` VALUES ('5', 'top_off_3', '3|90,6|85,12|80');
INSERT INTO `thinklc_setting` VALUES ('5', 'top_off_2', '3|95,6|90,12|85');
INSERT INTO `thinklc_setting` VALUES ('5', 'top_off_1', '3|95,6|90,12|85');
INSERT INTO `thinklc_setting` VALUES ('5', 'top_price', '100|150,300|400,600|600,1000|1000');
INSERT INTO `thinklc_setting` VALUES ('5', 'contact_ali', '0');
INSERT INTO `thinklc_setting` VALUES ('5', 'contact_msn', '1');
INSERT INTO `thinklc_setting` VALUES ('5', 'contact_qq', '1');
INSERT INTO `thinklc_setting` VALUES ('5', 'contact_add', '1');
INSERT INTO `thinklc_setting` VALUES ('5', 'contact_extend', '1');
INSERT INTO `thinklc_setting` VALUES ('6', 'cate_alias', '1');
INSERT INTO `thinklc_setting` VALUES ('6', 'area_alias', '1');
INSERT INTO `thinklc_setting` VALUES ('6', 'wappagenum', '20');
INSERT INTO `thinklc_setting` VALUES ('6', 'listtemp', 'phone');
INSERT INTO `thinklc_setting` VALUES ('6', 'pagenum', '30');
INSERT INTO `thinklc_setting` VALUES ('6', 'loadtype', '0');
INSERT INTO `thinklc_setting` VALUES ('6', 'top_credit', '1');
INSERT INTO `thinklc_setting` VALUES ('6', 'top_title', '1');
INSERT INTO `thinklc_setting` VALUES ('6', 'top_price', '120');
INSERT INTO `thinklc_setting` VALUES ('6', 'top_off', '2|95,3|90,6|85,12|80');
INSERT INTO `thinklc_setting` VALUES ('6', 'spread_credit', '1');
INSERT INTO `thinklc_setting` VALUES ('6', 'spread_price', '100');
INSERT INTO `thinklc_setting` VALUES ('6', 'spread_off', '2|95,3|90,6|85,12|80');
INSERT INTO `thinklc_setting` VALUES ('group_1', 'info_detail', '0');
INSERT INTO `thinklc_setting` VALUES ('group_1', 'info_pic', '0');
INSERT INTO `thinklc_setting` VALUES ('group_1', 'info_title', '0');
INSERT INTO `thinklc_setting` VALUES ('group_1', 'info_link', '0');
INSERT INTO `thinklc_setting` VALUES ('group_1', 'info_score', '0');
INSERT INTO `thinklc_setting` VALUES ('group_1', 'info_pass', '0');
INSERT INTO `thinklc_setting` VALUES ('group_1', 'info_count', '0');
INSERT INTO `thinklc_setting` VALUES ('group_4', 'info_title', '0');
INSERT INTO `thinklc_setting` VALUES ('group_4', 'info_pic', '0');
INSERT INTO `thinklc_setting` VALUES ('group_4', 'info_link', '0');
INSERT INTO `thinklc_setting` VALUES ('group_4', 'info_credit', '0');
INSERT INTO `thinklc_setting` VALUES ('group_4', 'info_pass', '1');
INSERT INTO `thinklc_setting` VALUES ('group_4', 'info_detail', '0');
INSERT INTO `thinklc_setting` VALUES ('group_4', 'info_count', '5');
INSERT INTO `thinklc_setting` VALUES ('group_5', 'info_detail', '1');
INSERT INTO `thinklc_setting` VALUES ('group_5', 'info_pic', '1');
INSERT INTO `thinklc_setting` VALUES ('group_5', 'info_title', '1');
INSERT INTO `thinklc_setting` VALUES ('group_5', 'info_credit', '1');
INSERT INTO `thinklc_setting` VALUES ('group_5', 'info_link', '1');
INSERT INTO `thinklc_setting` VALUES ('group_5', 'info_pass', '1');
INSERT INTO `thinklc_setting` VALUES ('group_5', 'info_count', '10');
INSERT INTO `thinklc_setting` VALUES ('oauth_qq', 'name', 'QQ登录');
INSERT INTO `thinklc_setting` VALUES ('oauth_qq', 'order', '1');
INSERT INTO `thinklc_setting` VALUES ('oauth_qq', 'enable', '0');
INSERT INTO `thinklc_setting` VALUES ('oauth_weibo', 'name', '新浪微博');
INSERT INTO `thinklc_setting` VALUES ('oauth_weibo', 'order', '2');
INSERT INTO `thinklc_setting` VALUES ('oauth_weibo', 'enable', '0');
INSERT INTO `thinklc_setting` VALUES ('oauth_msn', 'name', 'MSN');
INSERT INTO `thinklc_setting` VALUES ('oauth_msn', 'order', '3');
INSERT INTO `thinklc_setting` VALUES ('oauth_msn', 'enable', '0');
INSERT INTO `thinklc_setting` VALUES ('pay_alipay', 'name', '支付宝');
INSERT INTO `thinklc_setting` VALUES ('pay_alipay', 'enable', '0');
INSERT INTO `thinklc_setting` VALUES ('pay_alipay', 'order', '1');
INSERT INTO `thinklc_setting` VALUES ('pay_tenpay', 'name', '财付通');
INSERT INTO `thinklc_setting` VALUES ('pay_tenpay', 'enable', '0');
INSERT INTO `thinklc_setting` VALUES ('pay_tenpay', 'order', '2');
INSERT INTO `thinklc_setting` VALUES ('pay_chinabank', 'name', '网银在线');
INSERT INTO `thinklc_setting` VALUES ('pay_chinabank', 'order', '3');
INSERT INTO `thinklc_setting` VALUES ('pay_chinabank', 'enable', '0');
INSERT INTO `thinklc_setting` VALUES ('pay_paypal', 'name', 'Paypal');
INSERT INTO `thinklc_setting` VALUES ('pay_paypal', 'order', '4');
INSERT INTO `thinklc_setting` VALUES ('pay_paypal', 'enable', '0');
INSERT INTO `thinklc_setting` VALUES ('0', 'site_url', 'http://thinklc.com');
INSERT INTO `thinklc_setting` VALUES ('7', 'top_off_3', '3|90,6|85,12|80');
INSERT INTO `thinklc_setting` VALUES ('7', 'top_off_1', '3|95,6|90,12|85');
INSERT INTO `thinklc_setting` VALUES ('7', 'top_price', '100|150,300|400,600|600,1000|1000');
INSERT INTO `thinklc_setting` VALUES ('7', 'top_link', '1');
INSERT INTO `thinklc_setting` VALUES ('7', 'top_pic', '1');
INSERT INTO `thinklc_setting` VALUES ('7', 'top_detail', '1');
INSERT INTO `thinklc_setting` VALUES ('7', 'top_title', '1');
INSERT INTO `thinklc_setting` VALUES ('7', 'top_credit', '0');
INSERT INTO `thinklc_setting` VALUES ('7', 'contact_msn', '1');
INSERT INTO `thinklc_setting` VALUES ('7', 'contact_ali', '0');
INSERT INTO `thinklc_setting` VALUES ('7', 'contact_add', '1');
INSERT INTO `thinklc_setting` VALUES ('7', 'contact_qq', '1');
INSERT INTO `thinklc_setting` VALUES ('7', 'contact_extend', '1');
INSERT INTO `thinklc_setting` VALUES ('7', 'cell_width', '478|370|562|946');
INSERT INTO `thinklc_setting` VALUES ('7', 'cell_contentlen', '100');
INSERT INTO `thinklc_setting` VALUES ('7', 'cell_titlelen', '100');
INSERT INTO `thinklc_setting` VALUES ('7', 'water', '0');
INSERT INTO `thinklc_setting` VALUES ('7', 'cell_height', '140');
INSERT INTO `thinklc_setting` VALUES ('7', 'loadtype', '0');
INSERT INTO `thinklc_setting` VALUES ('7', 'pagenum', '10');
INSERT INTO `thinklc_setting` VALUES ('7', 'area_alias', '1');
INSERT INTO `thinklc_setting` VALUES ('7', 'listtemp', 'article');
INSERT INTO `thinklc_setting` VALUES ('7', 'cate_alias', '1');
INSERT INTO `thinklc_setting` VALUES ('7', 'description', 'lamp linux apache mysql php');
INSERT INTO `thinklc_setting` VALUES ('7', 'keywords', 'php');
INSERT INTO `thinklc_setting` VALUES ('7', 'title', 'php');
INSERT INTO `thinklc_setting` VALUES ('7', 'top_off_5', '2|95,3|90,6|85,12|80');

-- ----------------------------
-- Table structure for `thinklc_tag`
-- ----------------------------
DROP TABLE IF EXISTS `thinklc_tag`;
CREATE TABLE `thinklc_tag` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `count` mediumint(6) unsigned NOT NULL,
  `module` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`) USING BTREE,
  KEY `module` (`module`) USING BTREE,
  KEY `count` (`count`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='标签表，存放文章标签';

-- ----------------------------
-- Records of thinklc_tag
-- ----------------------------
INSERT INTO `thinklc_tag` VALUES ('1', '博客', '1', 'Blog');
INSERT INTO `thinklc_tag` VALUES ('2', 'bug', '1', 'Blog');
INSERT INTO `thinklc_tag` VALUES ('3', 'php', '1', 'Blog');
INSERT INTO `thinklc_tag` VALUES ('4', 'js', '8', 'Blog');
INSERT INTO `thinklc_tag` VALUES ('5', 'javascript', '3', 'Blog');
INSERT INTO `thinklc_tag` VALUES ('6', 'html', '3', 'Blog');
INSERT INTO `thinklc_tag` VALUES ('7', 'css', '3', 'Blog');
INSERT INTO `thinklc_tag` VALUES ('8', 'asdasd', '1', 'Article');
INSERT INTO `thinklc_tag` VALUES ('9', 'zhengzs', '1', 'Article');
INSERT INTO `thinklc_tag` VALUES ('11', 'rrr', '1', 'Article');
INSERT INTO `thinklc_tag` VALUES ('12', '文章', '1', 'Article');
INSERT INTO `thinklc_tag` VALUES ('13', '啦啊', '1', 'Article');
INSERT INTO `thinklc_tag` VALUES ('14', 'lamp', '1', 'Article');
INSERT INTO `thinklc_tag` VALUES ('15', 'linux', '1', 'Article');

-- ----------------------------
-- Table structure for `thinklc_tagged`
-- ----------------------------
DROP TABLE IF EXISTS `thinklc_tagged`;
CREATE TABLE `thinklc_tagged` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `record_id` int(11) unsigned NOT NULL,
  `tag_id` int(11) NOT NULL,
  `tag_time` int(11) NOT NULL,
  `module` varchar(25) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `module` (`module`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COMMENT='标签文章关联表';

-- ----------------------------
-- Records of thinklc_tagged
-- ----------------------------
INSERT INTO `thinklc_tagged` VALUES ('1', '0', '1', '1', '1362121398', 'Blog');
INSERT INTO `thinklc_tagged` VALUES ('2', '0', '1', '2', '1362121398', 'Blog');
INSERT INTO `thinklc_tagged` VALUES ('3', '0', '2', '3', '1362121522', 'Blog');
INSERT INTO `thinklc_tagged` VALUES ('9', '0', '3', '4', '1363617681', 'Blog');
INSERT INTO `thinklc_tagged` VALUES ('10', '0', '3', '5', '1363617681', 'Blog');
INSERT INTO `thinklc_tagged` VALUES ('20', '0', '3', '5', '1365948054', 'Blog');
INSERT INTO `thinklc_tagged` VALUES ('19', '0', '3', '4', '1365948054', 'Blog');
INSERT INTO `thinklc_tagged` VALUES ('13', '0', '4', '6', '1365433469', 'Blog');
INSERT INTO `thinklc_tagged` VALUES ('14', '0', '4', '7', '1365433469', 'Blog');
INSERT INTO `thinklc_tagged` VALUES ('21', '0', '16', '8', '1370135866', 'Article');
INSERT INTO `thinklc_tagged` VALUES ('22', '0', '8', '9', '1370139246', 'Article');
INSERT INTO `thinklc_tagged` VALUES ('24', '0', '12', '11', '1370139311', 'Article');
INSERT INTO `thinklc_tagged` VALUES ('25', '0', '18', '12', '1370155997', 'Article');
INSERT INTO `thinklc_tagged` VALUES ('26', '0', '18', '13', '1370155997', 'Article');
INSERT INTO `thinklc_tagged` VALUES ('27', '0', '19', '14', '1370182342', 'Article');
INSERT INTO `thinklc_tagged` VALUES ('28', '0', '19', '15', '1370182342', 'Article');

-- ----------------------------
-- Table structure for `thinklc_webpage`
-- ----------------------------
DROP TABLE IF EXISTS `thinklc_webpage`;
CREATE TABLE `thinklc_webpage` (
  `id` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(20) NOT NULL,
  `content` text NOT NULL,
  `alias` varchar(20) NOT NULL,
  `listorder` tinyint(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of thinklc_webpage
-- ----------------------------
