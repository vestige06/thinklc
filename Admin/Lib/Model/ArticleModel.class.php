<?php

class ArticleModel extends CommonModel {

    protected $_validate = array(
        array('title', 'require', '信息标题不能为空！'),
        array('title', 'checktitle', '标题长度错误', 2, 'callback'),
        array('catid', 'require', '请选择信息分类！'),
        array('ispic', 'require', '请选择信息类型！'),
        array('ispic', 'checktype', '请根据信息内容模式填写内容或上传图片', 2, 'callback'),
        array('content', 'checkcontent', '信息内容长度错误', 2, 'callback'),
        array('verify', 'require', '验证码必须填写！'),
    );
    protected $_auto = array(
        array('edittime', 'time', 1, 'function'),
        array('expired', 'getexpired', 3, 'callback'),
        array('linkurl', 'getlinkurl', 3, 'callback'),
        array('content', 'getcontent', 3, 'callback'),
        array('contact', 'getcontact', 3, 'callback'),
    );

    protected function getexpired() {
        if (empty($_POST['expired']) || !$_POST['expired'])
            return false;
        return strtotime($_POST['expired'] . ' 23:59:59');
    }

    protected function getlinkurl() {
        if ($_POST['linkurl'] == 'http://' || strlen($_POST['linkurl']) < 12)
            return false;
        else
            return $_POST['linkurl'];
    }

    protected function getcontact() {
        if (empty($_POST['contact']))
            return false;
        else
            return serialize($_POST['contact']);
    }

    protected function getcontent() {
        if (empty($_POST['content']))
            return false;
        $tmp = $_POST['content'];
        $tmp = str_replace("<br />", "<BR>", $tmp);
        if (!isset($_POST['is_top']) || !$_POST['is_top']) {
            $tmp = strip_tags($tmp, "<BR>");
            $tmp = str_replace("\r\n", "<BR>", $tmp);
            $tmp = str_replace("\n", "<BR>", $tmp);
        }
        $temp = strip_tags($tmp);
        if (empty($temp))
            return false;
        else
            return $tmp;
    }

    public function checktype() {
        if (!$_POST['ispic'] && $_POST['content'] == '')
            return false;
        if ($_POST['ispic'] && $_POST['picurl'] == '')
            return false;
        return true;
    }

    public function checkcontent() {
        $setting = F('config/module_7');
        if (mb_strlen(strip_tags($_POST['content']), 'utf-8') > $setting['cell_contentlen'])
            return false;
        else
            return true;
    }

    public function checktitle() {
        $setting = F('config/module_7');
        if (mb_strlen(strip_tags($_POST['title']), 'utf-8') > $setting['cell_titlelen'])
            return false;
        else
            return true;
    }

}