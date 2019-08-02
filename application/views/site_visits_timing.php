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
            <!-- BEGIN PAGE CONTENT-->
<div class="row-fluid" style="background-color:#252525; float:left;"><div id="fancyClock"></div></div>
<div style="display:block; zoom:1; overflow:hidden; height:10px; width:100%;"></div>
	  <div class="row-fluid">
		<div class="span12">
		  <div class="widget orange">
					<div class="widget-title">
					<h4><i class="icon-time"></i> <?php echo $this->lang->line('data_runing'); ?></h4>
					<span class="tools">
						<a href="javascript:;" class="icon-chevron-down"></a>
						<a href="javascript:;" class="icon-remove"></a>
					</span>
					</div>
<div class="widget-body">
<table class="table table-hover" id="editable-sample">
 <thead>
 <tr>
	 <th><?php echo $this->lang->line('google_id'); ?></th>
	 <th><?php echo $this->lang->line('project_id'); ?></th>
	 <th>网站流量数据</th>
	 <th>访客信息数据</th>
	 <th>入口流量数据</th>
	 <th>访客轨迹数据</th>
     <th>流量来路数据</th>
	 <th>百度竞价数据</th>
     <th>对话来源数据</th>
 </tr>
 </thead>
 <tbody>
 <?php
 foreach($analytics as $val):
 if(isset($val['ana_id'])):
 ?>
 <tr>
	 <td><?php echo $val['google_id']; ?></td>
	 <td><?php echo $val['project_id']; ?></td>
	 <td><button class="btn btn-primary" data_id="<?php echo $val['ana_id']; ?>" id="site_<?php echo $val['project_id']; ?>" data="<?php echo $val['view_site_first'];?>"><?php if($val['view_site_first'] != -1):?><i class="icon-coffee"></i> <?php echo $this->lang->line('waiting'); ?></button><?php else:?><i class="icon-ok"></i> <?php echo $this->lang->line('report_havd'); ?></button><?php endif;?></td>
	 <td><button class="btn btn-primary" data_id="<?php echo $val['ana_id']; ?>" id="visitor_<?php echo $val['project_id']; ?>" data="<?php echo $val['view_visitor_first'];?>"><?php if($val['view_visitor_first'] != -1):?><i class="icon-coffee"></i> <?php echo $this->lang->line('waiting'); ?></button><?php else:?><i class="icon-ok"></i> <?php echo $this->lang->line('report_havd'); ?></button><?php endif;?></td>
	 <td><button class="btn btn-primary" data_id="<?php echo $val['ana_id']; ?>" id="load_<?php echo $val['project_id']; ?>" data="<?php echo $val['view_load_first'];?>"><?php if($val['view_load_first'] != -1):?><i class="icon-coffee"></i> <?php echo $this->lang->line('waiting'); ?></button><?php else:?><i class="icon-ok"></i> <?php echo $this->lang->line('report_havd'); ?></button><?php endif;?></td>
	 <td><button class="btn btn-primary" data_id="<?php echo $val['ana_id']; ?>" id="path_<?php echo $val['project_id']; ?>" data="<?php echo $val['view_path_first'];?>"><?php if($val['view_path_first'] != -1):?><i class="icon-coffee"></i> <?php echo $this->lang->line('waiting'); ?></button><?php else:?><i class="icon-ok"></i> <?php echo $this->lang->line('report_havd'); ?></button><?php endif;?></td>
     <td><button class="btn btn-primary" data_id="<?php echo $val['ana_id']; ?>" id="from_<?php echo $val['project_id']; ?>" data="<?php echo $val['view_from_first'];?>"><?php if($val['view_from_first'] != -1):?><i class="icon-coffee"></i> <?php echo $this->lang->line('waiting'); ?></button><?php else:?><i class="icon-ok"></i> <?php echo $this->lang->line('report_havd'); ?></button><?php endif;?></td>
	 <td><button class="btn btn-primary" data_id="<?php echo $val['ana_id']; ?>" id="baidu_<?php echo $val['project_id']; ?>" data="<?php echo $val['view_baidu_first'];?>"><?php if($val['view_baidu_first'] != -1):?><i class="icon-coffee"></i> <?php echo $this->lang->line('waiting'); ?></button><?php else:?><i class="icon-ok"></i> <?php echo $this->lang->line('report_havd'); ?></button><?php endif;?></td>
     <td><button class="btn btn-primary" data_id="<?php echo $val['ana_id']; ?>" id="qq_<?php echo $val['project_id']; ?>" data="<?php echo $val['view_qq_first'];?>"><?php if($val['view_qq_first'] != -1):?><i class="icon-coffee"></i> <?php echo $this->lang->line('waiting'); ?></button><?php else:?><i class="icon-ok"></i> <?php echo $this->lang->line('report_havd'); ?></button><?php endif;?></td>
 </tr>
 <?php
 endif;
 endforeach;
 ?>
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
   <script src="static/js/jquery.tzineClock.js"></script>
