<?php
class core_error {
	public static $found = FALSE;
	public static function header() {
		headers_sent() OR header('HTTP/1.0 500 Server Error');
	}
	public static function handler($code, $error, $file = 0, $line = 0) {
		if((error_reporting() & $code) === 0) return TRUE;
		//log_message("[$code] $error [$file] ($line)");
		self::$found = TRUE;
		$view = C('view', true);
		self::header();
		$view->assign('error', $error);
		$view->assign('code', $code);
		if(DEBUG) {
			$view->display('error/error');
		} else {
			$view->display('error/500');
		}
		exit(1);
	}
	public static function err404() {
		header('HTTP/1.1 404 Not Found');
		$view = C('view', true);
		$view->display('error/404');
	}
	public static function source($file, $number, $padding = 5) {
		$lines = array_slice(file($file), $number-$padding-1, $padding*2+1, 1);
		$html = '';
		foreach($lines as $i => $line) {
			$html .= '<b>'.sprintf('%'.mb_strlen($number + $padding) . 'd', $i + 1) . '</b> '.($i + 1 == $number ? '<em>' . htmlspecialchars($line, ENT_QUOTES) . '</em>' : htmlspecialchars($line, ENT_QUOTES));
		}
		return $html;
	}
	public static function backtrace($offset, $limit = 5) {
		$trace = array_slice(debug_backtrace(), $offset, $limit);
		foreach($trace as $i => &$v) {
			if( ! isset($v['file'])) {
				unset($trace[$i]);
				continue;
			}
			$v['source'] = self::source($v['file'], $v['line']);
		}
		return $trace;
	}
}