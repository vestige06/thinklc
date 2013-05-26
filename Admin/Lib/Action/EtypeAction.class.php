<?php
class EtypeAction extends CommonAction {
	protected $item;
    protected function _initialize() {
		parent::_initialize();
		$this->assign("jumpUrl",$_SERVER['HTTP_REFERER']);
		if(!isset($_REQUEST['item'])) $this->error('参数错误');
		$this->item = $_REQUEST['item'];
		$this->assign("item",$this->item);
	}
	public function _before_index() {
		switch($this->item) {
			case 'help':
				$menus = array (
					array('title'=>"帮助管理", 'url'=>U('Help/index')),
					array('title'=>"问题分类", 'url'=>__SELF__),
				);
			break;
			case 'link':
				$menus = array (
					array('title'=>"友情链接", 'url'=>U('Link/index')),
					array('title'=>"链接分类", 'url'=>__SELF__),
				);
			break;
			default:
				$menus = array (
					array('title'=>'分类管理', 'url'=>__SELF__),
				);
			break;
		}
		$this->assign("menus",$menus);
		if(empty($_REQUEST['_order'])) $_REQUEST['_order'] = 'listorder';
		if(empty($_REQUEST['_sort'])) $_REQUEST['_sort'] = 'asc';
	}
	public function update() {
		$this->assign("jumpUrl",U('Etype/index?item='.$this->item));
		$this->_add($_POST['etype']['0']);
		unset($_POST['etype']['0']);
		foreach($_POST['etype'] as $k=>$v) {
			if(isset($v['delete'])) {
				$this->_delete($k);
				unset($_POST['etype'][$k]);
			}
		}
		$this->_edit($_POST['etype']);
		cache_etype($this->item);
		$this->success('更新分类成功');
	}
	protected function _add($post) {
		if(!$post['typename']) return false;
		$post['listorder'] = intval($post['listorder']);
		$etype = D('Etype');
		$data = array();
		$data['listorder'] = $post['listorder'];
		$data['typename'] = $post['typename'];
		$data['item'] = $this->item;
		$result	= $etype->add($data);
	}
	protected function _delete($id) {
		$etype = D('Etype');
		$etype->where('id='.$id)->delete();
	}
	protected function _edit($post) {
		$etype = D('Etype');
		foreach($post as $k=>$v) {
			if(!$v['typename']) continue;
			$v['listorder'] = intval($v['listorder']);
			$data = array();
			$data['id'] = $k;
			$data['listorder'] = intval($v['listorder']);
			$data['typename'] = $v['typename'];
			$result	= $etype->save($data);
		}
	}
}