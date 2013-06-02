<?php

// 信息模块
class ArticleAction extends CommonAction {

    protected $moduleid = 7;
    protected $model;
    protected $CATEGORY;
    protected $AREA;
    protected $CFG;

    public function _initialize() {
        $this->checkModuleStatus($this->moduleid);
        parent::_initialize();
        $this->model = M('Article');
        $this->CFG = F('config/module_' . $this->moduleid);
        $this->CATEGORY = F('config/category_' . $this->moduleid);
//        dump($this->CATEGORY);exit;
//        $this->AREA = F('config/area');
//        dump($this->AREA);exit;
        $CATEGORY_LIST = $AREA_LIST = array();
        foreach ($this->CATEGORY as $v) {
            if ($this->CFG['cate_alias']) {
                $v['name'] = $v['alias'];
                $v['url'] = U('Article/cate?id=' . $v['alias']);
            } else {
                $v['name'] = $v['id'];
                $v['url'] = U('Article/cate?id=' . $v['id']);
            }
            $CATEGORY_LIST[$v['id']] = $v;
        }
//        foreach ($this->AREA as $v) {
//            if ($this->CFG['area_alias']) {
//                $v['name'] = $v['alias'];
//                $v['url'] = U('Article/area?id=' . $v['alias']);
//            } else {
//                $v['name'] = $v['id'];
//                $v['url'] = U('Article/area?id=' . $v['id']);
//            }
//            $AREA_LIST[$v['id']] = $v;
//        }
        
        $this->assign("CATEGORY", $CATEGORY_LIST);
//        $this->assign("AREA", $AREA_LIST);
        $this->assign("CFG", $this->CFG);
//        dump($this->CFG);exit;//打印该模块下面的配置数据
//        dump($this->$CATEGORY);
        $this->assign("jumpUrl", $_SERVER['HTTP_REFERER']);
    }

    public function _empty($name) {
        $this->assign("jumpUrl", U('Article/index'));
        $this->error('非法操作，请与管理员联系');
    }

    public function buildindex() {
        // 生成当前模块首页静态文件
        $indexfile = ROOT_PATH . '/index.html'; //静态首页文件
        clearstatcache(true, $indexfile); //清除文件状态缓存
        $filetime = filemtime($indexfile); //获取文件最后修改时间
        C('HTML_CACHE_ON', false); //临时关闭静态缓存
        $this->index(1); //调用index操作给模版变量赋值
        $this->buildHtml('index', './', 'List:' . $this->CFG['listtemp']); //生成静态页
        clearstatcache(true, $indexfile); //清除文件状态缓存
        $newfiletime = filemtime($indexfile); //获取文件最后修改时间
        if ($newfiletime > $filetime)
            exit('1'); //生成成功
        else
            exit('0'); //生成失败
    }

    public function index($build = 0) {
        // $build 生成首页参数，不需要显示内容，默认为显示
//        $thistime = time();
//        $map['_string'] = '(topstatus!=2 OR toptotime<' . $thistime . ') AND status=1 AND topnum=1 AND (expired=0 OR expired>=' . $thistime . ')';
//        $sortBy = 'edittime desc';
//        $this->_mlist($this->model, $map, $sortBy, $this->CFG['pagenum'], U('Article/index?p=%Page%'));

//        $havelink = $havetop = 0;
//        if (!isset($_GET['p']) || $_GET['p'] == 1) {
//            $map['_string'] = 'Article.topstatus=2 AND Article.toptotime>=' . $thistime;
//            $sortBy = 'edittime desc';
//            $topmodel = D('ArticleTop');
//            $toplist = $this->_mlist($topmodel, $map, $sortBy);
//            if (!empty($toplist) && count($toplist) > 0) {
//                $toplist = sortTop($toplist);
//                $this->assign("toplist", $toplist);
//                $havetop = 1;
//            }
//            $modules = getModule();
//            if (C('DEFAULT_MODULE') == $modules[$this->moduleid]['mname']) {
//                $havelink = 1;
//            }
//        }
//        dump($this->CFG['title']);exit;
//        dump($this->CFG['keywords']);exit;
//        dump($this->CFG['listtemp']);exit;
        $ArticleList = $this->model->where('status = 1')->select();
        $this->assign('list', $ArticleList);
        $this->assign("site_title", $this->CFG['title']);
        $this->assign("site_keywords", $this->CFG['keywords']);
        $this->assign("site_description", $this->CFG['description']);
//        $this->assign("havetop", $havetop);
//        $this->assign("havelink", $havelink);
        if (!$build)
            $this->display('List:' . $this->CFG['listtemp']);
    }

