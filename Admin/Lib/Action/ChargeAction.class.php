<?php
class ChargeAction extends CommonAction {
	protected function _initialize() {
		parent::_initialize();
		$MCFG = F('config/module_1');
		$paytype = explode('|', $MCFG['paytype']);
		$this->assign('paytype', $paytype);
	}
	public function index() {
		if(!empty($_GET['fieldskey']) && !empty($_GET['fieldsvalue'])) $GLOBALS['_REQUEST'][$_GET['fieldskey']] = $_GET['fieldsvalue'];
		$map = $this->_search('Charge');
		$_query = '1';
		if(!empty($_GET['fromtime'])) $_query .= ' AND '.$_GET['timetype'].'>'.(strtotime($_GET['fromtime'].' 00:00:00'));
		if(!empty($_GET['totime'])) $_query .= ' AND '.$_GET['timetype'].'<'.(strtotime($_GET['totime'].' 23:59:59'));
		if($_GET['minamount'] != '') $_query .= ' AND '.$_GET['mtype'].'>='.$_GET['minamount'];
		if($_GET['maxamount'] != '') $_query .= ' AND '.$_GET['mtype'].'<='.$_GET['maxamount'];
		$map['_string'] = $_query;
		$list = $this->_list(D('Charge'), $map);

		$_status = array('<span style="color:green;">充值完成</span>', '<span style="color:red;">充值失败</span>', '<span style="color:#E5EDF2;">充值超时</span>', '<span style="color:#E5EDF2;">充值处理中</span>', '<span style="color:blue;">未支付</span>', '<span style="color:#FF00FF;">充值取消</span>', '<span style="color:red;">充值错误</span>', '<span style="color:green;">人工审核</span>');
		$amount = $fee = $money = 0;
		$charges = array();
		foreach($list as $r) {
			$r['sendtime'] = date("Y-m-d H:i",$r['sendtime']);
			$r['receivetime'] = $r['receivetime'] ? date("Y-m-d H:i",$r['receivetime']) : '--';
			$r['editor'] or $r['editor'] = 'system';
			$r['dstatus'] = $_status[$r['status']];
			$amount += $r['amount'];
			$fee += $r['fee'];
			$money += $r['money'];
			$charges[] = $r;
		}
        $this->assign('list', $charges);
		$this->assign( "amount", $amount );
		$this->assign( "fee", $fee );
		$this->assign( "money", $money );
		$this->display();
	}
	public function check() {
		$_POST['id'] or $this->error('请选择充值记录');
		$id = implode(',', $_POST['id']);
		$charge = M('charge');
		$result	= $charge->where('id IN ('.$id.') AND status<2')->select();
		$i = $j = 0;
		$MCFG = F('config/module_1');
		foreach($result as $r) {
			$data = array();
			$data['id'] = $r['id'];
			$data['status'] = 7;
			$data['amount'] = $r['money'] + $r['fee'];
			$data['editor'] = $this->adminname;
			$data['receivetime'] = time();
			$upresult = $charge->save($data);
			if(false !== $upresult) {
				money_add($r['uname'], $r['money']);
				money_record($r['uname'], $r['money'], $r['bank'], $this->adminname, '账户充值', '审核订单:'.$r['ordernum']);
				$credit_pay = intval($r['money'] * $MCFG['credit_pay']);
				if($credit_pay) {
					credit_add($r['uname'], $credit_pay);
					credit_record($r['uname'], $credit_pay, $this->adminname, '充值奖励', '审核订单:'.$r['ordernum']);
				}
				$i++;
			} else $j++;
		}
		$this->success('审核成功 '.$i.' 条充值记录，失败 '.$j.' 条充值记录');
	}
	public function recycle() {
		$_POST['id'] or $this->error('请选择充值记录');
		$id = implode(',', $_POST['id']);
		$charge = M('charge');
		$data = array();
		$data['status'] = 5;
		$data['editor'] = $this->adminname;
		$data['receivetime'] = time();
		$condition = 'id in ('.$id.')';
		if(!isset($_POST['enforce'])) $condition .= ' AND status=4';
		$result	= $charge->where($condition)->save($data);
		if(false !== $result) $this->success('作废记录成功');
		else $this->error('作废记录失败'); 
	}
	public function delete() {
		$_POST['id'] or $this->error('请选择充值记录');
		$id = implode(',', $_POST['id']);
		$charge = M('charge');
		$condition = 'id in ('.$id.')';
		if(!isset($_POST['enforce'])) $condition .= ' AND status=4';
		$charge->where($condition)->delete(); 
		$this->success('删除充值记录成功');
	}
}