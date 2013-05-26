<?php
// 充值状态响应模块
defined('IN_WEB') or exit('No permission resources.');
class RespondAction extends Action {
	protected $model;
	protected $payid;
	protected $payment;
    public function _initialize() {
		$this->model = M('Charge');
		$this->payid = $_REQUEST['payid'];
		$this->payment = F('config/pay');
		Vendor('ThinkLC.System');
		Vendor('ThinkLC.Pay.Global');
		Vendor('ThinkLC.Pay.Alipay');
		$this->assign("jumpUrl",U('Finance/charge'));
    }
	/**
	 * return_url get形式响应
	 */
	public function respond_get() {
		if ($_GET['payid']){
			$pay_name = $_GET['payid'];
			if(!isset($this->payment[$pay_name])) $this->error('支付平台错误');
			$pay_cfg = $this->payment[$pay_name];
			import("@.Pay.Factory");
			$payment_handler = new Factory($pay_name, $pay_cfg);
			$return_data = $payment_handler->receive();
			if($return_data) {
				if($return_data['order_status'] == 0) {				
					$this->update_member_amount_by_sn($return_data['order_id'],'R');
				}
				$this->update_recode_status_by_sn($return_data['order_id'],$return_data['order_status'],'R');
				$this->success('支付成功');
			} else {
				$this->error('支付失败');
			}
		} else {
			$this->success('支付完成');
		}
	}

	/**
	 * 服务器端 POST形式响应
	 */
	public function respond_post() {
		$_POST['payid'] = $_POST['payid'] ? $_POST['payid'] : $_GET['payid'];
		if ($_POST['payid']){
			$pay_name = $_POST['payid'];
			if(!isset($this->payment[$pay_name])) error_log(date('m-d H:i:s').'| POST: payment is null |'."\r\n", 3, LOG_PATH.'pay_error_log.php');
			$pay_cfg = $this->payment[$pay_name];
			import("@.Pay.Factory");
			$payment_handler = new Factory($pay_name, $pay_cfg);
			$return_data = $payment_handler->notify();
			if($return_data) {
				if($return_data['order_status'] == 0) {
					$this->update_member_amount_by_sn($return_data['order_id'],'N');
				}
				$this->update_recode_status_by_sn($return_data['order_id'],$return_data['order_status'],'N');				
                $result = TRUE;
			} else {
				$result = FALSE;
			}
			$payment_handler->response($result);
		}
	}

	/**
	 * 更新订单状态
	 * @param unknown_type $trade_sn 订单ID
	 * @param unknown_type $status 订单状态
	 */
	private function update_recode_status_by_sn($ordernum,$status,$post='N') {
		$ordernum = trim($ordernum);
		$status = trim(intval($status));
		$data = array();
		$data['status'] = $status;
		$data['editor'] = $post.':'.$this->payid;
		if(!$status) $data['receivetime'] = time();
		return $this->model->where("ordernum='".$ordernum."'")->save($data);
	}

	/**
	 * 更新用户账户余额
	 * @param unknown_type $ordernum
	 */
	private function update_member_amount_by_sn($ordernum,$post='N') {
		$data = $userinfo = array();
		$orderinfo = $this->get_orderinfo_by_sn($ordernum);
		if($orderinfo){
			if($orderinfo['status']==3 || $orderinfo['status']==4) {
				money_add($orderinfo['uname'], $orderinfo['money']);
				money_record($orderinfo['uname'], $orderinfo['money'], $orderinfo['bank'], $post.':'.$this->payid, '在线充值', $orderinfo['note']);
				$MCFG = F('config/module_1');
				$credit_pay = intval($orderinfo['money'] * $MCFG['credit_pay']);
				if($credit_pay) {
					credit_add($orderinfo['uname'], $credit_pay);
					credit_record($orderinfo['uname'], $credit_pay, $post.':'.$this->payid, '充值奖励', $orderinfo['note']);
				}
			} else {
				error_log(date('m-d H:i:s').'| POST: already completed! trade_sn:'.$ordernum.' |'."\r\n", 3, LOG_PATH.'pay_info_log.php');
				return false;
			}
		} else {
			error_log(date('m-d H:i:s').'| POST: rechange failed! trade_sn:'.$ordernum.' |'."\r\n", 3, LOG_PATH.'pay_error_log.php');
			return false;
		}
	}
	
	/**
	 * 通过订单ID抓取用户信息
	 * @param unknown_type $ordernum
	 */
	private function get_orderinfo_by_sn($ordernum) {
		$ordernum = trim($ordernum);
		$result = $this->model->getByOrdernum($ordernum);
		return ($result) ? $result : false;
	}
}