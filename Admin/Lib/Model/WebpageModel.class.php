<?php
class WebpageModel extends CommonModel {
	protected $_validate	=	array(
		array('title','require','标题不能为空！'),
	);
}