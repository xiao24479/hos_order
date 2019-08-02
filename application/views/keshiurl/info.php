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
                        <form method="post" action="?c=keshiurl&m=add" >
                        
                       <div class="span9" > 
							<div class="content"> 
								<label class="select_label">医院</label> 
								<select name="hos_id" id="hos_id">
								  <option value="0">请选择...</option> 
								  <?php foreach ($hos_data as $hos_data_temp){?><option value="<?php echo $hos_data_temp['hos_id'];?>"><?php echo $hos_data_temp['hos_name'];?></option><?php }?>
				               </select> 
							   <i class=""></i> 
							</div> 
					 
							<div class="content"> 
								<label class="select_label">科室</label> 
								<select name="keshi_id" id="keshi_id">
								  <option value="0">请选择...</option> 
				               </select> 
							<i class=""></i> 
							</div> 
				 
							<div class="content"> 
								<label class="select_label">状态</label> 
								<select name="status" id="status">
				                        <option value="1" selected="selected">启用</option> 
				                        <option value="2"  >关闭</option> 
				               </select> 
							<i class=""></i> 
							</div> 
				 
							<div class="content"> 
								<label class="select_label">名称</label> 
								  <input type="text" name="title" id="title"  class="layui-input" value="">
							</div> 
						 
							<div class="content"> 
								<label class="select_label">URL地址</label> 
								  <input type="text" name="url" id="url"  class="layui-input" value="">
							</div> 
					 
							<div class="content">  
                                 <p><input type="submit"  value="提交"/></p>
							</div>  
						</div>
						</form>
					</div>
				<!-- END PAGE CONTENT-->
			 </div>
			 <!-- END PAGE CONTAINER-->
		</div>
	</div>
    
</body>
</html>

    <script src="static/js/jquery-1.8.3.min.js"></script>
     <script language="javascript">
  	 $("#hos_id").change(function(){ 
		ajax_get_keshi( $(this).val(), 0); 
	});

	function ajax_get_keshi(hos_id, check_id)
	{
		$("#keshi_id").after("<i class='icon-refresh icon-spin'></i>");
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
			   $("#keshi_id").next(".icon-spin").remove();
			}
		});
	}
	 
   </script>