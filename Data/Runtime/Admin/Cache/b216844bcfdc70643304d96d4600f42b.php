<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<title><?php echo (C("site_name")); ?> - 后台管理中心</title>
<link href="__PUBLIC__/Css/admin.css" rel="stylesheet" type="text/css" />
<link href="__PUBLIC__/Css/global.css" rel="stylesheet" type="text/css" />
<link href="__PUBLIC__/Css/system.css" rel="stylesheet" type="text/css" />
<link href="__PUBLIC__/Css/table_form.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="__PUBLIC__/Js/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="__PUBLIC__/Js/common.js"></script>
<script language="javascript" type="text/javascript" src="__PUBLIC__/Js/admin_common.js"></script>
<script language="javascript" type="text/javascript" src="__PUBLIC__/Js/formvalidator.js" charset="UTF-8"></script>
<script language="javascript" type="text/javascript" src="__PUBLIC__/Js/formvalidatorregex.js" charset="UTF-8"></script>
<SCRIPT language=javascript>
if (top.location !== parent.location) {
	parent.location="<?php echo U('Index/main');?>";
}
$(function(){  
	$('.table').each(function(){
		$(this).find('tr:odd').find('td').css("background","#f1f1f1");
	});
 });
</SCRIPT>
</head>
<body>
<style type="text/css">
	html{_overflow-y:scroll}
</style>
<script charset="utf-8" src="__PUBLIC__/kindeditor/kindeditor-min.js"></script>
<script>
    <!--
    KindEditor.ready(function(K) {
        var detaileditor;
        detaileditor = K.create('#content', {
            newlineTag : 'br'
        });
    });
    $(function(){
        $.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:'200',height:'50'}, function(){this.close();$(obj).focus();})}});
        $("#catid").formValidator({onshow:"请选择信息分类",onfocus:"请选择信息分类",oncorrect:"输入正确"}).inputValidator({min:1,onerror:"请选择信息分类"});
        $("#title").formValidator({onshow:"请输入标题",onfocus:"请输入标题",oncorrect:"输入正确"}).inputValidator({min:1,onerror:"请输入标题"});
        $("#uid").formValidator({onshow:"请输入会员ID",onfocus:"会员ID应该为大于等于0的整数"}).inputValidator({max:5,onerror:"会员ID应该为大于等于0的整数"}).regexValidator({regexp:"num1",datatype:"enum",param:'i',onerror:"会员ID应该为大于等于0的整数"});
        //$("#linkurl").formValidator({empty:true,onshow:"请填写链接URL",onfocus:"请填写链接URL"}).inputValidator({onerror:"请填写链接URL"}).regexValidator({regexp:"url",datatype:"enum",param:'i',onerror:"链接URL格式错误"});
    })
    function delPic(obj) {
        var src = $('#picurl').val();
        $.get("__APP__?m=Article&a=delpic&picurl="+src);
        $('#picshow').html('');
        $('#picurl').val('');
    }
    //-->
</script>
<div class="common-form">
    <div style="clear:both;"><a href="<?php echo U('Article/index');?>" class="navbtn">返回<?php echo ($modulename); ?>管理</a></div>
    <form name="myform" id="myform" action="<?php echo U('Article/insert');?>" method="post">
        <input type="hidden" name="status" value="1">
        <input type="hidden" name="picurl" id="picurl" value="">
        <table width="100%" class="table_form contentWrap">
            <tr>
                <td align="right" width="80">文章标题：</td>
                <td align='left'><input type="text" name="title" id="title" onkeyup="checkLen(this,<?php echo ($CFG["cell_titlelen"]); ?>,'titlelen');" onchange="checkLen(this,<?php echo ($CFG["cell_titlelen"]); ?>,'titlelen');" size="60">
                    <?php echo show_style('toptitle');?> 不能超过<?php echo ($CFG["cell_titlelen"]); ?>个字符，还可以输入 <span id="titlelen" class="red"><?php echo ($CFG["cell_titlelen"]); ?></span> 字
                </td>
            </tr>
            <tr>
                <td align="right">文章摘要：</td>
                <td align='left' id="td_txt" style="display:">
                    <textarea name='summary' id='summary' style="width:<?php echo ($CFG['cell_width'][0]); ?>px;height:<?php echo ($CFG["cell_height"]); ?>px;" onchange="checkLen(this,<?php echo ($CFG["cell_contentlen"]); ?>,'contentlen');" onkeyup="checkLen(this,<?php echo ($CFG["cell_contentlen"]); ?>,'contentlen');"></textarea>  不能超过<?php echo ($CFG["cell_contentlen"]); ?>个字符，还可以输入 <span id="contentlen" class="red"><?php echo ($CFG["cell_contentlen"]); ?></span> 字
                </td>                
            </tr>
            <tr>
                <td align="right">文章内容：</td>
                <td align='left'><textarea name='content' id='content' style='height:400px;width:700px;'></textarea></td>
            </tr>
            <tr>
                <td align="right">文章分类：</td>
                <td align='left'>  
                    <select name="catid" id='catid'>
                        <option value="0" selected>--选择分类--</option>
                        <?php if(is_array($CATEGORY)): $i = 0; $__LIST__ = $CATEGORY;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["catname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">标签：</td>
                <td align='left'><input type="text" name="tags" maxlength='54' size="60"> 多个标签用空格分开（暂不支持逗号）</td>
            </tr>
            <tr>
                <td align="right"></td>
                <td align='left'><input type="submit" id="dosubmit" class="button" name="dosubmit" value=" 提 交 "/></td>
            </tr>
        </table>
        <div class="bk15"></div>
    </form>
</div>