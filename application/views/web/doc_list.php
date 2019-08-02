<!doctype html>
<html lang="en" class="no-js m">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1  user-scalable=no">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="renderer" content="webkit">
<title>医生列表 - <?php echo $hos_name;?></title>
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

  <!--菜单开始-->
 <?php $this->load->view('web/head');?>
  <!--菜单结束--> 
</header>

<ul data-am-widget="gallery" class="am-gallery am-avg-sm-2
  am-avg-md-3 am-avg-lg-4 am-gallery-default" data-am-gallery="{ pureview: true }">
 <?php foreach($docter as $val):?>
 <li>
    <div class="am-gallery-item"> 
		<img class="am-img-thumbnail" onclick="location='?c=show&m=doc_long&doc_id=<?php echo $val['doc_id'];?>'" width="324" height="400" src="static/upload/<?php echo $val['doc_img'];?>" alt="妇产科 医生名字 副主任医师" />
		<a href="?c=show&m=order&docter_name=<?php echo $val['doc_name'];?>"><button type="button" class="am-btn am-btn-primary am-btn-block"><?php echo $val['doc_name']?> 马上预约</button></a>
		<div class="am-gallery-desc"><?php echo $val['keshi_name'].' '.$val['doc_zc'];?></div>
    </div>
  </li>
  <?php endforeach;?>
</ul>

<!--分页-->
<ul class="am-pagination">
  <li class="am-pagination-prev"><a href="?c=show&m=get_all_docter&p=<?php echo $p-1;?>">&laquo; Prev</a></li>
  <li class="am-pagination-next"><a href="?c=show&m=get_all_docter&p=<?php echo $p+1;?>">Next &raquo;</a></li>
</ul>
<!--分页--> 

<!--底部菜单-->
 <?php $this->load->view('web/foot');?>
<!--底部菜单-->
</body>
</html>