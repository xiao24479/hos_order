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
<link rel="stylesheet" type="text/css" href="static/css/metro-gallery.css" media="screen" />
<link href="static/js/datepicker/css/datepicker.css" rel="stylesheet" />
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
            <form action="" method="get" class="date_form">
			<input type="hidden" value="site" name="c" />
			<input type="hidden" value="data" name="m" />
			<input type="hidden" value="<?php echo $site_info['site_id']; ?>" name="site_id" />
			  <div class="row-fluid">
				<div class="filter_wrapper">
					<div class="filter selected"><?php echo $this->lang->line('chart_data'); ?></div>
					<a href="?c=site_seo&m=area_data&site_id=<?php echo $site_info['site_id']; ?>&domain_id=<?php echo $domain_id; ?>" class="filter"><?php echo $this->lang->line('area_data'); ?></a>
					<a href="?c=site_seo&m=order_area_data&site_id=<?php echo $site_info['site_id']; ?>&domain_id=<?php echo $domain_id; ?>" class="filter"><?php echo $this->lang->line('order_area_data'); ?></a>
					<a href="?c=site_seo&m=site_from_data&site_id=<?php echo $site_info['site_id']; ?>&domain_id=<?php echo $domain_id; ?>" class="filter"><?php echo $this->lang->line('site_from_data'); ?></a>
					<a href="?c=site_seo&m=site_page_views&site_id=<?php echo $site_info['site_id']; ?>&domain_id=<?php echo $domain_id; ?>" class="filter"><?php echo $this->lang->line('site_page_views'); ?></a>
                    <a href="?c=site_seo&m=site_page_path&site_id=<?php echo $site_info['site_id']; ?>&domain_id=<?php echo $domain_id; ?>" class="filter"><?php echo $this->lang->line('site_page_path'); ?></a>
                    <div class="span3" style="float:right;"><input type="text" value="<?php echo $start; ?> - <?php echo $end; ?>" class="input-block-level" name="date" id="inputDate" /></div>
					<div class="clear"></div>
				</div>
                <div class="date_div">
				<div class="divdate"></div>
				<div class="anniu"><button type="submit" class="btn btn-success"> 确定 </button><br /><button id="reset" type="reset" class="btn"> 取消 </button></div>
				</div>
			  </div>
              </form>
              <div class="row-fluid">
			    <div class="span12">
				  <div class="widget purple">
                            <div class="widget-title">
                                <h4><i class="icon-reorder"></i> <?php
                                echo $site_info['site_name'] . " ("; 
								if($site_domain == 'www'){ echo "总数据";} else {echo $site_domain;}
								echo  ")";
								?> <?php echo $this->lang->line('chart_data'); ?></h4>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <a href="javascript:;" class="icon-remove"></a>
                            </span>
                            </div>
				<div class="widget-body">
				    <?php if(empty($site_data)):?>暂无数据<?php else:?><div id="data" style="height:500px; width:95%; display:block;"></div><?php endif;?>
				</div>
			  </div>
			  </div>
            </div>
            <!-- END PAGE CONTENT-->         
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
</div>
   <script src="static/js/jquery-1.8.3.min.js"></script>
   <script src="static/js/jquery.nicescroll.js" type="text/javascript"></script>
   <script src="static/js/bootstrap.min.js"></script>
   <script type="text/javascript" src="static/js/jquery.slimscroll.min.js"></script>
   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="static/js/excanvas.js"></script>
   <script src="static/js/respond.js"></script>
   <![endif]-->
   <script src="static/js/common-scripts.js"></script> 
   <script src="static/js/datepicker/js/datepicker.js"></script> 
   <script src="static/js/c/esl.js"></script>
   <script language="javascript">
$(function() {
	$('.date_div').slideUp(1);
    $('.divdate').DatePicker({
		flat: true,
		date: ['<?php echo $start; ?>','<?php echo $end; ?>'],
		current: '<?php echo $current; ?>',
		format: 'Y年m月d日',
		calendars: 3,
		mode: 'range',
		starts: 1,
		onChange: function(formated) {
			$('#inputDate').val(formated.join(' - '));
		}
	});
	
	$('#inputDate').click(function(){
		$('.date_div').slideDown(200);
	});
	
	$("#reset").click(function(){
		$('.date_div').slideUp(200);
	});
});
<?php if(!empty($site_data)):?>
    require.config({
        paths:{ 
            echarts:'static/js/c/echarts',
            'echarts/chart/bar' : 'static/js/c/echarts',
            'echarts/chart/line': 'static/js/c/echarts'
        }
    });

    require(
        [
            'echarts',
            'echarts/chart/bar',
            'echarts/chart/line'
        ],
        function(ec) {
            var myChart = ec.init(document.getElementById('data'));
            var option = {
				title : {
					text: '流量数据',
					subtext: '流量变化'
				},
                tooltip : {
                    trigger: 'axis'
                },
                legend: {
					selected: {
						'IP' : false,
						'PV' : false
					},
                    data:['IP','UV','PV','对话数','预约数','到诊数']
                },
                toolbox: {
                    show : true,
                    feature : {
                        mark : true,
                        dataView : {readOnly: false},
                        magicType:['line', 'bar'],
                        restore : true,
                        saveAsImage : true
                    }
                },
                calculable : true,
                xAxis : [
                    {
                        type : 'category',
                        data : [<?php $i = 1; foreach($site_data['time_arr'] as $val){ if($i > 1){ echo ",";} echo "'" . $val . "'"; $i ++;}?>]
                    }
                ],
                yAxis : [
                    {
                        type : 'value',
                        splitArea : {show : true}
                    }
                ],
                series : [
                    {
                        name:'IP',
                        type:'line',
                        data:[<?php $i = 1; foreach($site_data['ip_arr'] as $val){ if($i > 1){ echo ",";} echo $val; $i ++;}?>]
                    },
                    {
                        name:'UV',
                        type:'line',
                        data:[<?php $i = 1; foreach($site_data['uv_arr'] as $val){ if($i > 1){ echo ",";} echo $val; $i ++;}?>]
                    },
					{
                        name:'PV',
                        type:'line',
                        data:[<?php $i = 1; foreach($site_data['pv_arr'] as $val){ if($i > 1){ echo ",";} echo $val; $i ++;}?>]
                    },
					{
                        name:'对话数',
                        type:'line',
                        data:[<?php $i = 1; foreach($site_data['ask_arr'] as $val){ if($i > 1){ echo ",";} echo $val; $i ++;}?>]
                    },
					{
                        name:'预约数',
                        type:'line',
                        data:[<?php $i = 1; foreach($site_data['order_arr'] as $val){ if($i > 1){ echo ",";} echo $val; $i ++;}?>]
                    },
					{
                        name:'到诊数',
                        type:'line',
                        data:[<?php $i = 1; foreach($site_data['daozhen_arr'] as $val){ if($i > 1){ echo ",";} echo $val; $i ++;}?>]
                    }
                ]
            };
            
            myChart.setOption(option);
        }
    );
<?php endif;?>
   </script>
</body>
</html>