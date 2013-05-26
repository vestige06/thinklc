<?php
class WebpageAction extends CommonAction {
	public function _after_insert() {
		$this->_cache();
	}
	public function _after_update() {
		$this->_cache();
	}
	public function _after_delete() {
		$this->_cache();
	}
	public function _after_order() {
		$this->_cache();
	}
}