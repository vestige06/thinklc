<?php
//缓存函数
function cache_menu() {
	$menu = D('Menu');
	$menu_node = $menu_1 = $menu_2 = $menu_3 = $menu_4 = array();
	$list = $menu->field('id,mname,aname,parameter,mtitle,status,pid,level,action,display,listorder')->order('listorder asc,id asc')->select();
	foreach($list as $v) {
		if($v['display'] && 1==$v['level']) $menu_1[$v['id']] = $v;
		elseif($v['display'] && 2==$v['level']) $menu_2[$v['id']] = $v;
		elseif(3<=$v['level']) {
			if($v['display'] && 3==$v['level']) $menu_3[$v['id']] = $v;
			elseif($v['display'] && 4==$v['level']) $menu_4[$v['id']] = $v;
			$menu_node[$v['id']]['mname'] = strtolower($v['mname']);
			$menu_node[$v['id']]['aname'] = strtolower($v['aname']);
			$menu_node[$v['id']]['action'] = strtolower($v['action']);
		}
	}
	F('config/menu',$menu_node);
	F('config/menu_1',$menu_1);
	F('config/menu_2',$menu_2);
	F('config/menu_3',$menu_3);
	F('config/menu_4',$menu_4);
}
function cache_role($id = 0) {
	$role = D('Role');
	if($id) {
		$data = $role->find($id);
		if($data) {
			$access = explode(',',$data['access']);
			$data['access'] = $access;
			F('config/role_'.$id,$data);
		} else F('config/role_'.$id,NULL);
	} else {
		$arr = array();
		$list = $role->field('id,access')->select();
		foreach($list as $v) {
			if(!in_array($v['id'], $arr)) {
				$arr[] = $v['id'];
				cache_role($v['id']);
			}
		}
	}
}
function cache_setting($item = '') {
	$setting = D("setting");
	if($item == '') {
		$list = $setting->field('item')->where("(item NOT LIKE 'pay_%') AND (item NOT LIKE 'oauth_%')")->group('item')->select();
		foreach($list as $v) {
			cache_setting($v['item']);
		}
		cache_pay();
		cache_oauth();
	} else {
		$data =	$setting->where("item='".$item."'")->getField('item_key,item_value');
		if(strpos($item,'_')) $savefile = $item;
		else $savefile = 'module_'.$item;
		if($data) F('config/'.$savefile,$data);
		else F('config/module_'.$item,NULL);
	}
}
function cache_pay() {
	$model = D("setting");
	$setting = $order = $pay = array();
	$r = $model->where("item LIKE 'pay_%'")->field('item,item_key,item_value')->select();
	if($r) {
		foreach($r as $v) {
			$setting[substr($v['item'], 4)][$v['item_key']] = $v['item_value'];
			if($v['item_key'] == 'order') $order[substr($v['item'], 4)] = empty($v['item_value'])?0:$v['item_value'];
		}
		asort($order);
		foreach($order as $k=>$v) {
			$pay[$k] = $setting[$k];
		}
		F('config/pay',$pay);
	} else F('config/pay',NULL);
}
function cache_oauth() {
	$model = D("setting");
	$setting = $order = $oauth = array();
	$r = $model->where("item LIKE 'oauth_%'")->field('item,item_key,item_value')->select();
	if($r) {
		foreach($r as $v) {
			$setting[substr($v['item'], 6)][$v['item_key']] = $v['item_value'];
			if($v['item_key'] == 'order') $order[substr($v['item'], 6)] = empty($v['item_value'])?0:$v['item_value'];
		}
		asort($order);
		foreach($order as $k=>$v) {
			$oauth[$k] = $setting[$k];
		}
		F('config/oauth',$oauth);
	} else F('config/oauth',NULL);
}
function cache_category($moduleid = 0) {
	$category = D('category');
	if($moduleid == 0) {
		$list = $category->field('moduleid')->group('moduleid')->select();
		foreach($list as $v) {
			cache_category($v['moduleid']);
		}
	} else {
		$data = $category->where("moduleid=".$moduleid)->order('listorder asc,id asc')->select();
		if($data) {
			$categorys = array();
			foreach($data as $r) {
				$categorys[$r['id']] = $r;
			}
			F('config/category_'.$moduleid,$categorys);
		} else F('config/category_'.$moduleid,NULL);
	}
}
function cache_panel($aid = 0) {
	$panel = D('Panel');
	if($aid) {
		$data = $panel->where('aid='.$aid)->order('datetime desc')->select();
		if($data) F('config/panel_'.$aid,$data);
		else F('config/panel_'.$aid,NULL);
	} else {
		$list = $panel->field('aid')->group('aid')->select();
		foreach($list as $v) {
			cache_panel($v['aid']);
		}
	}
}
function cache_etype($item = '') {
	$etype = D('Etype');
	if($item) {
		$data = $etype->where("item='".$item."'")->order('listorder asc,id asc')->select();
		if($data) {
			$types = array();
			foreach($data as $v) {
				$types[$v['id']] = $v;
			}
			F('config/etype_'.$item,$types);
		} else F('config/etype_'.$item,NULL);
	} else {
		$list = $etype->field('item')->group('item')->select();
		foreach($list as $v) {
			cache_etype($v['item']);
		}
	}
}