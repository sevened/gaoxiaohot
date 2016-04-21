<?php
function L($name=null, $value=null) {
    static $_lang = array();
    if (empty($name))
        return $_lang;
    if (is_string($name)) {
        $name = strtoupper($name);
        if (is_null($value))
            return isset($_lang[$name]) ? $_lang[$name] : $name;
        $_lang[$name] = $value;
        return;
    }
    if (is_array($name))
        $_lang = array_merge($_lang, array_change_key_case($name, CASE_UPPER));
    return;
}
function G($name=null, $value=null) {
    static $_config = array();
    if (empty($name)) {
        return $_config;
    }
    if (is_string($name)) {
    	$name = strtoupper($name);
        if (!strpos($name, '.')) {
            $name = strtolower($name);
            if (is_null($value))
                return isset($_config[$name]) ? $_config[$name] : null;
            $_config[$name] = $value;
            return;
        }
        $name = explode('.', $name);
        $name[0]   =  strtolower($name[0]);
        if (is_null($value))
            return isset($_config[$name[0]][$name[1]]) ? $_config[$name[0]][$name[1]] : null;
        $_config[$name[0]][$name[1]] = $value;
        return;
    }
    if (is_array($name)) {
        $_config = array_merge($_config, array_change_key_case($name));
        return;
    }
    return null;
}
function I($name, $default='', $filter=null) {
    if(strpos($name,'.')) {
        list($method, $name) = explode('.',$name,2);
    }else{
        $method = 'R';
    }
    switch(strtolower($method)) {
        case 'g'   : $input =& $_GET;break;
        case 'p'   : $input =& $_POST;break;
        case 'put' : parse_str(file_get_contents('php://input'), $input);break;
        case 'r'   :
            switch($_SERVER['REQUEST_METHOD']) {
                case 'POST': $input  =  $_POST;break;
                case 'PUT': parse_str(file_get_contents('php://input'), $input);break;
                default:$input = $_GET;
            }
            break;
        case 's' : $input =& $_SESSION; break;
        case 'c' : $input =& $_COOKIE;  break;
        default:return NULL;
    }
    if(''==$name) { 
        $data = $input;
        array_walk_recursive($data, 'filter_exp');
        $filters = isset($filter) ? $filter : G('DEF_FILTER');
        if($filters) {
            $filters = explode(',', $filters);
            foreach($filters as $filter){
                $data = array_map_recursive($filter,$data);
            }
        }
    }elseif(isset($input[$name])) {
        $data = $input[$name];
        is_array($data) && array_walk_recursive($data,'filter_exp');
        $filters = isset($filter) ? $filter : G('DEF_FILTER');
        if($filters) {
            if(is_string($filters)){
                $filters = explode(',',$filters);
            }elseif(is_int($filters)){
                $filters = array($filters);
            }
            foreach($filters as $filter){
                if(function_exists($filter)) {
                    $data = is_array($data) ? array_map_recursive($filter,$data):$filter($data);
                }else{
                    $data = filter_var($data,is_int($filter)?$filter:filter_id($filter));
                    if(false === $data) return isset($default) ? $default : NULL;
                }
            }
        }
    }else{
        $data = isset($default) ? $default : NULL;
    }
    return $data;
}
function array_map_recursive($filter, $data) {
    $result = array();
    foreach ($data as $key => $val) {
        $result[$key] = is_array($val) ? array_map_recursive($filter, $val) : call_user_func($filter, $val);
    }
    return $result;
}
function filter_exp(&$value){
    if (in_array(strtolower($value),array('exp','or'))) $value .= ' ';
}
function require_cache($file) {
    static $_files = array();
    if (!isset($_files[$file])) {
        if (is_file($file)) {
            require $file;
            $_files[$file] = true;
        } else {
            $_files[$file] = false;
        }
    }
    return $_files[$file];
}
function route($route, $e=array(), $a=false) {
	static $_r = array();
	$route = trim($route, '/');
	if(!isset($_r[$route.$e[0]])) {
		$p = preg_match("#^(?:(SP|AP|ROOT)\.)?([a-z_][a-z0-9_\./]*/|)([a-z0-9_]+)(?:\.([a-z_][a-z0-9_]*))?(?:/|\$)#sim", $route, $m);
		if($p) {
			if(empty($m[1])) $m[1] = 'SP';
    		$path = $m[2] . $m[3];
    		//$space = str_replace("/", "\\", $path);
    		if(empty($m[4])) $m[4] = $e[2] ? $e[2] : 'run';
			$m[3] = $e[0].$m[3];
			$m[4] = $e[1].$m[4];
			if($a && is_string($a))
				$file = AP.'vip/'.$a.DS.$path.EXT;
			else
				$file = constant($m['1']).$path.EXT;
			if($a) {
				$refile = require_cache($file);
				if(empty($refile)) $refile = require_cache(AP.$path.EXT);
			}
			$_r[$route.$e[0]] = array('file'=>$refile, 'path'=>$m[2], 'class'=>$m[3], 'func'=>$m[4]);
			return $_r[$route.$e[0]];
		}
		trigger_error("route: [$route] is invalid", E_USER_ERROR);
	}
	return $_r[$route.$e[0]];
}
function A($route, $a=true) {
	$route = 'AP.controller/'.$route;
	$r = route($route, array('c_','a_','do') ,$a);
	//print_r($a);exit;
	if(class_exists($r['class'])) {
		$m = new $r['class']();
		if (method_exists($m, $r['func'])) {
			$before = G('HOOK_H').$r['func'];
			if (G('HOOK_ACTION') && method_exists($m, $before)) {
				$m->$before();
			}
			if($r['func'] != $r['class']) $m->$r['func']();
			$after = G('HOOK_F').$r['func'];
			if (G('HOOK_ACTION') && method_exists($m, $after)) {
				$m->$after();
			}
		} else err404();
	} else err404();
}
function err404() {
	header('HTTP/1.1 404 Not Found');
	$view = C('view', true)->display('error/404');
}
//实例化模块
function S($route, $e=array(), $p=array()) {
	static $_cls = array();
	$route = trim($route);
	if (isset($_cls[$route.$e[0]]) && is_object($_cls[$route.$e[0]])) {
		return $_cls[$route.$e[0]];
	} else {
		$r = route($route, $e, true);
        $class = $r['class'];
		if(!class_exists($class)) {
			trigger_error('class ['.$r['class'].'] is not exists', E_USER_ERROR);
		}
		$_cls[$route.$e[0]] = new $class($p);
		return $_cls[$route.$e[0]];
	}
}
function C($route, $p=array()) {
	$route = 'SP.core/'.$route;
	return S($route, array('core_'), $p);
}
function H($route, $p=array()) {
	$route = 'SP.library/'.$route;
	return S($route, array(), $p);
}
function D($route, $p=array()) {
	static $_model = array();
	if(isset($_model[$route])) return $_model[$route];
	if(empty($_model)) require(ROOT.'data/~model.php');
	$r = route('AP.model/'.$route, array('model_'), true);
	$class = $r['class'];
	if(!class_exists($class)) {
		$_model[$route] = new model($p, $route);
	} else {
		$_model[$route] = new $class($p);
	}
    return $_model[$route];
}
function template($route) {
	$view = C('view');
	$view->template($route);
	return $view->getcache($route);
}


