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
<link rel="stylesheet" type="text/css" href="static/css/chosen.css" />
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
					<h4><i class="icon-reorder"></i> <?php echo $site_info['site_name'] . " (<a style='color:#fff;' target='_blank' href='http://" . $site_info['site_domain'] . "/'>" . $site_info['site_domain'] . "</a>)&nbsp;&nbsp;" . $this->lang->line('cpc_account'); ?></h4>
					<span class="tools">
						<a href="javascript:;" class="icon-chevron-down"></a>
						<a href="javascript:;" class="icon-remove"></a>
					</span>
					</div>-->
<div class="widget-body profile" >
  <dl class="dl-horizontal" >
	<dt><?php echo $this->lang->line('bd_id'); ?></dt>
    <dd><?php echo $site_info['bd_id']; ?></dd>
    <dt><?php echo $this->lang->line('bd_balance'); ?></dt>
    <dd><i class="icon-jpy"></i> <?php echo $site_info['bd_balance']; ?></dd>
    <dt><?php echo $this->lang->line('bd_cost'); ?></dt>
    <dd><i class="icon-jpy"></i> <?php echo $site_info['bd_cost']; ?></dd>
    <dt><?php echo $this->lang->line('bd_payment'); ?></dt>
    <dd><i class="icon-jpy"></i> <?php echo $site_info['bd_payment']; ?></dd>
    <dt><?php echo $this->lang->line('bd_budget'); ?></dt>
    <dd><i class="icon-jpy"></i> <?php echo $site_info['bd_budget']; ?></dd>
    <dt><?php echo $this->lang->line('bd_opendomains'); ?></dt>
    <dd><?php echo $site_info['bd_opendomains']; ?></dd>
    <dt><?php echo $this->lang->line('bd_regiontarget'); ?></dt>
    <dd><?php echo $site_info['bd_regiontarget']; ?></dd>
    <dt><?php echo $this->lang->line('bd_update'); ?></dt>
    <dd><?php echo date("Y-m-d", $site_info['bd_update']); ?></dd>
  </dl>
 </div>
</div>
</div>
</div>
</div>
</div>
</div>

   <script src="static/js/jquery.js"></script>
   <script src="static/js/jquery.min.js"></script>
   <script src="static/js/jquery.nicescroll.js" type="text/javascript"></script>
   <script src="static/js/bootstrap.min.js"></script>
   <script type="text/javascript" src="static/js/bootstrap-datepicker.js"></script>
   <script src="static/js/chosen.jquery.js"></script>
   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="static/js/excanvas.js"></script>
   <script src="static/js/respond.js"></script>
   <![endif]-->
   <script src="static/js/common-scripts.js"></script>
<script type="text/javascript">
$('#dpYears').datepicker();
$(document).ready(function(){
	$("input[class^='action_']").change(function(){
		var id_name = $(this).attr("class");
		id_arr = id_name.split("_");
		if(id_name == "action_" + id_arr[1] + "_h")
		{
			if($(this).is(":checked"))
			{
				$(".action_" + id_arr[1] + "_c").checkCbx(true);
			}
			else
			{
				$(".action_" + id_arr[1] + "_c").checkCbx(false);
			}
		}
		else
		{
			if(!$(".action_" + id_arr[1] + "_h").is(":checked"))
			{
				$(".action_" + id_arr[1] + "_h").checkCbx(true);
			}
		}
	});
});

$.fn.checkCbx = function(type){ 
	return this.each(function(){
		this.checked = type; 
	}); 
} 
</script>
</body>
</html>