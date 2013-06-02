<?php

//后台系统函数库
function getStatus($status, $imageShow = true, $id = 0, $field = 'status', $showText = array()) {
    if (empty($showText))
        $showText = array('禁用', '正常');
    switch ($status) {
        case 0 :
            $showImg = '<IMG SRC="__PUBLIC__/Images/icon/locked.png" align="absmiddle" alt="' . $showText[0] . '" style="padding-top:3px;">';
            $url = U(MODULE_NAME . '/resume?id=' . $id . '&field=' . $field);
            $showLink = '<a href="' . $url . '">';
            break;
        case 1 :
        default :
            $showImg = '<IMG SRC="__PUBLIC__/Images/icon/ok.png" align="absmiddle" alt="' . $showText[1] . '" style="padding-top:3px;">';
            $url = U(MODULE_NAME . '/forbid?id=' . $id . '&field=' . $field);
            $showLink = '<a href="' . $url . '">';
    }
    $showStr = ($imageShow === true) ? $showImg : $showText[$status];
    if ($id)
        $showStr = $showLink . $showStr . "</a>";
    return $showStr;
}

function getIcon($action = 'add', $alt = '', $role = 1) {
    $action .= $role ? '' : '_off';
    return '<img src="__PUBLIC__/Images/icon/' . $action . '.png" align="absmiddle" alt="' . $alt . '" style="margin-right:10px;" />';
}

function getMtitle($mtitle = '') {
    if (empty($mtitle))
        return;
    $module = getModule();
    $modid = preg_replace("/.*\{(\d+?)\}.*/is", "\\1", $mtitle);
    if (is_numeric($modid) && isset($module[$modid]['mtitle']))
        return str_replace('{' . $modid . '}', $module[$modid]['mtitle'], $mtitle);
    else
        return $mtitle;
}

function getSort($str, $field = '', $order = '', $sort = 'desc') {
    if ($field == '')
        return $str;
    $sortstr = '<a href="javascript:void(0)" onclick="formSort(\'' . $field . '\',\'' . $order . '\',\'' . $sort . '\')">' . $str;
    if ($field != $order)
        $sortstr .= '<img src="__PUBLIC__/Images/icon/sort.png" align="absmiddle"></a>';
    else
        $sortstr .= '<img src="__PUBLIC__/Images/icon/' . $sort . '.png" align="absmiddle"></a>';
    return $sortstr;
}

//置顶模式
function getTopstatus($topstatus, $toptotime) {
    if ($toptotime >= time() && $topstatus == 1)
        return '分类';
    elseif ($toptotime >= time() && $topstatus == 2)
        return '首页/分类';
    else
        return '--';
}

function getListTemp($temp) {
    $config = require("./config.inc.php");
    $temppath = ROOT_PATH . '/Web/Tpl/Home/' . $config['DEFAULT_THEME'] . '/';
    $list = glob($temppath . '*');
    $select = '<select name="setting[listtemp]">';
    foreach ($list as $v) {
        $fileinfo = pathinfo($v);
        if (!is_dir($v) && $fileinfo['extension'] == 'html' && substr($fileinfo['filename'], 0, 5) == 'List_') {
            $tempname = substr($fileinfo['filename'], 5);
            if ($tempname == $temp)
                $select .= '<option value="' . $tempname . '" selected>' . $tempname . '</option>';
            else
                $select .= '<option value="' . $tempname . '">' . $tempname . '</option>';
        }
    }
    $select .= '</select> ' . tips('对应模版位置：<br>' . $temppath . 'List_列表页模版名.html');
    return $select;
}

//删除目录
function deldir($dir) {
    $dh = opendir($dir);
    while ($file = readdir($dh)) {
        if ($file != "." && $file != "..") {
            $fullpath = $dir . "/" . $file;
            if (!is_dir($fullpath)) {
                unlink($fullpath);
            } else {
                deldir($fullpath);
            }
        }
    }
    closedir($dh);
}

