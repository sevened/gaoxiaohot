<?php
class sms{
	private $url = 'http://sms.106jiekou.com/utf8/sms.aspx';
	private $account = 'fliker';
	private $password = 'emfads99';
	public function __construct() {
		
	}
	public function checkcode($mobile, $code) {
		if($mobile && $code) {
			$param = array('account'=>$this->account, 'password'=>$this->password);
			$param['mobile'] = $mobile;
			$param['content'] = rawurlencode("您的验证码是：".$code."【新医道】。请不要把验证码泄露给其他人。如非本人操作，可不用理会！");
			$output = H("http")->post($this->url, $param);
			if($output=='100') return true;
		}
		return false;
	}
}