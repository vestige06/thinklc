<?php

// 信息模块
class ArticleAction extends CommonAction {

    protected $moduleid = 7;
    protected $CATEGORY;
    protected $AREA;
    protected $CFG;

    protected function _initialize() {
        parent::_initialize();
        $this->CATEGORY = F('config/category_' . $this->moduleid);
        $this->CFG = F('config/module_' . $this->moduleid);
        $this->CFG['cell_width'] = explode('|', $this->CFG['cell_width']);
        $this->AREA = F('config/area');
        $module = F('config/module');
        $this->assign("moduleid", $this->moduleid);
        $this->assign("modulename", $module[$this->moduleid]['mtitle']);
        $this->assign("CATEGORY", $this->CATEGORY);
        $this->assign("AREA", $this->AREA);
        $this->assign("CFG", $this->CFG);
//        dump($this->CFG);exit;
//        echo date('Y-m-d h:i:s', '1367392521');exit;
    }

    public function _before_index() {
        if (!empty($_GET['fieldskey']) && !empty($_GET['fieldsvalue']))
            $GLOBALS['_REQUEST'][$_GET['fieldskey']] = $_GET['fieldsvalue'];
    }

    public function _filter(&$map) {
        $_query = 'status=1';
        if ($_GET['isexpired'] == 1)
            $_query .= ' AND (expired=0 OR expired>' . time() . ')';
        elseif ($_GET['isexpired'] == 2)
            $_query .= ' AND expired>0 AND expired<' . time();
        if (!empty($_GET['fromtime']))
            $_query .= ' AND edittime>' . (strtotime($_GET['fromtime'] . ' 00:00:00'));
        if (!empty($_GET['totime']))
            $_query .= ' AND edittime<' . (strtotime($_GET['totime'] . ' 23:59:59'));
        $map['_string'] = $_query;
    }

    public function insert() {
        $Blog = D("Article");
        if ($vo = $Blog->create()) {
            //过滤特殊字符
            //已经改在model里验证，填充
            $vo['title'] = h($_POST['title']);
            $vo['content'] = h($_POST['content']);
            $vo['tags'] = empty($vo['tags']) ? '' : h($_POST['tags']);
            $vo['summary'] = empty($_POST['summary']) ? utf8Substr(h($_POST['content']), 0, 150) : h($_POST['summary']);//摘要
            //插入成功，返回id
            $return_id = $Blog->add();
            //$return_id = $Blog -> getLastInsID();//此句和上一句回返回的数据id是一样的。
            if ($return_id) {
                //数据保存触发器
                //method_exists -- 检查类的方法是否存在
                if (method_exists($this, '_trigger')) {
                    $this->_trigger($vo, $return_id);
                }
                $this->assign('jumpUrl', __URL__);
                $this->success("操作成功");
            } else {
                $this->error("操作失败");
            }
        } else {
            $this->error($Blog->getError());
        }
    }

    // 保存日志的标签和附件
    public function _trigger($vo, $return_id) {
//        if (ACTION_NAME == 'insert') {
//            $Attach = M("Attach");
//            $att['verify'] = 0;
//            $att['recordId'] = $return_id;
//            $Attach->where("verify='" . $_SESSION["attach_verify"] . "'")->save($att);
//        }
        if (!empty($vo['tags']) && ACTION_NAME == 'insert') {
            $this->saveTag($vo, $return_id, "Article");
        }else{
            $this->updateTag($vo, $return_id, "Article");
        }
    }
    /**
     * 插入标签
     * @param type $vo
     * @param type $return_id
     * @param type $module
     */
    public function saveTag($vo, $return_id, $module) {
        //$return_id是刚插入的这篇文章成功返回的id
        if (!empty($vo) && !empty($return_id)) {
            $Tag = M("Tag");
            $Tagged = M("Tagged");
            $tags = explode(' ', $vo['tags']);
            //Array ( [0] => 波波 [1] => php )
            //标签循环插入数据库
            foreach ($tags as $val) {
                $val = trim($val);
                if (!empty($val)) {
                    // 记录已经存在的标签
                    $map["module"] = $module;
                    $map["name"] = $val;
                    $tagg = $Tag->where($map)->find(); //返回结果是0
                    //select * from think_blog where module="Article" and name="bobo";
                    if ($tagg) {
                        $tagId = $tagg['id'];
                        //如果此标签已存在，就自增1
                        $Tag->where('id = ' . $tagId)->setInc('count');
                    } else {
                        $t = array();
                        $t["name"] = $val;
                        $t["count"] = 1;
                        $t["module"] = $module;
                        $result = $Tag->add($t);
                        $tagId = $result;
                    }
                }
                //记录tag信息，放成关联表当中
                $t = array();
                $t["module"] = $module;
                $t["record_id"] = $return_id; //blog表主键Id
                $t["tag_time"] = time();
                $t["tag_id"] = $tagId; //tag表主键id
                $Tagged->add($t);
            }
        }
    }

