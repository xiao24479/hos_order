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
<link href="static/js/datepicker/css/datepicker.css" rel="stylesheet" />
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
					<h4><i class="icon-reorder"></i><?php echo $this->lang->line('ask_data_input'); ?></h4>
					<span class="tools">
						<a href="javascript:;" class="icon-chevron-down"></a>
						<a href="javascript:;" class="icon-remove"></a>
					</span>
					</div>-->
<div class="widget-body form">
<form action="?c=order&m=ask_data_update" enctype="multipart/form-data" method="post" class="form-horizontal">
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('data_type');?></label>
	<div class="controls">
	<select name="data_type" id="data_type">
	   <option value="0"><?php echo $this->lang->line('please_select');?></option>
       <option value="1"><?php echo $this->lang->line('swt');?></option>
       <option value="2"><?php echo $this->lang->line('bd_qiao');?></option>
	</select>
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('data_site');?></label>
	<div class="controls">
	<select name="data_site" class="input-xxlarge" id="data_site">
	   <option value="0"><?php echo $this->lang->line('please_select');?></option>
       <?php foreach($site_list as $val):?>
       <option value="<?php echo $val['site_id']?>"><?php echo $val['site_domain']; if(!empty($val['site_mobile_domain'])){ echo "（手机站：" . $val['site_mobile_domain'] . "）";}?></option>
       <?php endforeach;?>
	</select>
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('data_time');?></label>
	<div class="controls">
		<input type="text" name="data_time" value="<?php echo date("Y-m-d", time() - 86400); ?>" class="input-xlarge" id="data_time"/>
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('data_file');?></label>
	<div class="controls">
		<input type="file" name="file" class="input-xlarge" />
	</div>
</div>
<div class="control-group">
	<div class="controls">
		<input type="hidden" name="form_action" value="add" /><input type="submit" name="submit" value="<?php echo $this->lang->line('submit'); ?>" class="btn btn-success"/>  <input name="reset" type="reset" value="<?php echo $this->lang->line('reset'); ?>" class="btn"/>
	</div>
</div>
</form>
</div>
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
   <script src="static/js/datepicker/js/datepicker.js"></script> 
   <script src="static/js/common-scripts.js"></script>
<script language="javascript">
$(document).ready(function(e) {
    $("#data_type").change(function(){
		var type = $(this).val();
		if(type == 1)
		{
			$("#data_site").attr("disabled", true);
		}
		else
		{
			$("#data_site").attr("disabled", false);
		}
	});
	
	$('#data_time').DatePicker({
		format:'Y-m-d',
		date: $('#data_time').val(),
		current: $('#data_time').val(),
		starts: 1,
		position: 'right',
		onBeforeShow: function(){
			$('#data_time').DatePickerSetDate($('#data_time').val(), true);
		},
		onChange: function(formated, dates){
			$('#data_time').val(formated);
			$('#data_time').DatePickerHide();
		}
	});
});
</script>
</body>
</html>