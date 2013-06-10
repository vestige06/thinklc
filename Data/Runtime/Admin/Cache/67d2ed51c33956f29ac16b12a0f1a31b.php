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
<script type="text/javascript">
var _del = 0;
</script>
<div class="tablelist">
<form method="post" action="<?php echo U('Etype/update');?>">
<input type="hidden" name="item" value="<?php echo ($item); ?>"/>
	<table class="table">
		<tr>
			<th colspan="8" class="top_th">
			<?php if(is_array($menus)): $i = 0; $__LIST__ = $menus;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a class="add" href="<?php echo ($vo["url"]); ?>"><?php echo ($vo["title"]); ?></a><span></span><?php endforeach; endif; else: echo "" ;endif; ?>
			</th>
		</tr>
		<tr>
            <th width="60">ID</th>
            <th width="60">删除</th>
            <th width="80">排序</th>
            <th width="150">分类名</th>
		</tr>
		<tbody>
		<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
			<td><?php echo ($vo["id"]); ?></td>
			<td><input name="etype[<?php echo ($vo["id"]); ?>][delete]" type="checkbox" value="1" onclick="if(this.checked){_del++;}else{_del--;}"/></td>
			<td><input name="etype[<?php echo ($vo["id"]); ?>][listorder]" type="text" size="3" value="<?php echo ($vo["listorder"]); ?>" maxlength="3"/></td>
			<td><input name="etype[<?php echo ($vo["id"]); ?>][typename]" type="text" size="20" value="<?php echo ($vo["typename"]); ?>" maxlength="20" style="width:200px;"/></td>
		</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		<tr>
			<td></td>
			<td>新增</td>
			<td><input name="etype[0][listorder]" type="text" size="3" value="" maxlength="3"/></td>
			<td><input name="etype[0][typename]" type="text" size="20" value="" maxlength="20" style="width:200px;"/></td>
		</tr>
		<tr>
			<td></td>
			<td colspan='3' align="left"><input type="submit" name="dosubmit" value=" 更 新 " onclick="if(_del && !confirm('提示:您选择删除'+_del+'个分类？确定要删除吗？')) return false;" class="btn"/></td> 
		</tr>
		</tbody>
	</table>
</form>
</div>
</body>
</html>