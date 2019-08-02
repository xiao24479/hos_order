<!doctype html>
<html lang="en" class="no-js m">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1 user-scalable=no">
  <meta http-equiv="Cache-Control" content="no-siteapp"/>
  <meta name="renderer" content="webkit">
  <title>每日概览 - <?php echo $hos_data[$hos_id];?></title>
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
<div id="doc-oc-demo2" class="am-offcanvas">
  <div class="am-offcanvas-bar">
    <div class="am-offcanvas-content">
      <ul>
		<?php foreach($hos as $val):?>
        <li><?php echo '<a href="http://www.renaidata.com/?c=show&m=order_data&hos_id='.$val['hos_id'].'&p='.$d.'">'.$val['hos_name'].'</a>';?></li>
		<?php endforeach;?>
      </ul>
    </div>
  </div>
</div>
<ul data-am-widget="pagination" class="am-pagination am-pagination-select">
  <li class="am-pagination-prev ">
    <a href="?c=show&m=order_data&hos_id=<?php echo $hos_id;?>&p=<?php echo $d+1;?>"><i class="am-icon-chevron-left"></i> 前一天</a>
  </li>
 
  <li class="am-pagination-next ">
    <a href="?c=show&m=order_data&hos_id=<?php echo $hos_id;?>&p=<?php if($d-1<0){echo 0;}else{echo $d-1;}?>">后一天 <i class="am-icon-chevron-right"></i></a>
  </li>
</ul>
<div class="am-panel am-panel-default am-g">
  <div class="am-panel-hd">
    <h3 class="am-panel-title"><?php echo $hos_data[$hos_id];?><span class="am-fr am-text-default"><?php echo $day;?></span></h3>
  </div>
  <div class="am-panel-bd">
    每日总结
  </div>

  <ul class="am-list am-list-static">
  <li>预约：<?php echo $order['count'];echo '(其中有'.$order['mo'].'人未定)';?><?php if($order['count']>$last_order['count']):?><span class="am-badge am-badge-success am-fr am-text-default">↑<?php echo number_format(((($order['count']-$last_order['count']) / $last_order['count']) * 100), 2, '.', '');?>%</span><?php else:?><span class="am-badge am-badge-warning am-fl am-text-default">↓<?php echo number_format(((($last_order['count']-$order['count']) / $last_order['count']) * 100), 2, '.', '');?>%</span><?php endif;?></li>
  <li>预到：<?php echo $order['will_come'];echo '(其中有'.$order['will_c'].'人来院)';?><?php if($order['will_come']>$last_order['will_come']):?><span class="am-badge am-badge-success am-fr am-text-default">↑<?php echo number_format(((($order['will_come']-$last_order['will_come']) / $last_order['will_come']) * 100), 2, '.', '');?>%</span><?php else:?><span class="am-badge am-badge-warning am-fl am-text-default">↓<?php echo number_format(((($last_order['will_come']-$order['will_come']) / $last_order['will_come']) * 100), 2, '.', '');?>%</span><?php endif;?></li>
  <li>实到：<?php echo $order['come']?><?php if($order['come']>$last_order['come']):?><span class="am-badge am-badge-success am-fr am-text-default">↑<?php echo number_format(((($order['come']-$last_order['come']) / $last_order['come']) * 100), 2, '.', '');?>%</span><?php else:?><span class="am-badge am-badge-warning am-fl am-text-default">↓<?php echo number_format(((($last_order['come']-$order['come']) / $last_order['come']) * 100), 2, '.', '');?>%</span><?php endif;?></li>
  <li>预到到诊率：<?php echo number_format((($order['will_c'] / $order['will_come']) * 100), 2, '.', '');?>%</li>
  
  </ul>
</div>
<hr/>

</body>
</html>