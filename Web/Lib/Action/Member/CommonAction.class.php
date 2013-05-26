<?php
// 会员中心基础模块
class CommonAction extends BaseAction {
	var $uid,$uname,$ugid;
    protected function _initialize() {
		parent::_initialize();
		$this->checkModuleStatus(1);
		// 用户权限检查
		if(session('?uid')) {
			$this->uid = session('uid');
			$this->uname = session('uname');
			$this->ugid = session('ugid');

			$this->assign("uname",$this->uname);
			$this->assign("modules",getModule());
			if($this->MCFG['uc_enable'] && $this->MCFG['uc_verify'] && !session('ucverify')) {
				// 如果开启UC整合，并且需要UC验证，并且当前用户未验证情况下，跳转到验证页面
				$this->assign("jumpUrl",U('Public/ucverify'));
				$this->error('<br>您的账户还没有进行UC安全验证，无法同步登录到其他UC应用<br>请进行UC安全验证');
			}
		} else {
			$this->assign("jumpUrl",U('Public/login'));
            $this->error('您还没有登陆');
		}
	}
	protected function checkModuleStatus($moduleid = 0){
		if(!$moduleid) $this->error('模块不存在，请与管理员联系');
		$module = F('config/module');
		if(!$module[$moduleid]['status']) $this->error('模块已关闭，请与管理员联系');
	}
	public function index() {
        $model = M($this->getActionName());
        if (!empty($model)) {
			if(in_array('uid',$model->getDbFields())) $map['_string'] = "uid=".$this->uid;
			elseif(in_array('uname',$model->getDbFields())) $map['_string'] = "uname='".$this->uname."'";
			if (method_exists($this, '_filter')) {
				$this->_filter($map);
			}
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
		if(empty($id )) $this->error('参数错误');
        $row = $model->find($id);
		if(!$row) $this->error('信息不存在');
		//检查是否是当前用户信息
		if((isset($row['uid']) && $row['uid']!=$this->uid) || (isset($row['uname']) && $row['uname']!=$this->uname)) $this->error('权限错误，请勿非法操作');
        $this->assign('row', $row);
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
            // 返回值给回调接口
            if (method_exists($this, '_call_after_insert')) {
                $this->_call_after_insert($result);
            }
            //成功提示
            $this->success('添加成功');
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
            //成功提示
			$this->success('更新成功');
        } else {
            //错误提示
            $this->error('更新失败');
        }
    }
	public function refresh() {
		// 刷新时间
		$name = $this->getActionName();
		$model = D ($name);
		$pk = $model->getPk ();
		$id = $_REQUEST[$pk];
		$row = $model->find($id);
		if(!$row) $this->error('信息不存在');
		//检查是否是当前用户信息
		if((isset($row['uid']) && $row['uid']!=$this->uid) || (isset($row['uname']) && $row['uname']!=$this->uname)) $this->error('权限错误，请勿非法操作');
		$field = isset($_REQUEST['field']) ? $_REQUEST['field'] : 'edittime';
		$condition[$pk] = array('eq', $id);
		if (false !== $model->refresh ( $condition, $field )) {
			$this->success( '刷新时间成功' );
		} else {
			$this->error( '刷新时间失败！' );
		}
    }
    public function delete() {
        //删除指定记录
        $model = M($this->getActionName());
        if (!empty($model)) {
            $pk = $model->getPk();
            $id = $_REQUEST[$pk];
            if (isset($id)) {
				$row = $model->find($id);
				if(!$row) $this->error('信息不存在');
				//检查是否是当前用户信息
				if((isset($row['uid']) && $row['uid']!=$this->uid) || (isset($row['uname']) && $row['uname']!=$this->uname)) $this->error('权限错误，请勿非法操作');
				$condition[$pk] = array('eq', $id);
                if (false !== $model->where($condition)->delete()) {
					// 返回值给回调接口
					if (method_exists($this, '_call_after_delete')) {
						$this->_call_after_delete($row);
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
    /**
      +----------------------------------------------------------
     * @access protected
      +----------------------------------------------------------
     * @param Model $model 数据对象
     * @param HashMap $map 过滤条件
     * @param string $sortBy 排序
     * @param boolean $asc 是否正序
      +----------------------------------------------------------
     * @return void
      +----------------------------------------------------------
     * @throws ThinkExecption
      +----------------------------------------------------------
     */
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
            $page = $p->show();
            $list = $model->order($order . ' ' . $sort)->where($map)->page(empty($_GET['p']) ? 1 : $_GET['p'] . ',20')->select();
            $this->assign('page', $page);
            $this->assign('list', $list);
			$this->assign('sort', $sort);
			return $list;
        }
		return;
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
			$strjs .= "window.top.delPic();";
			$strjs .= "var picpath = '".$picurl."';";
			$strjs .= "window.top.document.getElementById('picurl').value = picpath;";
			$strjs .= "window.top.document.getElementById('picshow').innerHTML = '<img src='+picpath+'>';";
			$strjs .= "</script>";
			echo $strjs;
			return true;
		}
	}
}