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
<link href="static/css/bootstrap-fullcalendar.css" rel="stylesheet" />
<link href="static/js/datepicker/css/datepicker.css" rel="stylesheet" />
<style>
.metro-nav .metro-nav-block{ width:15.8%;}
.date_div{ position:absolute; top:inherit; left:600px; z-index:999;}

.anniu{ display:none;}
</style>
</head>
<body class="fixed-top">
   <?php echo $top; ?>
   <!-- END HEADER -->
   <!-- BEGIN CONTAINER -->
   <div id="container" class="row-fluid">
      <!-- BEGIN SIDEBAR -->
      <?php echo $sider_menu; ?>
      <!-- END SIDEBAR -->
      <!-- BEGIN PAGE -->
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->
            <?php echo $themes_color_select; ?>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
<form action="" method="get" class="date_form order_form" id="order_list_form" style="height:auto;">

<input type="hidden" value="order" name="c" />

<input type="hidden" value="zx_data" name="m" />

<div class="span5">

    <div class="row-form">
		<label class="select_label">选择时间</label>

		<input type="text" value="<?php echo $start; ?> - <?php echo $end; ?>" style="width:270px;" class="input-block-level" name="date" id="inputDate" />

    </div>
	<div class="row-form">
	<input type="radio" name="sel" value="count" style="width:40px" <?php if($sel=='count') echo 'checked';?> /> 预约
	<input type="radio" name="sel" value="Ncome" style="width:40px" <?php if($sel=='Ncome') echo 'checked';?>  /> 未定预约
	<input type="radio" name="sel" value="Ycome" style="width:40px" <?php if($sel=='Ycome') echo 'checked';?>  /> 预到
	<input type="radio" name="sel" value="come" style="width:40px" <?php if($sel=='come') echo 'checked';?>  /> 到诊
	</div>
	<div class="date_div">

    <div class="divdate"></div>

    <div class="anniu"><a href="javascript:;" class="btn btn-inverse guanbi"> 关闭 </a><br /><a href="javascript:;" class="btn btn-inverse today"> 今天 </a><br /><a href="javascript:;" class="btn btn-inverse week"> 一周 </a><br /><a href="javascript:;" class="btn btn-inverse month"> 一月 </a><br /><a href="javascript:;" class="btn btn-inverse year"> 一年 </a></div>

    </div>
</div>

<div class="span5">
    <div class="row-form">

		<label class="select_label">选择医院</label>

		<select name="hos_id" id="hos_id" style="width:180px;">

			<option value=""><?php echo $this->lang->line('zxy_select'); ?></option>

			<?php foreach($hospital as $val):?><option value="<?php echo $val['hos_id']; ?>" <?php if($val['hos_id'] == $hos_id){echo " selected";}?>><?php echo $val['hos_name']; ?></option><?php endforeach;?>

		</select>

	</div>

</div>

		




<div class="order_btn">

    <button type="submit" class="btn btn-success"> 搜索 </button> 

</div>

