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
					<div class="widget-title">
					<h4><i class="icon-reorder"></i> <?php echo $this->lang->line('content_form'); ?></h4>
					<span class="tools">
						<a href="javascript:;" class="icon-chevron-down"></a>
						<a href="javascript:;" class="icon-remove"></a>
					</span>
					</div>
					<div class="widget-body">
  <?php if(!empty($site_info)): ?>
<form action="?c=site&m=site_update" method="post" class="form-horizontal">
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_domain');?></label>
	<div class="controls">
		<input type="text" value="<?php echo $site_info['site_domain']; ?>" class="input-xlarge" name="site_domain" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_mobile_domain');?></label>
	<div class="controls">
		<input type="text" value="<?php echo $site_info['site_mobile_domain']; ?>" class="input-xlarge" name="site_mobile_domain" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_name');?></label>
	<div class="controls">
	   <input type="text" value="<?php echo $site_info['site_name']; ?>" class="input-xlarge" name="site_name" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_bd');?></label>
	<div class="controls">
	   <label class="checkbox"><input type="radio" value="1" class="input-xlarge" name="site_bd" <?php if($site_info['site_bd']){echo 'checked="checked"';}?>/>&nbsp;是</label>
	   <label class="checkbox"><input type="radio" value="0" class="input-xlarge" name="site_bd" <?php if(!$site_info['site_bd']){echo 'checked="checked"';}?>/>&nbsp;否</label>
	</div>
</div>
<div id="site_bd" style="display:<?php if($site_info['site_bd']){echo "block";}else{ echo "none";}?>;">
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_bd_username');?></label>
	<div class="controls">
	   <input type="text" value="<?php echo $site_info['site_bd_username']; ?>" class="input-xlarge" name="site_bd_username" />
	</div>
</div> 
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_bd_password');?></label>
	<div class="controls">
	   <input type="password" value="<?php echo $site_info['site_bd_password']; ?>" class="input-xlarge" name="site_bd_password" />
	</div>
</div> 
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_bd_token');?></label>
	<div class="controls">
	   <input type="text" value="<?php echo $site_info['site_bd_token']; ?>" class="input-xlarge" name="site_bd_token" />
	</div>
</div>
</div>  
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('swt_url');?></label>
	<div class="controls">
	   <input type="text" value="<?php echo $site_info['swt_url']; ?>" class="input-xlarge" name="swt_url" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_system');?></label>
	<div class="controls">
	   <select name="sys_id">
		  <option value="0"><?php echo $this->lang->line('please_select');?></option>
		  <?php foreach($site_system as $val):?>
		  <option value="<?php echo $val['sys_id']?>" <?php if($val['sys_id'] == $site_info['sys_id']) {echo 'selected';}?>><?php echo $val['sys_name'];?></option>  	 
		  <?php endforeach;?>
	   </select>
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_ba_no');?></label>
	<div class="controls">
	   <input type="text" value="<?php echo $site_info['site_ba_no']; ?>" class="input-xlarge" name="site_ba_no" />
	</div>
</div> 
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_ba_com');?></label>
	<div class="controls">
	   <input type="text" value="<?php echo $site_info['site_ba_com']; ?>" class="input-xlarge" name="site_ba_com" />
	</div>
</div> 
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_ba_name');?></label>
	<div class="controls">
	   <input type="text" value="<?php echo $site_info['site_ba_name']; ?>" class="input-xlarge" name="site_ba_name" />
	</div>
</div> 
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('ba_xz');?></label>
	<div class="controls">
	   <select name="xz_id" >
		<option value="0"><?php echo $this->lang->line('please_select');?></option>
		<?php foreach($ba_xz as $val):?>
		  <option value="<?php echo $val['xz_id']; ?>" <?php if($val['xz_id'] == $site_info['xz_id']) {echo 'selected';}?>><?php echo $val['xz_name'];?></option>  	 
		<?php endforeach;?>
	   </select>
	</div>
</div> 
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_ba_time');?></label>
	<div class="controls">
		<div class="input-append date" id="dpYears" data-date="<?php echo date("Y-m-d", time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
			<input class="m-ctrl-medium" size="16" type="text" value="<?php echo $site_info['site_ba_time'];?>"  name="site_ba_time" readonly>
			<span class="add-on"><i class="icon-calendar"></i></span>
		</div>
	</div>
