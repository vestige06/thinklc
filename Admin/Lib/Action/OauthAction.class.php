<?php
class OauthAction extends CommonAction {
	public function _before_index(){
		if(!empty($_GET['fieldskey']) && !empty($_GET['fieldsvalue'])) $GLOBALS['_REQUEST'][$_GET['fieldskey']] = $_GET['fieldsvalue'];
		$oauth = F('config/oauth');
		$this->assign("oauth", $oauth);
	}
}