function getSysinfo() {
    $sys_info['os'] = PHP_OS;
    $sys_info['zlib'] = function_exists('gzclose'); //zlib
    $sys_info['safe_mode'] = (boolean) ini_get('safe_mode'); //safe_mode = Off
    $sys_info['safe_mode_gid'] = (boolean) ini_get('safe_mode_gid'); //safe_mode_gid = Off
    $sys_info['socket'] = function_exists('fsockopen');
    $sys_info['web_server'] = $_SERVER['SERVER_SOFTWARE'];
    $sys_info['phpv'] = phpversion();
    $sys_info['fileupload'] = @ini_get('file_uploads') ? ini_get('upload_max_filesize') : 'unknown';
    return $sys_info;
}

function charge_add($uname, $bank = '站内', $money = 0, $fee = 0, $sendtime, $receivetime, $editor = 'system', $status = 0, $note = '', $ordernum = '') {
    if ($uname && $money) {
        $charge = M('charge');
        $data = array();
        $data['uname'] = $uname;
        $data['bank'] = $bank;
        $data['money'] = $money;
        $data['fee'] = $fee;
        $data['amount'] = $money + $fee;
        $data['sendtime'] = $sendtime ? $sendtime : time();
        $data['receivetime'] = $receivetime ? $receivetime : time();
        $data['editor'] = $editor;
        $data['status'] = $status;
        $data['note'] = $note;
        $data['ordernum'] = $ordernum ? $ordernum : date('Ymdhms') . mt_rand(10, 99);
        $data['paystyle'] = 0;
        $charge->add($data);
    }
}

function file_ext($filename) {
    return strtolower(trim(substr(strrchr($filename, '.'), 1)));
}

function is_image($file) {
    return preg_match("/^(jpg|jpeg|gif|png|bmp)$/i", file_ext($file));
}

function is_date($date, $sep = '-') {
    if (empty($date))
        return false;
    if (strlen($date) > 10)
        return false;
    list($year, $month, $day) = explode($sep, $date);
    return checkdate($month, $day, $year);
}

function get_alias($strname) {
    if (empty($strname))
        return;
    $letters = gbk_to_pinyin($strname);
    return strtolower(implode('', $letters));
}

/**
 * gbk转拼音
 * @param $txt
 */
function gbk_to_pinyin($txt) {
    $txt = iconv('UTF-8', 'GBK', $txt);
    $l = strlen($txt);
    $i = 0;
    $pyarr = array();
    $py = array();
    $filename = COMMON_PATH . 'gb-pinyin.table';
    $fp = fopen($filename, 'r');
    while (!feof($fp)) {
        $p = explode("-", fgets($fp, 32));
        $pyarr[intval($p[1])] = trim($p[0]);
    }
    fclose($fp);
    ksort($pyarr);
    while ($i < $l) {
        $tmp = ord($txt[$i]);
        if ($tmp >= 128) {
            $asc = abs($tmp * 256 + ord($txt[$i + 1]) - 65536);
            $i = $i + 1;
        } else
            $asc = $tmp;
        $py[] = asc_to_pinyin($asc, $pyarr);
        $i++;
    }
    return $py;
}

/**
 * Ascii转拼音
 * @param $asc
 * @param $pyarr
 */
function asc_to_pinyin($asc, &$pyarr) {
    if ($asc < 128)
        return chr($asc);
    elseif (isset($pyarr[$asc]))
        return $pyarr[$asc];
    else {
        foreach ($pyarr as $id => $p) {
            if ($id >= $asc)
                return $p;
        }
    }
}

