<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh" lang="zh">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="">
<meta name="keywords" content="">
<title><?php if($site_title != ''): echo ($site_title); endif; echo ($extend_title); ?> - <?php echo (C("site_name")); ?></title>
<meta name="keywords" content="<?php echo (C("site_keywords")); ?>">
<meta name="description" content="<?php echo (C("site_description")); ?>">
<script type="text/javascript" src="__PUBLIC__/Js/shouji.js"></script>
<script language="javascript" type="text/javascript" src="__PUBLIC__/Js/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="__PUBLIC__/Js/formvalidator.js" charset="UTF-8"></script>
<script language="javascript" type="text/javascript" src="__PUBLIC__/Js/formvalidatorregex.js" charset="UTF-8"></script>
<script language="javascript" type="text/javascript" src="__PUBLIC__/Js/dialog.js"></script>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/dialog.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/style.css"/>
<script language="JavaScript">
<!--
function fleshVerify(){
	//重载验证码
	var timenow = new Date().getTime();
	<?php if((C("URL_MODEL")) == "0"): ?>document.getElementById('verifyImg').src = "<?php echo U('Public/verify');?>"+"&"+timenow;
	<?php else: ?>
	document.getElementById('verifyImg').src = "<?php echo U('Public/verify');?>"+"<?php echo (C("URL_PATHINFO_DEPR")); ?>"+timenow;<?php endif; ?>
}
//-->
</script>
</head>
<body class='body'>
<div id="warp">
	<div class="head">
		<div class="w930 cent">
			<div class="head1">
				<a href="<?php echo (C("site_url")); ?>">网站首页</a><?php if(is_array($navigation)): $i = 0; $__LIST__ = $navigation;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo ($vo["url"]); ?>"><?php echo ($vo["name"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
			</div>
			<div class="head2" id="userline"><script type="text/javascript" src="<?php echo U('Member/Public/userline');?>"></script></div>
		</div>
	</div>
	<div class="w960 head3" style="border-bottom: solid #DBE0E3 1px;">
		<div class="logo"><a href="<?php echo (C("site_url")); ?>"><img src="__PUBLIC__/Images/logo.gif" alt="<?php echo (C("site_name")); ?>" /></a></div>
		<div class="head3-1"><?php echo ($extend_title); ?></div><?php if(isset($extend_oauth)): ?><div class="head3-oauth"><?php echo W('ShowOauth');?></div><?php endif; ?>
	</div>
<div style="clear:both"></div>
<div class="w960 center clear mt1">
    <div class="pleft">
        <div class="weiz"><h2>
                当前位置：<a href="/" >首页</a>&nbsp;>&nbsp;<a href="<?php echo U('Article/index');?>"><?php echo ($modulename); ?></a>
                <?php if($row['areaid'] != 0): ?>&nbsp;>&nbsp;<a href="<?php echo ($AREA[$row['areaid']]['url']); ?>"><?php echo ($AREA[$row['areaid']]['aname']); ?></a><?php endif; ?>
                <?php if($row['catid'] != 0): ?>&nbsp;>&nbsp;<a href="<?php echo ($CATEGORY[$row['catid']]['url']); ?>"><?php echo ($CATEGORY[$row['catid']]['catname']); ?></a><?php endif; ?>
                &nbsp;>&nbsp;<?php echo ($row['title']); ?>
            </h2></div>
        <UL class="video">
            <LI class="ads"><script type="text/javascript" src="__PUBLIC__/ads/detail1.js"></script></LI>
            <LI class="subject"><?php echo ($row['title']); ?></LI>
            <LI class="msg">发布时间: <?php echo (date("Y-m-d",$row["edittime"])); ?></LI>
            <?php if($row['keyword'] != ''): ?><LI class="msg">关&nbsp;键&nbsp;字: <a href="<?php echo U('Article/search?key='.$row['keyword']);?>"><?php echo ($row['keyword']); ?></a></LI><?php endif; ?>
            <?php if($row['linkurl'] != ''): ?><LI class="msg">相关链接: <a href="<?php echo ($row['linkurl']); ?>" title="<?php echo ($row['keyword']); ?>" target="_blank"><?php echo ($row['linkurl']); ?></a></LI><?php endif; ?>
            <?php if($row['uid'] != 0): ?><LI class="msg">公司名称: <?php echo ($user['company']); ?>&nbsp;&nbsp;<?php echo (getgrade($user["amount"])); ?></LI>
                <LI class="msg">公司地址: <?php echo ($user['address']); ?></LI>
                <LI class="msg">联&nbsp;系&nbsp;&nbsp;人: <?php echo ($user['contacter']); ?></LI>
                <LI class="msg">联系电话: <?php echo ($user['tel']); ?></LI>
                <?php elseif($row['extend'] == 1): ?>
                <LI class="msg">联&nbsp;系&nbsp;&nbsp;人: <?php echo ($row['contact']['contacter']); ?></LI>
                <LI class="msg">联系电话: <?php echo ($row['contact']['tel']); ?></LI><?php endif; ?>
            <?php if(($CFG['contact_extend'] == 1) AND ($row['extend'] == 1)): ?><LI class="msg">在线交流:
                <?php if(($CFG['contact_qq'] == 1) AND ($row['contact']['qq'] != '')): ?><a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo ($row['contact']['qq']); ?>&site=qq&menu=yes" target="_blank" rel="nofollow"><img src="http://wpa.qq.com/pa?p=1:<?php echo ($row['contact']['qq']); ?>:4" title="点击QQ交谈/留言" align="absmiddle" onerror="this.src='__PUBLIC__/Images/icon/qq-off.gif'"/></a>&nbsp;<?php endif; ?>
                <?php if(($CFG['contact_ali'] == 1) AND ($row['contact']['ali'] != '')): ?><a href="http://amos.im.alisoft.com/msg.aw?v=2&uid=<?php echo ($row['contact']['ali']); ?>&site=cnalichn&s=6" target="_blank" rel="nofollow"><img src="http://amos.im.alisoft.com/online.aw?v=2&uid=<?php echo ($row['contact']['ali']); ?>&site=cnalichn&s=6" title="点击旺旺交谈/留言" align="absmiddle" onerror="this.src='__PUBLIC__/Images/icon/ali-off.gif'" onload="if(this.width>20)this.src='__PUBLIC__/Images/icon/ali-off.gif'"/></a>&nbsp;<?php endif; ?>
                <?php if(($CFG['contact_msn'] == 1) AND ($row['contact']['msn'] != '')): ?><a href="msnim:chat?contact=<?php echo ($row['contact']['msn']); ?>" rel="nofollow"><img src="__PUBLIC__/Images/icon/msn.gif" width="16" height="16" title="点击MSN交谈/留言" align="absmiddle"/></a><?php endif; ?>
                </LI><?php endif; ?>
        </UL>
        <dl class="ckzl clear" >
            <div class="content"><?php if(($row["ispic"]) == "1"): ?><img src="<?php echo ($row['picurl']); ?>"><?php else: echo ($row['content']); endif; ?></div>
            <dt><span class="blue fb px16">详细信息</span></dt>
            <dd class="px14"><?php echo ($row['detail']); ?></dd>
        </dl>
    </div>

    <div class="pright">
        <dl class="jingp">
            <dt>广告赞助<span></span></dt>
            <dd><script type="text/javascript" src="__PUBLIC__/ads/detail2.js"></script></dd>
        </dl>
    </div>
    <div style="clear:both"></div>
</div>
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