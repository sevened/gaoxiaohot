<?php
class umapi{
	
	//auth_token认证
	function authorize(){
		$url = "http://api.umeng.com/authorize";
		$arr = array(
			'email'=>'yangjiao@ruimeng.mobi',
			'password'=>'221228'
		);
		return H('http')->post($url,$arr);
	}
	
	//------------------公共接口
	function get($app,$arr=array()){
		if($app){
			$str = "";
			foreach((array)$arr as $key=>$a){
				$str .= $key."=".$a."&";
			}
			$url = "http://api.umeng.com/".$app."?".$str;
			$result = H('http')->get($url);
			$data = json_decode($result,true);
			return $data;
		}
	}
	
	//------------------APPS接口
	//获取apps列表
	function apps($arr=array()){
		$str = "";
		foreach((array)$arr as $key=>$a){
			$str .= $key."=".$a."&";
		}
		$url = "http://api.umeng.com/apps?".$str;
		$result = H('http')->get($url);
		$data = json_decode($result,true);
		return $data;
	}
	
	//获取apps数量
	function apps_count($arr=array()){
		$str = "";
		foreach((array)$arr as $key=>$a){
			$str .= $key."=".$a."&";
		}
		$url = "http://api.umeng.com/apps/count?".$str;
		$result = H('http')->get($url);
		$data = json_decode($result,true);
		return $data;
	}
	
	//获取apps基本数据
	function apps_base_data(){
	    /*
	    [installations] => 总用户数
	    [yesterday_new_users] => 昨日新增用户
	    [today_new_users] => 今日新增用户
	    [yesterday_active_users] => 昨日活跃用户
	    [today_active_users] => 今日活跃用户
	    [yesterday_launches] => 昨日启动次数
	    [today_launches] => 今日启动次数
	    */
		$url = "http://api.umeng.com/apps/base_data?auth_token=XyPpDDk4eJHabdXNI95n";
		$result = H('http')->get($url);
		$data = json_decode($result,true);
		return $data;
	}
	
	//------------------  APP接口
	//获取所有渠道和基本数据
	function channels(){
		/*
		[date] => 返回日期
        [id] => channel的id
        [channel] => 渠道名称
        [total_install] => 总用户数
        [total_install_rate] => 当前渠道新增用户占当日新增比例
        [install] => 新增用户
        [active_user] => 活跃用户
        [launch] => 启动数
        [duration] => 使用时长
        [install_rate] => 用户数在总用户数中的比例
		*/
		$url = "http://api.umeng.com/channels?appkey=55922e1c67e58e59ca00153d&auth_token=XyPpDDk4eJHabdXNI95n";
		$result = H('http')->get($url);
		$data = json_decode($result,true);
		return $data;
	}
	
	//获取所有版本和基本数据
	function versions(){
		/*
        [version] => 版本号
        [install] => 新增用户
        [active_user] => 活跃用户
        [launch] => 启动数
        [total_install] => 该版本总新增用户数
        [total_install_rate] => 今天新增占该版本总新增的比例
		*/
		$url = "http://api.umeng.com/versions?appkey=55922e1c67e58e59ca00153d&auth_token=XyPpDDk4eJHabdXNI95n";
		$result = H('http')->get($url);
		$data = json_decode($result,true);
		return $data;
	}
	
	//获取今日数据
	function today_data(){
		/*
	    [date] => 日期
	    [new_users] => 新增用户
	    [active_users] => 活跃用户
	    [launches] => 启动次数
	    [installations] => 总用户数
		*/
		$url = "http://api.umeng.com/today_data?appkey=55922e1c67e58e59ca00153d&auth_token=XyPpDDk4eJHabdXNI95n";
		$result = H('http')->get($url);
		$data = json_decode($result,true);
		return $data;
	}
	
	//获取昨日数据
	function yestoday_data(){
		/*
	    [date] => 日期
	    [new_users] => 新增用户
	    [active_users] => 活跃用户
	    [launches] => 启动次数
	    [installations] => 总用户数
		*/
		$url = "http://api.umeng.com/yestoday_data?appkey=55922e1c67e58e59ca00153d&auth_token=XyPpDDk4eJHabdXNI95n";
		$result = H('http')->get($url);
		$data = json_decode($result,true);
		return $data;
	}
	
	//获取任意日期数据
	function base_data(){
		/*
	    [date] => 日期
	    [new_users] => 新增用户
	    [active_users] => 活跃用户
	    [launches] => 启动次数
	    [installations] => 总用户数
		*/
		$url = "http://api.umeng.com/base_data?date=2015-07-01&appkey=55922e1c67e58e59ca00153d&auth_token=XyPpDDk4eJHabdXNI95n";
		$result = H('http')->get($url);
		$data = json_decode($result,true);
		return $data;
	}
	
	//获取用户群列表
	function segmentations(){
		$url = "http://api.umeng.com/segmentations?appkey=55922e1c67e58e59ca00153d&auth_token=XyPpDDk4eJHabdXNI95n";
		$result = H('http')->get($url);
		$data = json_decode($result,true);
		return $data;
	}
	
