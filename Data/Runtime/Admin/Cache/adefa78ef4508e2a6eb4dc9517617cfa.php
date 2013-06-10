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
<form name="myform" action="__SELF__" method="post" id="myform">
<input type="hidden" name="item" value="<?php echo ($item); ?>">
<div class="pad-10">
	<div class="col-tab">
		<ul class="tabBut cu-li">
			<li id="tab_setting_1" class="on" onclick="SwapTab('setting','on','',3,1);">基本设置</li>
			<li id="tab_setting_2" onclick="SwapTab('setting','on','',3,2);">显示设置</li>
			<li id="tab_setting_3" onclick="SwapTab('setting','on','',3,3);">推广设置</li>
		</ul>
		<div id="div_setting_1" class="contentList pad-10">
			<table width="100%" class="table_form contentWrap">
				<tr>
					<td width="180">Title(模块标题)：</td>
					<td><input name="setting[title]" type="text" value="<?php echo ($setting["title"]); ?>" size="60"/></td>
				</tr>
				<tr>
					<td>Meta Keywords(模块关键词)：</td>
					<td><textarea name="setting[keywords]" id="keywords" style="width:400px;height:50px;"><?php echo ($setting["keywords"]); ?></textarea></td>
				</tr>
				<tr>
					<td>Meta Description(模块描述)：</td>
					<td><textarea name="setting[description]" id="description" style="width:400px;height:50px;"><?php echo ($setting["description"]); ?></textarea></td>
				</tr>
				<tr>
					<td>分类URL使用英文别名：</td>
					<td>
						<input type="radio" name="setting[cate_alias]" value="1" <?php if($setting['cate_alias'] == 1): ?>checked<?php endif; ?>>是&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" name="setting[cate_alias]" value="0" <?php if($setting['cate_alias'] == 0): ?>checked<?php endif; ?>>否<?php echo tips('需要为每个分类设置英文别名');?>
					</td>
				</tr>
				<tr>
					<td>地区URL使用英文别名：</td>
					<td>
						<input type="radio" name="setting[area_alias]" value="1" <?php if($setting['area_alias'] == 1): ?>checked<?php endif; ?>>是&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" name="setting[area_alias]" value="0" <?php if($setting['area_alias'] == 0): ?>checked<?php endif; ?>>否<?php echo tips('需要为每个地区设置英文别名');?>
					</td>
				</tr>
			</table>
		</div>
		<div id="div_setting_2" class="contentList pad-10 hidden">
			<table width="100%" class="table_form contentWrap">
				<tr>
					<td width="180">WAP模块列表页显示信息数：</td>
					<td><input name="setting[wappagenum]" type="text" value="<?php echo ($setting["wappagenum"]); ?>" size="10"/> <?php echo tips('仅对WAP模块有效，以下设置仅对网页版有效');?></td>
				</tr>
				<tr>
					<td>列表页模版：</td>
					<td><?php echo (getlisttemp($setting["listtemp"])); ?></td>
				</tr>
				<tr>
					<td>列表页显示信息数：</td>
					<td><input name="setting[pagenum]" type="text" value="<?php echo ($setting["pagenum"]); ?>" size="10"/> <?php echo tips('模块首页、分类页、地区页、搜索页每页信息数<br>如果选择无限加载方式，则为每次加载信息数');?></td>
				</tr>
				<tr>
					<td>列表页加载方式：</td>
					<td>
						<input type="radio" name="setting[loadtype]" value="0" <?php if($setting['loadtype'] == 0): ?>checked<?php endif; ?>>分页加载&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" name="setting[loadtype]" value="1" <?php if($setting['loadtype'] == 1): ?>checked<?php endif; ?>>单页无限加载
					</td>
				</tr>
			</table>
		</div>
		<div id="div_setting_3" class="contentList pad-10 hidden">
			<table width="100%" class="table_form contentWrap">
				<tr>
					<td width="180">开通积分置顶：</td>
					<td>
						<input name="setting[top_credit]" type="radio" value="1" <?php if($setting['top_credit'] == 1): ?>checked<?php endif; ?>/>是&nbsp;&nbsp;&nbsp;&nbsp;
						<input name="setting[top_credit]" type="radio" value="0" <?php if($setting['top_credit'] == 0): ?>checked<?php endif; ?>/>否<?php echo tips('此处开启后会员组使用积分置顶才有效');?>
					</td>
				</tr>
				<tr>
					<td>允许设置标题颜色：</td>
					<td>
						<input name="setting[top_title]" type="radio" value="1" <?php if($setting['top_title'] == 1): ?>checked<?php endif; ?>/>是&nbsp;&nbsp;&nbsp;&nbsp;
						<input name="setting[top_title]" type="radio" value="0" <?php if($setting['top_title'] == 0): ?>checked<?php endif; ?>/>否（选择否则继承会员组设置）
					</td>
				</tr>
				<tr>
					<td>置顶基本价格：</td>
					<td><input name="setting[top_price]" type="text" value="<?php echo ($setting["top_price"]); ?>" size="10"/>元/月 <?php echo tips('以|分割栏目和首页价格');?></td>
				</tr>
				<tr>
					<td>置顶优惠折扣：</td>
					<td><input name="setting[top_off]" type="text" value="<?php echo ($setting["top_off"]); ?>" size="20"/> <?php echo tips('以|分割购买月份和折扣');?></td>
				</tr>
				<tr>
					<td width="180">开通积分推广：</td>
					<td>
						<input name="setting[spread_credit]" type="radio" value="1" <?php if($setting['spread_credit'] == 1): ?>checked<?php endif; ?>/>是&nbsp;&nbsp;&nbsp;&nbsp;
						<input name="setting[spread_credit]" type="radio" value="0" <?php if($setting['spread_credit'] == 0): ?>checked<?php endif; ?>/>否<?php echo tips('此处开启后会员组使用积分置顶才有效');?>
					</td>
				</tr>
				<tr>
					<td>推广基本价格：</td>
					<td><input name="setting[spread_price]" type="text" value="<?php echo ($setting["spread_price"]); ?>" size="10"/>元/月</td>
				</tr>
				<tr>
					<td>推广优惠折扣：</td>
					<td><input name="setting[spread_off]" type="text" value="<?php echo ($setting["spread_off"]); ?>" size="20"/> <?php echo tips('以|分割购买月份和折扣');?></td>
				</tr>
			</table>
		</div>
		<div class="bk15"></div>
		<input name="dosubmit" type="submit" value=" 提 交 " class="button" id="dosubmit">
	</div>
</div>
</form>
</body>
</html>