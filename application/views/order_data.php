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

<input type="hidden" value="order_data" name="m" />

<div class="span5">

    <div class="row-form">
		<label class="select_label">选择时间</label>

		<input type="text" value="<?php echo $start; ?> - <?php echo $end; ?>" style="width:270px;" class="input-block-level" name="date" id="inputDate" />

    </div>
	<div class="date_div">

    <div class="divdate"></div>

    <div class="anniu"><a href="javascript:;" class="btn btn-inverse guanbi"> 关闭 </a><br /><a href="javascript:;" class="btn btn-inverse today"> 今天 </a><br /><a href="javascript:;" class="btn btn-inverse week"> 一周 </a><br /><a href="javascript:;" class="btn btn-inverse month"> 一月 </a><br /><a href="javascript:;" class="btn btn-inverse year"> 一年 </a></div>

    </div>
	<div class="row-form">

		<label class="select_label"><?php echo $this->lang->line('from_name');?></label>

		<select name="f_p_i" id="from_parent_id" style="width:130px;">

		   <option value="0"><?php echo $this->lang->line('please_select');?></option>

		   <?php foreach($from_list as $val){ ?>

		   <option value="<?php echo $val['from_id'];?>" <?php if($val['from_id'] == $f_p_i){echo " selected";}?>><?php echo $val['from_name'];?></option>

		   <?php } ?>

	   </select>

	   

	   <select name="f_i" id="from_id" style="width:137px;">

	   	<option value="0"><?php echo $this->lang->line('please_select');?></option>

	   </select>

	</div>
</div>

<div class="span5">
    <div class="row-form">

		<label class="select_label"><?php echo $this->lang->line('order_keshi');?></label>

		<select name="hos_id" id="hos_id" style="width:180px;">

			<option value=""><?php echo $this->lang->line('hospital_select'); ?></option>

			<?php foreach($hospital as $val):?><option value="<?php echo $val['hos_id']; ?>" <?php if($val['hos_id'] == $hos_id){echo " selected";}?>><?php echo $val['hos_name']; ?></option><?php endforeach;?>

		</select>

		<select name="keshi_id" id="keshi_id" style="width:130px;">

			<option value=""><?php echo $this->lang->line('keshi_select'); ?></option>

		</select>

	</div>
	<div class="row-form">

		<label class="select_label">病种</label>

		<select name="p_jb" id="p_jb" style="width:165px;">

			<option value=""><?php echo $this->lang->line('jb_parent_select'); ?></option>

			<?php foreach($jibing_parent as $key=>$val):?><option value="<?php echo $val['jb_id']; ?>" <?php if($val['jb_id'] == $p_jb){ echo "selected";}?>><?php echo $val['jb_name']; ?></option><?php endforeach;?>

		</select>
		<select name="jb" id="jb" style="width:165px;">

			<option value=""><?php echo $this->lang->line('jb_child_select'); ?></option>

		</select>

	</div>

</div>

<div>
	 <div class="row-form">
		<label class="select_label">统计类型</label>
		<select name="tj_lx" id="tj_lx" style="width:165px;">
			<option value="0">按天</option>
			<option value="1">按周</option>
			<option value="2">按月</option>
		</select>
	 </div>
</div>		




<div class="order_btn" style="top:170px;">

    <button type="submit" class="btn btn-success"> 搜索 </button> 

</div>