<script>
$(document).ready(function(){
	$('#fancyClock').tzineClock();
});
var domain = "http://i.renaidata.com/g";
var time_ge = 5000;
var type_arr = new Array();
type_arr[1] = new Array('site', domain + '/index.php?type=1');
type_arr[2] = new Array('visitor', domain + '/index.php?type=2');
type_arr[3] = new Array('load', domain + '/index.php?type=3');
type_arr[4] = new Array('path', domain + '/index.php?type=4');
type_arr[5] = new Array('from', domain + '/index.php?type=5');
type_arr[6] = new Array('baidu', domain + '/index.php?type=6');
type_arr[7] = new Array('qq', domain + '/index.php?type=7');


$.getScript(domain + "/clear.php", function(){
	setTimeout(function(){
		analytics(1, 0);
	}, time_ge);
});

function analytics(id, no)
{
	var len = type_arr.length;
	var id_name = type_arr[id][0];
	var id_url = type_arr[id][1];
	if(id > (len - 1))
	{
		alert("获取完成！");
		return false;
	}
	
	var data_name = $("button[id^='" + id_name + "']").eq(no).attr("id");
	var data_name_arr = data_name.split("_");
	var pid = data_name_arr[1];
	var data_name_last = $("button[id^='" + id_name + "']").last().attr("id");
	var id_first = $("#" + data_name).attr("data");
	id_first = id_first + '';

	if(id_first == "-1")
	{
		if(data_name == data_name_last)
		{
			id ++;
			no = 0;
			setTimeout(function(){
				analytics(id, no);
			}, 100);
			return false;
		}
		else
		{
			no ++;
			setTimeout(function(){
				analytics(id, no);
			}, 100);
			return false;
		}
	}
	id_url = id_url + "&pid=" + pid;
	/* 先执行远程程序，生成本地数据。 */
	$.getScript(id_url + "&first=" + id_first, function(){
		$("#" + data_name).html('<i class="icon-refresh icon-spin"></i> <?php echo $this->lang->line('report_having'); ?>');
		$.ajax({
			type:'post',
			url:'?c=site&m=analytics_ajax',
			data:'type=' + id_name + '&first=' + id_first + '&ana_id=' + $("#" + data_name).attr("data_id"),
			success:function(data)
			{
//alert(data);
				if(data == 'login')
				{
					alert("请登录google，以获取权限，URL：" + domain + "/index.php");
					open(domain + "/index.php?logout=1");
				}
				else
				{
					data = $.parseJSON(data);
					if(data['status'] == 1)
					{
						$.get(domain + "/clear.php");
						$("#" + data_name).html('<i class="icon-ok"></i> <?php echo $this->lang->line('success'); ?>');
						$("#" + data_name).attr("data", data['first']);
						setTimeout(function(){
							if(data['first'] == "-1")
							{
								$("#" + data_name).html('<i class="icon-ok"></i> <?php echo $this->lang->line('report_havd'); ?>');
							}
							else
							{
								$("#" + data_name).html('<i class="icon-coffee"></i> <?php echo $this->lang->line('waiting'); ?>');
							}
						}, 1000);
						setTimeout(function(){
							analytics(id, no);
						}, time_ge);
					}
					else
					{
						alert(data);
					}
				}
			},
			complete: function (XHR, TS)
			{
				XHR = null;
			}
		});
	});
}
</script>
</body>
</html>