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
<style>
.metro-nav .metro-nav-block{ width:15.8%;}
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

            <div id="page-wraper">
              <div class="row-fluid">
              <div class="metro-nav">
                    <div class="metro-nav-block nav-block-yellow">
                        <a data-original-title="" href="http://www.renaidata.com/?c=order&m=order_list&t=1&date=<?php echo urlencode(date("Y年m月d日", time()) . " - " . date("Y年m月d日", time()));?>#今天预约">
                            <i class="icon-hospital"></i>
                            <div class="info"><?php echo isset($order[0]['add'])? $order[0]['add']:0;?></div>
                            <div class="status">今天预约</div>
                        </a>
                    </div>
                    <div class="metro-nav-block nav-block-yellow">
                        <a data-original-title="" href="http://www.renaidata.com/?c=order&m=order_list&t=3&date=<?php echo urlencode(date("Y年m月d日", time()) . " - " . date("Y年m月d日", time()));?>#今天来院">
                            <i class="icon-user-md"></i>
                            <div class="info"><?php echo isset($order[0]['come'])? $order[0]['come']:0;?><hr />
                            <?php if($order[1]['order'] > 0){
									echo number_format((($order[0]['come'] / $order[0]['order']) * 100), 0, '.', '');
								}
								else
								{
									echo 0;
								}?>%
                            </div>

                            <div class="status">今天来院</div>
                        </a>
                    </div>
                    <div class="metro-nav-block nav-block-green">
                        <a data-original-title="" href="http://www.renaidata.com/?c=order&m=order_list&t=1&date=<?php echo urlencode(date("Y年m月d日", time() - 86400) . " - " . date("Y年m月d日", time() - 86400));?>#昨日预约">
                            <i class="icon-hospital"></i>
                            <div class="info"><?php echo isset($order[1]['add'])? $order[1]['add']:0;?></div>
                            <div class="status">昨日预约</div>
                        </a>
                    </div>
                    <div class="metro-nav-block nav-block-green">
                        <a data-original-title="" href="http://www.renaidata.com/?c=order&m=order_list&t=3&date=<?php echo urlencode(date("Y年m月d日", time() - 86400) . " - " . date("Y年m月d日", time() - 86400));?>#昨日来院">
                            <i class="icon-user-md"></i>
                            <div class="info"><?php echo isset($order[1]['come'])? $order[1]['come']:0;?><hr />
                            <?php if($order[1]['order'] > 0){
									echo number_format((($order[1]['come'] / $order[1]['order']) * 100), 0, '.', '');
								}
								else
								{
									echo 0;
								}?>%
                            </div>
                            <div class="status">昨日来院</div>
                        </a>
                    </div>
                    <div class="metro-nav-block nav-block-red">
                        <a data-original-title="" href="#">
                            <i class="icon-hospital"></i>
                            <div class="info"><?php echo isset($yue['add'])? $yue['add']:0;?></div>
                            <div class="status">本月预约</div>
                        </a>
                    </div>
                    <div class="metro-nav-block nav-block-orange">
                        <a data-original-title="" href="#">
                            <i class="icon-user-md"></i>
                            <div class="info"><?php echo isset($yue['come'])? $yue['come']:0;?></div>
                            <div class="status">本月来院</div>
                        </a>
                    </div>
                </div>
              </div>
              <div class="row-fluid">
                <div class="span12">
                     <!-- BEGIN NOTIFICATIONS PORTLET-->
                     <div class="widget purple">
                         <div class="widget-title" style="background-color:#00a186;">
                             <h4><i class="icon-hospital"></i> 最近30天预约情况 </h4>
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
                             <h4><i class="icon-bell"></i> <?php echo $this->lang->line('notification');?> </h4>
                           <span class="tools">
                               <a href="javascript:;" class="icon-chevron-down"></a>
                               <a href="javascript:;" class="icon-remove"></a>
                           </span>
                         </div>
                         <div class="widget-body">
                             <ul class="item-list scroller padding zhu_list"  style="overflow: hidden; width: auto; height: 320px;" data-always-visible="1">
                                 <script type="text/javascript" src="http://www.renaidata.com/bbs/api.php?mod=js&bid=4"></script>
                             </ul>
                         </div>
                     </div>
                     <!-- END NOTIFICATIONS PORTLET-->
                 </div>
                 <div class="span6">
                     <!-- BEGIN CHAT PORTLET-->
                     <div class="widget red">
                         <div class="widget-title" style="background-color:#00a186;">
                             <h4><i class="icon-comment-alt"></i> <?php echo $this->lang->line('new_post')?></h4>
									<span class="tools">
									<a href="javascript:;" class="icon-chevron-down"></a>
									<a href="javascript:;" class="icon-remove"></a>
									</span>
                         </div>
                         <div class="widget-body post_list" style="overflow: hidden; width: auto; height: 350px;">
                             <script type="text/javascript" src="http://www.renaidata.com/bbs/api.php?mod=js&bid=3"></script>
                         </div>
                     </div>
                     <!-- END CHAT PORTLET-->
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
            var option = {
					title : {
						text: ' 预到不包含未定时间'
					},
					tooltip : {
						trigger: 'axis'
					},
					legend: {
						selected: {
							'预约' : false
						},
						data:['预约','预到','来院', '预约来院转化率'],
					},
					toolbox: {
						show : true,
						feature : {
							mark : {show: true},
							dataView : {show: true, readOnly: false},
							magicType : {show: true, type: ['line', 'bar', 'stack', 'tiled']},
							restore : {show: true},
							saveAsImage : {show: true}
						}
					},
					calculable : true,
					xAxis : [
						{
							type : 'category',
							boundaryGap : false,
							data : [<?php
							foreach($order as $key=>$val){
								echo "'" . date("d", $val['time']);
								if(date("w", $val['time']) == 6 || date("w", $val['time']) == 0)
								{
									echo "*";
								}
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
							foreach($order as $key=>$val){
								echo $val['add'];
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
							name:'预到',
							type:'line',
							smooth:true,
							itemStyle: {normal: {areaStyle: {type: 'default'}}},
							data:[<?php
							foreach($order as $key=>$val){
								echo $val['order'];
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
							name:'来院',
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
							name:'预约来院转化率',
							type:'line',
							symbol: 'star',
							symbolSize : 5,
							yAxisIndex: 1,
							itemStyle: {
								normal: {lineStyle: {width: 0,type: 'dashed'}},
								},
							data:[<?php
							foreach($order as $key=>$val){
								if($val['order'] > 0){
									echo number_format((($val['come'] / $val['order']) * 100), 2, '.', '');

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
            myChart.setOption(option);
        }
    );
   </script>

</body>
</html>