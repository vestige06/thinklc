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
		detaileditor = K.create('#detail', {
			newlineTag : 'br'
		});
	});
	$(function(){
		$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:'200',height:'50'}, function(){this.close();$(obj).focus();})}});
		$("#catid").formValidator({onshow:"请选择信息分类",onfocus:"请选择信息分类",oncorrect:"输入正确"}).inputValidator({min:1,onerror:"请选择信息分类"});
		$("#title").formValidator({onshow:"请输入标题",onfocus:"请输入标题",oncorrect:"输入正确"}).inputValidator({min:1,onerror:"请输入标题"});
		$("#uid").formValidator({onshow:"请输入会员ID",onfocus:"会员ID应该为大于等于0的整数"}).inputValidator({max:5,onerror:"会员ID应该为大于等于0的整数"}).regexValidator({regexp:"num1",datatype:"enum",param:'i',onerror:"会员ID应该为大于等于0的整数"});
		$("#linkurl").formValidator({empty:true,onshow:"请填写链接URL",onfocus:"请填写链接URL"}).inputValidator({onerror:"请填写链接URL"}).regexValidator({regexp:"url",datatype:"enum",param:'i',onerror:"链接URL格式错误"});
	})
	function delPic(obj) {
		var src = $('#picurl').val();
		$.get("__APP__?m=Info&a=delpic&picurl="+src);
		$('#picshow').html('');
		$('#picurl').val('');
	}
