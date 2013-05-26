<?php
class PhoneViewModel extends ViewModel {
	public $viewFields = array(
		'Phone' => array('id', 'uid', 'COUNT(Phone.id)'=>'count', '_type'=>'LEFT'),
		'Member' => array('uname', '_on' => 'Phone.uid=Member.id'),
	);
}