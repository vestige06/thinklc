DROP TABLE IF EXISTS `thinklc_admin`;
CREATE TABLE IF NOT EXISTS `thinklc_admin` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='管理员' AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `thinklc_area`;
CREATE TABLE IF NOT EXISTS `thinklc_area` (
  `id` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `aname` varchar(20) NOT NULL,
  `alias` varchar(20) NOT NULL,
  `listorder` tinyint(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='地区' AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `thinklc_category`;
CREATE TABLE IF NOT EXISTS `thinklc_category` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='栏目分类' AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `thinklc_charge`;
CREATE TABLE IF NOT EXISTS `thinklc_charge` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='充值记录' AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `thinklc_credit`;
CREATE TABLE IF NOT EXISTS `thinklc_credit` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='积分记录' AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `thinklc_etype`;
CREATE TABLE IF NOT EXISTS `thinklc_etype` (
  `id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `listorder` smallint(4) NOT NULL DEFAULT '0',
  `typename` varchar(30) NOT NULL DEFAULT '',
  `item` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `item` (`item`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='扩展分类' AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `thinklc_group`;
CREATE TABLE IF NOT EXISTS `thinklc_group` (
  `id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `gname` varchar(10) NOT NULL,
  `upgrade` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `price` smallint(4) unsigned NOT NULL DEFAULT '0',
  `price2` smallint(4) unsigned NOT NULL DEFAULT '0',
  `listorder` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `system` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `usescore` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='会员组' AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `thinklc_help`;
CREATE TABLE IF NOT EXISTS `thinklc_help` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='帮助' AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `thinklc_info`;
CREATE TABLE IF NOT EXISTS `thinklc_info` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='综合信息' AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `thinklc_limit`;
CREATE TABLE IF NOT EXISTS `thinklc_limit` (
  `uid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `moduleid` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `lasttime` int(10) unsigned NOT NULL DEFAULT '0',
  `item` varchar(10) NOT NULL,
  `val` smallint(4) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `uid` (`uid`,`moduleid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `thinklc_link`;
CREATE TABLE IF NOT EXISTS `thinklc_link` (
  `id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `tid` smallint(4) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `listorder` smallint(4) NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `linkurl` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='友情链接' AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `thinklc_member`;
CREATE TABLE IF NOT EXISTS `thinklc_member` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户' AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `thinklc_menu`;
CREATE TABLE IF NOT EXISTS `thinklc_menu` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='菜单' AUTO_INCREMENT=100 ;

DROP TABLE IF EXISTS `thinklc_module`;
CREATE TABLE IF NOT EXISTS `thinklc_module` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='模型' AUTO_INCREMENT=10 ;

DROP TABLE IF EXISTS `thinklc_money`;
CREATE TABLE IF NOT EXISTS `thinklc_money` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='资金流水' AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `thinklc_oauth`;
CREATE TABLE IF NOT EXISTS `thinklc_oauth` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='一键登录' AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `thinklc_panel`;
CREATE TABLE IF NOT EXISTS `thinklc_panel` (
  `mid` smallint(4) unsigned NOT NULL DEFAULT '0',
  `aid` smallint(4) unsigned NOT NULL DEFAULT '0',
  `mtitle` char(32) NOT NULL,
  `url` char(255) NOT NULL,
  `datetime` int(10) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `aid` (`mid`,`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `thinklc_phone` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `title` char(20) NOT NULL,
  `business` char(50) NOT NULL,
  `tel` char(18) NOT NULL,
  `mobile` char(11) NOT NULL,
  `address` char(30) NOT NULL,
  `map` char(32) NOT NULL,
  `qq` char(12) NOT NULL,
  `areaid` tinyint(1) unsigned NOT NULL default '0',
  `catid` tinyint(2) unsigned NOT NULL default '0',
  `uid` int(4) unsigned NOT NULL default '0',
  `status` tinyint(1) unsigned NOT NULL default '1',
  `toptotime` int(10) unsigned NOT NULL default '0',
  `toptitle` char(10) NOT NULL,
  `spreadtotime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `catid` (`catid`),
  KEY `uid` (`uid`),
  KEY `areaid` (`areaid`),
  KEY `title` (`title`),
  KEY `toptotime` (`toptotime`),
  KEY `status` (`status`),
  KEY `address` (`address`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `thinklc_role`;
CREATE TABLE IF NOT EXISTS `thinklc_role` (
  `id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `rname` varchar(20) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `access` varchar(300) NOT NULL,
  `remark` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='角色权限' AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `thinklc_setting`;
CREATE TABLE IF NOT EXISTS `thinklc_setting` (
  `item` varchar(30) NOT NULL DEFAULT '0',
  `item_key` varchar(20) NOT NULL DEFAULT '',
  `item_value` varchar(200) NOT NULL DEFAULT '',
  KEY `item` (`item`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='网站设置';

DROP TABLE IF EXISTS `thinklc_webpage`;
CREATE TABLE IF NOT EXISTS `thinklc_webpage` (
  `id` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(20) NOT NULL,
  `content` text NOT NULL,
  `alias` varchar(20) NOT NULL,
  `listorder` tinyint(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='单页' AUTO_INCREMENT=1 ;