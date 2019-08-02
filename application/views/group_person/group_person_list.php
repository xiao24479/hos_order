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
            
					<div class="row-fluid" style="margin-top:10px;">
                        <form action="?c=group_person&m=group_person_list" method="post"   style="height:auto;">  
						<div class="span3"> 
							<div class="row-form">  
								<label class="select_label">选择医院</label> 
								<select name="hos_id" id="hos_id" > 
								 <option value=""><?php echo $this->lang->line('hospital_select'); ?></option>
								 <?php foreach($hospital as $val):?><option value="<?php echo $val['hos_id']; ?>" <?php if($val['hos_id'] == $hos_id){echo " selected";}?>><?php echo $val['hos_name']; ?></option><?php endforeach;?>
                                 </select> 
							<i class=""></i> 
							</div> 
						</div> 
						<div class="span3"> 
							<div class="row-form"> 
								<label class="select_label">选择科室</label> 
								<select name="keshi_id" id="keshi_id" > 
									<option value="">请选择科室..</option> 
                                     <?php foreach($keshi as $val):?><option value="<?php echo $val['keshi_id']; ?>" <?php if($val['keshi_id'] == $keshi_id){echo " selected";}?>><?php echo $val['keshi_name']; ?></option><?php endforeach;?>
								</select> 
							</div> 
						</div>		 
                        
                        <div class="span3"> 
							<div class="row-form"> 
								<label class="select_label">选择大组</label> 
								<select name="parent_id" id="parent_id" > 
									<option value="">请选组..</option> 
                                    <?php foreach($group_list as $val):?><?php if(intval($val['parent_id']) ==0){?><option value="<?php echo $val['id']; ?>" <?php if($val['id'] == $parent_id){echo " selected";}?>><?php echo $val['name']; ?></option><?php  }?><?php endforeach;?>
								</select> 
							</div> 
						</div>		 
                        
                        
						<div class="span4">
                        	<button type="submit" class="btn btn-success" style="background-color:#00a186;"> 搜索 </button> 
							<a href="?c=group_person&m=group_person_add" class="btn"> 新增组 </a>  
						</div>
					</form>
                     
                        <div class="span9" > 
                            <table width="100%">
                                <tr><td width="50">编号</td><td width="300">医院</td><td width="300">科室</td><td width="300">组长</td><td width="300">名称</td><td width="200">发布时间</td><td width="200">修改时间</td><td width="300">操作</td></tr> 
                                <?php 
                                $i=0;
                                foreach($group_list as $val){  $i++;  ?>
                                <tr <?php if(($i+1)%2==0){echo "style='background-color:#fff'";}?>><td width="50"  height="60"><?=$i?></td>
                                <td width="300"><?php foreach($hospital as $hospital_val){?> <?php if($val['hos_id'] == $hospital_val['hos_id'] ){echo  $hospital_val['hos_name'];break;}}?></td>
                                <td width="300"><?php foreach($keshi_show as $keshi_val){?> <?php if($val['keshi_id'] == $keshi_val['keshi_id'] ){echo  $keshi_val['keshi_name'];break;}}?></td>
                                 <td width="300"><?php foreach($admin_list as $admin_list_ts){?> <?php if($val['admin_id'] == $admin_list_ts['admin_id'] ){echo  $admin_list_ts['admin_name'].'_'.$admin_list_ts['admin_username'];break;}}?></td>
                                <td width="300"><?=$val['name']?></td>
                                <td width="80"><?php echo date('Y-m-d H:i:s',$val['add_time'])?></td><td width="200"><?php echo  date('Y-m-d H:i:s',$val['add_time']);?></td><td><a href="?c=group_person&m=update_group_person&id=<?=$val['id']?>">编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="?c=group_person&m=del_group_person&id=<?=$val['id']?>" style="color:#00a186;margin-left:10px; cursor: pointer;" >删除</a>
                                </td></tr>
                                <?php }?>
                            </table>
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
   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="static/js/excanvas.js"></script>
   <script src="static/js/respond.js"></script>
   <![endif]-->
   <script src="static/js/common-scripts.js"></script>
   <script>
		 function order_window(url){
			window.open (url, 'newwindow', 'height=600, width=1000, top=200, left=200, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=no, status=no');
		}
	
	
		$("#hos_id").change(function(){
			ajax_get_keshi($(this).val(), 0);
		});
		
		
		function ajax_get_keshi(hos_id, check_id)
		{
			$.ajax({
				type:'post',
				url:'?c=order&m=keshi_list_ajax',
				data:'hos_id=' + hos_id + '&check_id=' + check_id,
				success:function(data){
					$("#keshi_id").html(data);
				},
				complete: function (XHR, TS){
					 XHR = null;
				}
			});
		} 
		
		$("#keshi_id").change(function(){
			ajax_get_group_by_keshi_id($(this).val());
		}); 
		
		function ajax_get_group_by_keshi_id(keshi_id)
		{
			$.ajax({
				type:'post',
				url:'?c=group_person&m=group_list_ajax',
				data:'keshi_id=' + keshi_id ,
				success:function(data){
					$("#parent_id").html(data);
				},
				complete: function (XHR, TS){
					 XHR = null;
				}
			});
		}
			
   </script>
</body>
</html>