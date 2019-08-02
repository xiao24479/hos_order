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
<link rel="stylesheet" type="text/css" href="static/css/datepicker.css" />

<link rel="stylesheet" type="text/css" href="static/js/datetimepicker-master/jquery.datetimepicker.css"/>
<style type="text/css">
.custom-date-style {
	background-color: red !important;
} 
.input-wide{
	width: 500px;
}
</style>

<style>
.set{width:100px;}
.li{margin-top:10px;}
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
		<div class="span12" style="padding-bottom:150px;">
		  <div class="widget green">
<!--					<div class="widget-title">
					<h4><i class="icon-reorder"></i> <?php echo $this->lang->line('content_form'); ?></h4>
					<span class="tools">
						<a href="javascript:;" class="icon-chevron-down"></a>
						<a href="javascript:;" class="icon-remove"></a>
					</span>
					</div>-->
					<div class="widget-body">
<?php if(!empty($info)): ?>
  <form action="?c=system&m=baby_update"   method="post" class="form-horizontal" id="tab">
<div class="control-group">
	<label class="control-label">请选择病种</label>
	<div class="controls">
	    <?php echo $info['jb_name'];?>
	</div>
</div>
<div class="control-group">
	<label class="control-label">时间</label>
	<div class="controls">
		<?php echo $info['time_start'].' ~ '.$info['time_end'];?>
	</div>
</div> 
<div class="control-group">
	<label class="control-label">数量</label>
	<div class="controls">
		<input type="text" value="<?php echo $info['sum'];?>" class="input-xlarge" name="sum"  id="sum" />
	</div>
</div> 
  
<div class="control-group">
	<div class="controls">
		<input type="hidden" name="form_action" id="form_action" value="update" /> 
		<input type="hidden" name="id" value="<?php echo $info['id'] ?>"/>
		<button type="button"   class="btn btn-success"><i class="icon-ok"></i> <?php echo $this->lang->line('submit'); ?> </button>
		<button type="reset" class="btn"><i class="icon-remove"></i> <?php echo $this->lang->line('reset'); ?> </button>
	</div>
</div>
  </form>
  <?php else:?>
  <form action="?c=system&m=baby_update"   method="post" class="form-horizontal"  id="tab" >
<div class="control-group">
	<label class="control-label">请选择病种</label>
	<div class="controls">
		<select name="jb_id" id="jb_id" style="width:180px;"> 
			<option value="">请选择病种...</option> 
			<?php foreach($jb_list as $val):?> 
			<option value="<?php echo $val['jb_id'];?>" ><?php echo $val['jb_name']; ?></option>
			<?php endforeach;?>
		</select>
	</div>
</div>
 <div class="control-group">
	<label class="control-label">时间范围</label>
	<div class="controls">
	   <select name="time" id="time" style="width:180px;"></select>
	</div>
</div>

<div class="control-group">
	<label class="control-label">数量</label>
	<div class="controls">
		<input type="text" value="0" class="input-xlarge" name="sum" id="sum"/>
	</div>
</div>
 
<div class="control-group">
	<div class="controls">
		<input type="hidden" name="form_action" id="form_action" value="add" /> 
		<button type="button"  class="btn btn-success"><i class="icon-ok"></i> <?php echo $this->lang->line('submit'); ?> </button>
		<button type="reset" class="btn"><i class="icon-remove"></i> <?php echo $this->lang->line('reset'); ?> </button>
	</div>
</div>
  </form>
  <?php endif; ?>
  </div>
</div>
</div>
</div>
</div>
</div>
</div>
   <script src="static/js/jquery.js"></script>
    
   <script src="static/js/datetimepicker-master/build/jquery.datetimepicker.full.js"></script>

   <script src="static/js/jquery.nicescroll.js" type="text/javascript"></script>
   <script type="text/javascript" src="static/js/jquery.slimscroll.min.js"></script>
   <script src="static/js/bootstrap.min.js"></script> 
   
   
   
   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="static/js/excanvas.js"></script>
   <script src="static/js/respond.js"></script>
   <![endif]-->
   <script src="static/js/common-scripts.js"></script> 
   
   
   
<script>
$.datetimepicker.setLocale('ch');
var date=new Date;
var year=date.getFullYear(); 

