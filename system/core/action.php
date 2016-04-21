<?php
/**
 * Action控制器基类
 */
abstract class action {
	
	protected $view =  null;
	protected $var = array();
	
    public function __construct() {
        if(method_exists($this,'_init')) {
            $this->_init();
        }
    }
    
    protected function display($route, $_lang=array()) {
		$this->initView();
		$this->view->display($route, $_lang);
    }
    
    protected function assign($name, $value='') {
        if(is_array($name)) {
            $this->var = array_merge($this->var, $name);
        }else {
            $this->var[$name] = $value;
        }
    }
    
    //初始化视图
    private function initView(){
        if(!$this->view) $this->view = C('view');
        if($this->var) $this->view->assign($this->var);
    }
}