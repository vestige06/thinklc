<?php

//前后台公用函数库
function getModule() {
    $module = F('config/module');
    $modules = array();
    foreach ($module as $k => $v) {
        if ($v['status'] && !empty($v['mname']))
            $modules[$k] = $v;
    }
    return $modules;
}

//获取置顶图片
function getToppic($topstatus, $toptotime, $topnum) {
    if ($topstatus && $toptotime >= time()) {
        $tempstr = $topstatus == 1 ? '分类 ' : '首页/分类 ';
        $tempstr .= $topnum == 5 ? '通栏置顶' : $topnum . '格置顶';
        $tempstr .= '&#10;' . date("Y-m-d H:i", $toptotime) . ' 过期';
        return '<img src="__PUBLIC__/Images/icon/top_' . $topstatus . '_' . $topnum . '.gif" border="0" align="absmiddle" title="' . $tempstr . '">';
    } else
        return;
}

function getGrade($amount) {
    $ji = 10;
    $fen = 100;
    $top = 64;
    $jibie = $jifen = 0;
    do {
        $jifen += $fen;
        $tmp = $jifen * $ji;
        if ($amount > $tmp) {
            $amount -= $tmp;
            $jibie += $ji;
        } else {
            $jibie += (int) ($amount / $jifen);
            break;
        }
    } while ($jibie < $top);
    $picstr = '';
    $jb = $jibie;
    if ($jb >= $top)
        $picstr = '<img src="__PUBLIC__/Images/vip/top.gif" border="0" align="absmiddle" alt="信誉级别：至尊商家">';
    else {
        if ($jb >= 64) {
            $tmp = (int) ($jb / 64);
            $picstr .= str_repeat('<img src="__PUBLIC__/Images/vip/64.gif" border="0" align="absmiddle" alt="信誉级别：' . $jibie . '">', $tmp);
            $jb -= ($tmp * 64);
        }
        if ($jb >= 16) {
            $tmp = (int) ($jb / 16);
            $picstr .= str_repeat('<img src="__PUBLIC__/Images/vip/16.gif" border="0" align="absmiddle" alt="信誉级别：' . $jibie . '">', $tmp);
            $jb -= ($tmp * 16);
        }
        if ($jb >= 4) {
            $tmp = (int) ($jb / 4);
            $picstr .= str_repeat('<img src="__PUBLIC__/Images/vip/4.gif" border="0" align="absmiddle" alt="信誉级别：' . $jibie . '">', $tmp);
            $jb -= ($tmp * 4);
        }
        if ($jb >= 1) {
            $picstr .= str_repeat('<img src="__PUBLIC__/Images/vip/1.gif" border="0" align="absmiddle" alt="信誉级别：' . $jibie . '">', $jb);
        }
    }
    return $picstr;
}

//格式化置顶价格
function getPrice($pstr) {
    if (empty($pstr))
        return;
    $pricearr = array();
    $tmparr = explode(',', $pstr);
    foreach ($tmparr as $v) {
        $pricearr[] = explode('|', $v);
    }
    return $pricearr;
}