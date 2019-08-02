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
<link href="static/js/datepicker/css/datepicker.css" rel="stylesheet" />
<link href="static/css/clockface.css" rel="stylesheet" />
 
<style>
.a{ position:absolute; top:0; left:0; height:220px; width:100%;filter:alpha(Opacity=100);-moz-opacity:0.1;opacity: 0.1;z-index:100; background-color:#fff;}
.b{ position:absolute; top:250px; left:0; height:40px; width:100%;filter:alpha(Opacity=100);-moz-opacity:0.1;opacity: 0.1;z-index:100; background-color:#fff;}
<?php if(isset($order_data['data_time'])):?>
.b{ height:70px;}
<?php endif; ?>
.remark{height:auto; width:auto; overflow:hidden; position:relative; top:0px; left:0px;}
.black_overlay{
display: none;
position: absolute;
top: 0%;
left: 0%;
width: 100%;
height: 100%;
background-color: black;
z-index:1001;
-moz-opacity: 0.8;
opacity:.80;
filter: alpha(opacity=80);
}
.white_content {
display: none;
position: absolute;
top: 10%;
left: 10%;
width: 80%;
height: 90%;
border: 16px solid lightblue;
background-color: white;
z-index:1002;
overflow: auto;
}
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
<?php if(!empty($info)): ?>
<form onSubmit="return chkForm();" action="?c=order&m=order_update" method="post" class="form-horizontal" style="position:relative;">
    <input type="hidden" name="p" value="<?php echo $p; ?>">
<?php if($p == 1):?><input type="hidden" name="p" value="<?php echo $p; ?>"><div class="a"></div><div class="b"></div><?php endif;?>
<div class="row-fluid order_from">
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('order_no');?></label>
	<div class="controls" style="position:relative;">
		<input type="text" value="<?php echo $info['order_no'];?>" data-trigger="hover" class="input-large" name="order_no" readonly />&nbsp;<button type="button" id="yunzhou_btn" class="btn"> 孕周计算 </button>&nbsp;<button type="button" id="yuyue_btn" class="btn"> 预约卡 </button>
		<img id="img_suo" style="position: absolute;z-index: 1000;left: 30%;display: none;" src="">
	</div>
</div>
</div>
<div class="row-fluid">
<div class="span6 order_from">
	<div class="control-group order_from">
		<label class="control-label"><?php echo $this->lang->line('order_keshi');?></label>
		<div class="controls">
		   <select name="hos_id" id="hos_id">
		   <option value="0"><?php echo $this->lang->line('hospital_select');?></option>
		   <?php foreach($hospital as $val){ ?>
		   <option value="<?php echo $val['hos_id'];?>" <?php if($val['hos_id'] == $info['hos_id']){echo "selected";}?>><?php echo $val['hos_name'];?></option>
		   <?php } ?>
		   </select>
		   
		   <select name="keshi_id" id="keshi_id">
		   <option value="0"><?php echo $this->lang->line('keshi_select');?></option>
		   </select>
		</div>
	</div>
</div>
<div class="span5 order_from">
	<div class="control-group order_from">
		<label class="control-label"><?php echo $this->lang->line('type_name');?></label>
		<div class="controls">
		   <select name="type_id">
		   <option value="0"><?php echo $this->lang->line('please_select');?></option>
		   <?php foreach($type_list as $val){ ?>
		   <option value="<?php echo $val['type_id'];?>" <?php if($val['type_id'] == $info['type_id']){ echo "selected";}?>><?php echo $val['type_name'];?></option>
		   <?php } ?>
		   </select>
		</div>
	</div>
</div>
</div>
<div class="row-fluid">
<div class="span6 order_from">
          
	<div class="control-group order_from">
		<label class="control-label"><?php echo $this->lang->line('patient_jibing');?></label>
		<div class="controls">
		   <select name="jibing_parent_id" id="jibing_parent_id">
		   <option value="0"><?php echo $this->lang->line('jb_parent_select');?></option>
		   </select>
		   
		   <select name="jibing_id" id="jibing_id">
		   <option value="0"><?php echo $this->lang->line('jb_child_select');?></option>
		   </select>
		</div>                
              
	</div>

</div>
   
<div class="span5 order_from">
	<div class="control-group order_from">
		<label class="control-label"><?php echo $this->lang->line('patient_ask');?></label>
		<div class="controls">
		   <select name="admin_id" id="admin_id">
		   <option value="0"><?php echo $this->lang->line('please_select');?></option>
		   <?php foreach($asker_list as $val){ ?>
		   <option value="<?php echo $val['admin_id'];?>" <?php if($val['admin_id'] == $info['admin_id']){echo "selected";}?>><?php echo $val['admin_name'];?></option>
		   
                       <?php } ?>
		   </select>
		</div>
	</div>
</div>
</div>

<div class="row-fluid order_from">
<div class="control-group order_from">
	<label class="control-label"><?php echo $this->lang->line('from_name');?></label>
	<div class="controls">
	   <select name="from_parent_id" id="from_parent_id" >
	   <option value="0"><?php echo $this->lang->line('please_select');?></option>
	   <?php foreach($from_list as $val){ ?>
	   <option value="<?php echo $val['from_id'];?>" <?php if($val['from_id'] == $info['from_parent_id']){ echo "selected";}?>><?php echo $val['from_name'];?></option>
	   <?php } ?>
	   </select>
	   
	   <select name="from_id" id="from_id">
	   <option value="0"><?php echo $this->lang->line('please_select');?></option>
	   </select>
	   
	   <input type="text" name="from_value" id="from_value" value="<?php echo $info['from_value'];?>" class="input-xlarge" readonly  style="width:367px;"/>
	</div>
</div>
</div>
<div class="row-fluid">
<div class="span6 order_from">
	<div class="control-group order_from">
		<label class="control-label"><?php echo $this->lang->line('patient_name');?></label>
		<div class="controls">
			<input type="text" value="<?php echo $info['pat_name'];?>" class="input-large" id="patient_name" name="patient_name" />
            <select name="is_first" style="width:80px;">
            <option value="1" <?php if($info['is_first']){ echo "selected"; }?>>初诊</option>
            <option value="0" <?php if(!$info['is_first']){ echo "selected"; }?>>复诊</option>
            </select>
		</div>
	</div>
</div>
<div class="span5 order_from">
	<div class="control-group">
		<label class="control-label"><?php echo $this->lang->line('sex');?></label>
		<div class="controls">
		 <label class="radio1">
			<input type="radio" name="pat_sex" value="1" <?php if($info['pat_sex'] == 1):?>checked="checked"<?php endif;?>/>
			<?php echo $this->lang->line('man');?>
		</label>
		<label class="radio1">
			<input type="radio" name="pat_sex" value="2" <?php if($info['pat_sex'] == 2):?>checked="checked"<?php endif;?>/>
			<?php echo $this->lang->line('woman');?>
		</label>
	    </div>
	</div>
</div>
</div>
<div class="row-fluid">
<div class="span6 order_from">
	<div class="control-group order_from">
		<label class="control-label"><?php echo $this->lang->line('phone');?> </label> 
		<div class="controls">
			<input type="text" value="<?php echo $info['pat_phone']; if(!empty($info['pat_phone1'])){echo "/" . $info['pat_phone1'];}?>" data-trigger="hover" data-placement="right" data-content="【香港区号为：00852】【固定电话格式：075512345678】【手机号码前面不加0】" data-original-title="号码输入规则" class="input-large popovers" name="patient_phone" id="patient_phone" />
		</div>
	</div>
</div>
<div class="span5 order_from">
	<div class="control-group">
		<label class="control-label"><?php echo $this->lang->line('age');?></label>
		<div class="controls">
			<input type="text" value="<?php echo $info['pat_age'];?>" class="input-large" name="patient_age" style="width:205px;" />
	    </div>
	</div>
</div>
</div>
<div class="row-fluid order_from">
<div class="span6 order_from">
	<div class="control-group order_from">
		<label class="control-label"><?php echo $this->lang->line('order_time');?></label>
		<div class="controls">
			<div class="input-icon left">
				<i class="icon-time"></i>
				<?php if(!empty($info['order_time'])):?>
				<input type="text" name="order_time" class="input-large" value="<?php echo date("Y-m-d", $info['order_time']); ?>" id="order_time" style="width:95px;" />
				<input type="hidden" name="order_null_time" class="input-large" value="" id="order_null_time" style="width:95px;" />
				<select name="order_time_type" id="order_time_type" style="width:70px;">
				<option value="1" selected="selected"><?php echo $this->lang->line('time_define');?></option>
				<option value="2"><?php echo $this->lang->line('time_null');?></option>
				</select>
				<?php else:?>
				<input type="hidden" name="order_time" class="input-large" value="<?php echo date("Y-m-d", time()); ?>" id="order_time" style="width:95px;" />
				<input type="text" name="order_null_time" class="input-large" value="<?php echo $info['order_null_time']; ?>" id="order_null_time" style="width:95px;" />
				<select name="order_time_type" id="order_time_type" style="width:70px;">
				<option value="1"><?php echo $this->lang->line('time_define');?></option>
				<option value="2" selected="selected"><?php echo $this->lang->line('time_null');?></option>
				</select>
				<?php endif;?>
			</div>
		</div>
	</div>
</div>
<div class="span6 order_from">
	<div class="control-group order_from">
		<label class="control-label"><?php echo $this->lang->line('order_time_duan');?></label>
		<div class="controls">
		   <select name="order_time_duan_d" id="order_time_duan_d" style="width:135px;margin-right:5px;float:left;<?php if($info['duan_confirm'] == 2){ echo "display:none;";}?>">
		   <option value=""><?php echo $this->lang->line('please_select');?></option>
		   <?php foreach($this->lang->line('day_time') as $key=>$val){ ?>
		   <option value="<?php echo $val;?>" <?php if($val == $info['order_time_duan']){echo "selected";}?>><?php echo $val; ?></option>
		   <?php } ?>
		   </select>
           <div class="input-append" id="order_time_duan_j" style="margin-right:5px; <?php if($info['duan_confirm'] != 2){ echo "display:none;";}?> float:left;">
             <input type="text" style="width:87px; float:left;" name="order_time_duan_j" value="<?php if($info['duan_confirm'] == 2){ echo $info['order_time_duan'];}else{ echo "12:00";}?>">
             <div class="btn-group">
                <button data-toggle="dropdown" class="btn dropdown-toggle">
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu pull-right">
                    <li class="jzsj"><a href="#">08:00</a></li>
                    <li class="jzsj"><a href="#">08:30</a></li>
                    <li class="jzsj"><a href="#">09:00</a></li>
                    <li class="jzsj"><a href="#">09:30</a></li>
                    <li class="jzsj"><a href="#">10:00</a></li>
                     <li class="jzsj"><a href="#">10:30</a></li>
                    <li class="jzsj"><a href="#">11:00</a></li>
                     <li class="jzsj"><a href="#">11:30</a></li>
                    <li class="jzsj"><a href="#">12:00</a></li>
                     <li class="jzsj"><a href="#">12:30</a></li>
                    <li class="jzsj"><a href="#">13:00</a></li>
                     <li class="jzsj"><a href="#">13:30</a></li>
                    <li class="jzsj"><a href="#">14:00</a></li>
                     <li class="jzsj"><a href="#">14:30</a></li>
                    <li class="jzsj"><a href="#">15:00</a></li>
                     <li class="jzsj"><a href="#">15:30</a></li>
                    <li class="jzsj"><a href="#">16:00</a></li>
                     <li class="jzsj"><a href="#">16:30</a></li>
                    <li class="jzsj"><a href="#">17:00</a></li>
                     <li class="jzsj"><a href="#">17:30</a></li>
                      <li class="jzsj"><a href="#">18:00</a></li>
                    <li class="divider"></li>
                    <li class="zdy"><a href="#">自定义</a></li>
                </ul>
            </div>
           </div>
           <select name="duan_confirm" id="duan_confirm" style="width:80px;">
            <option value="1"<?php if($info['duan_confirm'] != 1){ echo " selected";}?>>大概</option>
            <option value="2"<?php if($info['duan_confirm'] == 2){ echo " selected";}?>>精确</option>
           </select>
		</div>
	</div>
</div>
</div>
<div class="row-fluid order_from" id="yunzhou_div"<?php if(!isset($order_data['data_time'])):?> style="display:none;"<?php endif; ?>>
<div class="control-group order_from">
	<label class="control-label">产科预约</label>
	<div class="controls">
        <input type="text" value="<?php if(isset($order_data['data_time'])){ echo date("Y-m-d", $order_data['data_time']);}else{echo date("Y-m-d");}?>" class="input-small" name="data_time" id="data_time" placeholder="末次月经时间"/> 
        已怀孕<input  type="text" value="" name="yunzhou" id="yunzhou" placeholder="0" style="width:24px; border: 0px; color: red; text-align: center; font-weight: bold;" />周，
        第<input type="text" value="" name="yunzhoutian" id="yunzhoutian" placeholder="0" style="width:24px; border: 0px; color: red; text-align: center; font-weight: bold;" />天，
        预产期是<input type="text" value="" class="input-large" name="yuchanqi" id="yuchanqi" placeholder="预产期"  style="border: 0px;" />
	</div>
</div>
</div>
<?php
if(($rank_type == 3) || ($_COOKIE['l_admin_action'] == 'all')):
	if($info['is_come'] >= 1):
?>
<div class="row-fluid order_from">
<div class="span6 order_from">
<div class="control-group">
	<label class="control-label">来院时间</label>
	<div class="controls">
		<input type="text" name="come_time" value="<?php if($info['is_come'] > 0){ echo date("Y-m-d H:i", $info['come_time']); }?>" data-trigger="hover" class="input-large" />
	</div>
</div>
</div>
<div class="span5 order_from">
<div class="control-group">
	<label class="control-label">接诊医生</label>
	<div class="controls">
		<input type="text" name="doctor_name" value="<?php echo $info['doctor_name'];?>" data-trigger="hover" class="input-large" />
	</div>
</div>
</div>
</div>
<?php
	endif;
endif;
?>
<div class="row-fluid order_from">
<div class="control-group order_from">
	<label class="control-label"><?php echo $this->lang->line('patient_address');?></label>
	<div class="controls">
		<select name="province" id="province" class="input-small m-wrap" style="width:150px;">
		   <option value="0"><?php echo $this->lang->line('please_select');?></option>
		   <?php foreach($province as $val){ ?>
		   <option value="<?php echo $val['region_id'];?>" <?php if($val['region_id'] == $info['pat_province']){echo "selected";}?>><?php echo $val['region_name']; ?></option>
		   <?php } ?>
		</select>
		<select name="city" id="city" class="input-small m-wrap" style="width:150px;">
		   <option value="0"><?php echo $this->lang->line('please_select');?></option>
		</select>
		<select name="area" id="area" class="input-small m-wrap" style="width:150px;">
		   <option value="0"><?php echo $this->lang->line('please_select');?></option>
		</select>
		<input type="text" value="<?php echo $info['pat_address'];?>" class="input-xlarge" name="patient_address" style="width:355px;"/>
	</div>
</div>
</div>
<div class="row-fluid order_from">
<div class="control-group order_from">
	<label class="control-label"><?php echo $this->lang->line('remark');?></label>
	<div class="controls remark">
<?php 
if(isset($remark)):
foreach($remark as $val):
if($val['mark_content'] != "1"):
	if($val['mark_type'] != 3):
?>
<blockquote <?php if($val['mark_type'] == 2){ echo ' class="d"';}elseif($val['mark_type'] == 1){ echo ' class="g"';}else{ echo ' class="r"';}?>>
<p><?php echo $val['mark_content']; ?></p>
<small><a href="###"><?php if($val['mark_type'] == 4){ echo '短信回复';} else{ echo $val['admin_name'];}?></a> <cite><?php echo date("m-d H:i", $val['mark_time']);?></cite></small>
</blockquote>
<?php else:?>
<blockquote>
<p><?php echo $val['mark_content']; ?></p>
<small><a href="###"><?php echo $val['admin_name'];?></a> <cite><?php echo date("m-d H:i", $val['mark_time']);?></cite></small>
</blockquote>
<?php 
	endif; 
endif; 
endforeach;
endif; 
?>
		<textarea class="input-xxlarge " rows="2" name="remark" style="width:820px; border:1px solid #00F;"></textarea>
	</div>
</div>
<div class="control-group order_from">
	<label class="control-label">对话记录</label>
	<div class="controls">
		<textarea class="input-xxlarge" rows="5" name="con_content" id="con_content" style="width:832px;"><?php echo $con_content; ?></textarea>
	</div>
</div>
</div>
<div class="row-fluid order_from">
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('sms_send');?></label>
	<div class="controls" style="height:30px; padding:0;">
		<label class="checkbox">
			<input type="checkbox" name="sms_send" id="sms_send" value="1" /> <?php echo $this->lang->line('yes');?>
        </label>
	</div>
</div>
</div>
<div id="sms" style="display:none;">
<div class="row-fluid order_from">
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('sms_themes');?></label>
	<div class="controls">
		<select name="sms_themes" id="sms_themes" class="input-small m-wrap" style="width:150px;">
		   <option value="0"><?php echo $this->lang->line('please_select');?></option>
		</select>
	</div>
</div>
</div>
<div class="row-fluid order_from">
<div class="control-group order_from">
	<label class="control-label"><?php echo $this->lang->line('sms_content');?></label>
	<div class="controls">
		<textarea class="input-xxlarge " rows="5" name="sms_content" id="sms_content" style="width:820px;"></textarea>
	</div>
</div>
</div>
</div>
<div class="control-group order_from">
	<div class="controls">
<input type="hidden" name="form_action" value="update" />
<input type="hidden" name="order_id" id="order_id" value="<?php echo $info['order_id']; ?>" />
<input type="hidden" name="pad_id" value="<?php echo $info['p_id']; ?>" />
<input type="hidden" name="p" value="<?php echo $p; ?>" />
<button type="submit" id="submit" class="btn btn-success"><i class="icon-ok"></i> <?php echo $this->lang->line('submit'); ?> </button>
<button type="reset" class="btn"><i class="icon-remove"></i> <?php echo $this->lang->line('reset'); ?> </button>
	</div>
</div>
  </form>
<?php else:?>
<form onSubmit="return chkForm();" action="?c=order&m=order_update" method="post" class="form-horizontal" style="position:relative;">
<div class="row-fluid order_from">
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('order_no');?></label>
	<div class="controls" style="position:relative;">
		<input type="text" value="" data-trigger="hover" data-placement="right" data-content="若不输入预约号，系统会自动生成新的预约号。之前生成的预约号，只要没有使用都可以继续使用！" data-original-title="预约号说明：" class="input-large popovers" name="order_no" id="order_no" /> <button type="button" id="get_order_no" class="btn btn-success"> <?php echo $this->lang->line('get_order_no'); ?> </button>&nbsp;<button type="button" id="yunzhou_btn" class="btn"> 孕周计算 </button>&nbsp;<button type="button" id="yuyue_btn" class="btn"> 预约卡 </button>
		&nbsp;<button type="button" onClick="show_card()" class="btn">隐藏/显示</button>
		<img id="img_suo" style="position: absolute;z-index: 1000;left: 30%;display: none;top:30px;" src="">
		<input type="hidden" value="" name="card" />
	</div>
</div>
</div>
<div class="row-fluid">
<div class="span6 order_from">
	<div class="control-group order_from">
		<label class="control-label"><?php echo $this->lang->line('order_keshi');?></label>
		<div class="controls">
		   <select name="hos_id" id="hos_id">
		   <option value="0"><?php echo $this->lang->line('hospital_select');?></option>
		   <?php foreach($hospital as $val){ ?>
		   <option value="<?php echo $val['hos_id'];?>"<?php if($val['hos_id'] == $l_hos_id){ echo " selected";}?>><?php echo $val['hos_name'];?></option>
		   <?php } ?>
		   </select>
		   
		   <select name="keshi_id" id="keshi_id">
		   <option value="0"><?php echo $this->lang->line('keshi_select');?></option>
		   </select>
		</div>
	</div>
</div>
<div class="span5 order_from">
	<div class="control-group order_from">
		<label class="control-label"><?php echo $this->lang->line('type_name');?></label>
		<div class="controls">
		   <select name="type_id">
		   <option value="0"><?php echo $this->lang->line('please_select');?></option>
		   <?php foreach($type_list as $val){ ?>
		   <option value="<?php echo $val['type_id'];?>"><?php echo $val['type_name'];?></option>
		   <?php } ?>
		   </select>
		</div>
	</div>
</div>
</div>
<div class="row-fluid">
<div class="span6 order_from">
	<div class="control-group order_from">
		<label class="control-label"><?php echo $this->lang->line('patient_jibing');?></label>
		<div class="controls">
		   <select name="jibing_parent_id" id="jibing_parent_id">
		   <option value="0"><?php echo $this->lang->line('jb_parent_select');?></option>
		   </select>
		   
		   <select name="jibing_id" id="jibing_id">
		   <option value="0"><?php echo $this->lang->line('jb_child_select');?></option>
		   </select>
		</div>
	</div>
</div>
<div class="span5 order_from">
	<div class="control-group order_from">
		<label class="control-label"><?php echo $this->lang->line('patient_ask');?></label>
		<div class="controls">
		   <select name="admin_id" id="admin_id">
		   <option value="0"><?php echo $this->lang->line('please_select');?></option>
		   <?php foreach($asker_list as $val){ ?>
		   <option value="<?php echo $val['admin_id'];?>"  <?php if($val['admin_id'] == $_COOKIE['l_admin_id']){ echo "selected";}?>><?php echo $val['admin_name'];?></option>
		   <?php } ?>
		   </select>
		</div>
	</div>
</div>
</div>
<div class="row-fluid order_from">
<div class="control-group order_from">
	<label class="control-label"><?php echo $this->lang->line('from_name');?></label>
	<div class="controls">
	   <select name="from_parent_id" id="from_parent_id">
	   <option value="0"><?php echo $this->lang->line('please_select');?></option>
	   <?php foreach($from_list as $val){ ?>
	   <option value="<?php echo $val['from_id'];?>"><?php echo $val['from_name'];?></option>
	   <?php } ?>
	   </select>
	   
	   <select name="from_id" id="from_id">
	   <option value="0"><?php echo $this->lang->line('please_select');?></option>
	   </select>
	   
	   <input type="text" name="from_value" id="from_value" value="" class="input-xlarge" readonly  style="width:367px;"/>
	</div>
</div>
</div>
<div class="row-fluid">
<div class="span6 order_from">
	<div class="control-group order_from">
		<label class="control-label"><?php echo $this->lang->line('patient_name');?></label>
		<div class="controls">
			<input type="text" value="" class="input-large" name="patient_name" id="patient_name" />
            <select name="is_first" style="width:80px;">
            <option value="1">初诊</option>
            <option value="0">复诊</option>
            </select>
		</div>
	</div>
</div>
<div class="span5 order_from">
	<div class="control-group">
		<label class="control-label"><?php echo $this->lang->line('sex');?></label>
		<div class="controls">
		 <label class="radio1">
			<input type="radio" name="pat_sex" value="1" <?php if($_COOKIE['l_rank_id'] == 59 || $_COOKIE['l_rank_id'] == 56 || $_COOKIE['l_rank_id'] == 53 || $_COOKIE['l_rank_id'] == 29 || $_COOKIE['l_rank_id'] == 16 || $_COOKIE['l_admin_action'] == 'all'){ echo "checked='checked'";}?>/>
			<?php echo $this->lang->line('man');?>
		</label>
		<label class="radio1">
			<input type="radio" name="pat_sex" value="2" <?php if($_COOKIE['l_rank_id'] == 31 || $_COOKIE['l_rank_id'] == 28){ echo "checked='checked'";}?>/>
			<?php echo $this->lang->line('woman');?>
		</label>
	    </div>
	</div>
</div>
</div>
<div class="row-fluid">
<div class="span6 order_from">
	<div class="control-group order_from">
		<label class="control-label">联系方式</label>
		<div class="controls">
                    <select name="contact" id="contact_type" style="width:100px;">
                        <option value="phone" selected="selected">手机/电话</option>
                        <option value="qq" >QQ</option>
                        <option value="weixin" >微信</option>
                    </select>
<!--			<input type="text" value="" data-trigger="hover" data-placement="right" data-content="【香港区号为：00852】【固定电话格式：075512345678】【手机号码前面不加0】【QQ号以数字填写】" data-original-title="联系方式输入规则" class="input-large popovers" name="patient_phone" id="patient_phone" />-->
		      <input type="text" value="" data-trigger="hover" data-placement="right" data-content="【香港区号为：00852】【固定电话格式：075512345678】【手机号码前面不加0】【QQ号以数字填写】" data-original-title="联系方式输入规则" class="input-large popovers" name="contact_input" id="contact_input" />
                </div>
	</div>
</div>
<div class="span5 order_from">
	<div class="control-group">
		<label class="control-label"><?php echo $this->lang->line('age');?></label>
		<div class="controls">
			<input type="text" value="" class="input-large" name="patient_age" id="patient_age" style="width:205px;" />
	    </div>
	</div>
</div>
</div>
<div class="row-fluid order_from">
<div class="span6 order_from">
	<div class="control-group order_from">
		<label class="control-label"><?php echo $this->lang->line('order_time');?></label>
		<div class="controls">
			<div class="input-icon left">
				<i class="icon-time"></i>
				<input type="text" name="order_time" class="input-large" value="<?php echo date("Y-m-d"); ?>" id="order_time" style="width:95px;" />
				<input type="hidden" name="order_null_time" class="input-large" value="" id="order_null_time" style="width:95px;" />
				<select name="order_time_type" id="order_time_type" style="width:70px;">
				<option value="1"><?php echo $this->lang->line('time_define');?></option>
				<option value="2"><?php echo $this->lang->line('time_null');?></option>
				</select>
			</div>
		</div>
	</div>
</div>
<div class="span6 order_from">
	<div class="control-group order_from">
		<label class="control-label"><?php echo $this->lang->line('order_time_duan');?></label>
		<div class="controls">
		   <select name="order_time_duan_d" id="order_time_duan_d" style="width:135px;margin-right:5px;float:left;">
		   <option value=""><?php echo $this->lang->line('please_select');?></option>
		   <?php foreach($this->lang->line('day_time') as $key=>$val){ ?>
		   <option value="<?php echo $val;?>"><?php echo $val; ?></option>
		   <?php } ?>
		   </select>
           <div class="input-append" id="order_time_duan_j" style="margin-right:5px; display:none; float:left;">
             <input type="text" style="width:87px; float:left;" name="order_time_duan_j" value="12:00">
             <div class="btn-group">
                <button data-toggle="dropdown" class="btn dropdown-toggle">
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu pull-right">
                    <li class="jzsj"><a href="#">08:00</a></li>
                    <li class="jzsj"><a href="#">08:30</a></li>
                    <li class="jzsj"><a href="#">09:00</a></li>
                    <li class="jzsj"><a href="#">09:30</a></li>
                    <li class="jzsj"><a href="#">10:00</a></li>
                    <li class="jzsj"><a href="#">10:30</a></li>
                    <li class="jzsj"><a href="#">11:00</a></li>
                    <li class="jzsj"><a href="#">11:30</a></li>
                    <li class="jzsj"><a href="#">12:00</a></li>
                    <li class="jzsj"><a href="#">12:30</a></li>
                    <li class="jzsj"><a href="#">13:00</a></li>
                    
                    <li class="jzsj"><a href="#">13:30</a></li>
                    <li class="jzsj"><a href="#">14:00</a></li>
                    <li class="jzsj"><a href="#">14:30</a></li>
                    <li class="jzsj"><a href="#">15:00</a></li>
                    <li class="jzsj"><a href="#">15:30</a></li>
                    <li class="jzsj"><a href="#">16:00</a></li>
                    <li class="jzsj"><a href="#">16:30</a></li>
                    <li class="jzsj"><a href="#">17:00</a></li>
                    <li class="jzsj"><a href="#">17:30</a></li>
                    <li class="jzsj"><a href="#">18:00</a></li>
                    <li class="divider"></li>
                    <li class="zdy"><a href="#">自定义</a></li>
                </ul>
            </div>
           </div>
           <select name="duan_confirm" id="duan_confirm" style="width:80px;">
            <option value="1">大概</option>
            <option value="2">精确</option>
           </select>
		</div>
	</div>
</div>
</div>
<div class="row-fluid order_from">
<div class="control-group order_from" id="yunzhou_div" style="display:none;">
	<label class="control-label">产科预约</label>
	<div class="controls">
        <input type="text" value="<?php echo date("Y-m-d");?>" class="input-small" name="data_time" id="data_time" placeholder="末次月经时间"/> 
        已怀孕<input  type="text" readonly="readonly" value="" name="yunzhou" id="yunzhou" placeholder="0" style="width:24px; border: 0px; color: red; text-align: center; font-weight: bold;" />周，
        第<input type="text" readonly="readonly" value="" name="yunzhoutian" id="yunzhoutian" placeholder="0" style="width:24px; border: 0px; color: red; text-align: center; font-weight: bold;" />天，
        预产期是<input type="text" readonly="readonly" value="" class="input-large" name="yuchanqi" id="yuchanqi" placeholder="预产期"  style="border: 0px;" />
	</div>
</div>
<div class="control-group order_from">
	<label class="control-label"><?php echo $this->lang->line('patient_address');?></label>
	<div class="controls">
		<select name="province" id="province" class="input-small m-wrap" style="width:150px;">
		   <option value="0"><?php echo $this->lang->line('please_select');?></option>
		   <?php foreach($province as $val){ ?>
		   <option value="<?php echo $val['region_id'];?>"><?php echo $val['region_name']; ?></option>
		   <?php } ?>
		</select>
		<select name="city" id="city" class="input-small m-wrap" style="width:150px;">
		   <option value="0"><?php echo $this->lang->line('please_select');?></option>
		</select>
		<select name="area" id="area" class="input-small m-wrap" style="width:150px;">
		   <option value="0"><?php echo $this->lang->line('please_select');?></option>
		</select>
		<input type="text" value="" class="input-xlarge" name="patient_address" style="width:355px;"/>
	</div>
</div>
</div>
<div class="row-fluid order_from">
<div class="control-group order_from">
	<label class="control-label"><?php echo $this->lang->line('remark');?></label>
	<div class="controls">
		<textarea class="input-xxlarge " rows="2" name="remark" style="width:820px; border:1px solid #00F;"></textarea>
	</div>
</div>
<div class="control-group order_from">
	<label class="control-label">对话记录</label>
	<div class="controls">
		<textarea class="input-xxlarge" rows="5" name="con_content" id="con_content" style="width:832px;"></textarea>
	</div>
</div>
</div>
<div class="row-fluid order_from">
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('sms_send');?></label>
	<div class="controls" style="height:30px; padding:0;">
		<label class="checkbox">
			<input type="checkbox" name="sms_send" id="sms_send" value="1" /> <?php echo $this->lang->line('yes');?>
        </label>
	</div>
</div>
</div>
<div id="sms" style="display:none;">
<div class="row-fluid order_from">
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('sms_themes');?></label>
	<div class="controls">
		<select name="sms_themes" id="sms_themes" class="input-small m-wrap" style="width:150px;">
		   <option value="0"><?php echo $this->lang->line('please_select');?></option>
		</select>
	</div>
</div>
</div>
<div class="row-fluid order_from">
<div class="control-group order_from">
	<label class="control-label"><?php echo $this->lang->line('sms_content');?></label>
	<div class="controls">
		<textarea class="input-xxlarge " rows="5" name="sms_content" id="sms_content" style="width:820px;"></textarea>
	</div>
</div>
</div>
</div>
<div class="control-group order_from">
	<div class="controls">
		<input type="hidden" name="form_action" value="add" />
		<input type="hidden" name="order_id" id="order_id" value="0" />
        <input type="hidden" name="p" value="<?php echo $p; ?>" />
		<button type="submit" id="submit" class="btn btn-success"><i class="icon-ok"></i> <?php echo $this->lang->line('submit'); ?> </button>
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
</div>
   <script src="static/js/jquery.js"></script>
   <script src="static/js/jquery.nicescroll.js" type="text/javascript"></script>
   <script type="text/javascript" src="static/js/jquery.slimscroll.min.js"></script>
   <script src="static/js/bootstrap.min.js"></script>
   <script type="text/javascript" src="static/js/bootstrap-datepicker.js"></script>
   <script charset="utf-8" src="static/editor/kindeditor.js"></script>
   <script charset="utf-8" src="static/js/clockface.js"></script>
<script charset="utf-8" src="static/editor/lang/zh_CN.js"></script>
   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="static/js/excanvas.js"></script>
   <script src="static/js/respond.js"></script>
   <![endif]-->
   <script src="static/js/common-scripts.js"></script>
   <script src="static/js/datepicker/js/datepicker.js"></script>
<script>
KindEditor.ready(function(K) {
	window.editor = K.create('#con_content', {
					resizeType : 1,
					allowPreviewEmoticons : false,
					allowImageUpload : false,
					items : [
						'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
						'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
						'insertunorderedlist', '|', 'emoticons', 'image', 'link', '|', 'fullscreen']
				});
});
$(document).ready(function(){
	/*$("input[type='text']").hover(function(){
		$(this).focus();
	});
	$("textarea").hover(function(){
		$(this).focus();
	});*/
	
	$("#yunzhou_btn").click(function(){
		$("#yunzhou_div").css("display", "block");
	});
//        $("#jibing_parent_id").click(function(){
//            
//            var aa=$(this).find('option:selected').text();
//            if(aa=='四维'){
//                var str="<a  onclick='open_window()'>&nbsp;&nbsp;&nbsp;&nbsp;四维排期表</a>";
//                $("#add_html").html(str);
//            }
//      }
//                
//                );
                  $("#submit").click(function(){
                       var aa=$("#hos_id").find('option:selected').text();
                      if(aa=='台州五洲生殖医院'||aa=='台州医院自媒体'){
                          var time=$("#order_time").val();
                          var time1=new Date(time);
                          var time2=new Date();
                          var end_time=time2.getTime()+30*24*60*60*1000;
                          var start_time=time2.getTime()-24*60*60*1000;
                          var dd=new Date(end_time);
                          if(time1.getTime()>end_time){
                              
                              alert("对不起！亲，您所输入的预到时间不能超过30天哦！");
                              return false;
                          }
                          if(time1.getTime()<start_time){
                              alert("对不起！亲，您所输入的预到时间不能是过去的时间哦！"); 
                              return false;
                          }
                           
                          
                      }
//                      var contact=$("#contact_input").val();
//                      if(contact==''){
//                          alert("对不起！联系方式不能为空，请输入联系方式！");
//                          return false;
//                      }
                  });
        
		$("#yuyue_btn").click(function(){
		
		var order_no = $('#order_no').val();
		if(order_no == ''){
			alert('预约单号不能为空');
			return false;
		}
		var patient_name = $('#patient_name').val();
		if(patient_name == ''){
			alert('病人姓名不能为空');
			return false;
		}
		var patient_age = $('#patient_age').val();
		if(patient_age == ''){
			alert('病人年龄不能为空');
			return false;
		}
		var patient_phone = $('#patient_phone').val();
		if(patient_phone == ''){
			alert('病人手机号不能为空');
			return false;
		}
		var order_time = $('#order_time').val();
		if(order_time == ''){
			alert('预定时间不能为空');
			return false;
		}
		var jibing_parent = $('#jibing_parent_id').find('option:selected').text();
		if(jibing_parent == '请选择大类病种...'){
			alert('请选择大病种');
			return false;
		}
		var hos_id = $("#hos_id").val();
		if(hos_id == 0){
			alert('请选择医院');
			return false;
		}
		var keshi_id = $("#keshi_id").val();
		var img = $('#img_suo').attr('src');
		$.ajax({
			type:'post',
			url:'?c=order&m=card_set_ajax',
			data:'hos_id=' + hos_id + '&keshi_id=' + keshi_id + '&order_no=' + order_no + '&patient_name=' + patient_name +　'&patient_age=' + patient_age + '&patient_phone=' + patient_phone + '&order_time=' + order_time + '&jibing_parent=' + jibing_parent + '&img='+ img,
			success:function(data)
			{
				if(data == '1'){
					alert('当前预约科室没有模版')
				}
				$("#img_suo").attr("src", data);
				$("input[name=card]").val(data);
				$("#img_suo").show();
			},
			complete: function (XHR, TS)
			{
				 XHR = null;
			}
		});
	});
	
	$("#yunzhou").click(function(){
		yunzhou();
	});
        $("#yunzhoutian").click(function(){
		yunzhoutian();
	});
	
	$(".jzsj").click(function(){
		var shijian = $(this).children("a").html();
		$("input[name=order_time_duan_j]").val(shijian);
	});
	
	$(".zdy").click(function(){
		$("input[name=order_time_duan_j]").val("");
		$("input[name=order_time_duan_j]").focus();
	});
	
	/*$('#order_time_duan_j').clockface({
        format: 'HH:mm',
        trigger: 'manual'
    });

    $('#order_time_duan_j').click(function (e) {
        e.stopPropagation();
        $('#order_time_duan_j').clockface('toggle');
    });*/
	
	$("#duan_confirm").change(function(){
		var c = $(this).val();
		if(c == 1)
		{
			$('#order_time_duan_d').css("display", "block");
			$('#order_time_duan_j').css("display", "none");
		}
		else
		{
			$('#order_time_duan_d').css("display", "none");
			$('#order_time_duan_j').css("display", "block");
		}
	});
	
	$("#data_time").change(function(){
		
	});
<?php if(!empty($info)):?>
ajax_from(<?php echo $info['from_parent_id']; ?>, <?php echo $info['from_id']; ?>);
ajax_get_keshi(<?php echo $info['hos_id']; ?>, <?php echo $info['keshi_id']; ?>);
ajax_get_jibing(<?php echo $info['keshi_id']; ?>, 0, <?php echo $info['jb_parent_id']; ?>);
ajax_get_jibing(<?php echo $info['keshi_id']; ?>, <?php echo $info['jb_parent_id']; ?>, <?php echo $info['jb_id']; ?>);
ajax_area('city', <?php echo $info['pat_province']; ?>, <?php echo $info['pat_city']; ?>, 3);
ajax_area('area', <?php echo $info['pat_city']; ?>, <?php echo $info['pat_area']; ?>, 3);
sms_themes_ajax();
<?php else:?>
ajax_get_keshi(<?php echo $l_hos_id; ?>);
<?php endif;?>
	$('#order_time').DatePicker({
		format:'Y-m-d',
		date: $('#order_time').val(),
		current: $('#order_time').val(),
		starts: 1,
		position: 'right',
		onBeforeShow: function(){
			$('#order_time').DatePickerSetDate($('#order_time').val(), true);
		},
		onChange: function(formated, dates){
			$('#order_time').val(formated);
			$('#order_time').DatePickerHide();
		}
	});
	
	$('#data_time').DatePicker({
		format:'Y-m-d',
		date: $('#data_time').val(),
		current: $('#data_time').val(),
		starts: 1,
		position: 'right',
		onBeforeShow: function(){
			$('#data_time').DatePickerSetDate($('#data_time').val(), true);
		},
		onChange: function(formated, dates){
			$('#data_time').val(formated);
			$('#data_time').DatePickerHide();
		}
	});

	$("#from_parent_id").change(function(){
		var parent_id = $(this).val();
		ajax_from(parent_id);
	});
	
	$("#hos_id").change(function(){
		var hos_id = $(this).val();
		ajax_get_keshi(hos_id, 0);
		ajax_get_jibing(0, 0, 0);
		ajax_get_jibing(0, -1, 0);
		sms_themes_ajax();
	});
	
	$("#keshi_id").change(function(){
		var keshi_id = $(this).val();
		ajax_get_jibing(keshi_id, 0, 0);
		sms_themes_ajax();
	});
	
	$("#jibing_parent_id").change(function(){
		var parent_id = $(this).val();
		ajax_get_jibing(0, parent_id, 0);
	});
	
	$("#province").change(function(){
		var province_id = $(this).val();
		ajax_area('city', province_id, 0, 2);
	});
	
	$("#city").change(function(){
		var city_id = $(this).val();
		ajax_area('area', city_id, 0, 3);
	});
	
//	$("#patient_phone").focusout(function(){
//		var phone = CtoH($(this).val());
//		$("#patient_phone").val(phone);
//
//		if(phone != "")
//		{
//			$("#patient_phone").after("<i class='icon-refresh icon-spin'></i>");
//			$.ajax({
//				type:'post',
//				url:'?c=order&m=phone_ajax',
//				data:'phone=' + phone,
//				success:function(data)
//				{
//					data = $.parseJSON(data);
//					type = data['type'];
//					if(type == 0)
//					{
//						$("#patient_phone").next("i").remove();
//						$("#patient_phone").next("span").remove();
//						$("#patient_phone").parent().parent().addClass("error");
//						$("#patient_phone").after('<span class="help-inline">请输入号码</span>');
//					}
//					else if(type == 1)
//					{
//						$("#patient_phone").next("i").remove();
//						$("#patient_phone").next("span").remove();
//						$("#patient_phone").parent().parent().addClass("error");
//						$("#patient_phone").after('<span class="help-inline">号码格式错误</span>');
//					}
//					else if(type == 2)
//					{
//						var city_id = data['info']['region_id'];
//						var province_id = data['info']['parent_id'];
//
//						$("#province option").removeAttr("selected");
//						$("#province option[value='" + province_id + "']").prop("selected",true);
//						
//						ajax_area('city', province_id, city_id, 2);
//						$("#patient_phone").next("i").remove();
//						$("#patient_phone").next("div").remove();
//						$("#patient_phone").parent().parent().removeClass("error");
//						
//						if(data['over'] != "")
//						{
//							var html = '&nbsp;<div class="btn-group"><button data-toggle="dropdown" class="btn btn-danger dropdown-toggle">当前号码预约过 <span class="caret"></span></button><ul class="dropdown-menu">';
//							$.each(data['over'], function(key, value){
//								html += '<li><a href="#">患者姓名：<font color="red">' + value.pat_name + '</font>、医院：<font color="red">' + value.hos_name + '</font>、预约号：<font color="red">' + value.order_no + '</font>、咨询员：<font color="red">' + value.admin_name + '</font>、登记时间：<font color="red">' + value.addtime + '</font></a></li>';
//							});
//							html += '</ul></div>';
//							$("#patient_phone").after(html);
//						}
//					}
//				},
//				complete: function (XHR, TS)
//				{
//				   XHR = null;
//				}
//			});
//		}
//	});
$("#contact_input").focusout(function(){
    if($("#contact_type").val()=='phone'){
		var phone = CtoH($(this).val());
		$("#contact_input").val(phone);

		if(phone != "")
		{
			$("#contact_input").after("<i class='icon-refresh icon-spin'></i>");
			$.ajax({
				type:'post',
				url:'?c=order&m=phone_ajax',
				data:'phone=' + phone,
				success:function(data)
				{
					data = $.parseJSON(data);
					type = data['type'];
					if(type == 0)
					{
						$("#contact_input").next("i").remove();
						$("#contact_input").next("span").remove();
						$("#contact_input").parent().parent().addClass("error");
						$("#contact_input").after('<span class="help-inline">请输入号码</span>');
					}
					else if(type == 1)
					{
						$("#contact_input").next("i").remove();
						$("#contact_input").next("span").remove();
						$("#contact_input").parent().parent().addClass("error");
						$("#contact_input").after('<span class="help-inline">号码格式错误</span>');
					}
					else if(type == 2)
					{
						var city_id = data['info']['region_id'];
						var province_id = data['info']['parent_id'];

						$("#province option").removeAttr("selected");
						$("#province option[value='" + province_id + "']").prop("selected",true);
						
						ajax_area('city', province_id, city_id, 2);
						$("#contact_input").next("i").remove();
						$("#contact_input").next("div").remove();
						$("#contact_input").parent().parent().removeClass("error");
						
						if(data['over'] != "")
						{
							var html = '&nbsp;<div class="btn-group"><button data-toggle="dropdown" class="btn btn-danger dropdown-toggle">当前号码预约过 <span class="caret"></span></button><ul class="dropdown-menu">';
							$.each(data['over'], function(key, value){
								html += '<li><a href="#">患者姓名：<font color="red">' + value.pat_name + '</font>、医院：<font color="red">' + value.hos_name + '</font>、预约号：<font color="red">' + value.order_no + '</font>、咨询员：<font color="red">' + value.admin_name + '</font>、登记时间：<font color="red">' + value.addtime + '</font></a></li>';
							});
							html += '</ul></div>';
							$("#contact_input").after(html);
						}
					}
				},
				complete: function (XHR, TS)
				{
				   XHR = null;
				}
			});
		}
                }else{
                alert('meishi');
                }
	});
	
	$("#get_order_no").click(function(){
		$("#order_no").next("i").remove();
		$("#order_no").next("span").remove();
		$("#order_no").parent().parent().removeClass("error");
		$.ajax({
			type:'post',
			url:'?c=order&m=order_no_ajax',
			data:'',
			success:function(data)
			{
				$("#order_no").val(data);
			},
			complete: function (XHR, TS)
			{
			   XHR = null;
			}
		});
	});
	
	$("#order_no").focusout(function(){
		$("#submit").attr("disabled", false);
		var order_no = $(this).val();
		if(order_no != '')
		{
			$("#order_no").after("<i class='icon-refresh icon-spin'></i>");
			$.ajax({
				type:'post',
				url:'?c=order&m=use_no_ajax',
				data:'order_no=' + order_no,
				success:function(data)
				{
					if(data == 1)
					{
						$("#order_no").next("i").remove();
						$("#order_no").next("span").remove();
						$("#order_no").parent().parent().addClass("error");
						$("#order_no").after('<span class="help-inline">此预约号已使用</span>');
						$("#submit").attr("disabled", true);
					}
					else if(data == 2)
					{
						$("#order_no").next("i").remove();
						$("#order_no").next("span").remove();
						$("#order_no").parent().parent().addClass("error");
						$("#order_no").after('<span class="help-inline">预约号不正确</span>');
					}
					else
					{
						$("#order_no").next("i").remove();
						$("#order_no").next("span").remove();
						$("#order_no").parent().parent().removeClass("error");
					}
				},
				complete: function (XHR, TS)
				{
				   XHR = null;
				}
			});
		}
	});
	
	$("#order_time_type").change(function(){
		var order_time_type = $(this).val();
		if(order_time_type == 1)
		{
			$("#order_time").attr("type", "text");
			$("#order_null_time").attr("type", "hidden");
		}
		else if(order_time_type == 2)
		{
			$("#order_time").attr("type", "hidden");
			$("#order_null_time").attr("type", "text");
		}
	});
	
	//  预约途径 ID 
	$("#from_value").focusout(function(){
		//$("#from_value").next("a").remove();
		var from_parent_id = $("#from_parent_id").val();
		var from_value = $("#from_value").val();
		if(from_parent_id == 1) // 如果是商务通，则调取商务通对话
		{
			$.ajax({
				type:'post',
				url:'?c=order&m=kefu_talk',
				data:'type=1&from_value=' + from_value,
				success:function(data)
				{
					data = $.parseJSON(data);
					html = data['str'];
					gid = data['gid_str'];
					$("#con_content").html(html);
					editor.html(html);
					//$("#from_value").after(" <a href=\"javascript:order_window('');\" class=\"btn btn-info\"><i class='icon-hand-right'></i></a>");
				},
				complete: function (XHR, TS)
				{
				   XHR = null;
				}
			});
		}
	});
	
	$("#sms_send").click(function(){
		$("#sms").slideToggle(200);
	});
	
	$("#sms_themes").change(function(){
		var themes_id = $(this).val();
		if(themes_id > 0)
		{
			var pat_name = $("#patient_name").val();
			var pat_phone = $("#patient_phone").val();
			var order_time = $("#order_time").val();
			var order_null_time = $("#order_null_time").val();
			var order_time_type = $("#order_time_type").val();
			var order_time_duan = $("#order_time_duan").val();
			var order_no = $("#order_no").val();
			var order_id = $("#order_id").val();
			$("#sms_themes").after("<i class='icon-refresh icon-spin'></i>");
			
			$.ajax({
				type:'post',
				url:'?c=order&m=sms_ajax',
				data:'pat_name=' + pat_name + '&pat_phone=' + pat_phone + '&order_time=' + order_time + '&order_null_time=' + order_null_time + '&order_time_type=' + order_time_type + '&order_no=' + order_no + '&order_id=' + order_id + '&themes_id=' + themes_id + '&order_time_duan=' + order_time_duan,
				success:function(data)
				{
					$("#sms_content").html(data);
				},
				complete: function (XHR, TS)
				{
				   XHR = null;
				   $("#sms_themes").next(".icon-spin").remove();
				}
			});
		}
	});
});
<?php if(isset($order_data['data_time'])):?>
yunzhou();
<?php endif; ?>
function CloseDiv(show_div,bg_div)
	{
		$('#MyDiv').css("display", "none");

		$('#fade').css("display", "none");
	}
// 验证
function chkForm()
{
	if($("#keshi_id").val() == 0)
	{
		alert("请选择科室！");
		return false;
	}
	/*else if($("#jibing_parent_id").val() == 0)
	{
		alert("请选择病种！");
		return false;
	}*/
	else if($("#hos_id").val() == 0)
	{
		alert("请选择医院！");
		return false;
	}
	else if($("#admin_id").val() == 0)
	{
		alert("请选择咨询员！");
		return false;
	}
	else if($("#patient_name").val() == "")
	{
		alert("请输入患者姓名！");
		return false;
	}
	
	var jb = $("#jibing_parent_id").val();
	
	/* 检查四维预约时间是否重复 */
	if(jb == 149)
	{
		var ok = false;
		var order_id = $("#order_id").val();
		var hos_id = $("#hos_id").val();
		var order_time = $("#order_time").val();
		var order_time_duan_j = $("input[name='order_time_duan_j']").val();
		$.ajax({
			type:'post',
			url:'?c=order&m=siwei',
			data:'hos_id=' + hos_id + '&order_time=' + order_time + "&order_time_duan_j=" + order_time_duan_j + "&order_id=" + order_id,
			async : false,
			success:function(data)
			{
				if(data == '1')
				{
					ok = false;
				}
				else
				{
					ok = true;
				}
			},
			complete: function (XHR, TS)
			{
			   XHR = null;
			}
		});
		
		if(ok)
		{
			return true;
		}
		else
		{
			alert('您预约的四维时段已经预约过了，请重新选择！');
			return false;
		}
	}
	else
	{
		return true;
	}
}
// 计算孕周
function yunzhou()
{
	var moci = transdate($("#data_time").val());
	var timestamp = (Date.parse(new Date())) / 1000;
	var yuntime = (timestamp - moci);
	var yunzhou = parseInt(yuntime / (60 * 60 * 24 * 7)) + 1;
        var yunzhoutian = parseInt(yuntime / (60 * 60 * 24));
	var yuchanqi = getdate(moci + (280 * 24 * 60 * 60));
	$("#yunzhou").val(yunzhou);
        $("#yunzhoutian").val(yunzhoutian);
	$("#yuchanqi").val(yuchanqi.substr(0, 10));
}

// 时间戳转换时间
function getdate(tm){
	var tt=new Date(parseInt(tm) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ")
	return tt;
}

//时间转换时间戳
function transdate(endTime){
	var date=new Date();
	date.setFullYear(endTime.substring(0,4));
	date.setMonth(endTime.substring(5,7)-1);
	date.setDate(endTime.substring(8,10));
	date.setHours(endTime.substring(11,13));
	date.setMinutes(endTime.substring(14,16));
	date.setSeconds(endTime.substring(17,19));
	return Date.parse(date)/1000;
}

function sms_themes_ajax()
{
	var hos_id = $("#hos_id").val();
	var keshi_id = $("#keshi_id").val();
	
	if(hos_id > 0 || keshi_id > 0)
	{
		$("#sms_themes").after("<i class='icon-refresh icon-spin'></i>");
		$.ajax({
			type:'post',
			url:'?c=order&m=sms_themes_ajax',
			data:'hos_id=' + hos_id + '&keshi_id=' + keshi_id,
			success:function(data)
			{
				$("#sms_themes").html(data);
			},
			complete: function (XHR, TS)
			{
			   XHR = null;
			   $("#sms_themes").next(".icon-spin").remove();
			}
		});
	}
}

function ajax_from(parent_id, from_id)
{
	$("#from_value").after("<i class='icon-refresh icon-spin'></i>");
		
	if(parent_id == 1)
	{
		$("#from_value").attr("readonly", false);
		$("#from_value").attr("placeholder", "请输入商务通访客唯一ID号");
	}
	else if(parent_id == 2)
	{
		$("#from_value").attr("readonly", false);
		$("#from_value").attr("placeholder", "请输入患者QQ号");
	}
	else if(parent_id == 3)
	{
		$("#from_value").attr("readonly", false);
		$("#from_value").attr("placeholder", "请输入百度商桥访客唯一身份ID");
	}
	else if(parent_id == 4)
	{
		$("#from_value").attr("readonly", true);
		$("#from_value").attr("placeholder", "");
	}
	else if(parent_id == 15)
	{
		$("#from_value").attr("readonly", false);
		$("#from_value").attr("placeholder", "请输入患者的微信号");
	}
	else if(parent_id == 12)
	{
		$("#from_value").attr("readonly", true);
		$("#from_value").attr("placeholder", "");
	}
	else if(parent_id == 11)
	{
		$("#from_value").attr("readonly", true);
		$("#from_value").attr("placeholder", "");
	}
	else if(parent_id == 23)
	{
		$("#from_value").attr("readonly", false);
		$("#from_value").attr("placeholder", "输入备注信息");
	}
	$.ajax({
		type:'post',
		url:'?c=order&m=from_order_ajax',
		data:'parent_id=' + parent_id + '&from_id=' + from_id,
		success:function(data)
		{
		   $("#from_id").html(data);
		},
		complete: function (XHR, TS)
		{
		   XHR = null;
		   $("#from_value").next(".icon-spin").remove();
		}
	});
}

function ajax_area(area_name, parent_id, check_id, type)
{
	$("#patient_address").after("<i class='icon-refresh icon-spin'></i>");
	$.ajax({
		type:'post',
		url:'?c=system&m=area_ajax',
		data:'parent_id=' + parent_id + '&check_id=' + check_id,
		success:function(data)
		{
			$("#" + area_name).html(data);
			
			if(type == 2)
			{
				ajax_area('area', check_id, 0, 3);
			}
		},
		complete: function (XHR, TS)
		{
		   XHR = null;
		   $("#patient_address").next(".icon-spin").remove();
		}
	});
}

function ajax_get_jibing(keshi_id, parent_id, check_id)
{
	$("#jibing_id").after("<i class='icon-refresh icon-spin'></i>");
	$.ajax({
		type:'post',
		url:'?c=order&m=jibing_ajax',
		data:'keshi_id=' + keshi_id + '&parent_id=' + parent_id + '&check_id=' + check_id,
		success:function(data)
		{
			if(parent_id == 0)
			{
				$("#jibing_parent_id").html(data);
			}
			else
			{
				$("#jibing_id").html(data);
			}
			
		},
		complete: function (XHR, TS)
		{
		   XHR = null;
		   $("#jibing_id").next(".icon-spin").remove();
		}
	});
}

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

function show_card()
{
	var src = $("#img_suo").attr('src');
	if(src == ''){
		return false;
	}
	if($("#img_suo").is(":hidden")==false){
		$("#img_suo").hide();
	}else{
		$("#img_suo").show();
	}

}

/* 全角字符转半角 */
function CtoH(str){ 
　　var result="";
　　for (var i = 0; i < str.length; i++){
　　　if (str.charCodeAt(i)==12288){
　　　　result+= String.fromCharCode(str.charCodeAt(i)-12256);
　　　　continue;
　　　}
　　　if (str.charCodeAt(i)>65280 && str.charCodeAt(i)<65375) result+= String.fromCharCode(str.charCodeAt(i)-65248);
　　　else result+= String.fromCharCode(str.charCodeAt(i));
　　}
　　return result;
} 
</script>
</body>
</html>