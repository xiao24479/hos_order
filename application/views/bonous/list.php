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
					     <form action="?c=bonous&m=lists" method="post"   style="height:auto;">  
						 <div class="span3">
						 <div class="row-form"> 
						 <label class="select_label">时间范围</label> 
						 <input type="text" value="<?php echo $start; ?> - <?php echo $end; ?>" style="width:200px;margin-top:8px; vertical-align:middle;height:16px;font-size:12px; " class="input-block-level" name="date" id="inputDate" /></span>
						</div>
						 </div>		
						 <div class="span3"> 
							<div class="row-form">  
								<label class="select_label">选择类型</label> 
								<select name="type" id="type">
								  <option value="0">请选择...</option>
				                        <option value="1"  <?php if($type == 1){ ?>selected="selected" <?php }?>>手术限价1200</option>
				                        <option value="2"  <?php if($type == 2){ ?>selected="selected" <?php }?>>单科检查12</option>
				                        <option value="3"  <?php if($type == 3){ ?>selected="selected" <?php }?>>术前检查120</option>
				                        <option value="4"  <?php if($type == 4){ ?>selected="selected" <?php }?>>手术减免300</option>
				                        <option value="5"  <?php if($type == 5){ ?>selected="selected" <?php }?>>手术优惠1200</option>
				                        <option value="6"  <?php if($type == 6){ ?>selected="selected" <?php }?>>检查／治疗免费</option>
				               </select> 
							<i class=""></i> 
							</div> 
						</div> 
						 <div class="span3"> 
							<div class="row-form">  
								<label class="select_label">已打/未打</label> 
								<select name="state" id="state">
								  <option value="0">请选择...</option>
				                        <option value="1"  <?php if($state == 1){ ?>selected="selected" <?php }?>>未打</option>
				                        <option value="2"  <?php if($state == 2){ ?>selected="selected" <?php }?>>已打</option> 
				               </select> 
							<i class=""></i> 
							</div> 
						</div> 
						<div class="span3"> 
							<div class="row-form"> 
								<label class="select_label">手机</label> 
								  <input type="text" name="mobile" id="mobile" placeholder="输入手机号码..." autocomplete="off" class="layui-input" value="<?php if(!empty($mobile)){echo $mobile;}?>">
							</div> 
						</div>		  
						<div class="span3">
                        	<button type="submit" class="btn btn-success" style="background-color:#00a186;"> 搜索 </button>  
                        	<span>总预览量:<?php if(!empty($amount)){echo $amount;}else{echo 0;}?></span>
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
                                  <th width="10%" style="text-align:center;">红包类型</th>
                                  <th width="10%" style="text-align:center;">手机号码</th>
                                  <th width="10%" style="text-align:center;">未打/已打</th>
                                  <th width="10%" style="text-align:center;">手机号码归属地</th>
                                  <th width="10%" style="text-align:center;">IP地址</th>
                                  <th width="15%" style="text-align:center;">领取时间</th>
                                  <th width="20%" style="text-align:center;">备注</th>
                                  <th width="10%" style="text-align:center;">操作</th>
    				 </tr>
    				 </thead>
    				 <tbody>
    				 <?php foreach($list_arr as $val){?>
                              <tr>
                                <td style="text-align:center;"><?php echo $val['id'];?></td>
                                <td style="text-align:center;">
                                   <?php if($val['mold'] == 1){ ?>手术限价1200<?php }?>
				                    <?php if($val['mold'] == 2){ ?>单科检查12<?php }?>
				                   <?php if($val['mold'] == 3){ ?>术前检查120<?php }?>
				                   <?php if($val['mold'] == 4){ ?>手术减免300<?php }?>
				                   <?php if($val['mold'] == 5){ ?>手术优惠1200<?php }?>
				                   <?php if($val['mold'] == 6){ ?>检查／治疗免费<?php }?>
                                </td>
                                <td style="text-align:center;"><?php echo $val['mobile'];?></td>
                                <td style="text-align:center;" class="td-status">
                                   <?php if($val['state']==1){?>
						            <a class="label label-danger radius radius_state" id="<?php echo $val['id'];?>" title="未打" style="cursor:pointer;">未打</a>
						       <?php }elseif($val['state']==2){?>
						            <a class="label label-success radius radius_state" id="<?php echo $val['id'];?>"  title="已打" style="cursor:pointer;">已打</a>
						        <?php }?>
                                </td>
                                <td style="text-align:center;"><?php echo $val['province'];?>&nbsp;<?php echo $val['city'];?></td>
                                <td style="text-align:center;"><?php echo $val['ip_address'];?></td>
                                <td style="text-align:center;"><?php echo $val['receive_time'];?></td>
                                <td style="text-align:center;">
                                    <?php foreach($val['remark_list'] as $key=>$value){
									if(strcmp($value['bonousid'],$val['id']) == 0){
									?>
                                       <p <?php if($key==0){?>style="color:green;"<?php }elseif($key==1){?>style="color:red;"<?php }elseif($key==1){?>style="color:blue;"<?php }?>><?php echo $value['remark']."  ".$value['add_time'];?></p>
                                    <?php }}?>
                                </td>
                                <td style="text-align:center;">
								<a href="?c=bonous&m=add_remark&id=<?php echo $val['id'];?>"  class="label label-success radius">备注</a>
								<a href="?c=bonous&m=list_log&bonousid=<?php echo $val['id'];?>"  class="label label-success radius">备注列表</a>
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
	   var state = 1;
	   if(title == '未打'){
		   state = 2;
	   }
	   $.ajax({
			type: 'GET',
			url:"?c=bonous&m=update_state&id="+id+"&state="+state,
			dataType: 'json',
			success: function(data){ 
				if(data==200){ 
					 if(title == '已打'){
					 	$("#"+id).html("未打");
						$("#"+id).attr("title","未打");
	  					 $("#"+id).attr("class","label label-danger radius radius_state");	
	  				 }else{
					    $("#"+id).html("已打");
						$("#"+id).attr("title","已打");
		  				$("#"+id).attr("class","label label-success radius radius_state");	
		  			 }
	  				 
				}
			},
		});	
   
	 });
 
   </script>
	
</body>
</html>