<?php if(!defined('SP')) exit();?><?php include template('comn.header'); ?><style type="text/css">*{padding:0;margin:0}body{background-color:#F2F2F2}.box{background-color:#292335;max-width:640px;margin:0 auto;width:100%}a{text-decoration:none}a:link{text-decoration:none}a:visited{text-decoration:none}a:hover{text-decoration:none}a:active{text-decoration:none}*{margin:0;padding:0;list-style-type:none}a,img{border:0}.content{background:#292335}.content .moves{width:95%;height:58px;line-height:58px;cursor:pointer;position:relative;vertical-align:middle;background-repeat:no-repeat;background-size:auto 15px;background-position:95% center;margin:auto;font-size:20px;color:#fff;font-family:"Microsoft YaHei"}.moves .left{width:50%;height:58px;background-image:url(/sprite/images/aa.png);background-position:2% center;background-repeat:no-repeat}.photo{width:95%;margin:auto}.photo .img_left{float:left;width:49%}.photo .img_right{float:right;width:49%}.toum{height:25px;width:100%;background-color:#242424;position:absolute;z-index:1000;opacity:.7;margin-top:-25px;color:#fff;font-size:14px;line-height:25px;text-align:center}.lefttishi{height:43px;width:100%;background-color:#FFF;line-height:43px;font-size:25px;font-family:Microsoft YaHei}.tishi{height:2.5rem;width:100%;background-color:#FFF;line-height:2.5rem;font-size:25px;font-family:Microsoft YaHei}.room{width:48%;float:left;margin:1%}
</style>
<div class="box">
<?php if($bestlist) { ?>
<div class="content">
<div class="moves"><div class="left">&nbsp;&nbsp;&nbsp;&nbsp;精选频道</div></div>
</div>
<div style="width:95%;margin:0 auto;"><?php if(is_array($bestlist)) foreach($bestlist as $val) { ?><a href="/list?cid=<?php echo $val['cid'];?>">
<div class="room">
<div class="img_right1" style="width:100%;position: relative;overflow: hidden;">
<div style="width:100%;"><img src="http://vip.52show.com/v2/<?php echo $val['img'];?>" style="width:100%;height:5rem;"/></div>

</div>
</div>
</a>
<?php } ?>
<div style="clear:both;"></div>
</div>
<?php } if($list) { ?>
<div class="content">
<div class="moves"><div class="left">&nbsp;&nbsp;&nbsp;&nbsp;其他频道</div></div>
</div>
<div style="width:95%;margin:0 auto;"><?php if(is_array($list)) foreach($list as $v) { ?><a href="/list?cid=<?php echo $v['cid'];?>">
<div class="room">
<div class="img_right1" style="width:100%;position: relative;overflow: hidden;">
<div style="width:100%;"><img src="http://vip.52show.com/v2/<?php echo $v['img'];?>" style="width:100%;height:5rem;"/></div>

</div>
</div>
</a>
<?php } ?>
<div style="clear:both;"></div>
</div>

<?php } ?>
<div style="height:70px;"></div>
</div>
<script src="/sprite/js/lazyload.js" type="text/javascript"></script>
<script>
function loadComplete(){
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
