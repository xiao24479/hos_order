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

<link href="static/js/datepicker/css/datepicker.css" rel="stylesheet" />

<style type="text/css">

/*#main-content{margin-left:0px;}

#sidebar{margin-left:-180px; z-index:-1;}

.sidebar-scroll{z-index:-1;}*/

.date_div{ position:absolute; top:55px; left:412px; z-index:999;}

.anniu{ display:none;}

.o_a a{ padding:0 10px;}

.from_value{ width:85px; overflow:hidden; display:block;}
.order_form{ height:120px}

.list_table .blacklist td{
    background-color:#999;
}
</style>

<script src="static/js/jquery.js"></script>

<script language="javascript">

if($(window).width() <= 1200)

{

	window.location.href = '?c=order&m=order_list&type=mi';

}

</script>

</head>



<body class="fixed-top">

<?php echo $top; ?>

<div id="container" class="row-fluid"> 

<?php echo $sider_menu; ?>



<div id="main-content"> 

         <!-- BEGIN PAGE CONTAINER-->

         <div class="container-fluid" style="position:relative; padding-top:10px;"> 

<div class="order_count">

<span>总预约人数：<font><?php echo $order_count['count']; ?></font></span><span>来院人数：<font><?php echo $order_count['come']; //$order_count['dao'] . " / " . $order_count['come']; ?></font></span><span>未来院人数：<font><?php echo $order_count['wei']; ?></font></span>

<span class="o_a">

<a href="javascript:jsearch('null');" target="_blank">刷新</a>

<a href="<?php echo $down_url;?>" target="_blank">导出数据</a>

<a href="javascript:jsearch('p_jb=149');" target="_blank">四维</a>

</span>

</div>

<form action="" method="get" class="date_form order_form" id="order_list_form">

<input type="hidden" value="order" name="c" />

<input type="hidden" value="order_swt" name="m" />

<div class="span5">

    <div class="row-form">

		<select name="t" style="width:110px;">

			<?php foreach($this->lang->line('order_type') as $key=>$val):?><option value="<?php echo $key; ?>" <?php if($key == $t){echo " selected";}?>><?php echo $val; ?></option><?php endforeach;?>

		</select>

		<input type="text" value="<?php echo $start; ?> - <?php echo $end; ?>" style="width:270px;" class="input-block-level" name="date" id="inputDate" />

    </div>

    <div class="date_div">

    <div class="divdate"></div>

    <div class="anniu"><a href="javascript:;" class="btn btn-inverse guanbi"> 关闭 </a><br /><a href="javascript:;" class="btn btn-inverse today"> 今天 </a><br /><a href="javascript:;" class="btn btn-inverse week"> 一周 </a><br /><a href="javascript:;" class="btn btn-inverse month"> 一月 </a><br /><a href="javascript:;" class="btn btn-inverse year"> 一年 </a></div>

    </div>

    

	<div class="row-form">

		<label class="select_label">来源网址</label>

		<input type="text" value="<?php echo $swt_url; ?>" class="input-medium" name="swt_url" style="width:300px;"  />

	</div>

    <div class="row-form">

		<label class="select_label"><?php echo $this->lang->line('order_keshi');?></label>

		<select name="hos_id" id="hos_id" style="width:180px;">

			<option value=""><?php echo $this->lang->line('hospital_select'); ?></option>

			<?php foreach($hospital as $val):?><option value="<?php echo $val['hos_id']; ?>" <?php if($val['hos_id'] == $hos_id){echo " selected";}?>><?php echo $val['hos_name']; ?></option><?php endforeach;?>

		</select>

		<select name="keshi_id" id="keshi_id" style="width:130px;">

			<option value=""><?php echo $this->lang->line('keshi_select'); ?></option>

		</select>

	</div>

</div>

