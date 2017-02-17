<?php
return array(
	//'配置项'=>'配置值'
	'DB_TYPE'               =>  'mysql',     // 数据库类型
	'DB_HOST'               =>  'localhost', // 服务器地址
	'DB_NAME'               =>  'wechat',          // 数据库名
	'DB_USER'               =>  'root',      // 用户名
	'DB_PWD'                =>  'liang123',          // 密码
	'DB_PORT'               =>  '3306',        // 端口
	'DB_PREFIX'             =>  '',    // 数据库表前缀
	'DB_CHARSET'            =>  'utf8',      // 数据库编码
	'DB_DEBUG'  =>  TRUE, // 数据库调试模式 开启后可以记录SQL日志
    //邮件配置
    'THINK_EMAIL' => array(
        'SMTP_HOST'   => 'smtp.qq.com', //SMTP服务器
        'SMTP_PORT'   => '465', //SMTP服务器端口
        'SMTP_USER'   => '280229278@qq.com', //SMTP服务器用户名
        'SMTP_PASS'   => 'xertgnbktxblbghd', //SMTP服务器密码
        'FROM_EMAIL'  => '280229278@qq.com', //发件人EMAIL
        'FROM_NAME'   => '狼灵', //发件人名称
        'REPLY_EMAIL' => '', //回复EMAIL（留空则为发件人EMAIL）
        'REPLY_NAME'  => '', //回复名称（留空则为发件人名称）
    ),
    'DB_CONFIG2' => 'mysql://root:liang123@localhost:3306/kf',
    'DB_CONFIG3' => 'mysql://root:liang123@localhost:3306/wechat',


    'SCOPE_INFO'  => array(    //这是授权选项。根据你自己的项目来
        array(
            'name' => '个人信息',
            'value' => 'basicinfo'
        ),
        array(
            'name' => '论坛发帖回帖',
            'value' => 'bbsinfo'
        ),
    ),

    'OAUTH2_CODES_TABLE'   =>'oauth_code',    //这里是oauth项目需要用的三个基础表
    'OAUTH2_CLIENTS_TABLE' =>'oauth_client',
    'OAUTH2_TOKEN_TABLE'   =>'oauth_token',

    'SECRETKYE' => 'Mumayi!@#',    //下面是一些网站自定义的项目。可以根据自己的情况来写或者不写
    //session 有效期
    'SESSION_EXPIRES' => 1200,
    //key 有效期
    'PASS_KEY_EXPIRES' => 86400,
    //key 有效期
    'PHONE_KEY_EXPIRES' => 300,
    //key 加密 整型 数字 必须为 int
    'PASS_KEY_CALC' => 1314,
);
