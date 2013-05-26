<?php
return array(
'DB_TYPE' => 'mysql', // 数据库类型
'DB_HOST' => 'localhost', // 数据库服务器
'DB_PORT' => '3306', // 数据库端口
'DB_NAME' => 'thinklc21', // 数据库名
'DB_USER' => 'root', // 数据库用户名
'DB_PWD' => '123456', // 数据库密码
'DB_PREFIX' => 'thinklc_', // 数据表前缀
'HTML_CACHE_ON' => false, // false关闭静态缓存，true开启
'HTML_CACHE_TIME' => 3600, // 静态缓存有效期，单位秒
'URL_MODEL' => '1', // url模式，0为参数模式，1为默认模式。配合下面的分割符号参数，可生成不同url,2为伪静态模式，3为兼容模式，1和2的结合
'URL_PATHINFO_DEPR' => '/', // url分割符号，比如设置为”/”就为目录模式，不能使用”:” 和”&”符号
'URL_HTML_SUFFIX' => '.html', // 伪静态后缀
'DEFAULT_THEME' => 'default', // 默认主题
'DEFAULT_MODULE' => 'Info', // 默认模块名
);