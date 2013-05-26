<?php
class AreaModel extends CommonModel {
	protected $_validate	=	array(
		array('aname','require','地区名称不能为空！'),
		array('alias','','英文别名已经存在',2,'unique',self::MODEL_BOTH),
	);
}