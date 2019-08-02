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
<script language="javascript">
var check_id = 0;
var is_edit = 0;
</script>
</head>

<body class="fixed-top">
<?php echo $top; ?>
<div id="container" class="row-fluid">
<?php echo $sider_menu; ?>

<div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <?php echo $themes_color_select; ?>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->

	  <div class="row-fluid">
		<div class="span12">
		  <div class="widget green">
<!--					<div class="widget-title">
					<h4><i class="icon-reorder"></i> <?php echo $this->lang->line('content_form'); ?></h4>
					<span class="tools">
						<a href="javascript:;" class="icon-chevron-down"></a>
						<a href="javascript:;" class="icon-remove"></a>
					</span>
					</div>-->
					<div class="widget-body">
<?php if(!empty($info)): ?>
<form action="?c=order&m=card_update" method="post" class="form-horizontal">
<div class="control-group">
	<label class="control-label">预约卡名称</label>
	<div class="controls">
		<input type="text" value="<?php echo $info['card_name'];?>" class="input-xlarge" name="card_name" />
	</div>
</div>
<div class="control-group">
	<label class="control-label">医院名称</label>
	<div class="controls">
	   <select name="hos_id" id="hos_id">
	   <option value="0"><?php echo $this->lang->line('please_select');?></option>
	   <?php foreach($hospital as $val){ ?>
	   <option value="<?php echo $val['hos_id'];?>" <?php if($val['hos_id'] == $info['hos_id']){ echo "selected";}?>><?php echo $val['hos_name'];?></option>
	   <?php } ?>
	   </select>
	   <script language="javascript">
	   check_id = <?php echo $info['keshi_id'];?>;
	   is_edit = 1;
	   </script>
	</div>
</div>
<div class="control-group">
	<label class="control-label">预约卡背景</label>
	<div class="controls">
		<div style="position:relative;" onMouseDown="test(event)" id="img_kuang"><img style="border: 1px solid #000;" src="<?php if(empty($info['img'])):?>http://www.w3cschool.cc/try/bootstrap/php-thumb.png<?php else: echo 'static/upload/'.$info['img']; endif;?>" alt="文章缩略图" id="img_suo"/></div>
		<div><div style="border: 1px solid #000;width:140px;height:30px;margin:5px 0;line-height:30px;float:left;" id="mjs">点击图片拾取坐标</div><div onclick="doUpload()" class="btn btn-success" style="float:left;margin:7px 10px;"> 换个背景  </div></div>
		
	</div>
	<input name="img" type="hidden" value="<?php echo $info['img'];?>">
	<input name="img_src" type="hidden" value="">
</div>
<div class="control-group">
	<label class="control-label">坐标分布</label>
	<div class="controls">
		<div class="span3">
			<div class="row-form">
				<label class="select_label">姓名</label>
				<input type="text" value="<?php if(!empty($info['username'])){echo $info['username'];}?>" placeholder="姓名" class="input-large" name="username" style="width: 60px;">
			</div>
			<div class="row-form">
				<label class="select_label">疾病</label>
				<input type="text" value="<?php if(!empty($info['jibing'])){echo $info['jibing'];}?>" placeholder="疾病" class="input-large" name="jibing" style="width: 60px;">
			</div>
		</div>
		<div class="span3">
			<div class="row-form">
				<label class="select_label">年龄</label>
				<input type="text" value="<?php if(!empty($info['age'])){echo $info['age'];}?>" placeholder="年龄"class="input-large" name="age" style="width: 60px;">
			</div>
			<div class="row-form">
				<label class="select_label">预约号</label>
				<input type="text" value="<?php if(!empty($info['orderno'])){echo $info['orderno'];}?>" placeholder="预约号" class="input-large" name="orderno" style="width: 60px;">
			</div>
		</div>
		<div class="span3">
			
			<div class="row-form">
				<label class="select_label">预约时间</label>
				<input type="text" value="<?php if(!empty($info['ordertime'])){echo $info['ordertime'];}?>" placeholder="预约时间" class="input-large" name="ordertime" style="width: 60px;">
			</div>
			<div class="row-form">
				<label class="select_label">电话</label>
				<input type="text" value="<?php if(!empty($info['phone'])){echo $info['phone'];}?>" placeholder="电话" class="input-large" name="phone" style="width: 60px;">
			</div>
		</div>
	</div>
</div>
<div class="control-group">
	<div class="controls">
		<input type="hidden" name="form_action" value="update" /><input name="card_id" type="hidden" value="<?php echo $info['card_id'];?>">
		<button type="submit" class="btn btn-success"><i class="icon-ok"></i> <?php echo $this->lang->line('submit'); ?> </button>
		<span  class="btn" onclick="read()">预览 </span>
</div>
</form>
  <?php else:?>
<form action="?c=order&m=card_update" method="post" class="form-horizontal">
<div class="control-group">
	<label class="control-label">预约卡名称</label>
	<div class="controls">
		<input type="text" value="" class="input-xlarge" name="card_name" />
	</div>
</div>
<div class="control-group">
	<label class="control-label">医院名称</label>
	<div class="controls">
	   <select name="hos_id" id="hos_id">
	   <option value="0"><?php echo $this->lang->line('please_select');?></option>
	   <?php foreach($hospital as $val){ ?>
	   <option value="<?php echo $val['hos_id'];?>"><?php echo $val['hos_name'];?></option>
	   <?php } ?>
	   </select>
	</div>
