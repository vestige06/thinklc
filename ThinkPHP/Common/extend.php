<?php
/**
 +------------------------------------------------------------------------------
 * ThinkPHP 项目共用函数库
 +------------------------------------------------------------------------------
 */

function setLimitInc($uid = 0, $moduleid = 0, $item = '', $val = 1) {
	if($item) {
		$limit = M('Limit');
		$limit->where("uid=".$uid." AND moduleid=".$moduleid." AND item='".$item."'")->setInc('val',$val);
	}
}