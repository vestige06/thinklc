<?php
return array(
    'HTML_FILE_SUFFIX'	=> '.html',			// 静态缓存文件后缀
	'HTML_PATH'			=> './Data/Html',	// 静态缓存目录
    'HTML_READ_TYPE'        => 0,			// 静态缓存读取方式 0 readfile 1 redirect
	'HTML_CACHE_RULES'		=> array (
		'info:index'=>array('info_index/p_{p}'),
		'info:detail'=>array('info_detail/item_{id}','3600'),
		'info:cate'=>array('info_cate/{id}_{p}'),
		'info:area'=>array('info_area/{id}_{p}'),
		'info:search'=>array('info_search/{$_SERVER.REQUEST_URI|md5}'),
		'phone:index'=>array('phone_index/p_{p}'),
		'phone:detail'=>array('phone_detail/item_{id}','3600'),
		'phone:cate'=>array('phone_cate/{id}_{p}'),
		'phone:area'=>array('phone_area/{id}_{p}'),
		'phone:search'=>array('phone_search/{$_SERVER.REQUEST_URI|md5}'),
		'help:index'=>array('help/index_{$_SERVER.REQUEST_URI|md5}','3600'),
		'help:show'=>array('help/show_{id}','2592000'),
		'help:usergrade'=>array('help/usergrade','2592000'),
		'help:top'=>array('help/top','2592000'),
		//'about:_empty'=>array('about/{:action}','2592000') 
	),
);