<?php
class HelpAction extends CommonAction {
	protected $moduleid = 2;
    public function _initialize() {
		$this->checkModuleStatus($this->moduleid);
		parent::_initialize();
		$this->assign ("extend_title", '帮助中心');
		$this->assign("jumpUrl",U('Help/index'));
	}
    public function _empty($name) {
			$this->assign("jumpUrl",U('Help/index'));
			$this->error('非法操作，请与管理员联系');
	}
	public function index() {
        $map = $this->_search();
		$map['status'] = array('eq',1);
		$map['display'] = array('eq',1);
        $model = M('Help');
        $this->_list($model, $map);
        $this->display();
    }
    public function show() {
		if($_GET['id']) {
		    $id = $_GET["id"];
            $help = M('Help'); 
            $row = $help->where('id='.$id.' AND status=1 AND display=1')->find(); 
			if($row) {
				$this->assign("site_title", $row['title'].' - ');
				if($row['content']!='') C('site_keywords',strip_tags($row['content']));
				else C('site_keywords',$row['title']);
				if($row['answer']!='') C('site_description',msubstr(h(strip_tags($row['answer'])),0,200));
				else C('site_description',$row['title']);
				$this->assign ( "row", $row );   
				$this->display();
			} else {
				$this->error('信息ID不存在');
			}
		} else {
			$this->error('信息ID错误');
		}
	}
    public function usergrade() {
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
		$this->assign("module", $module);
		$this->assign("grouplist", $grouplist);
		$this->assign("groupsetting", $groupsetting);
		$this->assign("groupname", $group[$this->ugid]['gname']);
		$this->assign("ugid", $this->ugid);
		$width = intval(80/(count($groupsetting)+1));
		$this->assign("width", $width);
		$this->assign("colspan", (count($groupsetting)+2));
		$this->assign("site_title", '会员等级与权限 - '); 
		$this->display();
	}
    public function top() {
		$modules = getModule();
		$CFGS = $module = array();
		foreach($modules as $v) {
			if($v['id']>4) {
				$CFGS[$v['id']] = F('config/module_'.$v['id']);
				$module[$v['id']] = $v;
			}
		}
		$this->assign("CFGS", $CFGS);
		$this->assign("module", $module);
		$this->assign("site_title", '信息置顶价格与优惠 - '); 
		$this->display();
	}
}