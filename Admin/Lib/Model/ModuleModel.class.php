<?php
class ModuleModel extends CommonModel {
	protected $_validate = array(
		array('mtitle','require','模块名称不能为空！'),
		array('islink','checklink','请输入模块文件名或链接URL',0,'callback'),
		array('mname','checkmname','模块文件已存在',2,'callback'),
	);
	protected $_auto = array ( 
		array('installtime','time',1,'function'),
	);
	public function checklink() {
		if($_POST['islink'] && empty($_POST['linkurl'])) return false;
		elseif(!$_POST['islink'] && empty($_POST['mname'])) return false;
		else return true;
	}
	public function checkmname() {
		$mfile = ROOT_PATH.'/Home/Lib/Action/'.ucfirst($_POST['mname']).'Action.class.php';
		if(file_exists($mfile)) return false;
		else return true;
	}
}