<div class="span3">

	<div class="row-form">

		<label class="select_label">患者姓名</label>

		<input type="text" value="<?php echo $p_n; ?>" class="input-medium" name="p_n"  />

	</div>

	<div class="row-form">

		<label class="select_label">关键词</label>

		<input type="text" value="<?php echo $swt_keyword; ?>" class="input-medium" name="swt_keyword"  />

	</div>

	<div class="row-form">

		<label class="select_label">大病种</label>

		<select name="p_jb" id="p_jb" style="width:165px;">

			<option value=""><?php echo $this->lang->line('jb_parent_select'); ?></option>

			<?php foreach($jibing_parent as $key=>$val):?><option value="<?php echo $val['jb_id']; ?>" <?php if($val['jb_id'] == $p_jb){ echo "selected";}?>><?php echo $val['jb_name']; ?></option><?php endforeach;?>

		</select>

	</div>

</div>	

<div class="span3">

	<div class="row-form">

		<label class="select_label"><?php echo $this->lang->line('patient_phone');?></label>

		<input type="text" value="<?php echo $p_p; ?>" class="input-medium" name="p_p"  />

	</div>

	

	<div class="row-form">

		<label class="select_label">轨迹来源</label>

		<select name="swt_type" style="width:165px;">

			<option value>请选择</option>
            
            <option value="竞价"<?php if($swt_type == '竞价'){ echo " selected";}?>>竞价</option>

            <option value="优化"<?php if($swt_type == '优化'){ echo " selected";}?>>优化</option>

            <option value="无轨迹"<?php if($swt_type == '无轨迹'){ echo " selected";}?>>无轨迹</option>

            <option value="找不到"<?php if($swt_type == '找不到'){ echo " selected";}?>>找不到</option>
	    <option value="未标注"<?php if($swt_type == '未标注'){ echo " selected";}?>>未标注</option>
		</select>

	</div>

	<div class="row-form">

		<label class="select_label">小病种</label>

		<select name="jb" id="jb" style="width:165px;">

			<option value=""><?php echo $this->lang->line('jb_child_select'); ?></option>

		</select>

	</div>

</div>

<div class="span3">

	<div class="row-form">

		<label class="select_label"><?php echo $this->lang->line('order_no');?></label>

		<input type="text" value="<?php echo $o_o; ?>" class="input-medium" name="o_o"  />

	</div>

    <div class="row-form">

		<label class="select_label"><?php echo $this->lang->line('status');?></label>

		<select name="s" id="status" style="width:165px;">

			<option value=""><?php echo $this->lang->line('please_select'); ?></option>

			<?php foreach($this->lang->line('order_status') as $key=>$val):?><option value="<?php echo $key; ?>" <?php if($key == $s){echo " selected";}?>><?php echo $val; ?></option><?php endforeach;?>

		</select>

	</div>

    <div class="row-form">

		<label class="select_label"><?php echo $this->lang->line('pager');?></label>

		<select name="p" style="width:165px;">

			<option value=""><?php echo $this->lang->line('please_select'); ?></option>

			<?php foreach($this->lang->line('page_no') as $key=>$val):?><option value="<?php echo $key; ?>" <?php if($key == $per_page){ echo "selected";}?>><?php echo $val; ?></option><?php endforeach;?>

		</select>

	</div>

</div>

<div class="order_btn">

    <button type="submit" class="btn btn-success"> 搜索 </button> <!--<a href="javascript:show(this);" id="gaoji" class="btn btn-default"> 高级 </a>-->

</div>

</form>

	  <div class="row-fluid">

		<div class="span12">

