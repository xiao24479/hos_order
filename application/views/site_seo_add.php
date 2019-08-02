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
					<div class="widget-title">
					<h4><i class="icon-reorder"></i><?php echo $this->lang->line('content_form'); ?></h4>
					<span class="tools">
						<a href="javascript:;" class="icon-chevron-down"></a>
						<a href="javascript:;" class="icon-remove"></a>
					</span>
					</div>
					<div class="widget-body">
<form action="?c=site&m=site_seo_update" method="post" class="form-vertical">
<div class="row-fluid">
	<div class="span3">
        <div class="control-group">
            <label class="control-label" ><?php echo $this->lang->line('admin_username');?></label>
            <div class="controls controls-row">
            <select name="admin_id">
                  <option value="0"><?php echo $this->lang->line('please_select');?></option>
                  <?php foreach($user_list as $val):?>
                  <option value="<?php echo $val['admin_id']?>" <?php if($_COOKIE['l_admin_id'] == $val['admin_id']){ echo "selected"; }?>><?php echo $val['admin_username'];?></option>  	 
                <?php endforeach;?>
            </select>
            </div>
        </div>
    </div>
    <div class="span3">
        <div class="control-group">
            <label class="control-label" ><?php echo $this->lang->line('record_time');?></label>
            <div class="controls controls-row">
				<input size="16" type="text" name="con_time" value="<?php echo date("Y-m-d", time());?>" readonly class="form_datetime">
            </div>
        </div>
    </div>
    <div class="span3">
        <div class="control-group">
            <label class="control-label" ><?php echo $this->lang->line('record_type');?></label>
            <div class="controls controls-row">
            <select name="record_type">
              <option value="0" ><?php echo $this->lang->line('please_select');?></option>
              <option value="1" selected="selected"><?php echo $this->lang->line('record_content'); ?></option>
              <option value="2" ><?php echo $this->lang->line('record_link'); ?></option>
            </select>
            </div>
        </div>
    </div>
    <div class="span3">
        <div class="control-group">
            <label class="control-label" ><?php echo $this->lang->line('site_list');?></label>
            <div class="controls controls-row">
            <select name="site_id" class="site_seo_add_select">
                  <option value="0" ><?php echo $this->lang->line('please_select');?></option>
                  <?php foreach($site as $val):?>
                  <option value="<?php echo $val['site_id']; ?>"><?php echo $val['site_domain'];?></option>  	 
                <?php endforeach;?>
            </select>
            </div>
        </div>
    </div>

</div>

<div class="control-group">
  <div class="controls controls-row">
	 <textarea rows="20" name="site_seo_content"  style=" width:98%"></textarea><br />
     <?php echo $this->lang->line('remind_info');?>
  </div>
</div>
<div class="control-group">
	<div class="controls controls-row">
		<button type="submit" class="btn btn-success"><i class="icon-ok"></i><?php echo $this->lang->line('submit'); ?></button>
		<button type="reset" class="btn"><i class="icon-remove"></i><?php echo $this->lang->line('reset'); ?></button>
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
   <script src="static/js/jquery.js"></script>
   <script src="static/js/jquery.nicescroll.js" type="text/javascript"></script>
   <script type="text/javascript" src="static/js/jquery.slimscroll.min.js"></script>
   <script src="static/js/bootstrap.min.js"></script>
   <script type="text/javascript" src="static/js/bootstrap-datepicker.js"></script>   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="static/js/excanvas.js"></script>
   <script src="static/js/respond.js"></script>
   <![endif]-->
   <script src="static/js/common-scripts.js"></script>
<script>
$(".form_datetime").datepicker({format: 'yyyy-mm-dd'});
</script>
</body>
</html>