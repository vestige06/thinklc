<?php
class ShowPostWidget extends Widget{
    public function render($data){
		$uid = session('uid');
		$gid = session('ugid');
		if(empty($uid) || empty($gid)) return;
		$module = getModule();
		$group = F('config/group_'.$gid);
		$show = '';
		foreach($module as $v) {
			if($v['id']>4) {
				$post = getLimit($uid, $v['id'], 'post');
				if(false !== $post){
					$limitcount = $group[strtolower($v['mname']).'_count'];
					$show .= 
					'<tr>
						<td align="right">'.$v['mtitle'].'统计：&nbsp;&nbsp;&nbsp;&nbsp;</td>
						<td align="left">今日总共可发布 <span class="red fb">'.$limitcount.'</span> 条，已发布 <span class="red fb">'.$post['val'].'</span> 条，还可以发布 <span class="red fb">'.($limitcount-$post['val']).'</span> 条 &nbsp;&nbsp;<a href="'.U($v['mname'].'/add').'">发布'.$v['mtitle'].'</a></td>
					</tr>';
				}
			}
		}
        return $show;
    } 
}