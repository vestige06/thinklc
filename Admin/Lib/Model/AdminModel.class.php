<?php
class AdminModel extends CommonModel {
	protected $_validate	=	array(
		array('aname','/^[a-z]\w{1,9}$/i','管理员用户名格式错误'),
		array('rid','require','所属角色不能为空！'),
		array('apwd','pwdCheck','密码不能小于6个字符或大于20个字符',0,'callback',1),
		array('apwd2','apwd','确认密码不一致',2,'confirm'),
		array('aname','','管理员已经存在',0,'unique',self::MODEL_BOTH),
	);
	protected $_auto		=	array(
		array('apwd','pwdHash',3,'callback'),
	);
	protected function pwdCheck() {
		if(empty($_POST['apwd']) || strlen($_POST['apwd'])>20 || strlen($_POST['apwd'])<6) return false;
		else return true;
	}
	protected function pwdHash() {
		if(!empty($_POST['apwd'])) return md5($_POST['apwd']);
		else return false;
	}
}