<?php
class CreditAction extends CommonAction {
	public function index() {
		if(!empty($_GET['fieldskey']) && !empty($_GET['fieldsvalue'])) $GLOBALS['_REQUEST'][$_GET['fieldskey']] = $_GET['fieldsvalue'];
		$map = $this->_search('Credit');
		$_query = '1';
		if($_GET['type']==1) $_query .= ' AND amount>0';
		elseif($_GET['type']==2) $_query .= ' AND amount<0';
		if(!empty($_GET['fromtime'])) $_query .= ' AND addtime>'.(strtotime($_GET['fromtime'].' 00:00:00'));
		if(!empty($_GET['totime'])) $_query .= ' AND addtime<'.(strtotime($_GET['totime'].' 23:59:59'));
		if($_GET['minamount'] != '') $_query .= ' AND '.$_GET['mtype'].'>='.$_GET['minamount'];
		if($_GET['maxamount'] != '') $_query .= ' AND '.$_GET['mtype'].'<='.$_GET['maxamount'];
		$map['_string'] = $_query;
		$list = $this->_list(D('Credit'), $map);

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
			$_POST['credit'] or $this->error('请填写分值');
			if($_POST['credit'] <= 0) $this->error('分值格式错误');
			$_POST['reason'] or $this->error('请填写事由');
			$unames = explode(",", trim($_POST['uname']));
			foreach($unames as $uname) {
				$uname = trim($uname);
				if(!$uname) continue;
				$model = M('Member');
				$r = $model->getByUname($uname);
				if(!$r) $this->error('会员 '.$uname.' 不存在');
				if(!$_POST['type']) {
					if($r['credit'] < $_POST['credit']) $this->error('会员 '.$uname.' 积分不足，当前积分为: '.$r['credit']);
					$GLOBALS['_POST']['credit'] = -$_POST['credit'];
				}
				$_POST['reason'] or $GLOBALS['_POST']['reason'] = '奖励';
				$_POST['note'] or $GLOBALS['_POST']['note'] = '手工';
				credit_add($uname, $_POST['credit']);
				credit_record($uname, $_POST['credit'], $this->adminname, $_POST['reason'], $_POST['note']);
			}
			if($_POST['doclose']) closeDialog('doDialog');
			else $this->success('积分添加成功');
		} else {
			$this->display();
		}
	}
}