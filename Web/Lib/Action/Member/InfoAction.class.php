<?php
// 信息模块
class InfoAction extends CommonAction {
	protected $moduleid = 5;
	protected $CATEGORY;
	protected $AREA;
	protected $CFG;
	protected function _initialize() {
		$this->checkModuleStatus($this->moduleid);
		parent::_initialize();
		$modules = getModule();
		$this->CATEGORY = F('config/category_'.$this->moduleid);
		$this->CFG = F('config/module_'.$this->moduleid);
		$this->CFG['cell_width'] = explode('|',$this->CFG['cell_width']);
		$this->AREA = F('config/area');
		$this->assign("moduleid",$this->moduleid);
		$this->assign("modulename",$modules[$this->moduleid]['mtitle']);
		$this->assign("CATEGORY",$this->CATEGORY);
		$this->assign("AREA",$this->AREA);
		$this->assign("CFG",$this->CFG);
		$this->assign("uid", $this->uid); 
		$this->assign("navmenu", 2); 
		$this->assign("jumpUrl",$_SERVER['HTTP_REFERER']);
	}
	public function add() {
		$this->assign("jumpUrl",U('Info/index'));
		$limit = get_g_rule($this->ugid,'info','count');
		if(!$limit) $this->error('当前会员组禁止发布信息');
		$post = getLimit($this->uid, $this->moduleid, 'post');
		if($post['val']>=$limit) {
			$this->error('今日最多可发布 '.$limit.' 条信息，当前已发布 '.$post['val'].' 条');
		} else {
			if(get_g_rule($this->ugid,'info','title')) $this->assign( "rule_title", 1 );
			else $this->assign( "rule_title", 0 );
			if(get_g_rule($this->ugid,'info','pic')) $this->assign( "rule_pic", 1 );
			else $this->assign( "rule_pic", 0 );
			if(get_g_rule($this->ugid,'info','detail')) $this->assign( "rule_detail", 1 );
			else $this->assign( "rule_detail", 0 );
			if(get_g_rule($this->ugid,'info','link')) $this->assign( "rule_link", 1 );
			else $this->assign( "rule_link", 0 );
			$status = get_g_rule($this->ugid,'info','pass');
			$this->assign("status", $status);
			$member = M('Member'); 
			$uinfo = $member->find($this->uid);
			$this->assign("uinfo", $uinfo);
			$this->display();  
		}
    }
	public function edit(){
		if(!$_GET['id']) $this->error('ID错误');
        $model = M('Info');
        $id = $_GET['id'];
        $row = $model->find($id);
		if($row) {
			if($row['topstatus'] && $row['toptotime']>=time()) {
				$is_top = 1;
			} else {
				$is_top = 0;
				$row['topnum'] = 1;
				$row['content'] = strip_tags($row['content'],"<BR>");
				$row['content'] = str_replace("<BR>","\r\n",$row['content']);
			}
			$topnum = $row['topnum']==5 ? ($row['topnum']-2) : ($row['topnum']-1);
			$allow = array();
			$allow['cell_width'] = $this->CFG['cell_width'][$topnum];
			$allow['cell_height'] = $this->CFG['cell_height'];
			$allow['cell_eheight'] = $is_top ? (40 + $this->CFG['cell_height']) : $this->CFG['cell_height'];
			$allow['cell_titlelen'] = $row['topnum'] * $this->CFG['cell_titlelen'];
			$allow['cell_contentlen'] = $row['topnum'] * $this->CFG['cell_contentlen'];
			if(get_g_rule($this->ugid,'info','title') || get_t_rule($this->moduleid,'title',$row['topstatus'],$row['viptotime'])) $this->assign( "rule_title", 1 );
			else $this->assign( "rule_title", 0 );
			if(get_g_rule($this->ugid,'info','pic') || get_t_rule($this->moduleid,'pic',$row['topstatus'],$row['viptotime'])) $this->assign( "rule_pic", 1 );
			else $this->assign( "rule_pic", 0 );
			if(get_g_rule($this->ugid,'info','detail') || get_t_rule($this->moduleid,'detail',$row['topstatus'],$row['viptotime'])) $this->assign( "rule_detail", 1 );
			else $this->assign( "rule_detail", 0 );
			if(get_g_rule($this->ugid,'info','link') || get_t_rule($this->moduleid,'link',$row['topstatus'],$row['viptotime'])) $this->assign( "rule_link", 1 );
			else $this->assign( "rule_link", 0 );
			$this->assign('allow', $allow);
			$this->assign('row', $row);
			$this->assign("is_top", $is_top);
			$this->display();
		} else {
			$this->assign("jumpUrl",U('Payment/index'));
			$this->error('信息不存在');
		}
	}
	public function _call_after_insert($id){
		setLimitInc($this->uid, $this->moduleid, 'post');
		buildIndex();
		if($this->MCFG['credit_post']) {
			credit_add($this->uname, $this->MCFG['credit_post']);
			credit_record($this->uname, $this->MCFG['credit_post'], 'system', '发布信息ID:'.$id, '自动');
		}
	}
	public function _after_update(){
		if(is_file(HTML_PATH.'/info_detail/item_'.$_REQUEST['id'].'.html')) unlink(HTML_PATH.'/info_detail/item_'.$_REQUEST['id'].'.html');
		buildIndex();
	}
	public function _call_after_delete($row){
		buildIndex();
		if($this->MCFG['credit_delete'] && ($row['expired']==0 || $row['expired']>=time())) {
			//删除非过期信息扣除积分
			credit_add($this->uname, -$this->MCFG['credit_delete']);
			credit_record($this->uname, -$this->MCFG['credit_delete'], $this->uname, '删除信息ID:'.$row['id'], '会员删除');
		}
	}
	public function top(){
		if(!$_REQUEST['id']) $this->error('ID错误');
        $model = M('Info');
        $id = $_REQUEST['id'];
        $row = $model->find($id);
		if($row) {
			if($_POST['dosubmit']) {
				$member = M('Member'); 
				$uinfo = $member->find($this->uid);
				$money = $_POST['money'];
				$paytype = $_POST['paytype'];
				if($paytype) {
					$credit_money = intval($this->MCFG['credit_money']);
					$credit = intval($money * $credit_money);
					if($uinfo['credit']<$credit) $this->error('您的账户积分不足');
				} elseif($uinfo['money']<$money) {
					$this->error('您的账户资金不足，请先充值');
				}

				$data = array();
				$data['id'] = $_POST['id'];
				$data['topnum'] = $_POST['topnum'];
				$data['topstatus'] = $_POST['topstatus'];
				if($row['topstatus'] && $row['toptotime']>time()) $data['toptotime'] = strtotime("+".$_POST['topdays']." month",$row['toptotime']);
				else $data['toptotime'] = strtotime("+".$_POST['topdays']." month");
				$result	= $model->save($data);
				if(false !== $result) {
					if($paytype) {
						credit_add($this->uname, -$credit);
						credit_record($this->uname, -$credit, 'system', 'ID:'.$_POST['id'].',信息置顶', '自动');
					} else {
						money_add($this->uname, -$money);
						money_record($this->uname, -$money, '站内', 'system', 'ID:'.$_POST['id'].',信息置顶', '自动');
					}
					buildIndex();
					$this->success('置顶成功，您已经成功置顶 '.$_POST['topdays'].' 个月');
				} else {
					$this->error('置顶失败');
				}
			} else {
				$top_price = $top_off_1 = $top_off_2 = $top_off_3 = $top_off_5 = array();
				$top_price = getPrice($this->CFG['top_price']);
				$top_off_1 = getPrice($this->CFG['top_off_1']);
				$top_off_2 = getPrice($this->CFG['top_off_2']);
				$top_off_3 = getPrice($this->CFG['top_off_3']);
				$top_off_5 = getPrice($this->CFG['top_off_5']);
				if($row['topstatus'] && $row['toptotime']>=time()) {
					$this->assign( "top_status", 1 );
					$tmp1 = $row['topnum'] == 5 ? ($row['topnum']-2) : ($row['topnum']-1);
					$tmp2 = $row['topstatus'] - 1;
					$this->assign( "top_startmoney", $top_price[$tmp1][$tmp2] );
				} else {
					$this->assign( "top_status", 0 );
					$this->assign( "top_startmoney", $top_price['0']['0'] );
				}
				if($this->CFG['top_credit'] && get_g_rule($this->ugid,'info','credit')) $this->assign( "top_credit", 1 );
				else $this->assign( "top_credit", 0 );
				$this->assign( "top_price", $top_price ); 
				$this->assign( "top_off_1", $top_off_1 ); 
				$this->assign( "top_off_2", $top_off_2 ); 
				$this->assign( "top_off_3", $top_off_3 ); 
				$this->assign( "top_off_5", $top_off_5 ); 
				$this->assign( "row", $row );
				$this->display();
			}
		} else {
			$this->error('信息不存在');
		}
	}
}