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
<link rel="stylesheet" type="text/css" href="static/css/chosen.css" />
</head>
<body class="fixed-top">


	  <div class="row-fluid">
		<div class="span12">
		  <div class="widget green">
<!--					<div class="widget-title">
					<h4><i class="icon-reorder"></i> <?php echo $this->lang->line('personal_info'); ?></h4>
					<span class="tools">
						<a href="javascript:;" class="icon-chevron-down"></a>
						<a href="javascript:;" class="icon-remove"></a>
					</span>
					</div>-->
					<div class="widget-body" >
<div class="profile">
  <div class="username-person-img" ><a href="http://www.renaidata.com/bbs/home.php?mod=spacecp&ac=avatar" target="_blank"><img src="<?php echo $this->lang->line('uc_url') . "/avatar.php?uid=" . $_COOKIE['l_admin_id'] . "&type={S}&size=big"; ?>" width="130" height="130" alt="<?php echo $info['admin_name'];?>"/></a></div>
  <dl class="dl-horizontal" >
	<dt><?php echo $this->lang->line('admin_name');?></dt>	
	<dd><?php echo $info['admin_name'];?><font class="admin_username_color"> &nbsp;&nbsp;(<?php echo $info['admin_username'];?>)</font></dd>
	<dt><?php echo $this->lang->line('admin_sex');?></dt>
	<dd><?php echo $info['admin_sex'];?> &nbsp;<?php if($info['admin_sex'] == $this->lang->line('sex_man')){echo "<i class='icon-male'></i>";} else{echo "<i class='icon-female'></i>";}?></dd>
	<dt><?php echo $this->lang->line('rank_name');?></dt>
	<dd><?php echo $info['rank_name'];?></dd>
	<dt><?php echo $this->lang->line('admin_addtime');?></dt>
	<dd><?php echo $info['admin_addtime'];?></dd>
	<dt><?php echo $this->lang->line('admin_lasttime'); ?></dt>
	<dd><?php echo $info['admin_lasttime'];?></dd>
    <dt><?php echo $this->lang->line('admin_lastip'); ?></dt>
	<dd><?php if(!empty($info['admin_lastip'])){echo $info['admin_lastip'];}else{ echo "&nbsp;";} ?></dd>
	<dt><?php echo $this->lang->line('admin_nowtime');?></dt>
	<dd><?php echo $info['admin_nowtime'];?></dd>
    <dt><?php echo $this->lang->line('admin_nowip');?></dt>
	<dd><?php if(!empty($info['admin_nowip'])){echo $info['admin_nowip'];}else{ echo "&nbsp;";} ?></dd>
	<dt><?php echo $this->lang->line('admin_logintimes'); ?></dt>
	<dd><?php echo $info['admin_logintimes'];?></dd>
	<!--<dt><?php echo $this->lang->line('admin_action');?></dt>
	<dd>
	  <table cellpadding="0" cellspacing="0" class="action_list">
	  <?php foreach($menu as $key => $item):?>
	  <tr>
	     <td class="action_th"><label class="checkbox"><b><?php if(in_array($item['act_id'], $info['admin_action']) || in_array("all", $info['admin_action'])) {echo $item['act_name'];}?></b></label></td>
	     <td>
	     <?php 
	     if(isset($item['son'])):	   
	     foreach($item['son'] as $k => $list):?>
	     <label class="checkbox"><?php if(in_array($list['act_id'], $info['admin_action']) || in_array("all", $info['admin_action'])) {echo $list['act_name'];}?></label>
	     <?php
	     endforeach;
	     endif;
	     ?></td>
	   </tr>
	   <?php endforeach;?>
	  </table>
	</dd>-->
  </dl>
  </div>
 </div>
</div>

<div class="widget blue">
					<div class="widget-title" >
					<h4><i class="icon-reorder"></i> <?php echo $this->lang->line('person_info'); ?></h4>
					<span class="tools">
						<a href="javascript:;" class="icon-chevron-down"></a>
						<a href="javascript:;" class="icon-remove"></a>
					</span>
					</div>
					<div class="widget-body">
