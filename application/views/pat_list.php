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
<link href="static/js/datepicker/css/datepicker.css" rel="stylesheet" />
<style>
.metro-nav .metro-nav-block{ width:15.8%;}
.date_div{ position:absolute; top:inherit; left:600px; z-index:999;}

.anniu{ display:none;}
</style>
</head>
<body class="fixed-top">
   <?php echo $top; ?>
   <!-- END HEADER -->
   <!-- BEGIN CONTAINER -->
   <div id="container" class="row-fluid">
      <!-- BEGIN SIDEBAR -->
      <?php echo $sider_menu; ?>
      <!-- END SIDEBAR -->
      <!-- BEGIN PAGE -->
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->
            <?php echo $themes_color_select; ?>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
<form action="" method="get" class="date_form order_form" id="order_list_form" style="height:auto;">

<input type="hidden" value="order" name="c" />

<input type="hidden" value="pat_list" name="m" />

<div class="span5">

    <div class="row-form">
		<label class="select_label">选择时间</label>

		<input type="text" value="<?php echo $start; ?> - <?php echo $end; ?>" style="width:270px;" class="input-block-level" name="date" id="inputDate" />

    </div>
	<div class="date_div">

    <div class="divdate"></div>

    <div class="anniu"><a href="javascript:;" class="btn btn-inverse guanbi"> 关闭 </a><br /><a href="javascript:;" class="btn btn-inverse today"> 今天 </a><br /><a href="javascript:;" class="btn btn-inverse week"> 一周 </a><br /><a href="javascript:;" class="btn btn-inverse month"> 一月 </a><br /><a href="javascript:;" class="btn btn-inverse year"> 一年 </a></div>

    </div>
	<div class="row-form">

		<label class="select_label">来源</label>


	   <input type="text" value="<?php if(isset($from_parent_id)){echo $from_parent_id;}?>" class="input-large" name="f_p_i" id="from_parent_id" />

	   
	  
	</div>
	<div class="row-form">

		<label class="select_label">来源渠道</label>


	  

	   
	   <input type="text" value="<?php if(isset($from_id)){echo $from_id;}?>" class="input-large" name="f_i" id="from_id" />

	</div>
	<div class="row-form">

		<label class="select_label"><?php echo $this->lang->line('order_keshi');?></label>


		<div class="controls">
			
			<select name="keshi_id" id="keshi_id">
			<option value="0"><?php echo $this->lang->line('please_select');?></option>
			<?php foreach($keshi as $val){ ?>
			<option value="<?php echo $val['keshi_id'];?>" <? if(isset($keshi_id) && $keshi_id == $val['keshi_id']){echo 'selected';}?>><?php echo $val['keshi_name'];?></option>
			<?php } ?>
			</select>
		</div>

	</div>
</div>

<div class="span4">
    <div class="row-form">

		<label class="select_label">患者姓名</label>


		<div class="controls">
			<input type="text" value="<?php if(isset($patient_name)){echo $patient_name;}?>" class="input-large" name="patient_name" id="patient_name" />
		</div>

	</div>
	 <div class="row-form">

		<label class="select_label">登记员</label>


		<div class="controls">
			<input type="text" value="<?php if(isset($admin_id)){echo $admin_id;}?>" class="input-large" name="admin_id" id="admin_id" />
		</div>

	</div>
	<div class="row-form">

		<label class="select_label">病种</label>
		<div class="controls">
			<input type="text" value="<?php if(isset($p_jb)){echo $p_jb;}?>" class="input-large" name="p_jb" id="p_jb" />
			
		</div>

	</div>

</div>

<div class="span2">
    <div class="row-form">

		<label class="select_label">患者电话</label>


		<div class="controls">
			<input type="text" value="<?php if(isset($patient_phone)){echo $patient_phone;}?>" class="input-large" name="patient_phone" id="patient_phone" style="width:88px;" />
		</div>

	</div>
	 <div class="row-form">

		<label class="select_label">接诊医生</label>


		<div class="controls">
			<input type="text" value="<?php if(isset($doctor_name)){echo $doctor_name;}?>" class="input-large" name="doctor_name" id="doctor_name" style="width:88px;" />
		</div>

	</div>
	<div class="row-form">

		<label class="select_label">预约号</label>
		<div class="controls">
			<input type="text" value="<?php if(isset($order_no)){echo $order_no;}?>" class="input-large" name="order_no" id="order_no" style="width:88px;"/>
		</div>

	</div>

