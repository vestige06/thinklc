<?php
class MenuAction extends CommonAction {
	public function index() {
		import("ORG.Util.Tree");
		$tree = new Tree;
		$tree->icon = array('&nbsp;&nbsp;&nbsp;│&nbsp;','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
		$tree->nbsp = '&nbsp;&nbsp;&nbsp;';
		$menu = D('Menu');
		$list = $menu->order('listorder ASC,id ASC')->select();
		foreach($list as $v) {
			$result[$v['id']] = $v;
		}
		$array = array();
		$module = getModule();
		foreach($result as $r) {
			$modid = preg_replace("/.*\{(\d+?)\}.*/is", "\\1", $r['mtitle']);
			if(is_numeric($modid)) $r['mtitle'] = str_replace('{'.$modid.'}', $module[$modid]['mtitle'], $r['mtitle']);
			if($r['level']<4) $mstr = "<a href=\"javascript:doDialog('".U('Menu/add?pid='.$r['id'])."','添加子菜单','640','400')\">".getIcon('add','添加子菜单')."</a>";
			else $mstr = getIcon('add','添加子菜单',0);
			if($r['system']) $mstr .= getIcon('edit','修改菜单',0).getIcon('del','删除子菜单',0);
			else $mstr .= "<a href=\"javascript:doDialog('".U('Menu/edit?id='.$r['id'])."','修改菜单——".$r['mtitle']."','640','400')\">".getIcon('edit','修改菜单')."</a><a href=\"".U('Menu/delete?id='.$r['id'])."\" onclick=\"return confirm('您确定要删除此菜单吗？')\">".getIcon('del','删除菜单')."</a>";
			$r['str_manage'] = $mstr;
			if($r['system'] && $r['display']) $r['str_display'] = getIcon('ok','菜单显示',0);
			else $r['str_display'] = getStatus($r['display'], true, $r['id'], 'display');
			if($r['system'] && $r['status']) $r['str_status'] = getIcon('ok','菜单状态',0);
			else $r['str_status'] = getStatus($r['status'], true, $r['id'], 'status');
			$array[$r['id']] = $r;
		}

		$str = "<tr>
					<td><input name='listorders[\$id]' type='text' size='3' value='\$listorder'></td>
					<td>\$id</td>
					<td>\$spacer\$mtitle</td>
					<td>\$str_display</td>
					<td>\$str_status</td>
					<td>\$str_manage</td>
				</tr>";
		$tree->init($array);
		$menus = $tree->get_tree(0, $str);
		$this->assign("menus",$menus);
		$this->display();
	}
	public function _before_add() {
		$pid = isset($_GET['pid']) ? $_GET['pid'] : 0;
		if($pid) {
			$menu = M("Menu");
			$menu->getById($pid);
			$level = $menu->level+1;
		} else $level = 1;
		if($level>4) closeDialog('doDialog','最多只能添加四级菜单！');
        $this->assign('pid',$pid);
		$this->assign('level',$level);
	}
	public function delete() {
		if($_GET['id']) {
			$id = intval($_GET['id']);
			$menu = D('Menu');
			$ids = array();
			$list = $menu->field('id,pid')->select();
			$childstr = $id.$this->_get_childs($id, $list);
			$menu->where('system=0 AND id IN('.$childstr.')')->delete();
			cache_menu();
			$this->success('删除菜单成功');
		} else $this->error('非法操作');
	}
	public function _after_insert() {
		cache_menu();
	}
	public function _after_update() {
		cache_menu();
	}
	public function _after_forbid() {
		cache_menu();
	}
	public function _after_resume() {
		cache_menu();
	}
	public function _after_order() {
		cache_menu();
	}
}