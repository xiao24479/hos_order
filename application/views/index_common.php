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
  <link rel="stylesheet" type="text/css" href="static/css/metro-gallery.css" media="screen" />
<link href="static/js/datepicker/css/datepicker.css" rel="stylesheet" />
<style>
.date_div {
	position:absolute;
    float: left;
    position: fixed;
    z-index: 99999;
	margin-left:5%;
    top: 205px;
}

.boxLoading {
	display: none;
	width: 50px;
	height: 50px;
	margin: auto;
	position: absolute;
	left: 0;
	right: 0;
	top: 100px;
	z-index: 99999;
}

.boxLoading:before {
content: '';
width: 50px;
height: 5px;
background: #000;
opacity: 0.1;
position: absolute;
top: 59px;
left: 0;
border-radius: 50%;
animation: box-loading-shadow 0.5s linear infinite;
}
.boxLoading:after {
content: '';
width: 50px;
height: 50px;
background: #06B8C0;
animation: box-loading-animate 0.5s linear infinite;
position: absolute;
top: 0;
left: 0;
border-radius: 3px;
}

@keyframes box-loading-animate {
17% {
  border-bottom-right-radius: 3px;
}
25% {
  transform: translateY(9px) rotate(22.5deg);
}
50% {
  transform: translateY(18px) scale(1, .9) rotate(45deg);
  border-bottom-right-radius: 40px;
}
75% {
  transform: translateY(9px) rotate(67.5deg);
}
100% {
  transform: translateY(0) rotate(90deg);
}
}

@keyframes box-loading-shadow {
0%, 100% {
  transform: scale(1, 1);
}
50% {
  transform: scale(1.2, 1);
}
}
</style>

