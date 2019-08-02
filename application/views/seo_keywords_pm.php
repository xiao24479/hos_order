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
					<div class="widget-title" style="background-color:#00a186;">
					<h4 class="site_seo_h4"><i class="icon-reorder"></i> <?php echo $site_info['site_name'];?> (<a href="http://<?php echo $site_info['site_domain'];?>" target="_blank"><?php echo $site_info['site_domain'];?></a>)</h4>
					<span class="tools">
						<a href="javascript:;" class="icon-chevron-down"></a>
						<a href="javascript:;" class="icon-remove"></a>
					</span>
					</div>
					<div class="widget-body" >
<div class="widget-body-log">
<form action="?c=site&m=seo_keywords_pm_update" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-striped table-bordered table-advance table-hover">
<thead>
  <tr>
  	<th><?php echo $this->lang->line('site_keywords');?></th>
	<th><?php echo $this->lang->line('log_time');?></th>
	<th><?php echo $this->lang->line('bd_pm');?></th>
	<th><?php echo $this->lang->line('google_pm');?></th>
	<th><?php echo $this->lang->line('360_pm');?></th>
	<th><?php echo $this->lang->line('bd_pm_change');?></th>
	<th><?php echo $this->lang->line('google_pm_change');?></th>
	<th><?php echo $this->lang->line('360_pm_change');?></th>
  </tr>
 </thead>
  <?php if(empty($keyword_log)): ?>
  <tbody>
	<?php foreach($site_keywords as $val):?>
	<tr>
	  <td><input type="hidden" class="input-small" name="keyword[<?php echo $val['key_id'];?>]" id="keyword" value="<?php echo $val['key_keyword'];?>"/><a href="?c=site&m=log_keywords_pm&site_id=<?php echo $val['site_id'];?>&key_id=<?php echo $val['key_id'];?>"><?php echo $val['key_keyword'];?></a></td>
	  <td><input type="text" class="input-small" style="margin:0;" name="log_time[<?php echo $val['key_id'];?>]" id="log_time" value="<?php echo date("Y-m-d")?>"/></td>
	  <td><input type="text" class="input-small" style="margin:0;" name="bd_pm[<?php echo $val['key_id'];?>]" id="bd_pm" value="0"/></td>
	  <td><input type="text" class="input-small" style="margin:0;" name="google_pm[<?php echo $val['key_id'];?>]" id="google_pm" value="0"/></td>
	  <td><input type="text" class="input-small" style="margin:0;" name="log_360_pm[<?php echo $val['key_id'];?>]" id="log_360_pm" value="0"/></td>
	  <td><input type="text" class="input-small" style="margin:0;" name="baidu_pm_change[<?php echo $val['key_id'];?>]" id="baidu_pm_change" value="0"/></td>
	  <td><input type="text" class="input-small" style="margin:0;" name="google_pm_change[<?php echo $val['key_id'];?>]" id="google_pm_change" value="0"/></td>
	  <td><input type="text" class="input-small" style="margin:0;" name="log_360_pm_change[<?php echo $val['key_id'];?>]" id="log_360_pm_change" value="0"/></td>
	</tr>
	<?php endforeach;?>
	<tr class="seo_keywords"><td colspan="8"><input type="hidden" name="site_id" value="<?php echo $site_info['site_id'];?>" /><input type="submit" name="submit" class="btn btn-success" value="<?php echo $this->lang->line('action_add');?>" class="btn" /></td></tr>
  </tbody>
  </form>
  <?php else:?> 
  <tbody>
  <?php foreach($keyword_log as $key => $val):?>
   <tr>
  	<td><a href="?c=site&m=log_keywords_pm&site_id=<?php echo $val['site_id'];?>&key_id=<?php echo $val['key_id'];?>"><?php echo $val['key_keyword'];?></a></td>
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
  <?php endif;?>
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