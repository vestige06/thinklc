<?php
// 友情链接
class ShowLinkWidget extends Widget{
    public function render($data){
		$content = '';
		$module = getModule();
		if($module[4]['status']) {
			$link = F('config/link');
			if($link && count($link)>0) {
				$data['links'] = $link;
				$data['linktype'] = F('config/etype_link');
				$content = $this->renderFile(TMPL_PATH.'Home/'.C('DEFAULT_THEME').'/Public_link.html',$data);
			}
		}
		return $content;
    } 
}