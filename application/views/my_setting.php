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
<link rel="stylesheet" type="text/css" href="static/css/chosen.css" />
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
<?php if($rank_type == 2):?>
<div class="widget green" >
					<div class="widget-title" style="background-color:#00a186;">
					<h4><i class="icon-reorder"></i> 在线客服账号设置</h4>
					<span class="tools">
						<a href="javascript:;" class="icon-chevron-down"></a>
						<a href="javascript:;" class="icon-remove"></a>
					</span>
					</div>
					<div class="widget-body">
<form action="?c=index&m=asker_name" method="post" class="form-horizontal" >
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('swt_name');?></label>
	<div class="controls">
		<input type="text" value="<?php echo $asker['asker_swt_name']; ?>" class="input-xlarge" name="asker_swt_name" id="asker_swt_name" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('qiao_name');?></label>
	<div class="controls">
		<input type="text" value="<?php echo $asker['asker_qiao_name']; ?>" class="input-xlarge" name="asker_qiao_name" id="asker_qiao_name" />
	</div>
</div>
<div class="control-group">
	<div class="controls">
		<input type="hidden" name="admin_id" value="<?php echo $asker['admin_id'];?>"/><input type="submit" name="submit" value="<?php echo $this->lang->line('submit'); ?>" class="btn btn-danger"/> <input name="reset" type="reset" value="<?php echo $this->lang->line('reset'); ?>" class="btn"/>
	</div>
</div>
</form>
 </div>
</div>
<?php endif; ?>
<div class="widget green" >
					<div class="widget-title" style="background-color:#00a186;">
					<h4><i class="icon-reorder"></i> 微信账号绑定</h4>
					<span class="tools">
						<a href="javascript:;" class="icon-chevron-down"></a>
						<a href="javascript:;" class="icon-remove"></a>
					</span>
					</div>
<div class="widget-body">
<?php if(!empty($openid)):?>
    你已经绑定了微信账号，若需要修改，请联系管理员！<br />在微信公众号中回复：90120，可查看近期数据！
<?php else:?>
  <div class="row-fluid">
    <div class="span4">
        <h3>第一步：关注仁爱医院微信公众账号</h3>
        <div class="well">
            <img src="static/images/weixin.jpg" alt="仁爱医院微信公众账号" width="150" />
        </div>
    </div>
    <div class="span6">
        <h3>第二步：获取绑定二维码，用微信扫描二维码进行绑定</h3>
        <div class="well" id="bangding">
            <a href="javascript:weixin_get();">获取绑定二维码</a>
        </div>
    </div>
  </div>
<?php endif;?>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

   <script src="static/js/jquery.js"></script>
   <script src="static/js/jquery.nicescroll.js" type="text/javascript"></script>
   <script src="static/js/bootstrap.min.js"></script>
   <script type="text/javascript" src="static/js/bootstrap-datepicker.js"></script>
   <script src="static/js/chosen.jquery.js"></script>
   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="static/js/excanvas.js"></script>
   <script src="static/js/respond.js"></script>
   <![endif]-->
   <script src="static/js/common-scripts.js"></script>
<script type="text/javascript">
function weixin_get()
{
	$("#bangding").html("获取中...");
	var admin_id = <?php echo $admin_id; ?>;
	$.getScript("http://w.renaidata.com/?act=scan&type=set&id=" + admin_id, function(){
		$("#bangding").html("<img src=\"" + src + "\" width=\"150\" />");
	});
}

$("#asker_swt_name").focusout(function(){
	var name = $(this).val();
	if(name != "")
	{
		$(this).after("<i class='icon-refresh icon-spin'></i>");
		$.ajax({
			type:'post',
			url:'?c=index&m=asker_name_ajax',
			data:'name=' + name + '&type=2',
			success:function(data)
			{
				$("#asker_swt_name").next("i").remove();
				$("#asker_swt_name").next("span").remove();
				$("#asker_swt_name").parent().parent().removeClass("error");
				if(data == 0)
				{
					$("#asker_swt_name").parent().parent().addClass("error");
					$("#asker_swt_name").after('<span class="help-inline">不能为空</span>');
				}
				else if(data == 1)
				{
					$("#asker_swt_name").parent().parent().addClass("error");
					$("#asker_swt_name").after('<span class="help-inline">此名称已被使用，请重新输入！</span>');
				}
			},
			complete: function (XHR, TS)
			{
			   XHR = null;
			}
		});
	}
});

$("#asker_qiao_name").focusout(function(){
	var name = $(this).val();
	if(name != "")
	{
		$(this).after("<i class='icon-refresh icon-spin'></i>");
		$.ajax({
			type:'post',
			url:'?c=index&m=asker_name_ajax',
			data:'name=' + name + '&type=1',
			success:function(data)
			{
				$("#asker_qiao_name").next("i").remove();
				$("#asker_qiao_name").next("span").remove();
				$("#asker_qiao_name").parent().parent().removeClass("error");
				if(data == 0)
				{
					$("#asker_qiao_name").parent().parent().addClass("error");
					$("#asker_qiao_name").after('<span class="help-inline">不能为空</span>');
				}
				else if(data == 1)
				{
					$("#asker_qiao_name").parent().parent().addClass("error");
					$("#asker_qiao_name").after('<span class="help-inline">此名称已被使用，请重新输入！</span>');
				}
			},
			complete: function (XHR, TS)
			{
			   XHR = null;
			}
		});
	}
});
</script>
</body>
</html>