/**
 * 创建像这样的查询: "IN('a','b')";
 *
 * @access   public
 * @param    mix      $item_list      列表数组或字符串
 * @param    string   $field_name     字段名称
 *
 * @return   void
 */
function db_create_in($item_list, $field_name = '')
{
    if (empty($item_list))
    {
        return $field_name . " IN ('') ";
    }
    else
    {
        if (!is_array($item_list))
        {
            $item_list = explode(',', $item_list);
        }
        $item_list = array_unique($item_list);
        $item_list_tmp = '';
        foreach ($item_list AS $item)
        {
            if ($item !== '')
            {
                $item_list_tmp .= $item_list_tmp ? ",'$item'" : "'$item'";
            }
        }
        if (empty($item_list_tmp))
        {
            return $field_name . " IN ('') ";
        }
        else
        {
            return $field_name . ' IN (' . $item_list_tmp . ') ';
        }
    }
}


/********检查管理员权限********/
function admin_priv($priv_str, $msg_type = '' , $msg_output = true){
	if ($_SESSION['admin']['action_list'] == 'all')
    {
        return true;
    }
    if (strpos(',' . $_SESSION['admin']['action_list'] . ',', ',' . $priv_str . ',') === false)
    {
        $link[] = array('text' => "返回上一页", 'href' => 'javascript:history.back(-1)');
	    if ( $msg_output)
        {
            sys_msg("对不起，您没有此操作的权限！", 0, $link);
	        die();
        }
        return false;
    }
    else
    {
        return true;
    }
    	
}

function admin_priv_json($priv_str, $msg_type = '' , $msg_output = true){
	if ($_SESSION['admin']['action_list'] == 'all')
    {
        return true;
    }
    if (strpos(',' . $_SESSION['admin']['action_list'] . ',', ',' . $priv_str . ',') === false)
    {
		die(json_encode(array('ret'=>'3','content'=>"暂无权限")));
    }
    else
    {
        return true;
    }
    	
}

/**
 * 系统提示信息
 *
 * @access      public
 * @param       string      msg_detail      消息内容
 * @param       int         msg_type        消息类型， 0消息，1错误，2询问
 * @param       array       links           可选的链接
 * @param       boolen      $auto_redirect  是否需要自动跳转
 * @return      void
 */
function sys_msg($msg_detail, $msg_type = 0, $links = array(), $auto_redirect = true)
{
    if (count($links) == 0)
    {
        $links[0]['text'] = "返回上一页";
        $links[0]['href'] = 'javascript:history.go(-1)';
    }
    $view = C('view',true);
    $view->assign('ur_here', "系统信息");
    $view->assign('msg_detail', $msg_detail);
    $view->assign('msg_type', $msg_type);
    $view->assign('links', $links);
    $view->assign('default_url', $links[0]['href']);
    $view->assign('auto_redirect', $auto_redirect);
	$view->display('message/sys_msg');
    exit;
}	