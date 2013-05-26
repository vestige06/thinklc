<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" class="off">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo (C("site_name")); ?> - 后台管理中心</title>
<link href="__PUBLIC__/Css/global.css" rel="stylesheet" type="text/css" />
<link href="__PUBLIC__/Css/admin.css" rel="stylesheet" type="text/css" />
<link href="__PUBLIC__/Css/system.css" rel="stylesheet" type="text/css" />
<link href="__PUBLIC__/Css/dialog.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="__PUBLIC__/Js/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="__PUBLIC__/Js/dialog.js"></script>
<script language="javascript" type="text/javascript" src="__PUBLIC__/Js/hotkeys.js"></script>
<script language="javascript" type="text/javascript" src="__PUBLIC__/Js/jquery.sgallery.js"></script>
<style type="text/css">
.objbody{overflow:hidden}
</style>
</head>
<body scroll="no" class="objbody">
<div id="dvLockScreen" class="ScreenLock" <?php if($lock_screen != 1): ?>style="display:none"<?php endif; ?>>
    <div id="dvLockScreenWin" class="inputpwd">
		<h5><b class="ico ico-info"></b><span id="lock_tips">锁屏状态，请输入密码解锁</span></h5>
		<div class="input">
    		<label class="lb">密码：</label>
			<input type="password" id="lock_password" class="input-text" size="20">
			<input type="submit" class="submit" value="&nbsp;" name="dosubmit" onclick="check_screenlock();return false;">
		</div>
	</div>
