<?php
function setLimit($uid = 0, $moduleid = 0, $item = '', $val = '', $lasttime = 0) {
	if($item) {
		$limit = M('Limit');
		$limit->where("uid=".$uid." AND moduleid=".$moduleid." AND item='".$item."'")->delete();
		$data = array();
		$data['uid'] = $uid;
		$data['moduleid'] = $moduleid;
		$data['item'] = $item;
		$data['val'] = $val;
		$data['lasttime'] = $lasttime ? $lasttime : time();
		$limit->add($data);
	}
}
function setLimitInc($uid = 0, $moduleid = 0, $item = '', $val = 1) {
	if($item) {
		$limit = M('Limit');
		$limit->where("uid=".$uid." AND moduleid=".$moduleid." AND item='".$item."'")->setInc('val',$val);
	}
}
function getLimit($uid = 0, $moduleid = 0, $item = '') {
	if($item) {
		$limit = M('Limit');
		$result = $limit->where("uid=".$uid." AND moduleid=".$moduleid." AND item='".$item."'")->find();
		return $result;
	} else return false;
}
//提取会员组相关权限
function get_g_rule($gid,$mname,$rname){
	$group = F('config/group_'.$gid);
	return $group[$mname.'_'.$rname];
}
//提取置顶信息相关权限
function get_t_rule($mid,$rname,$topstatus,$toptotime){
	if($topstatus && $toptotime>=time()) {
		$setting = F('config/module_'.$mid);
		return $setting['top_'.$rname];
	} else return 0;
}
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