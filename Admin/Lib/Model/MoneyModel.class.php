<?php
class MoneyModel extends CommonModel {
	protected $_validate	=	array(
		array('uname','require','用户名不能为空'),
		array('money','require','金额不能为空！'),
		array('bank','require','支付方式不能为空！'),
		array('reason','require','事由不能为空！'),
	);
}