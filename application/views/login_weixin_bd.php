<html><head>
        <meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximun-scale=1.0,initial-scale=1.0,user-scalable=no">
        <title>微信账户绑定</title>
  </head>
  <style>
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
  </style>
    <body> 
        <div id="content" style="position:absolute; width:100%; height:100%;  margin-top:0px;"> 
       	     
        
             <div style="position:fixed; z-index: 1;width:20%;height: 20%;margin-left: 50%;margin-top: 18%;">
            
                <div style="background: url(static/img/username.png);width: 280px;height:46px;margin-left: -140px;">
                <input type="text" style="height:46px;width:224px;margin-left:62px;background-color:transparent;border:none; outline:none; color:#808080;font-size: 18px;" placeholder="用户名" id="zh"></div>
                <div style="background: url(static/img/password.png);width: 280px;height:46px;margin-top: 7%;margin-left: -140px;">
                <input type="password" style="height:46px;width:224px;margin-left:62px;background-color:transparent;border:none; outline:none; color:#808080;font-size: 18px;" placeholder="密   码" id="pw"></div>
                <br>
                 <div style="background: url(static/img/username.png);width: 170px;height:46px;margin-left: -140px;">
                <table><tbody><tr> 
                <td><input type="text" style="height:46px;width:95px;margin-left:62px;background-color:transparent;border:none; outline:none; color:#808080;font-size: 18px;" placeholder="验证码" id="code"></td>
                <td><img id="change_code" title="点击刷新" style=" margin-top:-10px;margin-left:10px; height:44px;" src="<?php echo config_item('base_url');?>static/Validatecode/captcha.php" align="absbottom" onclick="this.src='<?php echo config_item('base_url');?>static/Validatecode/captcha.php?'+Math.random();"></img> 
               </td></tr>
                 </tbody></table>
                </div>
                <div style=" width: 280px;height:46px;margin-top: 7%;margin-left: -140px;"><input type="image" id="bd_sub" disabled="" style="height:46px;width:280px;" src="static/img/login_weixin_bd_no.jpg"></div>
                 
                <div style=" width: 280px;height:46px;margin-top: 7%;margin-left: -140px;" id="zh_msg"><?php if(isset($msg)){echo $msg;}?></div>
             
              
                 <input type="hidden" name="zh_id" id="zh_id" value=""> 
                 <!-- openid -->
                <input type="hidden"   name="openid" id="openid"  value="<?php echo base64_encode(urlencode($openid));?>">
                <!-- 唯一码 -->
                <input type="hidden"   name="newtime" id="newtime"  value="<?php echo base64_encode(urlencode($newtime));?>">
                
              </div> 
        </div> 
</body></html>




<script src="static/js/jquery.js"></script> 
<script src="static/js/base64/base.js"></script>

<script language="javascript" type="text/javascript"> 
 
	$("#zh").bind('input propertychange', function() {
		 get_msg();
	});
	$("#pw").bind('input propertychange', function() {
		 get_msg();
	});
	$("#code").bind('input propertychange', function() {
		 get_msg();
	}); 
			
    $("#bd_sub").click(function(){
	    if($("#zh_id").val() == null || $("#zh_id").val() == '' || $("#zh_id").val() == 0){
			$("#zh_msg").html("用户信息有误");
		}else  if($("#openid").val() == null || $("#openid").val() == '' || $("#openid").val() == 0){
			$("#zh_msg").html("用户信息有误");
		}else{
			$.ajax({
				type:'get',
				async: true,
				dataType: 'html',
				url:'?c=index&m=login_bd_from_wexin',
				data:'zh_id=' + $("#zh_id").val()+"&openid="+$("#openid").val()+"&newtime="+$("#newtime").val(),
				success:function(msg)
				{
					if(msg == 0){
						 alert("绑定失败,请重新绑定。");
					}else if(msg == 1){
						 alert("绑定成功,请前往网站查看。");
					}
				},
				complete: function (XHR, TS)
				{
				   XHR = null;
				}
			});
		}
	});  
	
	
	function  get_msg(){
		 $("#bd_sub").attr("src","static/img/login_weixin_bd_no.jpg");
		 $("#bd_sub").attr("disabled",true);
	    if($("#zh").val() == null || $("#zh").val() == '' || $("#zh").val() == 0){
			$("#zh_msg").html("请输入用户名称");
		}else if($("#pw").val() == null || $("#pw").val() == '' || $("#pw").val() == 0){
			$("#zh_msg").html("请输入密码");
		}else if($("#code").val() == null || $("#code").val() == '' || $("#code").val() == 0){
			$("#zh_msg").html("请输入验证码");
		}else{
			$("#zh_msg").html("确认中...");

			//将字符base编码+url编码
			var b = new Base64();
			var zh = encodeURIComponent(b.encode($("#zh").val()));
			var pw = encodeURIComponent(b.encode($("#pw").val()));
			$.ajax({
				type:'get',
				async: true,
				dataType: 'html',
				url:'?c=index&m=ajax_query_user',
				data:'zh=' + zh+"&pw="+pw+"&code="+$("#code").val(),
				success:function(msg)
				{
					$("#zh_id").val(0); 
					if(msg == 0){
						  $("#zh_msg").html("必须填写全部输入项"); 
					}else if(msg == 1){
						 $("#zh_msg").html("验证码有误");
					}else if(msg == 3){
						 $("#zh_msg").html("密码有误");
					}else if(msg == 4){
						 $("#zh_msg").html("用户名称有误"); 
					}else if(msg == 5){
						 $("#zh_msg").val("输入有误");
					}else{
						 $("#bd_sub").attr("disabled",false);
						 $("#bd_sub").attr("src","static/img/login_weixin_bd.jpg");
						 
						 $("#zh_msg").html("请点击确认绑定");
						 
						 $("#zh_id").val(msg);  
					} 
				},
				complete: function (XHR, TS)
				{
				   XHR = null;
				}
			});
		} 	
	}
</script>