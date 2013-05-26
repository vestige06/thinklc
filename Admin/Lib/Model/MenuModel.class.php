<?php
class MenuModel extends CommonModel {
	protected $_validate	=	array(
		array('mtitle','require','菜单显示名不能为空！'),
		array('mname','require','模块名不能为空！'),
		array('aname','require','操作名不能为空！'),
		array('mname','checkMenu','菜单已经存在',0,'callback'),
		array('pid','checkPid','不能添加子菜单',0,'callback'),
	);
	public function checkMenu() {
		$map['mname'] = $_POST['mname'];
		$map['aname'] = $_POST['aname'];
		$map['pid'] = isset($_POST['pid'])?$_POST['pid']:0;
        if(!empty($_POST['id'])) {
			$map['id'] = array('neq',$_POST['id']);
        }
		$result	= $this->where($map)->field('id')->find();
        if($result) {
        	return false;
        }else{
			return true;
		}
	}
	public function checkPid() {
		$map['id']	=	isset($_POST['pid'])?$_POST['pid']:0;
		if(!$map['id']) return true;
		$level = isset($_POST['level'])?$_POST['level']:1;
		$result	=	$this->where($map)->field('level')->find();
		if($result['level']>3 || $level>4) {
        	return false;
        }else{
			return true;
		}
	}
}