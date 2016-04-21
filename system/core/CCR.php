<?php
defined('SP') or exit();
abstract class CCR {
    static public function run() {
		self::init();
		$route = core_dispatch::run();
		$a = false;
		if(G('APP_VIP')) {
			$im = defined('_IM_') ? _IM_ : '';
			$g = AP.'vip/'.$im.DS.'config.php';
			if($im && is_file($g)) G(include($g));
			if(G('vip_app')) $a = $im;
		}
		if(0==strlen($a)) A($route);
			else A($route, $a);
    }
    static private function init() {
		static $_init;
		if ($_init) {return true;}
        define('IS_POST', $_SERVER['REQUEST_METHOD']=='POST' ? true : false);
        define('IS_AJAX', ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) ? true : false);
		spl_autoload_register(array('CCR', 'autoload'));
		//set_error_handler(array('core_error', 'handler'));
		session_start();
		self::load();
		$_init = true;
    }
    
    static private function load() {
    	$r = ROOT.'data/~runtime.php';
		self::merge($r, array(
			  SP.'core/common.php',
			  SP.'core/function.php',
			  SP.'core/dispatch.php',
			  SP.'core/action.php',
			));
		self::merge(ROOT.'data/~model.php', array(
			  SP.'core/model.php',
			  SP.'core/db/mysql.php'
			));
    	require($r);
		$files['config'] = array(
			  SP.'config.php',
			  ROOT.'config/config.php'
			);
        foreach($files['config'] as $file){
            if(is_file($file)) G(include $file);
        }
    }
    static private function merge($file, $params) {
    	if(is_file($file)) return;
		$content = '<?php defined(\'SP\') or exit();';
        foreach ($params as $v){
        	if(is_file($v)) $content .= self::compile($v);
        }
		file_put_contents($file, $content);
    }
	static private function compile($filename) {
	    $content = php_strip_whitespace($filename);
	    $content = trim(substr($content, 5));
	    $content = preg_replace('/defined(.*?);/', '', $content);
	    if ('?>' == substr($content, -2)) $content = substr($content, 0, -2);
	    return $content;
	}
    public static function autoload($route) {
    	$name = str_replace('_', DS, $route) . EXT;
    	if(is_file(SP.$name))
        	$path = SP;
    	else
            $path = AP.'controller'.DS;
    	$file = $path . $name;
    	if(is_file($file)) include $file;
    }
}