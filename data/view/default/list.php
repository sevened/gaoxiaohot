<?php if(!defined('SP')) exit();?><?php include template('comn.header'); ?><script src="/sprite/js/jquery-1.7.2.min.js" type="text/javascript"></script>
<script src="/sprite/js/jquery.lazyload.min.js" type="text/javascript"></script>
<style type="text/css">*{padding:0;margin:0}body{background-color:#F2F2F2;font-family:'MicroSoft YaHei'}.box{background-color:#292335;max-width:640px;margin:0 auto;width:100%}a{text-decoration:none}a:link{text-decoration:none}a:visited{text-decoration:none}a:hover{text-decoration:none}a:active{text-decoration:none}*{margin:0;padding:0;list-style-type:none}a,img{border:0}.content .moves{width:95%;height:58px;line-height:58px;cursor:pointer;position:relative;vertical-align:middle;background-image:url(/sprite/images/user-icon-jian.png);background-color:#F2F2F2;background-repeat:no-repeat;background-size:auto 15px;background-position:95% center;margin:auto;font-size:27px;font-family:"Microsoft YaHei"}.moves .left{width:50%;height:58px;background-image:url(/sprite/images/aa.png);background-position:2% center;background-repeat:no-repeat}.photo{width:95%;margin:auto}.photo .img_left{float:left;width:49%}.photo .img_right{float:right;width:49%}.toum{height:20px;width:100%;color:#fff;font-size:16px;line-height:20px}.lefttishi{height:43px;width:100%;background-color:#FFF;line-height:43px;font-size:25px;font-family:Microsoft YaHei}.tishi{height:2.5rem;width:100%;background-color:#FFF;line-height:2.5rem;font-size:25px;font-family:Microsoft YaHei}.room{width:95%;margin:auto;background:#3C3849;margin-bottom:0.8rem}a{text-decoration:none}a:link{text-decoration:none}a:visited{text-decoration:none}a:hover{text-decoration:none}a:active{text-decoration:none}a{text-decoration:none;color:#000}.use{height:40px;background:#292335;line-height:38px;color:#000;font-size:14px;width:100%;position:fixed;max-width:640px;margin:0 auto;z-index:100;font-size:18px;font-family:Microsoft YaHei}.back_a{display:inline-block;width:20%}.btn_back{background:url(./sprite/images/user-icon-jian-left.png) no-repeat center left;width:55px;text-align:right;background-size:25%;display:inline-block;margin-left:10px;color:#fff}.center_title{display:inline-block;width:60%;text-align:center;color:#FFF}
.room span{display:block;color:#FFF;font-size:1.4rem;margin-top:5px;}
.room p{font-size:1rem;}
.desc{padding:5px}

</style>
<div class="box">
<div class="use">
<a href="javascript:history.go(-1)" class="back_a"><span class="btn_back">&nbsp;&nbsp;&nbsp;返回</span></a>
<span class="center_title"><?php echo $category_name;?></span>
</div>
<?php if($list) { ?>
<div class="list" style="width:98%;margin:0 auto;padding-top:45px;"><?php if(is_array($list)) foreach($list as $val) { ?><a href="/play?cid=<?php echo $val['cid'];?>">
<div class="room">
<div class="img_right1" style="width:100%;position: relative;height: 13.3rem;overflow: hidden;">
<div style="width:100%;"><img src="" data-original='http://vip.52show.com/v2/<?php echo $val['file_path'];?>' style="width:100%;height:13.5rem;"/></div>
</div>
<div class="desc">
<span><?php echo $val['name'];?></span>
<p style="overflow: hidden;white-space: nowrap;text-overflow: ellipsis;color: #AEA3A3;">简介:<?php echo $val['description'];?></p>
</div>
</div>
</a>
<?php } ?>
<div style="clear:both;"></div>
</div>
<input type="hidden" value="<?php echo $cid;?>" id="cid" />
<?php } ?>
<div style="height:70px;"></div>
</div>

</body>

<script>
//更新manifest
//if (window.applicationCache.status == window.applicationCache.UPDATEREADY){
        window.applicationCache.update(); 
//}

$("img").lazyload({    
    placeholder : "lazy-grey.gif",    
    effect      : "fadeIn"
});  
$('img').lazyload();
var page_num=1;
var cid = $("#cid").val();
$(document).ready(function(){
$(window).scroll(function() {
if($(document).scrollTop()>=$(document).height()-$(window).height()){
var div1tem = $('.box').height()
var str =''
page_num = Number(page_num)+Number(1);
$.ajax({
    type:"GET",
    url:'list.do_load?cid='+cid+'&page='+page_num+'',
    dataType:'json',
    beforeSend:function(){
    },
    success:function(data){
    	if(data.ret==1){
    		$(".list").append(data.content); //将ajax请求的数据追加到内容里面
    		$('img').lazyload();
    	}
    }
})

}
  	});
    });




</script>
</html>
