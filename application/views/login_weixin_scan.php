<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximun-scale=1.0,initial-scale=1.0,user-scalable=no">
        <meta charset="UTF-8">
        <title>微信扫码</title>
        
        <style> 
		body{ text-align:center} 
		#content{ margin:0 auto;  margin-top:100px; border:0px solid} 
		
		  *{ margin:0; padding:0;}
		  #content{ background:url(static/img/login.jpg) top center no-repeat; background-size:auto 100%;}
		  @media screen and (min-width:320px) and ( max-width:464px) {
			body{ font-size:10px;}
			#content{ background:url(static/img/login_mobile.jpg) top center no-repeat; background-size:auto 100%;}
		  }
		  @media screen and ( max-width:320px) {
			body{ font-size:10px;}
			#content{ background:url(static/img/login_mobile.jpg) top center no-repeat; background-size:auto 100%;}
		  }
  
		/* css注释：为了观察效果设置宽度 边框 高度等样式 */ 
		</style>  
  </head>
    <body  >
    <div id="content" style="position:absolute; width:100%; height:100%; z-index:-1; margin-top:0px;"> 
    <?php 
        //产生唯一码
        $time = sprintf('%04X%04X%04X%04X%04X%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
        //测试APP 需要 和 wexinlogin.php 中配置一样
        $appid = 'wxe747c8ddc2edaef9'; 
        $redirect_uri = 'http://ceshi.renaidata.com/?c=weixinlogin&m=index&newtime='.$time;
        //正式APP 需要 和 wexinlogin.php 中配置一样
        $appid = 'wx745a64041574e32b'; 
        $redirect_uri = 'http://www.renaidata.com/?c=weixinlogin&m=index&newtime='.$time;
    	$auth_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".urlencode($redirect_uri)."&response_type=code&scope=snsapi_userinfo&state=".$appid."&connect_redirect=1#wechat_redirect";?>
   
     <!-- 唯一码 -->
     <input type="hidden"   name="newtime" id="newtime"  value="<?php echo $time;?>">
     
     <input type="hidden"    id="base_url"  value="<?php echo config_item('base_url');?>">
    <table style="margin:0 auto;margin-top: 20%;"> 
      <tr>
        <td><img src="<?php echo config_item('base_url');?>static/phpqrcode/barcode_create.php?url=<?php echo base64_encode($auth_url);?>"></td>
      </tr>
      <tr>
        <td id="msg">请打开手机扫码上面的二维码</td>
      </tr>
    </table> 
    </div>
  </body>
</html>
<script src="static/js/jquery.js"></script> 
<script language="javascript" type="text/javascript"> 
//循环执行，每隔3秒钟执行一次 showalert（）
window.setInterval(function(){
showalert("aaaaa");
}, 3000);
function showalert(mess)
{
	
	$.ajax({
		type:'get',
		async: true,
		dataType: 'html',
		url:'?c=index&m=ajax_scan_status',
		data:'newtime=' + $("#newtime").val(),
		success:function(msg)
		{
			if(msg !=0 ){
				$("#msg").html("<img src='static/img/icon_weixin_ok.png'>手机确认成功,系统即将登录。");
				window.location.href=$("#base_url").val()+msg;
			}
		},
		complete: function (XHR, TS)
		{
		   XHR = null;
		}
	});

	
//alert(mess);
}
//定时执 行，5秒后执行showalert()
window.setTimeout(function(){
showalert("bbbbbb");
},5000); 
</script>