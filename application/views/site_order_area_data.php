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
			<input type="hidden" value="order_area_data" name="m" />
			<input type="hidden" value="<?php echo $site_info['site_id']; ?>" name="site_id" />
			  <div class="row-fluid">
				<div class="filter_wrapper">
					<a href="?c=site&m=data&site_id=<?php echo $site_info['site_id']; ?>" class="filter"><?php echo $this->lang->line('chart_data'); ?></a>
					<a href="?c=site&m=area_data&site_id=<?php echo $site_info['site_id']; ?>" class="filter"><?php echo $this->lang->line('area_data'); ?></a>
					<div class="filter selected"><?php echo $this->lang->line('order_area_data'); ?></div>
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
                            <div class="widget-title">
                                <h4><i class="icon-reorder"></i> <?php echo $site_info['site_name'] . " (" . $site_info['site_domain'] . ")"; ?> <?php echo $this->lang->line('order_area_data'); ?></h4>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <a href="javascript:;" class="icon-remove"></a>
                            </span>
                            </div>
				<div class="widget-body">
				    <div id="area_ask" class="data_div"></div>
                    <div id="area_data" class="data_div" style="height:500px;"></div>
                    <div id="asker_ask" class="data_div"></div>
                    <div id="asker_data" class="data_div" style="height:500px;"></div>
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
            'echarts/chart/line': 'static/js/c/echarts',
            'echarts/chart/pie': 'static/js/c/echarts'
        }
    });
	require(
        [
             'echarts',
			'echarts/chart/line',
			'echarts/chart/bar',
			'echarts/chart/pie'
        ],
        function(ec) {
            var myChart = ec.init(document.getElementById('area_ask'));
			var option = {
				title : {
					text: '对话来源城市',
					x:'center'
				},
				tooltip : {
					trigger: 'item',
					formatter: "{a} <br/>{b} : {c} ({d}%)"
				},
				legend: {
					orient : 'vertical',
					x : 'left',
					data:[<?php $i = 1; foreach($area_data['area'] as $key => $val){ if($i > 1){ echo ",";} echo "'" . $key . "'"; $i ++;}?>]
				},
				toolbox: {
					show : true,
					feature : {
						mark : true,
						dataView : {readOnly: false},
						restore : true,
						saveAsImage : true
					}
				},
				calculable : true,
				series : [
					{
						name:'对话数',
						type:'pie',
						radius : [0, 110],
						center: [,225],
						data:[
							<?php $i = 1; foreach($area_data['area'] as $key=>$val){ if($i > 1){ echo ",";} echo "{value:" . $val['ask'] . ", name:'" . $key . "'}"; $i ++;}?>
						]
					}
				]
			};
            myChart.setOption(option);
        }
    );
	
	require(
        [
            'echarts',
			'echarts/chart/line',
			'echarts/chart/bar'
        ],
        function(ec) {
			var myChart = ec.init(document.getElementById('area_data'));
            var option = {
				title : {
					text: '对话、预约、到诊来源城市'
				},
				tooltip : {
					trigger: 'axis'
				},
				legend: {
					x:'center',
					data:['对话数','预约数','到诊数']
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
						data : [<?php $i = 1; foreach($area_data['area'] as $key => $val){ if($i > 1){ echo ",";} echo "'" . $key . "'"; $i ++;}?>]
					},
					{
						type : 'category',
						data : [<?php $i = 1; foreach($area_data['area'] as $key => $val){ if($i > 1){ echo ",";} echo "'" . $key . "'"; $i ++;}?>]
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
						precision: 0,
						splitNumber: 10,
						axisLabel : {
							formatter: function(value) {
								return value
							}
						},
						splitLine : {
							show: false
						}
					}
				],
				series : [
					{
						name: '对话数',
						type: 'line',
						yAxisIndex: 1,
						data:[<?php $i = 1; foreach($area_data['area'] as $val){ if($i > 1){ echo ",";} echo $val['ask']; $i ++;}?>]
					},
					{
						name: '预约数',
						type: 'bar',
						data: [<?php $i = 1; foreach($area_data['area'] as $val){ if($i > 1){ echo ",";} echo $val['order']; $i ++;}?>]
					},
					{
						name: '到诊数',
						type: 'bar',
						data: [<?php $i = 1; foreach($area_data['area'] as $val){ if($i > 1){ echo ",";} echo $val['dao']; $i ++;}?>]
					}
				]
			};
            myChart.setOption(option);
        }
    );
	
	require(
        [
             'echarts',
			'echarts/chart/line',
			'echarts/chart/bar',
			'echarts/chart/pie'
        ],
        function(ec) {
            var myChart = ec.init(document.getElementById('asker_ask'));
			var option = {
				title : {
					text: '咨询员处理对话数',
					x:'center'
				},
				tooltip : {
					trigger: 'item',
					formatter: "{a} <br/>{b} : {c} ({d}%)"
				},
				legend: {
					orient : 'vertical',
					x : 'left',
					data:[<?php $i = 1; foreach($area_data['asker'] as $key => $val){ if($i > 1){ echo ",";} echo "'" . $key . "'"; $i ++;}?>]
				},
				toolbox: {
					show : true,
					feature : {
						mark : true,
						dataView : {readOnly: false},
						restore : true,
						saveAsImage : true
					}
				},
				calculable : true,
				series : [
					{
						name:'对话数',
						type:'pie',
						radius : [0, 110],
						center: [,225],
						data:[
							<?php $i = 1; foreach($area_data['asker'] as $key=>$val){ if($i > 1){ echo ",";} echo "{value:" . $val['ask'] . ", name:'" . $key . "'}"; $i ++;}?>
						]
					}
				]
			};
            myChart.setOption(option);
        }
    );
	
	require(
        [
            'echarts',
			'echarts/chart/line',
			'echarts/chart/bar'
        ],
        function(ec) {
			var myChart = ec.init(document.getElementById('asker_data'));
            var option = {
				title : {
					text: '咨询员对话、预约、到诊'
				},
				tooltip : {
					trigger: 'axis'
				},
				legend: {
					x:'center',
					data:['对话数','预约数','到诊数']
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
						data : [<?php $i = 1; foreach($area_data['asker'] as $key => $val){ if($i > 1){ echo ",";} echo "'" . $key . "'"; $i ++;}?>]
					},
					{
						type : 'category',
						data : [<?php $i = 1; foreach($area_data['asker'] as $key => $val){ if($i > 1){ echo ",";} echo "'" . $key . "'"; $i ++;}?>]
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
						precision: 0,
						splitNumber: 10,
						axisLabel : {
							formatter: function(value) {
								return value
							}
						},
						splitLine : {
							show: false
						}
					}
				],
				series : [
					{
						name: '对话数',
						type: 'line',
						yAxisIndex: 1,
						data:[<?php $i = 1; foreach($area_data['asker'] as $val){ if($i > 1){ echo ",";} echo $val['ask']; $i ++;}?>]
					},
					{
						name: '预约数',
						type: 'bar',
						data: [<?php $i = 1; foreach($area_data['asker'] as $val){ if($i > 1){ echo ",";} echo $val['order']; $i ++;}?>]
					},
					{
						name: '到诊数',
						type: 'bar',
						data: [<?php $i = 1; foreach($area_data['asker'] as $val){ if($i > 1){ echo ",";} echo $val['dao']; $i ++;}?>]
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