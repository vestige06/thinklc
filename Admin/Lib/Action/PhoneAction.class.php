<?php
// 电话黄页模块
class PhoneAction extends CommonAction {
	protected $moduleid = 6;
	protected $CATEGORY;
	protected $AREA;
	protected $CFG;
	protected function _initialize() {
		parent::_initialize();
		$this->CATEGORY = F('config/category_'.$this->moduleid);
		$this->CFG = F('config/module_'.$this->moduleid);
		$this->AREA = F('config/area');
		$module = F('config/module');
		$this->assign("moduleid",$this->moduleid);
		$this->assign("modulename",$module[$this->moduleid]['mtitle']);
		$this->assign("CATEGORY",$this->CATEGORY);
		$this->assign("AREA",$this->AREA);
		$this->assign("CFG",$this->CFG);
	}
	public function _before_index() {
		if(!empty($_GET['fieldskey']) && !empty($_GET['fieldsvalue'])) $GLOBALS['_REQUEST'][$_GET['fieldskey']] = $_GET['fieldsvalue'];
	}
	public function _filter(&$map) {
		$map['_string'] = 'status=1';
	}
	public function move(){
		$mcatid = $_REQUEST['mcatid'];
		$id = $_POST['id'];
		if(!$mcatid || !$id) $this->error('参数错误');
		if(is_array($id)) $condition = 'id IN ('.implode(',',$id).')';
		else $condition = 'id='.$id;
		$info = D('Phone');
		$result = $info->where($condition)->setField('catid',$mcatid);
		if(false !== $result) $this->success('移动分类成功');
		else $this->error('移动分类失败');
	}
	public function check(){
		if(!empty($_GET['fieldskey']) && !empty($_GET['fieldsvalue'])) $GLOBALS['_REQUEST'][$_GET['fieldskey']] = $_GET['fieldsvalue'];
		$map = $this->_search('Phone');
		$map['_string'] = 'status=0';

        $model = M('Phone');
		$this->_list($model, $map, 'id');
		$this->display();
	}
	public function spread(){
		if($_GET['do']=='cache') $this->cachespread();
		if(!empty($_GET['fieldskey']) && !empty($_GET['fieldsvalue'])) $GLOBALS['_REQUEST'][$_GET['fieldskey']] = $_GET['fieldsvalue'];
		$map = $this->_search('Phone');
		$map['_string'] = 'status=1';
		if($_GET['istotime']==1) $map['_string'] .= ' AND spreadtotime>'.time();
		elseif($_GET['istotime']==2) $map['_string'] .= ' AND spreadtotime>0 AND spreadtotime<'.time();
		else $map['_string'] .= ' AND spreadtotime>0';

        $model = M('Phone');
		$this->_list($model, $map);
		$this->display();
	}
	public function cachespread(){
		$model = M('Phone');
		$r = $model->where('spreadtotime>='.time())->field('id,title,tel,mobile')->order('spreadtotime desc')->select();
		F('config/spread_phone',$r);
	}
	public function spreadbuy(){
		if(!$_REQUEST['id']) $this->error('ID错误');
        $model = M('Phone');
        $id = $_REQUEST['id'];
        $vo = $model->find($id);
		if($vo) {
			if($_POST['dosubmit']) {
				if($_POST['deduct'] && !$_POST['uid']) $this->error('用户ID错误，无法扣除费用，置顶失败');
				elseif($_POST['deduct'] && $_POST['uid']) {
					$member = M('Member'); 
					$uinfo = $member->find($_POST['uid']);
					$money = $_POST['money'];
					$paytype = $_POST['paytype'];
					if($paytype) {
						$MCFG=F('config/module_1');
						$credit_money = intval($MCFG['credit_money']);
						$credit = intval($money * $credit_money);
						if($uinfo['credit']<$credit) $this->error('用户 '.$uinfo['uname'].' 账户积分不足');
					} elseif($uinfo['money']<$money) $this->error('用户 '.$uinfo['uname'].' 账户资金不足，请先充值');
				}

				$data = array();
				$data['id'] = $_POST['id'];
				if($vo['spreadtotime']>time()) $data['spreadtotime'] = strtotime("+".$_POST['spreaddays']." month",$vo['spreadtotime']);
				else $data['spreadtotime'] = strtotime("+".$_POST['spreaddays']." month");
				$result	= $model->save($data);
				if(false !== $result) {
					if($_POST['deduct'] && $_POST['uid']) {
						if($paytype) {
							credit_add($uinfo['uname'], -$credit);
							credit_record($uinfo['uname'], -$credit, $this->adminname, 'ID:'.$_POST['id'].',商家推广', '自动');
						} else {
							money_add($uinfo['uname'], -$money);
							money_record($uinfo['uname'], -$money, '站内', $this->adminname, 'ID:'.$_POST['id'].',商家推广', '自动');
						}
					}
					$this->cachespread();
					$this->success('商家推广成功，您已经成功推广 '.$_POST['spreaddays'].' 个月');
				} else {
					$this->error('商家推广失败');
				}
			} else {
				$spread_off = array();
				$spread_price = $this->CFG['spread_price'];
				$spread_off = getPrice($this->CFG['spread_off']);
				if($vo['spreadtotime']>=time()) {
					$this->assign( "spread_status", 1 );
				} else {
					$this->assign( "spread_status", 0 );
				}
				$this->assign( "spread_price", $spread_price ); 
				$this->assign( "spread_off", $spread_off ); 
				$this->assign( "vo", $vo );
				$this->display();
			}
		} else {
			$this->error('信息不存在');
		}
	}
	public function map(){
		$this->display();
	}
	public function edit(){
		if(!$_GET['id']) $this->error('ID错误');
        $model = M('Phone');
        $id = $_GET['id'];
        $vo = $model->find($id);
		if($vo) {
			if($vo['toptotime']>=time()) {
				$is_top = 1;
			} else {
				$is_top = 0;
			}
			$this->assign('vo', $vo);
			$this->assign( "is_top", $is_top);
			$this->display();
		} else {
			$this->error('信息不存在');
		}
	}
	public function delete(){
		$id = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];
	    if($id) {
			if(is_array($id)) $ids = implode(',',$id);
			else $ids = $id;
			$MCFG = F('config/module_1');
			if($MCFG['credit_delete']) {
				$model = D("PhoneView");
				$dscore = $model->field('uname,count')->where('Phone.id IN ('.$ids.')')->group('Phone.uid')->select();
				if($dscore) {
					foreach($dscore as $v) {
						$score = $v['count'] * $MCFG['credit_delete'];		
						credit_add($v['uname'], -$score);
						credit_record($v['uname'], -$score, $this->adminname, '批量删除信息', '后台删除');
					}
				}
			}
		    $model = M('Phone');
		    $model->where('id in ('.$ids.')')->delete();
			$this->success('删除信息成功');
	    } else $this->error('请选择要删除的信息');
	}
	public function _after_insert(){
		if(is_file(HTML_PATH.'/phone_index/p_.html')) unlink(HTML_PATH.'/phone_index/p_.html');
	}
	public function _after_update(){
		if(is_file(HTML_PATH.'/phone_index/p_.html')) unlink(HTML_PATH.'/phone_index/p_.html');
		if(is_file(HTML_PATH.'/phone_detail/item_'.$_REQUEST['id'].'.html')) unlink(HTML_PATH.'/phone_detail/item_'.$_REQUEST['id'].'.html');
	}
	public function _after_delete(){
		if(is_file(HTML_PATH.'/phone_index/p_.html')) unlink(HTML_PATH.'/phone_index/p_.html');
	}
}