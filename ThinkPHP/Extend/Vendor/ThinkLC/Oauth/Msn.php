<?php
//MSN一键登录
$oauth = F('config/oauth');
// Application Specific Globals
define('WRAP_CLIENT_ID', $oauth['msn']['id']);
define('WRAP_CLIENT_SECRET', $oauth['msn']['key']);
define('WRAP_CALLBACK', C('site_url').'/index.php?g=Member&m=Oauth&a=callback&site=msn');
// Live URLs required for making requests.
define('WRAP_CONSENT_URL', 'https://login.live.com/oauth20_authorize.srf');
define('WRAP_ACCESS_URL', 'https://login.live.com/oauth20_token.srf');

function connect_to_site() {
	$aurl = WRAP_CONSENT_URL.'?client_id='.WRAP_CLIENT_ID.'&scope=wl.signin%20wl.basic&response_type=code&redirect_uri='.urlencode(WRAP_CALLBACK);
	header("Location:$aurl");
}
function get_oauthid() {
	if(!$_REQUEST['code']) return;
	$par = 'client_id='.urlencode(WRAP_CLIENT_ID)
		 . '&redirect_uri='.urlencode(WRAP_CALLBACK)
		 . '&client_secret='.urlencode(WRAP_CLIENT_SECRET)
		 . '&code='.urlencode($_REQUEST['code'])
		 . '&grant_type=authorization_code';
	$cur = curl_init(WRAP_ACCESS_URL);
	curl_setopt($cur, CURLOPT_POST, 1);
	curl_setopt($cur, CURLOPT_POSTFIELDS, $par);
	curl_setopt($cur, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($cur, CURLOPT_HEADER, 0);
	curl_setopt($cur, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($cur, CURLOPT_RETURNTRANSFER, 1);
	$rec = curl_exec($cur);
	curl_close($cur);
	if(strpos($rec, 'access_token') !== false) {
		$arr = json_decode($rec, true);
		$_SESSION['wrap_access_token'] = $arr['access_token'];
		$url = 'https://apis.live.net/v5.0/me?access_token='.$arr['access_token'];
		$cur = curl_init($url);
		curl_setopt($cur, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($cur, CURLOPT_HEADER, 0);
		curl_setopt($cur, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($cur, CURLOPT_RETURNTRANSFER, 1);
		$rec = curl_exec($cur);
		curl_close($cur);
		$arr = json_decode($rec, true);
		return $arr['id'];
	} else return;
}
function get_oauth_info() {
	$oauth_info = array();
	if($_SESSION['wrap_access_token']) {
		$url = 'https://apis.live.net/v5.0/me?access_token='.$_SESSION['wrap_access_token'];
		$cur = curl_init($url);
		curl_setopt($cur, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($cur, CURLOPT_HEADER, 0);
		curl_setopt($cur, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($cur, CURLOPT_RETURNTRANSFER, 1);
		$rec = curl_exec($cur);
		curl_close($cur);
		$arr = json_decode($rec, true);
		if(isset($arr['id'])) {
			if($arr['first_name']) {
				$nickname = $arr['first_name'];
			} else {
				$nickname = $arr['emails']['account'];
				$nickname = str_replace(strstr($nickname, '@'), '', $nickname);
			}
			$oauth_info['openid'] = $arr['id'];
			$oauth_info['nickname'] = $nickname;
			$oauth_info['avatar'] = '';
			$oauth_info['url'] = 'https://profile.live.com/cid-'.$arr['id'];
		}
	}
	return $oauth_info;
}
function del_token() {
	unset($_SESSION['wrap_access_token']);
}