</form>
            <div id="page-wraper">

              <div class="row-fluid">
                <div class="span12">
                     <!-- BEGIN NOTIFICATIONS PORTLET-->
                     <div class="widget purple">
                         <div class="widget-title" style="background-color:#00a186;">
                             <h4><i class="icon-hospital"></i> 预约情况 </h4>
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
			    <div class="span6">
                     <!-- BEGIN NOTIFICATIONS PORTLET-->
                     <div class="widget blue">
                         <div class="widget-title" style="background-color:#00a186;">
                             <h4><i class="icon-bell"></i> <?php echo $this->lang->line('notification');?> 预约病种分布</h4>
                           <span class="tools">
                               <a href="javascript:;" class="icon-chevron-down"></a>
                               <a href="javascript:;" class="icon-remove"></a>
                           </span>
                         </div>
                         <div class="widget-body" id="data1" style="overflow: hidden; width: auto; height: 400px;">
                             
                         </div>
                     </div>
                     <!-- END NOTIFICATIONS PORTLET-->
                 </div>
                 <div class="span6">
                     <!-- BEGIN CHAT PORTLET-->
                     <div class="widget red">
                         <div class="widget-title" style="background-color:#00a186;">
                             <h4><i class="icon-comment-alt"></i> <?php echo $this->lang->line('new_post')?> 预约小病种分布</h4>
									<span class="tools">
									<a href="javascript:;" class="icon-chevron-down"></a>
									<a href="javascript:;" class="icon-remove"></a>
									</span>
                         </div>
                         <div class="widget-body post_list" id="data2" style="overflow: hidden; width: auto; height: 400px;">
                             
                         </div>
                     </div>
                     <!-- END CHAT PORTLET-->
                 </div>
			  </div>
                <div class="row-fluid">
                <div class="span6">
                <!-- BEGIN EXAMPLE TABLE widget-->
                <div class="widget red">
                            <div class="widget-title" style="background-color:#00a186;">
                                <h4><i class="icon-reorder"></i>预约列表</h4>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <a href="javascript:;" class="icon-remove"></a>
                            </span>
                            </div>
                            <div class="widget-body">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                      <th>日期</th>
                                      <th>预约</th>
                                      <th>未定预约</th>
                                      <th>未定比例</th>
                                      <th>预到</th>
                                      <th>到诊</th>
                                      <th>预到到诊率</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tr>
				 <?php
                                    foreach($order_list as $key=>$val){
				 ?>
				    <tr align=center>
				    <td><?php echo $val['tag'];?></td>
                                  <td><?php echo $val['count'];?></td>
                                  <td><?php echo $val['come'];?></td>
                                  <td><?php
                                            if($val['count']==0){

                                                    echo '0%';

                                            }else{

                                                    echo number_format((($val['come'] / $val['count']) * 100), 2, '.', '');

                                            }
                                      ?>%
                                  </td>
                                  <td><?php echo $order[$key]['count'];?></td>
                                  <td><?php echo $order[$key]['come'];?></td>
                                  <td><?php 
                                            if($order[$key]['count']==0){
                                               echo '0%';
                                            }else{
                                                  echo number_format((($order[$key]['come'] / $order[$key]['count']) * 100), 2, '.', '');
                                                 }
                                      ?>%
                                  </td>
                                 </tr>
                                 <?php
                                        }
                                 ?>                                    
                                    </tbody>
                                </table>
                            </div>
                        </div>
                <!-- END EXAMPLE TABLE widget-->
                </div>
                <div class="span6">
                <!-- BEGIN EXAMPLE TABLE widget-->
                <div class="widget red">
                            <div class="widget-title" style="background-color:#00a186;">
                                <h4><i class="icon-reorder"></i>病种列表</h4>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <a href="javascript:;" class="icon-remove"></a>
                            </span>
                            </div>
                            <div class="widget-body">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
				      <th>序号</th>
                                      <th>病种</th>
                                      <th>预约</th>
                                      <th>到诊</th>
                                      <th>到诊率</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tr>
				 <?php
                                    rsort($order_jb);
                                    foreach($order_jb as $key=>$val){
				 ?>
				    <tr align=center>
					<td><?php echo $key+1;?></td>
				    <td><?php 
					
					if(array_key_exists($val['jb_parent_id'],$jb_arr)){
									if($jb_arr[$val['jb_parent_id']]){
										echo $jb_arr[$val['jb_parent_id']];
									}else{
									
										echo '未填写';
									}
									
								}else{
								
									echo '未知';
								}
					?></td>
                                  <td><?php echo $val['count'];?></td>                              
                                  <td><?php echo $val['come'];?></td>                              
                                  <td><?php 
                                            echo number_format((($val['come'] / $val['count']) * 100), 2, '.', '');                                                 
                                      ?>%</td>
                                 </tr>
                                 <?php
                                        }
                                 ?>                                    
                                    </tbody>
                                </table>
                            </div>
                        </div>
                <!-- END EXAMPLE TABLE widget-->
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
   
   <script src="static/js/common-scripts.js"></script>
   <script src="static/js/c/esl.js"></script>
	<script type="text/javascript" src="static/js/date.js"></script>
	<script type="text/javascript" src="static/js/daterangepicker.js"></script>
	<script src="static/js/datepicker/js/datepicker.js"></script>
	<script language="javascript">
