<?php
class RoleAction extends CommonAction {
	public function setting() {
		if(isset($_POST['dosubmit'])) {
			$access = implode(',',$_POST['menuid']);
			$role = D('Role');
			$result	= $role->where('id='.$_POST['id'])->setField('access',$access);
			if(false !== $result) {
				cache_role($_POST['id']);
				if ($_POST['doclose']) closeDialog('doDialog');
				else $this->success('角色授权成功');
			}else $this->error('角色授权失败');
		} else {
			$role = D('Role');
			$row = $role->find($_GET['id']);
			$this->assign( "row", $row );
			if(!empty($row['access'])) $access = explode(',',$row['access']);
			import("ORG.Util.Tree");
			$menu = new Tree;
			$menu->icon = array('&nbsp;&nbsp;&nbsp;│&nbsp;','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
			$menu->nbsp = '&nbsp;&nbsp;&nbsp;';
			$model = D('Menu');
			$list = $model->order('listorder ASC,id ASC')->select();
			foreach($list as $v) {
				$v['checked'] = (in_array($v['id'],$access)) ? ' checked' : '';
				$v['pid_node'] = ($v['pid'])? ' class="child-of-node-'.$v['pid'].'"' : '';
				$v['mtitle'] = getMtitle($v['mtitle']);
				$result[$v['id']] = $v;
			}
			$str = "<tr id='node-\$id' \$pid_node>
						<td style='padding-left:30px;'>\$spacer<input type='checkbox' name='menuid[]' value='\$id' level='\$level' \$checked onclick='javascript:checknode(this);'> \$mtitle</td>
					</tr>";
			$menu->init($result);
			$menus = $menu->get_tree(0, $str);
			$this->assign("menus",$menus);
			$this->display();
		}
	}
	public function _after_insert($id=0) {
		$this->_cache();
		cache_role($id);
	}
	public function _after_update($id=0) {
		$this->_cache();
		cache_role($id);
	}
	public function _after_delete($id=0) {
		$this->_cache();
		cache_role($id);
	}
	public function _after_forbid($id=0) {
		$this->_cache();
		cache_role($id);
	}
	public function _after_resume($id=0) {
		$this->_cache();
		cache_role($id);
	}
}