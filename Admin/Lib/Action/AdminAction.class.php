<?php
class AdminAction extends CommonAction {
	protected function _initialize() {
		parent::_initialize();
		if(isset($_REQUEST['id'])) {
			$admin = D('Admin');
			$founder = $admin->where('id='.$_REQUEST['id'])->getField('founder');
			if($founder) $this->error('创始人用户禁止更改');
		}
	}
	public function _before_index(){
		$rolelist = F('config/role');
		$this->assign('rolelist', $rolelist);
	}
	public function _before_add(){
		$rolelist = F('config/role');
		$this->assign('rolelist', $rolelist);
	}
	public function _before_edit(){
		$rolelist = F('config/role');
		$this->assign('rolelist', $rolelist);
	}
}