</form>
            <div id="page-wraper">

              <div class="row-fluid">
                <div class="span12">
                     <!-- BEGIN NOTIFICATIONS PORTLET-->
                     <div class="widget purple">
                         <div class="widget-title" style="background-color:#00a186;">
                             <h4><i class="icon-hospital"></i> 咨询员预约情况 </h4>
                           <span class="tools">
                               <a href="javascript:;" class="icon-chevron-down"></a>
                               <a href="javascript:;" class="icon-remove"></a>
                           </span>
                         </div>
                         <div class="widget-body"><div id="data" style="height:400px; width:100%; display:block;"></div></div>
                     </div>
                     <!-- END NOTIFICATIONS PORTLET-->
                 </div>
              </div>
			  <div class="row-fluid">
			    <div class="span12">
                     <!-- BEGIN NOTIFICATIONS PORTLET-->
                     <div class="widget blue">
                         <div class="widget-title" style="background-color:#00a186;">
                             <h4><i class="icon-bell"></i> <?php echo $this->lang->line('notification');?> 咨询预约到诊分布情况</h4>
                           <span class="tools">
                               <a href="javascript:;" class="icon-chevron-down"></a>
                               <a href="javascript:;" class="icon-remove"></a>
                           </span>
                         </div>
                         <div class="widget-body" id="data1" style="overflow: hidden; width: auto; height:400px;">
                             
                         </div>
                     </div>
                     <!-- END NOTIFICATIONS PORTLET-->
                 </div>
			  </div>
			  <div class="row-fluid">
                <div class="span12">
                     <!-- BEGIN NOTIFICATIONS PORTLET-->
                     <div class="widget purple">
                         <div class="widget-title" style="background-color:#00a186;">
                             <h4><i class="icon-hospital"></i>未到诊分析报表 </h4>
                           <span class="tools">
                               <a href="javascript:;" class="icon-chevron-down"></a>
                               <a href="javascript:;" class="icon-remove"></a>
                           </span>
                         </div>
                         <div class="widget-body"><div style="height:auto; width:100%; display:block;">
						 
						 <table class="table table-striped table-bordered" id="sample_1">
                                                     <thead>
								 <tr>
								   <th>姓名</th>
								  <th>预约</th>
								  <th>未定预约</th>
								  <th>未定比例</th>
								  <th>预到</th>
								  <th>到诊</th>
								  <th>预到到诊率</th>
								 </tr>
                                                                 <thead>
                                                                     <tbody>
								 <?php
									foreach($des as $k=>$v){
								 ?>
								  <tr class="odd gradeX">
								  <td><?php echo $k;?></td>
								  <td><?php echo $v['count'];?></td>
								  <td><?php echo $v['Ncome'];?></td>
								  <td><?php
										if($v['count']==0){
											echo '0%';
										}else{
											echo number_format((($v['Ncome'] / $v['count']) * 100), 2, '.', '').'%';
										}
								  ?></td>
								  <td><?php echo $v['Ycome'];?></td>
								  <td><?php echo $v['come'];?></td>
								  <td><?php 
										if($v['Ycome']==0){
											echo '0%';
										}else{
											echo number_format((($v['come'] / $v['Ycome']) * 100), 2, '.', '').'%';
										}
								  ?></td>
								 </tr>
								 <?php
									}
								 ?>
                                                                     </tbody></table>
						 </div></div>
                     </div>
                     <!-- END NOTIFICATIONS PORTLET-->
                 </div>
              </div>
                

            </div>

            <!-- END PAGE CONTENT-->
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
      <!-- END PAGE -->
   </div>
   <script src="static/js/jquery.js"></script>
   <script src="static/js/jquery-1.8.3.min.js"></script>
   <script src="static/js/jquery.nicescroll.js" type="text/javascript"></script>
   <script type="text/javascript" src="static/js/jquery.slimscroll.min.js"></script>
   <script type="text/javascript" src="static/js/jquery-ui-1.9.2.custom.min.js"></script>
   <script src="static/js/fullcalendar.min.js"></script>
   <script src="static/js/bootstrap.min.js"></script>
   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="static/js/excanvas.js"></script>
   <script src="static/js/respond.js"></script>
   <![endif]-->
   
   <!--动态表格JS-->
   <script type="text/javascript" src="static/js/jquery.dataTables.js"></script>
   <script type="text/javascript" src="static/js/DT_bootstrap.js"></script>
   <script src="static/js/dynamic-table.js"></script>
   <!--动态表格JS-->
   <script src="static/js/common-scripts.js"></script>
   <script src="static/js/c/esl.js"></script>
   <script type="text/javascript" src="static/js/date.js"></script>

   <script type="text/javascript" src="static/js/daterangepicker.js"></script>

   <script src="static/js/datepicker/js/datepicker.js"></script>
   
   <script language="javascript">
   	$(".anniu").css("display", "block");

	$('.divdate').DatePicker({

		flat: true,

		date: ['<?php echo $start_date; ?>','<?php echo $end_date; ?>'],

		current: '<?php echo $end_date; ?>',

		format: 'Y年m月d日',

		calendars: 2,

		mode: 'range',

		starts: 1,

		onChange: function(formated) {

			$('#inputDate').val(formated.join(' - '));

		}

	});

    $('.date_div').css("display", "none");

	$(".anniu .guanbi").click(function(){

		$('.date_div').css("display", "none");

	});

	$("#inputDate").focus(function(){

		$('.date_div').css("display", "block");

	});

	$(".anniu .today").click(function(){

		$('#inputDate').val(get_day(0) + " - " + get_day(0));

		$('.date_div').css("display", "none");

	});

	$(".anniu .week").click(function(){

		$('#inputDate').val(get_day(-6) + " - " + get_day(0));

		$('.date_div').css("display", "none");

	});

	$(".anniu .month").click(function(){

		$('#inputDate').val(get_day(-29) + " - " + get_day(0));

		$('.date_div').css("display", "none");

	});

	$(".anniu .year").click(function(){

		$('#inputDate').val(get_day(-364) + " - " + get_day(0));

		$('.date_div').css("display", "none");

	});
   
   
   </script>