//-->
</script>
<div class="common-form">
	<div style="clear:both;"><a href="<?php echo U('Info/index');?>" class="navbtn">返回<?php echo ($modulename); ?>管理</a></div>
	<form name="myform" id="myform" action="<?php echo U('Info/insert');?>" method="post">
	<input type="hidden" name="status" value="1">
	<input type="hidden" name="picurl" id="picurl" value="">
		<table width="100%" class="table_form contentWrap">
			<tr>
				<td align="right" width="130">信息分类：</td>
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
				<td align="right">信息标题：</td>
				<td align='left'><input type="text" name="title" id="title" onkeyup="checkLen(this,<?php echo ($CFG["cell_titlelen"]); ?>,'titlelen');" onchange="checkLen(this,<?php echo ($CFG["cell_titlelen"]); ?>,'titlelen');" size="30">
					<?php echo show_style('toptitle');?> 不能超过<?php echo ($CFG["cell_titlelen"]); ?>个字符，还可以输入 <span id="titlelen" class="red"><?php echo ($CFG["cell_titlelen"]); ?></span> 字
				</td>
			</tr>
			<tr>
				<td align="right">信息内容模式：</td>
				<td align='left'>
					<input type="radio" name="ispic" value="0" checked onclick="tp(0);">文字
					<input type="radio" name="ispic" value="1" onclick="tp(1);">图片 （图片宽高：<span class="red"><?php echo ($CFG['cell_width'][0]); ?> X <?php echo ($CFG["cell_height"]); ?></span> px，超过此宽度或高度将被自动裁剪。）
				</td>
			</tr>
			<tr>
				<td align="right">信息内容：</td>
				<td align='left' id="td_txt" style="display:">
					<textarea name='content' id='content' style="width:<?php echo ($CFG['cell_width'][0]); ?>px;height:<?php echo ($CFG["cell_height"]); ?>px;" onchange="checkLen(this,<?php echo ($CFG["cell_contentlen"]); ?>,'contentlen');" onkeyup="checkLen(this,<?php echo ($CFG["cell_contentlen"]); ?>,'contentlen');"></textarea>  不能超过<?php echo ($CFG["cell_contentlen"]); ?>个字符，还可以输入 <span id="contentlen" class="red"><?php echo ($CFG["cell_contentlen"]); ?></span> 字
				</td>
				<td id="td_pic" style="display:none;">
					<div class="waitpic cu" style="width:<?php echo ($CFG['cell_width'][0]); ?>px;height:<?php echo ($CFG["cell_height"]); ?>px;" onclick="if($('#picurl').val()!=''){preview($('#picurl').val());return false;}doDialog('<?php echo U('Info/upload');?>','上传图片','280','100');">
						<span id="picshow"></span>
					</div>
					<div style="width:<?php echo ($CFG['cell_width'][0]); ?>px;text-align:center" class="mt10">
						<span onclick="doDialog('<?php echo U('Info/upload');?>','上传图片','280','100')" class="cu"><img src="__PUBLIC__/Images/icon/img_upload.gif" title="上传"/></span>&nbsp;&nbsp;
						<span onclick="delPic();" class="cu"><img src="__PUBLIC__/Images/icon/img_delete.gif" width="12" height="12" title="删除"/></span>
					</div>
				</td>
			</tr>
			<tr>
				<td align="right">过期时间：</td>
				<td align='left'><?php echo show_date('expired','',0,0,'false');?>
					<select onchange="$('#expired').val(this.value);">
						<option value="">快捷选择</option>
						<option value="">长期有效</option>
						<option value="<?php echo date('Y-m-d',strtotime("+3 days"));?>">3天</option>
						<option value="<?php echo date('Y-m-d',strtotime("+7 days"));?>">一周</option>
						<option value="<?php echo date('Y-m-d',strtotime("+15 days"));?>">半月</option>
						<option value="<?php echo date('Y-m-d',strtotime("+1 months"));?>">一月</option>
						<option value="<?php echo date('Y-m-d',strtotime("+6 months"));?>">半年</option>
						<option value="<?php echo date('Y-m-d',strtotime("+1 years"));?>">一年</option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right">会员ID：</td>
				<td align='left'>
					<input name="uid" id="uid" value="0"> 
					<?php if(($CFG["contact_extend"]) == "1"): ?><input type="checkbox" name="extend" value='1' onclick="if(this.checked){ $('#contact').css('display','');}else{ $('#contact').css('display','none');}">扩展联系方式
					<?php echo tips('可以将联系方式直接放在信息内容里<br>也可以填写更加详细的联系方式，显示在信息栏右下角'); endif; ?>
				</td>
			</tr>
			<?php if(($CFG["contact_extend"]) == "1"): ?><tbody id="contact" style="display:none;">
			<tr>
				<td align="right">联系人：</td>
				<td align='left'><input name="contact[contacter]" value=""></td>
			</tr>
			<tr>
				<td align="right">联系电话：</td>
				<td align='left'><input name="contact[tel]" value=""></td>
			</tr>
			<?php if(($CFG["contact_qq"]) == "1"): ?><tr>
				<td align="right">QQ：</td>
				<td align='left'><input name="contact[qq]" value=""></td>
			</tr><?php endif; ?>
			<?php if(($CFG["contact_msn"]) == "1"): ?><tr>
				<td align="right">MSN：</td>
				<td align='left'><input name="contact[msn]" value=""></td>
			</tr><?php endif; ?>
			<?php if(($CFG["contact_ali"]) == "1"): ?><tr>
				<td align="right">阿里旺旺：</td>
				<td align='left'><input name="contact[ali]" value=""></td>
			</tr><?php endif; ?>
			</tbody><?php endif; ?>
			<tr>
				<td align="right">详细介绍：</td>
				<td align='left'><textarea name='detail' id='detail' style='height:400px;width:700px;'></textarea></td>
			</tr>
			<tr>
				<td align="right">关键字：</td>
				<td align='left'><input type="text" name="keyword" maxlength='10'> 信息关键字</td>
			</tr>
			<tr>
				<td align="right">外链：</td>
				<td align='left'><input type="text" name="linkurl" id="linkurl" value=''> 该信息在您网站上的连接，http:// 开头，如无请留空</td>
			</tr>
		</table>
		<div class="bk15"></div>
		<div class="btn"><input type="submit" id="dosubmit" class="button" name="dosubmit" value=" 提 交 "/></div>
	</form>
</div>