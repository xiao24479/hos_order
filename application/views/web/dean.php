<!doctype html>
<html lang="en" class="no-js m">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="renderer" content="webkit">
<title>院长信箱</title>
<meta name="description" content="">
  <link rel="alternate icon" type="image/png" href="i/favicon.png">
  <link rel="icon" type="image/svg+xml" href="i/favicon.svg"/>
  <link rel="apple-touch-icon-precomposed" href="i/app-icon72x72@2x.png">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
   <link rel="stylesheet" href="static/phone/css/amui.all.min.css">
  <script src="static/phone/js/jquery.min.js"></script>
  <script src="static/phone/js/handlebars.min.js"></script>
  <script src="http://cdn.amazeui.org/amazeui/2.0.0/js/amazeui.min.js"></script>
</head>
<body>
	<?php $this->load->view('web/head');?>
  <!--菜单结束--> 

<!--面包屑-->
<ol class="am-breadcrumb">
  <li><a href="#" class="am-icon-home">首页</a></li>
  <li class="am-active">院长信箱</li>
</ol>
<!--面包屑-->
<hr/>
<script>
function check(){
var name = $.trim($("#name").val()); 
var phone = $.trim($("#phone").val()); 
var email = $.trim($("#email").val()); 
$("#namelabel").text("");
$("#phonelabel").text("");
$("#emaillabel").text("");
var isSuccess = 1;
if(name.length == 0)
{
$("#namelabel").text("姓名不能为空！")
$("#namelabel").css({"color":"red"}); 
isSuccess = 0;
}
if(phone.length == 0)
{
$("#phonelabel").text("电话不能为空！")
$("#phonelabel").css({"color":"red"}); 
isSuccess = 0;
}
if(email.length == 0)
{
$("#emaillabel").text("邮箱不能为空！")
$("#emaillabel").css({"color":"red"}); 
isSuccess = 0;
}
if(isSuccess == 0)
{
return false;
}
return true;
} 
</script>
<form method="post" action="http://www.renaidata.com/?c=show&m=ask_dean" onsubmit="return check()" class="am-form">
  <fieldset>
    <legend>院长信箱</legend>
    <div class="am-form-group">
      <label for="name">姓名</label>&nbsp;&nbsp;&nbsp;&nbsp;<label id="namelabel"></label>
      <input type="text" class="" id="name" name="name" placeholder="输入您的姓名">
    </div>
    <div class="am-form-group">
      <label for="email">邮箱</label>&nbsp;&nbsp;&nbsp;&nbsp;<label id="emaillabel"></label>
      <input type="text" class="" id="email" name="email" placeholder="输入您的邮箱">
    </div>
    <div class="am-form-group">
      <label for="phone">手机</label>&nbsp;&nbsp;&nbsp;&nbsp;<label id="phonelabel"></label>
      <input type="text" class="" id="phone" name="phone" placeholder="输入您的手机号">
    </div>
    <div class="am-form-group">
      <label for="topic">邮件主题</label>
      <input type="text" class="" id="topic" name="topic" placeholder="输入主题">
    </div>
    <div class="am-form-group">
      <label for="content">邮件正文</label>
      <textarea class="" rows="5" id="content" name="content"></textarea>
    </div>
    <p>
      <button type="submit" class="am-btn am-btn-default">提交</button>
    </p>
  </fieldset>
</form><br />
<br />


<!--底部菜单-->
<?php $this->load->view('web/foot');?>
<!--底部菜单-->
</body>
</html>