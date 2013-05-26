<?php
class AreaAction extends CommonAction {
	public function bupdate() {
		//批量更新
		if(!$_POST['area'] || !is_array($_POST['area']) ) $this->error('参数错误');
		$model = D('Area');
		foreach($_POST['area'] as $k=>$v) {
			if(!$v['aname']) continue;
			$v['id'] = $k;
			$v['listorder'] = intval($v['listorder']);
			if(empty($v['alias'])) $v['alias'] = get_alias($v['aname']);
			$model->save($v);
		}
		$this->_cache();
		$this->success('地区更新成功');
	}
	public function _before_index() {
		if(empty($_REQUEST['_order'])) $_REQUEST['_order'] = 'listorder';
		if(empty($_REQUEST['_sort'])) $_REQUEST['_sort'] = 'asc';
	}
	public function _after_insert() {
		$this->_cache();
	}
	public function _after_update() {
		$this->_cache();
	}
	public function _after_delete() {
		$module = F('config/module');
		// 更新该地区信息状态为待审核
		foreach($module as $v) {
			if($v['id']>1) {
				$name = $v['mname'];
				M($name)->where('area='.$v['id'])->setField('status',0);
			}
		}
		$this->_cache();
	}
	public function _after_order() {
		$this->_cache();
	}
}