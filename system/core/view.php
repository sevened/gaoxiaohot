<?php
/**
 * 模板视图类
 */
class core_view {
	
	protected $var = array();
	protected $viewfile;
	protected $cachefile;
	protected $system = false;
	
    public function __construct($p) {
        if(!empty($p)) $this->system = true;
    }
    public function assign($name, $value=''){
        if(is_array($name)) {
            $this->var = array_merge($this->var, $name);
        }else {
            $this->var[$name] = $value;
        }
    }
    public function display($route='', $lang='') {
    	$this->viewfile = $this->getview($route);
    	$this->cachefile = $this->getcache($route);
        $content = $this->fetch($route, $lang);
        $this->render($content);
    }
    //解析和获取模板内容 用于输出
    public function fetch($route='', $lang='') {
    	if(!is_file($this->viewfile)) return null;
    	$this->template($route);
        ob_start();
        ob_implicit_flush(0);
        extract($this->var, EXTR_OVERWRITE);
        if (is_file($this->cachefile)) {
        	include $this->cachefile;
        }
    	$data = ob_get_clean();
    	$domain = 'http://'.$_SERVER['HTTP_HOST'].DS;
    	$tips = $_SESSION['tips'][$domain.trim($_SERVER['REQUEST_URI'], DS)];
    	$pc_tips = $_SESSION['pc_tips'][$domain.trim($_SERVER['REQUEST_URI'], DS)];
		if($tips) {
			$data .= '<script type="text/javascript">require(["lib/core"], function(){W.'.$tips['ret'].'("'.$tips['msg'].'")})</script>';
		}elseif($pc_tips){
			$data .= '<script type="text/javascript">W.'.$pc_tips['ret'].'("'.$pc_tips['msg'].'")</script>';
		}
		unset($_SESSION['tips']);
		unset($_SESSION['pc_tips']);
    	return $data;
    }
    public function template($route='') {
    	$this->viewfile = $this->getview($route);
    	$this->cachefile = $this->getcache($route);
    	$dir = dirname($this->cachefile);
    	if(!is_dir($dir)) {
        	mkdir($dir, 0777, true);
    	}
    	return $this->_update($route);
    }
    //刷新模板缓存
    public function _update($route) {
    	$viewtime = @filemtime($this->viewfile);
    	$cachetime  = @filemtime($this->cachefile);
    	if(empty($cachetime) || $viewtime > $cachetime) {
    		$template = C('template');
    		$template->parse($route, $this->viewfile, $this->cachefile);
    		return true;
    	}
		return false;
    }
    private function render($content){
        if(empty($charset))  $charset = G('DEF_CHARSET');
        if(empty($contentType)) $contentType = G('DEF_CONTENT_TYPE');
        header('Content-Type:'.$contentType.'; charset='.$charset);
        header('Cache-control: '.G('HTTP_CACHE_CONTROL'));
        header('X-Powered-By:ANCCR');
        echo $content;
    }
    public function getview($route) {
    	static $_c = array();
    	if(!isset($_c[$route])) {
	    	$file = DS.$route.G('EXT_TPL');
	    	$_c[$route] = AP.G('F_TPL').DS.G('DEF_TPL').$file;
	    	if(G("VIP_VIEW")) {
	    		$im = defined('_IM_') ? _IM_ : '';
	    		$diy_file = AP.'vip/'.$im.DS.'view/'.$route.G('EXT_TPL');
	    		if(is_file($diy_file)) {
	    			$_c[$route] = $diy_file;
	    		}
	    	}
	    	if($this->system) {
	    		if(is_file(AP.'view'.$file)) {
	    			$_c[$route] = AP.'view'.$file;
	    		} elseif(is_file(SP.'view'.DS.$route.G('EXT_TPL'))) {
	    			$_c[$route] = SP.'view'.$file;
	    		}
	    	}
    	}
    	return $_c[$route];
    }
    public function getcache($route) {
    	static $_c = array();
    	if(!isset($_c[$route])) {
	    	$_c[$route] = ROOT.G('F_DATA').DS.G('F_TPL').DS.G('DEF_TPL').DS.$route.'.php';
	    	if(G("VIP_VIEW")) {
	    		$im = defined('_IM_') ? _IM_ : '';
	    		$_c[$route] = ROOT.G('F_DATA').DS.G('F_TPL').DS.$im.DS.$route.'.php';
	    	}
	    	if($this->system) {
	    		$_c[$route] = ROOT.G('F_DATA').DS.G('F_TPL').DS.'system'.DS.$route.'.php';
	    	}
	    }
    	return $_c[$route];
    }
}
