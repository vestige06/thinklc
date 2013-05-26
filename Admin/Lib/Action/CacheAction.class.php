<?php
//缓存更新
class CacheAction extends CommonAction {
	protected function _initialize() {
		parent::_initialize();
		$menu = array(
			'config' => '配置缓存', 
			'compiled' => '编译缓存', 
			'templates' => '模版缓存', 
			'html' => '网页缓存', 
			'index' => '刷新静态首页', 
			'onekey' => '一键更新'
		);
		$description = array(
			'config' => '默认情况下在更新系统配置时，会自动更新相应配置缓存，不需要手工更新。如果修改了文件或者配置后发现内容没有及时更新，可使用本功能更新缓存，系统将自动重新生成配置缓存。', 
			'compiled' => '编译缓存以及数据库字段缓存仅在系统首次运行时自动生成，一般不需要手工更新。如果修改了系统函数文件、系统配置文件或更改了数据库，则必须重新生成编译缓存。', 
			'templates' => '模版缓存一般在模版更改后会自动更新，但被模版引用的模版文件更改后无法自动更新，必须手动更新模版缓存。', 
			'html' => '网页缓存是系统运行时生成的前台静态网页缓存，会根据系统设置的缓存时间自动更新，如果更改了系统文件，网页缓存还没有自动更新，可以手动清理网页缓存。', 
			'index' => '网站首页静态文件，会在前台用户添加、编辑、置顶信息时自动生成，后台管理员操作不会自动生成静态首页，后台用户可在完成全部操作后手动刷新静态首页。', 
			'onekey' => '一键更新系统所有缓存'
		);
		$this->assign('menu', $menu);
		$this->assign('description', $description);
		$this->assign('action', ACTION_NAME);
	}
	public function config() {
		if(isset($_GET['confirm'])) {
			$this->_cache('Role'); //后台角色缓存
			$this->_cache('Area'); //地区缓存
			$this->_cache('Webpage'); //单页缓存
			$this->_cache('Module'); //系统模块缓存
			$this->_cache('Group'); //用户组缓存
			$this->_cache('Link'); //友情连接缓存
			cache_menu(); //后台菜单缓存
			cache_panel(); //后台快捷方式缓存
			cache_role(); //后台角色配置缓存
			cache_setting(); //网站配置缓存
			cache_category(); //分类缓存
			cache_etype(); //扩展分类缓存
			if(ACTION_NAME=='onekey') return;
			else $this->success('配置缓存更新成功');
		} else {
			$this->display('cache');
		}
	}
	public function compiled() {
		if(isset($_GET['confirm'])) {
			if(ACTION_NAME=='onekey' || isset($_GET['db'])) deldir(DATA_PATH.'_fields/');
			if(ACTION_NAME=='onekey' || isset($_GET['admin'])) unlink(RUNTIME_PATH.'~runtime.php');
			if(ACTION_NAME=='onekey' || isset($_GET['web'])) unlink(ROOT_PATH.'/Data/Runtime/Web/~runtime.php');
			if(ACTION_NAME=='onekey') return;
			else $this->success('编译缓存清理完成');
		} else {
			$this->display('cache');
		}
	}
	public function templates() {
		if(isset($_GET['confirm'])) {
			if(ACTION_NAME=='onekey' || isset($_GET['admin'])) deldir(CACHE_PATH);
			if(ACTION_NAME=='onekey' || isset($_GET['web'])) deldir(ROOT_PATH.'/Data/Runtime/Web/Cache/');
			if(ACTION_NAME=='onekey') return;
			else $this->success('模版缓存清理完成');
		} else {
			$this->display('cache');
		}
	}
	public function html() {
		if(isset($_GET['confirm'])) {
			deldir(HTML_PATH);
			if(ACTION_NAME=='onekey') return;
			else $this->success('网页静态缓存清理完成');
		} else {
			$this->display('cache');
		}
	}
	public function index() {
		if(isset($_GET['confirm'])) {
			$r = buildIndex();
			$result = intval($r);
			if(ACTION_NAME=='onekey') return;
			elseif($result) $this->success('生成静态首页成功');
			else $this->error('生成静态首页失败');
		} else {
			$this->display('cache');
		}
	}
	public function onekey() {
		if(isset($_GET['confirm'])) {
			if(isset($_GET['compiled'])) $this->compiled();
			if(isset($_GET['config'])) $this->config();
			if(isset($_GET['templates'])) $this->templates();
			if(isset($_GET['html'])) $this->html();
			if(isset($_GET['index'])) $this->index();
			$this->success('一键更新缓存成功');
		} else {
			$this->display('cache');
		}
	}
}