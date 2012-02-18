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
// $Id: alias.php 478 2012-01-14 03:40:29Z luofei614@126.com $
if (!defined('THINK_PATH')) exit();
// 系统别名定义文件
return array(
    'Model'         => CORE_PATH.'Core/Model.class.php',
    'Db'            => CORE_PATH.'Core/Db.class.php',
    'Log'          =>   THINK_PATH.'Sae/Log.class.php',
    'ThinkTemplate' => THINK_PATH.'Sae/ThinkTemplate.class.php',
    'TagLib'        => CORE_PATH.'Template/TagLib.class.php',
    'Cache'         => CORE_PATH.'Core/Cache.class.php',
    'Widget'         => CORE_PATH.'Core/Widget.class.php',
    'TagLibCx'      => CORE_PATH.'Driver/TagLib/TagLibCx.class.php',
);