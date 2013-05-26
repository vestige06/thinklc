<?php
$ucpath = str_replace("\\", '/', substr(dirname(__FILE__), 0, -3));
$uc_mcfg = require($ucpath."./Data/config/module_1.php");
$uc_mcfg['uc_enable'] or exit('Access Denied');
define('UC_CONNECT', $uc_mcfg['uc_mysql'] ? 'mysql' : '');		//数据库连接方式
define('UC_DBHOST', $uc_mcfg['uc_dbhost']);	//数据库存放
define('UC_DBUSER', $uc_mcfg['uc_dbuser']);		//登录名
define('UC_DBPW', $uc_mcfg['uc_dbpw']);		//登录密码
define('UC_DBNAME', $uc_mcfg['uc_dbname']);			//数据库名 (Ucenter 所在数据库名)
define('UC_DBCHARSET', $uc_mcfg['uc_dbcharset']);		//数据库编码
define('UC_DBTABLEPRE', $uc_mcfg['uc_dbtablepre']);	//数据库前缀(`数据库名`.前缀)
define('UC_DBCONNECT', 0);			//是否持久连接（貌似是这个，一般为0）
define('UC_KEY', $uc_mcfg['uc_key']);			//通信密钥
define('UC_API', $uc_mcfg['uc_api']);	//Ucenter服务所在路径
define('UC_CHARSET', 'utf-8');		//字符编码
define('UC_IP', $uc_mcfg['uc_ip']);			//顾名思义（不知道怎么解释）,如果项目和Ucenter在本机就留空
define('UC_APPID', $uc_mcfg['uc_appid']);		//应用ID（在Ucenter添加应用时会生成一个ID）
define('UC_PPP', 20);
define('UC_BBS_PRE', $uc_mcfg['uc_bbs_pre']);
//同步登录 Cookie 设置
$cookiedomain = $uc_mcfg['uc_cookie_domain']; 			// cookie 作用域
$cookiepre = $uc_mcfg['uc_cookie_pre'];			// cookie 前缀
$cookiepath = '/';			// cookie 作用路径