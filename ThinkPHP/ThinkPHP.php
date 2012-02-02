<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

// ThinkPHP 入口文件

//记录开始运行时间
$GLOBALS['_beginTime'] = microtime(TRUE);
// 记录内存初始使用
define('MEMORY_LIMIT_ON',function_exists('memory_get_usage'));
if(MEMORY_LIMIT_ON) $GLOBALS['_startUseMems'] = memory_get_usage();
if(!defined('APP_PATH')) define('APP_PATH', dirname($_SERVER['SCRIPT_FILENAME']).'/');
if(!defined('RUNTIME_PATH')) define('RUNTIME_PATH',APP_PATH.'Runtime/');
if(!defined('APP_DEBUG')) define('APP_DEBUG',false); // 是否调试模式
$runtime = defined('MODE_NAME')?'~'.strtolower(MODE_NAME).'_runtime.php':'~runtime.php';
if(!defined('RUNTIME_FILE')) define('RUNTIME_FILE',RUNTIME_PATH.$runtime);
if(!APP_DEBUG && is_file(RUNTIME_FILE)) {
    // 部署模式直接载入allinone缓存
    require RUNTIME_FILE;
}else{
    if(version_compare(PHP_VERSION,'5.2.0','<'))  die('require PHP > 5.2.0 !');
    // ThinkPHP系统目录定义
    if(!defined('THINK_PATH')) define('THINK_PATH', dirname(__FILE__).'/');
    if(!defined('APP_NAME')) define('APP_NAME', basename(dirname($_SERVER['SCRIPT_FILENAME'])));
    // 加载运行时文件
    require THINK_PATH."Common/runtime.php";
    // 记录加载文件时间
    G('loadTime');
    // 执行入口
    Think::Start();
}