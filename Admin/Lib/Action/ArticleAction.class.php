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