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

<style type="text/css">
.date_div{ position:absolute; top:25%; left:13%; z-index:1000;}
.anniu{ display:none;}


.list_table .td2 td{background-color: linen;}
.list_table .td3 td{background-color:lightpink;}
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
					<div class="row-fluid" style="margin-top: 10px;">
					     <form action="?c=keshiurl&m=lists" method="post"  id="tab"  style="height:auto;">  
						 <div class="span3"> 
							<div class="row-form">  
								<label class="select_label">医院</label> 
								<select name="hos_id" id="hos_id">
								  <option value="0">请选择...</option> 
								  <?php foreach ($hos_data as $hos_data_temp){?><option value="<?php echo $hos_data_temp['hos_id'];?>"   <?php if($hos_id == $hos_data_temp['hos_id']){?>selected="selected"<?php } ?> ><?php echo $hos_data_temp['hos_name'];?></option><?php }?>
				               </select> 
							<i class=""></i> 
							</div> 
						</div> 
						 <div class="span3"> 
							<div class="row-form">  
								<label class="select_label">科室</label> 
								<select name="keshi_id" id="keshi_id">
								  <option value="0">请选择...</option> 
								  <?php foreach ($keshi_data as $hos_data_temp){?><option value="<?php echo $hos_data_temp['keshi_id'];?>"   <?php if($keshi_id == $hos_data_temp['keshi_id']){?>selected="selected"<?php } ?> ><?php echo $hos_data_temp['keshi_name'];?></option><?php }?>
				               </select> 
							<i class=""></i> 
							</div> 
						</div> 
						 <div class="span3"> 
							<div class="row-form">  
								<label class="select_label">状态</label> 
								<select name="status" id="status">
								  <option value="0">请选择...</option>
				                        <option value="1"  <?php if($status == 1){ ?>selected="selected" <?php }?>>启用</option>
				                        <option value="2"  <?php if($status == 2){ ?>selected="selected" <?php }?>>关闭</option> 
				               </select> 
							<i class=""></i> 
							</div> 
						</div> 
						<div class="span3"> 
							<div class="row-form"> 
								<label class="select_label">搜索内容</label> 
								  <input type="text" name="text" id="text"  class="layui-input" value="<?php if(!empty($text)){echo $text;}?>">
							</div> 
						</div>		  
						<div class="span3">
                        	<button type="submit" class="btn btn-success" style="background-color:#00a186;"> 搜索 </button>   
                            
                             <a href="?c=keshiurl&m=info"  class="label label-success radius">添加</a> 
						</div>
					</form>
                    
					<div class="date_div">
					<div class="divdate"></div>
					<div class="anniu"><a href="javascript:;" class="btn btn-inverse guanbi"> 关闭 </a><br /><a href="javascript:;" class="btn btn-inverse today"> 今天 </a><br /><a href="javascript:;" class="btn btn-inverse week"> 一周 </a><br /><a href="javascript:;" class="btn btn-inverse month"> 一月 </a><br /><a href="javascript:;" class="btn btn-inverse year"> 一年 </a></div>
					</div>
    
    				 <div class="row-fluid">
    				 <div class="span12">
    				 <table width="100%" border="0" cellspacing="0" cellpadding="2" class="list_table">
    				 <thead>
    				 <tr>
    				   <th width="5%" style="text-align:center;">ID</th>
                                  <th width="10%" style="text-align:center;">医院</th>
                                  <th width="10%" style="text-align:center;">科室</th>
                                  <th width="10%" style="text-align:center;">名称</th>
                                  <th width="10%" style="text-align:center;">地址</th>
                                  <th width="10%" style="text-align:center;">状态</th>
                                  <th width="10%" style="text-align:center;">操作</th>
    				 </tr>
    				 </thead>
    				 <tbody>
    				 <?php foreach($list_arr as $val){?>
                              <tr>
                                <td style="text-align:center;"><?php echo $val['id'];?></td>
                                <td style="text-align:center;"><?php foreach ($hos_data as $hos_data_temp){if(strcmp($hos_data_temp['hos_id'],$val['hos_id']) ==0 ){echo $hos_data_temp['hos_name'];break;}}?></td>
				                <td style="text-align:center;"><?php  foreach ($keshi_data as $keshi_data_temp){if(strcmp($keshi_data_temp['keshi_id'],$val['keshi_id']) ==0 ){echo $keshi_data_temp['keshi_name']; break;}}?></td>
                                <td style="text-align:center;"><?php echo $val['title'];?></td>
                                <td style="text-align:center;"><?php echo $val['url'];?></td>
                                <td style="text-align:center;"> 
                                   <?php if($val['status']==1){?>
						            <a class="label label-danger radius radius_state" id="<?php echo $val['id'];?>" title="关闭" style="cursor:pointer;">关闭</a>
						       <?php }elseif($val['status']==2){?>
						            <a class="label label-success radius radius_state" id="<?php echo $val['id'];?>"  title="启用" style="cursor:pointer;">启用</a>
						        <?php }?>
                                </td>
                                <td style="text-align:center;">
								<a herf="javaScript;void(0);" title="?c=keshiurl&m=del&id=<?php echo $val['id'];?>"  class="label label-success radius  delete">删除</a> 
								</td>
                              </tr>
                              <?php }?> 
                              <tr><td colspan="9"><?php echo $page;?></td></tr>
    				 </tbody>
    				 </table>
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
   <script type="text/javascript" src="static/js/date.js"></script>
   <script type="text/javascript" src="static/js/daterangepicker.js"></script>
   <script src="static/js/datepicker/js/datepicker.js"></script>
   <input type="hidden" id="start_date" value="<?php echo $start_date;?>">
    <input type="hidden" id="end_date" value="<?php echo $end_date;?>">
   <script src="static/js/time_select.js"></script>
   <script language="javascript">
   $(".list_table tr").hover(
	  function(){
	    $(this).addClass("over_list");
	  },
	  function(){
	    $(this).removeClass("over_list");
	  }
	); 

   $(".radius_state").live("click",function(){
	   var id =  $(this).attr("id");
	   var title  =  $(this).attr("title");
	   var status = 1;
	   if(title == '关闭'){
		   status = 2;
	   }
	   $.ajax({
			type: 'GET',
			url:"?c=keshiurl&m=update_status&id="+id+"&status="+status,
			dataType: 'html',
			success: function(data){
				if(data==1){
					 if(title == '启用'){
					 	$("#"+id).html("关闭");
						$("#"+id).attr("title","关闭");
	  					 $("#"+id).attr("class","label label-danger radius radius_state");	
	  				 }else{
					    $("#"+id).html("启用");
						$("#"+id).attr("title","启用");
		  				$("#"+id).attr("class","label label-success radius radius_state");	
		  			 }
				}
			}
		});	
   
	 });

    $(".delete").click(function(){ 
	   if(confirm("确认删除吗？")){
		   $.ajax({
				type: 'GET',
				url:$(this).attr("title"),
				dataType: 'html',
				success: function(data){
					if(data==1){
					   $("#tab").submit();
					}
				}
			});	
		}  
	});
	
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
	
</body>
</html>