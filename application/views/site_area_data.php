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
			<input type="hidden" value="area_data" name="m" />
			<input type="hidden" value="<?php echo $site_info['site_id']; ?>" name="site_id" />
			  <div class="row-fluid">
				<div class="filter_wrapper">
					<a href="?c=site&m=data&site_id=<?php echo $site_info['site_id']; ?>" class="filter"><?php echo $this->lang->line('chart_data'); ?></a>
					<div class="filter selected"><?php echo $this->lang->line('area_data'); ?></div>
					<a href="?c=site&m=order_area_data&site_id=<?php echo $site_info['site_id']; ?>" class="filter"><?php echo $this->lang->line('order_area_data'); ?></a>
					<a href="?c=site&m=site_from_data&site_id=<?php echo $site_info['site_id']; ?>" class="filter"><?php echo $this->lang->line('site_from_data'); ?></a>
					<a href="?c=site&m=site_page_views&site_id=<?php echo $site_info['site_id']; ?>" class="filter"><?php echo $this->lang->line('site_page_views'); ?></a>
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
                            <div class="widget-title" style="background-color:#00a186;">
                                <h4><i class="icon-reorder"></i> <?php echo $site_info['site_name'] . " (" . $site_info['site_domain'] . ")"; ?> <?php echo $this->lang->line('chart_data'); ?></h4>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <a href="javascript:;" class="icon-remove"></a>
                            </span>
                            </div>
				<div class="widget-body">
				    <?php if(empty($area_data)):?>暂无数据<?php else:?><div id="area" style="height:600px; width:95%; display:block;"></div><?php endif;?>
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
<?php if(!empty($area_data)):?>
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
            var myChart = ec.init(document.getElementById('area'));
			var option = {
				title : {
					text: '地域数据',
					subtext: '流量地域分布'
				},
				tooltip : {
					trigger: 'axis',
					formatter:'{b1}<br/>{a1} : {c1}<br/>{a0} : {c0}'
				},
				legend: {
					data:['UV','PV']
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
						type : 'value',
						position: 'top',
						boundaryGap : [0, 0.01]
					}
				],
				yAxis : [
					{
						type : 'category',
						data : [<?php 
						$gg_area = $this->lang->line("gg_area");
						$i = 1; 
						foreach($area_data['city'] as $val){
							if($i > 1)
							{
								echo ",";
							}
							if(isset($gg_area[$val]))
							{
								echo "'" . $gg_area[$val] . "'";
							}
							else
							{
								echo "'" . $val . "'";
							}
							$i ++;
						}
						?>]
					}
				],
				series : [
					{
						name:'PV',
                        type:'bar',
                        data:[<?php $i = 1; foreach($area_data['pv'] as $val){ if($i > 1){ echo ",";} echo $val; $i ++;}?>]
					},
					{
						name:'UV',
                        type:'bar',
                        data:[<?php $i = 1; foreach($area_data['uv'] as $val){ if($i > 1){ echo ",";} echo $val; $i ++;}?>]
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