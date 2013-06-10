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
<div class="title_h2">搜索</div>
<p class="line" style="margin-top:0;"></p>
<form action="<?php echo U('Help/index');?>" method="get" id="searchform">
<?php if($Think.config.URL_MODE == 0): ?><input type="hidden" name="m" value="<?php echo (MODULE_NAME); ?>" />
<input type="hidden" name="a" value="<?php echo (ACTION_NAME); ?>" /><?php endif; ?>
<input type="hidden" name="title_like" value="1" />
<input type="hidden" name="_order" id="_order" value="<?php echo ($order); ?>" />
<input type="hidden" name="_sort" id="_sort" value="<?php echo ($sort); ?>" />
	<div class="filed fl">
	<select name="tid">
		<option value="">选择分类</option>
		<?php if(is_array($etype)): $i = 0; $__LIST__ = $etype;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if(($_GET['tid']) == $vo["id"]): ?>selected=selected<?php endif; ?>><?php echo ($vo["typename"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
	</select>
	<select name="status" >
		<option value="">问题状态</option>
		<option value="1" <?php if(($_GET['status']) == "1"): ?>selected=selected<?php endif; ?>>已处理</option>
		<option value="0" <?php if(($_GET['status']) == "0"): ?>selected=selected<?php endif; ?>>未处理</option>
	</select>
	<select name="display" >
		<option value="">显示状态</option>
		<option value="1" <?php if(($_GET['display']) == "1"): ?>selected=selected<?php endif; ?>>前台显示</option>
		<option value="0" <?php if(($_GET['display']) == "0"): ?>selected=selected<?php endif; ?>>前台不显示</option>
	</select>
	<select name="fieldskey">
		<option value="title" <?php if(($_GET['fieldskey']) == "title"): ?>selected=selected<?php endif; ?>>标题</option>
		<option value="uid" <?php if(($_GET['fieldskey']) == "uid"): ?>selected=selected<?php endif; ?>>会员ID</option>
	</select>
	<input type="text" name="fieldsvalue" value="<?php echo ($_GET['fieldsvalue']); ?>"/>
	</div>
	<div class="filed fl">
		<input type="submit" name="dosubmit" class="button" value="" />
	</div>
</form>
<div class="tablelist">
	<form action="<?php echo U('Help/delete');?>" method="post">
	<table class="table">
		<tr>
			<th colspan="8" class="top_th">
				<a class="add" href="<?php echo U('Help/add');?>">添加问题</a><span></span>
				<a class="add" href="<?php echo U('Etype/index?item=help');?>">问题分类</a>
			</th>
		</tr>
		<tr>
            <th width="40"><input type="checkbox" id="check_box" onclick="selectall('id[]');"/></th>
            <th width="100">分类</th>
            <th>标题</th>
            <th width="60">会员ID</th>
            <th width="120"><?php echo getSort('提问时间', 'asktime', $order, $sortImg);?></th>
            <th width="80">处理状态</th>
            <th width="80">前台显示</th>
			<th>管理操作</th>
		</tr>
		<tbody>
		<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
            <td><input type="checkbox" name="id[]" value="<?php echo ($vo['id']); ?>"></td>
			<td><a href="<?php echo U('Help/index?tid='.$vo['tid']);?>"><?php echo ($etype[$vo['tid']]['typename']); ?></a></td>
            <td><?php if($vo["status"] == 0): ?><font color="red"><?php echo ($vo["title"]); ?></font><?php else: echo ($vo["title"]); endif; ?></td>
            <td><a href="<?php echo U('Help/index??fieldskey=uid&fieldsvalue='.$vo['uid']);?>"><?php echo ($vo["uid"]); ?></a></td>
            <td><?php if(($vo["uid"]) == "0"): ?>后台添加<?php else: ?><font color="blue"><?php echo (date("Y-m-d H:i",$vo["asktime"])); ?></font><?php endif; ?></td>
            <td><?php echo getStatus($vo['status'], true, $vo['id'], 'status', array('未处理','已处理'));?></td>
            <td><?php echo getStatus($vo['display'], true, $vo['id'], 'display', array('不显示','显示'));?></td>
			<td>
				<a href="<?php echo U('Help/edit?id='.$vo['id']);?>" title="问题处理与修改"><?php echo getIcon('edit','问题处理与修改');?></a>
				<a href="<?php echo U('Help/delete?id='.$vo['id']);?>" onclick="return confirm('你确定要删除此问题吗？')"><?php echo getIcon('del','删除问题');?></a>
			</td>
		</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		<tr><td colspan="10"><input type="submit" id="dosubmit" class="button" name="dosubmit" value=" 删 除 " onclick="return confirm('你确定要删除选中问题吗？')"/></td></tr>
		</tbody>
	</table>
	</form>
	<div id="pages"><?php echo ($page); ?></div>
</div>
</body>
</html>