<?php
class CreditModel extends CommonModel {
	protected $_validate	=	array(
		array('uname','require','用户名不能为空'),
		array('money','require','分值不能为空！'),
		array('reason','require','事由不能为空！'),
	);
}