<table width="100%" border="0" cellspacing="0" cellpadding="2" class="list_table">

  <thead>

  <tr>

	<th width="30">#</th>

    <th width="40"><?php echo $this->lang->line('order_no'); ?></th>

	<th width="150">预约日期</th>

	<th width="150">来院日期</th>

	<th width="80">患者姓名</th>

	<th width="80">联系电话</th>

	<th width="40">代码</th>

    <th width="100">病种</th>

	<th width="60">途径</th>

    <th width="120">关键词</th>

	<th width="80">轨迹来源</th>

    <th>网址</th>

  </tr>

  </thead>

  <tbody>

  <?php

  $i = 0;

  foreach($order_list as $item):

  ?>

  <tr class="<?php if($i % 2){ echo 'td1';}?> <?php if($item['pat_blacklist']==1){echo 'blacklist';}?>">

    <td><b><?php echo $now_page + $i + 1; ?></b></td>

    <td><?php echo $item['order_no']; ?></td>

    <td><?php echo $item['order_time']; ?> <font style="color:#F00; font-weight:bold;"><?php if($item['order_time_duan']){ echo $item['order_time_duan'];}?></font></td>

    <td><span id="come_time_<?php echo $item['order_id']; ?>"><?php if($item['come_time'] > 0){ echo date("Y-m-d H:i", $item['come_time']);}?></span></td>

    <td><?php echo $item['pat_name']?></td>

    <td><?php echo $item['pat_phone']; if(!empty($item['pat_phone1'])){echo "/" . $item['pat_phone1'];}?></td>

    <td><?php echo isset($jibing[$item["jb_parent_id"]])? $jibing[$item["jb_parent_id"]]['jb_code']:''; ?></td>

    <td><?php if(isset($jibing[$item['jb_parent_id']])){echo $jibing[$item['jb_parent_id']]['jb_name'];}?></td>

    <td><?php if(isset($from_list[$item['from_parent_id']])){  echo $from_list[$item['from_parent_id']]['from_name']; }?></td>

    <td><input type="text" value="<?php echo $item['swt_keyword']?>" class="input-medium" name="swt_keyword[<?php echo $item['order_id']; ?>]" data="<?php echo $item['order_id']; ?>" style="margin:0;"/></td>

    <td>

        <select name="swt_type[<?php echo $item['order_id']; ?>]" data="<?php echo $item['order_id']; ?>" style="width:100px; margin:0;">

            <option value="">请选择</option>

            <option value="竞价"<?php if($item['swt_type'] == '竞价'){ echo " selected";}?>>竞价</option>

            <option value="优化"<?php if($item['swt_type'] == '优化'){ echo " selected";}?>>优化</option>

            <option value="无轨迹"<?php if($item['swt_type'] == '无轨迹'){ echo " selected";}?>>无轨迹</option>

            <option value="找不到"<?php if($item['swt_type'] == '找不到'){ echo " selected";}?>>找不到</option>

        </select>

    </td>

    <td align="left"><input type="text" value="<?php echo $item['swt_url']?>" class="input-xlarge" data="<?php echo $item['order_id']; ?>" name="swt_url[<?php echo $item['order_id']; ?>]" style="margin:0; float:left; width:400px;"/></td>

  </tr>

  <?php 

  $i ++;

  endforeach; ?>

  </tbody>

  </table>

<?php echo $page; ?>

<p>&nbsp;</p>

<p>&nbsp;</p>

</div>

</div>

</div>

</div>

</div>

</div>

   <script src="static/js/jquery.nicescroll.js" type="text/javascript"></script>

   <script type="text/javascript" src="static/js/jquery.slimscroll.min.js"></script>

   <script src="static/js/bootstrap.min.js"></script>

   <script type="text/javascript" src="static/js/bootstrap-datepicker.js"></script>

   <!-- ie8 fixes -->

   <!--[if lt IE 9]>

   <script src="static/js/excanvas.js"></script>

   <script src="static/js/respond.js"></script>

   <![endif]-->

   <script src="static/js/common-scripts.js"></script>

   <script type="text/javascript" src="static/js/date.js"></script>

   <script type="text/javascript" src="static/js/daterangepicker.js"></script>

   <script src="static/js/datepicker/js/datepicker.js"></script>



<script language="javascript">

