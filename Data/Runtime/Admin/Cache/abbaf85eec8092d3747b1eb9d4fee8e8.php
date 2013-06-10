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
<form action="<?php echo U('Phone/check');?>" method="get" id="searchform">
<?php if($Think.config.URL_MODE == 0): ?><input type="hidden" name="m" value="<?php echo (MODULE_NAME); ?>" />
<input type="hidden" name="a" value="<?php echo (ACTION_NAME); ?>" /><?php endif; ?>
<input type="hidden" name="title_like" value="1" />
<input type="hidden" name="address_like" value="1" />
<input type="hidden" name="_order" id="_order" value="<?php echo ($order); ?>" />
<input type="hidden" name="_sort" id="_sort" value="<?php echo ($sort); ?>" />
	<div class="filed fl">
	<select name="catid">
		<option value="">分类</option>
		<?php if(is_array($CATEGORY)): $i = 0; $__LIST__ = $CATEGORY;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if(($_GET['catid']) == $vo["id"]): ?>selected=selected<?php endif; ?>><?php echo ($vo["catname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
	</select>
	<select name="areaid">
		<option value="">地区</option>
		<?php if(is_array($AREA)): $i = 0; $__LIST__ = $AREA;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if(($_GET['areaid']) == $vo["id"]): ?>selected=selected<?php endif; ?>><?php echo ($vo["aname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
	</select>
	<select name="fieldskey">
		<option value="title" <?php if(($_GET['fieldskey']) == "title"): ?>selected=selected<?php endif; ?>>标题</option>
		<option value="address" <?php if(($_GET['fieldskey']) == "address"): ?>selected=selected<?php endif; ?>>详细地址</option>
		<option value="uid" <?php if(($_GET['fieldskey']) == "uid"): ?>selected=selected<?php endif; ?>>会员ID</option>
	</select>
	<input type="text" name="fieldsvalue" value="<?php echo ($_GET['fieldsvalue']); ?>"/>
	</div>
	<div class="filed fl">
		<input type="submit" name="dosubmit" class="button" value="" />
	</div>
</form>
<div class="tablelist">
	<form action="<?php echo U('Phone/resume');?>" method="post">
	<input type="hidden" name="field" value="status" />
	<table class="table">
		<tr>
			<th colspan="12" class="top_th">
				<a class="add" href="<?php echo U('Phone/add');?>">添加<?php echo ($modulename); ?></a>
			</th>
		</tr>
		<tr>
            <th width="20"><input type="checkbox" id="check_box" onclick="selectall('id[]');"/></th>
			<th width="60">分类</th>
			<th width="60">地区</th>
			<th>商家名称</th>
			<th width="50">会员ID</th>
            <th width="40">状态</th>
			<th>管理操作</th>
		</tr>
		<tbody>
		<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
            <td><input type="checkbox" name="id[]" value="<?php echo ($vo['id']); ?>"></td>
			<td><a href="<?php echo U('Phone/check?catid='.$vo['catid']);?>"><?php echo ($CATEGORY[$vo['catid']]['catname']); ?></a></td>
			<td><?php if(($vo['areaid']) == "0"): ?>--<?php else: ?><a href="<?php echo U('Phone/check?areaid='.$vo['areaid']);?>"><?php echo ($AREA[$vo['areaid']]['aname']); ?></a><?php endif; ?></td>
			<td><span style="color:<?php echo ($vo["toptitle"]); ?>"><?php echo ($vo["title"]); ?></span></td>
			<td><a href="<?php echo U('Phone/check?fieldskey=uid&fieldsvalue='.$vo['uid']);?>"><?php echo ($vo['uid']); ?></a></td>
            <td><?php echo getStatus($vo['status'], true, $vo['id'], 'status', array('未审核','正常'));?></td>
			<td>
				<a href="<?php echo U('Phone/edit?id='.$vo['id']);?>" title="编辑信息"><?php echo getIcon('edit','编辑信息');?></a>
				<a href="<?php echo U('Phone/delete?id='.$vo['id']);?>" onclick="return confirm('你确定要删除此电话黄页吗？')"><?php echo getIcon('del','删除信息');?></a>
			</td>
		</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		<tr><td colspan="12">
			<input type="submit" id="dosubmit" class="button" name="dosubmit" value=" 通过审核 " />
			<input type="submit" id="dosubmit" class="button" name="dosubmit" value=" 删除信息 " onclick="if(!confirm('你确定要删除选中电话黄页吗？')){return false;}this.form.action='<?php echo U('Phone/delete');?>';"/>
			<input type="submit" id="dosubmit" class="button" name="dosubmit" value=" 移动分类 " onclick="if($('#mcatid').val()==0){alert('请选择分类');$('#mcatid').focus();return false;}this.form.action='<?php echo U('Phone/move');?>';"/>
			<select name="mcatid" id="mcatid">
				<option value="0">商家分类</option>
				<?php if(is_array($CATEGORY)): $i = 0; $__LIST__ = $CATEGORY;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["catname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
			</select>
		</td></tr>
		</tbody>
	</table>
	</form>
	<div id="pages"><?php echo ($page); ?></div>
</div>
</body>
</html>