    /**
     * 更新标签
     * @param type $vo
     * @param type $return_id
     * @param type $module
     */
    public function updateTag($vo, $return_id, $module) {
        //$return_id是刚插入的这篇文章成功返回的id
        if (!empty($vo) && !empty($return_id)) {
            $Tag = M("Tag");
            $Tagged = M("Tagged");
            $tagsNew = explode(' ', $vo['tags']);
            $tagList = $Tag->select();
            $tag = array();
            //查出全部标签的id和name
            foreach ($tagList as $v) {
                $tag[$v['id']] = $v['name'];
            }
            //查出和当前文章相关联的文章
            if($return_id){
                $taggedList = $Tagged->where('record_id = ' . $return_id)->select();
            }
            foreach ($taggedList as $value){
                $tagged[$value['tag_id']] = $tag[$value['tag_id']];
            }
            //每次更新，都要把现在的标签删除，再重新插入，这个种的比较准确
            foreach ($tagged as $key => $value) {
                $result = $Tag->where('id='.$key)->find();
                //如果count大于1,就减1,等于1就删除
                if($result['count'] > 1){
                    $Tag->where('id=' . $key)->setDec('count');
                } else {
                    $Tag->where('id = ' . $key)->delete();
                }
            }
            //删除关联标签
            $Tagged->where('record_id = ' . $return_id)->delete();
            //新标签循环插入数据库
            foreach ($tagsNew as $val) {
                $val = trim($val);
                if (!empty($val)) {
                    // 记录已经存在的标签
                    $map["module"] = $module;
                    $map["name"] = $val;
                    $tagg = $Tag->where($map)->find(); //返回结果是0
                    //select * from think_blog where module="Article" and name="bobo";
                    if ($tagg) {
                        $tagId = $tagg['id'];
                        //如果此标签已存在，就自增1
                        $Tag->where('id = ' . $tagId)->setInc('count');
                    } else {
                        $t = array();
                        $t["name"] = $val;
                        $t["count"] = 1;
                        $t["module"] = $module;
                        $result = $Tag->add($t);
                        $tagId = $result;
                    }
                }
                //记录tag信息，放成关联表当中
                $t = array();
                $t["module"] = $module;
                $t["record_id"] = $return_id; //blog表主键Id
                $t["tag_time"] = time();
                $t["tag_id"] = $tagId; //tag表主键id
                $Tagged->add($t);
            }
        }else{
            exit();
        }
    }
/*
    public function edit() {
        $id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
        if (!empty($id)) {
            $Blog = D('Blog');
            $vo = $Blog->getById($id);
            $this->assign('vo', $vo);

            $category = D('Category');
            $catelist = $category->select();
            $this->assign('category', $catelist);
            $this->display();
        } else {
            echo '请选择要编辑的文章';
            return;
        }
    }
 * 
 */

    public function update() {
        $model = D("Article");
        $vo = $model->create();
        $vo['title'] = h($_POST['title']);
        $vo['content'] = h($_POST['content']);
        $vo['tags'] = h($_POST['tags']);
        if (!$vo) {
            $this->error($model->getError());
        }
        //当前数据的主键
        $id = is_array($vo) ? $vo[$model->getPk()] : $vo->{$model->getPk()};
        $result = $model->save($vo);
        if ($result) {
            //数据保存触发器
            if (method_exists($this, '_trigger')) {
                $this->_trigger($vo, $id);
            }
            if (!empty($_FILES)) {//如果有文件上传
                //执行默认上传操作
                //保存附件信息到数据库
                $this->_upload(MODULE_NAME, $id);
            }
            //成功提示
            $this->success("操作成功！");
        } else {
            //错误提示
            $this->error($model->getError());
        }
    }
/*
    public function delete() {
        //把传过来的id转换成整型
        $did = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
        if (!empty($did)) {
            $Blog = D('Blog');
            $Tag = M('Tag');
            $Tagged = M('Tagged');
            $Comment = M('Comment');
            //删除文章操作，先删除tag表的tag，再删除tags表的关联，再删除评论，最后删除自己
            $del = $Blog->find($did);
            if (!empty($del['tags'])) {
                $tagArr = explode(' ', $del['tags']);
                foreach ($tagArr as $value) {
                    $tagResult = $Tag->where('name="' . $value . '"')->select();
                    //$id = array();
                    foreach ($tagResult as $val) {

                        if ($val['count'] > 1) {
                            //如果tag的个数大于1则count减1
                            $Tag->where('name="' . $val['name'] . '"')->setDec('count');
                        } else {
                            //如果只有一个tag，则删除此tag
                            $Tag->where('id=' . $val['id'])->delete();
                        }
                    }
                    //操作tagged
                    $Tagged->where('record_id=' . $did)->delete();
                    //删除评论
                    $Comment->where('record_id=' . $did)->delete();
                }
            }
            if (false !== $Blog->where('id=' . $did)->delete()) {
                //$this->assign('jumpUrl', __URL__ . '/index');
                $this->success('操作成功');
            } else {
                $this->error('操作失败：' . $Blog->getDbError());
            }
        } else {
            $this->error('请选择删除用户');
        }
    }
 * 
 */



