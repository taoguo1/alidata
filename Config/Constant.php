<?php
\define('APP_DEBUG', false);
\define('DEFAULT_CONTROLLER', 'index'); // www访问入口
\define('DEFAULT_ACTION', 'index'); // www访问入口
\define('APP_WWW', 'www'); // www访问入口
\define('APP_ADMIN', 'admin'); // 后台访问入口
\define('APP_API', 'api'); // api访问入口
\define('APP_CALL', 'call'); // api访问入口

\define('APP_SITE_PATH', '/'); // 站点根目录
\define('APP_ADMIN_STATIC', APP_SITE_PATH . 'Static/Admin/'); // 站点根目录
\define('APP_ADMIN_PUBLIC_CONTROLLER', 'Upload|Login|Index|ChangePwd');
//IP白名单
\define('SAFE_IPS','39.106.136.214,47.94.245.78,60.205.206.165,123.139.20.13,127.0.0.1');
\define('OEM_SITE','https://oem.dizaozhe.cn/');
\define('OSS_ENDDOMAIN', 'https://oem-pro.oss-cn-beijing.aliyuncs.com');

\define('ZF_URL','http://pay.dizaozhe.cn/product/PAF/');
\define('EX_DOMAIN', 'https://exchange-dev.dizaozhe.cn/');   //exchange
\define('OEM_CTRL_URL', 'https://manage-dev.dizaozhe.cn/');  //OEM主控制台url
\define('OEM_CTRL_URL_VERSION', 'V100');//OEM主控制台url 版本
\define('HK_SINGLE', 1); //还款笔数费，按对应还款所消费的笔数来收
//redis
\define('REDIS',[
    'token' => [
        'host' => 'r-2ze613db03308134.redis.rds.aliyuncs.com',
        'port' =>  6379,
        'username' => 'r-2ze613db03308134',
        'password' => 'Dzz123456',
        'select' => 0,
        'timeout' => 0,
        'expire' => 0,
        'persistent' => false,
        'prefix' => ''
    ],
    'plan' => [
        'host' => 'r-2ze613db03308134.redis.rds.aliyuncs.com',
        'port' => '6379',
        'username' => 'r-2ze613db03308134',
        'password' => 'Dzz123456',
        'select' => 0,
        'timeout' => 0,
        'expire' => 0,
        'persistent' => false,
        'prefix' => ''
    ],
    'msg' => [
        'host' => 'r-2ze613db03308134.redis.rds.aliyuncs.com',
        'port' => '6379',
        'username' => 'r-2ze613db03308134',
        'password' => 'Dzz123456',
        'select' => 0,
        'timeout' => 0,
        'expire' => 0,
        'persistent' => false,
        'prefix' => ''
    ]
]);
