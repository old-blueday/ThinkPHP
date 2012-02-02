<?php

if (!defined('THINK_PATH'))	exit();

$config = require("../config.php");
$array = array(
	'DEFAULT_MODULE' =>	'Blog',
    'SESSION_AUTO_START'=>true,
    'APP_AUTOLOAD_PATH'=>'@.TagLib,@.ORG',
    'TOKEN_ON'  => false,
    'URL_ROUTER_ON' => true,
    'URL_ROUTE_RULES' => array(
        'cate/:id\d'                 => 'Blog/category',
        '/^Blog\/(\d+)$/is'       => 'Blog/show?id=:1',
        '/^Blog\/(\d+)\/(\d+)/is'=> 'Blog/archive?year=:1&month=:2',
    ),
    'SHOW_PAGE_TRACE'=>true,
);
return array_merge($config,$array);
?>