$(document).ready(function(e) {

	$(".list_table tr").hover(

	  function(){

	    $(this).addClass("over_list");

	  },

	  function(){

	    $(this).removeClass("over_list");

	  }

	);

	

	$(".remark").hover(

	  function(){

	      $(this).css({height:"auto", background:"#dddddd", "z-index":"999", "padding-bottom":"50px"});

	  },

	  function(){

	     $(this).css({height:"70px", background:"none", "z-index":"1", "padding-bottom":"10px"});

	  }

	);

	

//	$('#sidebar > ul').hide();
//
//	$("#container").addClass("sidebar-closed");

	

	$(".anniu").css("display", "block");

	$('.divdate').DatePicker({

		flat: true,

		date: ['<?php echo $start_date; ?>','<?php echo $end_date; ?>'],

		current: '<?php echo $end_date; ?>',

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

		$('.date_div').css("display", "block");

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

	$("#hos_id").change(function(){

		var hos_id = $(this).val();

		ajax_get_keshi(hos_id, 0);

	});

	

	$("#keshi_id").change(function(){

		var keshi_id = $(this).val();

	});

	

	$("#from_parent_id").change(function(){

		var parent_id = $(this).val();

		ajax_from(parent_id, 0);

	});

	

	$("#p_jb").change(function(){

		var parent_id = $(this).val();

		ajax_get_jibing(0, parent_id, 0);

	});

	

	/* AJAX 处理轨迹 */

	$("input[name^='swt_keyword']").focusout(function(){

		var order_id = $(this).attr("data");

		ajax_guiji(order_id, 'swt_keyword', $(this).val());

	});

	$("input[name^='swt_url']").focusout(function(){

		var order_id = $(this).attr("data");

		ajax_guiji(order_id, 'swt_url', $(this).val());

	});

	$("select[name^='swt_type']").change(function(){

		var order_id = $(this).attr("data");

		ajax_guiji(order_id, 'swt_type', $(this).val());

	});	



<?php if($hos_id > 0):?>

ajax_get_keshi(<?php echo $hos_id?>, <?php echo $keshi_id?>);

<?php endif;?>

<?php if($f_p_i > 0):?>

ajax_from(<?php echo $f_p_i?>, <?php echo $f_i?>);

<?php endif;?>

});



<?php if($p_jb > 0):?>

ajax_get_jibing(0, <?php echo $p_jb?>, <?php echo $jb?>);

<?php endif;?>



function ajax_guiji(order_id, type, value)

{

	$.ajax({

		type:'post',

		url:'?c=order&m=order_swt_update',

		data:'order_id=' + order_id + '&type=' + type + '&value=' + value,

		success:function(data)

		{



		},

		complete: function (XHR, TS)

		{

		   XHR = null;

		}

	});

}





function jsearch(name)

{

	if(name == "null")

	{

		window.location.href = "http://www.renaidata.com/?c=order&m=order_list";

	}

	else

	{

		window.location.href = window.location.href + '&' + name;

	}

}



function ajax_get_jibing(keshi_id, parent_id, check_id)

{

	$("#p_jb").after("<i class='icon-refresh icon-spin'></i>");

	$.ajax({

		type:'post',

		url:'?c=order&m=jibing_ajax',

		data:'keshi_id=' + keshi_id + '&parent_id=' + parent_id + '&check_id=' + check_id,

		success:function(data)

		{

			if(parent_id == 0)

			{

				$("#jb").html(data);

			}

			else

			{

				$("#jb").html(data);

			}

			

		},

		complete: function (XHR, TS)

		{

		   XHR = null;

		   $("#p_jb").next(".icon-spin").remove();

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



function doHandleMonth(month){  

       var m = month;  

       if(month.toString().length == 1){  

          m = "0" + month;  

       }  

       return m;  

}

function ajax_from(parent_id, from_id)

{

	$.ajax({

		type:'post',

		url:'?c=order&m=from_order_ajax',

		data:'parent_id=' + parent_id + '&from_id=' + from_id,

		success:function(data)

		{

		   $("#from_id").html(data);

		},

		complete: function (XHR, TS)

		{

		   XHR = null;

		}

	});	

}



function ajax_get_keshi(hos_id, check_id)

{

	$.ajax({

		type:'post',

		url:'?c=order&m=keshi_list_ajax',

		data:'hos_id=' + hos_id + '&check_id=' + check_id,

		success:function(data)

		{

			$("#keshi_id").html(data);

		},

		complete: function (XHR, TS)

		{

		   XHR = null;

		}

	});

}



function show(obj)

{

	var h = $(".order_form").height();

	if(h == 120)

	{

		$(".order_form").height(38);

		$("#gaoji").html(" 高级 ");

	}

	else

	{

		$(".order_form").height(120);

		$("#gaoji").html(" 简单 ");

	}

}

</script>

</body>

</html>