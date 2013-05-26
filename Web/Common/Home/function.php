<?php
/**
 +----------------------------------------------------------
 * 检查字符串是否是UTF8编码
 +----------------------------------------------------------
 * @param string $string 字符串
 +----------------------------------------------------------
 * @return Boolean
 +----------------------------------------------------------
 */
function is_utf8($string){
	return preg_match('%^(?:
		 [\x09\x0A\x0D\x20-\x7E]            # ASCII
	   | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
	   |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
	   | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
	   |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
	   |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
	   | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
	   |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
   )*$%xs', $string);
}
// 自动转换字符集 支持数组转换
function auto_charset($fContents, $from='gbk', $to='utf-8') {
    $from = strtoupper($from) == 'UTF8' ? 'utf-8' : $from;
    $to = strtoupper($to) == 'UTF8' ? 'utf-8' : $to;
    if (strtoupper($from) === strtoupper($to) || empty($fContents) || (is_scalar($fContents) && !is_string($fContents))) {
        //如果编码相同或者非字符串标量则不转换
        return $fContents;
    }
    if (is_string($fContents)) {
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($fContents, $to, $from);
        } elseif (function_exists('iconv')) {
            return iconv($from, $to, $fContents);
        } else {
            return $fContents;
        }
    } elseif (is_array($fContents)) {
        foreach ($fContents as $key => $val) {
            $_key = auto_charset($key, $from, $to);
            $fContents[$_key] = auto_charset($val, $from, $to);
            if ($key != $_key)
                unset($fContents[$key]);
        }
        return $fContents;
    }
    else {
        return $fContents;
    }
}
/**
 +----------------------------------------------------------
 * 字符串截取，支持中文和其他编码
 +----------------------------------------------------------
 * @static
 * @access public
 +----------------------------------------------------------
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 +----------------------------------------------------------
 * @return string
 +----------------------------------------------------------
 */
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true){
	$str=strip_tags($str);
    if(function_exists("mb_substr"))
        return mb_substr($str, $start, $length, $charset);
    elseif(function_exists('iconv_substr')) {
        return iconv_substr($str,$start,$length,$charset);
    }
    $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
    $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
    $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
    $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
    preg_match_all($re[$charset], $str, $match);
    $slice = join("",array_slice($match[0], $start, $length));
    if($suffix) return $slice."…";
    return $slice;
}
//输出安全的html
function h($text, $tags = null){
	$text	=	trim($text);
	//完全过滤注释
	$text	=	preg_replace('/<!--?.*-->/','',$text);
	//完全过滤动态代码
	$text	=	preg_replace('/<\?|\?'.'>/','',$text);
	//完全过滤js
	$text	=	preg_replace('/<script?.*\/script>/','',$text);

	$text	=	str_replace('[','&#091;',$text);
	$text	=	str_replace(']','&#093;',$text);
	$text	=	str_replace('|','&#124;',$text);
	//过滤换行符
	$text	=	preg_replace('/\r?\n/','',$text);
	//br
	$text	=	preg_replace('/<br(\s\/)?'.'>/i','[br]',$text);
	$text	=	preg_replace('/(\[br\]\s*){10,}/i','[br]',$text);
	//过滤危险的属性，如：过滤on事件lang js
	while(preg_match('/(<[^><]+)( lang|on|action|background|codebase|dynsrc|lowsrc)[^><]+/i',$text,$mat)){
		$text=str_replace($mat[0],$mat[1],$text);
	}
	while(preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i',$text,$mat)){
		$text=str_replace($mat[0],$mat[1].$mat[3],$text);
	}
	if(empty($tags)) {
		$tags = 'table|td|th|tr|i|b|u|strong|img|p|br|div|strong|em|ul|ol|li|dl|dd|dt|a';
	}
	//允许的HTML标签
	$text	=	preg_replace('/<('.$tags.')( [^><\[\]]*)>/i','[\1\2]',$text);
	//过滤多余html
	$text	=	preg_replace('/<\/?(html|head|meta|link|base|basefont|body|bgsound|title|style|script|form|iframe|frame|frameset|applet|id|ilayer|layer|name|script|style|xml)[^><]*>/i','',$text);
	//过滤合法的html标签
	while(preg_match('/<([a-z]+)[^><\[\]]*>[^><]*<\/\1>/i',$text,$mat)){
		$text=str_replace($mat[0],str_replace('>',']',str_replace('<','[',$mat[0])),$text);
	}
	//转换引号
	while(preg_match('/(\[[^\[\]]*=\s*)(\"|\')([^\2=\[\]]+)\2([^\[\]]*\])/i',$text,$mat)){
		$text=str_replace($mat[0],$mat[1].'|'.$mat[3].'|'.$mat[4],$text);
	}
	//过滤错误的单个引号
	while(preg_match('/\[[^\[\]]*(\"|\')[^\[\]]*\]/i',$text,$mat)){
		$text=str_replace($mat[0],str_replace($mat[1],'',$mat[0]),$text);
	}
	//转换其它所有不合法的 < >
	$text	=	str_replace('<','&lt;',$text);
	$text	=	str_replace('>','&gt;',$text);
	$text	=	str_replace('"','&quot;',$text);
	 //反转换
	$text	=	str_replace('[','<',$text);
	$text	=	str_replace(']','>',$text);
	$text	=	str_replace('|','"',$text);
	//过滤多余空格
	$text	=	str_replace('  ',' ',$text);
	return $text;
}
//格式化置顶信息排序
function sortTop($toparr){
	$toptemp = $toplist = array();
	if(!is_array($toparr) || count($toparr)==0) return $toplist;
	foreach($toparr as $v) {
		if($v['topnum']==5) $toplist[] = $v;
		else $toptemp[$v['topnum']][] = $v;
	}
	if(!empty($toptemp[3]) && count($toptemp[3])>0) $top3 = count($toptemp[3]);
	else $top3 = 0;
	if(!empty($toptemp[2]) && count($toptemp[2])>0) $top2 = count($toptemp[2]);
	else $top2 = 0;
	if(!empty($toptemp[1]) && count($toptemp[1])>0) $top1 = count($toptemp[1]);
	else $top1 = 0;
	if($top3>0) {
		foreach($toptemp[3] as $k => $v) {
			$toplist[] = $v;
			$top3--;
			if($top3>0 && $top2>0) {
				$toplist[] = $toptemp[2][0];
				unset($toptemp[2][0]);
				array_values($toptemp[2]);
				$top2--;
			} elseif($top3>0 && $top2<=0) {
				$toplist[] = array('id'=>'0','topnum'=>'2');
			}
		}
	}
	if($top2>0) {
		foreach($toptemp[2] as $k => $v) {
			$toplist[] = $v;
			$top2--;
			if($k%2 == 1) {
				if($top2>0 && $top1>0) {
					$toplist[] = $toptemp[1][0];
					unset($toptemp[1][0]);
					array_values($toptemp[1]);
					$top1--;
				} elseif($top2>0 && $top1<=0) {
					$toplist[] = array('id'=>'0','topnum'=>'1');
				}
			}
		}
	}
	if($top1>0) {
		foreach($toptemp[1] as $k => $v) {
			$toplist[]=$v;
		}
	}
	return $toplist;
}
function getExtend($contact, $CFG, $add = 0) {
	$contact = unserialize($contact);
	$show = $show_add = $show_online = '';
	if(!empty($contact['contacter'])) $show_add .= '联&nbsp;系&nbsp;&nbsp;人：'.$contact['contacter'];
	if(!empty($contact['tel'])) $show_add .= '<br>联系电话：'.$contact['tel'];
	if($add) {
		if($CFG['contact_add']) return '<br>'.$show_add;
		else return;
	} else {
		if($CFG['contact_qq'] && !empty($contact['qq'])) $show_online .= '<a href="http://wpa.qq.com/msgrd?v=3&uin='.$contact['qq'].'&site=qq&menu=yes" target="_blank" rel="nofollow"><img src="http://wpa.qq.com/pa?p=1:'.$contact['qq'].':4" title="点击QQ交谈/留言" align="absmiddle" onerror="this.src=\'__PUBLIC__/Images/icon/qq-off.gif\'"/></a>&nbsp;';
		if($CFG['contact_ali'] && !empty($contact['ali'])) $show_online .= '<a href="http://amos.im.alisoft.com/msg.aw?v=2&uid='.$contact['ali'].'&site=cnalichn&s=6" target="_blank" rel="nofollow"><img src="http://amos.im.alisoft.com/online.aw?v=2&uid='.$contact['ali'].'&site=cnalichn&s=6" title="点击旺旺交谈/留言" align="absmiddle" onerror="this.src=\'__PUBLIC__/Images/icon/ali-off.gif\'" onload="if(this.width>20)this.src=\'__PUBLIC__/Images/icon/ali-off.gif\'"/></a>&nbsp;';
		if($CFG['contact_msn'] && !empty($contact['msn'])) $show_online .= '<a href="msnim:chat?contact='.$contact['msn'].'" rel="nofollow"><img src="__PUBLIC__/Images/icon/msn.gif" width="16" height="16" title="点击MSN交谈/留言" align="absmiddle"/></a>';
		$show = '<a href="javascript:" onclick="window.top.art.dialog({width:\'240\',title:\'联系方式\',content:\''.$show_add.'<br>在线交流：'.str_replace("'","\\'",str_replace('"',"'",$show_online)).'\',lock:true}, function(){this.close();$(obj).focus();})"><img src="__PUBLIC__/Images/icon/contact.gif" width="16" height="16" title="查看联系方式" align="absmiddle"/></a>&nbsp;'.$show_online;
		return $show;
	}
}
//置顶信息有效期
function getExpired($time){
	 $a = round(($time-time())/86400);
	 if($a>0) return '有效期还有'.$a.'天';
	 else return '今天过期';
}
function getByAlias($alias,$arr){
	$id = 0;
	foreach($arr as $v) {
		if($v['alias']==$alias) {
			$id = $v['id'];
			break;
		}
	}
	return $id;
}