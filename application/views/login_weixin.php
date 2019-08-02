<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8" />
<title><?php echo $admin['name'] . '-' . $title; ?></title>
<meta content="width=device-width, initial-scale=1.0" name="viewport" />
<meta content="" name="description" />
<meta content="" name="author" />
<link href="static/css/bootstrap.min.css" rel="stylesheet" />
<link href="static/css/bootstrap-responsive.min.css" rel="stylesheet" />
<link href="static/css/font-awesome.css" rel="stylesheet" />
<link href="static/css/style.css" rel="stylesheet" />
<link href="static/css/style-responsive.css" rel="stylesheet" />
<link href="static/css/style-default.css" rel="stylesheet" id="style_color" />
<style>
.login-wrap{ padding:0; margin:0 auto;}
h2{ line-height:50px; color:#FFF; font-size:20px; text-align:center; margin-top:10px;}
#info{ line-height:50px; color:#FFF; font-size:20px; text-align:center;}
</style>
</head>

<body class="lock">
<div class="lock-header">
  <!-- BEGIN LOGO -->
  <a class="center" id="logo" href="?c=index&m=login"><img class="center" alt="logo" src="static/images/logo.png"></a>     
  </div>
  <h2>请扫描下面微信二维码进行登录！</h2>
  <div class="login-wrap"></div>
  <div id="info"></div>
</body>
<!-- END BODY -->
<script src="static/js/jquery.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$(".login-wrap").html("获取中...");
	var id = <?php echo $id?>;
	$.getScript("http://w.renaidata.com/?act=scan&type=login&id=" + id, function(){
		$(".login-wrap").html("<img src=\"" + src + "\" width=\"300\" />");
	});
});
var time_load = setInterval("loading()", 1000);
function loading()
{
	window.clearTimeout(time_load);
	var id = <?php echo $id?>;
	$.ajax({
		type:'post',
		url:'?c=index&m=weixin_login',
		data:'id=' + id,
		success:function(data)
		{
			if(data != 0)
			{
				$("#info").html("您已经登录成功！");
				setTimeout(function(){
			      weigo();
				}, 1000);
			}
		},
		complete: function (XHR, TS)
		{
		   XHR = null;
		   time_load = setInterval("loading()", 1000);
		}
	});
}

function weigo()
{
	//alert(data);
	window.location.href = './?c=index&m=weixin_login_ck';
}
</script>
</html>