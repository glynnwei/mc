<?php
// 暂无index跳转到管理
header("Location:admin.php");

// 应用入口文件
include(dirname(__FILE__) . "/global.php");

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',True);

// 绑定模块到当前入口文件
define('BIND_MODULE','Home');

// 定义应用目录
define('APP_PATH','./App/');

// 缓存目录设置
define ('RUNTIME_PATH', './Runtime/' );

// 引入ThinkPHP入口文件
require FRAME_PATH.'/ThinkPHP/ThinkPHP.php';