<script language="javascript">
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
            var myChart1 = ec.init(document.getElementById('data1'));
			
            var option = {
					grid :{
						y:100,
					
					},
					title : {
						text: ''
					},
					tooltip : {
						trigger: 'axis'
					},
					legend: {
						data:[<?php
							foreach($res as $key=>$val){
							
								echo "'" . $key;
								echo "'";
								if($key)
								{
									echo ",";
								}
							}
						?>],
					},
					toolbox: {
                                                    show :true,
                                                    orient: 'vertical',      // 布局方式，默认为水平布局，可选为：
                                                                               // 'horizontal' ¦ 'vertical'
                                                    x: 'right',                // 水平安放位置，默认为全图右对齐，可选为：
                                                                               // 'center' ¦ 'left' ¦ 'right'
                                                                               // ¦ {number}（x坐标，单位px）
                                                    y: 'top',                  // 垂直安放位置，默认为全图顶端，可选为：
                                                                               // 'top' ¦ 'bottom' ¦ 'center'
                                                                               // ¦ {number}（y坐标，单位px）
                                                    color : ['#1e90ff','#22bb22','#4b0082','#d2691e'],
                                                    backgroundColor: 'rgba(0,0,0,0)', // 工具箱背景颜色
                                                    borderColor: '#ccc',       // 工具箱边框颜色
                                                    borderWidth: 0,            // 工具箱边框线宽，单位px，默认为0（无边框）
                                                    padding: 5,                // 工具箱内边距，单位px，默认各方向内边距为5，
                                                    showTitle: true,
                                                    feature : {
                                                        mark : {
                                                            show : true,
                                                            title : {
                                                                mark : '辅助线-开关',
                                                                markUndo : '辅助线-删除',
                                                                markClear : '辅助线-清空'
                                                            },
                                                            lineStyle : {
                                                                width : 1,
                                                                color : '#1e90ff',
                                                                type : 'dashed'
                                                            }
                                                        },
                                                        dataZoom : {
                                                            show : true,
                                                            title : {
                                                                dataZoom : '区域缩放',
                                                                dataZoomReset : '区域缩放-后退'
                                                            }
                                                        },
                                                        dataView : {
                                                            show : true,
                                                            title : '数据视图',
                                                            readOnly: false,
                                                            lang : ['数据视图', '关闭', '刷新']
                                                        },
                                                        magicType: {
                                                            show : false,
                                                            title : {
                                                                line : '动态类型切换-折线图',
                                                                bar : '动态类型切换-柱形图',
                                                                stack : '动态类型切换-堆叠',
                                                                tiled : '动态类型切换-平铺'
                                                            },
                                                            type : ['line', 'bar', 'stack', 'tiled'],
                                                        },
                                                        restore : {
                                                            show : true,
                                                            title : '还原',
                                                            color : 'black'
                                                        },
                                                        saveAsImage : {
                                                            show : true,
                                                            title : '保存为图片',
                                                            type : 'jpeg',
                                                            lang : ['点击本地保存'] 
                                                        },
                                                        
                                                    }
                                                },
					calculable : true,
					xAxis : [
						{
							type : 'category',
							boundaryGap: true,
							data : [<?php
								for($i=$st;$i<=$en;$i++){
									echo "'" . $i;
									echo "'";
									echo ",";
								}
							?>]
						}
					],
					yAxis : [
						{
							type : 'value',
							name : '数量',
							axisLabel : {
								formatter: '{value}'
							},
							splitArea : {show : true}
						}
					],
				
					series : [
						<?php
						foreach($res as $key=>$val)
						{
							echo "{";
							echo "name:'".$key."',";
							echo "type:'bar',";
                                                        echo "stack:'null',";
							echo "smooth:true,";
							echo "itemStyle: {normal: {areaStyle: {type: 'default'}}},";
							echo "data:[";
								foreach($val as $v){
									echo "'" . $v[$sel];
									echo "'";
									echo ",";
								
								}
							
							echo "],";
							echo "markPoint : {";
							echo "data : [";
								echo "	{type : 'max', name: '最大值'},";
								echo "	{type : 'min', name: '最小值'}";
								echo "		]";
								echo"		},";
							echo "}";
							echo ",";
						}
						?>
					]
				};

			var placeHoledStyle = {
				normal:{
					borderColor:'rgba(0,0,0,0)',
					color:'rgba(0,0,0,0)'
				},
				emphasis:{
					borderColor:'rgba(0,0,0,0)',
					color:'rgba(0,0,0,0)'
				}
			};
			var dataStyle = { 
				normal: {
					label : {
						show: true,
						textStyle:{
                                                    color: '#000'
                                                },
						position: 'right',
						formatter: '{c}%'
					}
				}
			};
			
			var option1 = {
				tooltip : {
					trigger: 'axis',
					axisPointer : {            // 坐标轴指示器，坐标轴触发有效
						type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
					},
					formatter : '{b}<br/>{a0}:{c0}%<br/>{a2}:{c2}%<br/>{a4}:{c4}%<br/>'
				},
				legend: {
					y: 55,
					itemGap : document.getElementById('data1').offsetWidth / 6,
					data:['预约','预到','到诊']
				},
				toolbox: {
					show : true,
					feature : {
						mark : {show: true},
						dataView : {show: true, readOnly: false},
						restore : {show: true},
						saveAsImage : {show: true}
					}
				},
				grid: {
					y: 80,
					y2: 30
				},
				xAxis : [
					{
						type : 'value',
						position: 'top',
						splitLine: {show: false},
						axisLabel: {show: false}
					}
				],
				yAxis : [
					{
						type : 'category',
						splitLine: {show: false},
						data : [<?php
							foreach($res as $key=>$val){
							
								echo "'" . $key;
								echo "'";
								if($key)
								{
									echo ",";
								}
							}
						?>]
					}
				],
				series : [
					{
						name:'预约',
						type:'bar',
						stack: '总量',
						itemStyle : dataStyle,
						data:[<?php
							foreach($info_last as $val){
							
								echo "'";
								echo number_format(($val['count'] / $count_s * 100), 2, '.', '');
								echo "'";
								if($val)
								{
									echo ",";
								}
							}
						?>]
					},
					{
						name:'预约',
						type:'bar',
						stack: '总量',
						itemStyle: placeHoledStyle,
						data:[<?php
							foreach($info_last as $val){
							
								echo "'";
								echo 100 - (number_format(($val['count'] / $count_s * 100), 2, '.', ''));
								echo "'";
								if($val)
								{
									echo ",";
								}
							}
						?>]
					},
					{
						name:'预到',
						type:'bar',
						stack: '总量',
						itemStyle : dataStyle,
						data:[<?php
							foreach($info_last as $val){
							
								echo "'";
								echo number_format(($val['Ycome'] / $Ycome_s * 100), 2, '.', '');
								echo "'";
								if($val)
								{
									echo ",";
								}
							}
						?>]
					},
					{
						name:'预到',
						type:'bar',
						stack: '总量',
						itemStyle: placeHoledStyle,
						data:[<?php
							foreach($info_last as $val){
							
								echo "'";
								echo 100 - (number_format(($val['Ycome'] / $Ycome_s * 100), 2, '.', ''));
								echo "'";
								if($val)
								{
									echo ",";
								}
							}
						?>]
					},
					{
						name:'到诊',
						type:'bar',
						stack: '总量',
						itemStyle : dataStyle,
						data:[<?php
							foreach($info_last as $val){
							
								echo "'";
								echo number_format(($val['come'] / $come_s * 100), 2, '.', '');
								echo "'";
								if($val)
								{
									echo ",";
								}
							}
						?>]
					},
					{
						name:'到诊',
						type:'bar',
						stack: '总量',
						itemStyle: placeHoledStyle,
						data:[<?php
							foreach($info_last as $val){
							
								echo "'";
								echo 100 - (number_format(($val['come'] / $come_s * 100), 2, '.', ''));
								echo "'";
								if($val)
								{
									echo ",";
								}
							}
						?>]
					}
				]
			};
                    
			

		myChart.setOption(option);
		myChart1.setOption(option1);

        }
    );
   </script>
</body>
</html>