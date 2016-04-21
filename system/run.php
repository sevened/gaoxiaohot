<?php
if(!defined('_DO_')) exit();
//error_reporting(E_ALL &~ E_STRICT);
error_reporting(E_ALL &~ E_NOTICE);
define('DEBUG', true);
define('DS', '/');
define('EXT', '.php');
define('ROOT', dirname($_SERVER['SCRIPT_FILENAME']) . DS);
define('SP', ROOT.'system'.DS);
define('AP', ROOT.'application'.DS);
date_default_timezone_set('PRC');
mb_internal_encoding("UTF-8");
header("Content-type: text/html; charset=utf-8");
require(SP.'core/CCR'.EXT);
CCR::run();