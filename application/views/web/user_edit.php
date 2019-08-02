<!doctype html>
<html lang="en" class="no-js m">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1 user-scalable=no">
  <meta http-equiv="Cache-Control" content="no-siteapp"/>
  <meta name="renderer" content="webkit">
  <title>修改个人资料 - <?php echo $hos_name;?></title>
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


<form class="am-form" method="post">
  <fieldset>
    <legend>修改个人资料</legend>

    <div class="am-form-group">
      <label for="doc-ipt-email-1">姓名</label>
      <input type="text" name="username" id="doc-ipt-email-1" value="<?php if(!empty($username)){echo $username;}?>">
    </div>

    <div class="am-form-group">
      <label class="am-radio-inline">
        <input type="radio"  value="1" name="sex" name="docInlineRadio" <?php if($sex==1){echo 'checked';}?> > 男
      </label>
      <label class="am-radio-inline">
        <input type="radio" value="2" name="sex" name="docInlineRadio" <?php if($sex==2){echo 'checked';}?> > 女
      </label>
    </div>
    
    <div class="am-form-group">
      <label for="doc-ipt-pwd-1">年龄</label>
      <input type="text" name="age" id="doc-ipt-pwd-1" value="<?php if(!empty($age)){echo $age;}?>">
    </div>

    <div class="am-form-group">
      <label for="doc-ipt-pwd-1">手机</label>
      <input type="text" name="phone" id="doc-ipt-pwd-1" value="<?php if(!empty($phone)){echo $phone;}?>">
    </div>

    <div class="am-form-group">
      <label for="doc-ipt-pwd-1">QQ</label>
      <input type="text" name="qq" id="doc-ipt-pwd-1" value="<?php if(!empty($qq)){echo $qq;}?>">
    </div>

    <div class="am-form-group">
      <label for="doc-ipt-pwd-1">邮箱</label>
      <input type="email" name="email" id="doc-ipt-pwd-1" value="<?php if(!empty($email)){echo $email;}?>">
    </div>
    <p><button type="submit" class="am-btn am-btn-default">提交</button></p>
  </fieldset>
</form>


<!--底部菜单-->
<?php $this->load->view('web/foot');?>
<!--底部菜单-->
</body>
</html>