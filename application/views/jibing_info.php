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
<form action="?c=system&m=jibing_update" method="post" class="form-horizontal">
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('jb_name');?></label>
	<div class="controls">
		<input type="text" value="<?php echo $info['jb_name']; ?>" class="input-xlarge" name="jb_name" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('jb_code');?></label>
	<div class="controls">
		<input type="text" value="<?php echo $info['jb_code']; ?>" class="input-xlarge" name="jb_code" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('jb_parent');?></label>
	<div class="controls">
	<select name="parent_id">
	   <option value="0"><?php echo $this->lang->line('please_select');?></option>
	   <?php echo $jibing; ?>
	</select>
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('order');?></label>
	<div class="controls">
		<input type="text" value="<?php echo $info['jb_order']; ?>" class="input-xlarge" name="jb_order" />
	</div>
</div>
<div class="control-group">
	<div class="controls">
		<input type="hidden" name="form_action" value="update" /><input type="hidden" name="jb_id" value="<?php echo $info['jb_id']; ?>" /><input type="submit" name="submit" value="<?php echo $this->lang->line('submit'); ?>" class="btn btn-success"/>  <input name="reset" type="reset" value="<?php echo $this->lang->line('reset'); ?>" class="btn"/>
	</div>
</div>
  </form>
  <?php else:?>
<form action="?c=system&m=jibing_update" method="post" class="form-horizontal">
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('jb_name');?></label>
	<div class="controls">
		<input type="text" value="" class="input-xlarge" name="jb_name" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('jb_code');?></label>
	<div class="controls">
		<input type="text" value="" class="input-xlarge" name="jb_code" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('jb_parent');?></label>
	<div class="controls">
	<select name="parent_id">
	   <option value="0"><?php echo $this->lang->line('please_select');?></option>
	   <?php echo $jibing; ?>
	</select>
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('order');?></label>
	<div class="controls">
		<input type="text" value="" class="input-xlarge" name="jb_order" />
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
</body>
</html>