</div>		




<div class="order_btn">

    <button type="submit" class="btn btn-success"> 搜索 </button> 
	<!--<button type="reset" class="btn"> <?php echo $this->lang->line('reset'); ?> </button>-->
</div>

</form>
<table width="100%" border="0px" cellspacing="0" cellpadding="2" class="list_table">

  <thead>

  <tr>

	<th width="40">序号</th>

    <th width="50"><?php echo $this->lang->line('order_no'); ?></th>

	<th width="150">时间</th>

	<th width="80">姓名</th>

	<th width="80">年龄</th>

	<th width="70"><?php echo $this->lang->line('order_keshi'); ?></th>

	<th width="70"><?php echo $this->lang->line('patient_jibing'); ?></th>

    <th width="80">医生</th>

	<th width="100">电话</th>


	<th width="100" >来源</th>
	<th width="80" >登记员</th>
	<th width="200" >备注</th>
	<th width="80" >操作</th>

  </tr>

  </thead>
    <?php

  $i = 0;

  foreach($pat_list as $item):

  ?>
  <tr<?php if($i % 2){ echo " class='td1'";}?> style="height:90px;">
	<td><b><?php echo $now_page + $i + 1; ?></b></td>
	<td><b><?php echo $item['order_no']; ?></b></td>
	<td><b><?php echo date('Y-m-d H:i:s',$item['add_time']); ?></b></td>
	<td><b><?php echo $item['pat_name']; ?></b></td>
	<td><b><?php echo $item['pat_age']; ?></b></td>
	<td><b><?php echo $keshi_list[$item['keshi_id']]; ?></b></td>
	<td><b><?php echo $item['jb_parent_id'];?></b></td>
	<td><b><?php echo $item['doctor_name']; ?></b></td>
	<td><b><?php echo $item['pat_phone']; ?></b></td>
	<td><b><?php echo $item['from_parent_id']; ?><br/><?php if($item['from_id']){echo $item['from_id'];} ?></b></td>
	<td><b><?php echo $item['admin_id']; ?></b></td>
	<td><b><?php echo $item['remark']; ?></b></td>
	<td>
		

		<a href="?c=order&m=pat_order&pat_id=<?php echo $item['enter_id']; ?>" role="button" class="btn btn-danger" data-toggle="modal"><?php echo $this->lang->line('edit'); ?></a>

		
	</td>
  </tr>
  <tbody>
   <?php 

  $i ++;

  endforeach; ?>
  </tbody>
</table>
<?php echo $page; ?>
          

            <!-- END PAGE CONTENT-->
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
      <!-- END PAGE -->
   </div>
   <script src="static/js/jquery.js"></script>
   <script src="static/js/jquery-1.8.3.min.js"></script>
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
   
   <script src="static/js/common-scripts.js"></script>
	<script type="text/javascript" src="static/js/date.js"></script>
	<script type="text/javascript" src="static/js/daterangepicker.js"></script>
	<script src="static/js/datepicker/js/datepicker.js"></script>
	<script language="javascript">
$(document).ready(function(e) {




	
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
	$("#inputDate").focus(function(){
	
		$('.date_div').css("display", "block");
	});
	
	$(".anniu .guanbi").click(function(){
		$('.date_div').css("display", "none");

	});
	
	$(".anniu .today").click(function(){
		

	

		$('.date_div').css("display", "none");

	});

	$(".anniu .week").click(function(){

	

		$('.date_div').css("display", "none");

	});

	$(".anniu .month").click(function(){



		$('.date_div').css("display", "none");

	});

	$(".anniu .year").click(function(){

	

		$('.date_div').css("display", "none");

	});
	
	$("#hos_id").change(function(){

		var hos_id = $(this).val();

		ajax_get_keshi(hos_id, 0);

	});
	
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

	
	$("#from_parent_id").change(function(){

		var parent_id = $(this).val();

		ajax_from(parent_id, 0);

	});
	
	
	
	function ajax_from(parent_id, from_id)

{

	$.ajax({

		type:'post',

		url:'?c=order&m=from_order_ajax',

		data:'parent_id=' + parent_id + '&from_id=' + from_id + '&tag=-1',

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
});
</script>
</body>
</html>