<?php
defined('SP') or exit();
return array(
	//开启
    	'APP_GROUP_DENY'=>'home1',
	    'APP_SUB_DOMAIN_DEPLOY' => true,   // 是否开启子域名部署
	    'APP_SUB_DOMAIN_RULES'  => array('m'=>array('mobile/', '')),
	    'APP_SUB_DOMAIN_DENY'   => array(), //子域名禁用列表
	//CCR分布式
		'APP_NETWORK'=>true,//开启分布式网络
		'APP_NETWORK_MODEL'=>'SUBDOMAIN',//SUBDOMAIN子域名模式：依赖于APP_SUB_DOMAIN_DEPLOY；partner变量模式
		'APP_NETWORK_DOMAIN'=>array('www','m'),//不占用的子域名
		'APP_VIP'=>true,//定制
    //基础
		'GET_G'=>'g', // $_GET分组名称
		'GET_C'=>'c', // $_GET模块名称
		'GET_A'=>'a', // $_GET操作名称
		'GET_PATHINFO'=>'s', // PATHINFO兼容模式
		'DEF_TPL'=>'default', // 默认模板文件夹
		'DEF_CHARSET'=>'utf-8', // 默认输出编码
		'DEF_CONTENT_TYPE'=>'text/html', // 默认模板输出类型
		'DEF_LANG'=>'zh-cn',
		'DEF_FILTER'=>'htmlspecialchars',
	//定义
		'F_TPL'=>'view', //模板文件夹
		'F_DATA'=>'data', //缓存文件夹
		'EXT_TPL'=>'.htm', //模板文件扩展名
	//设置
		'HOOK_H'=>'_h_', //前置模块HOOK后缀
		'HOOK_F'=>'_f_', //后置模块HOOK后缀
		'HOOK_PAGE'=>true, //是否启用页面HOOK
		'HOOK_ACTION'=>true, //是否启用模块HOOK
		'HTTP_CACHE_CONTROL'=>'private', // 网页缓存控制
		'URL_PATHINFO_FETCH'=>'ORIG_PATH_INFO,REDIRECT_PATH_INFO,REDIRECT_URL', // 用于兼容判断PATH_INFO 参数的SERVER替代变量列表
);