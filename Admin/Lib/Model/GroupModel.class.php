<?php
class GroupModel extends CommonModel {
	protected $_validate	=	array(
		array('gname','require','会员组名称不能为空！'),
	);
}