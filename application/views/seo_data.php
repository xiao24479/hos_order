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
					<h4 class="site_seo_h4"><i class="icon-reorder"></i> <?php echo $this->lang->line('site_canshu'); ?></h4>
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
					<h4 class="site_seo_h4"><i class="icon-reorder"></i> <?php echo $site_info['site_name'];?> (<a href="http://<?php echo $site_info['site_domain'];?>" target="_blank"><?php echo $site_info['site_domain'];?></a>)</h4>
					<span class="tools">
						<a href="javascript:;" class="icon-chevron-down"></a>
						<a href="javascript:;" class="icon-remove"></a>
					</span>
					</div>
					<div class="widget-body">
<div class="widget-body-log">
<table width="100%" border="0" cellspacing="0" cellpadding="2" class="table table-striped table-bordered table-advance table-hover">
<thead>
  <tr>
  	<th width="90"><?php echo $this->lang->line('log_time');?></th>
    <th><?php echo $this->lang->line('log_week');?></th>
	<th><?php echo $this->lang->line('site_pm');?></th>
	<th><?php echo $this->lang->line('GG_data');?></th>
	<th><?php echo $this->lang->line('site_googlelink');?></th>
	<th><?php echo $this->lang->line('bd_link');?></th>
	<th width="90"><?php echo $this->lang->line('bd_photo');?></th>
	<th><?php echo $this->lang->line('BD');?></th>
	<th><?php echo $this->lang->line('PR');?></th>
	<th><?php echo $this->lang->line('add_content');?></th>
	<th><?php echo $this->lang->line('add_link');?></th>
	<th><?php echo $this->lang->line('content_change');?></th>
	<th><?php echo $this->lang->line('link_change');?></th>
	<th><?php echo $this->lang->line('seo_action');?></th>
  </tr>
 </thead>
  <tbody>
    <form action="?c=site&m=site_update" method="post">
	<tr>
	  <td><input type="text" class="input-small" name="site_time" id="site_time" value="<?php echo date("Y-m-d");?>" /></td>
	  <td><input type="text" class="text" name="site_week" id="site_week" value="<?php echo date("w");?>" /></td>
	  <td><input type="text" class="text" name="site_bd" id="site_bd" value="0" /></td>
	  <td><input type="text" class="text" name="site_googlesite" id="site_googlesite" value="0" /></td>
	  <td><input type="text" class="text" name="site_baidudomain" id="site_baidudomain" value="0"  /></td>
	  <td><input type="text" class="text" name="site_googlelink" id="site_googlelink" value="0" /></td>
	  <td><input type="text" class="input-small" name="site_baidutime" id="site_baidutime" value="0" /></td>
	  <td><input type="text" class="text" name="site_br" id="site_br" value="0" /></td>
	  <td><input type="text" class="text" name="site_pr" id="site_pr" value="0" /></td>
	  <td><input type="text" class="text" name="site_addinfo" id="site_addinfo" value="0" /></td>
	  <td><input type="text" class="text" name="site_addlink" id="site_addlink" value="0" /></td>
	  <td><input type="text" class="text" name="seo_info_change" id="seo_info_change" value="0" /></td>
	  <td><input type="text" class="text" name="seo_link_change" id="seo_link_change" value="0" /></td>  
	  <td><span id="submit"><input type="hidden" name="site_id" value="<?php echo $site_info['site_id'];?>" /><button type="submit" class="btn btn-success" id="seo_data_submit"><i class="icon-refresh"></i></button></span></td>
	</tr>
	</form>
<?php foreach($site_seo as $val):?>
   <tr>
  	<td><?php echo $val['site_time'];?></td>
    <td><?php echo $val['site_week'];?></td>
	<td><?php echo $val['site_baidusite'];?></td>
	<td><?php echo $val['site_googlesite'];?></td>
	<td><?php echo $val['site_googlelink'];?></td>
	<td><?php echo $val['site_baidudomain'];?></td>
	<td><?php if($val['seo_baidutime_type'] == 0){echo $val['site_baidutime'];}if($val['seo_baidutime_type'] == 1){echo $this->lang->line('baidu_time_today');}if($val['seo_baidutime_type'] == 2){echo $this->lang->line('baidu_time_yesterday');}?></td>
	<td><?php echo $val['site_br'];?></td>
	<td><?php echo $val['site_pr'];?></td>
	<td><?php echo $val['site_addinfo'];?></td>
	<td><?php echo $val['site_addlink'];?></td>
	<td style=" <?php if($val['seo_info_change'] < 0){echo 'color: #FF0000;';} else if($val['seo_info_change'] > 0){ echo 'color: #0033FF;';} else{echo 'color: #888888;';}?>"><?php echo $val['seo_info_change'];echo "&nbsp;"; if($val['seo_info_change'] < 0){echo "<i class='icon-long-arrow-down'></i>";} else if($val['seo_info_change'] > 0){ echo "<i class='icon-long-arrow-up'></i>";} else{echo "<i class='icon-minus'></i>";}?></td>
	<td style=" <?php if($val['seo_link_change'] < 0){echo 'color: #FF0000;';} else if($val['seo_link_change'] > 0){ echo 'color: #0033FF;';} else{echo 'color: #888888;';}?>"><?php echo $val['seo_link_change'];echo "&nbsp;"; if($val['seo_link_change'] < 0){echo "<i class='icon-long-arrow-down'></i>";} else if($val['seo_link_change'] > 0){ echo "<i class='icon-long-arrow-up'></i>";} else{echo "<i class='icon-minus'></i>";}?></td>
	<td></td>
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