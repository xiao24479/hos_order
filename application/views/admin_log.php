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
<link href="static/js/datepicker/css/datepicker.css" rel="stylesheet" />
<style type="text/css">
    .date_div{ position:absolute; top:170px; left:400px; z-index:1000;}

   .anniu{ display:none; }
</style>
</head>

<body class="fixed-top">
   <?php echo $top; ?>
   <div id="container" class="row-fluid">
   <?php echo $sider_menu; ?>
   <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <?php echo $themes_color_select; ?>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
              <div class="row-fluid">
			    <div class="span12">
				  <div class="widget orange">
                                      <div class="form-inline"><form action="?c=index&m=admin_log" method="post">
                                              <div  style="font-size: 14px;color:#00a186;margin-top:20px;margin-left:25px;height:30px;">
   
                                                <font style="line-height:30px;">日期：</font><input type="text" value="<?=$start?>-<?=$end?> " style="width:240px;" name="date" id="inputDate" /></span> 
                                                 <font style="line-height:30px;">用户名：</font>
                                                  <input type="text"  id="" name="admin_name" placeholder="输入需要查找的姓名">
                                                  <input type="submit" class="btn btn-primary" value="提交" />
                                                  <a class="btn btn-primary" id="ip_submit" style="margin-left:30px;" onclick="get_ip_ajax();">ip地域查找</a> <input type="text"  id="ip" name="ip" placeholder="输入需要查找的ip">
  </div></form></div>   
                                   
                                      <!--这个是触发时间div,代码开始-->
                            <div class="date_div">

                            <div class="divdate"></div>

                            <div class="anniu"><a href="javascript:;" class="btn btn-primary guanbi"> 关闭 </a><br /><a href="javascript:;" class="btn btn-primary today"> 今天 </a><br /><a href="javascript:;" class="btn btn-primary week"> 一周 </a><br /><a href="javascript:;" class="btn btn-primary month"> 一月 </a><br /><a href="javascript:;" class="btn btn-primary year"> 一年 </a></div>

                            </div> <!--这个是触发时间div,代码结束-->
                            
                            <div class="widget-body">
			  <table width="100%" border="0" cellspacing="0" cellpadding="2" class="table table-hover">
  <thead>
  <tr>
    <th width="50">序号</th>
    <th width="100"><?php echo $this->lang->line('name'); ?></th>
    <th width="200">岗位</th>
	<th width="100">操作</th>
	<th width="150">IP</th> 
    <th width="200">时间</th>
	<th>内容</th>
  </tr>
  </thead>
  <tbody>
  <?php
  $i = 1;
  foreach($log as $item):
  ?>
  <tr  <?php if(($i+1)%2==0){echo "style='background-color:#fff'";}?>>
    <td><?php echo $i; ?></td>
    <td><?php echo $item['admin_name']?></td>
    <td><font style="color:#00a186;font-size:14px;"><?php echo $rank[$item['admin_name']]?></font></td>
	<td><?php echo $this->lang->line($item['log_action']); ?></td>
	<td><?php echo $item['log_ip']?></td> 
    <td><?php echo $item['log_time']?></td>
	<td><?php print_r($item['log_data'][0]);?></td>
  </tr>
  <?php 
  $i ++;
  endforeach; ?>
  </tbody>
  </table>
<?php echo $page; ?>  
			  </div>
			  </div>
			  </div>
            </div>
            <!-- END PAGE CONTENT-->
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
</div>
   <script src="static/js/jquery.js"></script>
   <script src="static/js/jquery.nicescroll.js" type="text/javascript"></script>
   <script type="text/javascript" src="static/js/jquery.slimscroll.min.js"></script>
   <script src="static/js/bootstrap.min.js"></script>
    <script src="static/js/datepicker/js/datepicker.js"></script>
   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="static/js/excanvas.js"></script>
   <script src="static/js/respond.js"></script>
   <![endif]-->
   <script src="static/js/common-scripts.js"></script>
   <script type="text/javascript">
   $(document).ready(function(){
       
    //IP地址的查询ajax
    
    
       
       
       
  //日期选择     
$(".anniu").css("display", "block");
  $(".divdate").DatePicker({
              
		flat: true,

		date: ['<?php echo $start;?>','<?php echo $end;?>'],

		current: '<?php echo date('Y-m-d',time());?>',

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
                 $("#gaoji").hide();
		$('.date_div').css("display", "block");
                  $('.date_div .datepicker').css({"width":"420px",'height':'160px','background':'black'});
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



});

function get_ip_ajax(){
var ip=$("#ip").val();   
    
        $.ajax({

				type:'post',

				url:'?c=index&m=ip_get_ajax',
                                
				data:'ip='+ip,

				success:function(data)

				{ $(".ip_info").remove();
                                   $("#ip").after("<font class='ip_info'>"+data+"</font>");
				
				},

				complete: function (XHR, TS)

				{

				   XHR = null;

				}

			});
                


}
function get_day(day){  

       var today = new Date();  

       var targetday_milliseconds=today.getTime() + 1000*60*60*24*day;

       today.setTime(targetday_milliseconds); /* 注意，这行是关键代码 */   

       var tYear = today.getFullYear();  

       var tMonth = today.getMonth();  

       var tDate = today.getDate();  

       tMonth = doHandleMonth(tMonth + 1);  

       tDate = doHandleMonth(tDate);  

       return tYear + "年" + tMonth + "月" + tDate + "日";  

}
//操作日期的js在单月份之前加0函数
function doHandleMonth(month){  

       var m = month;  

       if(month.toString().length == 1){  

          m = "0" + month;  

       }  

       return m;  

}
   
   </script>
   
</body>
</html>