$('#day').datetimepicker({
    lang:"ch", //语言选择中文 注：旧版本 新版方法：$.datetimepicker.setLocale('ch');
    format:"Y-m-d",      //格式化日期
    timepicker:false,    //关闭时间选项
    yearStart:year,     //设置最小年份
    yearEnd:year+1,        //设置最大年份
    todayButton:false    //关闭选择今天按钮
});


$("#day").change(function(){
	$msg = '';
	 if($("#jb_id").val() == '' || $("#jb_id").val() == null){
		   if($msg == ''){
			   $msg = '请选择病种';
		   }else{
			   $msg  = $msg+';  请选择病种';
		   }
	   }
	   if($msg  != ''){
		   $("#time").html("");
	   }else if($("#time").html() == ''){
		   $.ajax({
				type:'post',
				url:'?c=system&m=ajax_baby_time_list',
				data:'jb_id=' + $("#jb_id").val(),
				success:function(data)
				{
					$("#time").html(data);
				},
				complete: function (XHR, TS)
				{
				   XHR = null;
				}
			});
		}
});
$("#jb_id").change(function(){
	$msg = '';
	 if($("#jb_id").val() == '' || $("#jb_id").val() == null){
		   if($msg == ''){
			   $msg = '请选择病种';
		   }else{
			   $msg  = $msg+';  请选择病种';
		   }
	   }
	   if($msg  != ''){
		   $("#time").html("");
	   }else if($("#time").html() == ''){
		   $.ajax({
				type:'post',
				url:'?c=system&m=ajax_baby_time_list',
				data:'jb_id=' + $("#jb_id").val(),
				success:function(data)
				{
					$("#time").html(data);
				},
				complete: function (XHR, TS)
				{
				   XHR = null;
				}
			});
		}
});
$("#time").click(function(){
	$msg = '';
	 if($("#jb_id").val() == '' || $("#jb_id").val() == null){
		   if($msg == ''){
			   $msg = '请选择病种';
		   }else{
			   $msg  = $msg+';  请选择病种';
		   }
	   }
	   
	   if($msg  != ''){
		   alert($msg);
		   $("#time").html("");
	   }else if($("#time").html() == ''){
		   $.ajax({
				type:'post',
				url:'?c=system&m=ajax_baby_time_list',
				data:'jb_id=' + $("#jb_id").val(),
				success:function(data)
				{
					$("#time").html(data);
				},
				complete: function (XHR, TS)
				{
				   XHR = null;
				}
			});
		}
});

$(".btn-success").click(function(){
  
	$msg = '';
   if($("#form_action").val() == 'add'){
	   if($("#jb_id").val() == '' || $("#jb_id").val() == null){
		   if($msg == ''){
			   $msg = '请选择病种';
		   }else{
			   $msg  = $msg+';  请选择病种';
		   }
	   } 
	    var sum = $("#sum").val();
	    if(sum== '' || sum == null){
		   if($msg == ''){
			   $msg = '请输入可预约数量';
		   }else{
			   $msg  = $msg+';  请输入可预约数量';
		   }
	   }else if(isNaN(sum)){
	    	    if($msg == ''){
				   $msg = '可预约数量有误';
			   }else{
				   $msg  = $msg+';  可预约数量有误';
			   } 
		}else if(sum < 1){
    	    if($msg == ''){
				   $msg = '可预约数量有误';
			   }else{
				   $msg  = $msg+';  可预约数量有误';
			   } 
		}
  
	   if($("#time").val() == '' || $("#time").val() == null){
		   if($msg == ''){
			   $msg = '请选择时间范围';
		   }else{
			   $msg  = $msg+';  请选择时间范围';
		   }
	   }
   }else{
	   var sum = $("#sum").val();
	    if(sum== '' || sum == null){
		   if($msg == ''){
			   $msg = '请输入可预约数量';
		   }else{
			   $msg  = $msg+';  请输入可预约数量';
		   }
	   }else if(isNaN(sum)){
	    	    if($msg == ''){
				   $msg = '可预约数量有误';
			   }else{
				   $msg  = $msg+';  可预约数量有误';
			   } 
		}else if(sum < 1){
   	    if($msg == ''){
				   $msg = '可预约数量有误';
			   }else{
				   $msg  = $msg+';  可预约数量有误';
			   } 
		}
   }
   if($msg == ''){
	   $("#tab").submit();
   }else{
	   alert($msg);
   }
}); 
</script>
</body>
</html>