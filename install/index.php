<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: index.php 22348 2011-05-04 01:16:02Z monkey $
 */

error_reporting(E_ERROR | E_WARNING | E_PARSE);
@set_time_limit(1000);
@set_magic_quotes_runtime(0);

define('IN_THINKLC', TRUE);
define('ROOT_PATH', dirname(__FILE__).'/../');

require ROOT_PATH.'./version.inc.php';
require ROOT_PATH.'./install/include/install_var.php';
require ROOT_PATH.'./install/include/install_mysql.php';
require ROOT_PATH.'./install/include/install_function.php';
require ROOT_PATH.'./install/include/install_lang.php';

$view_off = getgpc('view_off');

define('VIEW_OFF', $view_off ? TRUE : FALSE);

$allow_method = array('show_license', 'env_check', 'app_reg', 'db_init', 'ext_info', 'install_check', 'tablepre_check');

$step = intval(getgpc('step', 'R')) ? intval(getgpc('step', 'R')) : 0;
$method = getgpc('method');

if(empty($method) || !in_array($method, $allow_method)) {
	$method = isset($allow_method[$step]) ? $allow_method[$step] : '';
}

if(empty($method)) {
	show_msg('method_undefined', $method, 0);
}

if(file_exists($lockfile) && $method != 'ext_info') {
	show_msg('install_locked', '', 0);
} elseif(!class_exists('dbstuff')) {
	show_msg('database_nonexistence', '', 0);
}

timezone_set();

$uchidden = getgpc('uchidden');

if(in_array($method, array('app_reg', 'ext_info', 'db_init'))) {
	$PHP_SELF = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
	$thinkserver = 'http://'.preg_replace("/\:\d+/", '', $_SERVER['HTTP_HOST']).($_SERVER['SERVER_PORT'] && $_SERVER['SERVER_PORT'] != 80 ? ':'.$_SERVER['SERVER_PORT'] : '');
	$default_ucapi = $thinkserver.'/ucenter';
	$default_appurl = $thinkserver.substr($PHP_SELF, 0, strrpos($PHP_SELF, '/') - 8);
}

