<?php
class c_login extends action{
	function a_do(){
		$cUser = D('admins')->getAdmin();
		if(!empty($cUser['uid'])) {
			togo('admin/product.do');
		}
		if($post = I("P.")){
			$user = $post['username'];
			$pas = $post['password'];
			if($user && $pas){
				$usr = D('admins')->onAdmin($user, $pas);
				if(empty($usr)) {
					err("不存在该帐户或者密码错误。", '/admin/login.do');
				}
				D('admins')->setAdmin($usr);
				togo('admin/product.do');
			}
		}
		$this->display('admin/login');
	}
	
	function a_logout() {
		unset($_SESSION['admin']);
		togo('admin/login.do');
	}
}