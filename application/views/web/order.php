<!doctype html>
<html lang="en" class="no-js m">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1  user-scalable=no">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="renderer" content="webkit">
<title>预约挂号 - <?php echo $hos_name;?></title>
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


<form class="am-form" method="post" action="?c=show&m=order_ins">
  <fieldset>
    <legend>预约挂号</legend>
	<?php if(isset($doc_name)):?>
    <div class="am-form-group">
      <label for="doc-ipt-email-1">专家</label>
      <input type="text" class="" name="doc_name" id="doc-ipt-email-1" value="<?php echo $doc_name;?>" disabled>
    </div>
	<?php endif;?>
    <div class="am-form-group">
      <label for="doc-ipt-email-1">姓名</label>
      <input type="text" class="" name="pat_name" id="doc-ipt-email-1" placeholder="输入您的姓名">
    </div>
    <div class="am-form-group">
      <label for="doc-ipt-pwd-1">手机</label>
      <input type="text" class="" name="pat_phone" id="doc-ipt-pwd-1" placeholder="输入您的手机号">
    </div>
    <div class="am-form-group">
      <label for="doc-select-1">科室</label>
      <select id="doc-select-1" name="keshi">
	  <?php if(isset($doc_name)):?>
		<option value="<?php echo $keshi['keshi_id'];?>"><?php echo $keshi['keshi_name'];?></option>
	  <?php else:?>
        <option value="">请选择科室</option>
		<?php foreach($keshi_list as $val):?>
        <option value="<?php echo $val['keshi_id'];?>"><?php echo $val['keshi_name'];?></option>
		<?php endforeach;?>
	   <?php endif;?>
      </select>
    </div>
    <div class="am-form-group">
      <label for="doc-select-1">预计到诊时间</label>
      <select id="doc-select-1" name="order_time">
        <option value="1">一天内</option>
        <option value="3">三天内</option>
        <option value="7">一周内</option>
        <option value="0">未定</option>
      </select>
    </div>
    <div class="am-form-group">
      <label for="doc-ta-1">留言</label>
      <textarea class="" rows="5" name="mark_content" id="doc-ta-1"></textarea>
    </div>
    <p>
	<input type="hidden" value="<?php if(isset($openid)){echo $openid;} ?>" name="openid">
      <button type="submit" class="am-btn am-btn-default">提交</button>
    </p>
  </fieldset>
</form>

<!--底部菜单-->
<?php $this->load->view('web/foot');?>
<!--底部菜单-->
</body>
</html>