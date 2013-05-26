<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'><html><head><title>页面提示</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta http-equiv='Refresh' content='<?php echo ($waitSecond); ?>;URL=<?php echo ($jumpUrl); ?>'><style>html, body{margin:0; padding:0; border:0 none;font:14px Tahoma,Verdana;line-height:150%;background:#F2F2F2;}
body{ font-size:12px;font-family: \5FAE\8F6F\96C5\9ED1, \5B8B\4F53, \5E7C\5706, Arial, Verdana; color: #333; }
a{color:blue;}
.public-success .title{ font-size: 24px;line-height: 2em; color: #459C01; border-bottom: 1px solid #EEEEEE; }
.public-success .content{line-height: 2em;color:#7D7D7D;font-size: 14px;}
.public-success .content .status{}
.public-success .content .message{font-size: 18px;font-weight: bold;color:#616161;}
.public-success .content .light{color:#FF5A00;}
.public-success .content .light:hover{border-bottom: solid 1px #FF5A00;}
<?php if(($_REQUEST['doclose']) == "1"): ?>.layout-prompt{margin:0px auto 0px auto;}
.layout-prompt .prompt{margin:0px auto;}
<?php else: ?>.layout-prompt{background: #F2F2F2;padding:12px 0px;margin:10% auto 0px auto;}
.layout-prompt .prompt{background: #FFF;margin:0px auto;border: 1px solid #CDCDCD;max-width:680px;min-height: 300px;}<?php endif; ?>.layout-prompt .prompt-center{margin:12px 0px 0px 24px;}
.fl { display: inline; float: left!important; }/*左浮动*/
.ml{ margin-left: 12px; }
.mt{ margin-top: 12px; }
.cf { zoom: 1; }/*清除浮动*/
</style></head><body><div class="public-success layout-prompt"><div class="prompt"><div class="prompt-center"><div class="title">信息提示</div><div class="content cf mt"><div class="fl mt"><?php if(isset($message)): ?><img src="__PUBLIC__/Images/img_success.gif" alt="操作成功！" /><?php else: ?><img src="__PUBLIC__/Images/img_error.gif" alt="操作失败！" /><?php endif; ?></div><div class="fl ml"><div class="message"><?php if(isset($message)): ?><span class="success"><?php echo ($msgTitle); echo ($message); ?></span><?php else: ?><span class="error"><?php echo ($msgTitle); echo ($error); ?></span><?php endif; ?></div><div><?php if(isset($closeWin)): ?>系统将在 <span class="light"><?php echo ($waitSecond); ?></span> 秒后自动关闭，如果不想等待请点击 <a class="light" href="<?php echo ($jumpUrl); ?>">这里</a> 关闭<br/><?php else: ?>						系统将在 <span class="light"><?php echo ($waitSecond); ?></span> 秒后自动跳转，如果不想等待请点击 <a class="light" href="<?php echo ($jumpUrl); ?>">这里</a> 跳转<br/><?php endif; ?></div><div>或者 <a class="light" href="__APP__">返回首页</a></div></div></div></div></div></div></body></html>