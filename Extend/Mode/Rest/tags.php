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

// Rest 系统行为扩展列表文件
return array(
    'app_begin'=>array(
        'CheckTemplate', // 模板检测
    ),
    'route_check'=>array(
        'CheckRestRoute', // 路由检测
    ), 
    'view_end'=>array(
        'ShowPageTrace', // 页面Trace显示
    ),
    'view_template'=>array(
        'LocationTemplate', // 自动定位模板文件
    ),
    'view_parse'=>array(
        'ParseTemplate', // 模板解析 支持PHP、内置模板引擎和第三方模板引擎
    ),
    'view_filter'=>array(
        'ContentReplace', // 模板输出替换
        'TokenBuild',   // 表单令牌
        'ShowRuntime', // 运行时间显示
    ),
    'path_info'=>array(
        'CheckUrlExt'
    ),
);