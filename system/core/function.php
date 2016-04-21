<?php
function getip() {
    $clientIp = "";
    if (isset ($_SERVER)) {
        if (isset ($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $clientIp = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif (isset ($_SERVER["HTTP_CLIENT_IP"])) {
            $clientIp = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            $clientIp = $_SERVER["REMOTE_ADDR"];
        }
    }
    return $clientIp;
}
function rand_uniqid($num = '8') {
    $in = time();
    $passKey = rand(0, $in);
	$index = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	if($passKey !== null) {
		for($n=0;$n<strlen($index);$n++) $i[] = substr($index,$n,1);
		$passhash = hash('sha256',$passKey);
		$passhash = (strlen($passhash) < strlen($index)) ? hash('sha512',$passKey) : $passhash;
        for($n=0;$n<strlen($index);$n++) $p[] =  substr($passhash, $n ,1);
		array_multisort($p, SORT_DESC, $i);
        $index = implode($i);
	}
	$base = strlen($index);
    if (is_numeric($num)) {
        $num--;
        if ($num > 0) $in += pow($base, $num);
    }
    $out = "";
    $t = floor(log($in, $base));
    for ($t = floor(log($in, $base)); $t >= 0; $t--) {
        $bcp = bcpow($base, $t);
        $a   = floor($in / $bcp) % $base;
        $out = $out . substr($index, $a, 1);
        $in  = $in - ($a * $bcp);
    }
    $out = strrev($out);
	return strtolower($out);
}
/*二维数组变成一维*/
function array_columns($arr,$col){
	if($arr){
		foreach($arr as $a){
    		$ar[] = $a[$col];
    	}
	}
	return $ar;
}
function dump() {
	$string = '';
	foreach(func_get_args() as $value) {
		$string .= '<pre>' . htmlspecialchars($value === NULL ? 'NULL' : (is_scalar($value) ? $value : print_r($value, TRUE))) . "</pre>\n";
	}
	return $string;
}
function tips($ret, $msg, $platform='mobile', $go='-1', $time=null) {
	$go = trim($go, '/');
	$domain = 'http://'.$_SERVER['HTTP_HOST'].DS;
	if(strpos($go, "http://") !== false) {
		$to = $go;
	} elseif($go == '-1') {
		$to = empty($_SERVER['HTTP_REFERER']) ? $domain : $_SERVER['HTTP_REFERER'];
	} elseif($go == 'this') {
		$to = $domain._GCA_;
	} else {
        $to = $domain.$go;
	}
    /* ealovexp 修改 */
    if($ret && $platform=='mobile' && in_array($ret, array('suc', 'err', 'tip','msg'))) {
    	$_SESSION['tips'][$to] = array('ret' => $ret, 'msg' => $msg, 'url' => $to);
    }elseif($ret && $platform=='pc' && in_array($ret, array('suc', 'err', 'tip','msg'))) {
    	$_SESSION['pc_tips'][$to] = array('ret' => $ret, 'msg' => $msg, 'url' => $to);
    }
    if ($to) {
    	header ('Location: '.$to);
    	echo $meta = "<meta http-equiv=\"refresh\" content=\"{$time};url={$to}\" />";
    	exit;
    }
}
function togo($go='-1',$platform='mobile') {
	tips('togo', '', $platform, $go);
}
function suc($msg, $gourl='-1', $platform='mobile', $time=null) {
	tips('suc', $msg, $platform, $gourl, $time);
}
function err($msg, $gourl='-1', $platform='mobile', $time=null) {
	tips('err', $msg, $platform, $gourl, $time);
}
/* ealovexp 修改 */
function tip($msg, $gourl='-1', $platform='mobile', $time=null) {
	tips('tip', $msg, $platform, $gourl, $time);
}
/**
* 将字符串转换为数组
* @param	string	$data	字符串
* @return	array	返回数组格式，如果，data为空，则返回空数组
*/
function string2array($data) {
	if ($data == '') return array();
	if (is_array($data)) return $data;
	if (strpos($data, 'array') !== false && strpos($data, 'array') === 0) {
	    @eval("\$array = $data;");
		return $array;
	}
	return unserialize($data);
}

/**
* 将数组转换为字符串
* @param	array	$data		数组
* @param	bool	$isformdata	如果为0，则不使用new_stripslashes处理，可选参数，默认为1
* @return	string	返回字符串，如果，data为空，则返回空
*/
function array2string($data, $isformdata = 1) {
	if($data == '') return '';
	if($isformdata) $data = new_stripslashes($data);
	return serialize($data);
}

/**
 * 返回经stripslashes处理过的字符串或数组
 * @param $string 需要处理的字符串或数组
 * @return mixed
 */
function new_stripslashes($string) {
	if(!is_array($string)) return stripslashes($string);
	foreach($string as $key => $val) $string[$key] = new_stripslashes($val);
	return $string;
}