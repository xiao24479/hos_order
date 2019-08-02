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
<link rel="stylesheet" type="text/css" href="static/css/datepicker.css" />
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
<form action="?c=system&m=hospital_update" method="post" class="form-horizontal">
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('hos_name');?></label>
	<div class="controls">
		<input type="text" value="<?php echo $info['hos_name']; ?>" class="input-xlarge" name="hos_name" />
	</div>
</div>

<div class="control-group">
	<label class="control-label">预约卡</label>
	<div class="controls"> 
		<div class="span4 well"> 
			<a class="thumbnail">
				<img onclick="doUpload_suo()"  src="<?php if(empty($info['yuyuka'])):?>/static/img/upload.jpg<?php else: echo '/static/upload/'.$info['yuyuka']; endif;?>" alt="预约卡" width="260" height="180" id="yuyuka"/>
			</a>		
		</div>
	</div>
</div>
  
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('hos_time');?></label>
	<div class="controls">
		<div class="input-append date" id="dpYears" data-date="<?php echo date("Y-m-d", $info['hos_time']);?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
			<input class="m-ctrl-medium" size="16" type="text" value="<?php echo date("Y-m-d", $info['hos_time']);?>" name="hos_time" readonly>
			<span class="add-on"><i class="icon-calendar"></i></span>
		</div>
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('hos_tel');?></label>
	<div class="controls">
		<input type="text" value="<?php echo $info['hos_tel']; ?>" class="input-xlarge" name="hos_tel" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('hos_website');?></label>
	<div class="controls">
		<input type="text" value="<?php echo $info['hos_website']; ?>" class="input-xlarge" name="hos_website" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('hos_address');?></label>
	<div class="controls">
		<input type="text" value="<?php echo $info['hos_address']; ?>" class="input-xxlarge" name="hos_address" />
	</div>
</div>
<div class="control-group">
	<label class="control-label">短信接口</label>
	<div class="controls">
		<input type="text" value="<?php echo $info['sms_int']; ?>" class="input-xlarge" name="sms_int" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('sms_name');?></label>
	<div class="controls">
		<input type="text" value="<?php echo $info['sms_name']; ?>" class="input-xlarge" name="sms_name" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('sms_pwd');?></label>
	<div class="controls">
		<input type="text" value="<?php echo $info['sms_pwd']; ?>" class="input-xlarge" name="sms_pwd" />
	</div>
</div>
<div class="control-group">
	<label class="control-label">医院缩略名</label>
	<div class="controls">
		<input type="text" value="<?php echo $info['hos_slug']; ?>" class="input-xlarge" name="hos_slug" />
	</div>
</div>
<div class="control-group">
	<label class="control-label">咨询员权限</label>
	<div class="controls">
		<input type="radio" value="0" <?php if($info['ask_auth']==0){echo 'checked="checked"';}?> class="input-xlarge" name="ask_auth" /> 所有可见
		<input type="radio" value="1" <?php if($info['ask_auth']==1){echo 'checked="checked"';}?> class="input-xlarge" name="ask_auth" /> 自己可见
	</div>
</div>
<div class="control-group">
	<label class="control-label">商务通链接</label>
	<div class="controls">
		<input type="text" value="<?php echo $info['hos_swt']; ?>" class="input-xxlarge" name="hos_swt" />
	</div>
</div>
<div class="control-group">
	<label class="control-label">地理坐标</label>
	<div class="controls">
		<input type="text" value="<?php echo $info['hos_pos']; ?>" class="input-xlarge" name="hos_pos" />
	</div>
</div>
<div class="control-group">
	<div class="controls">
		<input type="hidden" name="form_action" value="update" />
		<input type="hidden" name="yuyuka" value="<?php echo $info['yuyuka'] ?>"/>
		<input type="hidden" name="hos_id" value="<?php echo $info['hos_id']; ?>" />
		<button type="submit" class="btn btn-success"><i class="icon-ok"></i> <?php echo $this->lang->line('submit'); ?> </button>
		<button type="reset" class="btn"><i class="icon-remove"></i> <?php echo $this->lang->line('reset'); ?> </button>
	</div>
</div>
  </form>
  <?php else:?>
  <form action="?c=system&m=hospital_update" method="post" class="form-horizontal">
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('hos_name');?></label>
	<div class="controls">
		<input type="text" value="" class="input-xlarge" name="hos_name" />
	</div>
</div>
<div class="control-group">
	<label class="control-label">预约卡</label>
	<div class="controls">
		
		<div class="span4 well">
			<a class="thumbnail">
				<img onclick="doUpload_suo()" src="/static/img/upload.jpg" alt="预约卡" width="260" height="180" id="yuyuka"/>
			</a>		
		</div>
	</div>
</div>

<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('hos_time');?></label>
	<div class="controls">
		<div class="input-append date" id="dpYears" data-date="<?php echo date("Y-m-d");?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
			<input class="m-ctrl-medium" size="16" type="text" value="<?php echo date("Y-m-d");?>" name="hos_time" readonly>
			<span class="add-on"><i class="icon-calendar"></i></span>
		</div>
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('hos_tel');?></label>
	<div class="controls">
		<input type="text" value="" class="input-xlarge" name="hos_tel" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('hos_website');?></label>
	<div class="controls">
		<input type="text" value="" class="input-xlarge" name="hos_website" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('hos_address');?></label>
	<div class="controls">
		<input type="text" value="" class="input-xxlarge" name="hos_address" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('kltx_name');?></label>
	<div class="controls">
		<input type="text" value="" class="input-xlarge" name="kltx_name" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('kltx_pwd');?></label>
	<div class="controls">
		<input type="text" value="" class="input-xlarge" name="kltx_pwd" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('kltx_max');?></label>
	<div class="controls">
		<input type="text" value="200" class="input-xlarge" name="kltx_max" />
	</div>
</div>
<div class="control-group">
	<div class="controls">
		<input type="hidden" name="form_action" value="add" />
		<input type="hidden" name="yuyuka" value=""/>
		<button type="submit" class="btn btn-success"><i class="icon-ok"></i> <?php echo $this->lang->line('submit'); ?> </button>
		<button type="reset" class="btn"><i class="icon-remove"></i> <?php echo $this->lang->line('reset'); ?> </button>
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
   <script type="text/javascript" src="static/js/bootstrap-datepicker.js"></script>
   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="static/js/excanvas.js"></script>
   <script src="static/js/respond.js"></script>
   <![endif]-->
   <script src="static/js/common-scripts.js"></script> 
   <script type="text/javascript" src="static/js/jquery.upload.js"></script>
<script>
$('#dpYears').datepicker();


function doUpload_suo() {
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
				$("#yuyuka").attr("src", data.url); 	
				$("input[name='yuyuka']").val(data.name);
			}
	});
} 
</script>
</body>
</html>