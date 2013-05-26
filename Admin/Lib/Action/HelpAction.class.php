<?php
class HelpAction extends CommonAction {
	public function _before_index() {
		$etype = F('config/etype_help');
		$this->assign( "etype", $etype ); 
		if(!empty($_GET['fieldskey']) && !empty($_GET['fieldsvalue'])) $GLOBALS['_REQUEST'][$_GET['fieldskey']] = $_GET['fieldsvalue'];
	}
	public function _before_add() {
		$etype = F('config/etype_help');
		$this->assign( "etype", $etype ); 
	}
	public function _before_edit() {
		$etype = F('config/etype_help');
		$this->assign( "etype", $etype ); 
	}
}