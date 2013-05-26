<?php
class CommonAction extends Action {
	var $adminid,$adminname,$adminfounder,$adminrid,$adminrname,$adminrole;
    protected function _initialize() {
		if(session('?admin_id')) {
			$this->adminid = session('admin_id');
			$this->adminname = session('admin_name');
			$this->adminrid = session('admin_rid');
			$this->adminfounder = session('admin_founder');
			if($this->adminfounder) $this->adminrname = '创始人';
			elseif($this->adminrid) {
				$this->adminrole=F('config/role_'.$this->adminrid);
				$this->adminrname = $this->adminrole['rname'];
			} else {
				if(session('?logined')) $this->_login_dialog('角色权限错误');
				$this->assign("jumpUrl",U('Public/login'));
				$this->error('角色权限错误');
			}
			$this->_checkrole();
			// 读取系统配置参数
			Vendor('ThinkLC.Global');  //导入公用函数库
			Vendor('ThinkLC.System');
			C(include_once DATA_PATH . 'config/module_0.php');
			$this->assign("jumpUrl",$_SERVER['HTTP_REFERER']);
		} else {
			$this->assign("jumpUrl",U('Public/login'));
            $this->error('您还没登陆');
		}
	}
	protected function _checkrole() {
		//权限检查
		$role_mname = strtolower(MODULE_NAME);
		$role_aname = strtolower(ACTION_NAME);
		$role_action = $_GET['action'] ? $_GET['action'] : '';
		$menuarr = array('mname'=>$role_mname, 'aname'=>$role_aname, 'action'=>$role_action);
		$myrole = 0;
		$errstr = '';
		if($this->adminfounder) {
			//创始人身份，用有所有权限
			$myrole = 1;
		} elseif($this->adminrole['status']) {
			//角色组启用状态时进行下一步验证
			$menu = F('config/menu');
			$menu_3 = F('config/menu_3');
			$menu_4 = F('config/menu_4');
			$menuname = $menu_3 + $menu_4; //合并数组保留键名不变
			$menuid = array_search($menuarr,$menu);
			if(!$menuid) {
				//操作节点不在节点列表里，无需验证权限
				$myrole = 1;
			} elseif($menuname[$menuid]['status']) {
				if(in_array($menuid,$this->adminrole['access'])) {
					//有此操作节点访问权限
					$myrole = 1;
				} else {
					//没有此操作节点访问权限
					$errstr = '你没有操作节点 "'.$menuname[$menuid]['mtitle'].'" 的管理权限';
				}
			} else {
				//操作节点禁止访问
				$errstr = '操作节点 "'.$menuname[$menuid]['mtitle'].'" 禁止访问';
			}
		} else {
			//角色组禁止访问
			$errstr = '角色 "'.$this->adminrole['rname'].'" 已被禁用';
		}
		if(!$myrole) {
			$errstr = empty($errstr) ? '你没有此管理权限' : $errstr;
			if(session('?logined')) $this->_login_dialog($errstr);
			$this->assign("jumpUrl",$_SERVER['HTTP_REFERER']);
			$this->error($errstr);
			exit;
		}
	}

