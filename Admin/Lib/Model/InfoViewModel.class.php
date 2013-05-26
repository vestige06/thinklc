<?php
class InfoViewModel extends ViewModel {
	public $viewFields = array(
		'Info' => array('id', 'uid', 'expired', 'COUNT(Info.id)'=>'count', '_type'=>'LEFT'),
		'Member' => array('uname', '_on' => 'Info.uid=Member.id'),
	);
}