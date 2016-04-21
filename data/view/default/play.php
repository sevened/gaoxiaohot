<?php if(!defined('SP')) exit();?>
<!DOCTYPE html>
<html lang="zh-CN">
<head manifest="cache.manifest">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title><?php if($c['title']) { ?><?php echo $c['title'];?><?php } ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0,user-scalable=no">
<meta name="format-detection" content="telephone=no">
<link rel="stylesheet" href="/sprite/css/play.css" />
</head>
<body>
<div class="box">
<div class="tbanner">
<img src="/sprite/images/banner.jpg" alt="" width="100%"/>
</div>
<div class="use">
<a href="javascript:history.go(-1)" class="back_a"><span class="btn_back">&nbsp;&nbsp;&nbsp;返回</span></a>
<span class="center_title"><?php echo $list['name'];?></span>
</div>
<div class="video">
<img src="http://vip.52show.com/v2/<?php echo $list['file_path'];?>" alt="" style="display:block;width:100%;"/>
<img src="/sprite/images/playing.png" alt="" style="position:absolute;width:4rem;top:10rem;left:10.5rem"/>
</div>
<input type="hidden" value="<?php echo $cid;?>" class="cid"/>
<div class="content">
<span style="font-size:1.2rem;color:#111">内容介绍:</span>
<div style="font-size:0.8rem;margin-top:10px;line-height:25px;color:#333"><?php echo $list['description'];?></div>
</div>
<div class="viewinfo">
<img src="/sprite/images/war.png" alt="" /><p>&nbsp;系统检测到您还未安装免费播放插件 <a href="javascript:;" id="sdk">查看详情</a></p>
</div>
</div>
<script src="/sprite/js/lazyload.js" type="text/javascript"></script>
<script>
function loadComplete(){
window.RM.checkPay();
var cid=$('.cid').val();
$(".video,#sdk").click(function(){
//判断是否支付
if(window.RM.canPay()){
$.post("/play.getVideo",{'cid':cid},function(data){
window.RM.playVideo(data);
});
}
});
}
function loadscript(){
    LazyLoad.js([
    	'/sprite/js/zepto.min.js',
    	'/sprite/js/font.js'
    ], loadComplete);
}
setTimeout(loadscript,10);
</script>
</body>
</html>
