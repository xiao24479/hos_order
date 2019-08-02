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
<script src="static/js/jquery.js"></script>
<script language="javascript">
function ajax_get_keshi(obj, hos_id, check_id)
{
	$(obj).next().after("<i class='icon-refresh icon-spin'></i>");
	$.ajax({
		type:'post',
		url:'?c=system&m=keshi_list_ajax',
		data:'hos_id=' + hos_id + '&type=1&check_id=' + check_id,
		success:function(data)
		{
			$(obj).next().html(data);
		},
		complete: function (XHR, TS)
		{
		   XHR = null;
		   $(obj).next().next(".icon-spin").remove();
		}
	});
}
 
function select_add(obj)
{
	var i_value = $(obj).children("i").attr("class");
	if(i_value == 'icon-plus')
	{
		var html = '<div class="control-group"><div class="controls"><select name="hos_id[]"  onChange="ajax_get_keshi(this, $(this).val(), 0);" class="hos_id"><option value="0"><?php echo $this->lang->line('please_select');?></option><?php foreach($hospital as $val){ ?><option value="<?php echo $val['hos_id'];?>"><?php echo $val['hos_name'];?></option><?php } ?>   </select>   <select name="keshi_id[]" class="keshi_id"><option value="0"><?php echo $this->lang->line('please_select');?></option></select> <button type="button" class="btn btn-danger btn_id" onClick="select_add(this);"><i class="icon-minus"></i></button>';
		$(obj).parent().parent().after(html);
	}
	else
	{
		$(obj).parent().parent().remove();
	}
}
</script>
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
  <form action="?c=index&m=rank_update" method="post" class="form-horizontal">
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('rank_name');?></label>
	<div class="controls">
		<input type="text" value="<?php echo $info['rank_name']; ?>" class="input-xlarge" name="rank_name" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('parent_id');?></label>
	<div class="controls">
	   <select name="parent_id">
	   <option value="0"><?php echo $this->lang->line('please_select');?></option>
	   <?php echo $rank_option; ?>
	   </select>
	</div>
</div> 
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('rank_type');?></label>
	<div class="controls">
	   <select name="rank_type"  >
	   <option value="0"><?php echo $this->lang->line('please_select');?></option>
	   <?php foreach($this->lang->line("rank_type_arr") as $key => $val){ ?>
	   <option value="<?php echo $key;?>" <?php if($key == $info['rank_type']){ echo "selected";}?>><?php echo $val;?></option>
	   <?php } ?>
	   </select>
	</div>
</div> 
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('rank_keshi');?></label>
	<?php if(empty($rank_power)):?>
    <div class="controls">
	   <select name="hos_id[]" class="hos_id">
	   <option value="0"><?php echo $this->lang->line('please_select');?></option>
	   <?php foreach($hospital as $val){ ?>
	   <option value="<?php echo $val['hos_id'];?>"><?php echo $val['hos_name'];?></option>
	   <?php } ?>
	   </select>
	   
	   <select name="keshi_id[]" class="keshi_id">
	   <option value="0"><?php echo $this->lang->line('please_select');?></option>
	   </select> <button type="button" class="btn btn-success btn_id" onClick="select_add(this);"><i class="icon-plus"></i></button>
    </div>
    <?php
    else:
	$i = 1;
	foreach($rank_power as $v):
	if($i != 1){ echo ' </div><div class="control-group">';}
	?>
    <div class="controls">
       <select name="hos_id[]" class="hos_id" id="hos_<?php echo $v['hos_id'];?>_<?php echo $v['keshi_id'];?>">
	   <option value="0"><?php echo $this->lang->line('please_select');?></option>
	   <?php foreach($hospital as $val){ ?>
	   <option value="<?php echo $val['hos_id'];?>" <?php if($val['hos_id'] == $v['hos_id']){ echo " selected";}?>><?php echo $val['hos_name'];?></option>
	   <?php } ?>
	   </select>
	   
	   <select name="keshi_id[]" class="keshi_id">
	   <option value="0"><?php echo $this->lang->line('please_select');?></option>
	   </select>
    <script language="javascript">
	   ajax_get_keshi("#hos_<?php echo $v['hos_id'];?>_<?php echo $v['keshi_id'];?>", <?php echo $v['hos_id'];?>, <?php echo $v['keshi_id'];?>);
	</script>
    <?php if($i == 1){ echo '<button type="button" class="btn btn-success btn_id" onClick="select_add(this);"><i class="icon-plus"></i></button>';}else{ echo '<button type="button" class="btn btn-danger btn_id" onClick="select_add(this);"><i class="icon-minus"></i></button>';}?>
	</div>
	<?php 
	$i ++;
	endforeach;
	endif;
	?>
