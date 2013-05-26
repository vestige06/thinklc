<?php
// 用户注册，登录，激活模块
class PublicAction extends BaseAction {
    protected function _initialize() {
		parent::_initialize();
		//根据登录接口设置赋值
		$this->assign("extend_oauth", '1');
		$this->assign("jumpUrl",U('Public/login'));
	}
    public function _empty($name) {
		$this->error('非法操作，请与管理员联系');
	}
	// 注册限制检测
    protected function _check_reglimit() {
		if(!$this->MCFG['reg_enable']) $this->error('本站已禁止新用户注册');
		// IP限制
		$ip = get_client_ip();
		if($this->MCFG['reg_ip']!=0) {
			$User = D("Member");
			$regtime = $User->where("regip='".$ip."'")->order('regtime desc')->getField('regtime');
			if(!empty($regtime)) {
				if($this->MCFG['reg_ip']<0) $this->error('同一IP只能注册一个帐号');
				$tmp = $this->MCFG['reg_ip'] * 3600 + $regtime;
				if($tmp>time()) $this->error('同一IP必须间隔'.$this->MCFG['reg_ip'].'小时才能再次注册');
			}
		}
		// 地区限制
		$site_area = C('site_area');
		if($this->MCFG['reg_area'] && !empty($site_area)) {
			$address = getAddress();
			if(false===strpos($address,C('site_area'))) {
				$this->error('本站只允许 '.C('site_area').' 地区会员注册');
			}
		}
	}
	// 注册
	public function reg() {
		if(session('?uid')) {
			$this->assign("jumpUrl",U('Index/index'));
			$this->error('已经登陆，请先退出登录再注册用户');
		}else{ 
			$this->_check_reglimit();
			$this->assign("uc_enable", $this->MCFG['uc_enable']);
			$this->assign("extend_title", '会员注册');
			$this->display();
		}
    }
	// 保存用户注册信息
	public function regsave() {
        if($_SESSION['verify'] != md5($_POST['verify'])) {
			$this->error('验证码错误！');
		}
		if($this->MCFG['uc_enable']) {
			// 如果开启UC整合，进行UC注册
			$reg = UcApi::reg($_POST['uname'], $_POST['pwd'], $_POST['email']);
			// UC注册失败，抛出错误信息
			if ($reg === FALSE) $this->error(UcApi::getError());
			elseif($reg>0 && $this->MCFG['uc_bbs'] && !empty($this->MCFG['uc_bbs_pre'])) uc_user_regbbs($reg, $_POST['uname'], $_POST['pwd'], $_POST['email']);
		}
		$User = D("Member");
        if($User->create()){
			$result = $User->add();
			if(false !== $result) {
				credit_record($_POST['uname'], $this->MCFG['credit_reg'], 'system', '注册奖励', get_client_ip());
				$this->assign("jumpUrl",U('Public/login'));
				$this->success('注册成功，请登录会员中心');
            }else{
                $this->error('注册失败，请与管理员联系');
            }
       } else {    
		    $this->error($User->getError());
	   }
	}
	// 用户登录页面
	public function login() {
		if(session('?uid')) {
			$this->assign("jumpUrl",U('Index/index'));
			$this->success('您已经登陆');
		}else{
			$this->assign("randpic", mt_rand(1,2)); 
			$this->assign("extend_title", '会员登录');
			$this->display();
		}
	} 
	// 登录检测
	public function checklogin() {
		if(empty($_POST['uname'])) {
			$this->error('帐号不能为空！');
		} elseif(empty($_POST['upwd'])) {
			$this->error('密码不能为空');
		}
		// 兼容帐号绑定验证时无验证码
		if(isset($_POST['verify'])) {
			if(empty($_POST['verify'])){
				$this->error('验证码不能为空！');
			}
			if($_SESSION['verify'] != md5($_POST['verify'])) {
				$this->error('验证码错误！');
			}
		}
		$uname = trim($_POST['uname']);
		$upwd = trim($_POST['upwd']);
		// 先从ThinkLC拉取用户信息
		$user = D('Member')->getByUname($uname);
		// 默认情况下，登录ThinkLC
		$uclogin = false;
		if($this->MCFG['uc_enable']) {
			// 开启了UC整合
			if(!$this->MCFG['uc_verify'] || !$user || $user['ucverify']) {
				// 不需要用户进行UC验证、用户不存在，或者用户已通过UC验证时，直接登录UC
				$uclogin = true;
			}
		}
		if($uclogin) {
			// 进行UC登录
			$this->_login_uc($uname, $upwd, 0, $user);
		} else {
			// 普通登录
			$this->_login($uname, $upwd, $user);
		}
	}
	// 普通登录
	protected function _login($uname = '', $upwd = '', $user = false) {
		if(empty($uname) && empty($pwd)) $this->error('用户名和密码不能为空');
		if(!$user) {
			$model = D("Member");
			$user = $model->getByUname($uname);
		}
		if(!$user) {
			$this->assign("jumpUrl",U('Public/reg'));
			$this->error('用户不存在或被删除，请重新注册');
		} else {
			$this->_check_group($user['gid']);
			if($user['upwd'] == md5($upwd)) {
				$this->_login_success($user);
				$this->assign("jumpUrl",U('Index/index'));
				$this->success('登陆成功');
			} else {
				$this->error('密码错误');
			}
		}
	}
	// UC登录
	protected function _login_uc($uname = '', $upwd = '', $isuid = 0, $user = false) {
		if(empty($uname) && empty($pwd)) $this->error('用户名和密码不能为空');
		if(!$user) {
			// 从ThinkLC拉取用户信息
			$model = D("Member");
			$user = $model->getByUname($uname);
		}
		// ThinkLC用户存在，提前检查用户组
		if($user) $this->_check_group($user['gid']);
		$login = UcApi::login($uname, $upwd, $isuid);
		if ($login === FALSE) {
			// 登录失败，获取错误代码
			$errcode = UcApi::getErrorCode();
			if($errcode == -1) {
				// UC用户不存在
				if($user) {
					// 尝试注册新用户并且登录
					$reg = UcApi::reg($uname, $upwd, $user['email']);
					if ($reg === FALSE) {
						$this->error('Ucenter用户不存在或被删除<br>尝试注册Ucenter用户出错<br>'.UcApi::getError());
					} else {
						// 再次尝试登录UC
						$login = UcApi::login($uname, $upwd);
						if ($login === FALSE) {
							$this->error(UcApi::getError());
						}
					}
				} else {
					$this->error(UcApi::getError());
				}
			} elseif($errcode == -2) {
				// UC密码错误
				if($user) {
					// 更新UC密码并再次登录
					if($user['upwd'] == md5($upwd)) {
						// 如果ThinkLC和其他应用开始独立（包括2个应用开始都未整合UC或者开始只整合其中一个应用）、后来相互整合的话
						// 可能会出现2个应用有相同用户名但属于不同用户的情况，此时更新密码就出现安全隐患
						$edit = UcApi::edit($uname, '', $upwd, '', 1);
						if ($edit === FALSE) {
							$this->error('Ucenter密码错误<br>尝试更新Ucenter密码出错<br>'.UcApi::getError());
						} else {
							// 再次尝试登录UC
							$login = UcApi::login($uname, $upwd);
							if ($login === FALSE) {
								$this->error(UcApi::getError());
							}
						}
					} else {
						$this->error('ThinkLC密码错误，请重试');
					}
				} else {
					$this->error(UcApi::getError());
				}
			} else {
				// UC安全提问出错
				$this->error(UcApi::getError());
			}
		}
		if($login) {
			// UC登录成功
			if(!$user) {
				// ThinkLC用户不存在，自动激活，或者使用引导页面手动激活
				$auth = rawurlencode(encrypt($uname.'|'.$login['email']));
				$this->assign("jumpUrl",U('Public/active?auth='.$auth));
				$this->error($login['synlogin'].'此帐号需要激活');
			} else {
				// 同步ThinkLC密码，和上面更新UC密码一样，可能出现安全隐患
				$vpwd = md5($upwd);
				if($vpwd != $user['upwd']) D("Member")->where('id='.$user['id'])->setField('upwd',$vpwd);
				// 输出同步登录代码
				//exit($login['synlogin']);
				$this->_login_success($user, $login['synlogin']);
				$this->assign("jumpUrl",U('Index/index'));
				if(session('?uc_verify')) {
					session('uc_verify', null);
					$verify = M('Member')->where('id='.$user['id'])->setField('ucverify',1);
					if(false !== $verify) {
						session('ucverify', 1);
						$this->success($login['synlogin'].'UC安全验证成功');
					}
					else $this->error('UC安全验失败');
				} else {
					$this->success($login['synlogin'].'UC登录成功');
				}
			}
		}
	}
	// UC安全验证
	public function ucverify() {
		if(!$this->MCFG['uc_enable'] || !$this->MCFG['uc_verify'] || session('ucverify')) $this->error('您的账户不需要安全验证');
		if(!session('?uname')) $this->error('请登录后再进行安全验证');
		if(isset($_POST['dosubmit'])) {
			$uname = session('uname');
			$upwd = $_POST['upwd'];
			$upwd2 = $_POST['upwd2'];
			if($upwd != $upwd2) $this->error('两次密码输入不一致');
			session('uc_verify',1);
			$this->_login_uc($uname, $upwd);
		} else {
			$this->display();
		}
	}
	// 帐号激活
	public function active() {
		$this->assign("jumpUrl",U('Public/reg'));
		$auth = isset($_GET['auth']) ? $_GET['auth'] : '';
		if(empty($auth)) {
			$this->error('参数错误，请重新注册帐号');
		} else {
			$user = rawurldecode($auth);
			if($user) {
				$user = decrypt($user);
				$user = explode('|', $user);
			}
			if(!$user[0] || !uc_get_user($user[0])) $this->error('用户不存在，请重新注册帐号');
		}
		//兼容GBK编码UC
		if(!is_utf8($user[0])) $user[0] = auto_charset($user[0]);
		if(isset($_POST['dosubmit'])) {
			$this->_check_reglimit();
			$model = D('Member');
			$data = array();
			$data['uname'] = $user[0];
			$data['email'] = $user[1];
			$data['credit'] = $this->MCFG['credit_reg'];
			$data['gid'] = $this->MCFG['reg_status'] ? 4 : 3;
			$data['regtime'] = time();
			$data['regip'] = get_client_ip();
			// 自动通过UC验证
			$data['ucverify'] = 1;
			$check = $model->getByEmail($data['email']);
			if($check) $this->error('当前UC用户email地址与ThinkLC用户冲突');
			$result = $model->add($data);
			if(false === $result) {
				$this->error('激活ThinkLC用户失败');
			} else {
				credit_record($data['uname'], $this->MCFG['credit_reg'], 'system', '注册奖励', $data['regip']);
				$user = $model->getById($result);
				$this->_check_group($user['gid']);
				$this->_login_success($user);
				$this->assign("jumpUrl",U('Index/index'));
				$this->success('帐号激活成功');
			}
		} else {
			$this->assign("uname",$user[0]);
			$this->assign("extend_title", '帐号激活');
			$this->display();
		}
	}
	public function logout() {
        if(session('?uid')) {
			// 开启UC接口，输出同步登出的代码
			$uc_logout = '';
			if($this->MCFG['uc_enable'] && (!$this->MCFG['uc_verify'] || session('ucverify'))) $uc_logout = UcApi::logout();
			session('uid',null);
			session('ugid',null);
			session('uname',null);
			session('ucverify',null);
            $this->assign("jumpUrl",U('Public/login'));
            $this->success($uc_logout.'登出成功！');
        } else {
			$this->error('已经登出！');
        }
    }
	// 显示用户状态
    public function userline() {
		if(session('?uid')) {
			$this->assign("uid", session('uid')); 
			$this->assign("uname", session('uname'));
		} else {
			$this->assign("uid", '0'); 
		}
		$this->display();
	}
	// 验证码显示
    public function verify() {
        import("ORG.Util.Image");
        Image::buildImageVerify(4);
    }
}