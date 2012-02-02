<?php
    if (!defined('THINK_PATH')) exit();
    $config = require '../config.php';
    $array = array(
        'DATA_CACHE_TYPE' => 'file', // 数据缓存方式 文件
        'DATA_CACHE_TIME' => 10, // 数据缓存有效期 10 秒
        'DATA_CACHE_SUBDIR' => true,
        'DATA_PATH_LEVEL' => 2,
        'SHOW_PAGE_TRACE' => 1, //显示调试信息
    );
    return array_merge($config, $array);
?>