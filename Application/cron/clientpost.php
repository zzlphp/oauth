<?php
/**
 * @Author: zhangziliang
 * @Date:   2015-05-07 16:38:55
 * @Last Modified by:   zhangziliang
 * @Last Modified time: 2015-05-07 17:10:00
 */
$cookieJar = tempnam('c:/csdn/','csdn_'); //cookie存放地址
$requestUrl = 'http://passport.csdn.net/ajax/accounthandler.ashx?t=log';//登录地址
$requestFetchUrl = "http://hi.csdn.net/cp.php?ac=theme&op=use&dir=t14";//要抓取的页面，这里是设置主题为t14，执行完之后看空间效果
$requestData = array(
        'u'=>'pan269', //用户名
        'p'=>'qw13165',//密码
        'f'=>'http%3A//passport.csdn.net/account/login',
        'cb'=>'logined',
        'remember'=>0,
        'c'=>'',
);
$postFields = http_build_query($requestData);
$requestUrl .= '&'.$postFields;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $requestUrl);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_NOBODY, false);
$rs1 = curl_exec($ch);
if($rs1 == false) {
        var_dump(curl_error($ch));
}
curl_close($ch);

$ch2 = curl_init();
curl_setopt($ch2, CURLOPT_URL, $requestFetchUrl);
curl_setopt($ch2, CURLOPT_HEADER, false);
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_COOKIEFILE, $cookieJar);
$rs2 = curl_exec($ch2);
curl_close($ch2);
