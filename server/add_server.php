<?php
/**
 * Created by PhpStorm.
 * User: ziziliang
 * Date: 2017/2/16
 * Time: 下午2:33
 */

include_once './config.inc.php';
include_once './library/OAuthStore.php';

$store = OAuthStore::instance('MySQL', $dbOptions);

// 当前用户的ID, 必须为整数
$user_id = 1;

// 服务器描述信息
$server = array(
    'consumer_key'      => '35dea30fe2d7d36991235c0f4b8df591058a5445b',
    'consumer_secret'   => '3f34c77752767636ec28bc2fc4fb279a',
    'server_uri'        => 'http://o.zzlphp.com/',
    'signature_methods' => array('HMAC-SHA1', 'PLAINTEXT'),
    'request_token_uri' => 'http://o.zzlphp.com/request_token.php',
    'authorize_uri'     => 'http://o.zzlphp.com/authorize.php',
    'access_token_uri'  => 'http://o.zzlphp.com/access_token.php'
);

// 将服务器信息保存在 OAuthStore 中
$consumer_key = $store->updateServer($server, $user_id);
?>