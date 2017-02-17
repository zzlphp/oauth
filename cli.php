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
define( 'APP_MODE', 'cli');
require dirname( __FILE__).'/ThinkPHP/ThinkPHP.php';
