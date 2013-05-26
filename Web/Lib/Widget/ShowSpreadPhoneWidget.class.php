<?php
class ShowSpreadPhoneWidget extends Widget{
    public function render($data){
		$spread = F('config/spread_phone');
		$show = '';
		foreach($spread as $v) {
			if($v['tel']!='' && $v['mobile']!='') $demlit = '、';
			else $demlit = '';
			$show .= '<dd><ul><li><a href="'.U('Phone/detail?id='.$v['id']).'" target="_blank">'.$v['title'].'</a></li><li><span>电话：'.$v['tel'].$demlit.$v['mobile'].'</span></li></ul></dd>';
		}
        return $show;
    } 
}