<?php
class InfoTopModel extends ViewModel {
	public $viewFields = array(
		'Info' => array('id','title','content','detail','edittime','areaid','catid','linkurl','ispic','picurl','extend','contact','toptotime','topstatus','topnum','toptitle','_type'=>'LEFT'),
		'Member' => array('amount', '_on' => 'Info.uid=Member.id'),
	);
}