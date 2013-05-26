<?php
class HelpAction extends CommonAction {
	protected $moduleid = 2;
    public function _initialize() {
		$this->checkModuleStatus($this->moduleid);
		parent::_initialize();
		$etype = F('config/etype_help');
		$this->assign("etype", $etype);
		$this->assign("uid", $this->uid);
		$this->assign("navmenu", 4);
    }
    function detail() {
		if(!$_GET['id']) $this->error('ID错误');
        $model = M('Help');
        $row = $model->find($_GET['id']);
		if(!$row) $this->error('问题不存在');
		if($row['uid']!=$this->uid) $this->error('权限错误，请勿非法操作');
        $this->assign('row', $row);
        $this->display();
    }
}