<?php
class FinanceAction extends CommonAction {
    public function _initialize() {
		parent::_initialize();
		$this->assign("navmenu", 3);
    }
	public function charge() {
		$map['_string'] = "uname='".$this->uname."'";
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
		$this->assign("amount", $amount);
		$this->assign("fee", $fee);
		$this->assign("money", $money);
		$this->display();
	}
	public function credit() {
		$map['_string'] = "uname='".$this->uname."'";
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
	public function money() {
		$map['_string'] = "uname='".$this->uname."'";
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
    function pay() {
		$this->assign("jumpUrl",U('Finance/pay'));
		$payment = F('config/pay');
		Vendor('ThinkLC.Pay.Global');
		Vendor('ThinkLC.Pay.Alipay');
		if(isset($_POST['dosubmit'])) {
			$_POST['money'] or $this->error('请填写金额');
			if($_POST['money'] <= 0) $this->error('金额格式错误');
			if(!isset($_POST['paystyle'])) $this->error('请选择支付方式');
			if($_POST['paystyle'] && empty($_POST['payid'])) $this->error('请选择支付平台');
			elseif(!$_POST['paystyle'] && empty($_POST['bank'])) $this->error('请选择支付平台');
			$data = array();
			$data['uname'] = $this->uname;
			$data['money'] = dround($_POST['money']);
			$data['sendtime'] = time();
			$data['ordernum'] = date('Ymdhms').mt_rand(10,99).$this->uid;
			$data['note'] = $_POST['note'] ? $_POST['note'] : '订单号:'.$data['ordernum'];
			$data['paystyle'] = $_POST['paystyle'];
			$data['status'] = 4;
			if($_POST['paystyle']) {
				$pay_name = $_POST['payid'];
				if(!isset($payment[$pay_name])) $this->error('支付平台错误');
				$pay_cfg = $payment[$pay_name];
				$data['bank'] = $pay_cfg['name'];
				$data['fee'] = $pay_cfg['fee'] ? dround($_POST['money'] * $pay_cfg['fee'] / 100) : 0;
				$data['amount'] = $data['money'] + $data['fee'];
			} else {
				$data['bank'] = $_POST['bank'];
				$data['fee'] = 0;
				$data['amount'] = $data['money'];
			}
			$charge = M("Charge");
			$result = $charge->add($data);
			if(false !== $result) {
				if($_POST['paystyle']) {
					// 在线支付，生成支付表单
					$uinfo = M("Member")->getById($this->uid);
					// add order info
					$order_info['id']	= $data['ordernum'];
					$order_info['quantity']	= $_POST['quantity'] ? trim(intval($_POST['quantity'])) : 1;
					$order_info['buyer_email']	= $uinfo['email'];
					$order_info['order_time']	= $data['sendtime'];
					
					//add product info
					$product_info['name'] = $data['uname'].'在线充值';
					$product_info['body'] = $data['note'];
					$product_info['price'] = $data['amount'];
					$product_info['url'] = C('site_url').U('/Member');
					
					//add set_customerinfo
					$customerinfo['telephone'] = $uinfo['tel'];
					import("@.Pay.Factory");
					$payment_handler = new Factory($pay_name, $pay_cfg);
					$payment_handler->set_productinfo($product_info)->set_orderinfo($order_info)->set_customerinfo($customer_info);
					$code = $payment_handler->get_code('value="确认支付" class="button" onclick="javascript:{this.disabled=true;document.payform.submit();}"');
					$this->assign("code", $code);
				}
				$this->assign("data", $data);
				$this->display('confirm');
			} else {
				$this->error('订单提交失败');
			}
		} else {
			$paytype = explode('|',$this->MCFG['paytype']);
			$this->assign("paytype", $paytype);
			$this->assign("payment", $payment);
			$this->display();
		}
    }
}