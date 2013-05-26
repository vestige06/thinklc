<?php
// 会员首页模块
class IndexAction extends CommonAction {
	var $uinfo;
    public function _initialize() {
		parent::_initialize();
		$User = D("Member"); 
		$this->uinfo = $User->getById($this->uid);
		$this->assign("uinfo", $this->uinfo);
		$this->assign("jumpUrl",U('Index/index'));
		$this->assign("navmenu", 1); 
    }
    public function index(){
		$group = F('config/group');
		$this->assign("groupname", $group[$this->ugid]['gname']);
        $this->display();
    } 
    public function oauth() {
		$this->assign("jumpUrl",U('Index/oauth'));
		if(isset($_GET['action']) && $_GET['action']=='delete' && $_GET['id']) {
			$r = M('Oauth')->where('id='.$_GET['id'])->delete();
			if(false !== $r) $this->success("解除绑定成功");
			else $this->error('解除绑定失败！');
		} else {
			$r = M('Oauth')->where("uname='".$this->uname."'")->select();
			$oauth = F('config/oauth');
			foreach($r as $v) {
				if($oauth[$v['site']]['enable']) $list[$v['site']] = $v;
			}
			$this->assign("list", $list);
			$this->assign("oauth", $oauth);
			$this->display();
		}
    } 
    public function edit() {
		if($_POST['dosubmit']) {
			if(!empty($_POST['oldupwd']) || !empty($_POST['upwd'])) {
				if($this->uinfo['upwd'] != md5($_POST['oldupwd'])) $this->error('旧密码错误');
				if($_POST['upwd'] != $_POST['upwd2']) $this->error('两次密码不一致');
			}
			$User = D("Member"); 
			if(!empty($_POST['oldupwd']) && !empty($_POST['upwd'])) $User->upwd = md5($_POST['upwd']);
			$User->company = $_POST['company'];
			$User->address = $_POST['address'];
			$User->contacter = $_POST['contacter'];
			$User->tel = $_POST['tel'];
			$User->id = $this->uid;
			$result	= $User->save();
			if(false !== $result) {
				if($this->MCFG['uc_enable'] && !empty($_POST['upwd']) && (!$this->MCFG['uc_verify'] || session('ucverify'))) {
					$edit = UcApi::edit($this->uname, '', $_POST['upwd'], '', 1);
					if ($edit === FALSE) {
						$this->error('资料修改成功<br>更新Ucenter密码出错：'.UcApi::getError());
					}
				}
				$this->success("资料修改成功");
			} else {
				$this->error('资料修改失败！');
			} 
		} else {
			$this->display();
		}
    }
    public function vip() {
		if($_POST['dosubmit']) {
			$gid = intval($_POST['gid']);
			$money = $_POST['money'];
			if(!$money || !$gid) $this->error('会员组或资金错误');
			$paytype = $_POST['paytype'][$gid];
			if($paytype) {
				$credit_money = intval($this->MCFG['credit_money']);
				$credit = intval($money * $credit_money);
				if(!$credit) $this->error('升级会员组所需积分错误');
				if($this->uinfo['credit']<$credit) $this->error('您的账户积分不足');
			} elseif($this->uinfo['money']<$money) {
				$this->assign("jumpUrl",U('Charge/pay'));
				$this->error('您的账户资金不足，请先充值');
			}

			$User = D("Member"); 
			$data = array();
			$data['id'] = $this->uid;
			$data['gid'] = $gid;
			if($paytype) $data['credit'] = $this->uinfo['credit'] - $credit;
			else $data['money'] = $this->uinfo['money'] - $money;
			if($gid==$this->uinfo['gid'] && $this->uinfo['viptotime']>time()) $data['viptotime'] = strtotime("+1 year",$this->uinfo['viptotime']);
			else $data['viptotime'] = strtotime("+1 year");
			$reason = $gid==$this->uinfo['gid'] ? '续费会员组' : '升级会员组';
			$tmpstr1 = $gid==$this->uinfo['gid'] ? '续费会员组成功' : '升级会员组成功';
			$tmpstr2 = $gid==$this->uinfo['gid'] ? '续费会员组失败' : '升级会员组失败';
			$result	=$User->save($data);
			if(false !== $result) {
				if($paytype) credit_record($this->uinfo['uname'], -$credit, 'system', $reason, '自动');
				else money_record($this->uinfo['uname'], -$money, '站内', 'system', $reason, '自动');
				session('ugid',$gid);
				$this->success($tmpstr1);
			}else {
				$this->error($tmpstr2);
			}
		} else { 
			$group = F('config/group');
			foreach($group as $k => $v) {
				if($v['id']>3) {
					$grouplist[$v['id']] = $v;
					$groupsetting[$v['id']] = F('config/group_'.$v['id']);
				}
			}
			$modules = getModule();
			$CFGS = $module = array();
			foreach($modules as $v) {
				if($v['id']>4) {
					$CFGS[$v['id']] = F('config/module_'.$v['id']);
					$module[$v['id']] = $v;
				}
			}
			$this->assign("CFGS", $CFGS);
			$this->assign("grouplist", $grouplist);
			$this->assign("groupsetting", $groupsetting);
			$this->assign("groupname", $group[$this->ugid]['gname']);
			$this->assign("ugid", $this->ugid);
			$width = intval(80/count($groupsetting));
			$this->assign("width", $width);
			$this->assign("colspan", (count($groupsetting)+1));
			$this->display();
		}
    }
}