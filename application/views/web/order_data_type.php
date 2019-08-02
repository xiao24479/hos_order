<!doctype html>
<html lang="en" class="no-js m">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1 user-scalable=no">
  <meta http-equiv="Cache-Control" content="no-siteapp"/>
  <meta name="renderer" content="webkit">
  <title><?php echo $title;?> - <?php echo $hos_data[$hos_id];?></title>
  <meta name="description" content="">
  <link rel="alternate icon" type="image/png" href="i/favicon.png">
  <link rel="icon" type="image/svg+xml" href="i/favicon.svg"/>
  <link rel="apple-touch-icon-precomposed" href="i/app-icon72x72@2x.png">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="stylesheet" href="static/phone/css/amui.all.min.css">
  <script src="static/phone/js/jquery.min.js"></script>
  <script src="static/phone/js/handlebars.min.js"></script>
  <script src="http://cdn.amazeui.org/amazeui/2.0.0/js/amazeui.min.js"></script>
</head>
<body>
<?php $this->load->view('web/head1');?>

<hr/>


<!-- 侧边栏内容 -->
<div id="doc-oc-demo2" class="am-offcanvas">
  <div class="am-offcanvas-bar">
    <div class="am-offcanvas-content">
      <ul>
		<?php foreach($hos as $val):?>
        <li><?php echo '<a href="http://www.renaidata.com/?c=show&m=order_data_type&hos_id='.$val['hos_id'].'&type='.$type.'">'.$val['hos_name'].'</a>';?></li>
		<?php endforeach;?>
      </ul>
    </div>
  </div>
</div>

<section data-am-widget="accordion" class="am-accordion am-accordion-default"
data-am-accordion='{ "multiple": true }'>
  <dl class="am-accordion-item am-active">
    <dt class="am-accordion-title">每<?php if($type=="zhou"):?>周<?php elseif($type=="yue"):?>月<?php else:?>日<?php endif;?>数据趋势图<span class="am-fr am-text-default"><?php echo $hos_data[$hos_id]?></span></dt>
    <dd class="am-accordion-bd am-collapse am-in">
      <!-- 规避 Collapase 处理有 padding 的折叠内容计算计算有误问题， 加一个容器 -->
      <div class="am-accordion-content" id="data" style="width:100%;height:300px;">
    
      </div>
    </dd>
  </dl>
  <dl class="am-accordion-item am-active">
    <dt class="am-accordion-title">每<?php if($type=="zhou"):?>周<?php elseif($type=="yue"):?>月<?php else:?>日<?php endif;?>数据详细表<span class="am-fr am-text-default"><?php echo $hos_data[$hos_id]?></span></dt>
    <dd class="am-accordion-bd am-collapse am-in">
      <!-- 规避 Collapase 处理有 padding 的折叠内容计算计算有误问题， 加一个容器 -->
      <div class="am-accordion-content">
      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="am-table am-table-bordered am-table-radius am-table-striped">
  <tr>
    <th>时间</th>
    <th>预约</th>
    <th>预到</th>
    <th>实到</th>
    
    <th>到诊率</th>
    </tr>
	<?php if(empty($type)):?>
	<?php foreach($order as $tag=>$val):?>
	  <tr>
		<td><?php echo $tag;?></td>
		<td><?php echo $val['count'];?></td>
		<td><?php echo $val['will_come'];?></td>
		<td><?php echo $val['come'];?></td>
		
		<td><?php echo number_format((($val['come'] / $val['will_come']) * 100), 2, '.', '');?>%</td>
	  </tr>
	<?php endforeach;?>
  <?php elseif('zhou' == $type):?>
	<?php foreach($order as $tag=>$val):?>
	  <tr>
		<td>第<?php echo $tag;?>周</td>
		<td><?php echo $val['count'];?></td>
		<td><?php echo $val['will_come'];?></td>
		<td><?php echo $val['come'];?></td>
		
		<td><?php echo number_format((($val['come'] / $val['will_come']) * 100), 2, '.', '');?></td>
	  </tr>
	<?php endforeach;?> 
  <?php elseif('yue' == $type):?>
	<?php foreach($order as $tag=>$val):?>
	  <tr>
		<td><?php echo $tag;?>月</td>
		<td><?php echo $val['count'];?></td>
		<td><?php echo $val['will_come'];?></td>
		<td><?php echo $val['come'];?></td>
		
		<td><?php echo number_format((($val['come'] / $val['will_come']) * 100), 2, '.', '');?></td>
	  </tr>
	<?php endforeach;?> 
  <?php endif;?>
</table>
      </div>
    </dd>
  </dl>
</section>
<hr />

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
            'echarts/chart/line',
        ],
	function(ec) {
		var myChart = ec.init(document.getElementById('data'));
		option = {
			tooltip : {
				trigger: 'axis'
			},
			legend: {
                                selected: {
							'预约' : false
						},
                                y: '-0',                
				data:['预约','预到','实到']
			},
			toolbox: {
				show : false,
				feature : {
					mark : {show: true},
					dataView : {show: true, readOnly: false},
					magicType : {show: true, type: ['line', 'bar', 'stack', 'tiled']},
					restore : {show: true},
					saveAsImage : {show: true}
				}
			},
			grid: {
					x: 40,
					y: 20,
					x2: 5,
					y2: 20,
					// width: {totalWidth} - x - x2,
					// height: {totalHeight} - y - y2,
				},

			calculable : true,
			xAxis : [
				{
					type : 'category',
					boundaryGap : false,
					data : [<?php
							foreach($order as $key=>$val){
								if(empty($type)){
									$arr = explode('-',$key);
									$key = $arr[2];
								}
								echo "'" . $key;
								
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
					type : 'value'
				}
			],
			series : [
				{
					name:'预约',
					type:'line',
					
					itemStyle: {normal: {areaStyle: {type: 'default'}}},
					data:[<?php
							foreach($order as $key=>$val){
								echo $val['count'];
								if($key >= 0)
								{
									echo ",";
								}
							}
							?>]
				},
								
				{
					name:'预到',
					type:'line',
					
					itemStyle: {normal: {areaStyle: {type: 'default'}}},
					data:[<?php
							foreach($order as $key=>$val){
								echo $val['will_come'];
								if($key >= 0)
								{
									echo ",";
								}
							}
							?>]
                                },
				{
					name:'实到',
					type:'line',
					
					itemStyle: {normal: {areaStyle: {type: 'default'}}},
					data:[<?php
							foreach($order as $key=>$val){
								echo $val['come'];
								if($key >= 0)
								{
									echo ",";
								}
							}
							?>]
				}
				
				
			]
		};
		myChart.setOption(option);
	} 
);                   
</script>
</body>
</html>