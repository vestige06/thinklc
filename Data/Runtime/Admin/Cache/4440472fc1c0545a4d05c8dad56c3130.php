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
<div class="tablelist" style="width:480px">	
	<table class="table">
		<tr><th colspan="12" class="top_th">
			<h6><?php if($action == 'config'): ?>更新<?php echo ($menu[$action]); elseif(($action == 'index') OR ($action == 'onekey')): echo ($menu[$action]); else: ?>清理<?php echo ($menu[$action]); endif; ?></h6>
		</th></tr>
		<tr>
			<td>
				<div class="content">
					<?php echo ($description[$action]); ?>
					<br />
					<div class="bk20 hr"><hr /></div>
					<?php switch($action): case "config": ?><a href="<?php echo U('Cache/config?confirm=1');?>">点击这里确认更新配置缓存</a><?php break;?>
					<?php case "compiled": ?><form action="<?php echo U('Cache/compiled');?>" method="get">
						<?php if($Think.config.URL_MODE == 0): ?><input type="hidden" name="m" value="<?php echo (MODULE_NAME); ?>" />
						<input type="hidden" name="a" value="<?php echo (ACTION_NAME); ?>" /><?php endif; ?>
						<input type="checkbox" name="db" value="1">数据库字段缓存
						<input type="checkbox" name="web" value="1" checked>前台编译文件
						<input type="checkbox" name="admin" value="1" checked>后台编译文件<br><br>
						<input type="submit" name="confirm" value="确认更新" class="button">
						</form><?php break;?>
					<?php case "templates": ?><form action="<?php echo U('Cache/templates');?>" method="get">
						<?php if($Think.config.URL_MODE == 0): ?><input type="hidden" name="m" value="<?php echo (MODULE_NAME); ?>" />
						<input type="hidden" name="a" value="<?php echo (ACTION_NAME); ?>" /><?php endif; ?>
						<input type="checkbox" name="web" value="1" checked>前台模版缓存
						<input type="checkbox" name="admin" value="1">后台模版缓存<br><br>
						<input type="submit" name="confirm" value="确认更新" class="button">
						</form><?php break;?>
					<?php case "html": ?><a href="<?php echo U('Cache/html?confirm=1');?>">点击这里确认清理网页缓存</a><?php break;?>
					<?php case "index": ?><a href="<?php echo U('Cache/index?confirm=1');?>">点击这里确认刷新静态首页</a><?php break;?>
					<?php case "onekey": ?><form action="<?php echo U('Cache/onekey');?>" method="get">
						<?php if($Think.config.URL_MODE == 0): ?><input type="hidden" name="m" value="<?php echo (MODULE_NAME); ?>" />
						<input type="hidden" name="a" value="<?php echo (ACTION_NAME); ?>" /><?php endif; ?>
						<input type="checkbox" name="config" value="1" checked>配置缓存
						<input type="checkbox" name="compiled" value="1">编译缓存
						<input type="checkbox" name="templates" value="1" checked>模版缓存
						<input type="checkbox" name="html" value="1" checked>网页缓存
						<input type="checkbox" name="index" value="1">静态首页<br><br>
						<input type="submit" name="confirm" value="确认更新" class="button">
						</form><?php break; endswitch;?>
				</div>
			</td>
		</tr>
</div>
</body>
</html>