    public function cate() {
        if (isset($_GET['id'])) {
            if ($this->CFG['cate_alias'])
                $catid = getByAlias($_GET['id'], $this->CATEGORY);
            else
                $catid = $_GET['id'];
            if (!$catid)
                $this->error('分类不存在');
            $caturl = U('Article/cate?id=' . $_GET['id'] . '&p=%Page%');
        } else
            $this->error('参数错误');

        $thistime = time();
        $map['_string'] = 'catid=' . $catid . ' AND (topstatus=0 OR toptotime<' . $thistime . ') AND status=1 AND topnum=1 AND (expired=0 OR expired>=' . $thistime . ')';
        $sortBy = 'edittime desc';
        $this->_mlist($this->model, $map, $sortBy, $this->CFG['pagenum'], $caturl);

        $havetop = 0;
        if (!isset($_GET['p']) || $_GET['p'] == 1) {
            $map['_string'] = 'catid=' . $catid . ' AND Article.topstatus>0 AND Article.toptotime>=' . $thistime;
            $sortBy = 'edittime desc';
            $topmodel = D('ArticleTop');
            $toplist = $this->_mlist($topmodel, $map, $sortBy);
            if (!empty($toplist) && count($toplist) > 0) {
                $toplist = sortTop($toplist);
                $this->assign("toplist", $toplist);
                $havetop = 1;
            }
        }

        if ($this->CATEGORY[$catid]['seo_title']) {
            $this->assign("site_title", $this->CATEGORY[$catid]['seo_title']);
        } else {
            $this->assign("site_title", C('site_area') . $this->CATEGORY[$catid]['catname'] . ' - ' . $this->CFG['title']);
        }
        if ($this->CATEGORY[$catid]['seo_keywords']) {
            $this->assign("site_keywords", $this->CATEGORY[$catid]['seo_keywords']);
        } else {
            $this->assign("site_keywords", C('site_area') . $this->CATEGORY[$catid]['catname'] . ' - ' . $this->CFG['keywords']);
        }
        if ($this->CATEGORY[$catid]['seo_description']) {
            $this->assign("site_description", $this->CATEGORY[$catid]['seo_description']);
        } else {
            $this->assign("site_description", C('site_area') . $this->CATEGORY[$catid]['catname'] . ' - ' . $this->CFG['description']);
        }
        $this->assign("havetop", $havetop);
        $this->display('List:' . $this->CFG['listtemp']);
    }

    public function area() {
        if (isset($_GET['id'])) {
            if ($this->CFG['area_alias'])
                $areaId = getByAlias($_GET['id'], $this->AREA);
            else
                $areaId = $_GET['id'];
            if (!$areaId)
                $this->error('地区不存在');
            $areaurl = U('Article/area?id=' . $_GET['id'] . '&p=%Page%');
        } else
            $this->error('参数错误');

        $thistime = time();
        $map['_string'] = 'areaid=' . $areaId . ' AND (topstatus=0 OR toptotime<' . $thistime . ') AND status=1 AND topnum=1 AND (expired=0 OR expired>=' . $thistime . ')';
        $sortBy = 'edittime desc';
        $this->_mlist($this->model, $map, $sortBy, $this->CFG['pagenum'], $areaurl);

        $havetop = 0;
        if (!isset($_GET['p']) || $_GET['p'] == 1) {
            $map['_string'] = 'areaid=' . $areaId . ' AND Article.topstatus>0 AND Article.toptotime>=' . $thistime;
            $sortBy = 'edittime desc';
            $topmodel = D('ArticleTop');
            $toplist = $this->_mlist($topmodel, $map, $sortBy);
            if (!empty($toplist) && count($toplist) > 0) {
                $toplist = sortTop($toplist);
                $this->assign("toplist", $toplist);
                $havetop = 1;
            }
        }

        $this->assign("site_title", str_replace(C('site_area'), $this->AREA[$areaId]['aname'], $this->CFG['title']));
        $this->assign("site_keywords", str_replace(C('site_area'), $this->AREA[$areaId]['aname'], $this->CFG['keywords']));
        $this->assign("site_description", str_replace(C('site_area'), $this->AREA[$areaId]['aname'], $this->CFG['description']));
        $this->assign("havetop", $havetop);
        $this->display('List:' . $this->CFG['listtemp']);
    }

