<?php
class MemberModel extends CommonModel {
	protected $_validate	=	array(
		array('uname','/^[\x80-\xff_a-zA-Z0-9]{4,20}$/i','用户名格式错误'),
		array('gid','require','所属会员组不能为空！'),
		array('upwd','pwdCheck','密码不能小于6个字符或大于20个字符',0,'callback',1),
		array('upwd2','upwd','确认密码不一致',0,'confirm'),
		array('email','require','邮箱不能为空！'),
		array('email','email','邮箱格式错误！'),
		array('uname','','用户名已经存在',0,'unique',self::MODEL_INSERT),
		array('email','','该邮箱已被注册',0,'unique',self::MODEL_BOTH),
	);
	protected $_auto		=	array(
		array('upwd','pwdHash',3,'callback'),
		array('regtime','time',1,'function'),
		array('regip','get_client_ip',1,'function'),
	);
	protected function pwdCheck() {
		if(empty($_POST['upwd']) || strlen($_POST['upwd'])>20 || strlen($_POST['upwd'])<6) return false;
		else return true;
	}
	protected function pwdHash() {
		if(!empty($_POST['upwd'])) return md5($_POST['upwd']);
		else return false;
	}
}