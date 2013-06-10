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
<script>
<!--
	$(function(){
		$.formValidator.initConfig({formid:"myform",autotip:true,onsuccess:function(){if($("#tel").val()=="" && $("#mobile").val()==""){art.dialog({content:"电话和手机至少填写一个",lock:true}, function(){this.close();$("#tel").focus();});return false}},onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:'200',height:'50'}, function(){this.close();$(obj).focus();})}});
		$("#catid").formValidator({onshow:"请选择商家分类",onfocus:"请选择商家分类"})
			.inputValidator({min:1,onerror:"请选择商家分类"});
		$("#areaid").formValidator({onshow:"请选择所在地区",onfocus:"请选择所在地区"})
			.inputValidator({min:0,onerror:"请选择所在地区"});
		$("#title").formValidator({onshow:"请输入商家名称",onfocus:"请输入商家名称"})
			.inputValidator({onerror:"请输入商家名称"})
			.regexValidator({regexp:"^[\\w\\u4e00-\\u9fa5]{2,20}$",onerror:"只能填写汉字,数字,字母(2-20个字)。" });
		$("#business").formValidator({onshow:"请输入公司主要业务或经营范围",onfocus:"请输入公司主要业务或经营范围",'empty':true})
			.regexValidator({regexp:"^[\\w\\u4e00-\\u9fa5\\uFF00-\\uFFFF,]{4,60}$",onerror:"只能填写汉字,数字,字母，不超过60个字。" });
		$('#tel').formValidator({onshow:"电话和手机至少填写一个",onfocus:'请输入电话号码','empty':true})
			.regexValidator({regexp: "(^(0\\d{2,3})?-?([1-9]\\d{2,7})(-\\d{1,5})?$)|(^(400|800)\\d{7}(-\\d{1,6})?$)|(^(95013)\\d{6,8}$)",onerror:"电话填写错误。请参照格式填写：87654321-001"});
		$('#mobile').formValidator({onshow:"手机和电话至少填写一个",onfocus:'请输入手机号码','empty':true})
			.regexValidator({regexp:"(^(13|14|15|18)\\d{9}$)",onerror:"手机填写错误。请参照格式填写：138********"});
        $('#qq').formValidator({onshow:"方便别人与您在线交流",onfocus:'请输入您的QQ号码','empty':true})
			.regexValidator({regexp: "^[1-9]\\d{4,11}$", onerror: "QQ格式不正确"});
		$("#address").formValidator({onshow:"请输入详细地址",onfocus:"请输入详细地址",'empty':true})
			.regexValidator({regexp:"^[-\\w\\u4e00-\\u9fa5]{5,30}$",onerror:"只能填写汉字,数字,字母(5-30个字)。" });
	})
//-->
</script>
<div class="common-form">
	<div style="clear:both;"><a href="<?php echo U('Phone/index');?>" class="navbtn">返回<?php echo ($modulename); ?>管理</a></div>
	<form name="myform" id="myform" action="<?php echo U('Phone/insert');?>" method="post">
	<input type="hidden" name="status" value="1">
		<table width="100%" class="table_form contentWrap">
			<tr>
				<td align="right" width="130">商家分类：</td>
				<td align='left'>  
					<select name="catid" id='catid'>
					<option value="0" selected>--选择分类--</option>
					<?php if(is_array($CATEGORY)): $i = 0; $__LIST__ = $CATEGORY;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["catname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right">所在地区：</td>
				<td align='left'>  
					<select name="areaid" id='areaid'>
					<option value="0" selected>--选择地区--</option>
					<?php if(is_array($AREA)): $i = 0; $__LIST__ = $AREA;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["aname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right">商家名称：</td>
				<td align='left'><input type="text" name="title" id="title" maxlength="20" size="30"><?php echo show_style('toptitle');?></td>
			</tr>
			<tr>
				<td align="right">主营业务：</td>
				<td align='left'><input type="text" name="business" id="business" maxlength="60" size="30"></td>
			</tr>
			<tr>
				<td align="right">固定电话：</td>
				<td align='left'><input type="text" name="tel" id="tel" maxlength="18" size="30"></td>
			</tr>
			<tr>
				<td align="right">手机号码：</td>
				<td align='left'><input type="text" name="mobile" id="mobile" maxlength="11" size="30"></td>
			</tr>
			<tr>
				<td align="right">QQ联系：</td>
				<td align='left'><input type="text" name="qq" id="qq" maxlength="12" size="30"></td>
			</tr>
			<tr>
				<td align="right">详细地址：</td>
				<td align='left'><input type="text" name="address" id="address" maxlength="30" size="30"></td>
			</tr>
			<tr>
				<td align="right">标注地图：</td>
				<td align='left'>
					<input type="button" id="map_add" value="在地图上标记位置" onclick="pageDialog('<?php echo U('Phone/map');?>', '标记地图')"/>
					<input style="display:none" type="button" id="map_edit" value="已标注地图，可修改" onclick="pageDialog('<?php echo U('Phone/map');?>', '修改地图标记')"/>
					<input type="hidden" name="map" id="map">
				</td>
			</tr>
		</table>
		<div class="bk15"></div>
		<div class="btn"><input type="submit" id="dosubmit" class="button" name="dosubmit" value=" 提 交 "/></div>
	</form>
</div>