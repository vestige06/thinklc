<?php
//前台会员组和后台公用函数库
function money_add($uname, $money) {
	$user = M('Member');
	$user->where("uname='".$uname."'")->setInc('money',$money);
	if($money>0) $user->where("uname='".$uname."'")->setInc('amount',$money); 
}
function money_record($uname, $amount = 0, $bank = '站内', $editor = 'system', $reason = '账户充值', $note = '') {
	if($uname && $amount) {
		$user = M('Member');
		$tmpuser = $user->where("uname='".$uname."'")->find();
		$balance = $tmpuser['money'];

		$money = M('Money');
		$data = array();
		$data['uname'] = $uname;
		$data['bank'] = $bank;
		$data['amount'] = $amount;
		$data['balance'] = $balance;
		$data['addtime'] = time();
		$data['reason'] = $reason;
		$data['note'] = $note;
		$data['editor'] = $editor;
		$money->add($data);
	}
}
function credit_add($uname, $credit) {
	$user = M('Member');
	$user->where("uname='".$uname."'")->setInc('credit',$credit);
}
function credit_record($uname, $amount = 0, $editor = 'system', $reason = '积分奖励', $note = '') {
	if($uname && $amount) {
		$user = M('Member');
		$tmpuser = $user->where("uname='".$uname."'")->find();
		$balance = $tmpuser['credit'];

		$credit = M('Credit');
		$data = array();
		$data['uname'] = $uname;
		$data['amount'] = $amount;
		$data['balance'] = $balance;
		$data['addtime'] = time();
		$data['reason'] = $reason;
		$data['note'] = $note;
		$data['editor'] = $editor;
		$credit->add($data);
	}
}
function getAddress($client_ip = '') {
	$client_ip = empty($client_ip) ? get_client_ip() : $client_ip;
	import("ORG.Net.IpLocation"); // 导入IpLocation类
	$Ip = new IpLocation("UTFWry.dat"); // 实例化类 参数表示IP地址库文件
	$area = $Ip->getlocation($client_ip); // 获取某个IP地址所在的位置
	return mb_convert_encoding($area['country'],"utf-8","gb2312"); // 编码转换
}
function closeDialog($id, $str = '', $reload = 1) {
	$tmpstr = '<script style="text/javascript">';
	if(!empty($str)) $tmpstr .= 'alert("'.$str.'");';
	if($reload) $tmpstr .= 'window.top.right.location.reload();';
	$tmpstr .= 'window.top.art.dialog({id:"'.$id.'"}).close();</script>';
	echo $tmpstr;
}
function dround($var, $precision = 2, $sprinft = false) {
	$var = round(floatval($var), $precision);
	if($sprinft) $var = sprintf('%.'.$precision.'f', $var);
	return $var;
}
function tips($tips) {
	return '&nbsp;<img src="__PUBLIC__/Images/icon/help.png" align="absmiddle" alt="'.$tips.'" style="cursor: pointer" onclick="window.top.art.dialog({content:\''.$tips.'\',lock:true}, function(){this.close();$(obj).focus();})" />';
}
/**
	 * 日期时间控件
	 * 
	 * @param $name 控件name，id
	 * @param $value 选中值
	 * @param $isdatetime 是否显示时间
	 * @param $loadjs 是否重复加载js，防止页面程序加载不规则导致的控件无法显示
	 * @param $showweek 是否显示周，使用，true | false
*/
function show_date($name, $value = '', $isdatetime = 0, $loadjs = 0, $showweek = 'true', $timesystem = 1) {
	if($value == '0000-00-00 00:00:00' || $value == 0) $value = '';
	$id = preg_match("/\[(.*)\]/", $name, $m) ? $m[1] : $name;
	if($isdatetime) {
		$size = 21;
		$format = '%Y-%m-%d %H:%M:%S';
		if($timesystem){
			$showsTime = 'true';
		} else {
			$showsTime = '12';
		}	
	} else {
		$size = 10;
		$format = '%Y-%m-%d';
		$showsTime = 'false';
	}
	$str = '';
	if($loadjs || !defined('CALENDAR_INIT')) {
			define('CALENDAR_INIT', 1);
			$str .= '<link rel="stylesheet" type="text/css" href="__PUBLIC__/Js/calendar/jscal2.css"/>
			<link rel="stylesheet" type="text/css" href="__PUBLIC__/Js/calendar/border-radius.css"/>
			<link rel="stylesheet" type="text/css" href="__PUBLIC__/Js/calendar/win2k.css"/>
			<script type="text/javascript" src="__PUBLIC__/Js/calendar/calendar.js"></script>
			<script type="text/javascript" src="__PUBLIC__/Js/calendar/lang/en.js"></script>';
	}
	$str .= '<input type="text" name="'.$name.'" id="'.$id.'" value="'.$value.'" size="'.$size.'" class="date" readonly>&nbsp;';
	$str .= '<script type="text/javascript">
			Calendar.setup({
			weekNumbers: '.$showweek.',
		    inputField : "'.$id.'",
		    trigger    : "'.$id.'",
		    dateFormat: "'.$format.'",
		    showTime: '.$showsTime.',
		    minuteStep: 1,
		    onSelect   : function() {this.hide();}
			});
        </script>';
	return $str;
}
function show_style($name, $value = '') {
	global $thinklc_style_id;
	$style = $color = '';
	if(preg_match("/^#[0-9a-zA-Z]{6}$/", $value)) $color = $value;
	if(!$thinklc_style_id) {
		$thinklc_style_id = 1;
		$style .= '<script type="text/javascript" src="__PUBLIC__/Js/color.js"></script>';
	} else {
		$thinklc_style_id++;
	}
	$style .= '<input type="hidden" name="'.$name.'" id="color_input_'.$thinklc_style_id.'" value="'.$color.'"/><img src="__PUBLIC__/Images/color.gif" width="21" height="18" align="absmiddle" id="color_img_'.$thinklc_style_id.'" style="cursor:pointer;background:'.$color.'" onclick="color_show('.$thinklc_style_id.', $(\'#color_input_'.$thinklc_style_id.'\').val(), this);"/>';
	return $style;
}
//创建目录
function createDir($dir){ 
	return is_dir($dir) or (createdir(dirname($dir)) and mkdir($dir, 0777)); 
}
function buildIndex() {
	$config = require("./config.inc.php");
	$modulename = $config['DEFAULT_MODULE'];  //获取前台默认模块名
	$url = C('site_url').'/index.php?g=Home&m='.$modulename.'&a=buildindex'; //前台默认模块生成成静态页方法
	$result = file_get_contents($url);
	$charset[1] = substr($result, 0, 1);
	$charset[2] = substr($result, 1, 1);
	$charset[3] = substr($result, 2, 1);
	if (ord($charset[1]) == 239 && ord($charset[2]) == 187 && ord($charset[3]) == 191) $result = substr($result, 3); //去除BOM头
	return $result;
}
function _authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	$ckey_length = 4;

	$key = md5($key ? $key : UC_KEY);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
				return '';
			}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}
}
function encrypt($txt, $key = '') {
	$rnd = md5(microtime());
	$len = strlen($txt);
	$ren = strlen($rnd);
	$ctr = 0;
	$str = '';
	for($i = 0; $i < $len; $i++) {
		$ctr = $ctr == $ren ? 0 : $ctr;
		$str .= $rnd[$ctr].($txt[$i] ^ $rnd[$ctr++]);
	}
	return str_replace('=', '', base64_encode(kecrypt($str, $key)));
}
function decrypt($txt, $key = '') {
	$txt = kecrypt(base64_decode($txt), $key);
	$len = strlen($txt);
	$str = '';
	for($i = 0; $i < $len; $i++) {
		$tmp = $txt[$i];
		$str .= $txt[++$i] ^ $tmp;
	}
	return $str;
}
function kecrypt($txt, $key) {
	$key = md5($key);
	$len = strlen($txt);
	$ken = strlen($key);
	$ctr = 0;
	$str = '';
	for($i = 0; $i < $len; $i++) {
		$ctr = $ctr == $ken ? 0 : $ctr;
		$str .= $txt[$i] ^ $key[$ctr++];
	}
	return $str;
}
function file_del($filename) {
	@chmod($filename, 0777);
	return is_file($filename) ? @unlink($filename) : false;
}
function pic_del($picurl) {
	if(C('ftp_enable') && C('remote_url') && file_exists(LIBRARY_PATH.'ORG/Net/Ftp.class.php') && false !== strpos($picurl,C('remote_url'))) {
		$ftp_pasv = is_null(C('ftp_pasv')) ? 0 : C('ftp_pasv');
		$ftp_ssl = is_null(C('ftp_ssl')) ? 0 : C('ftp_ssl');
		$ftp_path = is_null(C('ftp_path')) ? '/' : C('ftp_path');
		$picurl = $ftp_path.str_replace(C('remote_url'),'',$picurl);
		import("ORG.Net.Ftp");
		$ftp = new Ftp;
		if($ftp->connect(C('ftp_host'), C('ftp_user'), C('ftp_pass'), C('ftp_port'), $ftp_pasv, $ftp_ssl, 25)) {
			return $ftp->f_delete($picurl);
		} else return false;
	} else {
		$picurl = ROOT_PATH.$picurl;
		@chmod($picurl, 0777);
		return is_file($picurl) ? @unlink($picurl) : false;
	}
}
function get_picurl($remote) {
	$picurl = '/Uploads/'.$remote;
	if(C('ftp_enable') && C('remote_url') && file_exists(LIBRARY_PATH.'ORG/Net/Ftp.class.php')) {
		import("ORG.Net.Ftp");
		$ftp_pasv = is_null(C('ftp_pasv')) ? 0 : C('ftp_pasv');
		$ftp_ssl = is_null(C('ftp_ssl')) ? 0 : C('ftp_ssl');
		$ftp_path = is_null(C('ftp_path')) ? '/' : C('ftp_path');
		$ftp = new Ftp;
		if($ftp->connect(C('ftp_host'), C('ftp_user'), C('ftp_pass'), C('ftp_port'), $ftp_pasv, $ftp_ssl, 25)) {
			//$ftp->chdir($ftp_path);
			if($ftp->put($ftp_path.$remote, ROOT_PATH.'/Uploads/'.$remote)) {
				$picurl = C('remote_url').$remote;
				file_del(ROOT_PATH.'/Uploads/'.$remote);
			}
		}
	}
	return $picurl;
}