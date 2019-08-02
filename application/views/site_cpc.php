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
			<input type="hidden" value="cpc" name="m" />
			<input type="hidden" value="<?php echo $site_info['site_id']; ?>" name="site_id" />
              <div class="row-fluid">
				<div class="filter_wrapper">
					<div class="filter selected"><?php echo $this->lang->line('cpc_account_data'); ?></div>
					<a href="?c=site&m=cpc_keyword&site_id=<?php echo $site_info['site_id']; ?>" class="filter"><?php echo $this->lang->line('cpc_keyword_data'); ?></a>
                    <a href="?c=site&m=cpc_plan_data&site_id=<?php echo $site_info['site_id']; ?>" class="filter"><?php echo $this->lang->line('cpc_plan_data'); ?></a>
                    <a href="?c=site&m=cpc_hour&site_id=<?php echo $site_info['site_id']; ?>" class="filter"><?php echo $this->lang->line('cpc_hour'); ?></a>
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
                                <h4><i class="icon-reorder"></i> <?php echo $site_info['site_name'] . " (" . $site_info['site_domain'] . ") " . $this->lang->line('cpc_account_data'); ?></h4>
							<span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <a href="javascript:;" class="icon-remove"></a>
                            </span>
                            </div>
				<div class="widget-body">
				    <?php if(empty($site_cpc)):?>暂无数据<?php else:?><div id="data" style="height:550px; width:98%; display:block;"></div><?php endif;?>
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
	<?php if(!empty($site_cpc)):?>
    require.config({
        paths:{ 
			echarts:'static/js/c/echarts',
			'echarts/chart/bar':'static/js/c/echarts',
			'echarts/chart/line':'static/js/c/echarts'
        }
    });

    require(
        [
            'echarts',
			'echarts/chart/line',
			'echarts/chart/bar'
        ],
        function(ec) {
			var myChart = ec.init(document.getElementById('data'));
            var option = {
				tooltip : {
					trigger: 'axis'
				},
				legend: {
					x:'left'|20,
					selected: {
						'总展现' : false,
						'总点击' : false,
						'PC展现' : false,
						'PC点击' : false,
						'移动展现' : false,
						'移动点击' : false
					},
					data:['总消费','总展现','总点击','总点击率','PC展现','PC点击','PC点击率','移动展现','移动点击','移动点击率']
				},
				toolbox: {
					show : true,
					feature : {
						mark : true,
						dataView : true,
						x:'right'|0,
						magicType:['line', 'bar'],
						restore : true,
						saveAsImage : true
					}
				},
				xAxis : [
					{
						type : 'category',
						position: 'bottom',
						boundaryGap: true,
						axisLine : {    // 轴线
							show: true
						},
						axisTick : {    // 轴标记
							show:true
						},
						axisLabel : {
							show:true,
							interval: 'auto',    // {number}
							margin: 8
						},
						splitLine : {
							show:true,
							lineStyle: {
								width: 1
							}
						},
						splitArea : {
							show: false
						},
						data : [<?php $i = 1; foreach($site_cpc['time_arr'] as $val){ if($i > 1){ echo ",";} echo "'" . $val . "'"; $i ++;}?>]
					},
					{
						type : 'category',
						data : [<?php $i = 1; foreach($site_cpc['time_arr'] as $val){ if($i > 1){ echo ",";} echo "'" . $val . "'"; $i ++;}?>]
					}
				],
				yAxis : [
					{
						type : 'value',
						position: 'left',
						//min: 0,
						//max: 300,
						//precision: 1,
						//power: 10,
						splitNumber: 7,
						boundaryGap: [0,0.1],
						axisLine : {    // 轴线
							show: true
						},
						axisTick : {    // 轴标记
							show:true
						},
						axisLabel : {
							show:true,
							interval: 'auto',    // {number}
							formatter: '{value}'   // Template formatter!
						},
						splitLine : {
							show:true,
							lineStyle: {
								width: 1
							}
						},
						splitArea : {
							show: false
						}
					},
					{
						type : 'value',
						precision: 1,
						splitNumber: 10,
						axisLabel : {
							formatter: function(value) {
								// Function formatter
								return value + ' %'
							}
						},
						splitLine : {
							show: false
						}
					}
				],
				series : [
					{
						name: '总消费',
						type: 'bar',
						data:[<?php $i = 1; foreach($site_cpc['cost'] as $val){ if($i > 1){ echo ",";} echo $val; $i ++;}?>]
					},
					{
						name: '总展现',
						type: 'bar',
						data: [<?php $i = 1; foreach($site_cpc['show'] as $val){ if($i > 1){ echo ",";} echo $val; $i ++;}?>]
					},
					{
						name: '总点击',
						type: 'bar',
						data: [<?php $i = 1; foreach($site_cpc['click'] as $val){ if($i > 1){ echo ",";} echo $val; $i ++;}?>]
					},
					{
						name:'总点击率',
						type: 'line',
						yAxisIndex: 1,
						data: [<?php $i = 1; foreach($site_cpc['click_lv'] as $val){ if($i > 1){ echo ",";} echo $val; $i ++;}?>]
					},
					{
						name: 'PC展现',
						type: 'bar',
						data: [<?php $i = 1; foreach($site_cpc['pc_show'] as $val){ if($i > 1){ echo ",";} echo $val; $i ++;}?>]
					},
					{
						name: 'PC点击',
						type: 'bar',
						data: [<?php $i = 1; foreach($site_cpc['pc_click'] as $val){ if($i > 1){ echo ",";} echo $val; $i ++;}?>]
					},
					{
						name:'PC点击率',
						type: 'line',
						yAxisIndex: 1,
						data: [<?php $i = 1; foreach($site_cpc['pc_click_lv'] as $val){ if($i > 1){ echo ",";} echo $val; $i ++;}?>]
					},
					{
						name: '移动展现',
						type: 'bar',
						data: [<?php $i = 1; foreach($site_cpc['mobile_show'] as $val){ if($i > 1){ echo ",";} echo $val; $i ++;}?>]
					},
					{
						name: '移动点击',
						type: 'bar',
						data: [<?php $i = 1; foreach($site_cpc['mobile_click'] as $val){ if($i > 1){ echo ",";} echo $val; $i ++;}?>]
					},
					{
						name:'移动点击率',
						type: 'line',
						yAxisIndex: 1,
						data: [<?php $i = 1; foreach($site_cpc['mobile_click_lv'] as $val){ if($i > 1){ echo ",";} echo $val; $i ++;}?>]
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