</div>
<div class="control-group">
	<label class="control-label">预约卡背景</label>
	<div class="controls">
		<div style="position:relative;" onMouseDown="test(event)" id="img_kuang"><img style="border: 1px solid #000;" src="<?php if(!isset($thumb)):?>http://www.w3cschool.cc/try/bootstrap/php-thumb.png<?php else: echo $thumb; endif;?>" alt="文章缩略图" id="img_suo"/></div>
		<div><div style="border: 1px solid #000;width:140px;height:30px;margin:5px 0;line-height:30px;float:left;" id="mjs">点击图片拾取坐标</div><div onclick="doUpload()" class="btn btn-success" style="float:left;margin:7px 10px;"> 换个背景  </div></div>
		
	</div>
	<input name="img" type="hidden" value="<?php if(isset($img)){echo $img;}else{ echo 'zampo.jpg';}?>">
	<input name="img_src" type="hidden" value="">
</div>
<div class="control-group">
	<label class="control-label">坐标分布</label>
	<div class="controls">
		<div class="span3">
			<div class="row-form">
				<label class="select_label">姓名</label>
				<input type="text" value="" placeholder="姓名" class="input-large" name="username" style="width: 60px;">
			</div>
			<div class="row-form">
				<label class="select_label">疾病</label>
				<input type="text" value="" placeholder="疾病" class="input-large" name="jibing" style="width: 60px;">
			</div>
		</div>
		<div class="span3">
			<div class="row-form">
				<label class="select_label">年龄</label>
				<input type="text" value="" placeholder="年龄"class="input-large" name="age" style="width: 60px;">
			</div>
			<div class="row-form">
				<label class="select_label">预约号</label>
				<input type="text" value="" placeholder="预约号" class="input-large" name="orderno" style="width: 60px;">
			</div>
		</div>
		<div class="span3">
			<div class="row-form">
				<label class="select_label">预约时间</label>
				<input type="text" value="" placeholder="预约时间" class="input-large" name="ordertime" style="width: 60px;">
			</div>
			<div class="row-form">
				<label class="select_label">电话</label>
				<input type="text" value="" placeholder="电话" class="input-large" name="phone" style="width: 60px;">
			</div>
		</div>
	</div>
</div>
<div class="control-group">
	<div class="controls">
		<input type="hidden" name="form_action" value="add" />
		<button type="submit" class="btn btn-success"><i class="icon-ok"></i> <?php echo $this->lang->line('submit'); ?> </button>
		<span  class="btn" onclick="read()">预览 </span>
	</div>
</div>
  </form>
  <?php endif; ?>
  </div>
</div>
</div>
</div>
</div>
</div>
</div>
   <script src="static/js/jquery.js"></script>
   <script src="static/js/jquery.nicescroll.js" type="text/javascript"></script>
   <script type="text/javascript" src="static/js/jquery.slimscroll.min.js"></script>
   <script src="static/js/bootstrap.min.js"></script>
   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="static/js/excanvas.js"></script>
   <script src="static/js/respond.js"></script>
   <![endif]-->
   <script src="static/js/common-scripts.js"></script>
    <script type="text/javascript" src="static/js/jquery.upload.js"></script>
<script>
$(document).ready(function(e) {
	
	if(is_edit > 0)
	{
		ajax_get_keshi($("#hos_id").val(), check_id);
	}
});

function read()
{
	var img = $('input[name="img"]').val();
	var age = $('input[name="age"]').val();
	var phone = $('input[name="phone"]').val();
	var username = $('input[name="username"]').val();
	var jibing = $('input[name="jibing"]').val();
	var ordertime = $('input[name="ordertime"]').val();
	var orderno = $('input[name="orderno"]').val();
	var img_suo = $('input[name="img_src"]').val();
	$.ajax({
		type:'post',
		url:'?c=order&m=card_set',
		data:'username=' + username + '&age=' + age + '&phone=' + phone + '&jibing=' + jibing + '&ordertime=' + ordertime + '&orderno=' + orderno + '&img=' + img + '&img_suo=' + img_suo ,
		success:function(data)
		{
			$("#img_suo").attr("src", data);
			$('input[name="img_src"]').val(data);
		},
		complete: function (XHR, TS)
		{
		   XHR = null;
		}
	});
}
function doUpload() {
		// 上传方法
		$.upload({
				// 上传地址
				url: '?c=weixin&m=upload', 
				// 文件域名字
				fileName: 'file', 
				// 其他表单数据
				params: {name: 'pxblog'},
				// 上传完成后, 返回json, text
				dataType: 'json',
				// 上传之前回调,return true表示可继续上传
				onSend: function() {
						return true;
				},
				// 上传之后回调
				onComplate: function(data) {
					$("#img_suo").attr("src", data.url); 	
					$("input[name='img']").val(data.name);
				}
		});
}
function mousePos(e){
	var x,y;
	var e = e||window.event;
	return {
	x:e.clientX+document.body.scrollLeft+document.documentElement.scrollLeft-397,
	y:e.clientY+document.body.scrollTop+document.documentElement.scrollTop-285
	};
};
function test(e){
	document.getElementById("mjs").innerHTML = mousePos(e).x+'-'+mousePos(e).y;    
};
</script>
</body>
</html>