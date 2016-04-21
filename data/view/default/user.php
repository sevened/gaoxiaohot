<?php if(!defined('SP')) exit();?><?php include template('comn.header'); ?><style type="text/css">body{background-color:#2A2335}*{padding:0;margin:0}a{text-decoration:none}a:link{text-decoration:none}a:visited{text-decoration:none}a:hover{text-decoration:none}a:active{text-decoration:none}.box{width:100%;background-color:#2A2335;height:100%;max-width:640px;margin:0 auto}.box .top{width:95%;margin:auto;background-color:#F2F2F2;border-top:1px solid #D9D9D9;border-left:1px solid #D9D9D9;border-right:1px solid #D9D9D9}.box .bottom{border-bottom:1px solid #D9D9D9}.top .user-menus{width:95%;height:4rem;cursor:pointer;line-height:4rem;position:relative;vertical-align:middle;background-image:url(/sprite/images/user-icon-jian-000.png);background-color:#F2F2F2;background-repeat:no-repeat;background-size:auto 15px;background-position:right center;margin:auto}.user-menus .icon{float:left;width:20%;height:100%;text-align:center}.user-menus .icon img{border:0;margin-top:26%;width:1.7rem}.user-menus .title{float:left;font-size:20px;color:#000;height:100%;font-family:"Microsoft YaHei"}
.mask{position:fixed;top:0;filter:alpha(opacity=60);background-color:#777;z-index:10001;left:0;opacity:.5;-moz-opacity:.5;}.model{position: fixed;z-index: 99999;	width: 100%;display: none;margin:auto;}.tiao {overflow: hidden;}
.user-menus .icon img.version-icon{width:1.9rem}
</style>
<div id="add">
<div id="mask" class="mask"></div>
<div class='model' id='model'>
<div style='height: 50px;width: 90%;line-height: 50px;background: #1E252B;color: #fff;margin:auto;'>
<div style='font-size: 18px;height: 48px;width: 100%;line-height: 48px;background: #1E252B;color: #fff;font-family: Microsoft YaHei'><b>&nbsp;&nbsp;投诉电话</b></div>
<div style='border:1px solid #A3A3A3;width: 100%;margin: auto'></div>
</div>
<div style='width: 90%;background: #2E373E;color: #fff;margin:auto;'>
<div style='overflow-x: auto;overflow-y: auto;width: 100%;font-size: 16px;line-height: 48px;font-family: Microsoft YaHei;background:#1E252B'>&nbsp;&nbsp;&nbsp;&nbsp;投诉电话:10086</div>
<div  style='width:96%;	font-size: 24px;font-family: Microsoft YaHei;background:#7F7F7F;padding: 2%;'>
<div style="width:90%;margin:auto;">
<a href="tel:10086"><div style="width:48%;cursor: pointer;float:left;text-align:center;line-height: 35px;font-family: Microsoft YaHei;background:#fff;color:#000;">确定</div></a>
<div id='close' style="width:48%;cursor: pointer;float:right;text-align:center;line-height: 35px;font-family: Microsoft YaHei;background:#fff;color:#000;">取消</div>
<div style="clear:both;"></div>
</div>
</div>
</div>
</div>
</div>

<div class="box">
<div class="top bottom" style="margin-top:30px;">
<a href="javascript:;" id="tocall">
<div class="user-menus look">
<div class="icon"><img src="/sprite/images/phone.png"></div>
<div class="title">投诉热线</div>
<div style="clear:both;"></div>
</div>
</a>
</div>
<!--<div class="top" style="margin-top:5px;"> 
<a href="#">
<div class="user-menus">
<div class="icon"><img src="/sprite/images/history.png"></div>
<div class="title">播放历史</div>
<div style="clear:both;"></div>
</div>
</a>
</div>-->
<div class="top bottom" style="margin-top:10px;"> 
<a href="http://index.qq137.com/">
<div class="user-menus">
<div class="icon"><img src="/sprite/images/recommend.png"></div>
<div class="title">精品推荐</div>
<div style="clear:both;"></div>
</div>
</a>
</div>
<!--<div class="top" style="margin-top:10px;display:none;">
<a href="#">
<div class="user-menus">
<div class="icon"><img src="/sprite/images/set.png"></div>
<div class="title">软件设置</div>
<div style="clear:both;"></div>
</div>
</a>
</div>-->
<div class="top" style="margin-top:30px;"> 
<a href="/user.feedback">
<div class="user-menus">
<div class="icon"><img src="/sprite/images/info.png"></div>
<div class="title">意见反馈</div>
<div style="clear:both;"></div>
</div>
</a>
</div>
<div class="top"> 
<a href="/user.protocol">
<div class="user-menus">
<div class="icon"><img src="/sprite/images/user.png"></div>
<div class="title">用户协议</div>
<div style="clear:both;"></div>
</div>
</a>
</div>
<div class="top bottom"> 
<a href="/user.about">
<div class="user-menus">
<div class="icon"><img src="/sprite/images/version.png" class="version-icon"></div>
<div class="title">当前版本</div>
<div style="clear:both;"></div>
</div>
</a>
</div>
<div style="height:70px;"></div>
</div>
<script src="/sprite/js/lazyload.js" type="text/javascript"></script>
<script>
function loadComplete(){
/*$(".look").click(function(){
var height=$(window.parent.document.body).height()+50;
$("#mask").css("height",height);
    $("#mask").css("width","100%"); 
    $("#mask").show(); 
    var th = $("#model").height();
var top = ($(window).height()-th)/2;   
    var scrollTop = $(document).scrollTop(); 
    $('body').attr("class","tiao");
    $('.box').addClass("dw");
$("#model").css( { position : 'absolute', 'top' : top} ).show();  
})*/

$("#close").click(function(){
$("#model").hide();
$("#mask").hide();
$('body').removeClass("tiao");
$('.box').removeClass("dw");

})

$("#tocall").click(function(){
window.RM.toCall();
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
