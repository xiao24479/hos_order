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
<div class="filter_wrapper">
	<?php if($type == ""):?>
	<div class="filter selected">类型数据</div>
	<a href="?c=site_seo&m=search_report&type=area&pro_id=<?php echo $pro_info['pro_id']; ?>" class="filter">地区数据</a>
	<?php else:?>
	<a href="?c=site_seo&m=search_report&pro_id=<?php echo $pro_info['pro_id']; ?>" class="filter">类型数据</a>
	<div class="filter selected">地区数据</div>
	<?php endif;?>
	<div class="clear"></div>
</div>
</div>
	  <div class="row-fluid">
		<div class="span12">
		   <div class="widget green">
					<div class="widget-title" style="background-color:#00a186;">
						<h4><i class="icon-reorder"></i><?php echo $pro_info['pro_name'] . '搜索项目 ' . $this->lang->line('pro_report');?></h4>
					<span class="tools">
						<a href="javascript:;" class="icon-chevron-down"></a>
						<a href="javascript:;" class="icon-remove"></a>
					</span>
					</div>
<div class="widget-body">
  <?php if(empty($report)):?>暂无数据<?php else:?><div id="data" style="height:550px; width:98%; display:block;"></div><?php endif;?>
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
   <script src="static/js/c/esl.js"></script>
<script>
<?php if(!empty($report)):?>
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
						'词数量' : false,
						'搜索量比率' : false,
						'百度收录' : false,
						'百度收录比率' : false,
						'google收录' : false,
						'google收录比率' : false,
						'百度竞价数' : false
					},
					data:['词数量','词比率','搜索量','搜索量比率','百度收录','百度收录比率','google收录','google收录比率','百度竞价数']
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
						data : [<?php $i = 1; foreach($report['name'] as $val){ if($i > 1){ echo ",";} echo "'" . $val . "'"; $i ++;}?>]
					},
					{
						type : 'category',
						data : [<?php $i = 1; foreach($report['name'] as $val){ if($i > 1){ echo ",";} echo "'" . $val . "'"; $i ++;}?>]
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
						name: '词数量',
						type: 'bar',
						data:[<?php $i = 1; foreach($report['key_count'] as $val){ if($i > 1){ echo ",";} echo $val; $i ++;}?>]
					},
					{
						name: '词比率',
						type: 'line',
						yAxisIndex: 1,
						data: [<?php $i = 1; foreach($report['key_count_lv'] as $val){ if($i > 1){ echo ",";} echo $val; $i ++;}?>]
					},
					{
						name: '搜索量',
						type: 'bar',
						data: [<?php $i = 1; foreach($report['search_sum'] as $val){ if($i > 1){ echo ",";} echo $val; $i ++;}?>]
					},
					{
						name:'搜索量比率',
						type: 'line',
						yAxisIndex: 1,
						data: [<?php $i = 1; foreach($report['search_sum_lv'] as $val){ if($i > 1){ echo ",";} echo $val; $i ++;}?>]
					},
					{
						name: '百度收录',
						type: 'bar',
						data: [<?php $i = 1; foreach($report['bd_site_sum'] as $val){ if($i > 1){ echo ",";} echo $val; $i ++;}?>]
					},
					{
						name: '百度收录比率',
						type: 'line',
						yAxisIndex: 1,
						data: [<?php $i = 1; foreach($report['bd_site_sum_lv'] as $val){ if($i > 1){ echo ",";} echo $val; $i ++;}?>]
					},
					{
						name:'google收录',
						type: 'bar',
						data: [<?php $i = 1; foreach($report['google_site_sum'] as $val){ if($i > 1){ echo ",";} echo $val; $i ++;}?>]
					},
					{
						name: 'google收录比率',
						type: 'line',
						yAxisIndex: 1,
						data: [<?php $i = 1; foreach($report['google_site_sum_lv'] as $val){ if($i > 1){ echo ",";} echo $val; $i ++;}?>]
					},
					{
						name: '百度竞价数',
						type: 'bar',
						data: [<?php $i = 1; foreach($report['bd_cpc_sum'] as $val){ if($i > 1){ echo ",";} echo $val; $i ++;}?>]
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