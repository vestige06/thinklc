<?php
class GroupAction extends CommonAction {
	public function _before_index() {
		if(empty($_REQUEST['_order'])) $_REQUEST['_order'] = 'listorder';
		if(empty($_REQUEST['_sort'])) $_REQUEST['_sort'] = 'asc';
	}
	public function _after_insert($id=0) {
		$this->_cache();
	}
	public function _after_update($id=0) {
		$this->_cache();
	}
	public function _after_delete($id=0) {
		// 更新被删除会员组会员为普通会员
		M('Member')->where('gid='.$_GET['id'])->setField('gid','4');
		$this->_cache();
	}
	public function _after_order($id=0) {
		$this->_cache();
	}
	public function order() {
		// 更新排序
		$model = D('Group');
		foreach($_POST['listorders'] as $id => $listorder) {
			if($listorder<5) $listorder = $id;
			$model->where('id='.$id)->setField('listorder',$listorder);
		}
		$this->_cache();
		$this->success('更新排序成功');
    }
}