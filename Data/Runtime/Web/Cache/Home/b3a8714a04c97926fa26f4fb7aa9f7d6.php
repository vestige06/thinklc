<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh" lang="zh">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo ($site_title); ?> - <?php echo (C("site_name")); ?></title>
<meta name="keywords" content="<?php echo ($site_keywords); ?>">
<meta name="description" content="<?php echo ($site_description); ?>">
<script type="text/javascript" src="__PUBLIC__/Js/shouji.js"></script>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/style.css"/>
<script language="javascript">
<!--
function soua(){
	var key=document.getElementById("key").value;
	if(key == '请输入您要找的信息关键词') {
		key = '';
	}
	<?php if((C("search_type")) == "1"): ?>var cate=document.getElementById("cate").value;
	var area=document.getElementById("area").value;
    if(key=='' && cate==0 && area==0) {
		alert('请输入您要找的信息关键词或选择相关地区和分类');
		document.getElementById("key").value='';
		document.getElementById("key").focus();
    } else {
		var s_url = "<?php echo U(MODULE_NAME.'/search?cate=Cate&area=Area&key=Key');?>";
		s_url = s_url.replace("Cate",cate);
		s_url = s_url.replace("Area",area);
		s_url = s_url.replace("Key",encodeURI(key));
		window.location.href = s_url;
    }
	<?php else: ?>
	var module=document.getElementById("module").value;
	if(key=='') {
		alert('请输入您要找的信息关键词');
		document.getElementById("key").value='';
		document.getElementById("key").focus();
		return false;
    }
	if(module=='') {
		alert('请选择要搜索的模块');
		document.getElementById("module").focus();
		return false;
    }
	var s_url = "<?php echo U('searchmodule/search?cate=0&area=0&key=Key');?>";
	s_url = s_url.replace("searchmodule",module);
	s_url = s_url.replace("Key",encodeURI(key));
	window.location.href = s_url;<?php endif; ?>
}
//-->
</script>
</head>
<body class='body'>
<div id="warp">
	<div class="head">
		<div class="head1"><a href="<?php echo (C("site_url")); ?>"><?php echo (C("site_name")); ?></a>&nbsp;-&nbsp;<?php echo (C("site_area")); ?>生活信息发布网</div>
		<div class="head2" id="userline"><script type="text/javascript" src="<?php echo U('Member/Public/userline');?>"></script></div>
	</div>
	<div class="w960 cent head3">
		<div class="logo"><a href="<?php echo (C("site_url")); ?>"><img src="__PUBLIC__/Images/logo.gif" alt="<?php echo (C("site_name")); ?>" /></a></div>
		<div class="head3-1"><script type="text/javascript" src="__PUBLIC__/ads/head.js"></script></div>
		<div class="head3-2"></div>
		<div class="head3-3">
			<input type="text" name="key" id="key" <?php if($searchkey != ''): ?>value="<?php echo ($searchkey); ?>"<?php else: ?>value="请输入您要找的信息关键词" onclick='this.value=""'<?php endif; ?> />
			<div class="head3-sel">
			<?php if((C("search_type")) == "1"): ?><select name="area" id='area'>
				<option value="0">地区</option>
				<?php if(is_array($AREA)): $i = 0; $__LIST__ = $AREA;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["name"]); ?>" <?php if(($vo["name"]) == $_GET['area']): ?>selected<?php endif; ?>><?php echo ($vo["aname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
				<select name="cate" id='cate'>
				<option value="0">分类</option>
				<?php if(is_array($CATEGORY)): $i = 0; $__LIST__ = $CATEGORY;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["name"]); ?>" <?php if(($vo["name"]) == $_GET['cate']): ?>selected<?php endif; ?>><?php echo ($vo["catname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
			<?php else: ?>
				<input type="hidden" name="cate" value="0">
				<input type="hidden" name="area" value="0">
				<select name="module" id="module">
				<option value="">选择模块</option>
				<?php if(is_array($modules)): $i = 0; $__LIST__ = $modules;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["mname"]); ?>" <?php if(($vo["mname"]) == MODULE_NAME): ?>selected<?php endif; ?>><?php echo ($vo["mtitle"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select><?php endif; ?>
			</div>
			<div class="head3-sou" onclick="soua()">&nbsp;</div>
		</div>
	</div>
	 
	<ul class="mtab2 cent tc">
		<li><a href="/">网站首页</a></li>
		<?php if(is_array($navigation)): $i = 0; $__LIST__ = $navigation;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo ($vo["url"]); ?>"><?php echo ($vo["name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
	</ul>
	 
	<?php if(!empty($CATEGORY)): ?><div class="box_class"> 
	<div class="box_c">
		<ul>
			<?php if(is_array($CATEGORY)): $i = 0; $__LIST__ = $CATEGORY;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo ($vo["url"]); ?>"><?php echo ($vo["catname"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
			<?php if(is_array($AREA)): $i = 0; $__LIST__ = $AREA;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="warea"><a href="<?php echo ($vo["url"]); ?>"><?php echo ($vo["aname"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
		</ul>
	</div>
</div><?php endif; ?>
	
	<?php if(!empty($searchkey)): ?><div class="w960 searchtop clear">您输入的关键字：<span class="red"><?php echo ($searchkey); ?></span>，以下是搜索结果</div><?php else: ?><div style="clear:both"></div><?php endif; ?>
<?php if(($CFG['contact_extend']) == "1"): ?><script language="javascript" type="text/javascript" src="/Public/Js/dialog.js"></script>
<link href="/Public/Css/dialog.css" rel="stylesheet" type="text/css" /><?php endif; ?>
<?php if(($CFG["loadtype"]) == "0"): ?><div class="w960 pagetop clear"><?php echo ($page); ?></div><?php endif; ?>
<div class="w960 center clear mt1">
	<ul class="infocont" id="container">
	<?php $infocount=0; ?>
	<?php if($havetop == 1): if(is_array($toplist)): $i = 0; $__LIST__ = $toplist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$top): $mod = ($i % 2 );++$i; $thisrow=(int)($infocount/5);$thisbg=$thisrow%2; ?>
		<li class="post sj_<?php echo ($top["topnum"]); ?> color<?php echo ($thisbg); ?>">
		<?php if($top["id"] == 0): ?><div class="info_title blue">置顶广告</div>
			<div class="info_cont info_ad" <?php if(($CFG["water"]) == "0"): ?>style="height:<?php echo ($CFG["cell_height"]); ?>px"<?php endif; ?>>
				广告位招租，详情请咨询<BR>咨询QQ：<?php echo (C("site_qq")); ?><BR>电话：<?php echo (C("site_tel")); ?>
			</div>
			<div class="info_cate"><?php echo (C("site_name")); ?></div>
			<div class="info_date"><span class="red">置顶信息</span></div>
		<?php else: ?>
			<div class="info_title">
				<a href="<?php echo U('Info/detail?id='.$top['id']);?>" target="_blank"><font color="<?php echo ($top["toptitle"]); ?>"><?php echo ($top["title"]); ?></font></a>
			</div>
			<div class="info_cont" <?php if(($CFG["water"]) == "0"): ?>style="height:<?php echo ($CFG["cell_height"]); ?>px"<?php endif; ?>>
				<?php if(($top['ispic']) == "1"): ?><img src="<?php echo ($top["picurl"]); ?>">
				<?php else: ?><p class="txt"><?php echo ($top["content"]); if(($CFG['contact_extend'] == 1) AND ($top['extend'] == 1)): echo getExtend($top['contact'], $CFG, 1); endif; ?></p><?php endif; ?>
			</div>
			<div class="info_cate">
				<?php echo (getgrade($top["amount"])); ?>
				<?php if(($CFG['contact_extend'] == 1) AND ($top['extend'] == 1)): ?><span class="extend"><?php echo getExtend($top['contact'], $CFG);?></span><?php endif; ?>
			</div>
			<div class="info_date"><span class="red">置顶信息</span>&nbsp;&nbsp;<?php echo (getexpired($top["toptotime"])); if($vo["detail"] != ''): ?>&nbsp;<a href="<?php echo U('Info/detail?id='.$top['id']);?>" target="_blank" title="查看详细信息"><img src="__PUBLIC__/Images/icon/xiang.png" height="15" alt="信息详情" align="absmiddle" /></a><?php endif; ?></div><?php endif; ?>
		</li>
		<?php $infocount += $top['topnum']; endforeach; endif; else: echo "" ;endif; endif; ?>
	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; $thisrow=(int)($infocount/5);$thisbg=$thisrow%2; ?>
		<li class="post sj_<?php echo ($vo["topnum"]); ?> color<?php echo ($thisbg); ?>">
			<div class="info_title">
				<a href="<?php echo U('Info/detail?id='.$vo['id']);?>" target="_blank"><font color="<?php echo ($vo["toptitle"]); ?>"><?php echo ($vo["title"]); ?></font></a>
			</div>
			<div class="info_cont" <?php if(($CFG["water"]) == "0"): ?>style="height:<?php echo ($CFG["cell_height"]); ?>px"<?php endif; ?>>
				<?php if(($vo['ispic']) == "1"): ?><img src="<?php echo ($vo["picurl"]); ?>">
				<?php else: ?><p class="txt"><?php echo ($vo["content"]); if(($CFG['contact_extend'] == 1) AND ($vo['extend'] == 1)): echo getExtend($vo['contact'], $CFG, 1); endif; ?></p><?php endif; ?>
			</div>
			<div class="info_cate">
				<?php if(($vo['areaid']) == "0"): echo (C("site_area")); else: echo ($AREA[$vo['areaid']]['aname']); endif; echo ($CATEGORY[$vo['catid']]['catname']); ?>
				<?php if(($CFG['contact_extend'] == 1) AND ($vo['extend'] == 1)): ?><span class="extend"><?php echo getExtend($vo['contact'], $CFG);?></span><?php endif; ?>
			</div>
			<div class="info_date">日期：<?php echo (date("Y-m-d",$vo["edittime"])); if($vo["detail"] != ''): ?>&nbsp;<a href="<?php echo U('Info/detail?id='.$vo['id']);?>" target="_blank" title="查看详细信息"><img src="__PUBLIC__/Images/icon/xiang.png" height="15" alt="信息详情" align="absmiddle" /></a><?php endif; ?></div>
		</li>
		<?php $infocount += $vo['topnum']; endforeach; endif; else: echo "" ;endif; ?> 
	</ul>
</div>
<div class="w960 clear pagefoot"><?php echo ($page); ?></div>
<script type="text/javascript" src="__PUBLIC__/Js/jquery.min.js"></script>
<?php if(($CFG["water"]) == "1"): ?><script type="text/javascript" src="__PUBLIC__/Js/jquery.masonry.min.js"></script><?php endif; ?>
<?php if(($CFG["loadtype"]) == "1"): ?><script type="text/javascript" src="__PUBLIC__/Js/jquery.infinitescroll.min.js"></script><?php endif; ?>
<script type="text/javascript">
$(document).ready(function(){
	var $container = $('#container');
	<?php if(($CFG["water"]) == "1"): ?>$container.imagesLoaded(function(){
		$container.masonry({
			itemSelector: '.post',
			columnWidth: 1 //每两列之间的间隙为5像素
		});
	});<?php endif; ?>
	<?php if(($CFG["loadtype"]) == "1"): ?>$container.infinitescroll({
		navSelector  : '.pagefoot',    // 选择的分页导航 
		nextSelector : '.pagefoot #nextpage',  // 选择的下一个链接到（第2页）
		itemSelector : '.post',     // 选择检索所有项目
		loading: {
			finishedMsg: '没有更多的页面加载。',
			img: '/Public/Image/loadings.gif'
		}
	},function(newElements){
		<?php if(($CFG["water"]) == "1"): ?>// 隐藏新的项目，而他们正在加载
		var $newElems = $( newElements ).css({ opacity: 0 });
		
		// 确保的图像装载增加砖石布局
		$newElems.imagesLoaded(function(){	
			// 元素展示准备
			$newElems.animate({opacity:1});
			$container.masonry( 'appended', $newElems, true ); 
		});<?php endif; ?>
	});<?php endif; ?>
	$('<a href="#" id="retop">返回顶部</a>').appendTo('body').fadeOut().click(function(){
		$(document).scrollTop(0);
		$(this).fadeOut();
		return false
	});
	var $retop = $('#retop');
	function backTopLeft(){
		var btLeft = $(window).width() / 2 + 490;
		if (btLeft <= 950){
			$retop.css({ 'left': 955 })
		}else{
			$retop.css({ 'left': btLeft }) 
		}
	}
	backTopLeft();
	$(window).resize(backTopLeft);
	$(window).scroll(function(){
		if ($(document).scrollTop() === 0){
			$retop.fadeOut()
		}else{
			$retop.fadeIn()
		}
		if ($.browser.msie && $.browser.version == 6.0 && $(document).scrollTop() !== 0){
			$retop.css({ 'opacity': 1 })
		} 
	});
});
</script>
<div class="clear"></div>
	<?php if(!empty($CATEGORY)): ?><div class="box_class"> 
	<div class="box_c">
		<ul>
			<?php if(is_array($CATEGORY)): $i = 0; $__LIST__ = $CATEGORY;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo ($vo["url"]); ?>"><?php echo ($vo["catname"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
			<?php if(is_array($AREA)): $i = 0; $__LIST__ = $AREA;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="warea"><a href="<?php echo ($vo["url"]); ?>"><?php echo ($vo["aname"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
		</ul>
	</div>
</div><?php endif; ?>
	
	<div style="clear:both"></div>
	<div class="warning red">郑重提示：<?php echo (C("site_name")); ?>是一个公益性的信息发布平台，我们对您的交易不做担保！建议大家当面交易，交易需谨慎，以免上当！</div>
	<?php if($havelink == 1): ?><div style="clear:both"></div><?php echo W('ShowLink'); endif; ?>
	<div style="clear:both"></div>
	<div class="w960 tc copyright">
		<?php echo W('ShowAbout');?>
		联系电话：<font color="#0000FF"><?php echo (C("site_tel")); ?></font>&nbsp;&nbsp;&nbsp;客服QQ：<a target="_blank" href="http://wpa.qq.com/msgrd?V=3&Uin=<?php echo (C("site_qq")); ?>&Site=www.life0573.com&Menu=yes"><font color="#0000FF"><?php echo (C("site_qq")); ?></font></a><br />
		<a href="<?php echo (C("site_url")); ?>"><?php echo (C("site_name")); ?></a>&nbsp;&nbsp;&nbsp;<?php echo (C("site_copyright")); ?>&nbsp;&nbsp;&nbsp;<?php echo (C("site_icpno")); ?>
	</div>
	<div style="display:none"><script type="text/javascript" src="__PUBLIC__/ads/tongji.js"></script></div>
</div>
</body>
</html>