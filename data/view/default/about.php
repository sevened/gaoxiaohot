<?php if(!defined('SP')) exit();?><?php include template('comn.header'); ?><style type="text/css">*{padding:0;margin:0}body{background-color:#F2F2F2}.box{background-color:#F2F2F2;max-width:640px;margin:0 auto;width:100%}*{margin:0;padding:0;list-style-type:none}a,img{border:0}a{text-decoration:none}a:link{text-decoration:none}a:visited{text-decoration:none}a:hover{text-decoration:none}a:active{text-decoration:none}a{text-decoration:none;color:#000}.use{height:40px;background:#292335;line-height:38px;color:#000;font-size:14px;width:100%;max-width:640px;margin:0 auto;z-index:100;font-size:18px;font-family:Microsoft YaHei}
.back_a{display:inline-block;width:20%}.btn_back{background:url(/sprite/images/user-icon-jian-left.png) no-repeat center left;width:55px;text-align:right;background-size:25%;display:inline-block;margin-left:10px;color:#fff}.center_title{display:inline-block;width:60%;text-align:center;color:#FFF}
.content{padding-top: 45px;width:98%;text-indent:2em;font-family:Microsoft YaHei;font-size:16px;margin:0 auto;}
.logo{width:100%;text-align:center;margin-top:10px;}
.info{width:100%;text-align:center;font-size:18px;font-family:Microsoft YaHei;margin-top:10px;}
.content{text-indent:0em;}
</style>
<div class="box">
<div class="use">
<a href="/user" class="back_a"><span class="btn_back">&nbsp;&nbsp;&nbsp;返回</span></a>
<span class="center_title">当前版本</span>
</div>
<div class="content">
<div class="logo"><img src="/sprite/images/icon_img.png" alt="" /></div>
<div class="info">看片快手<span></span></div>
</div>
</div>
<script src="/sprite/js/lazyload.js" type="text/javascript"></script>
<script>
function loadComplete(){
var v = window.RM.getVersion();
$(".info span").html(v);
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