    public function search() {
        $key = trim($_REQUEST["key"]);
        if ($this->CFG['cate_alias'] && $_REQUEST['cate'] !== 0)
            $catid = getByAlias($_REQUEST['cate'], $this->CATEGORY);
        else
            $catid = $_REQUEST['cate'];
        if ($this->CFG['area_alias'] && $_REQUEST['area'] !== 0)
            $areaId = getByAlias($_REQUEST['area'], $this->AREA);
        else
            $areaId = $_REQUEST['area'];
        if (empty($key) && !$catid && !$areaId)
            $this->error('请输入您要找的信息关键词或选择相关地区和分类！');
        if (!is_utf8($key))
            $key = auto_charset($key, 'gbk', 'utf-8');
        $thistime = time();
        $map['_string'] = 'status=1 AND topnum=1 AND (expired=0 OR expired>=' . $thistime . ')';
        $topmap['_string'] = 'Article.topstatus>0 AND Article.toptotime>=' . $thistime;
        $site_title = $this->CFG['title'];
        $site_keywords = $this->CFG['keywords'];
        $site_description = $this->CFG['description'];
        if (!empty($key)) {
            $map['_string'] .= " AND title like '%" . $key . "%'";
            $topmap['_string'] .= " AND Article.title like '%" . $key . "%'";
        }
        if ($catid) {
            $map['_string'] .= " AND catid=" . $catid;
            $topmap['_string'] .= " AND Article.catid=" . $catid;
            if ($this->CATEGORY[$catid]['seo_title'])
                $site_title = $this->CATEGORY[$catid]['seo_title'];
            else
                $site_title = C('site_area') . $this->CATEGORY[$catid]['catname'] . ' - ' . $site_title;
            if ($this->CATEGORY[$catid]['seo_keywords'])
                $site_keywords = $this->CATEGORY[$catid]['seo_keywords'];
            else
                $site_keywords = C('site_area') . $this->CATEGORY[$catid]['catname'] . ' - ' . $site_keywords;
            if ($this->CATEGORY[$catid]['seo_description'])
                $site_description = $this->CATEGORY[$catid]['seo_description'];
            else
                $site_description = C('site_area') . $this->CATEGORY[$catid]['catname'] . ' - ' . $site_description;
        }
        if ($areaId) {
            $map['_string'] .= " AND areaid=" . $areaId;
            $topmap['_string'] .= " AND Article.areaid=" . $areaId;
            $site_title = str_replace(C('site_area'), $this->AREA[$areaId]['aname'], $site_title);
            $site_keywords = str_replace(C('site_area'), $this->AREA[$areaId]['aname'], $site_keywords);
            $site_description = str_replace(C('site_area'), $this->AREA[$areaId]['aname'], $site_description);
        }
        $sortBy = 'edittime desc';
        $this->_mlist($this->model, $map, $sortBy, $this->CFG['pagenum'], U('Article/search?cate=' . $_REQUEST['cate'] . '&area=' . $_REQUEST['area'] . '&key=' . $key . '&p=%Page%'));

        $havetop = 0;
        if (!isset($_GET['p']) || $_GET['p'] == 1) {
            $topmodel = D('ArticleTop');
            $toplist = $this->_mlist($topmodel, $topmap, $sortBy);
            if (!empty($toplist) && count($toplist) > 0) {
                $toplist = sortTop($toplist);
                $this->assign("toplist", $toplist);
                $havetop = 1;
            }
        }

        if (!empty($key)) {
            $site_title = '搜索 ' . $key . ' 相关信息 - ' . $site_title;
            $site_keywords = $key . ',' . $site_keywords;
        }
        $this->assign("site_title", $site_title);
        $this->assign("site_keywords", $site_keywords);
        $this->assign("site_description", $site_description);
        $this->assign("havetop", $havetop);
        $this->assign("searchkey", $key);
        $this->display('List:' . $this->CFG['listtemp']);
    }

    /**
     * 文章显示页面
     */
    public function detail() {
        $this->assign("jumpUrl", U('Article/index'));
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = $_GET["id"];
            $row = $this->model->where('id=' . $id)->find();
            if ($row) {
                if ($row['uid']) {
                    $user = M('Member');
                    $uarticle = $user->where('id=' . $row['uid'])->find();
                    $this->assign("user", $uarticle);
                }
                $this->assign("site_title", $row['title']);
                if ($row['extend'])
                    $row['contact'] = unserialize($row['contact']);
                if ($row['content'] != '')
                    $this->assign("site_keywords", h(strip_tags($row['content'])));
                else
                    $this->assign("site_keywords", $row['title']);
                if ($row['detail'] != '')
                    $this->assign("site_description", msubstr(h(strip_tags($row['detail'])), 0, 200));
                else
                    $this->assign("site_description", $row['title']);
                //获取所有的Module
                $modules = getModule();
                $this->assign("modulename", $modules[$this->moduleid]['mtitle']);
                $this->assign("row", $row);
                $this->display();
            } else {
                $this->error('信息ID不存在');
            }
        } else {
            $this->error('信息ID错误');
        }
    }

}