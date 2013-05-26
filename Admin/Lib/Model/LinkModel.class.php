<?php
class LinkModel extends CommonModel {
	protected $_validate	=	array(
		array('tid','require','链接分类不能为空！'),
		array('title','require','链接名称不能为空！'),
		array('linkurl','require','链接URL不能为空！'),
	);
}