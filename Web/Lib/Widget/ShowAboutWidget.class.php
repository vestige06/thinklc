<?php
class ShowAboutWidget extends Widget{
    public function render($data){
		$module = F('config/module');
		$show = '';
		$pre = C('URL_MODEL') ? '/' : 'Home/';
		if($module[3]['status']) {
			$about = F('config/webpage');
			foreach($about as $v) {
				if($data['type']) $show .= '<dt><a href="'.U($pre.'About/'.$v['alias']).'">'.$v['title'].'</a></dt>';
				else $show .= '<a href="'.U($pre.'About/'.$v['alias']).'">'.$v['title'].'</a>&nbsp;&nbsp;|&nbsp;&nbsp;';
			}
			if($data['type'])  return $show;
		}
		if($module[2]['status']) $show .= '<a href="'.U($pre.'Help/usergrade').'">会员等级</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="'.U($pre.'Help/top').'">置顶价格</a>';
		$show = empty($show) ? '' : $show.'<br>';
        return $show;
    } 
}