<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="keywords" content="子良 张子良 280229278" />
<meta name="description" content="子良个人网站 张子良个人网站 280229278 子良 张子良 zzlphp php zzl">
<meta name="author" content="子良个人网站">
<title>子良个人网站</title>


<script type="text/javascript" src="/Public/js/login.js"></script>
<script type="text/javascript" src="/Public/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="/Public/js/checkcard.js"></script>
	  
	  
</head>
<style type="text/css">

    .container{
        width: 960px;
        margin: 0 auto;
    }
    .content{
        width: 960px;
        margin: 10 auto;

    }
    .box{
        width:300px;
        margin: 30px auto;
    }
    .header{
        margin: 80px auto 30px auto;
        text-align: center;
        font-size: 34px;
    }
    input{
        width: 200px;
        padding: 6px 9px;
    }
    button{
        cursor: pointer;
        line-height: 35px;
        width: 110px;
        margin:30px 0 0 90px;
        border: 1px solid #FFFFF0;
        background-color: #31C552;

        border-radius: 4px;
        font-size: 14px;
        color: #FFFFF0;
    }
</style>
<body>
    <div style="width: 100%; height:100px;">
    </div>
    <div class="content" style="width: 1000px;height: 500px;margin: 0 auto;">
        <form method="post" action="#">
            <div class="box">
                <label>用户</label>
                    <input type="text" id="username" name="username" value="test"/>
            </div>
            <div class="box">
                <label>密码</label>
                    <input type="password" id="password" name="password" value="111111"/>
            </div>
            <div class="box">
                <?php echo ($res); ?>
            </div>
            <div class="box">
                <button id="submit_button">登陆</button>
            </div>
        </form>
    </div>
<script language="JavaScript">
$(function(){
    $("#submit_button").click(function() {
        var name = $("#username").val();
        var pwd = $("#password").val();
        var geetest_challenge = $("input[name='geetest_challenge']").val();
        var geetest_validate = $("input[name='geetest_validate']").val();
        var geetest_seccode = $("input[name='geetest_seccode']").val();


        $.post('/home/login/sublog/',{'username':name,'password':pwd,'geetest_challenge':geetest_challenge,'geetest_validate':geetest_validate,'geetest_seccode':geetest_seccode},function(data){
            var response = $.parseJSON(data);
            if(response.num=='success'){
                window.location='/home/list/index';
                //alert(response.msg);
            }else{
                alert(response.msg);
            }
        });
    });
});
</script>
</body>
</html>