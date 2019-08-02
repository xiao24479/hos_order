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
<form action="?c=index&m=admin_update" method="post" class="form-horizontal">
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('username');?></label>
	<div class="controls">
		<input type="text" value="<?php echo $info['admin_username']; ?>" class="input-xlarge" name="admin_username" />
	</div>
</div>
			<?php if($_COOKIE['l_admin_action'] == 'all'){?>
<div class="control-group">
	<label class="control-label">微信openid</label>
	<div class="controls">
		<input type="text" value="<?php echo $info['openid']; ?>" class="input-xlarge" name="openid" />
	</div>
</div>
<?php }?>


<div class="control-group">
	<label class="control-label">登录方式</label>
	<div class="controls">
		 <label class="radio1">
			<input type="radio" name="login_style" value="1"<?php if($info['login_style'] == 1):?> checked="checked"<?php endif; ?> />
			微信登录
		</label>&nbsp;&nbsp;&nbsp;&nbsp;
		<label class="radio1">
			<input type="radio" name="login_style" value="0"<?php if($info['login_style'] == 0):?> checked="checked"<?php endif; ?> />
			账户登录
		</label>
	</div>
</div>



<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('password');?></label>
	<div class="controls">
		<input type="password" value="" class="input-xlarge" name="admin_password" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('confrim_pass');?></label>
	<div class="controls">
		<input type="password" value="" class="input-xlarge" name="confrim_pass" />
	</div>
</div>
<div class="control-group">
	<label class="control-label">密码错误次数</label>
	<div class="controls">
		<input type="text" value="<?php echo $info['log_errs']; ?>" class="input-xlarge" name="log_errs" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('name');?></label>
	<div class="controls">
		<input type="text" value="<?php echo $info['admin_name']; ?>" class="input-xlarge" name="admin_name" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('sex');?></label>
	<div class="controls">
		 <label class="radio1">
			<input type="radio" name="admin_sex" value="1"<?php if($info['admin_sex'] == 1):?> checked="checked"<?php endif; ?> />
			<?php echo $this->lang->line('man');?>
		</label>&nbsp;&nbsp;&nbsp;&nbsp;
		<label class="radio1">
			<input type="radio" name="admin_sex" value="0"<?php if($info['admin_sex'] == 0):?> checked="checked"<?php endif; ?> />
			<?php echo $this->lang->line('woman');?>
		</label>
	</div>
</div>
    <div class="control-group" style="display:none;">
	<label class="control-label"><?php echo $this->lang->line('birthday');?></label>
	<div class="controls">
		<div class="input-append date" id="dpYears" data-date="<?php echo $info['birthday']; ?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
			<input class="m-ctrl-medium" size="16" type="text" value="<?php echo $info['birthday']; ?>"  name="admin_birthday" readonly>
			<span class="add-on"><i class="icon-calendar"></i></span>
		</div>
	</div>
</div>
<div class="control-group"style="display:none;">
	<label class="control-label"><?php echo $this->lang->line('qq');?></label>
	<div class="controls">
	<div class="input-icon left">
		<i class="icon-comments"></i>
		<input type="text" value="<?php echo $info['admin_qq']; ?>" class="input-xlarge" name="admin_qq"  />
	</div>
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('tel');?></label>
	<div class="controls">
	<div class="input-icon left">
		<i class="icon-mobile-phone"></i>
		<input type="text" value="<?php echo $info['admin_tel']; ?>" class="input-xlarge" name="admin_tel"  />
	</div>
	</div>
</div>
<div class="control-group" style="display:none;">
	<label class="control-label"><?php echo $this->lang->line('admin_tel_duan');?></label>
	<div class="controls">
	<div class="input-icon left">
		<i class="icon-comment"></i>
		<input type="text" value="<?php echo $info['admin_tel_duan']; ?>" class="input-xlarge" name="admin_tel_duan"  />
	</div>
	</div>
</div>
<div class="control-group" >
	<label class="control-label"><?php echo $this->lang->line('email');?></label>
	<div class="controls">
	<div class="input-icon left">
		<i class="icon-envelope"></i>
		<input type="text" value="<?php echo $info['admin_email']; ?>" class="input-xlarge" name="admin_email"  />
	</div>
	</div>
</div>
<div class="control-group" style="display:none;">
	<label class="control-label"><?php echo $this->lang->line('address');?></label>
	<div class="controls">
	<div class="input-icon left">
		<i class="icon-home"></i>
		<input type="text" value="<?php echo $info['admin_address']; ?>" class="input-xxlarge" name="admin_address"  />
	</div>
	</div>
