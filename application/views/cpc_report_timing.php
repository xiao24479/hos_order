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
<link rel="stylesheet" type="text/css" href="static/css/jquery.tzineClock.css" />
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
            <!-- BEGIN PAGE CONTENT <div class="row-fluid" style="background-color:#252525; float:left;"><div id="fancyClock"></div></div> -->
<div style="display:block; zoom:1; overflow:hidden; height:10px; width:100%;"></div>
	  <div class="row-fluid">
		<div class="span12">
		  <div class="widget orange">
<!--					<div class="widget-title">
					<h4><i class="icon-time"></i> <?php echo $this->lang->line('data_runing'); ?></h4>
					<span class="tools">
						<a href="javascript:;" class="icon-chevron-down"></a>
						<a href="javascript:;" class="icon-remove"></a>
					</span>
					</div>-->
<div class="widget-body">
<table class="table table-hover" id="editable-sample">
 <thead>
 <tr>
	 <th>序号</th>
	 <th><?php echo $this->lang->line('site_name'); ?></th>
	 <th><?php echo $this->lang->line('day_account_report'); ?></th>
	 <th><?php echo $this->lang->line('day_keyword_report'); ?></th>
	 <th><?php echo $this->lang->line('day_keyword_pc_report'); ?></th>
	 <th><?php echo $this->lang->line('day_keyword_mobile_report'); ?></th>
	 <th><?php echo $this->lang->line('hour_keyword_report'); ?></th>
	 <th><?php echo $this->lang->line('hour_keyword_pc_report'); ?></th>
	 <th><?php echo $this->lang->line('hour_keyword_mobile_report'); ?></th>
 </tr>
 </thead>
 <tbody>
 <?php $i=0;
 foreach($site_list as $list):
     $i++;
     ?>
     <tr <?php if(($i+1)%2==0){echo "style='background-color:#fff'";}?>>
 	 <td><span class="site_id"><?php echo $list['site_id'];?></span></td>
	 <td><?php echo $list['site_name'] . " (" . $list['site_domain'] . ")";?></td>
	 <td><button class="btn btn-primary" id="day_account_<?php echo $list['site_id'];?>" data="<?php echo $list['da']; ?>"><?php if($list['da'] == 1){echo '<i class="icon-ok"></i>  ' . $this->lang->line('report_havd');}else{ echo '<i class="icon-remove"></i>  ' . $this->lang->line('report_empty');} ?></button></td>
	 <td><button class="btn btn-primary" id="day_keyword_<?php echo $list['site_id'];?>" data="<?php echo $list['dr']; ?>"><?php if($list['dr'] == 1){echo '<i class="icon-ok"></i>  ' . $this->lang->line('report_havd');}else{ echo '<i class="icon-remove"></i>  ' . $this->lang->line('report_empty');} ?></button></td>
	 <td><button class="btn btn-primary" id="day_keyword_pc_<?php echo $list['site_id'];?>" data="<?php echo $list['dr_pc']; ?>"><?php if($list['dr_pc'] == 1){echo '<i class="icon-ok"></i>  ' . $this->lang->line('report_havd');}else{ echo '<i class="icon-remove"></i>  ' . $this->lang->line('report_empty');} ?></button></td>
	 <td><button class="btn btn-primary" id="day_keyword_mobile_<?php echo $list['site_id'];?>" data="<?php echo $list['dr_mobile']; ?>"><?php if($list['dr_mobile'] == 1){echo '<i class="icon-ok"></i>  ' . $this->lang->line('report_havd');}else{ echo '<i class="icon-remove"></i>  ' . $this->lang->line('report_empty');} ?></button></td>
	 <td><button class="btn btn-primary" id="hour_keyword_<?php echo $list['site_id'];?>" data=""><i class="icon-coffee"></i> <?php echo $this->lang->line('waiting'); ?></button></td>
	 <td><button class="btn btn-primary" id="hour_keyword_pc_<?php echo $list['site_id'];?>" data=""><i class="icon-coffee"></i> <?php echo $this->lang->line('waiting'); ?></button></td>
	 <td><button class="btn btn-primary" id="hour_keyword_mobile_<?php echo $list['site_id'];?>" data=""><i class="icon-coffee"></i> <?php echo $this->lang->line('waiting'); ?></button></td>
 </tr>
 <?php endforeach; ?>
 </tbody>
</table>
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
   <!--<script src="static/js/jquery.tzineClock.js"></script>-->
