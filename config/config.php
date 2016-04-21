<?php
defined('SP') or exit();
return array(
	'SITE_NAME'=>'ccr',
	'mysql'=>array (
		'type'=>'mysql',
		'host'=>'127.0.0.1',
		'user'=>'root',
		'pwd'=>'root',
		'charset'=>'utf8',
		'pre'=>'g_',
		'dbname'=>'gaoxiaohot',
	),
	'cookie' => array(
		'key' => 'EMF',
		'timeout' => time(),
		'expires' => 0,
		'path' => '/',
		'domain' => '',
		'secure' => '',
		'httponly' => '',
	),
);