if($method == 'show_license') {

	transfer_ucinfo($_POST);
	show_license();

} elseif($method == 'env_check') {

	VIEW_OFF && function_check($func_items);

	env_check($env_items);

	dirfile_check($dirfile_items);

	show_env_result($env_items, $dirfile_items, $func_items, $filesock_items);

} elseif($method == 'app_reg') {

	//@include ROOT_PATH.CONFIG;
	//@include ROOT_PATH.CONFIG_UC;
	if(!defined('UC_API')) {
		define('UC_API', '');
	}
	if(empty($uchidden)) {
		header("Location: index.php?step=3&from_ucenter=no");
		die;
	}
	$submit = true;
	$error_msg = array();
	if(isset($form_app_reg_items) && is_array($form_app_reg_items)) {
		foreach($form_app_reg_items as $key => $items) {
			$$key = getgpc($key, 'p');
			if(!isset($$key) || !is_array($$key)) {
				$submit = false;
				break;
			}
			foreach($items as $k => $v) {
				$tmp = $$key;
				$$k = $tmp[$k];
				if(empty($$k) || !preg_match($v['reg'], $$k)) {
					if(empty($$k) && !$v['required']) {
						continue;
					}
					$submit = false;
					VIEW_OFF or $error_msg[$key][$k] = 1;
				}
			}
		}
	} else {
		$submit = false;
	}

 	$ucapi = defined('UC_API') && UC_API ? UC_API : $default_ucapi;

	if($submit) {

		$app_type = 'OTHER';

		$app_name = $sitename ? $sitename : SOFT_NAME;
		$app_url = $siteurl ? $siteurl : $default_appurl;

		$ucapi = $ucurl ? $ucurl : (defined('UC_API') && UC_API ? UC_API : $default_ucapi);
		$ucip = isset($ucip) ? $ucip : '';
		$ucfounderpw = $ucpw;
		$app_tagtemplates = '';

		$ucapi = preg_replace("/\/$/", '', trim($ucapi));
		if(empty($ucapi) || !preg_match("/^(http:\/\/)/i", $ucapi)) {
			show_msg('uc_url_invalid', $ucapi, 0);
		} else {
			if(!$ucip) {
				$temp = @parse_url($ucapi);
				$ucip = gethostbyname($temp['host']);
				if(ip2long($ucip) == -1 || ip2long($ucip) === FALSE) {
					show_msg('uc_dns_error', $ucapi, 0);
				}
			}
		}
		include_once ROOT_PATH.'./api/uc_client/client.php';

		$ucinfo = dfopen($ucapi.'/index.php?m=app&a=ucinfo&release='.UC_CLIENT_RELEASE, 500, '', '', 1, $ucip);
		list($status, $ucversion, $ucrelease, $uccharset, $ucdbcharset, $apptypes) = explode('|', $ucinfo);
		if($status != 'UC_STATUS_OK') {
			show_msg('uc_url_unreachable', $ucapi, 0);
		} else {
			$dbcharset = strtolower($dbcharset ? str_replace('-', '', $dbcharset) : $dbcharset);
			$ucdbcharset = strtolower($ucdbcharset ? str_replace('-', '', $ucdbcharset) : $ucdbcharset);
			if(UC_CLIENT_VERSION > $ucversion) {
				show_msg('uc_version_incorrect', $ucversion, 0);
			} elseif($dbcharset && $ucdbcharset != $dbcharset) {
				show_msg('uc_dbcharset_incorrect', '', 0);
			}

			$postdata = 'm=app&a=add&ucfounder=&ucfounderpw='.urlencode($ucpw).'&apptype='.urlencode($app_type).'&appname='.urlencode($app_name).'&appurl='.urlencode($app_url).'&appip=&appcharset='.CHARSET.'&appdbcharset='.DBCHARSET.'&release='.S_RELEASE;
			$ucconfig = dfopen($ucapi.'/index.php', 500, $postdata, '', 1, $ucip);
			if(empty($ucconfig)) {
				show_msg('uc_api_add_app_error', $ucapi, 0);
			} elseif($ucconfig == '-1') {
				show_msg('uc_admin_invalid', '', 0);
			} else {
				list($appauthkey, $appid) = explode('|', $ucconfig);
				$ucconfig_array = explode('|', $ucconfig);
				$ucconfig_array[] = $ucapi;
				$ucconfig_array[] = $ucip;
				if(empty($appauthkey) || empty($appid)) {
					show_msg('uc_data_invalid', '', 0);
				} elseif($succeed = save_uc_config($ucconfig_array, ROOT_PATH.'./Data/ucconfig.php')) {
					if(VIEW_OFF) {
						show_msg('app_reg_success');
					} else {
						$step = $step + 1;
						header("Location: index.php?step=$step");
						exit;
					}
				} else {
					show_msg('config_unwriteable', '', 0);
				}
			}
		}

	}
	if(VIEW_OFF) {

		show_msg('missing_parameter', '', 0);

	} else {

		show_form($form_app_reg_items, $error_msg);

	}

} elseif($method == 'db_init') {

	if(getgpc('from_ucenter') == 'no') {
		define('FROMUC', false);
	} else {
		define('FROMUC', true);
	}

	$submit = true;

	$default_config = $_config = array();
	$default_configfile = './config.default.php';

	if(!file_exists(ROOT_PATH.$default_configfile)) {
		exit('config.default.php was lost, please reupload this file.');
	} else {
		$default_config = include ROOT_PATH.$default_configfile;
	}

	if(file_exists(ROOT_PATH.CONFIG)) {
		$_config = include ROOT_PATH.CONFIG;
	} else {
		$_config = $default_config;
	}

	$dbhost = $_config['DB_HOST'];
	$dbname = $_config['DB_NAME'];
	$dbpw = $_config['DB_PWD'];
	$dbuser = $_config['DB_USER'];
	$tablepre = $_config['DB_PREFIX'];

	$error_msg = array();
	if(isset($form_db_init_items) && is_array($form_db_init_items)) {
		foreach($form_db_init_items as $key => $items) {
			$$key = getgpc($key, 'p');
			if(!isset($$key) || !is_array($$key)) {
				$submit = false;
				break;
			}
			foreach($items as $k => $v) {
				$tmp = $$key;
				$$k = $tmp[$k];
				if(empty($$k) || !preg_match($v['reg'], $$k)) {
					if(empty($$k) && !$v['required']) {
						continue;
					}
					$submit = false;
					VIEW_OFF or $error_msg[$key][$k] = 1;
				}
			}
		}
	} else {
		$submit = false;
	}

	if($submit && !VIEW_OFF && $_SERVER['REQUEST_METHOD'] == 'POST') {
		if($password != $password2) {
			$error_msg['admininfo']['password2'] = 1;
			$submit = false;
		}
		$forceinstall = isset($_POST['dbinfo']['forceinstall']) ? $_POST['dbinfo']['forceinstall'] : '';
		$dbname_not_exists = true;
		if(!empty($dbhost) && empty($forceinstall)) {
			$dbname_not_exists = check_db($dbhost, $dbuser, $dbpw, $dbname, $tablepre);
			if(!$dbname_not_exists) {
				$form_db_init_items['dbinfo']['forceinstall'] = array('type' => 'checkbox', 'required' => 0, 'reg' => '/^.*+/');
				$error_msg['dbinfo']['forceinstall'] = 1;
				$submit = false;
				$dbname_not_exists = false;
			}
		}
	}

	if($submit) {

		$step = $step + 1;
		if(empty($dbname)) {
			show_msg('dbname_invalid', $dbname, 0);
		} else {
			$link = @mysql_connect($dbhost, $dbuser, $dbpw);
			if(!$link) {
				$errno = mysql_errno($link);
				$error = mysql_error($link);
				if($errno == 1045) {
					show_msg('database_errno_1045', $error, 0);
				} elseif($errno == 2003) {
					show_msg('database_errno_2003', $error, 0);
				} else {
					show_msg('database_connect_error', $error, 0);
				}
			}

			if(mysql_get_server_info() > '4.1') {
				mysql_query("CREATE DATABASE IF NOT EXISTS `$dbname` DEFAULT CHARACTER SET ".DBCHARSET, $link);
			} else {
				mysql_query("CREATE DATABASE IF NOT EXISTS `$dbname`", $link);
			}

			if(mysql_errno()) {
				show_msg('database_errno_1044', mysql_error(), 0);
			}
			mysql_close($link);
		}

		if(strpos($tablepre, '.') !== false || intval($tablepre{0})) {
			show_msg('tablepre_invalid', $tablepre, 0);
		}

		if($username && $password) {
			if(strlen($username) > 15 || preg_match("/^$|^c:\\con\\con$|　|[,\"\s\t\<\>&]|^Guest/is", $username)) {
				show_msg('admin_username_invalid', $username, 0);
			} else {
				/*
				if(FROMUC) {
					$adminuser = check_adminuser($username, $password, $email);
					if($adminuser['uid'] < 1) {
						show_msg($adminuser['error'], '', 0);
					}
				}
				*/
			}
		} else {
			show_msg('admininfo_invalid', '', 0);
		}

		//$uid = FROMUC ? $adminuser['uid'] : 1;
		$config = array();
		$config['DB_HOST'] = $_config['DB_HOST'] = $dbhost;
		$config['DB_USER'] = $_config['DB_USER'] = $dbuser;
		$config['DB_PWD'] = $_config['DB_PWD'] = $dbpw;
		$config['DB_NAME'] = $_config['DB_NAME'] = $dbname;
		$config['DB_PREFIX'] = $_config['DB_PREFIX'] = $tablepre;

		save_config_file(ROOT_PATH.CONFIG, $config);

		$db = new dbstuff;

		$db->connect($dbhost, $dbuser, $dbpw, $dbname, DBCHARSET);

		if(!VIEW_OFF) {
			show_header();
			show_install();
		}

		$sql = file_get_contents($sqlfile);
		$sql = str_replace("\r\n", "\n", $sql);

		runquery($sql);

		$sql = file_get_contents(ROOT_PATH.'./install/data/install_data.sql');
		$sql = str_replace("\r\n", "\n", $sql);
		runquery($sql);

		install_ucconfig();

		$password = md5($password);
		$db->query("INSERT INTO {$tablepre}admin (id, rid, founder, aname, apwd, loginip, logintime, count, status) VALUES (1, 0, 1, '$username', '$password', '', 0, 0, 1);");

		$installtime = time();
		$db->query("INSERT INTO {$tablepre}module VALUES(1, 'Member', '会员中心', 4, 1, 1, ".$installtime.", 1, 0, '');");
		$db->query("INSERT INTO {$tablepre}module VALUES(2, 'Help', '帮助中心', 3, 1, 1, ".$installtime.", 1, 0, '');");
		$db->query("INSERT INTO {$tablepre}module VALUES(3, 'Webpage', '关于我们', 5, 1, 0, ".$installtime.", 1, 0, '');");
		$db->query("INSERT INTO {$tablepre}module VALUES(4, 'Link', '友情链接', 6, 1, 0, ".$installtime.", 1, 0, '');");
		$db->query("INSERT INTO {$tablepre}module VALUES(5, 'Info', '综合信息', 1, 1, 1, ".$installtime.", 1, 0, '');");
		$db->query("INSERT INTO {$tablepre}module VALUES(6, 'Phone', '电话黄页', 2, 1, 1, ".$installtime.", 1, 0, '');");

		$db->query("INSERT INTO {$tablepre}setting (item, item_key, item_value) VALUES ('0', 'site_url', '$default_appurl');");

		$testdata = 0;
		if($testdata) {
			install_testdata($username, $uid);
		}

		touch($lockfile);
		VIEW_OFF && show_msg('initdbresult_succ');

		if(!VIEW_OFF) {
			echo '<script type="text/javascript">function setlaststep() {document.getElementById("laststep").disabled=false;window.location=\'index.php?method=ext_info\';}</script><script type="text/javascript">setTimeout(function(){window.location=\'index.php?method=ext_info\'}, 30000);</script><iframe src="/admin.php?m=Public&a=initsys" style="display:none;" onload="setlaststep()"></iframe>'."\r\n";
			show_footer();
		}

	}
	if(VIEW_OFF) {

		show_msg('missing_parameter', '', 0);

	} else {
		show_form($form_db_init_items, $error_msg);

	}

} elseif($method == 'ext_info') {
	@touch($lockfile);
	if(VIEW_OFF) {
		show_msg('ext_info_succ');
	} else {
		show_header();
		echo '</div><div class="main" style="margin-top: -123px;"><ul style="line-height: 200%; margin-left: 30px;">';
		echo '<div id="platformIntro">'.lang('install_succeed').'</div>';
		echo '</ul></div>';
		show_footer();
	}

} elseif($method == 'install_check') {

	if(file_exists($lockfile)) {
		show_msg('installstate_succ');
	} else {
		show_msg('lock_file_not_touch', $lockfile, 0);
	}

} elseif($method == 'tablepre_check') {

	$dbinfo = getgpc('dbinfo');
	extract($dbinfo);
	if(check_db($dbhost, $dbuser, $dbpw, $dbname, $tablepre)) {
		show_msg('tablepre_not_exists', 0);
	} else {
		show_msg('tablepre_exists', $tablepre, 0);
	}
}