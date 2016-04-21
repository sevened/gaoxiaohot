<?php if(!defined('SP')) exit();?><?php include template('comn.header'); ?><style type="text/css">*{padding:0;margin:0}body{background-color:#F2F2F2;font-family:"Microsoft YaHei";}.box{background-color:#F2F2F2;max-width:640px;margin:0 auto;width:100%;margin-bottom:50px;}a{text-decoration:none}a:link{text-decoration:none}a:visited{text-decoration:none}a:hover{text-decoration:none}a:active{text-decoration:none}*{margin:0;padding:0;list-style-type:none}a,img{border:0}
.use{height:40px;background:#292335;line-height:38px;color:#000;font-size:14px;width:100%;max-width:640px;margin:0 auto;z-index:100;font-size:18px;font-family:Microsoft YaHei}
.back_a{display:inline-block;width:20%}.btn_back{background:url(/sprite/images/user-icon-jian-left.png) no-repeat center left;width:55px;text-align:right;background-size:25%;display:inline-block;margin-left:10px;color:#fff}.center_title{display:inline-block;width:60%;text-align:center;color:#FFF}
.tishi{height:40px;width:100%;line-height:40px;background-color:#B4AEAE;font-family:"Microsoft YaHei";}
.type{margin-top:5px;padding:15px;}.type_ca{margin-top:10px;}.radio{width:40%;display:block; float:left;margin:10px}.radio label{font-size:1rem}.radio input{margin-right:10px;}
.type_ca textarea{outline:none;padding:5px;font-family:"Microsoft YaHei";} .type_ca .text{width:100%;height:30px;outline:none;padding:5px;font-family:"Microsoft YaHei";} .type .bj{background-color:#4EDFB0;width:50%;margin:auto;height:40px;border-radius:10px;text-align:center;line-height:40px;color:#fff;cursor:pointer}
.bj input{background-color:#4EDFB0;width:100%;margin:auto;height:40px;border-radius:10px;text-align:center;line-height:40px;color:#fff;cursor:pointer;border:0px;outline:none}
#tips {
width: 100%;
height: 100%;
position: fixed;
top: 0;
left: 0;
z-index: 1001;
display: none;font-size:1rem
}
#tips .tipContent{position:absolute;height:auto;max-width:70%;min-width:160px;opacity:1;z-index:1002;background: rgba(0,0,0,.8);color:#fff;padding:15px 20px;-webkit-box-shadow:0 1px 10px rgba(0,0,0,.6);-webkit-border-radius:5px;text-align:center;}
#tips .suc{background-color:#68af02}
#tips .err{background-color:#fcc;color:#900}
#tips .mask {
  position: fixed;
  top: 0;
  left: 0;
  background: #000;
  opacity: .18;
  width: 100%;
  height: 100%;
  -webkit-transform: translateZ(0);
  -webkit-tap-highlight-color: rgba(255,255,255,0);
  -webkit-user-select: none;
  -webkit-user-drag: none;
}
</style>
<div class="box">
<div class="use">
<a href="/user" class="back_a"><span class="btn_back">&nbsp;&nbsp;&nbsp;返回</span></a>
<span class="center_title">意见反馈</span>
</div><!--
<div class="tishi">
<span style="color:#fff;font-size:16px;">&nbsp;&nbsp;&nbsp;&nbsp;付费问题请联系QQ客服:</span>
<span style="color:#FC7B0A;font-size:14px;">2851538080</span>
</div>-->
<form action="/user.feedback" method="post">
<div class="type">
<span>投诉类型:</span>
<div class="type_ca">
<span class="radio"><input type="radio" value="1" name="type" id="radio" value="付费问题"/><label for="radio">付费问题</label></span>
<span class="radio"><input type="radio" value="2" name="type" id="radio2" value="异常退出"/><label for="radio2">异常退出</label></span>
<span class="radio"><input type="radio" value="3" name="type" id="radio3" value="无法播放"/><label for="radio3">无法播放</label></span>
<span class="radio"><input type="radio" value="4" name="type" id="radio4" value="其他问题"/><label for="radio4">其他问题</label></span>
<span class="radio"><input type="radio" value="5" name="type" id="radio5" value="播放卡屏"/><label for="radio5">播放卡屏</label></span>
<div style="clear:both;"></div>
</div>
</div>
<div class="type">
<span>意见建议:</span>
<div class="type_ca">
<textarea name="suggest" style="width:100%;height:15%;"></textarea>
</div>
</div>
<div class="type">
<span>联系方式:</span>
<div class="type_ca">
<input type="text" class="text" placeholder="手机号码或者qq号" name="contact_way"/>
</div>
</div>
<div class="type">
<div class="bj"><input type="submit" value="确认提交"/></div>
</div>
</form>
</div>
<script src="/sprite/admin/js/require.js" type="text/javascript" data-main="/sprite/admin/js/no.js"></script>
</body>
</html>