</div>

<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('rank');?></label>
	<div class="controls">
		<select name="rank_id" id="rank_id_1" onChange="get_menu_checkbox(this)">
	   <option value="0"><?php echo $this->lang->line('please_select');?></option>
	   <?php echo $rank_option;?>
	   </select>
	</div>
</div>

<div class="control-group">
	<label class="control-label">成员组</label>
	<div class="controls">
		    <select name="admin_group">
              <option value="0"><?php echo $this->lang->line('please_select');?></option>
               <?php 
			   $parnt_temp_list = array();
				foreach($group_list as $parnt_val){
					if($parnt_val['parent_id']  == 0){
					   $parnt_temp_list[] =$parnt_val;
					}
				}
				 foreach($parnt_temp_list as $parnt_temp_val){
					 if( $html == ''){
						 if($info['admin_group'] == $parnt_temp_val['id']){ ?>
							   <option value="<?php echo $parnt_temp_val['id'];?>"  selected ><?php echo $parnt_temp_val['name'];?></option>  
						   <?php  }else{ ?>
							  <option value="<?php echo $parnt_temp_val['id'];?>'"><?php echo $parnt_temp_val['name'];?></option>
						   <?php  }  
					 }else{
						  if($info['admin_group'] == $parnt_temp_val['id']){ ?>
							 <option value="<?php echo $parnt_temp_val['id'];?>'"  selected><?php echo $parnt_temp_val['name'];?></option>  
						   <?php  }else{?>
							  <option value="<?php echo $parnt_temp_val['id'];?>'"><?php echo $parnt_temp_val['name'];?></option>
						   <?php  } 
					 }
					 foreach($group_list as $parnt_val){
						if($parnt_val['parent_id'] == $parnt_temp_val['id']){
							  if($info['admin_group'] == $parnt_val['id']){ ?>
								<option value="<?php echo $parnt_val['id'];?>"  selected  > &nbsp;&nbsp; &nbsp;&nbsp;<?php echo $parnt_val['name'];?></option>  
							   <?php  }else{ ?>
								 <option value="<?php echo $parnt_val['id'];?>"> &nbsp;&nbsp; &nbsp;&nbsp;<?php echo $parnt_val['name'];?></option>  
							   <?php  } 
						}
					}
				} 
			   ?>
             </select>
	</div>
</div>

<div class="control-group">
	<label class="control-label">账户状态</label>
	<div class="controls">
		<label class="radio1">
			<input type="radio" name="is_pass" value="1"<?php if($info['is_pass'] == 1):?> checked="checked"<?php endif; ?>/>
			是（启用）
		</label>&nbsp;&nbsp;&nbsp;&nbsp;
		<label class="radio1">
			<input type="radio" name="is_pass" value="0"<?php if($info['is_pass'] == 0):?> checked="checked"<?php endif; ?>/>
			否（禁用）
		</label>
	</div>
</div>
<div class="control-group" id="swt">
	<label class="control-label"><?php echo $this->lang->line('swt_name');?></label>
	<div class="controls">
		<input type="text" value="<?php echo $asker['asker_swt_name']; ?>" class="input-xlarge" name="asker_swt_name" id="asker_swt_name" />
	</div>
</div>
<div class="control-group" id="qiao">
	<label class="control-label"><?php echo $this->lang->line('qiao_name');?></label>
	<div class="controls">
		<input type="text" value="<?php echo $asker['asker_qiao_name']; ?>" class="input-xlarge" name="asker_qiao_name" id="asker_qiao_name" />
	</div>
</div>
<div class="control-group" <?php if($_COOKIE['l_admin_action']!='all'){echo "style='display:none;'";}?>>
	<label class="control-label"><?php echo $this->lang->line('rank_action');?></label>
	<div class="controls">
	<span id="check_ok" style="display:none;"><i class="icon-spinner icon-spin"></i>&nbsp;</span>
	<table cellpadding="0" cellspacing="0" class="action_list">
	<?php foreach($menu as $key => $item):?>
	<tr>
	   <td class="action_th"><label><input name="admin_action[]" type="checkbox" class="action_<?php echo $key; ?>_h" id="ch_<?php echo $item['act_id']; ?>" value="<?php echo $item['act_id'];?>"/><b><?php echo $item['act_name'];?></b></label></td>
	   <td><?php 
	   if(isset($item['son'])):
	   foreach($item['son'] as $k => $list):?>
	   <label><input name="admin_action[]" type="checkbox" class="action_<?php echo $key; ?>_c" id="ch_<?php echo $list['act_id']; ?>" value="<?php echo $list['act_id'];?>"/>
	   <?php echo $list['act_name'];?></label>
	   <?php
	   endforeach;
	   endif;
	   ?></td>
	</tr>
	<?php endforeach;?>
	</table>
	</div>
