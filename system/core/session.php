<?php
class session {
	public static function start($name = 'session') {
		if(!empty($_SESSION)) return false;
		$_SESSION = C('cookie')->get($name);
		return true;
	}

	public static function save($name='session', $timeout='') {
		return C('cookie')->set($name, $_SESSION, $timeout);
	}
	
	
	public static function destroy($name = 'session') {
		C('cookie')->set($name, '');
		unset($_COOKIE[$name], $_SESSION);
	}

	public static function token($token = NULL) {
		if(!isset($_SESSION)) return false;
		if($token !== NULL) {
			if( ! empty($_SESSION['token']) && $token === $_SESSION['token']) {
				return true;
			}
			return false;
		}
		return $_SESSION['token'] = token();
	}
}