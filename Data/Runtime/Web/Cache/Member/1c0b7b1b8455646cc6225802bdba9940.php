<?php if (!defined('THINK_PATH')) exit();?>var tp_member = '';
<?php if(($uid) == "0"): ?>tp_member += '<?php echo W('ShowOauth',array('type'=>1));?><a href="<?php echo U('Member/Public/login');?>">登录</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo U('Member/Public/reg');?>">注册</a>';
<?php else: ?>
	tp_member = '<?php echo ($uname); ?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo U('Member/Index/index');?>">会员中心</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo U('Member/Public/logout');?>">退出</a>';<?php endif; ?>
try { document.getElementById('userline').innerHTML = tp_member; } catch(e) {}