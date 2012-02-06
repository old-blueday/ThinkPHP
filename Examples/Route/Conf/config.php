<?php
return array(
    //ThinkPHP支持URL路由功能，要启用路由功能，需要设置URL_ROUTER_ON 参数为true
	'URL_ROUTER_ON'=>true,
	//路由定义
	'URL_ROUTE_RULES'=> array(
		'blog/:year\d/:month\d'=>'Blog/archive', //规则路由
		'blog/:id\d'=>'Blog/read', //规则路由
		'blog/:cate'=>'Blog/category', //规则路由
		
		'/(\d+)/' => 'Blog/view?id=:1',//正则路由
	),
);
?>