<?php
class AboutAction extends CommonAction {
	protected $moduleid = 3;
    public function _initialize() {
		$this->checkModuleStatus($this->moduleid);
		parent::_initialize();
		$this->assign ("extend_title", '关于我们');
	}
    public function _empty($name) {
		$alias = $name ? $name : 'index';
		$about = F('config/webpage');
		foreach($about as $v) {
			if(strtolower($v['alias']) == strtolower($alias)) {
				$this->assign("about",$v);
				$this->display('index');
				exit;
			}
		}
		$this->assign("jumpUrl",U('About/index'));
		$this->error('非法操作，请与管理员联系');
	}
}