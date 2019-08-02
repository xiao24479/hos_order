<!doctype html>
<html lang="en" class="no-js m">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1  user-scalable=no">
  <meta http-equiv="Cache-Control" content="no-siteapp"/>
  <meta name="renderer" content="webkit">
  <title>微信抽奖</title>
  <meta name="description" content="">
  <link rel="alternate icon" type="image/png" href="i/favicon.png">
  <link rel="icon" type="image/svg+xml" href="i/favicon.svg"/>
  <link rel="apple-touch-icon-precomposed" href="i/app-icon72x72@2x.png">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="stylesheet" href="static/phone/css/amui.all.min.css">
  <script src="static/phone/js/zepto.min.js"></script>
  <script src="static/phone/js/handlebars.min.js"></script>
  <script src="static/phone/js/amui.min.js"></script>
 
<style type="text/css">
.demo{width:100%; height:320px; position:relative; margin:5px auto}
#disk{width:320px; height:320px; background:url(static/phone/images/disk.jpg) no-repeat; background-size:320px 320px;}
#start{width:126px; height:246px; position:absolute; top:35px; left:98px;}
#start img{cursor:pointer}
</style>
<script type="text/javascript" src="static/phone/js/jquery.min.js"></script>
<script type="text/javascript" src="static/phone/js/jQueryRotate.2.2.js"></script>
<script type="text/javascript" src="static/phone/js/jquery.easing.min.js"></script>
<script type="text/javascript">
$(function(){ 
    $("#startbtn").click(function(){ 
		var sun_flag = <?php echo $sun_flag;?>;
		var prize_num = <?php echo $prize_num;?>;
		if(!sun_flag){
			alert('请先关注仁爱医院官方微信公众号');
			return false;
		}
		if(prize_num>=3){
			alert('您已经抽了3次,不能再抽了');
			return false;
		}
		
        lottery(); 
    }); 
}); 
function lottery(){ 
    $.ajax({ 
        type: 'POST', 
        url: '?c=show&m=chou_data', 
		data: 'wx_uid=<?php echo $wx_uid;?>',
        dataType: 'json', 
        cache: false, 
        error: function(xml){
        	//alert(xml);
            alert('出错了！'); 
            return false; 
        }, 
        success:function(json){ 
            $("#startbtn").unbind('click').css("cursor","default"); 
            var a = json.angle; //角度 
            var p = json.prize; //奖项 
            $("#startbtn").rotate({ 
                duration:3000, //转动时间 
                angle: 0, 
                animateTo:1800+a, //转动角度 
                easing: $.easing.easeOutSine, 
                callback: function(){ 
                    //var con = '恭喜你，中得'+p+'\n';
                   // alert(con);
                    
                   
                   var con = confirm('恭喜你，中得'+p+'\n还要再来一次吗？');
                   if(con){ 
                        lottery(); 
                    }else{ 
                        return false; 
                    } 
                } 
            }); 
        } 
    }); 
} 
</script>
</head>

<body>
<?php $this->load->view('web/head');?>


  <!--面包屑-->
 <ol class="am-breadcrumb">
  <li><a href="?c=show" class="am-icon-home">首页</a></li>
  <li class="am-active">抽奖页</li>
</ol>
  <!--面包屑-->
<hr/>


   <div class="am-list-news-bd">
	   <div class="msg"></div>
	   <div class="demo">
			<div id="disk"></div>
			<div id="start"><img src="static/phone/images/start.png" id="startbtn"></div>
	   </div>
    </div>



 
 
<?php $this->load->view('web/foot');?>
<!--底部菜单-->
</body>
</html>