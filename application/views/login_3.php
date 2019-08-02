<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8" />
<title>恒信健网络预约挂号管理系统后台登录</title>
<meta content="width=device-width, initial-scale=1.0" name="viewport" />
<meta content="" name="description" />
<meta content="" name="author" />
<link href="static/css/bootstrap.min.css" rel="stylesheet" />
<link href="static/css/bootstrap-responsive.min.css" rel="stylesheet" />
<link href="static/css/font-awesome.css" rel="stylesheet" />
<link href="static/css/style.css" rel="stylesheet" />
<link href="static/css/style-responsive.css" rel="stylesheet" />
<link href="static/css/style-default.css" rel="stylesheet" id="style_color" />
<script type="text/javascript">
<!--var url = window.location.href;
                if (url.indexOf("https") < 0) {
                    url = url.replace("http:", "https:");
                    window.location.replace(url);
                }-->
</script> 
<script>
    //声明_czc对象:
var _czc = _czc || [];
//绑定siteid，请用您的siteid替换下方"XXXXXXXX"部分
//_czc.push(["_setAccount", "1254115876"]);
//_czc.push(["_setCustomVar","<?php echo $_SERVER["REMOTE_ADDR"]; ?>","登录页",1]);
</script>
</head>

<body class="lock">

<div class="lock-header">
  <!-- BEGIN LOGO 
  <a class="center" id="logo" href="?c=index&m=login"><img class="center" alt="logo" src="static/images/logo.png"></a>-->
  </div>
  <div class="login-wrap">
    <h2 style="font-size:20px; line-height:30px;">请使用google浏览器或者360浏览器极速版打开系统</h2>
    <div class="metro single-size red" style="color:#eee;">
      <div class="locked">
        <i class="icon-lock"></i>
        <span>Login</span>
      </div>
    </div>
    <form action="?c=index&m=login_ck" method="post">
	<div class="metro double-size green">
      <div class="input-append lock-input">
        <input type="text" class="" placeholder="Username" name="admin_username"/>
      </div>
    </div>
    <div class="metro double-size yellow">
      <div class="input-append lock-input">
        <input type="password" class="" placeholder="Password" name="admin_password"/>
      </div>
    </div>
    <div class="metro single-size terques login">
      <button type="submit" class="btn login-btn">
        Login
        <i class=" icon-long-arrow-right"></i>
      </button>
    </div>
	<div class="metro double-size navy-blue ">
		<a href="./" target="_blank" class="social-link">
			<i class="icon-ambulance"></i>
			<span>学习交流</span>
		</a>
	</div>
	<div class="metro single-size deep-red">
		<a href="./" target="_blank" class="social-link">
			<i class="icon-stethoscope"></i>
			<span>妇产科交流</span>
		</a>
	</div>
	<div class="metro double-size blue">
		<a href="./" class="social-link">
			<i class="icon-hospital"></i>
			<span>数据仓库</span>
		</a>
	</div>
	<div class="metro single-size purple">
		<a href="?c=index&m=login&type=weixin" target="_blank" class="social-link">
			<i class="icon-comments"></i>
			<span>微信登录</span>
		</a>
	</div>
    <div class="login-footer">
      <div class="remember-hint pull-left">
        <label><input type="checkbox" name="remember" value="1"/> Remember Me</label>
      </div>
	<div class="forgot-hint pull-right">
       <a id="forget-password" class="" href="javascript:;">Forgot Password?</a>
    </div>
   </div>
   </form>
</div>
<!-- <div style="display: none;"><script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1254115876'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s11.cnzz.com/stat.php%3Fid%3D1254115876' type='text/javascript'%3E%3C/script%3E"));</script></div>
</body> -->
<!-- END BODY -->
</html>