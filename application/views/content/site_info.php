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
<form action="?c=system&m=site_update" method="post" class="form-horizontal">
<div class="control-group">
	<label class="control-label">网站名称</label>
	<div class="controls">
		<input type="text" value="<?php echo $info['sitename'];?>" class="input-xlarge" name="sitename" />
	</div>
</div>

<div class="control-group">
	<label class="control-label">网站域名</label>
	<div class="controls">
		<input type="text" value="<?php echo $info['domain'];?>" class="input-xlarge" name="domain" />
	</div>
</div>
<div class="control-group">
	<label class="control-label">网站类型</label>
	<div class="controls">
		<SELECT style="width:150" name="sysid"> 
			<OPTION value="">请选择类型...</OPTION> 
			<OPTION value="1" <?php if($info['sysid']==1){echo 'selected';};?> >phpcms</OPTION> 
			<OPTION value="2" <?php if($info['sysid']==2){echo 'selected';};?> >dedecms</OPTION> 
			<OPTION value="3" <?php if($info['sysid']==3){echo 'selected';};?> >其他</OPTION> 
		</SELECT> 
	</div>
</div>
<div class="control-group">
	<label class="control-label">所属医院</label>
	<div class="controls">
		<SELECT style="width:150" name="hos_id"> 
			<OPTION value="">请选择医院...</OPTION> 
			<?php foreach($hospital as $item): ?>
					<OPTION value="<?php echo $item['hos_id']; ?>" <?php if($info['hos_id']==$item['hos_id']){echo 'selected';};?> ><?php echo $item['hos_name']; ?></OPTION> 
			<?php endforeach; ?>
		</SELECT> 
	</div>
</div>
<div class="control-group">
	<div class="controls">
		<input type="hidden" name="form_action" value="update" /><input type="hidden" name="st_id" value="<?php echo $info['st_id']; ?>" />
		<button type="submit" class="btn btn-success"><i class="icon-ok"></i> <?php echo $this->lang->line('submit'); ?> </button>
		<button type="reset" class="btn"><i class="icon-remove"></i> <?php echo $this->lang->line('reset'); ?> </button>
	</div>
</div>
  </form>
  <?php else:?>
  <form action="?c=system&m=site_update" method="post" class="form-horizontal">
<div class="control-group">
	<label class="control-label">网站名称</label>
	<div class="controls">
		<input type="text" value="" class="input-xlarge" name="sitename" />
	</div>
</div>

<div class="control-group">
	<label class="control-label">网站域名</label>
	<div class="controls">
		<input type="text" value="" class="input-xlarge" name="domain" />
	</div>
</div>
<div class="control-group">
	<label class="control-label">网站类型</label>
	<div class="controls">
		<SELECT style="width:150" name="sysid"> 
			<OPTION value="">请选择类型...</OPTION> 
			<OPTION value="1">phpcms</OPTION> 
			<OPTION value="2">dedecms</OPTION> 
			<OPTION value="3">其他</OPTION> 
		</SELECT> 
	</div>
</div>
<div class="control-group">
	<label class="control-label">所属医院</label>
	<div class="controls">
		<SELECT style="width:150" name="hos_id"> 
			<OPTION value="">请选择医院...</OPTION> 
			<?php foreach($hospital as $item): ?>
					<OPTION value="<?php echo $item['hos_id']; ?>" ><?php echo $item['hos_name']; ?></OPTION> 
			<?php endforeach; ?>
		</SELECT> 
	</div>
</div>
<div class="control-group">
	<div class="controls">
		<input type="hidden" name="form_action" value="add" />
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
</body>
</html>