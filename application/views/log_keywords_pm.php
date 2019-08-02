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
		  <div class="widget black">
                      <div class="widget-title" style="background-color:#00a186;">
					<h4 class="site_seo_h4"><i class="icon-reorder"></i> <?php echo $site_info['site_name'];?> (<a href="http://<?php echo $site_info['site_domain'];?>" target="_blank"><?php echo $site_info['site_domain'];?></a>) <?php echo $this->lang->line('site_canshu'); ?></h4>
					<span class="tools">
						<a href="javascript:;" class="icon-chevron-up"></a>
						<a href="javascript:;" class="icon-remove"></a>
					</span>
					</div>
					<div class="widget-body" style="display:none;">
					<dl class="dl-horizontal">
					  <dt><?php echo $this->lang->line('site_domain');?></dt>
					  <dd><?php echo $site_info['site_domain'];?></dd>
					  <dt><?php echo $this->lang->line('site_name');?></dt>
					  <dd><?php echo $site_info['site_name'];?></dd>
					  <dt><?php echo $this->lang->line('site_time');?></dt>
					  <dd><?php echo $site_info['site_time'];?></dd>
					  <dt><?php echo $this->lang->line('swt_url');?></dt>
					  <dd><?php echo $site_info['swt_url'];?></dd>
					  <dt><?php echo $this->lang->line('site_system');?></dt>
					  <dd><?php echo $site_info['sys_name'];?></dd>
					  <dt><?php echo $this->lang->line('site_ba_no');?></dt>
					  <dd><?php echo $site_info['site_ba_no'];?></dd>
					  <dt><?php echo $this->lang->line('ba_xz');?></dt>
					  <dd><?php echo $site_info['xz_name'];?></dd>
					  <dt><?php echo $this->lang->line('site_host');?></dt>
					  <dd><?php echo $site_info['site_host'];?></dd>
					  <dt><?php echo $this->lang->line('site_ba_name');?></dt>
					  <dd><?php echo $site_info['site_ba_name'];?></dd>
					  <dt><?php echo $this->lang->line('site_ba_time');?></dt>
					  <dd><?php echo $site_info['site_ba_time'];?></dd>
					  <dt><?php echo $this->lang->line('site_ip');?></dt>
					  <dd><?php echo $site_info['site_ip'];?></dd>
					  <dt><?php echo $this->lang->line('site_host_system');?></dt>
					  <dd><?php echo $site_info['site_host_system'];?></dd>
					  <dt><?php echo $this->lang->line('site_domain_time');?></dt>
					  <dd><?php echo $site_info['site_domain_time'];?></dd>
                    </dl>
	                </div>
		   </div>				
		  <div class="widget green">
					<div class="widget-title" style="background-color:#00a186;">
					<h4 class="site_seo_h4"><i class="icon-reorder"></i> <?php if(empty($log_keywords)){ echo "";} else{echo $log_keywords[0]['key_keyword'];}?></h4>
					<span class="tools">
						<a href="javascript:;" class="icon-chevron-down"></a>
						<a href="javascript:;" class="icon-remove"></a>
					</span>
					</div>
					<div class="widget-body" >
<div class="widget-body-log">
<table width="100%" border="0" cellspacing="0" cellpadding="2" class="table table-striped table-bordered table-advance table-hover">
<thead>
  <tr>
  	<th><?php echo $this->lang->line('log_time');?></th>
	<th><?php echo $this->lang->line('bd_pm');?></th>
	<th><?php echo $this->lang->line('google_pm');?></th>
	<th><?php echo $this->lang->line('360_pm');?></th>
	<th><?php echo $this->lang->line('bd_pm_change');?></th>
	<th><?php echo $this->lang->line('google_pm_change');?></th>
	<th><?php echo $this->lang->line('360_pm_change');?></th>
  </tr>
 </thead>
  <tbody>
<?php foreach($log_keywords as $key => $val):?>
   <tr>
  	<td><?php echo $val['log_time'];?></td>
	<td><?php echo $val['bd_pm'];?></td>
	<td><?php echo $val['google_pm'];?></td>
	<td><?php echo $val['360_pm'];?></td>
	<td style=" <?php if($val['bd_pm_change'] < 0){echo 'color: #FF0000;';} else if($val['bd_pm_change'] > 0){ echo 'color: #0033FF;';} else{echo 'color: #888888;';}?>"><?php echo $val['bd_pm_change'];echo "&nbsp;"; if($val['bd_pm_change'] < 0){echo "<i class='icon-long-arrow-down'></i>";} else if($val['bd_pm_change'] > 0){ echo "<i class='icon-long-arrow-up'></i>";} else{echo "<i class='icon-minus'></i>";}?></td>
	<td style=" <?php if($val['google_pm_change'] < 0){echo 'color: #FF0000;';} else if($val['google_pm_change'] > 0){ echo 'color: #0033FF;';} else{echo 'color: #888888;';}?>"><?php echo $val['google_pm_change'];echo "&nbsp;"; if($val['google_pm_change'] < 0){echo "<i class='icon-long-arrow-down'></i>";} else if($val['google_pm_change'] > 0){ echo "<i class='icon-long-arrow-up'></i>";} else{echo "<i class='icon-minus'></i>";}?></td>
	<td style=" <?php if($val['360_pm_change'] < 0){echo 'color: #FF0000;';} else if($val['360_pm_change'] > 0){ echo 'color: #0033FF;';} else{echo 'color: #888888;';}?>"><?php echo $val['360_pm_change'];echo "&nbsp;"; if($val['360_pm_change'] < 0){echo "<i class='icon-long-arrow-down'></i>";} else if($val['360_pm_change'] > 0){ echo "<i class='icon-long-arrow-up'></i>";} else{echo "<i class='icon-minus'></i>";}?></td>
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
</script>
</body>
</html>