<?php
class CategoryAction extends CommonAction {
	protected $moduleid;
	protected function _initialize() {
		parent::_initialize();
		if(!isset($_REQUEST['moduleid'])) $this->error('模块ID错误');
		$this->moduleid = $_REQUEST['moduleid'];
		$this->assign("moduleid",$this->moduleid);
	}
	public function bupdate() {
		//批量更新分类
		if(!$_POST['category'] || !is_array($_POST['category']) ) $this->error('参数错误');
		$model = D('Category');
		foreach($_POST['category'] as $k=>$v) {
			if(!$v['catname']) continue;
			$v['id'] = $k;
			$v['listorder'] = intval($v['listorder']);
			if(empty($v['alias'])) $v['alias'] = get_alias($v['catname']);
			$model->save($v);
		}
		cache_category($this->moduleid);
		$this->success('分类更新成功');
	}
	public function _before_index() {
		if(empty($_REQUEST['_order'])) $_REQUEST['_order'] = 'listorder';
		if(empty($_REQUEST['_sort'])) $_REQUEST['_sort'] = 'asc';
	}
	public function _after_insert() {
		cache_category($this->moduleid);
	}
	public function _after_update() {
		cache_category($this->moduleid);
	}
	public function _after_delete() {
		$module = F('config/module');
		$name = $module[$this->moduleid]['mname'];
		// 更新该分类信息状态为待审核
		M($name)->where('cate='.$_GET['id'])->setField('status',0);
		cache_category($this->moduleid);
	}
	public function _after_order() {
		cache_category($this->moduleid);
	}
}