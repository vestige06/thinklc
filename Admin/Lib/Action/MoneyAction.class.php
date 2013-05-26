<?php
class MoneyAction extends CommonAction {
	protected function _initialize() {
		parent::_initialize();
		$MCFG = F('config/module_1');
		$paytype = explode('|', $MCFG['paytype']);
		$this->assign('paytype', $paytype);
	}
	public function index() {
		if(!empty($_GET['fieldskey']) && !empty($_GET['fieldsvalue'])) $GLOBALS['_REQUEST'][$_GET['fieldskey']] = $_GET['fieldsvalue'];
		$map = $this->_search('Money');
		$_query = '1';
		if($_GET['type']==1) $_query .= ' AND amount>0';
		elseif($_GET['type']==2) $_query .= ' AND amount<0';
		if(!empty($_GET['fromtime'])) $_query .= ' AND addtime>'.(strtotime($_GET['fromtime'].' 00:00:00'));
		if(!empty($_GET['totime'])) $_query .= ' AND addtime<'.(strtotime($_GET['totime'].' 23:59:59'));
		if($_GET['minamount'] != '') $_query .= ' AND '.$_GET['mtype'].'>='.$_GET['minamount'];
		if($_GET['maxamount'] != '') $_query .= ' AND '.$_GET['mtype'].'<='.$_GET['maxamount'];
		$map['_string'] = $_query;
		$list = $this->_list(D('Money'), $map);

		$records = array();
		$income = $expense = 0;
		foreach($list as $r) {
			$r['addtime'] = date("Y-m-d H:i",$r['addtime']);
			$r['amount'] > 0 ? $income += $r['amount'] : $expense += $r['amount'];
			$r['editor'] or $r['editor'] = 'system';
			$records[] = $r;
		}
        $this->assign('list', $records);
		$this->assign("income", $income);
		$this->assign("expense", $expense);
		$this->display();
	}
	public function add() {
		if($_POST['dosubmit']) {
			$_POST['uname'] or $this->error('请填写会员名');
			$_POST['money'] or $this->error('请填写金额');
			if($_POST['money'] <= 0) $this->error('金额格式错误');
			$_POST['bank'] or $this->error('请选择支付方式');
			$_POST['reason'] or $this->error('请填写事由');
			$unames = explode(",", trim($_POST['uname']));
			foreach($unames as $uname) {
				$uname = trim($uname);
				if(!$uname) continue;
				$model = M('Member');
				$r = $model->getByUname($uname);
				if(!$r) $this->error('会员 '.$uname.' 不存在');
				if(!$_POST['type']) {
					if($r['money'] < $_POST['money']) $this->error('会员 '.$uname.' 余额不足，当前余额为: '.$r['money']);
					$GLOBALS['_POST']['money'] = -$_POST['money'];
				}
				$_POST['reason'] or $GLOBALS['_POST']['reason'] = '账户充值';
				$_POST['note'] or $GLOBALS['_POST']['note'] = '手工';
				money_add($uname, $_POST['money']);
				money_record($uname, $_POST['money'], $_POST['bank'], $this->adminname, $_POST['reason'], $_POST['note']);
				$ordernum = date('Ymdhms').mt_rand(10,99).$r['id'];
				if($_POST['charge'] && $_POST['type']) charge_add($uname, $_POST['bank'], $_POST['money'], 0, time(), time(), $this->adminname, 7, $_POST['note'], $ordernum);
			}
			if($_POST['doclose']) closeDialog('doDialog');
			else $this->success('资金添加成功');
		} else {
			$this->display();
		}
	}
}