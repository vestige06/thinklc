<?php 
/**
 * @package      	ThinkLC 2.0
 * @author          土豆 QQ:25897 <25897@qq.com>
 * @copyright     	Copyright (c) 2012  (http://www.life0573.com)
 */

if (!file_exists('./config.inc.php')) header('Location: /install/');
define('ROOT_PATH', str_replace("\\", '/', dirname(__FILE__)));
define('DATA_PATH', ROOT_PATH.'/Data/');
define('RUNTIME_PATH', ROOT_PATH.'/Data/Runtime/Web/');
define('HTML_PATH', ROOT_PATH.'/Data/Html/');
define('IN_WEB', 1);
define('APP_NAME', 'Web');
define('APP_PATH', './Web/');
require("./ThinkPHP/ThinkPHP.php");