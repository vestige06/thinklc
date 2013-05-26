<?php
class SettingAction extends CommonAction {
	protected $item;
	protected function _initialize() {
		parent::_initialize();
		$this->item = isset($_REQUEST['item']) ? $_REQUEST['item'] : '0';
		$this->assign("item",$this->item);
	}
    public function config(){
		$this->assign("jumpUrl","__SELF__");
		if(isset($_POST['dosubmit'])) {
			$config = require("./config.inc.php");
			$tmp = file_get_contents('./config.inc.php');
			foreach($_POST['config'] as $k=>$v)	{
				if($k == 'HTML_CACHE_ON') $tmp = preg_replace("/'HTML_CACHE_ON'\s*\=\>\s*(?:true|false)\,/is", "'HTML_CACHE_ON' => ".$v.",", $tmp);
				elseif($k == 'HTML_CACHE_TIME') $tmp = preg_replace("/'HTML_CACHE_TIME'\s*\=\>\s*\d*\,/is", "'HTML_CACHE_TIME' => ".$v.",", $tmp);
				else $tmp = preg_replace("/'".$k."'\s*\=\>\s*'.*?'\,/is", "'".$k."' => '".$v."',", $tmp);
			}
			if(file_put_contents('./config.inc.php',$tmp))
			{
				@unlink(RUNTIME_PATH.'~runtime.php');
				@unlink(ROOT_PATH.'/Data/Runtime/Web/~runtime.php');
				if($_POST['config']['DEFAULT_MODULE']!=$config['DEFAULT_MODULE']) {
					//修改默认模块，重新生成首页
				}
				$this->success('系统配置修改成功');
			}
			else $this->error('系统配置修改失败');
		} else {
			$config = require("./config.inc.php");
			$this->assign('config', $config);
			$this->assign('module', getModule());
			$this->display();
		}
    }
    public function index() {
		$this->assign("jumpUrl","__SELF__");
		if(isset($_POST['dosubmit'])) {
			// 更新数据
			if ($this->_update($_POST['setting'])) {
				//成功提示
				cache_setting($this->item);
				if ($_POST['doclose']) closeDialog('doDialog');
				elseif($this->item) $this->success('模块设置更新成功');
				else $this->success('网站设置更新成功');
			} else {
				//错误提示
				if($this->item) $this->error('模块设置更新失败');
				else $this->error('网站设置更新失败');
			}
		} else {
			if($this->item=='0') {
				if (function_exists('ftp_ssl_connect')) $ftp_ssl = 1;
				else $ftp_ssl = 0;
				if(file_exists(LIBRARY_PATH.'ORG/Net/Ftp.class.php')) $ftp_disable = 0;
				else $ftp_disable = 1;
				$this->assign("ftp_ssl",$ftp_ssl);
				$this->assign("ftp_disable",$ftp_disable);
			}
			if(false !== strpos($this->item,'group_')) {
				$module = getModule();
				$modules = array();
				$g_setting_field = array('count','pass','credit','link','title','pic','detail');
				foreach($module as $v) {
					if($v['id']>4 && !$v['islink']) {
						foreach($g_setting_field as $vo) {
							$v[$vo] = strtolower($v['mname']).'_'.$vo;
						}
						$modules[$v['id']] = $v;
					}
				}
				$this->assign("modules",$modules);
			}
			$model = M("Setting");
			$setting = $model->where("item='".$this->item."'")->getField('item_key,item_value');
			$this->assign("setting",$setting);
			if(strpos($this->item,'_')) {
				$tmp = explode('_',$this->item);
				$this->display("setting_".$tmp[0]);
			} else $this->display("setting_".$this->item);
		}
    }
    public function member() {
		$this->assign("jumpUrl","__SELF__");
		if(isset($_POST['dosubmit'])) {
			foreach($_POST['pay'] as $k=>$v) {
				$this->_update($v, 'pay_'.$k);
				cache_pay();
			}
			$_POST['setting']['oauth'] = 0;
			foreach($_POST['oauth'] as $k=>$v) {
				if($v['enable']) $_POST['setting']['oauth'] = 1;
				$this->_update($v, 'oauth_'.$k);
				cache_oauth();
			}
			if ($this->_update($_POST['setting'])) {
				cache_setting($this->item);
				$this->success('会员模块设置更新成功');
			} else {
				$this->error('会员模块设置更新失败');
			}
		} else {
			$model = M("Setting");
			$setting = $model->where("item='".$this->item."'")->getField('item_key,item_value');
			$this->assign("setting",$setting);

			cache_pay();
			$paylist = F('config/pay');
			$pay = array();
			foreach($paylist as $k => $v) {
				$pfile = ROOT_PATH.'/Web/Lib/Pay/'.ucfirst($k).'.class.php';
				if(file_exists($pfile)) $v['disabled'] = 0;
				else $v['disabled'] = 1;
				$pay[$k] = $v;
			}
			$this->assign("pay",$pay);

			cache_oauth();
			$oauthlist = F('config/oauth');
			$oauth = array();
			foreach($oauthlist as $k => $v) {
				$ofile = VENDOR_PATH.'/ThinkLC/Oauth/'.ucfirst($k).'.php';
				if(file_exists($ofile)) $v['disabled'] = 0;
				else $v['disabled'] = 1;
				$oauth[$k] = $v;
			}
			$this->assign("oauth",$oauth);

			$this->display("setting_member");
		}
    }
	protected function _update($settingarr, $item = '') {
		if($item == '') $item = $this->item;
		$setting = M('Setting');
		$setting->where("item='".$item."'")->delete(); 
		$data = array();
		foreach($settingarr as $k => $v) {
			if(is_array($v)) $v = implode(',', $v);
			$data['item'] = $item;
			$data['item_key'] = $k;
			$data['item_value'] = $v;
			$result = $setting->add($data);
			if(false === $result) return false;
		}
		return true;
	}
    public function ftp_test(){
		$host = isset($_GET['host']) && trim($_GET['host']) ? trim($_GET['host']) : exit('请输入FTP服务器地址');
		$port = isset($_GET['port']) && intval($_GET['port']) ? intval($_GET['port']) : exit('请输入FTP服务器端口');
		$username = isset($_GET['username']) && trim($_GET['username']) ? trim($_GET['username']) : exit('请输入FTP用户名');
		$password = isset($_GET['password']) && trim($_GET['password']) ? trim($_GET['password']) : exit('请输入FTP密码');
		$pasv = isset($_GET['pasv']) && trim($_GET['pasv']) ? trim($_GET['pasv']) : 0;
		$ssl = isset($_GET['ssl']) && trim($_GET['ssl']) ? trim($_GET['ssl']) : 0;
		$path = isset($_GET['path']) && trim($_GET['path']) ? trim($_GET['path']) : '/';
		import("ORG.Net.Ftp");
		$ftp = new Ftp;
		if ($ftp->connect($host, $username, $password, $port, $pasv, $ssl, 15)) {
			if ($ftp->link_time > 15) {
				exit('FTP连接时间过长，请优化FTP服务器。');
			}
			if(!$ftp->chdir($path)) exit('FTP无法进入远程存储目录，请检查远程存储目录');
			exit('1');
		} else {
			exit('无法连接FTP服务器');
		}
    }
}