<?php
error_reporting(E_ALL &~ E_NOTICE);
class cfg{
	protected $cpid;
    public function __construct() {
    	$mod = trim($_GET['mod']);
    	if(in_array($mod, array('conf','quit'))) {
	    	$this->cpid = $_GET['cpid'] ? trim($_GET['cpid']) : trim($_POST['cpid']);
	    	$pro = $_GET['pro'] ? trim($_GET['pro']) : trim($_POST['pro']);
	    	if($pro) {
	    		if($pro=='book') $pro = 'b';
	    		if($pro=='play') $pro = 'v';
	    	} else {
	    		$pro = substr($this->cpid, 0, 1);
	    	}
			if(in_array($pro, array('b','v','y','d'))) {
				$this->pro = $pro;
			} else {
				$this->err404();
			}
    	}
    }

	public function conf(){
		$ret['all'] = array('wappay'=>0, 'wtb'=>0, 'npay'=>1, 'adsdk'=>0, 'adlist'=>array(
			'http://vip-52show-com.b0.upaiyun.com/xysp_avplayf01796.apk|http://jump.anzhuangla.com/img/lg1.jpg|私密直播',
			'http://xx.8782.net/16109729|http://jump.anzhuangla.com/img/lg6.jpg|啪啪视频',
		));
		$ret['b'] = array(
			'b000' => array('wtb'=>1),
			'b003' => array('wtb'=>1),
			'b009' => array('wtb'=>1),
			'b010' => array('wtb'=>1),
			'b021' => array('wtb'=>1),
			'b030' => array('wtb'=>1),
			'b032' => array('wtb'=>1),
			'b038' => array('wtb'=>1),
			'b044' => array('wtb'=>1),
			'b047' => array('wtb'=>1),
			'b059' => array('wtb'=>1),
			'b061' => array('wtb'=>1),
		);
		$ret['v'] = array(
			'v003' => array('wtb'=>1),
			'v010' => array('wtb'=>1),
			'v016' => array('wtb'=>1),
			'v021' => array('wtb'=>1),
			'v045' => array('wtb'=>1),
			'v047' => array('wtb'=>1),
			'v057' => array('wtb'=>1),
			'v060' => array('wtb'=>1),
			'v066' => array('wtb'=>1),
			'v074' => array('wtb'=>1),
			'v078' => array('wtb'=>1),
			'v092' => array('wtb'=>1),
			'v109' => array('wtb'=>1),
			'v120' => array('wtb'=>1),
			'v123' => array('wtb'=>1),
		);
		$ret['y'] = array(
			'y007' => array('wtb'=>1),
			'y008' => array('wtb'=>1),
			'y009' => array('wtb'=>1),
			'y012' => array('wtb'=>1),
			'y014' => array('wtb'=>1),
		);
		$ret['d'] = array(
			'd001' => array('wtb'=>1),
			'd002' => array('wtb'=>1),
			'd003' => array('wtb'=>1),
			'd004' => array('wtb'=>1),
			'd005' => array('wtb'=>1),
			'd006' => array('wtb'=>1),
			'd007' => array('wtb'=>1),
			'd008' => array('wtb'=>1),
			'd009' => array('wtb'=>1),
			'd010' => array('wtb'=>1),
		);
		$cpid = $this->cpid;
		$out = $ret[$this->pro][$cpid] ? $ret[$this->pro][$cpid] + $ret['all'] : ($ret[$this->pro]['all'] ? $ret[$this->pro]['all'] : $ret['all']);
		// http://jump.anzhuangla.com/cfg.php?mod=conf&cpid=b021
		exit(json_encode($out, true));
	}
	public function quit(){
		$down['b'] = array(
				'all'  => 'http://a.03471.com/v044rkzGOUMW.apk',
				'b008' => 'http://a.03471.com/v044rkzGOUMW.apk',
				'b030' => 'http://a.03471.com/v044rkzGOUMW.apk',
			);
		$down['v'] = array(
				'all'  => 'http://a.03471.com/b034uRJwlUOS.apk',
				'v003' => 'http://a.03471.com/b009JjLzSOfN.apk',
				'v008' => 'http://a.03471.com/b008TAywSVtp.apk',
				'v021' => 'http://a.03471.com/b009JjLzSOfN.apk',
				'v030' => 'http://a.03471.com/b034uRJwlUOS.apk',
				'v034' => 'http://a.03471.com/b009JjLzSOfN.apk',
				'v035' => 'http://a.03471.com/b009JjLzSOfN.apk',
				'v044' => 'http://a.03471.com/b009JjLzSOfN.apk',
				'v050' => 'http://a.03471.com/b009JjLzSOfN.apk',
				'v051' => 'http://a.03471.com/b009JjLzSOfN.apk',
				'v052' => 'http://a.03471.com/b009JjLzSOfN.apk',
				'v053' => 'http://a.03471.com/b009JjLzSOfN.apk',
				'v054' => 'http://a.03471.com/b009JjLzSOfN.apk',
			);
		$down['y'] = array(
				'all' => 'http://a.03471.com/b034uRJwlUOS.apk',
			);
		$down['d'] = array(
				'all' => 'http://a.03471.com/b034uRJwlUOS.apk',
			);
		$cpid = $this->cpid;
		$out = $down[$this->pro][$cpid] ? $down[$this->pro][$cpid] : $down[$this->pro]['all'];
		header("location: ".$out);
	}
	public function hao123(){
		header("location: http://index.qq137.com/");
	}
	public function err404(){
		header('HTTP/1.1 404 Not Found');
		exit("404 Not Found");
	}
}
$mod = trim($_GET['mod']) ? trim($_GET['mod']) : 'index';
$cfg = new cfg();
if(method_exists($cfg, $mod)) {
	$cfg->$mod();
} else {
	$cfg->err404();
}