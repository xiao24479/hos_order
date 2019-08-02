<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8" />
<title><?php echo $title; ?></title>
<meta content="width=device-width, initial-scale=1.0" name="viewport" />
<meta content="" name="description" />
<meta content="" name="author" />
<link href="static/css/bootstrap.min.css" rel="stylesheet" />
<link href="static/css/bootstrap-responsive.min.css" rel="stylesheet" />
<link href="static/css/font-awesome.css" rel="stylesheet" />
<link href="static/css/style.css" rel="stylesheet" />
<link href="static/css/style-responsive.css" rel="stylesheet" />
<link href="static/css/style-default.css" rel="stylesheet" id="style_color" />
<link type="text/css" href="static/css/jquery-ui-1.8.17.custom.css" rel="stylesheet" />
<link type="text/css" href="static/css/jquery-ui-timepicker-addon.css" rel="stylesheet" />
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
  <?php if(empty($info)): ?>
  <form action="?c=order&m=talk_update" method="post" class="form-horizontal">
<div class="control-group">
	<label class="control-label">咨询员</label>
	<div class="controls">
		<input type="text" value="<?php echo $add_name ?>" class="input-xlarge" name="zx_name" />
	</div>
</div>
<div class="control-group">
	<label class="control-label">对话途径</label>
	<div class="controls">
		<select name="zx_from">
	   <option value="0"><?php echo $this->lang->line('please_select');?></option>
	   <?php foreach($from_list as $item):?>
	   <option value="<?php echo $item['from_id'];?>"><?php echo $item['from_name'];?></option>
	   <?php endforeach;?>
	   </select>
	</div>
</div> 
<div class="control-group">
	<label class="control-label">日期</label>
	<div class="controls">
		<input type="text" value="" class="input-xlarge" name="zx_data" id="inputDate"/>
	</div>
</div>
<div class="control-group">
	<label class="control-label">对话数</label>
	<div class="controls">
		<input type="text" value="" class="input-xlarge" name="zx_num" />
	</div>
</div>
<div class="control-group">
	<div class="controls">
		<input type="hidden" name="form_action" value="add" /><input type="hidden" name="add_id" value="<?php echo $add_id ?>" /><input type="submit" name="submit" value="<?php echo $this->lang->line('submit'); ?>" class="btn btn-success"/>  <input name="reset" type="reset" value="<?php echo $this->lang->line('reset'); ?>" class="btn"/>
	</div>
</div>
  </form>
  <?php else:?>
  <form action="?c=order&m=admin_action_update" method="post" class="form-horizontal">
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('act_name');?></label>
	<div class="controls">
		<input type="text" value="" class="input-xlarge" name="act_name" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('parent_id');?></label>
	<div class="controls">
		<select name="parent_id" class="sel">
	   <option value="0"><?php echo $this->lang->line('please_select');?></option>
	   <?php foreach($menu as $item):?>
	   <option value="<?php echo $item['act_id'];?>"><?php echo $item['act_name'];?></option>
	   <?php 
	   if($item['son']):
	   foreach($item['son'] as $list):?>
	   <option value="<?php echo $list['act_id'];?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $list['act_name'];?></option>
	   <?php
	   endforeach;
	   endif;
	   ?>
	   <?php endforeach;?>
	   </select>
	</div>
</div> 
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('act_action');?></label>
	<div class="controls">
		<input type="text" value="" class="input-xlarge" name="act_action" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('act_url');?></label>
	<div class="controls">
		<input type="text" value="" class="input-xlarge" name="act_url" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('is_show');?></label>
	<div class="controls">
	    <label class="radio">
			<input type="radio" name="is_show" value="1" checked="checked" />
			<?php echo $this->lang->line('yes');?>
		</label>&nbsp;&nbsp;&nbsp;&nbsp;
		<label class="radio">
			<input type="radio" name="is_show" value="0" />
			<?php echo $this->lang->line('no');?>
		</label>
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('order');?></label>
	<div class="controls">
		<input type="text" value="" class="input-xlarge" name="act_order" />
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
    <script type="text/javascript" src="static/js/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="static/js/jquery-ui-1.8.17.custom.min.js"></script>
	<script type="text/javascript" src="static/js/jquery-ui-timepicker-addon.js"></script>
    <script type="text/javascript" src="static/js/jquery-ui-timepicker-zh-CN.js"></script>
<script>
    $(function () {
        $("#inputDate").datetimepicker({
            //showOn: "button",
            //buttonImage: "./css/images/icon_calendar.gif",
            //buttonImageOnly: true,
            showSecond: true,
            timeFormat: '',
            stepHour: 1,
            stepMinute: 1,
            stepSecond: 1
        })
    });
</script>
</body>
</html>