</div>
<div class="top">
	<div class="top_about">	
		<a href="http://www.saxue.com" class="help1" id="btn2" target="_blank">官方网站</a>
		<a href="javascript:" class="help2" onclick="window.top.art.dialog({title:'关于',content:'ThinkLC地方分类信息管理系统<br>Copyright © life0573.com All rights reserved.<br>技术 QQ：25897，30796933<br>商业服务：13857379691',lock:true}, function(){this.close();$(obj).focus();})">关于</a>
	</div>
	<div class="admin_logo">
		<img src="__PUBLIC__/Images/admin/admin_logo.gif">
	</div>
	<div class="top_nav">
			<ul>
				<li class="top_nav_l"></li>
				<?php if(is_array($topmenu)): $i = 0; $__LIST__ = $topmenu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li id="_M<?php echo ($vo["id"]); ?>" class="top_menu"><a href="javascript:_M(<?php echo ($vo["id"]); ?>)" hidefocus="true" style="outline:none;"><?php echo ($vo["mtitle"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
				<li class="top_nav_r"></li>
			</ul>
	</div>
	<div class="top_member">
	欢迎您，<?php echo ($adminname); ?>  [<font color="red"><?php echo ($adminrname); ?></font>]&nbsp;&nbsp;|&nbsp;&nbsp;<a href="/">网站首页</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo U('Index/main');?>" target="right">后台首页</a> 
	</div>
</div>
<div id="content">
	<div class="col-left left">
		<div class="member_info">
			<div class="member_ico"><img src="__PUBLIC__/Images/admin/a.png" width="43" height="43"></div>
			<a class="system_a" href="<?php echo U('Setting/config');?>" target="right">系统配置</a><a href="javascript:;" onclick="lock_screen()" class="system_log">锁屏</a><a href="<?php echo U('Public/logout');?>" class="system_logout">退出</a>
		</div>
    	<div id="Scroll"><div id="leftMain"></div></div>
    </div>
	<div class="side_switch" id="side_switch"></div>
    <div class="col-auto">
		<div class="top_subnav">当前位置：<span id="current_pos">首页</span></div>
    	<div class="col-1">
        	<div class="content" style="position:relative; overflow:hidden">
                <iframe name="right" id="rightMain" src="<?php echo U('Index/main');?>" frameborder="false" scrolling="auto" style="border:none; margin-bottom:30px" width="100%" height="auto" allowtransparency="true"></iframe>
                <div class="fav-nav">
					<div id="panellist"><?php echo ($panel); ?></div>
					<div id="paneladd"></div>
					<input type="hidden" id="menuid" value="">
					<input type="hidden" id="bigid" value="" />
                    <div id="help" class="fav-help"></div>
				</div>
        	</div>
        </div>
    </div>
</div>
<div class="scroll">
	<a href="javascript:;" class="per" title="使用鼠标滚轴滚动侧栏" onclick="menuScroll(1);"></a>
	<a href="javascript:;" class="next" title="使用鼠标滚轴滚动侧栏" onclick="menuScroll(2);"></a>
</div>
<script type="text/javascript"> 
if(!Array.prototype.map)
	Array.prototype.map = function(fn,scope) {
		var result = [],ri = 0;
		for (var i = 0,n = this.length; i < n; i++){
			if(i in this){
				result[ri++]  = fn.call(scope ,this[i],i,this);
			}
		}
	return result;
	};
var getWindowSize = function(){
	return ["Height","Width"].map(function(name){
		return window["inner"+name] || document.compatMode === "CSS1Compat" && document.documentElement[ "client" + name ] || document.body[ "client" + name ]
	});
}
window.onload = function (){
	if(!+"\v1" && !document.querySelector) { // for IE6 IE7
	  document.body.onresize = resize;
	} else { 
	  window.onresize = resize;
	}
	function resize() {
		wSize();
		return false;
	}
}
function wSize(){
	//这是一字符串
	var str=getWindowSize();
	var strs= new Array(); //定义一数组
	strs=str.toString().split(","); //字符分割
	var heights = strs[0]-150,Body = $('body');$('#rightMain').height(heights);   
	//iframe.height = strs[0]-46;
	if(strs[1]<980){
		$('.top').css('width',980+'px');
		$('#content').css('width',980+'px');
		Body.attr('scroll','');
		Body.removeClass('objbody');
	}else{
		$('.top').css('width','auto');
		$('#content').css('width','auto');
		Body.attr('scroll','no');
		Body.addClass('objbody');
	}
	var side_switch = $("#rightMain").height()+39;
	$("#side_switch").height(side_switch+18);
	$("#Scroll").height(side_switch-20);
	windowW();
}
wSize();
function windowW(){
	if($('#Scroll').height()<$("#leftMain").height()){
		$(".scroll").show();
	}else{
		$(".scroll").hide();
	}
}
windowW();
//左侧开关
$("#side_switch").click(function(){
	if($(this).data('clicknum')==1) {
		$(".left").show();
		$(this).removeClass("side_switch_close");
		$(this).addClass("side_switch");
		$("#main").contents().find(".right_body").css('margin-left',200);
		$(this).data('clicknum', 0);
		$("#side_switch").show();
	} else {
		$(".left").hide();
		$(this).removeClass("side_switch");
		$(this).addClass("side_switch_close");
		$("#main").contents().find(".right_body").css('margin-left',0);
		$(this).data('clicknum', 1);
		$(".side_switch").hide();
	}
	return false;
});
function _M(menuid) {
	//$("#menuid").val(menuid);
	$("#leftMain").load("__APP__?m=index&a=leftmenu&pid="+menuid, {limit: 25}, function(){
		   windowW();
	});
	$('.top_menu').removeClass("on");
	$('#_M'+menuid).addClass("on");
	$.get("__APP__?m=index&a=menu_pos&menutype=1&menuid="+menuid, function(data){
		$("#current_pos").html(data);
	});
	//显示左侧菜单，当点击顶部时，展开左侧
	$(".left").show();
	$("#side_switch").removeClass("side_switch_close");
	$("#side_switch").addClass("side_switch");
	$("#main").contents().find(".right_body").css('margin-left',200);
	$("#side_switch").data('clicknum', 0);
	$("#side_switch").show();
}
_M(4);
function _MP(menuid,targetUrl) {
	$("#menuid").val(menuid);
	$("#paneladd").html('<a class="panel-add" href="javascript:add_panel();"><em>添加</em></a>');
	$("#rightMain").attr('src', targetUrl);
	$('.sub_menu').removeClass("selected fb blue");
	$('#_MP'+menuid).addClass("selected fb blue");
	$.get("__APP__?m=index&a=menu_pos&menutype=3&menuid="+menuid, function(data){
		$("#current_pos").html(data+'<span id="current_pos_attr"></span>');
	});
	$("#current_pos").data('clicknum', 1);
}
function add_panel() {
	var menuid = $("#menuid").val();
	$.ajax({
		type: "POST",
		url: "<?php echo U('Index/ajax_add_panel');?>",
		data: "menuid=" + menuid,
		success: function(data){
			if(data) {
				$("#panellist").html(data);
			}
		}
	});
}
function delete_panel(menuid) {
	$.ajax({
		type: "POST",
		url: "<?php echo U('Index/ajax_del_panel');?>",
		data: "menuid=" + menuid,
		success: function(data){
			$("#panellist").html(data);
		}
	});
}
function paneladdclass(id) {
	$("#panellist span a[class='on']").removeClass();
	$(id).addClass('on')
}
setInterval("session_life()", 160000);
function session_life() {
	$.get("<?php echo U('Index/session_life');?>");
}
function lock_screen() {
	$.get("<?php echo U('Index/lock_screen');?>");
	$('#dvLockScreen').css('display','');
}
function check_screenlock() {
	var lock_password = $('#lock_password').val();
	if(lock_password=='') {
		$('#lock_tips').html('<font color="red">密码不能为空。</font>');
		return false;
	}
	$.get("<?php echo U('Index/lock_screenlock');?>", { lock_password: lock_password},function(data){
		if(data==1) {
			$('#dvLockScreen').css('display','none');
			$('#lock_password').val('');
			$('#lock_tips').html('锁屏状态，请输入密码解锁');
		} else {
			$('#lock_tips').html('<font color="red">密码错误！</font>');
		}
	});
}
(function(){
    var addEvent = (function(){
             if (window.addEventListener) {
                return function(el, sType, fn, capture) {
                    el.addEventListener(sType, fn, (capture));
                };
            } else if (window.attachEvent) {
                return function(el, sType, fn, capture) {
                    el.attachEvent("on" + sType, fn);
                };
            } else {
                return function(){};
            }
        })(),
    Scroll = document.getElementById('Scroll');
    // IE6/IE7/IE8/Opera 10+/Safari5+
    addEvent(Scroll, 'mousewheel', function(event){
        event = window.event || event ;  
		if(event.wheelDelta <= 0 || event.detail > 0) {
				Scroll.scrollTop = Scroll.scrollTop + 29;
			} else {
				Scroll.scrollTop = Scroll.scrollTop - 29;
		}
    }, false);

    // Firefox 3.5+
    addEvent(Scroll, 'DOMMouseScroll',  function(event){
        event = window.event || event ;
		if(event.wheelDelta <= 0 || event.detail > 0) {
				Scroll.scrollTop = Scroll.scrollTop + 29;
			} else {
				Scroll.scrollTop = Scroll.scrollTop - 29;
		}
    }, false);
	
})();
function menuScroll(num){
	var Scroll = document.getElementById('Scroll');
	if(num==1){
		Scroll.scrollTop = Scroll.scrollTop - 60;
	}else{
		Scroll.scrollTop = Scroll.scrollTop + 60;
	}
}
</script>
</body>
</html>