<?php
class PublicAction extends Action {       
	// 用户登录页面
	public function login() {
		if(session('?admin_name')) {
			$this->assign("jumpUrl",U('Index/index'));
			$this->success('已经登陆');
		}else{ 
			$this->display();
		}
	} 
	// 登录检测
	public function checklogin() {
		$this->assign("jumpUrl",U('Public/login'));
		if(empty($_POST['aname'])) {
			$this->error('帐号不能为空！');
		}elseif (empty($_POST['apwd'])){
			$this->error('密码不能为空！');
		}elseif (empty($_POST['verify'])){
			$this->error('验证码不能为空！');
		}
        if($_SESSION['verify'] != md5($_POST['verify'])) {
			$this->error('验证码错误！');
		}
		$aname = $_POST['aname'];
		$apwd = $_POST['apwd'];
		$model = M("admin");
		$admin = $model->getByAname($aname);
		if($admin) {
			if(md5($apwd)==$admin['apwd']) {
				if($admin['status']) {
					session('admin_name',$admin['aname']);
					session('admin_founder',$admin['founder']);
					session('admin_rid',$admin['rid']);
					session('admin_id',$admin['id']);
					$loginip = get_client_ip();
					$data = array('loginip'=>$loginip,'logintime'=>time());
					$model->where('id='.$admin['id'])->setField($data);
					$this->assign("jumpUrl",U('Index/index'));
					$this->success('登陆成功'); 
				} else $this->error('管理员被禁用！');
			} else $this->error('密码错误！');
		} else $this->error('管理员不存在！');
	}
	public function logout() {
        if(session('?admin_id')) {	
			session('admin_id',null);
			session('admin_name',null);
			session('admin_rid',null);
			session('admin_founder',null);
			session('logined',null);
			session('lock_screen',null);
			//session_destroy();
            $this->assign("jumpUrl",U('Public/login'));
            $this->success('登出成功！');
        }else {
			$this->error('已经登出！');
        }
    }
	public function initsys() {
		$lockfile = DATA_PATH.'initsys.lock';
		$lockinstall = DATA_PATH.'install.lock';
		if(file_exists($lockfile)) return false;
        $Model = M('Module');
		$order = 'listorder asc';
        $list = $Model->order($order)->select();
        $data = array();
        foreach ($list as $key => $val) {
            $data[$val['id']] = $val;
        }
		if($data) F('config/module',$data);
		cache_menu(); //后台菜单缓存
		cache_setting(); //网站配置缓存
		@touch($lockfile);
		@touch($lockinstall);
		return true;
    }
	// 验证码显示
    public function verify() {
        import("ORG.Util.Image");
        Image::buildImageVerify(4);
    }
}