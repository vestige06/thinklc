<?php
class EtypeModel extends CommonModel {
	protected $_validate	=	array(
		array('typename','require','分类名不能为空！'),
		array('item','require','参数错误！'),
	);
}