</head>
<body class="fixed-top" style="background-color: #eeeeee;">
   <?php echo $top; ?>
   <!-- END HEADER -->
   <!-- BEGIN CONTAINER -->
   <div id="container" class="row-fluid" >
      <!-- BEGIN SIDEBAR -->
      <?php echo $sider_menu; ?>
      <!-- END SIDEBAR -->
      <!-- BEGIN PAGE -->
      <div id="main-content" style="background-color: #dcdcdc;">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->
            <?php echo $themes_color_select; ?>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->

            <div id="page-wraper">
              	<div class="row-fluid">
	              	<div class="metro-nav">
	                  	<div style="margin-bottom: -10px;margin-top:30px;">

		                    <form action="?c=index&m=index_<?php echo $type; ?>" method="post">
		                        <span style="line-height:36px; display:block;float:left;font-size:16px;">所选医院：</span>
		                        <div style="margin-left:50px; padding-top:3px;">
			                        <SELECT style="width:150" name="hos_id" id="hos_id">
			                        	<OPTION value="" >请选择···</OPTION>
										<?php foreach ($hospital as $hos): ?>
											<option value="<?php echo $hos['hos_id']; ?>" <?php if($hos['hos_id'] == $hos_id): ?> selected <?php endif; ?> ><?php echo $hos['hos_name']; ?></option>
										<?php endforeach; ?>
			                        </SELECT>

			                        <input type="submit" value="提交" class="btn search_from" style="margin-bottom:5px;background-color:#00a186;color:#fff;margin-left: 5px;"/>
		                        </div>
		                        <span style="line-height:36px; display:block;float:left;font-size:16px;">搜索时间：</span>

		                      	<?php if(!empty($date)){?>
		                    		<input id="inputDate" class="input-block-level" value="<?php echo $date; ?>" style="width:240px;vertical-align:middle;height:16px;font-size:12px; " name="date" type="text">
		                    	<?php  }else{?>
		                    		<input id="inputDate" class="input-block-level" value="" style="width:240px;vertical-align:middle;height:16px;font-size:12px; " name="date" type="text">
		                    	<?php }?>
		                    </form>

	                    	<div class="date_div"><div class="divdate"></div><div class="anniu"><a href="javascript:;" class="btn btn-inverse guanbi"> 关闭 </a><br /><a href="javascript:;" class="btn btn-inverse today"> 今天 </a><br /><a href="javascript:;" class="btn btn-inverse week"> 一周 </a><br /><a href="javascript:;" class="btn btn-inverse month"> 一月 </a><br /><a href="javascript:;" class="btn btn-inverse year"> 一年 </a></div></div>
			        	</div>

			        	<?php if(isset($search_time_get_html)):?>
			                <?php echo  $search_time_get_html; ?>
			            <?php else: ?>

						<div class="metro-nav-block nav-block-yellow" style="background-color: #00a187;width:13%;">
                            <a href="?c=order&m=order_list_liulian&t=1#今天留联" >
                                <i class="icon-hospital"></i>
                                <div class="info"><?php echo $today_ll_count; ?></div>
                                <div class="status">今天留联</div>
                            </a>
                        </div>
                        <div class="metro-nav-block nav-block-yellow" style="background-color: #00a187;width:13%;">
                            <a href="?c=order&m=order_list&t=1#今天预约" >
                                <i class="icon-hospital"></i>
                                <div class="info"><?php echo $today_order_add_count; ?></div>
                                <div class="status">今天预约</div>
                            </a>
                        </div>
                        <div class="metro-nav-block nav-block-yellow" style="background-color: #00a187;width:13%;">
                            <a href="?c=order&m=order_list&t=2#今天预到" >
                                <i class="icon-hospital"></i>
                                <div class="info"><?php echo $today_order_count; ?></div>
                                <div class="status">今天预到</div>
                            </a>
                        </div>
                        <div class="metro-nav-block nav-block-yellow" style="background-color: #00a187;width:13%;">
                            <a href="?c=order&m=order_list&t=3#今天来院" >
                                <i class="icon-user-md"></i>
                                <div class="info"><?php echo $today_come_count; ?></div>
                                <div class="status">今天来院</div>
                            </a>
                        </div>
                        <div class="metro-nav-block nav-block-yellow" style="background-color: #00a187;width:13%;">
                            <a href="javaScript:void(0);">
                                <i class="icon-hospital"></i>
                                <div class="info"><?php echo $today_fz_count; ?></div>
                                <div class="status">今天复诊</div>
                            </a>
                        </div>
                         <div class="metro-nav-block nav-block-green" style="background-color: #fe9b00;width:13%;">
                            <a href="?c=order&m=order_list&t=1#昨日预约" >
                                <i class="icon-hospital"></i>
                                <div class="info"><?php echo $yes_order_add_count; ?></div>
                                <div class="status">昨日预约</div>
                            </a>
                        </div>
                        <div class="metro-nav-block nav-block-green" style="background-color: skyblue;width:13%;">
                            <a href="?c=order&m=order_list&t=2#明日预到" >
                                <i class="icon-hospital"></i>
                                <div class="info"><?php echo $tomo_order_count; ?></div>
                                <div class="status">明日预到</div>
                            </a>
                        </div>

                        <div class="metro-nav-block nav-block-red" style="background-color: #da542e;width:13%;">
                            <a href="?c=order&m=order_list_liulian&t=1#昨日留联" >
                                <i class="icon-hospital"></i>
                                <div class="info"><?php echo $yesterday_ll_count; ?></div>
                                <div class="status">昨日留联</div>
                            </a>
                        </div>

                         <div class="metro-nav-block nav-block-red" style="background-color: #da542e;width:13%;">
                            <a href="?c=order&m=order_list_liulian&t=1#本月预约">
                                <i class="icon-hospital"></i>
                                <div class="info"><?php echo $month_ll_count; ?></div>
                                <div class="status">本月留联</div>
                            </a>
                        </div>
                        <div class="metro-nav-block nav-block-red" style="background-color: #da542e;width:13%;">
                            <a href="?c=order&m=order_list&t=1#本月预约">
                                <i class="icon-hospital"></i>
                                <div class="info"><?php echo $month_order_add_count; ?></div>
                                <div class="status">本月预约</div>
                            </a>
                        </div>

                         <div class="metro-nav-block nav-block-red" style="background-color: #da542e;width:13%;">
                            <a href="?c=gonghai&m=gonghai&t=1#本月来院">
                                <i class="icon-hospital"></i>
                                <div class="info"><?php echo $month_gh_count; ?></div>
                                <div class="status">本月公海</div>
                            </a>
                        </div>

                        <div class="metro-nav-block nav-block-orange" style="background-color: #da542e;width:13%;">
                            <a href="?c=order&m=order_list&t=3#本月来院">
                                <i class="icon-user-md"></i>
                                <div class="info"><?php echo $month_come_count; ?></div>
                                <div class="status">本月来院</div>
                            </a>
                        </div>

                         <div class="metro-nav-block nav-block-red" style="background-color: #da542e;width:13%;">
                            <a href="javaScript:void(0);">
                                <i class="icon-hospital"></i>
                                <div class="info"><?php echo $month_fz_count; ?></div>
                                <div class="status">本月复诊</div>
                            </a>
                        </div>
                        <div class="metro-nav-block nav-block-green" style="background-color: #fe9b00;width:13%;">
                            <a href="?c=order&m=order_list&t=3#昨日来院" >
                                <i class="icon-user-md"></i>
                                <div class="info"><?php echo $yes_come_count; ?></div>
                                <div class="status">昨日来院</div>
                            </a>
                        </div>
                    <?php endif; ?>
	                </div>
              	</div>

                <div class="row-fluid" style="margin-left: 0px;" id="month_data"></div>
			  	<div class="row-fluid">
                  	<div class="">
	                    <!-- BEGIN NOTIFICATIONS PORTLET-->
	                    <div class="widget purple" style="width:100%;border: 1px solid #e7e7e7;float:left">
	                        <div class="widget-title" style="background-color:#00a186;">
	                            <h4><i class="icon-hospital"></i>每日数据 </h4>
	                           	<span class="tools">
	                               <a href="javascript:;" class="icon-chevron-down"></a>
	                               <a href="javascript:;" class="icon-remove"></a>
	                            </span>
	                        </div>
	                        <div class="widget-body">
	                        	<div style="height:auto; width:100%; display:block;">

							<table class="table table-striped">

								<tr >
								 	<th>日期</th>
								  	<th>预约</th>
								  	<th>预到</th>
								  	<th>到诊</th>
								  	<th>预到到诊率</th>

								</tr>
								<?php foreach($order as $key=>$val):?>
									<tr align=center>
									  	<td>
									  		<?php echo date("y-m-d", $val['time']);if(date("w", $val['time']) == 6 || date("w", $val['time']) == 0){echo "*";}?>
									  	</td>
										<td><?php echo $val['add'];?></td>
										<td><?php echo $val['order'];?></td>
										<td><?php echo $val['come'];?></td>
									  	<td>
									  		<?php if($val['order']==0){echo '0%';}else{echo number_format((($val['come'] / $val['order']) * 100), 2, '.', '').'%';}?>
									  	</td>
									</tr>
								<?php endforeach;?>
							</table>
						 </div></div>
                     </div>
                     <!-- END NOTIFICATIONS PORTLET-->

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

   <script type="text/javascript" src="static/js/date.js"></script>
   <script type="text/javascript" src="static/js/daterangepicker.js"></script>
   <script src="static/js/datepicker/js/datepicker.js"></script>

   <script src="static/js/common-scripts.js"></script>
   <script src="static/js/c/esl.js"></script>
   <script language="javascript" type="text/javascript">

	$(document).ready(function() {
	    $(".anniu").css("display", "block");

	    $('.divdate').DatePicker({

	        flat: true,

	        date: ['<?php echo $start_date; ?>', '<?php echo $end_date; ?>'],

	        current: '<?php echo $end_date; ?>',

	        format: 'Y年m月d日',

	        calendars: 2,

	        mode: 'range',

	        starts: 1,

	        onChange: function(formated) {

	            $('#inputDate').val(formated.join(' - '));

	        }

	    });

	    $("#nextdate").focus(function() {

	        $('.date_lx').css("display", "block");

	        $('.date_lx .datepicker').css({
	            "width": "210px",
	            'height': '160px',
	            'background': 'black'
	        });

	    });

	    $('.date_div').css("display", "none");

	    $(".anniu .guanbi").click(function() {

	        $('.date_div').css("display", "none");

	    });

	    $("#inputDate").focus(function() {
	        $("#gaoji").hide();
	        $('.date_div').css("display", "block");
	        $('.date_div .datepicker').css({
	            "width": "420px",
	            'height': '160px',
	            'background': 'black'
	        });
	    });

	    $(".anniu .today").click(function() {

	        $('#inputDate').val(get_day(0) + " - " + get_day(0));

	        $('.date_div').css("display", "none");

	    });

	    $(".anniu .week").click(function() {

	        $('#inputDate').val(get_day( - 6) + " - " + get_day(0));

	        $('.date_div').css("display", "none");

	    });

	    $(".anniu .month").click(function() {

	        $('#inputDate').val(get_day( - 29) + " - " + get_day(0));

	        $('.date_div').css("display", "none");

	    });

	    $(".anniu .year").click(function() {

	        $('#inputDate').val(get_day( - 364) + " - " + get_day(0));

	        $('.date_div').css("display", "none");

	    });

	    function get_day(day) {

	        var today = new Date();

	        var targetday_milliseconds = today.getTime() + 1000 * 60 * 60 * 24 * day;

	        today.setTime(targetday_milliseconds);
	        /* 注意，这行是关键代码 */

	        var tYear = today.getFullYear();

	        var tMonth = today.getMonth();

	        var tDate = today.getDate();

	        tMonth = doHandleMonth(tMonth + 1);

	        tDate = doHandleMonth(tDate);

	        return tYear + "年" + tMonth + "月" + tDate + "日";

	    }

	    function doHandleMonth(month) {

	        var m = month;

	        if (month.toString().length == 1) {

	            m = "0" + month;

	        }
	        return m;
	    }
	    ajax_get_month('<?php echo $hos_id; ?>', '<?php echo $type; ?>');
	});


    function ajax_get_month(hos_id,type){

        $.ajax({
			type:'post',
			url:'?c=index&m=get_month_ajax',
			data:'hos_id='+hos_id+'&type='+type+'&date='+$("#inputDate").val(),
			success:function(data)
			{
				$("#month_data").html(data);
				$("#get_month_data_table tr").find("td").each(function(){
					if($(this).html() == 0 && $(this).html() != ''){
					 	$(this).html("<span style='color:red'>0</span>");
					}
				});

			}
		});
    }

   </script>

</body>
</html>