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
					<h4 class="site_seo_h4"><i class="icon-reorder"></i> <?php echo $this->lang->line('site_seo_info'); ?></h4>
					<span class="tools">
						<a href="javascript:;" class="icon-chevron-down"></a>
						<a href="javascript:;" class="icon-remove"></a>
					</span>
					</div>
					<div class="widget-body">
<form action="?c=site&m=site_seo_link_update" method="post">
<div class="seo_list_info">
<div class="site_seo_add">
	<div class="site_seo_list_user"><?php echo $this->lang->line('admin_username');?></div>
	<div class="site_seo_list_username">
	<select name="admin_id" class="site_seo_user_select">
		  <option value="0" ><?php echo $this->lang->line('please_select');?></option>
		  <?php foreach($info as $val):?>
		  <option value="<?php echo $val['admin_id']?>" ><?php echo $val['admin_name'];?></option>  	 
		<?php endforeach;?>
	</select>
	</div>
	<div class="site_seo_add_record"><?php echo $this->lang->line('date');?></div>
	<div class="site_seo_add_record_time">
		<div class="input-append date" id="dpYears" data-date="<?php echo date("Y-m-d", time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years" >
			<input class="m-ctrl-medium" size="16" type="text" value="<?php echo date("Y-m-d", time());?>"  name="record_time" readonly>
			<span class="add-on"><i class="icon-calendar"></i></span>
		</div>
	</div>
	<div class="site_seo_add_record_r"><?php echo $this->lang->line('record_type');?></div>
	<div class="site_seo_add_option">
		<select name="record_type" class="site_seo_add_select">
		  <option value="0" ><?php echo $this->lang->line('please_select');?></option>
		  <option value="1" ><?php echo $this->lang->line('record_content'); ?></option>
		  <option value="2"  selected="selected"><?php echo $this->lang->line('record_link'); ?></option>
		</select>
	</div>
	<div class="site_seo_add_record_r"><?php echo $this->lang->line('site_list');?></div>
	<div class="site_seo_add_option">
	<select name="site_id" class="site_seo_add_select">
		  <option value="0" ><?php echo $this->lang->line('please_select');?></option>
		  <?php foreach($site as $val):?>
		  <option value="<?php echo $val['site_id']; ?>"><?php echo $val['site_domain'];?></option>  	 
		<?php endforeach;?>
	</select>
	</div>
	<div class="site_seo_add_record"><input type="submit" value="<?php echo $this->lang->line('seo_list_xs');?>" class="btn btn-success" name="submit"/></div>
</div>
</form>
<div class="widget-body">
<table width="100%" border="0" cellspacing="0" cellpadding="2" class="table table-striped table-bordered table-advance table-hover">
<thead>
  <tr>
  	<th width="100"><?php echo $this->lang->line('seo_url_time');?></th>
    <th><?php echo $this->lang->line('seo_url');?></th>
	<th><?php echo $this->lang->line('site_keywords');?></th>
	<th width="100"><?php echo $this->lang->line('is_index');?></th>
	<th width="100"><?php echo $this->lang->line('bd_time');?></th>
  </tr>
 </thead>
  <tbody>
 <?php foreach($seo_info as $val):?>
  <tr>
    <td><?php echo $val['add_time'];?></td>
    <td><?php echo $val['link_url'];?></td>
	<td><?php echo $val['link_keyword'];?></td>
	<td><?php echo $val['is_index'];?></td>
	<td><?php echo $val['index_time'];?></td>
	</tr> 
 <?php endforeach;?>
  </tbody> 
</table>
</div>
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
   <script src="static/js/common-scripts.js"></script>
<script>
$('#dpYears').datepicker();
</script>
</body>
</html>