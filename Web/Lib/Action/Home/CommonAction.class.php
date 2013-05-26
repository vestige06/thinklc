<?php
class CommonAction extends Action {
	/**
      +----------------------------------------------------------
     * 项目初始化
      +----------------------------------------------------------
     */
    protected function _initialize() {
        // 获取导航列表
		$navigation = array();
		$list = F('config/module');
		foreach($list as $v) {
			if($v['status'] && $v['display'] && $v['mname']!=C('DEFAULT_MODULE')) {
				$tmp['name'] = $v['mtitle'];
				if($v['islink']) $tmp['url'] = $v['linkurl'];
				elseif($v['mname']=='Webpage') $tmp['url'] = C('URL_MODEL') ? U('/About') : '/index.php/About';
				else $tmp['url'] = C('URL_MODEL') ? U('/'.$v['mname']) : '/index.php/'.$v['mname'];
				$navigation[] = $tmp;
			}
		}
		Vendor('ThinkLC.Global');  //导入公用函数库
        $this->assign('navigation', $navigation);
		// 初始化网站基本设置
		C(include_once DATA_PATH . 'config/module_0.php');
		if(!C('search_type')) {
			$module = F('config/module');
			foreach($module as $v) {
				if($v['id']>4 && $v['status'] && !$v['islink']) $modules[] = $v;
			}
			$this->assign('modules', $modules);
		}
    }
	protected function checkModuleStatus($moduleid = 0){
		if(!$moduleid) $this->error('模块不存在，请与管理员联系');
		$module = F('config/module');
		if(!$module[$moduleid]['status']) $this->error('模块已关闭，请与管理员联系');
	}
    protected function _search($name = '') {
        // 生成查询条件
        if (empty($name)) {
            $name = $this->getActionName();
        }
        $model = M($name);
        $map = array();
        foreach ($model->getDbFields() as $key => $val) {
            if (substr($key, 0, 1) == '_')
                continue;
            if (isset($_REQUEST[$val]) && $_REQUEST[$val] != '') {
				if($_REQUEST[$val.'_like']) $map[$val] = array('like','%'.$_REQUEST[$val].'%');  //设置字段模糊查询
				else $map[$val] = $_REQUEST[$val];
            }
        }
        return $map;
    }
    protected function _list($model, $map = array(), $listRows = '', $sortBy = '', $asc = false) {
        //排序字段 默认为主键名
        if (isset($_REQUEST['_order'])) {
            $order = $_REQUEST['_order'];
        } else {
            $order = !empty($sortBy) ? $sortBy : $model->getPk();
        }
        //排序方式默认按照倒序排列
        //接受 sost参数 0 表示倒序 非0都 表示正序
        if (isset($_REQUEST['_sort'])) {
            $sort = $_REQUEST['_sort'] ? 'asc' : 'desc';
        } else {
            $sort = $asc ? 'asc' : 'desc';
        }
        //取得满足条件的记录数
        $count = $model->where($map)->count($model->getPk());
        if ($count > 0) {
            //分页显示
            import("ORG.Util.Page");
            $p = new Page($count, $listRows);
			$list = $model->where($map)->order($order . ' ' . $sort)->limit($p->firstRow . ',' . $p->listRows)->select();
			//分页跳转的时候保证查询条件
			foreach ($map as $key => $val) {
				if (!is_array($val)) {
					$p->parameter .= "$key=" . urlencode($val) . "&";
				}
			}
            $page = $p->show();
            $this->assign('page', $page);
            $this->assign('list', $list);
			$this->assign('sort', $sort);
			return $list;
        }
		return;
    }
    protected function _mlist($model, $map = array(), $sortBy = '', $listRows = '', $preurl = '') {
        //排序字段 默认为主键名
        $order = !empty($sortBy) ? $sortBy : $model->getPk().' desc';
        //取得满足条件的记录数
		if(isset($model->viewFields)) {
			// 视图模型
			$action = $this->getActionName().'.';
			$count = $model->where($map)->count($action.$model->getPk());
		}
		else $count = $model->where($map)->count($model->getPk());
        if ($count > 0) {
			if($preurl!='') {
				// 需要分页显示
				import("ORG.Util.Pages");
				$p = new Page($count, $listRows);
				$list = $model->where($map)->order($order)->limit($p->firstRow . ',' . $p->listRows)->select();
				$p->setConfig('preurl',$preurl);
				$p->setConfig('header','条');
				$p->setConfig('first','首页'); 
				$p->setConfig('last','末页');
				$p->setConfig('theme','第%nowPage%/%totalPage%页&nbsp;&nbsp;'.$listRows.'条/页&nbsp;&nbsp;共%totalRow%条&nbsp;&nbsp;%first%&nbsp;&nbsp;%prePage%&nbsp;&nbsp;%linkPage%&nbsp;&nbsp;%nextPage%&nbsp;&nbsp;%end%');
				$page = $p->show();
				$this->assign('page', $page);
				$this->assign('list', $list);
				return $list;
			} else {
				// 不需要分页
				$list = $model->where($map)->order($order)->select();
				return $list;
			}
        }
		return;
    }
}