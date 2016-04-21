<?php
class model_admins extends model{
	protected $_table = 'admins';
    protected $_fields = array('username','password','salt','trust');
    protected $_id = 'uid';	
	protected static $_data = array();

	
	//获得加密随机码
    public function getSalt() {
        return substr(uniqid(rand()), -6);
    }
	
	//加密密码
    public function formatPassword($password, $salt) {
        return md5(md5($password).$salt);
    }
	
    public function onAdmin($name, $pwd) {
        $pwd = $this->formatPassword($pwd, $this->getSaltByName($name));
        return $this->queryOne('*', array(array('username',$name), array('password',$pwd)));
    }
    
    public function onUser($name, $pwd) {
        $pwd = $this->formatPassword($pwd, $this->getSaltByName($name));
        return $this->queryOne('*', array(array('username', $name), array('password', $pwd)));
    }

    public function getUserByname($name) {
    	if(!isset(self::$_data['u.'.$name]))
    		self::$_data['u.'.$name] = $this->queryOne('*', array(array('username', $name)));
    	return self::$_data['u.'.$name];
    }
    public function getSaltByName($name) {
    	$uinfo = $this->getUserByname($name);
        return empty($uinfo['salt']) ? '' : $uinfo['salt'];
    }
    public function getUserByUid($uid) {
    	if(!isset(self::$_data['uid.'.$uid]))
    		self::$_data['uid.'.$uid] = $this->queryOne('*', array (array ('uid', $uid)));
    	return self::$_data['uid.'.$uid];
    }
    public function setAdmin($user) {
    	$_SESSION['admin']['uid']        =  $user['uid'];
    	$_SESSION['admin']['username']   =  $user['username'];
        $_SESSION['admin']['lastupdate'] =  time();
    }
    public function getAdmin() {
    	$user = array();
        $user['uid']      = isset($_SESSION['admin']['uid']) ? $_SESSION['admin']['uid'] : null;
        $user['username'] = isset($_SESSION['admin']['username']) ? $_SESSION['admin']['username'] : null;
        if(empty($_SESSION['admin']['lastupdate']) || ($user['uid'] && time() - $_SESSION['admin']['lastupdate'] > 300)) 
            $_SESSION['admin']['lastupdate'] = time ();
        return $user;
    }
    public function setUser($user) {
    	$_SESSION['admin']['uid']        =  $user['uid'];
    	$_SESSION['admin']['username']   =  $user['username'];
        $_SESSION['admin']['lastupdate'] =  time();
    }
    public function getUser() {
    	$user = array();
        $user['uid']      = isset($_SESSION['admin']['uid']) ? $_SESSION['admin']['uid'] : null;
        $user['username'] = isset($_SESSION['admin']['username']) ? $_SESSION['admin']['username'] : null;
        if(empty($_SESSION['admin']['lastupdate']) || ($user['uid'] && time() - $_SESSION['admin']['lastupdate'] > 300)) 
            $_SESSION['admin']['lastupdate'] = time ();
        return $user;
    }
    
    //用户登录
    public function onLogin($username, $password) {
        $password = $this->formatPassword($password, $this->getSaltByName($username));        
        return $this->queryOne('*', array(array('username', $username), array('password', $password)));        
    }

    
   //用户登出
    public function onLogout() {
        unset($_SESSION['admin']);
        unset($_SESSION['backurl']);
    }
    
    //修改密码时验证旧密码是否正确
    public function checkUserOldPassword($username, $password) {
        $password = $this->formatPassword($password, $this->getSaltByName($username));
        return $this->getCount(array(array('username', $username), array('password', $password)));
    }
}