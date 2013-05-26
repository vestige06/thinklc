<?php
if (!defined('IN_ADMIN'))	exit();
$config = require("./config.inc.php");
$array = array(
	'HTML_CACHE_ON' => false, // false关闭静态缓存，true开启
	'URL_MODEL' => '1', // url模式，0为参数模式，1为默认模式。配合下面的分割符号参数，可生成不同url,2为伪静态模式，3为兼容模式，1和2的结合
	'URL_PATHINFO_DEPR' => '/', // url分割符号，比如设置为”/”就为目录模式，不能使用”:” 和”&”符号
	'URL_HTML_SUFFIX' => '', // 伪静态后缀
	'DEFAULT_THEME' => '', // 默认主题
	'DEFAULT_MODULE' => 'Index', // 默认模块名
	'TOKEN_ON' => true,						// 使用令牌
    'TOKEN_NAME' => 'think_html_token',		// 表单令牌名称，TP会在模板form内自动生成一个隐藏域，域名就是这个字符串
    'TOKEN_TYPE' => 'md5',					// 令牌加密方式，换用其他加密也可以
	'TMPL_ACTION_ERROR'=>'./Admin/Tpl/Public/jump.html',
	'TMPL_ACTION_SUCCESS'=>'./Admin/Tpl/Public/jump.html',
	"LOAD_EXT_FILE"=>"cache",
	'TMPL_STRIP_SPACE' => false,
	'URL_CASE_INSENSITIVE'  => true,
);
return array_merge($config,$array);