	//获取新增用户汇总
	function new_users(){
		$arr = array(
			'auth_token'=>'XyPpDDk4eJHabdXNI95n',
			'appkey'=>'55922e1c67e58e59ca00153d',
			'start_date'=>'2015-06-01',
			'end_date'=>'2015-07-01',
			'period_type'=>'daily',//日期类型：hourly daily weekly monthly 默认为daily
			'channels'=>'',//渠道id
			'versions'=>'',
			//'segments'=>'',//分群id
		);
		$str = "";
		foreach((array)$arr as $key=>$a){
			$str .= $key."=".$a."&";
		}
		$url = "http://api.umeng.com/new_users?".$str;
		$result = H('http')->get($url);
		$data = json_decode($result,true);
		return $data;
	}
	
	//获取活跃用户汇总
	function active_users(){
		$arr = array(
			'auth_token'=>'XyPpDDk4eJHabdXNI95n',
			'appkey'=>'55922e1c67e58e59ca00153d',
			'start_date'=>'2015-06-01',
			'end_date'=>'2015-07-01',
			'period_type'=>'daily',//日期类型：hourly daily weekly monthly 默认为daily
			'channels'=>'',
			'versions'=>'',
			//'segments'=>''
		);
		$str = "";
		foreach((array)$arr as $key=>$a){
			$str .= $key."=".$a."&";
		}
		$url = "http://api.umeng.com/active_users?".$str;
		$result = H('http')->get($url);
		$data = json_decode($result,true);
		return $data;
	}
	
	//获取启动用户汇总
	function launches(){
		$arr = array(
			'auth_token'=>'XyPpDDk4eJHabdXNI95n',
			'appkey'=>'55922e1c67e58e59ca00153d',
			'start_date'=>'2015-06-01',
			'end_date'=>'2015-07-11',
			'period_type'=>'daily',//日期类型：hourly daily weekly monthly 默认为daily
			'channels'=>'',
			'versions'=>'',
			//'segments'=>''
		);
		$str = "";
		foreach((array)$arr as $key=>$a){
			$str .= $key."=".$a."&";
		}
		$url = "http://api.umeng.com/launches?".$str;
		$result = H('http')->get($url);
		$data = json_decode($result,true);
		return $data;
	}
	
	//获取使用时长汇总
	function durations(){
		$arr = array(
			'auth_token'=>'XyPpDDk4eJHabdXNI95n',
			'appkey'=>'55922e1c67e58e59ca00153d',
			'start_date'=>'2015-06-01',
			'end_date'=>'2015-07-11',
			'period_type'=>'daily',//日期类型：hourly daily weekly monthly 默认为daily
			'channels'=>'',
			'versions'=>'',
			//'segments'=>''
		);
		$str = "";
		foreach((array)$arr as $key=>$a){
			$str .= $key."=".$a."&";
		}
		$url = "http://api.umeng.com/durations?".$str;
		$result = H('http')->get($url);
		$data = json_decode($result,true);
		return $data;
	}
	
	//获取留存用户
	function retentions(){
		$arr = array(
			'auth_token'=>'XyPpDDk4eJHabdXNI95n',
			'appkey'=>'55922e1c67e58e59ca00153d',
			'start_date'=>'2015-06-01',
			'end_date'=>'2015-07-11',
			'period_type'=>'daily',//日期类型：hourly daily weekly monthly 默认为daily
			'channels'=>'',
			'versions'=>'',
			//'segments'=>''
		);
		$str = "";
		foreach((array)$arr as $key=>$a){
			$str .= $key."=".$a."&";
		}
		$url = "http://api.umeng.com/retentions?".$str;
		$result = H('http')->get($url);
		$data = json_decode($result,true);
		return $data;
	}
	
	//------------------------自定义事件
	//获取事件Group列表
	function events_group_list(){
		$arr = array(
			'auth_token'=>'jxPoLCHf8XT9S5QHWNHB',
			'appkey'=>'55b04878e0f55a5b51009d18',
			'start_date'=>'2015-01-01',
			'end_date'=>'2015-07-23',
			'versions'=>'',
		);
		$str = "";
		foreach((array)$arr as $key=>$a){
			$str .= $key."=".$a."&";
		}
		$url = "http://api.umeng.com/events/group_list?".$str;
		$result = H('http')->get($url);
		$data = json_decode($result,true);
		return $data;
	}
	
	//获取事件列表
	function events_event_list(){
		$arr = array(
			'auth_token'=>'XyPpDDk4eJHabdXNI95n',
			'appkey'=>'55922e1c67e58e59ca00153d',
			'start_date'=>'2015-05-01',
			'end_date'=>'2015-07-11',
			'group_id'=>'',
		);
		$str = "";
		foreach((array)$arr as $key=>$a){
			$str .= $key."=".$a."&";
		}
		$url = "http://api.umeng.com/events/event_list?".$str;
		$result = H('http')->get($url);
		$data = json_decode($result,true);
		return $data;
	}
	
	//反馈列表
	function feedbacks(){
		$arr = array(
			'auth_token'=>'XyPpDDk4eJHabdXNI95n',
			'appkey'=>'55922e1c67e58e59ca00153d',
			'per_page'=>'10',
			'page'=>'1'
		);
		$str = "";
		foreach((array)$arr as $key=>$a){
			$str .= $key."=".$a."&";
		}
		$url = "http://api.umeng.com/feedbacks?".$str;
		$result = H('http')->get($url);
		$data = json_decode($result,true);
		return $data;
	}

}