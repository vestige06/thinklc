<?php
class CategoryModel extends CommonModel {
	protected $_validate	=	array(
		array('moduleid','require','模块ID不能为空！'),
		array('catname','require','分类名称不能为空！'),
		array('alias','','英文别名已经存在',2,'unique',self::MODEL_BOTH),
	);
}