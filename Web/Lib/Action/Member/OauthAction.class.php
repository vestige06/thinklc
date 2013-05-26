<?php
// 一键登录模块
class OauthAction extends BaseAction {
	var $oauth,$site;
    protected function _initialize() {
		parent::_initialize();
		$this->assign("jumpUrl",U('Public/login'));

		$this->oauth = F('config/oauth');
		$this->site = $_GET['site'];
		if(!isset($this->oauth[$this->site])) $this->error('登录接口不存在');
		elseif(!$this->oauth[$this->site]['enable']) $this->error('登录接口已关闭');

		Vendor('ThinkLC.Oauth.'.ucfirst($this->site));
	}
    public function _empty($name) {
		$this->error('非法操作，请与管理员联系');
	}
    public function connect() {
		// 连接到登录接口
		if(session('?uname')) {
			// 已登录且已绑定，禁止连接
			$model = M('Oauth');
			$result = $model->field('uname')->where("uname='".session('uname')."' AND site='".$this->site."'")->find();
			if($result) {
				$this->assign("jumpUrl",U('Index/oauth'));
				$this->error($this->oauth[$this->site]['name'].'已绑定<br>如果需要绑定其他帐号，请先解除绑定');
			}
		}
		connect_to_site();
	}
    public function callback() {
		// 授权成功后的处理
		$redirect_url = U('Public/login');
		$openid = get_oauthid();
		if(empty($openid)) $this->error($this->oauth[$this->site]['name'].'方式登录失败');

		$model = M('Oauth');
		$result = $model->field('id,uname')->where("openid='".$openid."' AND site='".$this->site."'")->find();

		if($result){
			// 已绑定，从ThinkLC拉取用户信息
			$user = M('Member')->getByUname($result['uname']);
			if($user) {
				// 登录成功
				$model->where('id='.$result['id'])->setInc('logintimes'); 
				$model->where('id='.$result['id'])->setField('logintime',time()); 
				$this->_check_group($user['gid']);
				$this->_login_success($user);
				$synlogin = '';
				if($this->MCFG['uc_enable'] && (!$this->MCFG['uc_verify'] || $user['ucverify'])) {
					// 如果开启UC，并且不需要进行UC验证或者验证通过，进行同步登录
					if($ucuser = uc_get_user($user['uname'])) {
						$synlogin = uc_user_synlogin($ucuser[0]);
					}
				}
				$this->assign("jumpUrl",U('Index/index'));
				$this->success($synlogin.$this->oauth[$this->site]['name'].'方式登录成功!');
			} else {
				// ThinkLC用户不存在，可能被删除
				$this->assign("jumpUrl", U('Oauth/bind?site='.$this->site));
				session('oauth_openid',$openid);
				session('oauth_site',$this->site);
				$this->error('此帐号绑定用户不存在，请重新绑定一个新用户');
			}
		} else {
			// 未绑定
			if(session('?uname')) {
				// 如果已登录，自动绑定
				$this->assign("jumpUrl",U('Index/index'));
				$bind = $this->_oauth_bind(session('uname'), $this->site, $openid);
				if($bind) {
					$this->success('绑定当前登录用户成功');
				} else {
					$this->error('绑定当前登录用户失败');
				}
			} else {
				$this->assign("jumpUrl", U('Oauth/bind?site='.$this->site));
				session('oauth_openid',$openid);
				session('oauth_site',$this->site);
				$this->success($this->oauth[$this->site]['name'].'方式登录成功，请进行帐号绑定设置!');
			}
		}
	}
    public function bind() {
		// 绑定一键登录
		$oauth_info = get_oauth_info();
		if(empty($oauth_info['openid'])) $this->error('授权信息已失效，请重新登录');
		$this->assign("oauth_site", $this->oauth[$this->site]);
		$this->assign("site", $this->site);
		$this->assign("oauth_info", $oauth_info);
		$this->display();
	}
}