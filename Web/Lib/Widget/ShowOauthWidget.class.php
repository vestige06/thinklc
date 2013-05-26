<?php
class ShowOauthWidget extends Widget{
    public function render($data){
		$oauth = F('config/oauth');
		$show = '';
		$num = 0;
		foreach($oauth as $k => $v) {
			if($v['enable']) {
				$num++;
				if($data['type']) $show .= '<a href="'.U('Member/Oauth/connect?site='.$k).'"><img src="__PUBLIC__/Images/oauth/'.$k.'.png" align="absmiddle">'.$v['name'].'</a>&nbsp;&nbsp;|&nbsp;&nbsp;';
				elseif($num<3) $show .= '&nbsp;&nbsp;&nbsp;&nbsp;<a href="'.U('Member/Oauth/connect?site='.$k).'"><img src="__PUBLIC__/Images/oauth/'.$k.'login.png" align="absmiddle"></a>';
			}
		}
        return $show;
    } 
}