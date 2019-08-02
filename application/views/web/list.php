<!doctype html>
<html lang="en" class="no-js m">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1  user-scalable=no">
  <meta http-equiv="Cache-Control" content="no-siteapp"/>
  <meta name="renderer" content="webkit">
  <title><?php echo $meta_name;?> 列表 - <?php echo $hos_name;?></title>
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
<?php $this->load->view('web/head');?>




<!--文章列表-->
<div data-am-widget="list_news" class="am-list-news am-list-news-default">
  <!--列表标题-->
  
 <div class="am-list-news-hd am-cf">
    <!--带更多链接-->
    <a href="###">
      <h2><?php echo $meta_name;?></h2>
     <!-- <span class="am-list-news-more am-fr">更多 &raquo;</span>-->
    </a>
  </div>
  <div class="am-list-news-bd">
    <ul class="am-list">
      <!--缩略图在标题左边-->
	  <?php foreach($posts as $val):?>
      <li class="am-g am-list-item-desced am-list-item-thumbed am-list-item-thumb-left">
		<?php if(!empty($val['thumb'])):?>
        <div class="am-col col-sm-4 am-list-thumb">
          <a href="?c=show&m=view&slug=<?php echo $val['slug'];?>">
            <img src="static/upload/<?php echo $val['thumb']?>" alt="我很囧，你保重....晒晒旅行中的那些囧！"/>
          </a>
        </div>
		<?php endif;?>
        <div class="am-col col-sm-12 am-list-main">
          <h3 class="am-list-item-hd">
            <a href="?c=show&m=view&slug=<?php echo $val['slug'];?>"><?php echo $val['title'];?></a>
          </h3>
          <div class="am-list-item-text"><?php echo $val['description']?></div>
        </div>
      </li>
		<?php endforeach; ?>
    </ul>
  </div>
</div>
<!--文章列表-->

<!--分页-->
	<?php echo $page;?>
<!--分页-->

<!--底部菜单-->
<?php $this->load->view('web/foot');?>
<!--底部菜单-->
</body>
</html>