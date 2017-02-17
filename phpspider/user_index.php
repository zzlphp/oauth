<?php
//设置时区
date_default_timezone_set('Asia/Shanghai');
ini_set('display_errors', 1);
include "config/inc_config.php";
include "config/inc_mimetype.php";

include "library/cls_curl.php";
include "core/db.php";
include "core/cache.php";
include "core/worker.php";
include "user.php";

//save_user_index($w);
//exit;
//$username = "yangzetao";
//$data = get_user($username);
//echo '<pre>';print_r($data);echo '</pre>';
//exit("\n");

$w = new worker();
$w->count = 8;
$w->is_once = true;

$count = 100000;        // 每个进程循环多少次
$w->on_worker_start = function($worker) use ($count) {

    //$progress_id = posix_getpid();

    for ($i = 0; $i < $count; $i++) 
    {
        save_user_index($worker);
    }
}; 

$w->run();
