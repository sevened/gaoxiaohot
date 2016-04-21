<?php
class core_dispatch {
    static public function run() {
    	$varp  =  G('GET_PATHINFO');
		$varg  =  G('GET_G');
		$varc  =  G('GET_C');
		$vara  =  G('GET_A');
		if(isset($_GET[$varp])) {
            $_SERVER['PATH_INFO'] = $_GET[$varp];
            unset($_GET[$varp]);
		}
		if(G('APP_SUB_DOMAIN_DEPLOY')) {//子域名部署
			$rules = G('APP_SUB_DOMAIN_RULES');
            if(isset($rules[$_SERVER['HTTP_HOST']])) {
                $rule = $rules[$_SERVER['HTTP_HOST']];
            } else {
            	$sub = substr($_SERVER['HTTP_HOST'], 0, strpos($_SERVER['HTTP_HOST'], '.'));
	            if($sub && isset($rules[$sub])) {
	            	if(G('APP_NETWORK') && G('APP_NETWORK_MODEL')=='SUBDOMAIN') {
	            		if(in_array($sub, G('APP_NETWORK_DOMAIN'))) $rule =  $rules[$sub];
	            	} else {
            			$rule = $rules[$sub];
	            	}
	            }elseif(isset($rules['*'])) {
	                if('www' != $sub && !in_array($sub, G('APP_SUB_DOMAIN_DENY'))) $rule =  $rules['*'];
	            }
            }
            if(!empty($rule)) {
            	$r = self::route($rule[0]);
                if(!empty($r['g'])) {$_GET[$varg] = $r['g'];$dog = true;}
                if(!empty($r['c'])) {$_GET[$varc] = $r['c'];$doc = true;}
                if(!empty($r['a'])) {$_GET[$vara] = $r['a'];$doa = true;}
                if(isset($rule[1])) {
                    parse_str($rule[1], $parms);
                    $_GET = array_merge($_GET, $parms);
                }
            }
		}
		if(G('APP_NETWORK')) {//分布式网络
			if(G('APP_NETWORK_MODEL')=='SUBDOMAIN') {
				$sub = substr($_SERVER['HTTP_HOST'], 0, strpos($_SERVER['HTTP_HOST'], '.'));
				if('www'!=$sub && !in_array($sub, G('APP_NETWORK_DOMAIN')))
					$partner = $sub;
			} else {
				$partner = trim($_GET[G('APP_NETWORK_MODEL')]);
			}
			define('_IM_', $partner);
		}
    	if(!isset($_SERVER['PATH_INFO'])) {
    		$types = explode(',', G('URL_PATHINFO_FETCH'));
            foreach ($types as $type){
                if(0===strpos($type,':')) {
                    $_SERVER['PATH_INFO'] = call_user_func(substr($type,1));break;
                }elseif(!empty($_SERVER[$type])) {
                    $_SERVER['PATH_INFO'] = (0 === strpos($_SERVER[$type],$_SERVER['SCRIPT_NAME']))?
                        substr($_SERVER[$type], strlen($_SERVER['SCRIPT_NAME']))   :  $_SERVER[$type];
                    break;
                }
            }
    	}
    	$path = trim($_SERVER['PATH_INFO'], DS);
       	if(0==strlen($path)) {
        	$r = self::route(_DO_);
        } else {
        	$r = self::route($path);
        }
    	$p = explode('/', $r['g']);
		$var = array();
		$group_deny = G('APP_GROUP_DENY');
        if($group_deny && in_array($p[0], explode(',', $group_deny))) {
            exit;
        }
        empty($dog) && $var[$varg] = $r['g'];
        empty($doc) && $var[$varc] = $r['c'];
        empty($doa) && $var[$vara] = $r['a'];
        $_GET = array_merge($var, $_GET);
        if(!empty($rule) && $r['g']) $_G = $_GET['g'].DS.$r['g'];
        	else $_G = !empty($rule) ? $_GET['g'] : (empty($path) ? $r['g'] : $_GET['g']);
        $_C = empty($path) ? $r['c'] : $_GET['c'];
        $_A = empty($path) ? $r['a'] : $_GET['a'];
        $_A = $_A ? $_A : 'do';
    	unset($_GET[$varg], $_GET[$varc], $_GET[$vara]);
        define('_G_', strip_tags($_G));
        define('_C_', strip_tags($_C));
        define('_A_', strip_tags($_A));
        define('_GCA_', (_G_?_G_.DS:'')._C_.'.'._A_);
        $_REQUEST = array_merge($_POST, $_GET);
        //echo _GCA_;exit;
        return (_G_?_G_.DS:'')._C_.'.'._A_;
    }
    static public function route($route) {
    	$p = preg_match("#^([a-z_][a-z0-9_\./]*/|)([a-z0-9_]+/?|)(?:\.([a-z_][a-z0-9_]*)?)?\$#sim", $route, $m);
    	if($p) return array('g'=>trim($m[1], DS), 'c'=>$m[2], 'a'=>$m[3]);
    }
}
