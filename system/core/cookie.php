<?php

class cookie{

	public static $settings = array();
	
	function cookie() {
		self::$settings = G('cookie');
	}
	
	public static function get($name, $config = NULL) {
		$config = $config ? $config : self::$settings;
		if(isset($_COOKIE[$name])) {
			if($v = json_decode(C('cipher/mcrypt')->decrypt(base64_decode($_COOKIE[$name]), $config['key']))) {
				if($v[0] > $config['timeout']) {
					return is_scalar($v[1]) ? $v[1] : (array)$v[1];
				}
			}
		}
		return FALSE;
	}

	public static function set($name, $value, $quit='', $config = NULL) {
		extract($config ? $config : self::$settings);
		$timequit = time() + ($quit ? $quit : 60*60*4);
		$value = $value ? base64_encode(C('cipher/mcrypt')->encrypt(json_encode(array($timequit, $value)), $key)) : '';
		setcookie($name, $value, $expires, $path, $domain, $secure, $httponly);
	}
}