    public function index() {
        //列表过滤器，生成查询Map对象
        $map = $this->_search();
        if (method_exists($this, '_filter')) {
            $this->_filter($map);
        }
        $model = M($this->getActionName());
        if (!empty($model)) {
            $this->_list($model, $map);
        }
        $this->display();
        return;
    }
    public function add() {
        $this->display();
    }
    function edit() {
        $model = M($this->getActionName());
        $id = $_REQUEST[$model->getPk()];
        $vo = $model->find($id);
        $this->assign('vo', $vo);
        $this->display();
    }
    function insert() {
        // 新增数据
        $model = D($this->getActionName());
        if (false === $model->create()) {
            $this->error($model->getError());
        }
        //保存当前数据对象
        if ($result = $model->add()) {
            // AJAX时使用回调接口
            if ($this->isAjax() && method_exists($this, '_after_insert')) {
                $this->_after_insert($result);
            }
            //成功提示
            if ($_POST['doclose']) closeDialog('doDialog');
            else $this->success('添加成功');
        } else {
            //失败提示
            $this->error('添加失败');
        }
    }
    function update() {
        // 更新数据
        $model = D($this->getActionName());
        if (false === $model->create()) {
            $this->error($model->getError());
        }
        if (false !== $model->save()) {
            // AJAX时使用回调接口
            if ($this->isAjax() && method_exists($this, '_after_update')) {
                $this->_after_update($_REQUEST[$model->getPk()]);
            }
            //成功提示
			if ($_POST['doclose']) closeDialog('doDialog');
            else $this->success('更新成功');
        } else {
            //错误提示
            $this->error('更新失败');
        }
    }
    public function delete() {
        //删除指定记录
        $model = M($this->getActionName());
        if (!empty($model)) {
            $pk = $model->getPk();
            $id = $_REQUEST[$pk];
            if (isset($id)) {
				//禁止删除system系统数据
				if(in_array('system',$model->getDbFields())) $condition['system'] = array('eq', 0);
				if(is_array($id)) $condition[$pk] = array('in', implode(',', $id));
				else $condition[$pk] = array('eq', $id);
                if (false !== $model->where($condition)->delete()) {
					// AJAX时使用回调接口
					if ($this->isAjax() && method_exists($this, '_after_delete')) {
						$this->_after_delete($id);
					}
                    $this->success('删除成功');
                } else {
                    $this->error('删除失败');
                }
            } else {
                $this->error('非法操作');
            }
        }
    }
	public function order() {
		// 更新排序
		$name = $this->getActionName();
		$model = D ($name);
		$pk = $model->getPk ();
		foreach($_POST['listorders'] as $id => $listorder) {
			$condition = array($pk => $id);
			$model->where($condition)->setField('listorder',$listorder);
		}
		// AJAX时使用回调接口
		if ($this->isAjax() && method_exists($this, '_after_order')) {
			$this->_after_order();
		}
		$this->success('更新排序成功');
    }
	public function refresh() {
		// 刷新时间
		$name = $this->getActionName();
		$model = D ($name);
		$pk = $model->getPk ();
		$id = $_REQUEST[$pk];
		$field = isset($_REQUEST['field']) ? $_REQUEST['field'] : 'edittime';
		if(is_array($id)) $condition[$pk] = array('in', implode(',', $id));
		else $condition[$pk] = array('eq', $id);
		if (false !== $model->refresh ( $condition, $field )) {
            // AJAX时使用回调接口
            if ($this->isAjax() && method_exists($this, '_after_refresh')) {
                $this->_after_refresh($id);
            }
			$this->success( '刷新时间成功' );
		} else {
			$this->error( '刷新时间失败！' );
		}
    }
	public function forbid() {
        // 状态禁用
		$name=$this->getActionName();
		$model = D ($name);
		$pk = $model->getPk ();
		$id = $_REQUEST[$pk];
		$field = $_REQUEST['field'];
		if(is_array($id)) $condition[$pk] = array('in', implode(',', $id));
		else $condition[$pk] = array('eq', $id);
		if (false !== $model->forbid ( $condition, $field )) {
            // AJAX时使用回调接口
            if ($this->isAjax() && method_exists($this, '_after_forbid')) {
                $this->_after_forbid($id);
            }
			$this->success( '状态禁用成功' );
		} else {
			$this->error( '状态禁用失败！' );
		}
	}
	function resume() {
		//恢复指定记录状态
		$name=$this->getActionName();
		$model = D ($name);
		$pk = $model->getPk ();
		$id = $_REQUEST[$pk];
		$field = $_REQUEST['field'];
		if(is_array($id)) $condition[$pk] = array('in', implode(',', $id));
		else $condition[$pk] = array('eq', $id);
		if (false !== $model->resume ( $condition, $field )) {
            // AJAX时使用回调接口
            if ($this->isAjax() && method_exists($this, '_after_resume')) {
                $this->_after_resume($id);
            }
			$this->success( '状态恢复成功！' );
		} else {
			$this->error( '状态恢复失败！' );
		}
	}
	protected function _get_childs($id, $arr){
		//查找指定ID所有子分类
		$childs = '';
		$child = $this->_get_child($id, $arr);
		if(is_array($child)){
			foreach($child as $sid => $val){
				$childs .= ','.$sid;
				$childs .= $this->_get_childs($sid, $arr);
			}
		}
		return $childs;
	}
	protected function _get_child($id, $arr){
		//查找指定ID下级分类
		$newarr = array();
		if(is_array($arr)){
			foreach($arr as $k => $v){
				if($v['pid'] == $id) $newarr[$v['id']] = $v;
			}
		}
		return $newarr ? $newarr : false;
	}
    protected function _search($name = '') {
        //生成查询条件
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
    protected function _list($model, $map = array(), $sortBy = '', $asc = false) {
        //排序字段 默认为主键名
        if (isset($_REQUEST['_order']) && !empty($_REQUEST ['_order'])) {
            $order = $_REQUEST['_order'];
        } else {
			if(in_array('listorder',$model->getDbFields())) $dorder = 'listorder asc,'.$model->getPk();
			else $dorder = $model->getPk();
            $order = !empty($sortBy) ? $sortBy : $dorder;
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
        import("ORG.Util.Page");
        //创建分页对象
        if (!empty($_REQUEST['listRows'])) {
            $listRows = $_REQUEST['listRows'];
        } else {
            $listRows = '';
        }
        $p = new Page($count, $listRows);
		$p->setConfig('theme','<a class="a1">共%totalRow%条</a> %first% %prePage% %upPage% %linkPage% %downPage% %nextPage% %end%'); 
        //分页查询数据
        $list = $model->where($map)->order($order . ' ' . $sort)->limit($p->firstRow . ',' . $p->listRows)->select();
		//echo $model->getPk().','.$model->getlastsql();
        //分页跳转的时候保证查询条件
        foreach ($map as $key => $val) {
            if (!is_array($val) && substr($key, 0, 1) != '_') {
                $p->parameter .= "$key=" . urlencode($val) . "&";
            }
        }

        //分页显示
        $page = $p->show();
        //列表排序显示
        $sortImg = $sort;                                   //排序图标
        $sortAlt = $sort == 'desc' ? '倒序排列' : '升序排列';    //排序提示
        $sort = $sort == 'desc' ? 0 : 1;                     //排序方式
        //模板赋值显示
        $this->assign('list', $list);
        $this->assign('sort', $sort);
        $this->assign('order', $order);
        $this->assign('sortImg', $sortImg);
        $this->assign('sortType', $sortAlt);
        $this->assign("page", $page);
        return $list;
    }
    protected function getCacheFilename($name = '') {
        $name = $name ? $name : $this->getActionName();
        return DATA_PATH . 'config/' . strtolower($name) . '.php';
    }
    protected function _cache($name = '', $fields = '') {
        $name = $name ? $name : $this->getActionName();
        $Model = M($name);
		if(in_array('listorder',$Model->getDbFields())) $order = 'listorder asc';
		else $order = $Model->getPk().' asc';
        $list = $Model->order($order)->select();
        $data = array();
        foreach ($list as $key => $val) {
            if (empty($fields)) {
                $data[$val[$Model->getPk()]] = $val;
            } else {
                // 获取需要的字段
                if (is_string($fields)) {
                    $fields = explode(',', $fields);
                }
                if (count($fields) == 1) {
                    $data[$val[$Model->getPk()]] = $val[$fields[0]];
                } else {
                    foreach ($fields as $field) {
                        $data[$val[$Model->getPk()]][] = $val[$field];
                    }
                }
            }
        }
		if($data) F('config/'.strtolower($name),$data);
		else F('config/'.strtolower($name),NULL);
    }
    public function delpic() {
		if($_GET['picurl']) unlink(ROOT_PATH.$_GET['picurl']);
    }
    public function upload() {
		$topnum = isset($_REQUEST['topnum']) ? $_REQUEST['topnum'] : 1;
		$this->assign("topnum",$topnum);
		if($_POST['dosubmit']) {
			if (!empty($_FILES)) {
				//如果有文件上传
				$moduleid = $this->moduleid ? $this->moduleid : 0;
				if($this->_upload($topnum, $moduleid)) $this->success('上传成功');
				else $this->error('上传失败');
				if($_POST['doclose']) closeDialog('doDialog','',0);
			} else $this->error('请选择图片');
		} else {
			$this->display('Public:upload');
		}
    }
	protected function _upload($topnum = 0, $moduleid = 0) {
		$topnum = $topnum==5 ? ($topnum-2) : ($topnum-1);
		import("ORG.Net.UploadFile");
		$upload = new UploadFile();
		//设置上传文件大小 
		$upload->maxSize = 3292200; 
		//设置上传文件类型 
		$upload->allowExts = explode(',', 'jpg,gif,png,jpeg'); 
		//设置附件上传目录 
		$upload->savePath = './Uploads/'.date('Ym').'/'; 
		//设置子目录保存
		createDir($upload->savePath); 
		//设置需要生成缩略图，仅对图像文件有效 
		$upload->thumb = true; 
		// 设置引用图片类库包路径 
		$upload->imageClassPath = 'ORG.Util.Image'; 
		//设置需要生成缩略图的文件后缀 
		$upload->thumbPrefix = 't_';  //生产缩略图 
		//设置缩略图最大宽度 
		$upload->thumbMaxWidth = $moduleid ? $this->CFG['cell_width'][$topnum] : '600'; 
		//设置缩略图最大高度 
		$upload->thumbMaxHeight = $moduleid ? $this->CFG['cell_height'] : '400';
		//设置上传文件规则 
		$upload->saveRule = uniqid; 
		//删除原图 
		$upload->thumbRemoveOrigin = true;
		if (!$upload->upload()) {
			//捕获上传异常
			$this->error($upload->getErrorMsg());
			return false;
		} else {
			//取得成功上传的文件信息
			$uploadList = $upload->getUploadFileInfo();
			$remote = date('Ym')."/t_".$uploadList['0']['savename'];
			$picurl = get_picurl($remote);
			$strjs = "<script language=javascript>";
			$strjs .= "var B = window.top.frames['right'];";
			$strjs .= "var picpath = '".$picurl."';";
			$strjs .= "B.delPic();";
			$strjs .= "B.document.getElementById('picurl').value = picpath;";
			$strjs .= "B.document.getElementById('picshow').innerHTML = '<img src='+picpath+'>';";
			$strjs .= "</script>";
			echo $strjs;
			return true;
		}
	}
}