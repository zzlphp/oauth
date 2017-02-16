<?php
/**
 * Created by PhpStorm.
 * User: ziziliang
 * Date: 2017/2/16
 * Time: 下午1:51
 */
include_once './config.inc.php';
include_once './library/OAuthStore.php';
include_once './library/OAuthServer.php';

$store = OAuthStore::instance('MySQL', $dbOptions);

$server = new OAuthServer();
$server->requestToken();
exit();
?>