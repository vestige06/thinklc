<?php
class LinkAction extends CommonAction {
	public function _before_index() {
		$etype = F('config/etype_link');
		$this->assign( "etype", $etype );
		if(empty($_REQUEST['_order'])) $_REQUEST['_order'] = 'listorder';
		if(empty($_REQUEST['_sort'])) $_REQUEST['_sort'] = 'asc';
	}
	public function _before_add() {
		$etype = F('config/etype_link');
		$this->assign( "etype", $etype ); 
	}
	public function _before_edit() {
		$etype = F('config/etype_link');
		$this->assign( "etype", $etype ); 
	}
	public function _after_insert() {
		$this->_cache();
	}
	public function _after_update() {
		$this->_cache();
	}
	public function _after_forbid() {
		$this->_cache();
	}
	public function _after_resume() {
		$this->_cache();
	}
	public function _after_delete() {
		$this->_cache();
	}
	public function _after_order() {
		$this->_cache();
	}
}