</div>
<div class="control-group">
	<div class="controls">
		<input type="hidden" name="form_action" value="update" /><input type="hidden" name="admin_id" id="admin_id" value="<?php echo $info['admin_id']; ?>" />
        <input type="submit" name="submit" id="sub_1" value="<?php echo $this->lang->line('submit'); ?>" class="btn btn-success"/>  
        <input name="reset" type="reset" value="<?php echo $this->lang->line('reset'); ?>" class="btn"/>
	</div>
</div>
  </form>
  <?php else:?>
  <form action="?c=index&m=admin_update" method="post" class="form-horizontal">
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('username');?></label>
	<div class="controls">
		<input type="text" value="" class="input-xlarge" name="admin_username" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('password');?></label>
	<div class="controls">
		<input type="password" value="" class="input-xlarge" name="admin_password" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('confrim_pass');?></label>
	<div class="controls">
		<input type="password" value="" class="input-xlarge" name="confrim_pass" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('name');?></label>
	<div class="controls">
		<input type="text" value="" class="input-xlarge" name="admin_name" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('sex');?></label>
	<div class="controls">
		 <label class="radio1">
			<input type="radio" name="admin_sex" value="1" checked="checked" />
			<?php echo $this->lang->line('man');?>
		</label>&nbsp;&nbsp;&nbsp;&nbsp;
		<label class="radio1">
			<input type="radio" name="admin_sex" value="0" />
			<?php echo $this->lang->line('woman');?>
		</label>
	</div>
</div>
<div class="control-group" style="display:none;">
	<label class="control-label"><?php echo $this->lang->line('birthday');?></label>
	<div class="controls">
		<div class="input-append date" id="dpYears" data-date="1988-10-10" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
			<input class="m-ctrl-medium" size="16" type="text" value="1988-10-10"  name="admin_birthday" readonly>
			<span class="add-on"><i class="icon-calendar"></i></span>
		</div>
	</div>
</div>
<div class="control-group" style="display:none;">
	<label class="control-label"><?php echo $this->lang->line('qq');?></label>
	<div class="controls">
	<div class="input-icon left">
		<i class="icon-comments"></i>
		<input type="text" value="" class="input-xlarge" name="admin_qq"  />
	</div>
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('tel');?></label>
	<div class="controls">
	<div class="input-icon left">
		<i class="icon-mobile-phone"></i>
		<input type="text" value="" class="input-xlarge" name="admin_tel"  />
	</div>
	</div>
</div>
<div class="control-group" style="display:none;">
	<label class="control-label"><?php echo $this->lang->line('admin_tel_duan');?></label>
	<div class="controls">
	<div class="input-icon left">
		<i class="icon-comment"></i>
		<input type="text" value="" class="input-xlarge" name="admin_tel_duan"  />
	</div>
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('email');?></label>
	<div class="controls">
	<div class="input-icon left">
		<i class="icon-envelope"></i>
		<input type="text" value="" class="input-xlarge" name="admin_email"  />
	</div>
	</div>
</div>
<div class="control-group" style="display:none;">
	<label class="control-label"><?php echo $this->lang->line('address');?></label>
	<div class="controls">
	<div class="input-icon left">
		<i class="icon-home"></i>
		<input type="text" class="input-xxlarge" name="admin_address"  />
	</div>
	</div>
</div>

<div class="control-group">
	<label class="control-label">登录方式</label>
	<div class="controls">
		 <label class="radio1">
			<input type="radio" name="login_style" value="1"  />
			微信登录
		</label>&nbsp;&nbsp;&nbsp;&nbsp;
		<label class="radio1">
			<input type="radio" name="login_style" value="0"  checked="checked" />
			账户登录
		</label>
	</div>
</div> 

<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('rank');?></label>
	<div class="controls">
		<select name="rank_id" id="rank_id_2" onChange="get_menu_checkbox(this)">
	   <option value="0"><?php echo $this->lang->line('please_select');?></option>
	   <?php echo $rank_option;?>
	   </select>
	</div>
</div>

