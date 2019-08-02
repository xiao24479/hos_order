<!doctype html>
<html lang="en" class="no-js m">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1 user-scalable=no">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="renderer" content="webkit">
<title>交通导航 - <?php echo $hos_name;?></title>
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
<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>
</head>
<body>
<?php $this->load->view('web/head');?>
	<div id="ditu" style="width:auto;height:300px"></div>
    <div style='width: auto; height: 180px' id="infoDiv"></div>
<?php $this->load->view('web/foot');?>
    <script>
        var center = new qq.maps.LatLng(<?php echo $hos_pos[0]?>, <?php echo $hos_pos[1]?>);
      	var start = new qq.maps.LatLng(<?php echo $position[0]?>, <?php echo $position[1]?>);
      	var end = new qq.maps.LatLng(<?php echo $hos_pos[0]?>, <?php echo $hos_pos[1]?>);
        var map = new qq.maps.Map(document.getElementById("ditu"), {
            center: center
        });
         //设置获取公交换乘线路方案的服务
        var transferService = new qq.maps.TransferService({
            map: map,
            //展现结果
            panel: document.getElementById('infoDiv')

        });
         //设置搜索地点信息、乘坐方案等属性
        function search() {
            var policy = 'LEAST_TIME';
            //设置乘坐方案
            transferService.setPolicy(qq.maps.TransferPolicy[policy]);
            //设置公交换乘的区域范围
            transferService.setLocation("北京");
            //设置回调函数
            transferService.setComplete(function(result) {
                //如果service返回的结果类型为qq.maps.ServiceResultType.MULTI_DESTINATION（起点或终点位置不唯一），弹出提示信息
                if (result.type == qq.maps.ServiceResultType.MULTI_DESTINATION) {
                    alert("起终点不唯一");
                }
            });
            //设置检索失败回调函数
            transferService.setError(function(result) {
                //如果service返回的结果类型为qq.maps.ServiceErrorType.Error（服务器异常），弹出提示信息
                if (result.type == qq.maps.ServiceErrorType.Error) {
                    alert("服务器异常");
                }
            });
            //设置公交换乘的起点和终点
            transferService.search(start, end);
        }
        window.onload = search;
    </script>

</body>

</html>