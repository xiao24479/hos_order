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
<form action="?c=order&m=type_update" method="post" class="form-horizontal">
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('type_name');?></label>
	<div class="controls">
		<input type="text" value="<?php echo $info['type_name']; ?>" class="input-xlarge" name="type_name" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('hos_id');?></label>
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
	<label class="control-label"><?php echo $this->lang->line('keshi_id');?></label>
	<div class="controls">
	   <select name="keshi_id" id="keshi_id">
	   <option value="0"><?php echo $this->lang->line('please_select');?></option>
	   </select>
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('type_desc');?></label>
	<div class="controls">
		<textarea class="input-xlarge" rows="5" name="type_desc" ><?php echo $info['type_desc']; ?></textarea>
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('order');?></label>
	<div class="controls">
		<input type="text" value="<?php echo $info['type_order']; ?>" class="input-xlarge" name="type_order" />
	</div>
</div>
<div class="control-group">
	<div class="controls">
		<input type="hidden" name="form_action" value="update" /><input type="hidden" name="type_id" value="<?php echo $info['type_id']; ?>" /><input type="submit" name="submit" value="<?php echo $this->lang->line('submit'); ?>" class="btn btn-success"/>  <input name="reset" type="reset" value="<?php echo $this->lang->line('reset'); ?>" class="btn"/>
	</div>
</div>
  </form>
  <?php else:?>
  <form action="?c=order&m=type_update" method="post" class="form-horizontal">
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('type_name');?></label>
	<div class="controls">
		<input type="text" value="" class="input-xlarge" name="type_name" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('hos_id');?></label>
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
	<label class="control-label"><?php echo $this->lang->line('keshi_id');?></label>
	<div class="controls">
	   <select name="keshi_id" id="keshi_id">
	   <option value="0"><?php echo $this->lang->line('please_select');?></option>
	   </select>
	</div>
</div>

<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('type_desc');?></label>
	<div class="controls">
		<textarea class="input-xlarge" rows="5" name="type_desc" ></textarea>
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('order');?></label>
	<div class="controls">
		<input type="text" value="50" class="input-xlarge" name="type_order" />
	</div>
</div>
<div class="control-group">
	<div class="controls">
		<input type="hidden" name="form_action" value="add" /><input type="submit" name="submit" value="<?php echo $this->lang->line('submit'); ?>" class="btn btn-success"/>  <input name="reset" type="reset" value="<?php echo $this->lang->line('reset'); ?>" class="btn"/>
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
<script>
$(document).ready(function(e) {
    $("#hos_id").change(function(){
		var hos_id = $(this).val();
		ajax_get_keshi(hos_id, 0);
	});
	
	if(is_edit > 0)
	{
		ajax_get_keshi($("#hos_id").val(), check_id);
	}
});

function ajax_get_keshi(hos_id, check_id)
{
	$("#keshi_id").after("<i class='icon-refresh icon-spin'></i>");
	$.ajax({
		type:'post',
		url:'?c=system&m=keshi_list_ajax',
		data:'hos_id=' + hos_id + '&type=1&check_id=' + check_id,
		success:function(data)
		{
			$("#keshi_id").html(data);
		},
		complete: function (XHR, TS)
		{
		   XHR = null;
		   $("#keshi_id").next(".icon-spin").remove();
		}
	});
}
</script>
</body>
</html>