    public function move() {
        $mcatid = $_REQUEST['mcatid'];
        $id = $_POST['id'];
        if (!$mcatid || !$id)
            $this->error('参数错误');
        if (is_array($id))
            $condition = 'id IN (' . implode(',', $id) . ')';
        else
            $condition = 'id=' . $id;
        $article = D('Article');
        $result = $article->where($condition)->setField('catid', $mcatid);
        if (false !== $result)
            $this->success('移动分类成功');
        else
            $this->error('移动分类失败');
    }

    public function check() {
        if (!empty($_GET['fieldskey']) && !empty($_GET['fieldsvalue']))
            $GLOBALS['_REQUEST'][$_GET['fieldskey']] = $_GET['fieldsvalue'];
        $map = $this->_search('Article');
        $_query = 'status=0';
        if ($_GET['isexpired'] == 1)
            $_query .= ' AND (expired=0 OR expired>' . time() . ')';
        elseif ($_GET['isexpired'] == 2)
            $_query .= ' AND expired>0 AND expired<' . time();
        if (!empty($_GET['fromtime']))
            $_query .= ' AND edittime>' . (strtotime($_GET['fromtime'] . ' 00:00:00'));
        if (!empty($_GET['totime']))
            $_query .= ' AND edittime<' . (strtotime($_GET['totime'] . ' 23:59:59'));
        $map['_string'] = $_query;

        $model = M('Article');
        $this->_list($model, $map, 'edittime');
        $this->display();
    }

    public function top() {
        if (!empty($_GET['fieldskey']) && !empty($_GET['fieldsvalue']))
            $GLOBALS['_REQUEST'][$_GET['fieldskey']] = $_GET['fieldsvalue'];
        $map = $this->_search('Article');
        $_query = 'status=1';
        if ($_GET['istotime'] == 1)
            $_query .= ' AND toptotime>' . time();
        elseif ($_GET['istotime'] == 2)
            $_query .= ' AND toptotime<' . time();
        if (!empty($_GET['fromtime']))
            $_query .= ' AND toptotime>' . (strtotime($_GET['fromtime'] . ' 00:00:00'));
        if (!empty($_GET['totime']))
            $_query .= ' AND toptotime<' . (strtotime($_GET['totime'] . ' 23:59:59'));
        $map['_string'] = $_query;
        $map['topstatus'] = array('gt', 0);

        $model = M('Article');
        $this->_list($model, $map);
        $this->display();
    }

