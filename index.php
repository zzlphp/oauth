<?php
/**
 * Created by PhpStorm.
 * User: ziziliang
 * Date: 2017/2/16
 * Time: 下午2:07
 */

if(isset($_GET['req']) && ($_GET['req'] == 1)){
    include_once 'config.inc.php';
    include_once './library/OAuthStore.php';
    include_once './library/OAuthRequester.php';

    $store = OAuthStore::instance('MySQL', $dbOptions);

    // 用户Id, 必须为整型
    $user_id = 1;

    // 消费者key
    $consumer_key = '35dea30fe2d7d36991235c0f4b8df591058a5445b';

    // 从服务器获取未授权的token
    $token = OAuthRequester::requestRequestToken($consumer_key, $user_id);
    var_dump($token);
    die();
}
else{
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>测试页面</title>
    </head>

    <body>
    <p>消费放测试页面，点击下面的按钮开始测试</p>
    <input type="button" name="button" value="Click Me" id="RequestBtn"/>
    <script type="text/javascript">
        document.getElementById('RequestBtn').onclick = function(){
            window.location = 'index.php?req=1';
        }
    </script>

    </body>
    </html>
    <?php
}
?>