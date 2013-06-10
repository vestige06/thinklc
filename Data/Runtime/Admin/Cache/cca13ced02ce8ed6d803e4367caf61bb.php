<?php if (!defined('THINK_PATH')) exit(); if(is_array($menu_2)): $i = 0; $__LIST__ = $menu_2;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="left_title cu on"><?php echo ($vo["mtitle"]); ?></div>
<ul class="side">
	<?php if(is_array($vo['menu_3'])): $i = 0; $__LIST__ = $vo['menu_3'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub): $mod = ($i % 2 );++$i; if(($vo['id']) == "7"): ?><li id="_MP<?php echo ($sub["mid"]); ?>" class="sub_menu"><a href="javascript:_MP(<?php echo ($sub["mid"]); ?>,'<?php echo ($sub['url']); ?>');" hidefocus="true" style="outline:none;"><?php echo ($sub["mtitle"]); ?></a></li>
	<?php else: ?>
	<li id="_MP<?php echo ($sub["id"]); ?>" class="sub_menu"><a href="javascript:_MP(<?php echo ($sub["id"]); ?>,'<?php echo U($sub['mname'].'/'.$sub['aname'].'?'.$sub['parameter']);?>');" hidefocus="true" style="outline:none;"><?php echo ($sub["mtitle"]); ?></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?> 
</ul><?php endforeach; endif; else: echo "" ;endif; ?> 
<script type="text/javascript">
$(".left_title").each(function(i){
	var ul = $(this).next();
	$(this).click(
	function(){
		if(ul.is(':visible')){
			ul.hide();
			$(this).removeClass('on');
			$(this).addClass('off');
		}else{
			ul.show();
			$(this).removeClass('off');
			$(this).addClass('on');
		}
	})
});
</script>