<div class="control-group">
	<label class="control-label">成员组</label>
	<div class="controls">
		     <select name="admin_group">
              <option value="0"><?php echo $this->lang->line('please_select');?></option>
               <?php 
			   $parnt_temp_list = array();
				foreach($group_list as $parnt_val){
					if($parnt_val['parent_id']  == 0){
					   $parnt_temp_list[] =$parnt_val;
					}
				} 
				 foreach($parnt_temp_list as $parnt_temp_val){
					 if( $html == ''){
						 if($info['admin_group'] == $parnt_temp_val['id']){ ?>
							   <option value="<?php echo $parnt_temp_val['id'];?>"  selected ><?php echo $parnt_temp_val['name'];?></option>  
						   <?php  }else{ ?>
							  <option value="<?php echo $parnt_temp_val['id'];?>'"><?php echo $parnt_temp_val['name'];?></option>
						   <?php  }  
					 }else{
						  if($info[''] == $parnt_temp_val['id']){ ?>
							 <option value="<?php echo $parnt_temp_val['id'];?>'"  selected><?php echo $parnt_temp_val['name'];?></option>  
						   <?php  }else{?>
							  <option value="<?php echo $parnt_temp_val['id'];?>'"><?php echo $parnt_temp_val['name'];?></option>
						   <?php  } 
					 }   
					 foreach($group_list as $parnt_val){
						if($parnt_val['parent_id'] == $parnt_temp_val['id']){
							  if($info['admin_group'] == $parnt_val['id']){ ?>
								 <option value="<?php echo $parnt_val['id'];?>"  selected  > &nbsp;&nbsp; &nbsp;&nbsp;<?php echo $parnt_val['name'];?></option>  
							   <?php  }else{ ?>
								  <option value="<?php echo $parnt_val['id'];?>"> &nbsp;&nbsp; &nbsp;&nbsp;<?php echo $parnt_val['name'];?></option>  
							   <?php  } 
						}
					}
				} 
			   ?>
             </select>
	</div>
</div>

<div class="control-group">
	<label class="control-label">账户状态</label>
	<div class="controls">
		<label class="radio1">
			<input type="radio" name="is_pass" value="1" checked="checked" />
			是（启用）
		</label>&nbsp;&nbsp;&nbsp;&nbsp;
		<label class="radio1">
			<input type="radio" name="is_pass" value="0" />
			否（禁用）
		</label>
	</div>
</div>
<div class="control-group" id="swt" style="display:none">
	<label class="control-label"><?php echo $this->lang->line('swt_name');?></label>
	<div class="controls">
		<input type="text" value="" class="input-xlarge" name="asker_swt_name" id="asker_swt_name" />
	</div>
</div>
<div class="control-group" id="qiao" style="display:none;">
	<label class="control-label"><?php echo $this->lang->line('qiao_name');?></label>
	<div class="controls">
		<input type="text" value="" class="input-xlarge" name="asker_qiao_name" id="asker_qiao_name" />
	</div>
</div>
<div class="control-group" <?php if($_COOKIE['l_admin_action']!='all'){echo "style='display:none;'";}?>>
	<label class="control-label"><?php echo $this->lang->line('rank_action');?></label>
	<div class="controls">
	<span id="check_ok" style="display:none;"><i class="icon-spinner icon-spin"></i>&nbsp;</span>
	<table cellpadding="0" cellspacing="0" class="action_list">
	<?php foreach($menu as $key => $item):?>
	<tr>
	   <td class="action_th"><label><input name="admin_action[]" type="checkbox" class="action_<?php echo $key; ?>_h" id="ch_<?php echo $item['act_id']; ?>" value="<?php echo $item['act_id'];?>"/><b><?php echo $item['act_name'];?></b></label></td>
	   <td><?php 
	   if(isset($item['son'])):
	   foreach($item['son'] as $k => $list):?>
	   <label><input name="admin_action[]" type="checkbox" class="action_<?php echo $key; ?>_c" id="ch_<?php echo $list['act_id']; ?>" value="<?php echo $list['act_id'];?>"/>
	   <?php echo $list['act_name'];?></label>
	   <?php
	   endforeach;
	   endif;
	   ?></td>
	</tr>
	<?php endforeach;?>
	</table>
	</div>
