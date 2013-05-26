<?php
class HelpModel extends CommonModel {
	protected $_validate	=	array(
		array('tid','require','分类不能为空！'),
		array('title','require','标题不能为空！'),
	);
	protected $_auto = array ( 
		array('asktime','time',1,'function'),
	);
}