<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------


//xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
// 应用入口文件

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');


// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',True);

//// 定义应用目录
//define('APP_PATH','./Application/');
//
//// 引入ThinkPHP入口文件
//require './ThinkPHP/ThinkPHP.php';

define( 'APP_PATH', dirname(__FILE__).'/Application/' );
require dirname( __FILE__).'/ThinkPHP/ThinkPHP.php';



/*
$xhprof_data = xhprof_disable();
include_once "xhprof_lib/utils/xhprof_lib.php";
include_once "xhprof_lib/utils/xhprof_runs.php";

$xhprof_runs = new XHProfRuns_Default();
$run_id = $xhprof_runs->save_run($xhprof_data, "xhprof");


$href = "http://www.zzlphp.com/xhprof_html/index.php?run=".$run_id."&source=xhprof";

//echo "<a href='".$href."'>性能测试</a>";
//echo "http://test.zzlphp.com/xhprof_html/index.php?run=".$run_id."&source=xhprof";
// 亲^_^ 后面不需要任何代码了 就是如此简单
*/