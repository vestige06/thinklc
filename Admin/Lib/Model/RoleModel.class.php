<?php
class RoleModel extends CommonModel {
	protected $_validate	=	array(
		array('rname','require','角色名称不能为空！'),
	);
}