</div>
<div class="control-group">
	<div class="controls">
		<input type="hidden" name="form_action" value="add" /><input type="submit" name="submit" id="sub_2" value="<?php echo $this->lang->line('submit'); ?>" class="btn btn-success"/>  <input name="reset" type="reset" value="<?php echo $this->lang->line('reset'); ?>" class="btn"/>
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
<script>

         $("#ud1").click(function(){
             $(this).find("input").attr("checked",true);
         });
		  $("#ud2").click(function(){
             $(this).find("input").attr("checked",true);
         });

         $("#sub_1").click(function(){
             var rank=$("#rank_id_1").val();
             if(rank=='0'){
                 alert('请选择用户所属的岗位！');
                 return false;
             }  
			 
         });
         $("#sub_2").click(function(){
             var rank1=$("#rank_id_2").val();
             if(rank1=='0'){
                 alert('请选择用户所属的岗位！');
                 return false;
             }
			 
         });
    
    
    
	$('#dpYears').datepicker();
	
	$("input[class^='action_']").change(function(){
		var id_name = $(this).attr("class");
		id_arr = id_name.split("_");
		if(id_name == "action_" + id_arr[1] + "_h")
		{
			if($(this).is(":checked"))
			{
				$(".action_" + id_arr[1] + "_c").checkCbx(true);
			}
			else
			{
				$(".action_" + id_arr[1] + "_c").checkCbx(false);
			}
		}
		else
		{
			if(!$(".action_" + id_arr[1] + "_h").is(":checked"))
			{
				$(".action_" + id_arr[1] + "_h").checkCbx(true);
			}
		}
	});
   
$("#asker_swt_name").focusout(function(){
	var name = $(this).val();
	if(name != "")
	{
		$(this).after("<i class='icon-refresh icon-spin'></i>");
		$.ajax({
			type:'post',
			url:'?c=index&m=asker_name_ajax',
			data:'name=' + name + '&type=2&admin_id=' + $("#admin_id").val(),
			success:function(data)
			{
				$("#asker_swt_name").next("i").remove();
				$("#asker_swt_name").next("span").remove();
				$("#asker_swt_name").parent().parent().removeClass("error");
				if(data == 0)
				{
					$("#asker_swt_name").parent().parent().addClass("error");
					$("#asker_swt_name").after('<span class="help-inline">不能为空</span>');
				}
				else if(data == 1)
				{
					$("#asker_swt_name").parent().parent().addClass("error");
					$("#asker_swt_name").after('<span class="help-inline">此名称已被使用，请重新输入！</span>');
				}
			},
			complete: function (XHR, TS)
			{
			   XHR = null;
			}
		});
	}
});

$("#asker_qiao_name").focusout(function(){
	var name = $(this).val();
	if(name != "")
	{
		$(this).after("<i class='icon-refresh icon-spin'></i>");
		$.ajax({
			type:'post',
			url:'?c=index&m=asker_name_ajax',
			data:'name=' + name + '&type=1&admin_id=' + $("#admin_id").val(),
			success:function(data)
			{
				$("#asker_qiao_name").next("i").remove();
				$("#asker_qiao_name").next("span").remove();
				$("#asker_qiao_name").parent().parent().removeClass("error");
				if(data == 0)
				{
					$("#asker_qiao_name").parent().parent().addClass("error");
					$("#asker_qiao_name").after('<span class="help-inline">不能为空</span>');
				}
				else if(data == 1)
				{
					$("#asker_qiao_name").parent().parent().addClass("error");
					$("#asker_qiao_name").after('<span class="help-inline">此名称已被使用，请重新输入！</span>');
				}
			},
			complete: function (XHR, TS)
			{
			   XHR = null;
			}
		});
	}
});

   
   function get_menu_checkbox(obj)
   {
   		$("#check_ok").css('display', 'block');
		$("input[class^='action_']").checkCbx(false);
		$.ajax({
		   type:'post',         //数据发送方式
		   url:'?c=index&m=ajax_rank_action', //后台处理程序
		   data:'id=' + obj.value,         //要传递的数据
		   success:function(data)
		   {
			   data = $.parseJSON(data);
			   if(data['rank_type'] == 2)
			   {
				   $("#qiao").css("display", "block");
				   $("#swt").css("display", "block");
			   }
			   else
			   {
				   $("#qiao").css("display", "none");
				   $("#swt").css("display", "none");
			   }
			   var rank_action = data['rank_action'].split(",");
			   for (var i = 0; i < rank_action.length; i++) {
				  $("#ch_" + rank_action[i]).checkCbx(true);
			   }
			   $("#check_ok").css('display', 'none');
		   }
		});
   }
   $.fn.checkCbx = function(type){ 
	return this.each(function(){
		this.checked = type; 
	}); 
   }
   
<?php if(isset($info['admin_action']) && !empty($info['admin_action'])):?>
var admin_action_data = '<?php echo $info['admin_action']; ?>';
admin_action_data = admin_action_data.split(",");
for (var i = 0; i < admin_action_data.length; i++)
{
  $("#ch_" + admin_action_data[i]).checkCbx(true);
}
<?php endif; ?>
</script>
</body>
</html>