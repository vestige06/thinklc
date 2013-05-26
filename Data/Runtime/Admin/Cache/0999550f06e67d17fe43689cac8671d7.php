<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo (C("site_name")); ?></title>
<link href="__PUBLIC__/Css/global.css" rel="stylesheet" type="text/css" />
<link href="__PUBLIC__/Css/system.css" rel="stylesheet" type="text/css" />
<link href="__PUBLIC__/Css/admin.css" rel="stylesheet" type="text/css" />
<SCRIPT language=javascript>
if (top.location !== parent.location) {
	parent.location="<?php echo U('Index/default');?>";
}
</SCRIPT>
</head>
<body>
<div class="body">
	<div class="lf mr10" style="width:48%">
		<div class="quick">
			<div class="title">
				<div class="title_info zs">您好，<?php echo ($ainfo["aname"]); ?>，欢迎使用<?php echo (C("site_name")); ?></div>
			</div>
			<div class="login_info">
				您上次登录的时间是：<?php echo (date('Y-m-d H:i',$ainfo["logintime"])); ?> ，登录IP:<?php echo ($ainfo["loginip"]); ?>&nbsp;(不是您登录的？<a href="<?php echo U('Person/password');?>">请点这里</a>)
			</div>
			<p class="line"></p>
			<div class="quick_block">
				<a href="<?php echo U('Setting/index');?>"><img src="__PUBLIC__/Images/admin/index_ico.jpg"><span>网站设置</span></a>
				<a href="<?php echo U('Info/add');?>"><img src="__PUBLIC__/Images/admin/add_ico.jpg"><span>发布信息</span></a>
				<a href="<?php echo U('Charge/index');?>"><img src="__PUBLIC__/Images/admin/tj_ico.jpg"><span>充值记录</span></a>
			</div>
		</div>
	</div>
	<div class="col-auto">
		<div class="use_meth">
			<div class="title">
				<div class="title_info light">安全提示</div>
			</div>
			<div>
			<p class="red">※ 为了系统安全，强烈建议您修改后台文件名admin.php（修改后需手动删除/Data/Runtime/Admin/~runtime.php）</p>
			<p class="blue">※ 强烈建议您将Data和Uploads以外的目录设置为644（linux/unix）或只读（NT）</p>
			<p class="blue">※ 网站根目录下config.inc.php和index.html必须具有写权限</p>
			</div>
		</div>
	</div>
	<div style="clear:both"></div>
	<div class="lf mr10" style="width:48%">
		<div class="use_meth">
			<div class="title">
				<div class="title_info light">系统信息</div>
			</div>
			<div>
			<p>ThinkLC程序版本：ThinkLC V<?php echo ($s_version); ?> Release<?php echo ($s_release); ?></p>
			<p>操作系统：<?php echo ($sysinfo['os']); ?></p>
			<p>服务器软件：<?php echo ($sysinfo['web_server']); ?></p>
			<p>MySQL版本：<?php echo ($sysinfo['mysqlv']); ?></p>
			<p>上传文件：<?php echo ($sysinfo['fileupload']); ?></p>
			</div>
		</div>
	</div>
	<div class="col-auto">
		<div class="use_meth">
			<div class="title">
				<div class="title_info light">快捷方式</div>
			</div>
			<div>
			<p><?php echo ($panel); ?></p>
			</div>
		</div>
	</div>
</div>
</body>
</html>