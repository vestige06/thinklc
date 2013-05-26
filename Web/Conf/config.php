<?php
$config = require("./config.inc.php");
$array = array(
	'APP_GROUP_LIST' => 'Member,Home,Wap',
	'DEFAULT_GROUP' => 'Home',
	'TMPL_FILE_DEPR'=>'_',
	'TMPL_STRIP_SPACE' => true,
	'URL_CASE_INSENSITIVE'  => true,
	'TMPL_ACTION_ERROR' => './Web/Tpl/jump.html',
	'TMPL_ACTION_SUCCESS' => './Web/Tpl/jump.html',
	'URL_ROUTER_ON' => true,
	'URL_ROUTE_RULES' => array(
		'member$' => 'Member/Index/index',
	),
);
return array_merge($config,$array);