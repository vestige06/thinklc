<?php
$oauth = F('config/oauth');
// 申请到的appid
$_SESSION["appid"] = $oauth['qq']['appid']; 
// 申请到的appkey
$_SESSION["appkey"] = $oauth['qq']['appkey']; 
// QQ登录成功后跳转的地址,请确保地址真实可用，否则会导致登录失败。
$_SESSION["callback"] = C('site_url').'/index.php?g=Member&m=Oauth&a=callback&site=qq';
// QQ授权api接口.按需调用
$_SESSION["scope"] = "get_user_info";

function qq_login( $appid, $scope, $callback ) {
		$_SESSION['state'] = md5( uniqid( rand(), true ) ); //CSRF protection
		$login_url = "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=" . $appid . "&redirect_uri=" . urlencode( $callback ) . "&state=" . $_SESSION['state'] . "&scope=" . $scope;
		header( "Location:$login_url" );
} 

function qq_callback() {
		// debug
		// print_r($_REQUEST);
		// print_r($_SESSION);
		if ( $_REQUEST['state'] == $_SESSION['state'] ) { // csrf
				$token_url = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&" . "client_id=" . $_SESSION["appid"] . "&redirect_uri=" . urlencode( $_SESSION["callback"] ) . "&client_secret=" . $_SESSION["appkey"] . "&code=" . $_REQUEST["code"];

				$response = get_url_contents( $token_url );
				if ( strpos( $response, "callback" ) !== false ) {
						$lpos = strpos( $response, "(" );
						$rpos = strrpos( $response, ")" );
						$response = substr( $response, $lpos + 1, $rpos - $lpos -1 );
						$msg = json_decode( $response );
						if ( isset( $msg -> error ) ) {
								echo "<h3>callback error:</h3>" . $msg -> error;
								echo "<h3>msg  :</h3>" . $msg -> error_description;
								exit;
						} 
				} 

				$params = array();
				parse_str( $response, $params ); 
				// debug
				// print_r($params);
				// set access token to session
				$_SESSION["access_token"] = $params["access_token"];
		} else {
				exit( "The state does not match. You may be a victim of CSRF." );
		} 
} 

function get_openid() {
		$graph_url = "https://graph.qq.com/oauth2.0/me?access_token=" . $_SESSION['access_token'];

		$str = get_url_contents( $graph_url );
		if ( strpos( $str, "callback" ) !== false ) {
				$lpos = strpos( $str, "(" );
				$rpos = strrpos( $str, ")" );
				$str = substr( $str, $lpos + 1, $rpos - $lpos -1 );
		} 

		$user = json_decode( $str );
		if ( isset( $user -> error ) ) {
				echo "<h3>openid error:</h3>" . $user -> error;
				echo "<h3>msg  :</h3>" . $user -> error_description;
				exit;
		} 
		// debug
		// echo("Hello " . $user->openid);
		// set openid to session
		$_SESSION["openid"] = $user -> openid;
} 

function do_post( $url, $data ) {
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
		curl_setopt( $ch, CURLOPT_URL, $url );
		$ret = curl_exec( $ch );

		curl_close( $ch );
		return $ret;
} 

function get_url_contents( $url ) {
		if ( ini_get( "allow_url_fopen" ) == "1" ) {
				$result = file_get_contents( $url );
		} else {
				$ch = curl_init();
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
				curl_setopt( $ch, CURLOPT_URL, $url );
				$result = curl_exec( $ch );
				curl_close( $ch );
		} 
		if ( empty( $result ) ) {
				exit( "<h2>可能是服务器无法请求https协议</h2>可能未开启curl支持,请尝试开启curl支持，重启web服务器，如果问题仍未解决，请联系我们" );
		} 
		return $result;
} 

function get_user_info() {
		$get_user_info = "https://graph.qq.com/user/get_user_info?access_token=" . $_SESSION['access_token'] . "&oauth_consumer_key=" . $_SESSION["appid"] . "&openid=" . $_SESSION["openid"] . "&format=json";

		$info = get_url_contents( $get_user_info );
		$arr = json_decode( $info, true );

		return $arr;
} 

function connect_to_site() {
		qq_login( $_SESSION["appid"], $_SESSION["scope"], $_SESSION["callback"] );
} 

function get_oauthid() {
		// QQ登录成功后的回调地址,主要保存access token
		qq_callback(); 
		// 获取用户标示id
		get_openid();

		return $_SESSION["openid"];
} 

function get_oauthinfo() {
		$arr = get_user_info();
		$oauth_info = array();
		$oauth_info['openid'] = $_SESSION["openid"];
		$oauth_info['nickname'] = $arr['nickname'];
		$oauth_info['avatar'] = $arr['figureurl_1'];
		$oauth_info['url'] = '';
		return $oauth_info;
} 

function del_token() {
		unset( $_SESSION["openid"] );
		unset( $_SESSION['access_token'] );
		unset( $_SESSION["appid"] );
		unset( $_SESSION['appkey'] );
		unset( $_SESSION['scope'] );
		unset( $_SESSION["callback"] );
} 
