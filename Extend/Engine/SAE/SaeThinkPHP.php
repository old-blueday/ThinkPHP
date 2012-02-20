<?php

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id: SaeThinkPHP.php 668 2012-01-17 09:44:19Z luofei614@126.com $
// Sae版ThinkPHP 入口文件
//[sae]判断是否运行在SAE上。
if (!isset($_SERVER["HTTP_APPCOOKIE"])) {
    define("IS_SAE", FALSE);
    if (!defined('THINK_PATH'))
        define('THINK_PATH', dirname(__FILE__) . '/');
    if (!defined('APP_PATH'))
        define('APP_PATH', dirname($_SERVER['SCRIPT_FILENAME']) . '/');
    //加载平滑函数
    require THINK_PATH.'Sae/sae_functions.php';
    //加载模拟器
    if (!defined('SAE_APPNAME'))
        require THINK_PATH . 'Sae/SaeImit.php';
    require THINK_PATH . 'ThinkPHP.php';
    exit();
}
define("IS_SAE", TRUE);
require dirname(__FILE__).'/Sae/SaeMC.class.php';
//记录开始运行时间
$GLOBALS['_beginTime'] = microtime(TRUE);
// 记录内存初始使用
define('MEMORY_LIMIT_ON', function_exists('memory_get_usage'));
if (MEMORY_LIMIT_ON)
    $GLOBALS['_startUseMems'] = memory_get_usage();
if (!defined('APP_PATH'))
    define('APP_PATH', dirname($_SERVER['SCRIPT_FILENAME']) . '/');
//[sae] 判断是否手动建立项目目录
if (!is_dir(APP_PATH . "/Lib/")) {
    header("Content-Type:text/html; charset=utf-8");
    exit('<div style=\'font-weight:bold;float:left;width:430px;text-align:center;border:1px solid silver;background:#E8EFFF;padding:8px;color:red;font-size:14px;font-family:Tahoma\'>sae环境下请手动生成项目目录~</div>');
}
if (!defined('RUNTIME_PATH'))
    define('RUNTIME_PATH', APP_PATH . 'Runtime/');
if (!defined('APP_DEBUG'))
    define('APP_DEBUG', false); // 是否调试模式
$runtime = defined('MODE_NAME') ? '~' . strtolower(MODE_NAME) . '_runtime.php' : '~runtime.php';
if (!defined('RUNTIME_FILE'))
    define('RUNTIME_FILE', RUNTIME_PATH . $runtime);
//[sae] 载入核心编译缓存
if (!APP_DEBUG && SaeMC::file_exists(RUNTIME_FILE)) {
    // 部署模式直接载入allinone缓存
    SaeMC::include_file(RUNTIME_FILE);
} else {
    if (version_compare(PHP_VERSION, '5.2.0', '<'))
        die('require PHP > 5.2.0 !');
    // ThinkPHP系统目录定义
    if (!defined('THINK_PATH'))
        define('THINK_PATH', dirname(__FILE__) . '/');
    if (!defined('APP_NAME'))
        define('APP_NAME', basename(dirname($_SERVER['SCRIPT_FILENAME'])));
    //[sae] 加载运行时文件
    require THINK_PATH . "Sae/runtime.php";
    // 记录加载文件时间
    G('loadTime');
    // 执行入口
    Think::Start();
}