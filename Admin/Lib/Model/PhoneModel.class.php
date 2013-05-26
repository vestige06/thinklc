<?php 
class PhoneModel extends CommonModel {
	protected $_validate = array(    
		array('title','require','商家名称不能为空！'),
		array('catid','require','请选择商家分类！'),
		array('areaid','require','请选择所在地区！'),
		array('tel','checkphone','电话和手机至少填写一个！',2,'callback'),
		array('mobile','checkphone','电话和手机至少填写一个！',2,'callback'),
		array('verify','require','验证码必须填写！'),
		array('verify','checkverify','验证码错误！',2,'callback'),
	); 
	public function checkphone() {
		if(empty($_POST['tel']) && empty($_POST['mobile'])) return false;
		return true;
	}
	public function checkverify() {
		$verify = md5($_POST['verify']);
		if($verify==$_SESSION['verify']) return true;
		else return false;
	}
}