//输出安全的html
function h($text, $tags = null) {
    $text = trim($text);
    //完全过滤注释
    $text = preg_replace('/<!--?.*-->/', '', $text);
    //完全过滤动态代码
    $text = preg_replace('/<\?|\?' . '>/', '', $text);
    //完全过滤js
    $text = preg_replace('/<script?.*\/script>/', '', $text);

    $text = str_replace('[', '&#091;', $text);
    $text = str_replace(']', '&#093;', $text);
    $text = str_replace('|', '&#124;', $text);
    //过滤换行符
    $text = preg_replace('/\r?\n/', '', $text);
    //br
    $text = preg_replace('/<br(\s\/)?' . '>/i', '[br]', $text);
    $text = preg_replace('/(\[br\]\s*){10,}/i', '[br]', $text);
    //过滤危险的属性，如：过滤on事件lang js
    while (preg_match('/(<[^><]+)( lang|on|action|background|codebase|dynsrc|lowsrc)[^><]+/i', $text, $mat)) {
        $text = str_replace($mat[0], $mat[1], $text);
    }
    while (preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i', $text, $mat)) {
        $text = str_replace($mat[0], $mat[1] . $mat[3], $text);
    }
    if (empty($tags)) {
        $tags = 'table|td|th|tr|i|b|u|strong|img|p|br|div|strong|em|ul|ol|li|dl|dd|dt|a';
    }
    //允许的HTML标签
    $text = preg_replace('/<(' . $tags . ')( [^><\[\]]*)>/i', '[\1\2]', $text);
    //过滤多余html
    $text = preg_replace('/<\/?(html|head|meta|link|base|basefont|body|bgsound|title|style|script|form|iframe|frame|frameset|applet|id|ilayer|layer|name|script|style|xml)[^><]*>/i', '', $text);
    //过滤合法的html标签
    while (preg_match('/<([a-z]+)[^><\[\]]*>[^><]*<\/\1>/i', $text, $mat)) {
        $text = str_replace($mat[0], str_replace('>', ']', str_replace('<', '[', $mat[0])), $text);
    }
    //转换引号
    while (preg_match('/(\[[^\[\]]*=\s*)(\"|\')([^\2=\[\]]+)\2([^\[\]]*\])/i', $text, $mat)) {
        $text = str_replace($mat[0], $mat[1] . '|' . $mat[3] . '|' . $mat[4], $text);
    }
    //过滤错误的单个引号
    while (preg_match('/\[[^\[\]]*(\"|\')[^\[\]]*\]/i', $text, $mat)) {
        $text = str_replace($mat[0], str_replace($mat[1], '', $mat[0]), $text);
    }
    //转换其它所有不合法的 < >
    $text = str_replace('<', '&lt;', $text);
    $text = str_replace('>', '&gt;', $text);
    $text = str_replace('"', '&quot;', $text);
    $text = str_replace("'", '&#039;', $text);
    //反转换
    $text = str_replace('[', '<', $text);
    $text = str_replace(']', '>', $text);
    $text = str_replace('|', '"', $text);
    //过滤多余空格
    $text = str_replace('  ', ' ', $text);
    return $text;
}

/**
  +----------------------------------------------------------
 * 字节格式化 把字节数格式为 B K M G T 描述的大小
  +----------------------------------------------------------
 * @return string
  +----------------------------------------------------------
 */
function byte_format($size, $dec = 2) {
    $a = array("B", "KB", "MB", "GB", "TB", "PB");
    $pos = 0;
    while ($size >= 1024) {
        $size /= 1024;
        $pos++;
    }
    return round($size, $dec) . " " . $a[$pos];
}

function showTags($tags) {
    $tags = explode(' ', $tags);
    $str = '';
    foreach ($tags as $key => $val) {
        $tag = trim($val);
        $str .= ' <a href="' . __URL__ . '/tag/name/' . urlencode($tag) . '">' . $tag . '</a>  ';
    }
    return $str;
}

function getTitleSize($count) {
    $size = (ceil($count / 10) + 11) . 'pt';
    return $size;
}

function rcolor() {
    $rand = rand(0, 255);
    return sprintf("%02X", "$rand");
}

function rand_color() {
    return '#' . rcolor() . rcolor() . rcolor();
}

/* 内容处理 */

function getAbstract($content, $id) {
    $content = msubstr($content, 0, 150) . '  <P> <a href="' . __URL__ . '/article/id/' . $id . '"><B>阅读全文… </B></a></P>';
    return $content;
}

/* 处理内容 */

function removeAbstract($content) {
    return str_replace('[separator]', '', $content);
}

/**
 * 对提交的内容进行转换HTML标签操作,包括单引号也转换.
 * @param string $content 提交的内容
 */
function safeHtml($content) {
    return htmlspecialchars(trim($content), ENT_QUOTES);
}

//截取utf8字符串
function utf8Substr($str, $from, $len) {
    return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,' . $from . '}' .
                    '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,' . $len . '}).*#s', '$1', $str);
}