<script language="javascript">
var now_action = '';
var timing = setTimeout("timing_time()", 950);
function timing_time()
{
	clearTimeout(timing);
	var d = new Date();
	var vYear = d.getFullYear();
	var vMon = d.getMonth() + 1;
	var vDay = d.getDate();
	var h = d.getHours();
	var m = d.getMinutes();
	var se = d.getSeconds();
	
	if(se == 0)
	{
		if(m == 28)
		{
			report_id('hour_keyword');
		}
		else if(m == 30)
		{
			report_id('hour_keyword_pc');
		}
		else if(m == 32)
		{
			report_id('hour_keyword_mobile');
		}
		else if(m == 5)
		{
			report_id('day_keyword');
		}
		else if(m == 10)
		{
			report_id('day_keyword_pc');
		}
		else if(m == 15)
		{
			report_id('day_keyword_mobile');
		}
		else if(m == 1)
		{
			report_id('day_account');
		}
	}
	timing = setTimeout("timing_time()", 950);
}

function report_id(type)
{
	var btn_id = '';
	btn_id = type + "_";
	
	$(".site_id").each(function(i){
		var site_id = $(".site_id").eq(i).html();
		setTimeout(function(){
			/* 判断昨日关键词报告是否已经获取 */
			if($("#" + btn_id + site_id).attr("data") == 1)
			{
				return false;
			}
			
			$("#" + btn_id + site_id).html('<i class="icon-refresh icon-spin"></i> <?php echo $this->lang->line('report_having'); ?>');
			$.ajax({
				type:'post',
				url:'?c=site&m=report_cache_ajax',
				data:'site_id=' + site_id + '&type=' + type,
				success:function(data)
				{
				   data = $.parseJSON(data);
				   if(data['type'] == 0)
				   {
					   $("#" + btn_id + site_id).html('<i class="icon-remove"></i> <?php echo $this->lang->line('no_empty'); ?>');
				   }
				   else if(data['type'] == 1)
				   {
					   $("#" + btn_id + site_id).attr("data", data['report_id']);
					   $("#" + btn_id + site_id).html('<i class="icon-coffee"></i> <?php echo $this->lang->line('waiting'); ?>');
					   setTimeout(function(){
						  $("#" + btn_id + site_id).html('<i class="icon-refresh icon-spin"></i> <?php echo $this->lang->line('report_having'); ?>');
						  report_get(site_id, type);
					   }, 5000);
				   }
				   else if(data['type'] == 2)
				   {
					   $("#" + btn_id + site_id).html('<i class="icon-remove"></i> <?php echo $this->lang->line('fail'); ?>');
				   }
				},
				complete: function (XHR, TS)
				{
				   XHR = null;
				}
			});
		}, ((i + 1) * 30000));
	});
}

function report_get(site_id, type)
{
	var report_id = $("#" + type + "_" + site_id).attr("data");
	$.ajax({
		type:'post',
		url:'?c=site&m=report_get_ajax',
		data:'site_id=' + site_id + '&type=' + type + '&report_id=' + report_id,
		success:function(data)
		{
		   if(data == 0)
		   {
		   	  $("#" + type + "_" + site_id).html('<i class="icon-remove"></i> <?php echo $this->lang->line('no_empty'); ?>');
		   }
		   else if(data == 1)
		   {
		   	   $("#" + type + "_" + site_id).html('<i class="icon-remove"></i> <?php echo $this->lang->line('fail'); ?>');
		   }
		   else if(data == 2)
		   {
		       $("#" + type + "_" + site_id).html('<i class="icon-coffee"></i> <?php echo $this->lang->line('waiting'); ?>');
			   setTimeout(function(){
			   $("#" + type + "_" + site_id).html('<i class="icon-refresh icon-spin"></i> <?php echo $this->lang->line('report_having'); ?>');
				  report_get(site_id, type);
			   }, 5000);
		   }
		   else
		   {
		   	    if((type == 'day_keyword') || (type == 'day_keyword_pc') || (type == 'day_keyword_mobile'))
				{
					$("#" + type + "_" + site_id).html('<i class="icon-ok"></i> <?php echo $this->lang->line('report_havd'); ?>');
					$("#" + type + "_" + site_id).attr("data", '1');
				}
				else if((type == 'hour_keyword') || (type == 'hour_keyword_pc') || (type == 'hour_keyword_mobile'))
				{
					$("#" + type + "_" + site_id).html('<i class="icon-ok"></i> <?php echo $this->lang->line('success'); ?>');
					$("#" + type + "_" + site_id).attr("data", "");
					setTimeout(function(){
						$("#" + type + "_" + site_id).html('<i class="icon-coffee"></i> <?php echo $this->lang->line('waiting'); ?>');
					}, 5000);
				}
				else if(type == 'day_account')
				{
					$("#" + type + "_" + site_id).html('<i class="icon-ok"></i> <?php echo $this->lang->line('report_havd'); ?>');
					$("#" + type + "_" + site_id).attr("data", '1');
				}
		   }
		},
		complete: function (XHR, TS)
		{
		    XHR = null;
		}
	});
}
</script>
</body>
</html>