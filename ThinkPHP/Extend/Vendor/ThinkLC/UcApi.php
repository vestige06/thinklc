<?php
require_once './api/uc_config.php';
require_once './api/uc_client/client.php';

class UcApi{
    static protected $lastAction = '';
    static protected $lastErrorCode = '';
    static protected $authPre = 'bpi_';
    static protected $uid = '';
    static protected $username = '';
    static protected $password = '';
    static protected $email ='';
    static protected $errorCode = array(
        'reg' => array(
            '-1' => '用户名不合法' ,
            '-2' => '包含不允许注册的词语' ,
            '-3' => '用户名已经存在<br>请更换你的用户名，或者使用已注册帐号登录' ,
            '-4' => 'Email格式有误' ,
            '-5' => 'Email不允许注册' ,
            '-6' => '该Email已经注册' ,
        ) ,  
        'edit' => array(
            '0' => '没有做任何修改' ,
            '-1' => '旧密码错误' ,
            '-4' => 'Email 格式有误' ,
            '-5' => 'Email 不允许注册' ,
            '-6' => '该 Email 已经被注册' ,
            '-7' => '没有做任何修改' ,
            '-8' => '该用户受保护无权限更改' ,
        ) ,    
        'login' => array(
            '-1' => '用户不存在或被删除' ,
            '-2' => '密码错误，请使用UCENTER密码登录' ,
            '-3' => 'Ucenter安全提示问答错误' ,
        ),
        'checkemail' => array(
            '-4' => 'Email格式错误' ,
            '-5' => '该Email不允许注册' ,
            '-6' => '该Email已经被注册' ,
        ),
        'checkname' => array(
            '-1' => '用户名不合法' ,
            '-2' => '包含不允许注册的词语' ,
            '-3' => '用户名已存在' ,
        ),
        'addfeed' => array(
            '0' => '增加事件动态失败' ,
        ),        
    );
    
    static function login($username, $password, $isuid = 0) {
	   if(UC_DBCHARSET == 'gbk' && is_utf8($username)) $username = auto_charset($username, 'utf-8', 'gbk');
       list($uid, $username, $password, $email) = uc_user_login($username, $password, $isuid);
       setcookie(self::$authPre . 'auth', '', -86400);
       if($uid > 0) {
		   if(!is_utf8($username)) $username = auto_charset($username);
           self::$uid = $uid;
           self::$username = $username;
           self::$password = md5($password);
           self::$email = $email;
           setcookie(self::$authPre . 'auth', uc_authcode($uid . "\t" . $username . "\t" . md5($password) . "\t" . $email, 'ENCODE'));
           return array(
               'uid' => $uid ,
               'username' => $username ,
               'password' => $password ,
               'email'  => $email ,
               'synlogin' => uc_user_synlogin($uid),
           );
       } else {
            self::$lastAction = 'login';
            self::$lastErrorCode = $uid;
            return FALSE;
       }
    }

    static function edit($username, $oldpw, $newpw, $email, $ignoreoldpw = 0, $questionid = '', $answer = '') {
		if(UC_DBCHARSET == 'gbk' && is_utf8($username)) $username = auto_charset($username, 'utf-8', 'gbk');
		$edit = uc_user_edit($username, $oldpw, $newpw, $email, $ignoreoldpw, $questionid, $answer);
        if($edit > 0) {
            return TRUE;
        } else {
            self::$lastAction = 'edit';
            self::$lastErrorCode = $edit;
            return FALSE;
        }
    }
    
    static function reg($username, $password, $email, $autologin = false) {
		if(UC_DBCHARSET == 'gbk' && is_utf8($username)) $username = auto_charset($username, 'utf-8', 'gbk');
        $ip = get_client_ip();
        $zhuce = uc_user_register($username, $password, $email, '', '', $ip);
        if($zhuce > 0) {
            if($autologin){
				if(!is_utf8($username)) $username = auto_charset($username);
                self::$uid = $uid;
                self::$username = $username;
                self::$password = md5($password);
                self::$email = $email;
                setcookie(self::$authPre . 'auth', uc_authcode($uid . "\t" . $username . "\t" . md5($password) . "\t" . $email, 'ENCODE'));
            }
            return $zhuce;   //返回UID
        } else {
            self::$lastAction = 'reg';
            self::$lastErrorCode = $zhuce;
            return FALSE;
        }
    }
    