    public function topbuy() {
        if (!$_REQUEST['id'])
            $this->error('ID错误');
        $model = M('Article');
        $id = $_REQUEST['id'];
        $vo = $model->find($id);
        if ($vo) {
            if ($_POST['dosubmit']) {
                if ($_POST['deduct'] && !$_POST['uid'])
                    $this->error('用户ID错误，无法扣除费用，置顶失败');
                elseif ($_POST['deduct'] && $_POST['uid']) {
                    $member = M('Member');
                    $uarticle = $member->find($_POST['uid']);
                    $money = $_POST['money'];
                    $paytype = $_POST['paytype'];
                    if ($paytype) {
                        $MCFG = F('config/module_1');
                        $credit_money = intval($MCFG['credit_money']);
                        $credit = intval($money * $credit_money);
                        if ($uarticle['credit'] < $credit)
                            $this->error('用户 ' . $uarticle['uname'] . ' 账户积分不足');
                    } elseif ($uarticle['money'] < $money)
                        $this->error('用户 ' . $uarticle['uname'] . ' 账户资金不足，请先充值');
                }

                $data = array();
                $data['id'] = $_POST['id'];
                $data['topnum'] = $_POST['topnum'];
                $data['topstatus'] = $_POST['topstatus'];
                if ($vo['topstatus'] && $vo['toptotime'] > time())
                    $data['toptotime'] = strtotime("+" . $_POST['topdays'] . " month", $vo['toptotime']);
                else
                    $data['toptotime'] = strtotime("+" . $_POST['topdays'] . " month");
                $result = $model->save($data);
                if (false !== $result) {
                    if ($_POST['deduct'] && $_POST['uid']) {
                        if ($paytype) {
                            credit_add($uarticle['uname'], -$credit);
                            credit_record($uarticle['uname'], -$credit, $this->adminname, 'ID:' . $_POST['id'] . ',信息置顶', '自动');
                        } else {
                            money_add($uarticle['uname'], -$money);
                            money_record($uarticle['uname'], -$money, '站内', $this->adminname, 'ID:' . $_POST['id'] . ',信息置顶', '自动');
                        }
                    }
                    $this->success('置顶成功，您已经成功置顶 ' . $_POST['topdays'] . ' 个月');
                } else {
                    $this->error('置顶失败');
                }
            } else {
                $top_price = $top_off_1 = $top_off_2 = $top_off_3 = $top_off_5 = array();
                $top_price = getPrice($this->CFG['top_price']);
                $top_off_1 = getPrice($this->CFG['top_off_1']);
                $top_off_2 = getPrice($this->CFG['top_off_2']);
                $top_off_3 = getPrice($this->CFG['top_off_3']);
                $top_off_5 = getPrice($this->CFG['top_off_5']);
                if ($vo['topstatus'] && $vo['toptotime'] >= time()) {
                    $this->assign("top_status", 1);
                    $tmp1 = $vo['topnum'] == 5 ? ($vo['topnum'] - 2) : ($vo['topnum'] - 1);
                    $tmp2 = $vo['topstatus'] - 1;
                    $this->assign("top_startmoney", $top_price[$tmp1][$tmp2]);
                } else {
                    $this->assign("top_status", 0);
                    $this->assign("top_startmoney", $top_price['0']['0']);
                }
                $this->assign("top_price", $top_price);
                $this->assign("top_off_1", $top_off_1);
                $this->assign("top_off_2", $top_off_2);
                $this->assign("top_off_3", $top_off_3);
                $this->assign("top_off_5", $top_off_5);
                $this->assign("vo", $vo);
                $this->display();
            }
        } else {
            $this->error('信息不存在');
        }
    }

    public function edit() {
        if (!$_GET['id'])
            $this->error('ID错误');
        $model = M('Article');
        $id = $_GET['id'];
        $vo = $model->find($id);
        if ($vo) {
            if ($vo['topstatus'] && $vo['toptotime'] >= time()) {
                $is_top = 1;
            } else {
                $is_top = 0;
                $vo['topnum'] = 1;
                $vo['content'] = strip_tags($vo['content'], "<BR>");
                $vo['content'] = str_replace("<BR>", "\r\n", $vo['content']);
            }
            $topnum = $vo['topnum'] == 5 ? ($vo['topnum'] - 2) : ($vo['topnum'] - 1);
            $allow = array();
            $allow['cell_width'] = $this->CFG['cell_width'][$topnum];
            $allow['cell_height'] = $this->CFG['cell_height'];
            $allow['cell_eheight'] = $is_top ? (40 + $this->CFG['cell_height']) : $this->CFG['cell_height'];
            $allow['cell_titlelen'] = $vo['topnum'] * $this->CFG['cell_titlelen'];
            $allow['cell_contentlen'] = $vo['topnum'] * $this->CFG['cell_contentlen'];
            $this->assign('allow', $allow);
            $this->assign('vo', $vo);
            $this->assign("is_top", $is_top);
            $this->display();
        } else {
            $this->error('信息不存在');
        }
    }

    public function delete() {
        $id = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];
        if ($id) {
            if (is_array($id))
                $ids = implode(',', $id);
            else
                $ids = $id;
            $MCFG = F('config/module_1');
            if ($MCFG['credit_delete']) {
                //删除非过期信息扣除积分
                $model = D("ArticleView");
                $dscore = $model->field('uname,count')->where('Article.id IN (' . $ids . ') AND (Article.expired=0 OR Article.expired>=' . time() . ')')->group('Article.uid')->select();
                if ($dscore) {
                    foreach ($dscore as $v) {
                        $score = $v['count'] * $MCFG['credit_delete'];
                        credit_add($v['uname'], -$score);
                        credit_record($v['uname'], -$score, $this->adminname, '批量删除信息', '后台删除');
                    }
                }
            }
            $model = M('Article');
            $model->where('id in (' . $ids . ')')->delete();
            $this->success('删除信息成功');
        } else
            $this->error('请选择要删除的信息');
    }

}