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
<div class="tablelist">
	<form name="myform" id="myform" action="<?php echo U('Webpage/order');?>" method="post">
	<table class="table">
		<tr>
			<th colspan="8" class="top_th">
				<a class="add" href="<?php echo U('Webpage/add');?>">添加单页</a><span></span>
				<a href="javascript:void(0)" onclick="$('#myform').submit();" class="sort">排序</a>
			</th>
		</tr>
		<tr>
            <th width="80">排序</th>
            <th width="60">ID</th>
            <th width="100">标题</th>
            <th width="100">英文别名</th>
			<th>管理操作</th>
		</tr>
		<tbody>
		<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
			<td><input name="listorders[<?php echo ($vo['id']); ?>]" type='text' size='3' value='<?php echo ($vo["listorder"]); ?>'></td>
            <td><?php echo ($vo["id"]); ?></td>
            <td><?php echo ($vo["title"]); ?></td>
            <td><?php echo ($vo["alias"]); ?></td>
			<td>
				<a href="<?php echo U('Webpage/edit?id='.$vo['id']);?>" title="修改单页"><?php echo getIcon('edit','修改单页');?></a>
				<a href="<?php echo U('Webpage/delete?id='.$vo['id']);?>" onclick="return confirm('你确定要删除此单页吗？')"><?php echo getIcon('del','删除单页');?></a>
			</td>
		</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		</tbody>
	</table>
	</form>
</div>
</body>
</html>