</div> 
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_ip');?></label>
	<div class="controls">
	   <input type="text" value="<?php echo $site_info['site_ip']; ?>" class="input-xlarge" name="site_ip" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_host');?></label>
	<div class="controls">
	   <input type="text" value="<?php echo $site_info['site_host']; ?>" class="input-xlarge" name="site_host" />
	</div>
</div>  
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_host_system');?></label>
	<div class="controls">
	   <input type="text" value="<?php echo $site_info['site_host_system']; ?>" class="input-xlarge" name="site_host_system" />
	</div>
</div> 
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_domain_time');?></label>
	<div class="controls">
		<div class="input-append date" id="site_domain_time" data-date="<?php echo date("Y-m-d", time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
			<input class="m-ctrl-medium" size="16" type="text" value="<?php echo $site_info['site_domain_time'];?>"  name="site_domain_time" readonly>
			<span class="add-on"><i class="icon-calendar"></i></span>
		</div>
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_time');?></label>
	<div class="controls">
		<div class="input-append date" id="site_time" data-date="<?php echo $site_info['site_time']; ?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
			<input class="m-ctrl-medium" size="16" type="text" value="<?php echo $site_info['site_time']; ?>"  name="site_time" readonly>
			<span class="add-on"><i class="icon-calendar"></i></span>
		</div>
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_keywords');?></label>
	<div class="controls">
	 <textarea class="input-xlarge" rows="5" name="site_keywords" ><?php echo $keywords; ?></textarea>
	 <textarea class="input-xlarge" style="display: none;" rows="5" name="old_site_keywords" ><?php echo $keywords; ?></textarea>
	</div>
</div>   
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_rank');?></label>
	<div class="controls">
	   <div class="rank_tree">
	     <ul>
           <?php echo $rank_list; ?>
         </ul>
	   </div>
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('order');?></label>
	<div class="controls">
		<input type="text" value="<?php echo $site_info['site_order']; ?>" class="input-xlarge" name="rank_order" />
	</div>
</div>
<div class="control-group">
	<div class="controls">
		<input type="hidden" name="form_action" value="update" /><input type="hidden" name="site_id" value="<?php echo $site_info['site_id']; ?>" /><input type="submit" name="submit" value="<?php echo $this->lang->line('submit'); ?>" class="btn btn-success"/>  <input name="reset" type="reset" value="<?php echo $this->lang->line('reset'); ?>" class="btn"/>
	</div>
</div>
  </form>
  <?php else:?>
  <form action="?c=site&m=site_update" method="post" class="form-horizontal">
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_domain');?></label>
	<div class="controls">
		<input type="text" value="" class="input-xlarge" name="site_domain" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_mobile_domain');?></label>
	<div class="controls">
		<input type="text" value="" class="input-xlarge" name="site_mobile_domain" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_name');?></label>
	<div class="controls">
	   <input type="text" value="" class="input-xlarge" name="site_name" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_bd');?></label>
	<div class="controls">
	   <label class="checkbox"><input type="radio" value="1" class="input-xlarge" name="site_bd" checked="checked"/>&nbsp;是</label>
	   <label class="checkbox"><input type="radio" value="0" class="input-xlarge" name="site_bd"/>&nbsp;否</label>
	</div>
</div>
<div id="site_bd" style="display:block;">
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_bd_username');?></label>
	<div class="controls">
	   <input type="text" value="" class="input-xlarge" name="site_bd_username" />
	</div>
</div> 
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_bd_password');?></label>
	<div class="controls">
	   <input type="text" value="" class="input-xlarge" name="site_bd_password" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_bd_token');?></label>
	<div class="controls">
	   <input type="text" value="" class="input-xlarge" name="site_bd_token" />
	</div>
</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('swt_url');?></label>
	<div class="controls">
	   <input type="text" value="" class="input-xlarge" name="swt_url" />
	</div>
</div> 
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_system');?></label>
	<div class="controls">
	   <select name="sys_id">
	      <option value="0"><?php echo $this->lang->line('please_select');?></option>
		  <?php foreach($site_system as $val){echo "<option value=\"" . $val['sys_id'] . "\">" . $val['sys_name'] . "</option>";}?>
	   </select>
	</div>
</div> 
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_ba_no');?></label>
	<div class="controls">
	   <input type="text" value="" class="input-xlarge" name="site_ba_no" />
	</div>
</div> 
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_ba_com');?></label>
	<div class="controls">
	   <input type="text" value="" class="input-xlarge" name="site_ba_com" />
	</div>