<form action="?c=index&m=person_info_check" method="post" class="form-horizontal" >
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('admin_birthday');?></label>
	<div class="controls">
		<div class="input-append date" id="dpYears" data-date="1988-10-10" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
			<input class="input-xlarge" type="text" value="<?php echo $info['admin_birthday'];?>"  name="site_time" readonly>
			<span class="add-on"><i class="icon-calendar"></i></span>
		</div>
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('admin_tel');?></label>
	<div class="controls">
	<div class="input-icon left">
		<i class="icon-mobile-phone"></i>
		<input type="text" value="<?php echo $info['admin_tel'];?>" class="input-xlarge" name="admin_tel"  />
	</div>
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('admin_tel_duan');?></label>
	<div class="controls">
	<div class="input-icon left">
		<i class="icon-comment"></i>
		<input type="text" value="<?php echo $info['admin_tel_duan'];?>" class="input-xlarge" name="admin_tel_duan"  />
	</div>
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('admin_qq');?></label>
	<div class="controls">
	<div class="input-icon left">
		<i class="icon-comments"></i>
		<input type="text" value="<?php echo $info['admin_qq'];?>" class="input-xlarge" name="admin_qq"  />
	</div>
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('admin_email');?></label>
	<div class="controls">
	<div class="input-icon left">
		<i class="icon-envelope"></i>
		<input type="text" value="<?php echo $info['admin_email'];?>" class="input-xlarge" name="admin_email" />
	</div>
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('admin_address');?></label>
	<div class="controls">
	<div class="input-icon left">
		<i class="icon-home"></i>
		<input type="text" value="<?php echo $info['admin_address'];?>" class="input-xxlarge" name="admin_address" id="adress"/>
	</div>
	</div>
</div>
<div class="control-group">
	<div class="controls">
		<input type="hidden" name="admin_id" value="<?php echo $info['admin_id'];?>"/><input type="submit" name="submit" value="<?php echo $this->lang->line('submit'); ?>" class="btn btn-primary"/>  <input name="reset" type="reset" value="<?php echo $this->lang->line('reset'); ?>" class="btn"/>
	</div>
</div>
</form>
 </div>
</div>

<div class="widget red" >
					<div class="widget-title">
					<h4><i class="icon-reorder"></i> <?php echo $this->lang->line('re_pass'); ?></h4>
					<span class="tools">
						<a href="javascript:;" class="icon-chevron-down"></a>
						<a href="javascript:;" class="icon-remove"></a>
					</span>
					</div>
					<div class="widget-body">
<form action="?c=index&m=pass_check" method="post" class="form-horizontal" >
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('admin_password');?></label>
	<div class="controls">
		<input type="password" value="" class="input-xlarge" name="admin_password" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('re_password');?></label>
	<div class="controls">
		<input type="password" value="" class="input-xlarge" name="re_password" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('confirm_password');?></label>
	<div class="controls">
		<input type="password" value="" class="input-xlarge" name="confirm_password" />
	</div>
</div>
<div class="control-group">
	<div class="controls">
		<input type="hidden" name="admin_id" value="<?php echo $info['admin_id'];?>"/><input type="submit" name="submit" value="<?php echo $this->lang->line('submit'); ?>" class="btn btn-danger"/> <input name="reset" type="reset" value="<?php echo $this->lang->line('reset'); ?>" class="btn"/>
	</div>
</div>
</form>
 </div>
</div>
</div>
</div>


   <script src="static/js/jquery.js"></script>
   <script src="static/js/jquery.min.js"></script>
   <script src="static/js/jquery.nicescroll.js" type="text/javascript"></script>
   <script src="static/js/bootstrap.min.js"></script>
   <script type="text/javascript" src="static/js/bootstrap-datepicker.js"></script>
   <script src="static/js/chosen.jquery.js"></script>
   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="static/js/excanvas.js"></script>
   <script src="static/js/respond.js"></script>
   <![endif]-->
   <script src="static/js/common-scripts.js"></script>
<script type="text/javascript">
$('#dpYears').datepicker();
$(document).ready(function(){
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
});

$.fn.checkCbx = function(type){ 
	return this.each(function(){
		this.checked = type; 
	}); 
} 
</script>
</body>
</html>