<?php
class MemberAction extends CommonAction {
	protected $moduleid = 1;
	protected function _initialize() {
		parent::_initialize();
		$grouplist = F('config/group');
		$this->assign('grouplist', $grouplist);
		$MCFG = F('config/module_'.$this->moduleid);
		$this->assign('MCFG', $MCFG);
	}
	public function _filter(&$map) {
		if(!isset($map['gid']) || $map['gid']==3) $map['gid']	= array('neq',3);
		if(!empty($_GET['fieldskey']) && !empty($_GET['fieldsvalue'])) {
			if(isset($_GET[$_GET['fieldskey'].'_like'])) $map[$_GET['fieldskey']] = array('like','%'.$_GET['fieldsvalue'].'%');
			else $map[$_GET['fieldskey']] = array('eq',$_GET['fieldsvalue']);
		}
	}
	public function _after_update(){
		$MCFG = F('config/module_'.$this->moduleid);
		$ucverify = M('Member')->where('id='.$_POST['id'])->getField('ucverify');
		if($MCFG['uc_enable'] && (!empty($_POST['uname']) || !empty($_POST['upwd']) || !empty($_POST['email'])) && (!$MCFG['uc_verify'] || $ucverify)) {
			Vendor('ThinkLC.UcApi');
			$edit = UcApi::edit($_POST['uname'], '', $_POST['upwd'], $_POST['email'], 1);
			if ($edit === FALSE) {
				$this->error('更新Ucenter资料出错：'.UcApi::getError());
			}
		}
	}
	public function _before_insert(){
		$MCFG = F('config/module_'.$this->moduleid);
		if($MCFG['uc_enable']) {
			Vendor('ThinkLC.UcApi');
			// 如果开启UC整合，进行UC注册
			$reg = UcApi::reg($_POST['uname'], $_POST['pwd'], $_POST['email']);
			// UC注册失败，抛出错误信息
			if ($reg === FALSE) $this->error(UcApi::getError());
			elseif($reg>0 && $MCFG['uc_bbs'] && !empty($MCFG['uc_bbs_pre'])) uc_user_regbbs($reg, $_POST['uname'], $_POST['pwd'], $_POST['email']);
		}
	}
	public function _after_insert(){
		$credit = intval($_POST['credit']);
		if($credit) {
			credit_record($_POST['uname'], $credit, $this->adminname, '注册奖励', '后台添加会员');
		}
	}
	public function oauth(){
		$this->assign("jumpUrl",U('Member/oauth'));
		$model = M('Oauth');
		if(isset($_GET['action']) && $_GET['action']=='delete' && $_GET['id']) {
			$r = M('Oauth')->where('id='.$_GET['id'])->delete();
			if(false !== $r) $this->success("解除绑定成功");
			else $this->error('解除绑定失败！');
		} else {
			if(!empty($_GET['fieldskey']) && !empty($_GET['fieldsvalue'])) $GLOBALS['_REQUEST'][$_GET['fieldskey']] = $_GET['fieldsvalue'];
			$map = $this->_search('Oauth');
			$this->_list($model, $map, 'logintime');
			$oauth = F('config/oauth');
			$this->assign("oauth", $oauth);
			$this->display();
		}
	}
	public function move(){
		$mgid = $_REQUEST['mgid'];
		$id = $_POST['id'];
		if(!$mgid || !$id) $this->error('参数错误');
		if($mgid>4) $this->error('如果需要添加VIP会员，请使用<a href="'.U('Member/vip').'">VIP管理</a>');
		if(is_array($id)) $condition = 'id IN ('.implode(',',$id).')';
		else $condition = 'id='.$id;
		$member = D('Member');
		$result = $member->where($condition)->setField('gid',$mgid);
		if(false !== $result) $this->success('设置会员组成功');
		else $this->error('设置会员组失败');
	}
	public function check(){
		if(!empty($_GET['fieldskey']) && !empty($_GET['fieldsvalue'])) $GLOBALS['_REQUEST'][$_GET['fieldskey']] = $_GET['fieldsvalue'];
		$map = $this->_search('Member');
		$map['gid']	= array('eq',3);
        $model = M('Member');
		$this->_list($model, $map, 'regtime');
		$this->display();
	}
	public function vip(){
		$model = M('Member');
		$action = $_GET['action'];
		switch($action) {
			case 'uname':
				if(isset($_POST['id']) && !empty($_POST['id'])) {
					$ids = is_array($_POST['id']) ? implode(',', $_POST['id']) : $_POST['id'];
					$result = $model->where('id IN('.$ids.')')->getField('id,uname');
					echo implode(',', $result);
				}
			break;
			case 'add':
				if(isset($_POST['dosubmit'])) {	
					if(!$_POST['uname']) $this->error('会员名不能为空');
					$uname = trim($_POST['uname']);
					$vip = $_POST['vip'];
					$money = dround($vip['money']);
					$credit = intval($vip['credit']);
					$reason = $_POST['reason'] ? $_POST['reason'] : '升级赠送';
					$unames = explode(",", $uname);
					$grouplist = F('config/group');
					foreach($unames as $uname) {
						$uname = trim($uname);
						if(!$uname) continue;
						$vip['uname'] = $uname;
						if($this->_vip($vip)) {
							if($money) money_record($uname, $money, '站内', $this->adminname, $reason, '升级'.$grouplist[$vip['gid']]['gname']);
							if($credit) credit_record($uname, $credit, $this->adminname, $reason, '升级'.$grouplist[$vip['gid']]['gname']);
						}
					}
					if($_POST['doclose']) closeDialog('doDialog');
					else $this->success('设置VIP成功');
				} else {
					$this->display('vip_add');
				}
			break;
			case 'edit':
				if(isset($_POST['dosubmit'])) {
					if($this->_vip($_POST['vip'])) {
						if($_POST['doclose']) closeDialog('doDialog');
						else $this->success('修改VIP成功');
					} else $this->error('修改VIP失败');
				} else {
					if(!$_GET['id']) $this->error('请选择会员');
					$vo = $model->getById($_GET['id']);
					$this->assign('vo', $vo);
					$this->display('vip_edit');
				}
			break;
			case 'delete':
				if(!$_POST['id']) $this->error('请选择会员');
				$ids = is_array($_POST['id']) ? implode(',', $_POST['id']) : intval($_POST['id']);
				$result = $model->where('id IN('.$ids.')')->setField('gid','4');
				if(false !== $result) $this->success('撤销VIP成功');
				else $this->error('撤销VIP失败');
			break;
			default:
				if(!empty($_GET['fieldskey']) && !empty($_GET['fieldsvalue'])) $GLOBALS['_REQUEST'][$_GET['fieldskey']] = $_GET['fieldsvalue'];
				$map = $this->_search('Member');
				if(!isset($map['gid']) || $map['gid']<5) $map['gid'] = array('gt',4);
				if(isset($_GET['start_time'])) $map['viptotime'] = array('gt',strtotime($_GET['start_time'].' 00:00:00'));
				if(isset($_GET['end_time'])) $map['viptotime'] = array('lt',strtotime($_GET['end_time'].' 23:59:59'));
				$this->_list($model, $map, 'viptotime');
				$this->display();
			break;
		}
	}
	protected function _vip($vip) {
		if(!is_array($vip)) return false;
		if(!$vip['uname']) return $this->error('会员名不能为空');
		if(!$vip['gid']) return $this->error('请选择会员组');
		if(!$vip['viptotime'] || !is_date($vip['viptotime'])) return $this->error('请选择服务结束日期');
		$model = M('Member');
		$r = $model->getByUname($vip['uname']);
		if(!$r) return $this->error('会员 '.$vip['uname'].' 不存在');
		if($r['gid'] < 4) return $this->error($vip['uname'].' 会员所在会员组不能添加VIP');
		$vip['id'] = $r['id'];
		$vip['viptotime'] = strtotime($vip['viptotime'].' 23:59:59');
		if(isset($vip['money'])) {
			$vip['money'] = dround($vip['money']) + $r['money'];
			$vip['amount'] = dround($vip['money']) + $r['amount'];
		}
		if(isset($vip['credit'])) $vip['credit'] = intval($vip['credit']) + $r['credit'];
		if(false !== $model->save($vip)) return true;
		else return false;
	}
}