</div>
<div class="control-group">
	<label class="control-label">限制登录</label>
	<div class="controls">
		<label class="radio1">
			<input type="radio" name="is_limit" value="1" <?php if($info['is_limit'] == '1'){ echo " checked";}?>>
			是（启用）
		</label>&nbsp;&nbsp;&nbsp;&nbsp;
		<label class="radio1">
			<input type="radio" name="is_limit" value="0" <?php if($info['is_limit'] == '0'){ echo " checked";}?>>
			否（禁用）
		</label>
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('rank_action');?></label>
	<div class="controls">
	<table cellpadding="0" cellspacing="0" class="action_list">
	<?php foreach($menu as $key => $item):?>
	<tr>
	   <td class="action_th"><label><input name="rank_action[]" type="checkbox" class="action_<?php echo $key; ?>_h" value="<?php echo $item['act_id'];?>" <?php if(in_array($item['act_id'], $info['rank_action'])){echo 'checked="checked"';}?>/><b><?php echo $item['act_name'];?></b></label></td>
	   <td><?php 
	   if(isset($item['son'])):
	   foreach($item['son'] as $k => $list):?>
	   <label style="width: 200px;"><input name="rank_action[]" type="checkbox" class="action_<?php echo $key; ?>_c" value="<?php echo $list['act_id'];?>" <?php if(in_array($list['act_id'], $info['rank_action'])){echo 'checked="checked"';}?>/><?php echo $list['act_name'];?></label>
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
	<label class="control-label">选择岗位管理员</label>
	<div class="controls" style="min-height:100px;overflow-y:auto; max-height:300px;">
	<table cellpadding="0" cellspacing="0" class="action_list"> 
	<tr>
	   <td><?php  
	   if(!empty($info['rank_user'])){
	   	  $rank_user = explode(",",$info['rank_user']);
	   }else{
	   	$rank_user = array();
	   } 
	   if(isset($user)):
	   foreach($user as $key => $list):?>
	   <label style="width: 200px;"><input name="user[]" <?php  if(!empty($info['rank_user']) && in_array("'".$list['admin_id']."'", $rank_user)){?> checked="checked"<?php }?> type="checkbox" class="action_<?php echo $key; ?>_c" value="<?php echo $list['admin_id'];?>"/><?php if(!empty($list['admin_name'])){echo $list['admin_name'].'-'.$list['admin_username'];}else{echo  $list['admin_username'];}?></label>
	   <?php
	   endforeach;
	   endif;
	   ?></td>
	</tr> 
	</table>
	</div>
</div>

<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('order');?></label>
	<div class="controls">
		<input type="text" value="<?php echo $info['rank_order']; ?>" class="input-xlarge" name="rank_order" />
	</div>

</div>
<div class="control-group">
	<div class="controls">
            <input type="hidden" name="form_action" value="update" /><input type="hidden" name="rank_id" value="<?php echo $info['rank_id']; ?>" /><input type="submit" name="submit" value="<?php echo $this->lang->line('submit'); ?>" class="btn btn-success"/>  <input name="reset" type="reset" value="<?php echo $this->lang->line('reset'); ?>" class="btn"/>
            &nbsp;<span style="color:lightcoral;"><img src="static/img/transfer.png" onclick="go_url('?c=index&m=update_user_rank&rank_id=<?php echo $info['rank_id'];?>');">同步</span>
	</div>
</div>
  </form>
  <?php else:?>
  <form action="?c=index&m=rank_update" method="post" class="form-horizontal">
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('rank_name');?></label>
	<div class="controls">
		<input type="text" value="" class="input-xlarge" name="rank_name" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('parent_id');?></label>
	<div class="controls">
	   <select name="parent_id">
	   <option value="0"><?php echo $this->lang->line('please_select');?></option>
	   <?php echo $rank_option; ?>
	   </select>
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('rank_type');?></label>
	<div class="controls">
	   <select name="rank_type"  >
	   <option value="0"><?php echo $this->lang->line('please_select');?></option>
	   <?php foreach($this->lang->line("rank_type_arr") as $key => $val){ ?>
	   <option value="<?php echo $key;?>"><?php echo $val;?></option>
	   <?php } ?>
	   </select>
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('rank_keshi');?></label>
	<div class="controls">
	   <select name="hos_id[]" class="hos_id">
	   <option value="0"><?php echo $this->lang->line('please_select');?></option>
	   <?php foreach($hospital as $val){ ?>
	   <option value="<?php echo $val['hos_id'];?>"><?php echo $val['hos_name'];?></option>
	   <?php } ?>
	   </select>
	   
	   <select name="keshi_id[]" class="keshi_id">
	   <option value="0"><?php echo $this->lang->line('please_select');?></option>
	   </select> <button type="button" class="btn btn-success btn_id" onClick="select_add(this);"><i class="icon-plus"></i></button>
    </div>
</div>
<div class="control-group">
	<label class="control-label">限制登录</label>
	<div class="controls">
		<label class="radio1">
			<input type="radio" name="is_limit" value="1">
			是（启用）
		</label>&nbsp;&nbsp;&nbsp;&nbsp;
		<label class="radio1">
			<input type="radio" name="is_limit" value="0" checked="checked">
			否（禁用）
		</label>
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('rank_action');?></label>
	<div class="controls"> 
	<table cellpadding="0" cellspacing="0" class="action_list">
	<?php foreach($menu as $key => $item):?>
	<tr>
	   <td class="action_th"><label><input name="rank_action[]" type="checkbox" class="action_<?php echo $key; ?>_h" value="<?php echo $item['act_id'];?>"/><b><?php echo $item['act_name'];?></b></label></td>
	   <td><?php 
	   if(isset($item['son'])):
	   foreach($item['son'] as $k => $list):?>
	   <label style="width: 200px;"><input name="rank_action[]" type="checkbox" class="action_<?php echo $key; ?>_c" value="<?php echo $list['act_id'];?>"/><?php echo $list['act_name'];?></label>
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
	<label class="control-label">选择岗位管理员</label>
	<div class="controls" style="min-height:100px;overflow-y:auto; max-height:300px;">
	<table cellpadding="0" cellspacing="0" class="action_list"> 
	<tr>
	   <td><?php  if(isset($user)):
	   foreach($user as $key => $list):?>
	   <label style="width: 200px;"><input name="user[]" type="checkbox" class="action_<?php echo $key; ?>_c" value="<?php echo $list['admin_id'];?>"/><?php if(!empty($list['admin_name'])){echo $list['admin_name'].'-'.$list['admin_username'];}else{echo  $list['admin_username'];}?></label>
	   <?php
	   endforeach;
	   endif;
	   ?></td>
	</tr> 
	</table>
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
	
	$(".hos_id").change(function(){
		var hos_id = $(this).val();
		ajax_get_keshi(this, hos_id, 0);
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