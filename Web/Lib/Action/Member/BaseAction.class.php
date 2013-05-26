<?php
// 接口模块，UC同步登录和一键登录绑定操作
class BaseAction extends Action { 
	var $MCFG;
    protected function _initialize() {
		C(include_once DATA_PATH . 'config/module_0.php');
		$this->MCFG = F('config/module_1');
		//导入公用函数库
		Vendor('ThinkLC.Global'); 
		Vendor('ThinkLC.System');
		$this->assign("header",'./Web/Tpl/Home/'.C('DEFAULT_THEME').'/Public_header.html');
		$this->assign("footer",'./Web/Tpl/Home/'.C('DEFAULT_THEME').'/Public_footer.html');
		$this->assign("jumpUrl",$_SERVER['HTTP_REFERER']);
		
		if($this->MCFG['uc_enable']) {
			//载入UcApi扩展
			Vendor('ThinkLC.UcApi');
			// 如果开启了UC整合，并且ThinkLC为未登录状态，自动检测
			if(!session('?uid') && MODULE_NAME!='Oauth') $this->_autologin();
		}
	}
    public function _empty($name) {
		$this->error('非法操作，请与管理员联系');
	}
	//自动登录检测
    public function _autologin() {
		$cookiename = $this->MCFG['uc_cookie_pre'].'thinklc_auth';
        if (isset($_COOKIE[$cookiename]) && !empty($_COOKIE[$cookiename])) {
			//得到加了密的UC里的uid和uername
            $userinfo = explode("\t", _authcode($_COOKIE[$cookiename], 'DECODE'));
			//兼容GBK编码UC
			if(!is_utf8($userinfo[1])) $userinfo[1] = auto_charset($userinfo[1]);
			$user = D('Member')->getByUname($userinfo[1]);
			setcookie($cookiename, '', -86400 * 365, '/', $this->MCFG['uc_cookie_domain'], $_SERVER['SERVER_PORT'] == 443 ? 1 : 0);
			if(!$user) {
				// ThinkLC用户不存在
				$user = uc_get_user($userinfo[1]);
				if($user[0]) {
					$auth = rawurlencode(encrypt($user[1].'|'.$user[2]));
					$this->assign("jumpUrl",U('Public/active?auth='.$auth));
					$this->error('此帐号需要激活');
				} else {
					$this->assign("jumpUrl",U('Public/reg?uname='.$userinfo[1]));
					$this->error('该用户在ThinkLC和UC中都不存在，请重新注册帐号');
				}
			} elseif(!$this->MCFG['uc_verify'] || $user['ucverify']) {
				// 不需要UC验证或者已验证通过
				$this->_check_group($user['gid']);
				$this->_login_success($user);
			}
        }
    }
	protected function _check_group($gid) {
		if($gid == 1) {
			$this->error('您的账户已被禁用');
		} elseif($gid == 2) {
			$this->error('游客身份禁止登录会员后台');
		} elseif($gid == 3) {
			$this->error('您的账户还没有通过审核，请与管理员联系');
		}
	}
	protected function _login_success($user, $synlogin = '') {
		// 成功登录后的处理
		$data = array();
		$data['id'] = $user['id'];
		if($user['viptotime']<time() && $user['gid']>4) $data['gid'] = 4;
		$data['logintime'] = time();
		$data['loginip'] = get_client_ip();
		if($user['logintime']<strtotime(date("Y-m-d"))) {
			$data['credit'] = $user['credit'] + $this->MCFG['credit_login'];
		}
		D("Member")->save($data);
		if($user['logintime']<strtotime(date("Y-m-d"))) credit_record($user['uname'], $this->MCFG['credit_login'], 'system', '登录奖励', $data['loginip']);
		session('uid',$user['id']);
		session('ugid',$user['gid']);
		session('uname',$user['uname']);
		session('ucverify',$user['ucverify']);
		$this->_post_limit();
		if(session('?oauth_openid') && session('?oauth_site')) {
			$oauth_openid = session('oauth_openid');
			$oauth_site = session('oauth_site');
			session('oauth_openid',null);
			session('oauth_site',null);
			if(empty($oauth_openid) || empty($oauth_site)) $this->error($synlogin.'绑定用户失败');
			// 进行一键登录绑定
			$bind = $this->_oauth_bind($user['uname'], $oauth_site, $oauth_openid);
			$this->assign("jumpUrl",U('Index/index'));
			if($bind) {
				$this->success($synlogin.'绑定用户成功');
				exit;
			} else {
				$this->error($synlogin.'绑定用户失败');
			}
		}
	}
	protected function _post_limit() {
		// 每日发布信息限制
		$module = getModule();
		$uid = session('uid');
		foreach($module as $v) {
			if($v['id']>4 && !$v['islink']) {
				$post = getLimit($uid, $v['id'], 'post');
				if(is_null($post) || false === $post || ($v['id']!=6 && date("Y-m-d",$post['lasttime']) != date("Y-m-d"))) {
					setLimit($uid, $v['id'], 'post', '0');
				}
			}
		}
	}
    protected function _oauth_bind($uname, $site, $openid) {
		// 绑定一键登录
		if(empty($uname) || empty($site) || empty($openid)) return false;
		if(!function_exists('get_oauth_info')) {
			Vendor('ThinkLC.Oauth.'.ucfirst($site));
		}
		$oauth_info = get_oauth_info();
		del_token();
		$model = M('Oauth');
		$data = array();
		$data['uname'] = $uname;
		$data['site'] = $site;
		$data['openid'] = $openid;
		$data['addtime'] = time();
		$data['logintime'] = time();
		$data['logintimes'] = 1;
		$data['nickname'] = $oauth_info['nickname'];
		$data['avatar'] = $oauth_info['avatar'];
		$data['url'] = $oauth_info['url'];
		if(false !== $model->add($data)) return true;
		else return false;
	}
}