</div> 
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_ba_name');?></label>
	<div class="controls">
	   <input type="text" value="" class="input-xlarge" name="site_ba_name" />
	</div>
</div> 
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('ba_xz');?></label>
	<div class="controls">
	   <select name="xz_id" >
	   <option value="0"><?php echo $this->lang->line('please_select');?></option>
	     <?php foreach($ba_xz as $val){echo "<option value=\"" . $val['xz_id'] . "\">" . $val['xz_name'] . "</option>";}?>
	   </select>
	</div>
</div> 
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_ba_time');?></label>
	<div class="controls">
		<div class="input-append date" id="dpYears" data-date="<?php echo date("Y-m-d", time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
			<input class="m-ctrl-medium" size="16" type="text" value="<?php echo date("Y-m-d", time());?>"  name="site_ba_time" readonly>
			<span class="add-on"><i class="icon-calendar"></i></span>
		</div>
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_ip');?></label>
	<div class="controls">
	   <input type="text" value="" class="input-xlarge" name="site_ip" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_host');?></label>
	<div class="controls">
	   <input type="text" value="" class="input-xlarge" name="site_host" />
	</div>
</div>  
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_host_system');?></label>
	<div class="controls">
	   <input type="text" value="" class="input-xlarge" name="site_host_system" />
	</div>
</div> 
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_domain_time');?></label>
	<div class="controls">
		<div class="input-append date" id="site_domain_time" data-date="<?php echo date("Y-m-d", time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
			<input class="m-ctrl-medium" size="16" type="text" value="<?php echo date("Y-m-d", time());?>"  name="site_domain_time" readonly>
			<span class="add-on"><i class="icon-calendar"></i></span>
		</div>
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_time');?></label>
	<div class="controls">
		<div class="input-append date" id="site_time" data-date="<?php echo date("Y-m-d", time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
			<input class="m-ctrl-medium" size="16" type="text" value="<?php echo date("Y-m-d", time());?>"  name="site_time" readonly>
			<span class="add-on"><i class="icon-calendar"></i></span>
		</div>
	</div>
</div>  
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_keywords');?></label>
	<div class="controls">
	 <textarea class="input-xlarge" rows="5" name="site_keywords" ></textarea>
	</div>
</div> 
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('site_rank');?></label>
	<div class="controls">
	   <div class="rank_tree">
	     <ul>
           <?php echo $rank_list; ?>
         </ul>
	   </div>
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('order');?></label>
	<div class="controls">
		<input type="text" value="" class="input-xlarge" name="rank_order" />
	</div>
</div>
<div class="control-group">
	<div class="controls">
		<input type="hidden" name="form_action" value="add" /><input type="submit" name="submit" value="<?php echo $this->lang->line('submit'); ?>" class="btn btn-success"/>  <input name="reset" type="reset" value="<?php echo $this->lang->line('reset'); ?>" class="btn"/>
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
$('#dpYears').datepicker();
$('#site_domain_time').datepicker();
$('#site_time').datepicker();
$(document).ready(function(){
	$(".rank_tree input").click(function(){
		tree_check($(this).attr("parent_id"), $(this).attr("rank_id"), this.checked);
	});
	
	$("input[name='site_bd']").click(function(){
		$("#site_bd").slideToggle("300");
	});
});

function tree_check(parent_id, rank_id, check)
{
	if(check)
	{
		$("input[rank_id='" + parent_id + "']").checkCbx(true);
		$("input[rank_id='" + parent_id + "']").each(function(i){
			p_parent_id = $("input[rank_id='" + parent_id + "']").eq(i).attr("parent_id");
			if($("input[rank_id='" + p_parent_id + "']").attr("name"))
			{
				tree_check(p_parent_id, parent_id, true);
			}
		});
	}
	else
	{
		$("input[parent_id='" + rank_id + "']").checkCbx(false);
		$("input[parent_id='" + rank_id + "']").each(function(i){
			p_rank_id = $("input[parent_id='" + rank_id + "']").eq(i).attr("rank_id");
			if($("input[parent_id='" + p_rank_id + "']").attr("name"))
			{
				tree_check(parent_id, p_rank_id, false);
			}
		});
	}
}

$.fn.checkCbx = function(type){ 
	return this.each(function(){
		this.checked = type;
	}); 
} 
</script>
</body>
</html>