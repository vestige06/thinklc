<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>ThinkLC地方分类管理系统</title>
        <link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/admin_login.css" />
        <script language="JavaScript">
            <!--
            function fleshVerify(){
                //重载验证码
                var timenow = new Date().getTime();
                document.getElementById('verifyImg').src = "<?php echo U('Public/verify');?>"+"?"+timenow;
            }
            //-->
        </script>
    </head>
    <body id="login">
        <div class="login">
            <div class="login_form">
                <div class="form_info">
                    <form method="post" action="<?php echo U('Public/checklogin');?>">
                        <div class="field">
                            <label for="aname">用户名：</label>
                            <input type="text" class="text" size="20" id="aname" name="aname" />
                        </div>
                        <div class="field">
                            <label for="apwd">密　码：</label>
                            <input type="password" class="text" size="20" id="apwd" name="apwd" />
                        </div>
                        <div class="field">
                            <label for="verify">验证码：</label>
                            <input type="text" class="text" size="10" id="verify" name="verify" />
                                <cite class="yzm"><img id="verifyImg" src="<?php echo U('Public/verify');?>" onClick="fleshVerify()" border="0" alt="点击刷新验证码" style="cursor:pointer" align="absmiddle" /></cite>
                        </div>
                        <div class="field">
                            <label></label>
                            <input class="button" style="margin-left:50px;_margin-left:48px" type="submit" value=" " />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>