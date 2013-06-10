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
		var answereditor;
		answereditor = K.create('#answer', {
			newlineTag : 'br'
		});
	});
	$(function(){
		$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:'200',height:'50'}, function(){this.close();$(obj).focus();})}});
		$("#title").formValidator({onshow:"请输入标题",onfocus:"请输入标题",oncorrect:"输入正确"}).inputValidator({min:1,onerror:"请输入标题"});
		$("#tid").formValidator({onshow:"请选择问题分类",onfocus:"请选择问题分类",oncorrect:"输入正确"}).inputValidator({min:1,onerror:"请选择问题分类"});
	})
//-->
</script>
<div class="common-form">
	<div style="clear:both;"><a href="<?php echo U('Help/index');?>" class="navbtn">返回帮助管理</a></div>
	<form name="myform" id="myform" action="<?php echo U('Help/insert');?>" method="post">
	<input type="hidden" name="doclose" id="doclose" value="0">
	<input type="hidden" name="status" value="1">
	<input type="hidden" name="answertime" value="<?php echo time();?>">
		<table width="100%" class="table_form contentWrap">
		<tr>
			<td width="120">问题分类：</td>
			<td>
				<select name="tid" id="tid">
					<option value="">选择分类</option>
					<?php if(is_array($etype)): $i = 0; $__LIST__ = $etype;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["typename"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>标题：</td>
			<td><input name="title" id="title" type="text" size="60"/></td>
		</tr>
		<tr>
			<td>问题描述：</td>
			<td><textarea name='content' id='content' style='height:100px;width:400px;'></textarea></td>
		</tr>
		<tr>
			<td>回答：</td>
			<td><textarea name='answer' id='answer' style='height:400px;width:700px;'></textarea></td>
		</tr>
		<tr>
			<td>前台显示：</td>
			<td><input name="display" type="radio" value="1" checked/>是&nbsp;&nbsp;<input name="display" type="radio" value="0"/>否</td>
		</tr>
		</table>
		<div class="bk15"></div>
		<div class="btn"><input type="submit" id="dosubmit" class="button" name="dosubmit" value=" 提 交 "/></div>
	</form>
</div>