    static function logout() {
        setcookie(self::$authPre . 'auth', '', -86400);
        return uc_user_synlogout();
    }
    
    static function addFeed($uid, $username, $url, $where, $action, $event, $desc, $images =array()) {
		if(UC_DBCHARSET == 'gbk' && is_utf8($username)) $username = auto_charset($username, 'utf-8', 'gbk');
        $feed = array();
        $feed['icon'] = 'thread';
        $feed['title_template'] = '<b>{username} 在{where}{action}了{event}</b>';
        $feed['title_data'] = array(
            'username' => $username ,
            'where' => $where ,
            'action' => $action ,
            'event' => $event ,
            );
        $feed['body_template'] = '<br>{message}';
        $feed['body_data'] = array(
            'message' => cutstr(strip_tags(preg_replace("/\[.+?\]/is", '', $desc)), 150) ,
        );
        $feed['images'] = $images;
        
        $addfeed = uc_feed_add($feed['icon'], $uid, $username, $feed['title_template'], $feed['title_data'], $feed['body_template'], $feed['body_data'], '', '', $feed['images']);
        
        if($addfeed > 0) {
            return $addfeed;
        } else {
            self::$lastAction = 'addfeed';
            self::$lastErrorCode = $addfeed;
            return FALSE;
        }

    }
    
    static function checkEmail($email) {
        $checkemail = uc_user_checkemail();
        if($checkemail > 0) {
            return TRUE;
        }else{
            self::$lastAction = 'checkemail';
            self::$lastErrorCode = $checkemail;
            return FALSE;
        }
    }

    static function checkName($username) {
		if(UC_DBCHARSET == 'gbk' && is_utf8($username)) $username = auto_charset($username, 'utf-8', 'gbk');
        $checkname = uc_user_checkname();
        if($checkname > 0) {
            return TRUE;
        }else{
            self::$lastAction = 'checkname';
            self::$lastErrorCode = $checkname;
            return FALSE;
        }
    }
    
    static function isLogin () {
       return self::getUserByCookie();
    }
    
    static function getUserByCookie() {
        if(!empty($_COOKIE[self::$authPre . 'auth'])) {
            list(self::$uid, self::$username, self::$password, self::$email) = explode("\t", uc_authcode($_COOKIE[self::$authPre . 'auth'], 'DECODE'));
            return array(
                'uid' => self::$uid,
                'username' => self::$username,
                'password' => self::$password,
                'email' => self::$email,
            );
        } else {
            return FALSE;
        }

    }
    
    static function getUid() {
        if(empty(self::$uid)) {
            self::getUserByCookie();
        }  
            return self::$uid;
    }
    
    static function getUserName() {
        if(empty(self::$username)) {
            self::getUserByCookie();
        }  
            return self::$username;
    }
    
    static function getPassWord() {
        if(empty(self::$password)) {
            self::getUserByCookie();
        }  
            return self::$password;
    }
    
    static function getEmail() {
        if(empty(self::$email)) {
            self::getUserByCookie();
        }  
            return self::$email;
    }
    
    static function getError() {
        return self::$lastErrorCode = '' ? '' :  'UC错误代码: ' . self::$lastErrorCode . ' <br> ' . self::$errorCode[self::$lastAction][self::$lastErrorCode];
        //return self::$lastErrorCode = '' ? '' :  '错误: ' . self::$errorCode[self::$lastAction][self::$lastErrorCode];
    }
    
    static function getErrorCode() {
        return self::$lastErrorCode;
    }
}