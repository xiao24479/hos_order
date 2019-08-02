<!doctype html>
<html lang="en" class="no-js m">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1  user-scalable=no">
  <meta http-equiv="Cache-Control" content="no-siteapp"/>
  <meta name="renderer" content="webkit">
  <title><?php echo $title;?> - <?php echo $hos_name;?></title>
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


<!--文章内容-->
<blockquote><h1><?php echo $title;?></h1></blockquote>
<article data-am-widget="paragraph" class="am-paragraph am-paragraph-default" data-am-paragraph="{ tableScrollable: true, pureview: true }">
  <?php echo $content;?>


</article>
<!--文章内容-->
 <!--文章功能-->

 <div class="am-btn-group" style="margin-bottom: 50px;">
 <button type="button" class="am-btn am-btn-default"><span class="am-icon-eye"></span><?php echo $click;?></button>
 <button type="button" class="am-btn am-btn-default"><span class="am-icon-thumbs-up"></span><span id="up_num"><?php echo $good; ?></span></button>
 <button type="button" class="am-btn am-btn-default"><span class="am-icon-clock-o am-right"></span><?php echo $modified;?></button>
</div>
   
<!--文章功能-->


<!--底部菜单-->
<?php $this->load->view('web/foot');?>
<!--底部菜单-->
<script>
$(function(){
	$('.am-icon-thumbs-up').click(function(){
	
		$.ajax({

			type:'post',

			url:'?c=show&m=up_num',

			data:'pid=<?php echo $pid;?>',

			success:function(data)

			{
				var num = $('#up_num').text();
				
				$('#up_num').text(parseInt(num)+1);
			},

			complete: function (XHR, TS)

			{

			   XHR = null;

			}

		});
	});


});
</script>
</body>
</html>