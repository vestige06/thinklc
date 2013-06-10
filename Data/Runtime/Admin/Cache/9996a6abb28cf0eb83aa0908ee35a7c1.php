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
	<form name="myform" id="myform" action="<?php echo U('Category/bupdate');?>" method="post">
	<input type="hidden" name="moduleid" value="<?php echo ($moduleid); ?>">
	<table class="table">
		<tr>
			<th colspan="8" class="top_th">
				<a class="add" href="javascript:doDialog('<?php echo U('Category/add?moduleid='.$moduleid);?>','添加分类','520','320')">添加分类</a>
			</th>
		</tr>
		<tr>
            <th width="60">ID</th>
            <th width="80">排序</th>
            <th width="100">分类名称</th>
            <th width="100">英文别名</th>
            <th width="60">信息数</th>
			<th>管理操作</th>
		</tr>
		<tbody>
		<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
            <td><?php echo ($vo["id"]); ?></td>
			<td><input name="category[<?php echo ($vo['id']); ?>][listorder]" type='text' size='3' value='<?php echo ($vo["listorder"]); ?>'></td>
			<td><input name="category[<?php echo ($vo['id']); ?>][catname]" type='text' size='10' value='<?php echo ($vo["catname"]); ?>'></td>
			<td><input name="category[<?php echo ($vo['id']); ?>][alias]" type='text' size='10' value='<?php echo ($vo["alias"]); ?>'></td>
            <td><?php echo ($vo["items"]); ?></td>
			<td>
				<a href="javascript:doDialog('<?php echo U('Category/edit?moduleid='.$moduleid.'&id='.$vo['id']);?>','修改分类——<?php echo ($vo['catname']); ?>','520','320')" title="修改分类——<?php echo ($vo['catname']); ?>"><?php echo getIcon('edit','修改分类');?></a>
				<a href="<?php echo U('Category/delete?moduleid='.$moduleid.'&id='.$vo['id']);?>" onclick="return confirm('你确定要删除此分类吗？')"><?php echo getIcon('del','删除分类');?></a>
			</td>
		</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		<tr><td colspan="12">
			<input type="submit" value="更新分类" class="button" /><?php echo tips('当英文别名为空时，系统会自动生成分类名的拼音作为英文别名');?>
		</td></tr>
		</tbody>
	</table>
	</form>
	<div id="pages"><?php echo ($page); ?></div>
</div>
</body>
</html>