$(document).ready(function(e) {

<?php if($hos_id > 0):?>

ajax_get_keshi(<?php echo $hos_id?>, <?php echo $keshi_id?>);

<?php endif;?>
<?php if($f_p_i > 0):?>

ajax_from(<?php echo $f_p_i?>, <?php echo $f_i?>);

<?php endif;?>
<?php if($p_jb > 0):?>

ajax_get_jibing(0, <?php echo $p_jb?>, <?php echo $jb?>);

<?php endif;?>
	
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
	$("#inputDate").focus(function(){
	
		$('.date_div').css("display", "block");
	});
	
	$(".anniu .guanbi").click(function(){
		$('.date_div').css("display", "none");

	});
	
	$(".anniu .today").click(function(){
		

	

		$('.date_div').css("display", "none");

	});

	$(".anniu .week").click(function(){

	

		$('.date_div').css("display", "none");

	});

	$(".anniu .month").click(function(){



		$('.date_div').css("display", "none");

	});

	$(".anniu .year").click(function(){

	

		$('.date_div').css("display", "none");

	});
	
	$("#hos_id").change(function(){

		var hos_id = $(this).val();

		ajax_get_keshi(hos_id, 0);

	});
	
function ajax_get_keshi(hos_id, check_id)

{

	$.ajax({

		type:'post',

		url:'?c=order&m=keshi_list_ajax',

		data:'hos_id=' + hos_id + '&check_id=' + check_id,

		success:function(data)

		{

			$("#keshi_id").html(data);

		},

		complete: function (XHR, TS)

		{

		   XHR = null;

		}

	});

}

	$("#p_jb").change(function(){

		var parent_id = $(this).val();

		ajax_get_jibing(0, parent_id, 0);

	});
	
	$("#from_parent_id").change(function(){

		var parent_id = $(this).val();

		ajax_from(parent_id, 0);

	});
	
	$("#keshi_id").change(function(){

		var keshi_id = $(this).val();

		ajax_get_jibing(keshi_id, 0, 0);

	});
	function ajax_get_jibing(keshi_id, parent_id, check_id)

{

	$("#p_jb").after("<i class='icon-refresh icon-spin'></i>");

	$.ajax({

		type:'post',

		url:'?c=order&m=jibing_ajax',

		data:'keshi_id=' + keshi_id + '&parent_id=' + parent_id + '&check_id=' + check_id,

		success:function(data)

		{

			if(parent_id == 0)

			{

				$("#p_jb").html(data);

			}

			else

			{

				$("#jb").html(data);

			}

			

		},

		complete: function (XHR, TS)

		{

		   XHR = null;

		   $("#p_jb").next(".icon-spin").remove();

		}

	});

}
	
	function ajax_from(parent_id, from_id)

{

	$.ajax({

		type:'post',

		url:'?c=order&m=from_order_ajax',

		data:'parent_id=' + parent_id + '&from_id=' + from_id + '&tag=-1',

		success:function(data)

		{

		   $("#from_id").html(data);

		},

		complete: function (XHR, TS)

		{

		   XHR = null;

		}

	});	

}
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
            'echarts/chart/line',
            'echarts/chart/pie'
        ],
        function(ec) {
            var myChart = ec.init(document.getElementById('data'));
            var option = {
					title : {
						text: ' '
					},
					tooltip : {
						trigger: 'axis'
					},
					legend: {
						selected: {
							'预到' : false,
                                                        '预到到诊率' : false,
                                                        '到诊' : false
						},
						data:['预约','未定预约','未定比例','预到', '到诊','预到到诊率'],
					},
					toolbox: {
                                                    show : true,
                                                    orient: 'horizontal',      // 布局方式，默认为水平布局，可选为：
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
                                                            show : true,
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
							boundaryGap : false,
							data : [<?php
							foreach($order_list1 as $key=>$val){
								echo "'" . $val['tag'];
								echo "'";
								if($key >= 0)
								{
									echo ",";
								}
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
						},
						{
							type : 'value',
							name : '转化率',
							axisLabel : {
								formatter: '{value} %'
							},
							splitLine : {show : false}
						}
					],
					series : [
						{
							name:'预约',
							type:'line',
							smooth:true,
							itemStyle: {normal: {areaStyle: {type: 'default'}}},
							data:[<?php
							foreach($order_list1 as $key=>$val){
								echo $val['count'];
								if($key >= 0)
								{
									echo ",";
								}
							}
							?>],
							markPoint : {
								data : [
									{type : 'max', name: '最大值'},
									{type : 'min', name: '最小值'}
										]
										},
						},
						{
							name:'未定预约',
							type:'line',
							smooth:true,
							itemStyle: {normal: {areaStyle: {type: 'default'}}},
							data:[<?php
							foreach($order_list as $key=>$val){
								echo $val['come'];
								if($key >= 0)
								{
									echo ",";
								}
							}
							?>],
							markPoint : {
								data : [
									{type : 'max', name: '最大值'},
									{type : 'min', name: '最小值'}
										]
										},
						},
						{
							name:'未定比例',
							type:'line',
							symbol: 'star',
							symbolSize : 5,
							yAxisIndex: 1,
							itemStyle: {
								normal: {lineStyle: {width: 1,type: 'dashed'}},
								},
							data:[<?php
							foreach($order_list1 as $key=>$val){
								if($val['count'] > 0){
									echo number_format((($val['come'] / $val['count']) * 100), 2, '.', '');

								}
								else
								{
									echo 0;
								}

								if($key >= 0)
								{
									echo ",";
								}
							}
							?>],
							markPoint : {
								data : [
									{type : 'max', name: '最大值'},
									{type : 'min', name: '最小值'}
										]
										},
							markLine : {
								data : [
									{type : 'average', name : '平均转化率'}
								]
							}
						},
						{
							name:'预到',
							type:'line',
							smooth:true,
							itemStyle: {normal: {areaStyle: {type: 'default'}}},
							data:[<?php
							foreach($order as $key=>$val){
								echo $val['count'];
								if($key >= 0)
								{
									echo ",";
								}
							}
							?>],
							markPoint : {
								data : [
									{type : 'max', name: '最大值'},
									{type : 'min', name: '最小值'}
										]
										},
						},
{
							name:'到诊',
							type:'line',
							smooth:true,
							itemStyle: {normal: {areaStyle: {type: 'default'}}},
							data:[<?php
							foreach($order as $key=>$val){
								echo $val['come'];
								if($key >= 0)
								{
									echo ",";
								}
							}
							?>],
							markPoint : {
								data : [
									{type : 'max', name: '最大值'},
									{type : 'min', name: '最小值'}
										]
										},
						},
						{
							name:'预到到诊率',
							type:'line',
							symbol: 'star',
							symbolSize : 5,
							yAxisIndex: 1,
							itemStyle: {
								normal: {lineStyle: {width: 1,type: 'dashed'}},
								},
							data:[<?php
							foreach($order as $key=>$val){
								if($val['count'] > 0){
									echo number_format((($val['come'] / $val['count']) * 100), 2, '.', '');

								}
								else
								{
									echo 0;
								}

								if($key >= 0)
								{
									echo ",";
								}
							}
							?>],
							markPoint : {
								data : [
									{type : 'max', name: '最大值'},
									{type : 'min', name: '最小值'}
										]
										},
							markLine : {
								data : [
									{type : 'average', name : '平均转化率'}
								]
							}
						}
					]
				};
            
		
			var myChart1 = ec.init(document.getElementById('data1'));
			option1 = {
				title : {
					text: '',
					x:'center'
				},
				tooltip : {
					trigger: 'item',
					formatter: "{a} <br/>{b} : {c} ({d}%)"
				},
				legend: {
					orient : 'vertical',
					x : 'left',
					data:[
					
					
					]
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
				calculable : true,
				series : [
					{
						name:'分布情况',
						type:'pie',
						radius : '75%',
						center: ['60%', 225],
						data:[
							<?php
							foreach($little as $key=>$val){
								echo "{value:" . $val['count'];
								echo ",";
								if(array_key_exists($val['jb_parent_id'],$jb_arr)){
									if($jb_arr[$val['jb_parent_id']]){
										echo " name:'" . $jb_arr[$val['jb_parent_id']];
										}else{
										echo "name:'未填写";
										}
								}else{
									echo " name:'未知".$val['jb_parent_id'];
								}
								echo "'}";
								if($key >= 0)
								{
									echo ",";
								}
							}
							?>
						]
					}
				]
			};
		var myChart2 = ec.init(document.getElementById('data2'));
			option2 = {
				title : {
					text: '',
					x:'center'
				},
				tooltip : {
					trigger: 'item',
					formatter: "{a} <br/>{b} : {c} ({d}%)"
				},
				legend: {
					orient : 'vertical',
					x : 'left',
					data:[
					
					
					]
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
				calculable : true,
				series : [
					{
						name:'分布情况',
						type:'pie',
						radius : '75%',
						center: ['60%', 225],
						data:[
							<?php
							foreach($big as $key=>$val){
								echo "{value:" . $val['count'];
								echo ",";
								if(array_key_exists($val['jb_parent_id'],$jb_arr)){
									if($jb_arr[$val['jb_parent_id']]){
										echo " name:'" . $jb_arr[$val['jb_parent_id']];
										}else{
										echo "name:'未填写";
										}
								}else{
								echo " name:'未知".$val['jb_parent_id'];
								}
								echo "'}";
								if($key >= 0)
								{
									echo ",";
								}
							}
								echo "{value:" . $little_c;
								echo ",";
								echo " name:'其它'";
								echo "}";
							
							?>
						]
					}
				]
			};
		myChart.setOption(option);
		myChart1.setOption(option2);
		myChart2.setOption(option1);
        }
    );
   </script>

</body>
</html>