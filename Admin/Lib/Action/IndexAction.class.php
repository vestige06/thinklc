<?php
class IndexAction extends CommonAction
{
    public function index(){
		if(!file_exists(DATA_PATH.'config/menu.php')) cache_menu();
		$topmenuarr = F('config/menu_1');
		$topmenu = array();
		foreach($topmenuarr as $v) {
			if($this->adminfounder || ($v['status'] && in_array($v['id'],$this->adminrole['access']))) $topmenu[] = $v;
		}
		$this->assign("topmenu",$topmenu);
		$this->assign("adminname",$this->adminname);
		$this->assign("adminrname",$this->adminrname);
		if(session('?lock_screen')) $this->assign( "lock_screen", session('lock_screen') );
		$panel = $this->ajax_show_panel(0);
		$this->assign("panel",$panel);
        $this->display();
    }
    public function main(){
		$panel = $this->ajax_show_panel(0);
		$this->assign("panel",$panel);
		$ainfo = M('Admin')->find($this->adminid);
		$this->assign("ainfo",$ainfo);
		require("./version.inc.php");
		$this->assign('s_version', S_VERSION);
		$this->assign('s_release', S_RELEASE);
		$sysinfo = getSysinfo();
		$sysinfo['mysqlv'] = mysql_get_server_info();
		$this->assign("sysinfo",$sysinfo);
		$this->display();
    }
    public function leftmenu(){
		$pid = intval($_GET['pid']);
		$menu_2 = $this->_submenu($pid,2);
		foreach($menu_2 as $k => $v) {
			// 调用常用操作
			if($v['id']==7) $menu_2[$k]['menu_3'] = F('config/panel_'.$this->adminid);
			else $menu_2[$k]['menu_3'] = $this->_submenu($v['id'],3);
		}
		$this->assign("menu_2",$menu_2);
		$this->display();
    }
    public function _submenu($pid,$m=2){
		$pid = intval($pid);
		$menu = F('config/menu_'.$m);
		$menuarr = array();
		$module = getModule();
		foreach($menu as $v) {
			if($pid==$v['pid'] && ($this->adminfounder || ($v['status'] && in_array($v['id'],$this->adminrole['access'])))) {
				$v['mtitle'] = getMtitle($v['mtitle']);
				$menuarr[] = $v;
			}
		}
		return $menuarr;
    }
    public function menu_pos(){
		$menuid = intval($_GET['menuid']);
		$menutype = $_GET['menutype'];
		if(1==$menutype) {
			$menu = F('config/menu_1');
			exit($menu[$menuid]['mtitle'].' >');
		} elseif(3==$menutype) {
			$menu_1 = F('config/menu_1');
			$menu_2 = F('config/menu_2');
			$menu_3 = F('config/menu_3');
			$menu_2_mid = $menu_3[$menuid]['pid'];
			$menu_1_mid = $menu_2[$menu_2_mid]['pid'];
			exit($menu_1[$menu_1_mid]['mtitle'].' > '.getMtitle($menu_2[$menu_2_mid]['mtitle']).' > '.getMtitle($menu_3[$menuid]['mtitle']).' >');
		} else exit;
    }
	public function ajax_add_panel() {
		$menuid = isset($_POST['menuid']) ? $_POST['menuid'] : exit;
		$menu_3 = F('config/menu_3');
		if(empty($menu_3[$menuid])) exit;
		$url = U($menu_3[$menuid]['mname'].'/'.$menu_3[$menuid]['aname']);
		$model = D('Panel');
		$data = array('mid'=>$menuid, 'aid'=>$this->adminid, 'mtitle'=>getMtitle($menu_3[$menuid]['mtitle']), 'url'=>$url, 'datetime'=>time());
		$result = $model->add($data);
		if(false !== $result) {
			cache_panel($this->adminid);
			$this->ajax_show_panel();
		} else exit;
	}
	public function ajax_del_panel() {
		$menuid = isset($_POST['menuid']) ? $_POST['menuid'] : exit('0');
		$model = D('Panel');
		$model->where('mid='.$menuid.' AND aid='.$this->adminid)->delete(); 
		cache_panel($this->adminid);
		$this->ajax_show_panel();
	}
	protected  function ajax_show_panel($show=1) {
		$panelarr = F('config/panel_'.$this->adminid);
		$panel = '';
		foreach($panelarr as $v) {
			$panel .= "<span><a onclick='paneladdclass(this);' target='right' href='".$v['url']."'>".$v['mtitle']."</a> <a class='panel-delete' href='javascript:delete_panel(".$v['mid'].");'></a></span>";
		}
		if($show) exit($panel);
		else return $panel;
	}
	public function session_life() {
		session('admin_name',$this->adminname);
		return true;
	}
	public function lock_screen() {
		session('lock_screen',1);
	}
	public function lock_screenlock() {
		if(empty($_GET['lock_password'])) $this->error('密码不能为空');
		$model = D("Admin"); 
		$r = $model->getById($this->adminid);
		$password = md5($_GET['lock_password']);
		if($r['apwd'] != $password) exit('2');
		session('lock_screen',0);
		exit('1');
	}
}