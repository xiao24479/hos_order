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
<!--<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyADMtBBeXtMywdtWWccenLOn-EW-90F2Nw&sensor=false"></script>-->
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=5ace1e167c03ae8f6b481ef6066c69d7"></script>
</head>

<body class="fixed-top"  onload="initialize()">
   <?php echo $top; ?>
   <div id="container" class="row-fluid">
   <?php echo $sider_menu; ?>
   <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
<div style="height:10px;"></div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
			  <div class="row-fluid">
				<div class="filter_wrapper">
					<a href="?c=site&m=data&site_id=<?php echo $site_info['site_id']; ?>" class="filter"><?php echo $this->lang->line('chart_data'); ?></a>
					<div class="filter selected"><?php echo $this->lang->line('map_data'); ?></div>
					<div class="clear"></div>
				</div>
			  </div>
              <div class="row-fluid" id="row_map">
			    <div class="span12">
				  <div class="widget purple">
                            <div class="widget-title">
                                <h4><i class="icon-reorder"></i> <?php echo $site_info['site_name'] . " (" . $site_info['site_domain'] . ")"; ?> <?php echo $this->lang->line('map_data'); ?></h4>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <a href="javascript:;" class="icon-remove"></a>
                            </span>
                            </div>
				<div class="widget-body" style="padding:0;">
                  <div id="map_canvas" style="height:650px; width:100%;"></div>
				</div>
			  </div>
			  </div>
            </div>
            <!-- END PAGE CONTENT-->         
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
</div>
   <script src="static/js/jquery-1.8.3.min.js"></script>
   <script src="static/js/jquery.nicescroll.js" type="text/javascript"></script>
   <script src="static/js/bootstrap.min.js"></script>
   <script type="text/javascript" src="static/js/jquery.slimscroll.min.js"></script>
   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="static/js/excanvas.js"></script>
   <script src="static/js/respond.js"></script>
   <![endif]-->
   <script src="static/js/common-scripts.js"></script>  
   <script language="javascript">
/*var stockholm = new google.maps.LatLng(22.520324060109985,114.04117114841938);
var marker;
var map;
function initialize() {
	var mapOptions = {
		zoom: 10,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		center: stockholm
	};

	map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
	marker = new google.maps.Marker({
		position: stockholm,
		map: map,
		title:"深圳仁爱医院"
	});
	marker.setMap(map);
}*/
/* 鼠标滚动禁用 */
function disabledMouseWheel() {
  if (document.addEventListener) {
    document.addEventListener('DOMMouseScroll', scrollFunc, false);
  }//W3C
  window.onmousewheel = document.onmousewheel = scrollFunc;//IE/Opera/Chrome
}
function scrollFunc(evt) {
  evt = evt || window.event;
    if(evt.preventDefault) {
    // Firefox
      evt.preventDefault();
      evt.stopPropagation();
    } else {
      // IE
      evt.cancelBubble=true;
      evt.returnValue = false;
  }
  return false;
}
window.onload=disabledMouseWheel;

// 百度地图API功能
var pt = new BMap.Point(114.0477,22.526093);
var map = new BMap.Map("map_canvas");                      // 创建Map实例
map.centerAndZoom(pt, 18);
var index = 0;
var myGeo = new BMap.Geocoder();
/*var adds = [
    new BMap.Point(116.307852,40.057031),
    new BMap.Point(116.313082,40.047674),
    new BMap.Point(116.328749,40.026922),
    new BMap.Point(116.347571,39.988698),
    new BMap.Point(116.316163,39.997753),
    new BMap.Point(116.345867,39.998333),
    new BMap.Point(116.403472,39.999411),
    new BMap.Point(116.307901,40.05901)
];

for(var i = 0; i<adds.length; i++){
    var marker = new BMap.Marker(adds[i]);
    map.addOverlay(marker);
}
*/

function bdGEO(){
    var pt = adds[index];
    geocodeSearch(pt);
    index++;
}

var marker = new BMap.Marker(pt);  // 创建标注
map.addOverlay(marker);              // 将标注添加到地图中
var infoWindow = new BMap.InfoWindow("深圳仁爱医院");
marker.openInfoWindow(infoWindow);
marker.addEventListener("click", function(){this.openInfoWindow(infoWindow);});

map.addControl(new BMap.NavigationControl());               // 添加平移缩放控件
map.addControl(new BMap.ScaleControl());                    // 添加比例尺控件
map.addControl(new BMap.OverviewMapControl());              //添加缩略地图控件
map.enableScrollWheelZoom();                            //启用滚轮放大缩小
map.addControl(new BMap.MapTypeControl());          //添加地图类型控件
</script>
</body>
</html>