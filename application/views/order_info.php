<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8" />
<title>添加预约<?php echo $admin['name'] . '-' . $title; ?></title>
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
 <script type="text/javascript"    >
        function open_window(){

            window.open('?c=order&m=siwei_show_window','newwindow','width=1000,top=100,left=100,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no')

        }

        </script>
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
border: 16px solid #00a186;
background-color: white;
z-index:1002;
overflow: auto;
}
.widget-title{

  background-color: #00a186;

}
</style>
</head>

<body class="fixed-top">
<?php
	$dy = $this->config->item('renfi_fk_by_day_time');
  	if(!is_numeric($dy)){
  		$dy = 7;
  	}else if(floor($dy) == 0){
  		$dy = 7;
  	}else if(floor($dy) != $dy){
  		$dy = 7;
  	}
  	?>
  <!--  仁爱妇科 不孕流入公海 预到时间+指定天数 -->
<input type="hidden" id="renfi_fk_by_day_time" value="<?php echo $dy;?>">


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
		  <div class="widget green" style="border-color:#e7e7e7;">
<!--                      <div class="widget-title" style="background-color:#00a186;">
					<h4><i class="icon-reorder"></i> <?php echo $this->lang->line('content_form'); ?></h4>
					<span class="tools">
						<a href="javascript:;" class="icon-chevron-down"></a>
						<a href="javascript:;" class="icon-remove"></a>
					</span>
					</div>-->
					<div class="widget-body">
<?php if(!empty($info)): ?>
<!-- 系统添加时间 -->
<input type="hidden" id="order_add_time_check" value="<?php echo date("Y-m-d H:i:s", $info['order_addtime']);?>"/>
<!-- 第一次预到时间 -->
<?php
$yd_time = 0;
$yd_sum = 0;
if(isset($info_yd_time) && !empty($info_yd_time['order_time'])){
	$yd_time = date("Y-m-d H:i:s", $info_yd_time['order_time']);
	$yd_sum = $info_yd_time['sum'];;
}
if(empty($yd_time)){
	$yd_time = 0;
	$yd_sum = 0;
}
?>
<input type="hidden" id="yd_time_check" value="<?php echo $yd_time;?>"/>
<input type="hidden" id="yd_sum_check" value="<?php echo $yd_sum;?>"/>

<!--  操作者 -->
<input type="hidden" id="yd_admin_id_check" value="<?php echo $_COOKIE['l_admin_id'];?>"/>
<!--  当前操作角色 -->
<input type="hidden" id="l_rank_id_check" value="<?php echo $_COOKIE['l_rank_id'];?>"/>
<!--  当前所属咨询员 -->
<input type="hidden" id="admin_id_check" value="<?php echo $info['admin_id'];?>"/>
<!-- 科室ID -->
<input type="hidden" id="keshi_old_id_check"  value="<?php echo $info['keshi_id'];?>">

<form onSubmit="return chkForm();" action="?c=order&m=order_update" method="post" class="form-horizontal" style="position:relative;">
    <input type="hidden" name="p" id="p" value="<?php echo $p; ?>">
<?php if($p == 1):?><input type="hidden" name="p" id="p"  value="<?php echo $p; ?>"><div class="a"></div><div class="b"></div><?php endif;?>
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
			   <?php }?>
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
		<label class="control-label"><?php echo $this->lang->line('phone');?></label>
		<div class="controls">

            <?php //咨询只能看自己的电话 其他电话不可见
$keshi_check_ts = $this->config->item('keshi_check_ts');
$keshi_check_ts = explode(",",$keshi_check_ts);
$zixun_check_ts = $this->config->item('zixun_check_ts');
$zixun_check_ts = explode(",",$zixun_check_ts);
			if(false){
			if(in_array($_COOKIE["l_rank_id"], $zixun_check_ts) && $rank_type == 2 && $info['hos_id'] == 3 && in_array($info['keshi_id'], $keshi_check_ts)){
				if($_COOKIE['l_admin_id'] != $info['admin_id']){
					$info['pat_phone']  =  $info['pat_phone'][0].$info['pat_phone'][1].$info['pat_phone'][2].'*****';
					$info['pat_phone1']  =  $info['pat_phone1'][0].$info['pat_phone1'][1].$info['pat_phone1'][2].'*****';
					$info['pat_qq'] = "*****";
					$info['pat_weixin'] = "*****";
				}
			}}?>
		    <input type="hidden" value="<?php echo $info['pat_phone']; if(!empty($info['pat_phone1'])){echo "/" . $info['pat_phone1'];}?>"   name="defult_patient_phone"  id="defult_patient_phone"/>

			   <?php if(empty($info['pat_phone'])){ ?>
			   <input type="text" value="" data-trigger="hover" data-placement="right" data-content="【香港区号为：00852】【固定电话格式：075512345678】【手机号码前面不加0】" data-original-title="号码输入规则" class="input-large popovers" name="patient_phone" id="patient_phone" />

	    <?php  }else if(!isset($order_edit_person_info)){ ?>

               <input type="hidden" value="<?php echo $info['pat_phone']; if(!empty($info['pat_phone1'])){echo "/" . $info['pat_phone1'];}?>"  name="patient_phone" id="patient_phone" />
               <?php echo $info['pat_phone']; if(!empty($info['pat_phone1'])){echo "/" . $info['pat_phone1'];}?>

		   <?php }else{ ?>
                <input type="text" value="<?php echo $info['pat_phone']; if(!empty($info['pat_phone1'])){echo "/" . $info['pat_phone1'];}?>" data-trigger="hover" data-placement="right" data-content="【香港区号为：00852】【固定电话格式：075512345678】【手机号码前面不加0】" data-original-title="号码输入规则" class="input-large popovers" name="patient_phone" id="patient_phone" />
           <?php }?>

	       <!--  检查当前修改信息的是否可以修改 电话号码 ，无权修改的不检测电话号码 -->
           <input type="hidden" id="order_edit_person_info" value="<?php echo $order_edit_person_info;?>">

             <!-- 电话检查 为0的时候正常提交，不为0的时候 需要检查当前号码是否已经存在，如果存在重复则需要判断是否在两个月以内，是则不能提交信息 -->
            <input type="hidden" id="phone_check_month" value="0">

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

<div class="row-fluid">
<div class="span6 order_from">
	<div class="control-group order_from">
		<label class="control-label">QQ</label>
		<div class="controls">
		<input type="text" value="<?php echo $info['pat_qq'];?>" class="input-large" name="patient_qq" style="width:205px;" />
		</div>
	</div>
</div>
<div class="span5 order_from">
	<div class="control-group">
		<label class="control-label">微信</label>
		<div class="controls">
			<input type="text" value="<?php echo $info['pat_weixin'];?>" class="input-large" name="patient_weixin" style="width:205px;" />
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
			 <?php if($info['keshi_id'] == 32 || $info['keshi_id'] == 1){?>
						<?php if(!empty($info['order_time'])):?>
						<input  type="text" name="order_time" class="input-large" value="<?php echo date("Y-m-d", $info['order_time']); ?>" id="order_time" style="width:95px;" />
						<input   echo date("Y-m-d", $info['order_time']);?>"/>
						<input  type="hidden" id="order_hidden_time_duan_j" value="<?php if($info['duan_confirm'] == 2){ echo $info['order_time_duan'];}else{ echo "12:00";}?>"/>

						<input  type="hidden" name="order_null_time" class="input-large" value="" id="order_null_time" style="width:95px;" />
						<select  name="order_time_type" id="order_time_type" style="width:70px;">
						<option value="1" selected="selected"><?php echo $this->lang->line('time_define');?></option>
						</select>
						<?php else:?>
						<input  type="hidden" name="order_time" class="input-large" value="<?php echo date("Y-m-d", time()); ?>" id="order_time" style="width:95px;" />

						  <input  type="hidden" id="order_hidden_time" value="<?php echo date("Y-m-d",time());?>"/>

		                   <input  type="hidden" id="order_hidden_time_duan_j" value="<?php if($info['duan_confirm'] == 2){ echo $info['order_time_duan'];}else{ echo "12:00";}?>"/>


						<input  type="text" name="order_null_time" class="input-large" value="<?php echo $info['order_null_time']; ?>" id="order_null_time" style="width:95px;" />
						<select  name="order_time_type" id="order_time_type" style="width:70px;">
						<option value="1"><?php echo $this->lang->line('time_define');?></option>
						</select>
						<?php endif;?>
						 <color id="order_time_type_msg" style="color:red;"><!-- <?php if($yd_time != 0 ){?>修改次数剩余:<?php echo $yd_sum;?>次;最大预到时间:<?php   echo $yd_time;}?>--></color>
				<?php }else{?>
						<?php if(!empty($info['order_time'])):?>
						<input type="text" name="order_time" class="input-large" value="<?php echo date("Y-m-d", $info['order_time']); ?>" id="order_time" style="width:95px;" />
						 <input type="hidden" id="order_hidden_time" value="<?php echo date("Y-m-d", $info['order_time']);?>"/>

		                  <input type="hidden" id="order_hidden_time_duan_j" value="<?php if($info['duan_confirm'] == 2){ echo $info['order_time_duan'];}else{ echo "12:00";}?>"/>

						<input type="hidden" name="order_null_time" class="input-large" value="" id="order_null_time" style="width:95px;" />
						<select name="order_time_type" id="order_time_type" style="width:70px;">
						<option value="1" selected="selected"><?php echo $this->lang->line('time_define');?></option>
						<option value="2"><?php echo $this->lang->line('time_null');?></option>
						</select>
						<?php else:?>
						<input type="hidden" name="order_time" class="input-large" value="<?php echo date("Y-m-d", time()); ?>" id="order_time" style="width:95px;" />

						  <input type="hidden" id="order_hidden_time" value="<?php echo date("Y-m-d",time());?>"/>

		                   <input type="hidden" id="order_hidden_time_duan_j" value="<?php if($info['duan_confirm'] == 2){ echo $info['order_time_duan'];}else{ echo "12:00";}?>"/>


						<input type="text" name="order_null_time" class="input-large" value="<?php echo $info['order_null_time']; ?>" id="order_null_time" style="width:95px;" />
						<select name="order_time_type" id="order_time_type" style="width:70px;">
						<option value="1"><?php echo $this->lang->line('time_define');?></option>
						<option value="2" selected="selected"><?php echo $this->lang->line('time_null');?></option>
						</select>
						<?php endif;?>
						 <color id="order_time_type_msg" style="color:red;"></color>
				<?php }?>

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

		   <!-- 判断是四维预约还是  宝宝缸 -->
		   <?php if($info['jb_parent_id'] == 281){?>
		   	 	<div class="input-append" id="order_time_duan_j" style="margin-right:5px;display:none;float:left;">
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

           <div class="input-append" id="order_time_duan_b" style="margin-right:5px;float:left;<?php if($info['duan_confirm'] == 1){ echo "display:none;";}?>">
             <div class="btn-group">
                 <select name="order_time_duan_b"  id="order_time_duan_b_select" style="width:140px;">
                 <?php if($info['duan_confirm'] == 2){?>  selected
				   <option value="<?php echo $info['order_time_duan'];?>" selected ><?php echo $info['order_time_duan'];?></option>
				 <?php }else{?>
					 <option value="9:00-9:40">9:00-9:40</option>
				 <?php }?>
                 </select>
            </div>
           </div>
		   <?php }else{?>

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

           <div class="input-append" id="order_time_duan_b" style="margin-right:5px; display:none; float:left;<?php if($info['duan_confirm'] == 1){ echo "display:none;";}?>">
             <div class="btn-group">
                 <select name="order_time_duan_b"  id="order_time_duan_b_select" style="width:140px;">
                    <?php if($info['duan_confirm'] == 2){?>  selected
                       <option value="<?php echo $info['order_time_duan'];?>" selected ><?php echo $info['order_time_duan'];?></option>
                     <?php }else{?>
                         <option value="9:00-9:40">9:00-9:40</option>
                     <?php }?>
                 </select>
            </div>
           </div>
		   <?php }?>


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
	<label class="control-label">末次月经</label>
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
	<label class="control-label">商务通来源网址</label>
		<strong class="controls">
		    <input type="text" name="laiyuanweb" id="laiyuanweb" value="<?php echo $info['laiyuanweb'];?>" class="input-xlarge"  style="width:367px;"/>


		</div>
</div>
<div class="control-group order_from">
	<label class="control-label">关键词</label>
		<div class="controls">
		    <input type="text" name="guanjianzi" id="guanjianzi"  value="<?php echo $info['guanjianzi'];?>" class="input-xlarge"  style="width:367px;"/>
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
		<textarea class="input-xxlarge " rows="2" name="remark" style="width:820px;"></textarea>
	</div>
</div>
<div class="control-group order_from">
	<label class="control-label">对话记录</label>
	<div class="description controls">
             <script id="editor" type="text/plain"><?php echo $con_content; ?></script>
     </div>
	<!-- 将商品详情赋值到此处 -->
    <textarea name="con_content" style="display: none;"  id="con_content" ></textarea>


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

<div class="row-fluid">
	<div class="span6 order_from">
		<div class="control-group order_from">
			<label class="control-label">来院网址</label>
			<div class="controls">
			   <select name="keshiurl_id" id="keshiurl_id">
			   <option value="0">请选择</option>
			   <?php foreach ($keshiurl_data as $keshiurl_data_temp){?>
			   	<option value="<?php echo $keshiurl_data_temp['id']?>"  <?php if(strcmp($keshiurl_data_temp['id'],$info['keshiurl_id']) == 0){?>selected="selected"<?php }?>><?php echo $keshiurl_data_temp['url']?>-<?php echo $keshiurl_data_temp['title']?></option>
			    <?php }?>
			   </select>
			</div>
		</div>
	</div>
</div>

<div class="control-group order_from">
	<div class="controls">
<input type="hidden" name="form_action" id="form_action"  value="update" />
<input type="hidden" name="order_id" id="order_id" value="<?php echo $info['order_id']; ?>" />
<input type="hidden"   id="hidden_check_hos_id" value="<?php echo $info['hos_id']; ?>" />
<input type="hidden"   id="hidden_check_keshi_id" value="<?php echo $info['keshi_id']; ?>" />

<input type="hidden" name="pad_id" value="<?php echo $info['p_id']; ?>" />
<input type="hidden" name="p" id="p" value="<?php echo $p; ?>" />
<button type="submit" id="submit" class="btn btn-success"><i class="icon-ok"></i> <?php echo $this->lang->line('submit'); ?> </button>
<button type="reset" class="btn"><i class="icon-remove"></i> <?php echo $this->lang->line('reset'); ?> </button>
	</div>
</div>
  </form>
<?php else:?>

<!-- 系统添加时间 -->
<input type="hidden" id="order_add_time_check" value="0"/>
<!-- 第一次预到时间 -->
<input type="hidden" id="yd_time_check" value="0"/>


<form onSubmit="return chkForm();" action="?c=order&m=order_update" method="post" class="form-horizontal" style="position:relative;">

<!-- 留联单号 -->
<input type="hidden" name="order_liulian_id"  value="<?php echo $order_liulian_id;?>">



<div class="row-fluid order_from">
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('order_no');?></label>
	<div class="controls" style="position:relative;">
            <input type="text" value="" data-trigger="hover" data-placement="right" data-content="若不输入预约号，系统会自动生成新的预约号。之前生成的预约号，只要没有使用都可以继续使用！" data-original-title="预约号说明：" class="input-large popovers" name="order_no" id="order_no" /> <button type="button" id="get_order_no" class="btn btn-success" style="background-color:#00a186;"> <?php echo $this->lang->line('get_order_no'); ?> </button>&nbsp;<button type="button" id="yunzhou_btn" class="btn"> 孕周计算 </button>&nbsp;<button type="button" id="yuyue_btn" class="btn"> 预约卡 </button>
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
		   <?php foreach($hospital as $val){
		   if(isset($liulian_info)){$l_hos_id = $liulian_info['hos_id'];}?>
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
		   <?php foreach($type_list as $val){?>
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
		     <?php foreach($asker_list as $val){
			   		if(isset($liulian_info)){?>
	   			   		<option value="<?php echo $val['admin_id'];?>"  <?php if($val['admin_id'] == $_COOKIE['l_admin_id'] || $val['admin_id'] ==  $liulian_info['admin_id']){ echo "selected";}?>><?php echo $val['admin_name'];?></option>
			     	<?php	}else{?>
	   			   		<option value="<?php echo $val['admin_id'];?>"  <?php if($val['admin_id'] == $_COOKIE['l_admin_id']){ echo "selected";}?>><?php echo $val['admin_name'];?></option>
			        <?php	}?>
		   		<?php }  ?>
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
	   <?php foreach($from_list as $val){
	    	if(isset($liulian_info)){?>
		   		<option value="<?php echo $val['from_id'];?>" <?php if($val['from_id'] == $liulian_info['from_parent_id']){ echo " selected";}?> ><?php echo $val['from_name'];?></option>
		   <?php	}else{?>
		   		 <option value="<?php echo $val['from_id'];?>"><?php echo $val['from_name'];?></option>
		   <?php	}?>

	   <?php } ?>
	   </select>

	   <select name="from_id" id="from_id">
	   <option value="0"><?php echo $this->lang->line('please_select');?></option>
       <?php
	   $l_from_id=0;if(isset($liulian_info)){$l_from_id = $liulian_info['from_id'];}
	   if(isset($order_from_two)){
		   foreach($order_from_two as $order_from_temp){ ?>
			  <option value="<?php echo $order_from_temp['from_id'];?>"  <?php if(strcmp($order_from_temp['from_id'],$l_from_id) ==0){echo "selected='selected'";}?>><?php echo $order_from_temp['from_name'];?></option>
		   <?php }
	   }
	   ?>
	   </select>

	   <input type="text" name="from_value" id="from_value" value="" class="input-xlarge" readonly  style="width:367px;background-color: #fff;"/>
	</div>
</div>
</div>
<div class="row-fluid">
<div class="span6 order_from">
	<div class="control-group order_from">
		<label class="control-label"><?php echo $this->lang->line('patient_name');?></label>
		<div class="controls">
			<input type="text"   class="input-large" name="patient_name" id="patient_name" value="<?php if(isset($liulian_info)){echo $liulian_info['pat_name'];}?>"/>
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
			<input type="radio" name="pat_sex" value="1" checked='checked'/>
			<?php echo $this->lang->line('man');?>
		</label>
		<label class="radio1">
			<input type="radio" name="pat_sex" value="2"  />
			<?php echo $this->lang->line('woman');?>
		</label>
	    </div>
	</div>
</div>
</div>
<div class="row-fluid">
<div class="span6 order_from">
	<div class="control-group order_from">
		<label class="control-label"><?php echo $this->lang->line('phone');?></label>
		<div class="controls">
			<input type="text" value="<?php if(isset($liulian_info)){echo $liulian_info['pat_phone'];}?>" data-trigger="hover" data-placement="right" data-content="【香港区号为：00852】【固定电话格式：075512345678】【手机号码前面不加0】" data-original-title="号码输入规则" class="input-large popovers" name="patient_phone" id="patient_phone" />
            <!-- 电话检查 为0的时候正常提交，不为0的时候 需要检查当前号码是否已经存在，如果存在重复则需要判断是否在两个月以内，是则不能提交信息 -->
            <input type="hidden" id="phone_check_month" value="0">
		</div>
	</div>
</div>
<div class="span5 order_from">
	<div class="control-group">
		<label class="control-label"><?php echo $this->lang->line('age');?></label>
		<div class="controls">
			<input type="text" value="<?php if(isset($liulian_info)){echo $liulian_info['pat_age'];}?>" class="input-large" name="patient_age" id="patient_age" style="width:205px;" />
	    </div>
	</div>
</div>
</div>


<div class="row-fluid">
<div class="span6 order_from">
	<div class="control-group order_from">
		<label class="control-label">QQ</label>
		<div class="controls">
		<input type="text" value="<?php if(isset($liulian_info)){echo $liulian_info['pat_qq'];}?>" class="input-large" name="patient_qq" style="width:205px;" />
		</div>
	</div>
</div>
<div class="span5 order_from">
	<div class="control-group">
		<label class="control-label">微信</label>
		<div class="controls">
			<input type="text" value="<?php if(isset($liulian_info)){echo $liulian_info['pat_weixin'];}?>" class="input-large" name="patient_weixin" style="width:205px;" />
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
				 <input type="hidden" id="order_hidden_time" value="<?php echo date("Y-m-d",time());?>"/>

				<input type="hidden" name="order_null_time" class="input-large" value="" id="order_null_time" style="width:95px;" />

				    <select name="order_time_type" id="order_time_type" style="width:70px;">
						<option value="1"><?php echo $this->lang->line('time_define');?></option>
						<option value="2"><?php echo $this->lang->line('time_null');?></option>
					</select>

				 <color id="order_time_type_msg" style="color:red;"></color>
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

           <div class="input-append" id="order_time_duan_b" style="margin-right:5px; display:none; float:left;">
             <div class="btn-group">
                 <select name="order_time_duan_b"  id="order_time_duan_b_select" style="width:140px;"></select>
            </div>
           </div>

           <select name="duan_confirm" id="duan_confirm" style="width:80px;">
            <option value="1" >大概</option>
            <option value="2">精确</option>
           </select>
		</div>
	</div>
</div>
</div>
<div class="row-fluid order_from">
<div class="control-group order_from" id="yunzhou_div" style="display:none;">
	<label class="control-label">末次月经</label>
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
		   <option value="<?php echo $val['region_id'];?>" <?php if($val['region_id'] == $liulian_info['pat_province']){echo "selected";}?>><?php echo $val['region_name']; ?></option>
		   <?php } ?>
		</select>
		<select name="city" id="city" class="input-small m-wrap" style="width:150px;">
		   <option value="0"><?php echo $this->lang->line('please_select');?></option>
		</select>
		<select name="area" id="area" class="input-small m-wrap" style="width:150px;">
		   <option value="0"><?php echo $this->lang->line('please_select');?></option>
		</select>
		<input type="text" value="<?php echo $liulian_info['pat_address'];?>" class="input-xlarge" name="patient_address" style="width:355px;"/>
	</div>
</div>
</div>

<div class="row-fluid order_from">
<div class="control-group order_from">
	<label class="control-label">商务通来源网址</label>
		<div class="controls">
		    <input type="text" name="laiyuanweb" id="laiyuanweb" value="<?php if(isset($liulian_info)){if(!empty($liulian_info['laiyuanweb'])){echo $liulian_info['laiyuanweb'];}}?>" class="input-xlarge"  style="width:367px;"/>
		</div>
</div>

<div class="control-group order_from">
	<label class="control-label">关键字</label>
		<div class="controls">
		    <input type="text" name="guanjianzi" id="guanjianzi"  value="<?php if(isset($liulian_info)){if(!empty($liulian_info['guanjianzi'])){echo $liulian_info['guanjianzi'];}}?>" class="input-xlarge"  style="width:367px;"/>
		</div>
</div>
</div>

<div class="row-fluid order_from">
<div class="control-group order_from">
	<label class="control-label"><?php echo $this->lang->line('remark');?></label>
	<div class="controls">
		<textarea class="input-xxlarge " rows="2" name="remark" style="width:820px;"><?php if(isset($liulian_info)){if(!empty($liulian_info['pat_weixin'])){echo '微信号:'.$liulian_info['pat_weixin'];}if(!empty($liulian_info['pat_qq'])){echo ';QQ号:'.$liulian_info['pat_qq'];}echo '     '.$remark;}?></textarea>
	</div>
</div>
<div class="control-group order_from">
	<label class="control-label">对话记录</label>
	<div class="description controls">
             <script id="editor" type="text/plain"><?php if(isset($liulian_info)){if(!empty($liulian_info['con_content'])){echo $liulian_info['con_content'];}}?></script>
     </div>
	<!-- 将商品详情赋值到此处 -->
    <textarea name="con_content" style="display: none;"  id="con_content" ><?php if(isset($liulian_info)){if(!empty($liulian_info['con_content'])){echo $liulian_info['con_content'];}}?></textarea>

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

<div class="row-fluid">
	<div class="span6 order_from">
		<div class="control-group order_from">
			<label class="control-label">来院网址</label>
			<div class="controls">
			   <select name="keshiurl_id" id="keshiurl_id">
			   <option value="0">请选择</option>
			   </select>
			</div>
		</div>
	</div>
</div>


<div class="control-group order_from">
	<div class="controls">
		<input type="hidden" name="form_action" id="form_action" value="add" />
		<input type="hidden" name="order_id" id="order_id" value="0" />
        <input type="hidden" name="p" id="p"  value="<?php echo $p; ?>" />
        <button type="submit" id="submit" class="btn btn-success" style="background-color:#00a186;"><i class="icon-ok"></i> <?php echo $this->lang->line('submit'); ?> </button>
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
   <script charset="utf-8" src="static/js/clockface.js"></script>
<script charset="utf-8" src="static/editor/lang/zh_CN.js"></script>
   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="static/js/excanvas.js"></script>
   <script src="static/js/respond.js"></script>
   <![endif]-->
   <script src="static/js/common-scripts.js"></script>
   <script src="static/js/datepicker/js/datepicker.js"></script>


   <!-- 百度編輯器  -->
    <script type="text/javascript" charset="utf-8" src="static/js/ueditor/ueditor.config.js"></script>
	<script type="text/javascript" charset="utf-8" src="static/js/ueditor/ueditor.all.min.js"> </script>
	<!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
	<!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
	<script type="text/javascript" charset="utf-8" src="static/js/ueditor/lang/zh-cn/zh-cn.js"></script>
     <script type="text/javascript">
	 //实例化编辑器
	 //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
	 var editor = UE.getEditor('editor',{toolbars:[[
	                                           'toggletool','fullscreen', 'source', '|', 'undo', 'redo', '|',
	                                           'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist','|',
	                                           'simpleupload', 'insertimage','snapscreen','|', 'selectall', 'cleardoc'
	                                       ]],initialFrameWidth:830,initialFrameHeight:400});

	 </script>
	 <style type="text/css">
	.description{width:80%;}
	</style>


<script>

$(document).ready(function(){

	$("#jibing_id").change(function(){
		if($(this).val() ==  300  || $(this).val() ==  301 ){
			$.ajax({
				type:'post',
				url:'?c=system&m=ajax_baby_time_to_order_list',
				data:'jb_id=' + $(this).val()+"&defualtval="+$("#order_time_duan_b_select").val()+"&days="+$('#order_time').val(),
				success:function(data)
				{
					$("#order_time_duan_b_select").html(data);

					$('#order_time_duan_b').css("display", "block");
				    $('#order_time_duan_j').css("display", "none");
					$('#order_time_duan_d').css("display", "none");

					$("#duan_confirm").html('<option value="1">大概</option><option value="2" selected>精确</option>');
				},
				complete: function (XHR, TS)
				{
				   XHR = null;
				}
			});

		}
	});


	/*$("input[type='text']").hover(function(){
		$(this).focus();
	});
	$("textarea").hover(function(){
		$(this).focus();
	});*/

	$("#yunzhou_btn").click(function(){
		$("#yunzhou_div").css("display", "block");
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
			data:'hos_id=' + hos_id + '&keshi_id=' + keshi_id + '&order_no=' + order_no + '&patient_name=' + patient_name +'&patient_age=' + patient_age + '&patient_phone=' + patient_phone + '&order_time=' + order_time + '&jibing_parent=' + jibing_parent + '&img='+ img,
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
		if($("#jibing_parent_id").val() == 281){
			$("input[name=order_time_duan_b]").val(shijian);
	    }else{
	    	$("input[name=order_time_duan_j]").val(shijian);
		}
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
	    	$('#order_time_duan_b').css("display", "none");
		}
		else
		{
			$('#order_time_duan_d').css("display", "none");
			if($("#jibing_parent_id").val() == 281){
				$('#order_time_duan_b').css("display", "block");
				$('#order_time_duan_j').css("display", "none");
		    }else{
		    	$('#order_time_duan_j').css("display", "block");
		    	$('#order_time_duan_b').css("display", "none");
			}
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
<?php elseif(!empty($liulian_info)): ?>
ajax_from(<?php echo $liulian_info['from_parent_id']; ?>, <?php echo $liulian_info['from_id']; ?>);
ajax_get_keshi(<?php echo $liulian_info['hos_id']; ?>, <?php echo $liulian_info['keshi_id']; ?>);
ajax_get_jibing(<?php echo $liulian_info['keshi_id']; ?>, 0, <?php echo $liulian_info['jb_parent_id']; ?>);
ajax_get_jibing(<?php echo $liulian_info['keshi_id']; ?>, <?php echo $liulian_info['jb_parent_id']; ?>, <?php echo $liulian_info['jb_id']; ?>);
ajax_area('city', <?php echo $liulian_info['pat_province']; ?>, <?php echo $liulian_info['pat_city']; ?>, 3);
ajax_area('area', <?php echo $liulian_info['pat_city']; ?>, <?php echo $liulian_info['pat_area']; ?>, 3);
<?php else:?>
ajax_get_keshi(<?php echo $l_hos_id; ?>);
<?php endif;?>
	var current_order_time = $('#order_time').val();
	if(current_order_time == null || current_order_time == "" || current_order_time == "未定"){
		 if($('#order_hidden_time').val() == null || $('#order_hidden_time').val() == "" || $('#order_hidden_time').val() == "未定"){
		 	current_order_time = <?php echo date("Y-m-d",time());?>;
	     }else{
			current_order_time = $('#order_hidden_time').val();
		 }
	}
	$('#order_time').DatePicker({
		format:'Y-m-d',
		date: current_order_time,
		current: current_order_time,
		starts: 1,
		position: 'right',
		onBeforeShow: function(){
			$('#order_time').DatePickerSetDate(current_order_time, true);
		},
		onChange: function(formated, dates){
			$('#order_time').val(formated);
			 $('#order_time').DatePickerHide();
			 $("#order_time_type_msg").html("");


           //修改挂号的时候  自动判断识别当前可选择宝宝 缸的时间范围
			 if($("#jibing_id").val() ==  300  || $("#jibing_id").val() ==  301 ){
					$.ajax({
						type:'post',
						url:'?c=system&m=ajax_baby_time_to_order_list',
						data:'jb_id=' + $("#jibing_id").val()+"&defualtval="+$("#order_time_duan_b_select").val()+"&days="+formated,
						success:function(data)
						{
							$("#order_time_duan_b_select").html(data);

							$('#order_time_duan_b').css("display", "block");
							$('#order_time_duan_j').css("display", "none");
							$('#order_time_duan_d').css("display", "none");

							$("#duan_confirm").html('<option value="1">大概</option><option value="2" selected>精确</option>');

						},
						complete: function (XHR, TS)
						{
						   XHR = null;
						}
					});
			 }


			 /***
			var today=new Date();
			today.setHours(0);
			today.setMinutes(0);
			today.setSeconds(0);
			today.setMilliseconds(0);
			$("#order_time_type_msg").html("");
			if (Date.parse(today) > Date.parse(new Date(formated))) {
				$("#order_time").val($("#order_hidden_time").val());
				$("#order_time_type_msg").html("必须大于等于当前时间");
			}else{
				 $('#order_time').val(formated);
				 $('#order_time').DatePickerHide();
				 $("#order_time_type_msg").html("");
			}
				***/
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
		ajax_get_form(hos_id,0);
		sms_themes_ajax();
		//来源网址
		$("#keshiurl_id").html("<option value='0'>请选择</optiaon>");

		 $.ajax({
				type:'get',
				url:'?c=keshiurl&m=get_html',
				data:'hos_id='+$("#hos_id").val()+'&keshi_id=0',
				success:function(data)
				{
					$("#keshiurl_id").html(data);
				}
			});

			//判断台州 东方
		 var hos_id = $("#hos_id").val();
		if(hos_id == 3 || hos_id == 6){
			$("#order_time").attr("type", "text");
			$("#order_null_time").attr("type", "hidden");
			$("#order_null_time").val($("#order_hidden_time").val());
			$("#order_time_type").html('<option value="1" selected="selected">确定</option>');
		}else{
			$("#order_time").attr("type", "text");
			$("#order_null_time").attr("type", "hidden");
			$("#order_null_time").val($("#order_hidden_time").val());
			$("#order_time_type").html('<option value="1" selected="selected">确定</option><option value="2" >待定</option>');
		}
	});


	if($("#keshiurl_id").val() == 0){
		//来源网址
		 $.ajax({
				type:'get',
				url:'?c=keshiurl&m=get_html',
				data:'hos_id='+$("#hos_id").val()+'&keshi_id=0',
				success:function(data)
				{
					$("#keshiurl_id").html(data);
				}
			});
	}


	$("#keshi_id").change(function(){
		var keshi_id = $(this).val();
		ajax_get_jibing(keshi_id, 0, 0);
		//判断当前的医院 科室 电话号码是否变更 如果变更，才需要请求后台核实电话有效性
		if($("#hidden_check_hos_id").val() != $("#hos_id").val() || $("#hidden_check_keshi_id").val() != $("#keshi_id").val() ||  $.trim($("#defult_patient_phone").val()) != $.trim($("#patient_phone").val())){
			ajax_checkphone(CtoH($("#patient_phone").val()));
	    }
		sms_themes_ajax();

		//判断是否是 仁爱妇科和不孕
		var hos_id = $("#hos_id").val();
		if(keshi_id == 1 || keshi_id == 32){
			$("#order_time").attr("type", "text");
			$("#order_null_time").attr("type", "hidden");
			$("#order_null_time").val($("#order_hidden_time").val());
			$("#order_time_type").html('<option value="1" selected="selected">确定</option>');
		}else if(hos_id == 3 || hos_id == 6){//判断台州 东方
			$("#order_time").attr("type", "text");
			$("#order_null_time").attr("type", "hidden");
			$("#order_null_time").val($("#order_hidden_time").val());
			$("#order_time_type").html('<option value="1" selected="selected">确定</option>');
		}else{
			$("#order_time").attr("type", "text");
			$("#order_null_time").attr("type", "hidden");
			$("#order_null_time").val($("#order_hidden_time").val());
			$("#order_time_type").html('<option value="1" selected="selected">确定</option><option value="2" >待定</option>');
		}

		//科室变化影响性别的变化
		 $.ajax({
				type:'get',
				url:'?c=system&m=keshi_sex_get_ajax',
				data:'keshi_id='+keshi_id,
				success:function(data)
				{
					 $("input[name='pat_sex']").each(function(){
						 $(this).prop('checked', false);
						 if($(this).val() == data){
							 $(this).prop('checked', true);
					     }
					 });
				}
			});



		 //来源网址
		 $.ajax({
				type:'get',
				url:'?c=keshiurl&m=get_html',
				data:'hos_id='+$("#hos_id").val()+'&keshi_id='+keshi_id,
				success:function(data)
				{
					$("#keshiurl_id").html(data);
				}
			});

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

	$("#patient_phone").keyup(function(){
		//判断当前的医院 科室 电话号码是否变更 如果变更，才需要请求后台核实电话有效性
		if($("#hidden_check_hos_id").val() != $("#hos_id").val() || $("#hidden_check_keshi_id").val() != $("#keshi_id").val() ||  $.trim($("#defult_patient_phone").val()) != $.trim($("#patient_phone").val())){
		    ajax_checkphone(CtoH($(this).val()));
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

	//新增默认添加单号
	if($("#form_action").val()== 'add'){
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
	}

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

	$("#order_time").change(function(){
		 $("#order_time_type_msg").html("");
		   if($("#order_time_type").val() == 1){
				if($("#order_time").val() == '' || $("#order_time").val() == null || $("#order_time").val() == 0){
					("#order_time_type_msg").html("必须填写预到时间");
					return false;
				}else{
					 var DATE_FORMAT = /^[0-9]{4}-[0-1]?[0-9]{1}-[0-3]?[0-9]{1}$/;
					if($("#form_action").val() == 'add'){
						if(!DATE_FORMAT.test($("#order_time").val())){
							  $("#order_time_type_msg").html("抱歉，您输入的日期格式有误，正确格式应为\"<?php echo date("Y-m-d",time());?>\".");
							  $("#order_time").val($("#order_hidden_time").val());
						 }else{
							 /***
							var today=new Date();
							today.setHours(0);
							today.setMinutes(0);
							today.setSeconds(0);
							today.setMilliseconds(0);
							$("#order_time_type_msg").html("");
							if (Date.parse(today) > Date.parse($("#order_time").val())) {
								$("#order_time").val($("#order_hidden_time").val());
								$("#order_time_type_msg").html("必须大于等于当前时间");
								return false;
							}
								***/
						 }
					}else{
						if($("#order_hidden_time").val() != $("#order_time").val()){
							 if(!DATE_FORMAT.test($("#order_time").val())){
								  $("#order_time_type_msg").html("抱歉，您输入的日期格式有误，正确格式应为\"<?php echo date("Y-m-d",time());?>\".");
								  $("#order_time").val($("#order_hidden_time").val());
							 }else{
								 /***
								var today=new Date();
								today.setHours(0);
								today.setMinutes(0);
								today.setSeconds(0);
								today.setMilliseconds(0);
								$("#order_time_type_msg").html("");
								if (Date.parse(today) > Date.parse($("#order_time").val())) {
									$("#order_time").val($("#order_hidden_time").val());
									$("#order_time_type_msg").html("必须大于等于当前时间");
									return false;
								}
									***/
							 }
						}
					}
				}
			}
	});
		$("#order_time_type").change(function(){		var order_time_type = $(this).val();		if(order_time_type == 1){			$("#order_time").attr("type", "text");			$("#order_null_time").attr("type", "hidden");			$("#order_null_time").val($("#order_hidden_time").val());		}else if(order_time_type == 2){			 $("#order_time").attr("type", "hidden");			 $("#order_null_time").attr("type", "text");			 $("#order_null_time").val("未定");		}	});

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


	var keshi_id = $("#keshi_id").val();
	var order_time_type_check =0 ;
	if(keshi_id > 0){
		$.ajax({
			type:'post',
			url:'?c=system&m=ajax_keshi_weiding',
			data:'keshi_id=' + keshi_id,
			async : false,
			success:function(data){
				if(data == '1' && $("#order_time_type").val() == 2 ){
					order_time_type_check =1;
				}
			},
			complete: function (XHR, TS){XHR = null;}});
        }
	if(order_time_type_check   > 0){
		alert("当前科室下,预到时间不能待定！"); return false;
	}

	$("#con_content").html(editor.getContent());
	if($("#patient_phone").val() != '' && $("#order_id").val() == 0 ){//添加判断电话格式
		var phone_msg = ajax_checkphone(CtoH($("#patient_phone").val()));
		if(phone_msg != ''){
			alert(phone_msg);
			return false;
		}
	}else if($("#patient_phone").val() != '' &&  $("#order_id").val() > 0 ){//修改判断电话格式
		 //判断当前的医院 科室 电话号码是否变更 如果变更，才需要请求后台核实电话有效性
		if($("#hidden_check_hos_id").val() != $("#hos_id").val() || $("#hidden_check_keshi_id").val() != $("#keshi_id").val() ||  $.trim($("#defult_patient_phone").val()) != $.trim($("#patient_phone").val())){
			var phone_msg = ajax_checkphone(CtoH($("#patient_phone").val()));
			if(phone_msg != ''){
				alert(phone_msg);
				return false;
			}
	    }
	}

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
	}else if(($("#hos_id").val()  == 45 ||  $("#hos_id").val() == 46) && ($("#laiyuanweb").val() == null || $("#laiyuanweb").val() == '' || $("#laiyuanweb").val() == '' ))
	{
		alert("鹭港医院必须录入商务通来源网址");
		$("#laiyuanweb").focus();
		return false;
	}
	else if($("#admin_id").val() == 0)
	{
		alert("请选择咨询员！");
		return false;
	}else if($('#from_parent_id').val() == '0'){
			alert('预约途径不能为空');
			return false;
	}
	else if($("#patient_name").val() == "")
	{
		alert("请输入患者姓名！");
		return false;
	}
	else if($("#laiyuanweb").val() == '' || $("#laiyuanweb").val() == null){
        alert("为了规范统计数据，必须录入来源，商务通录入来源网址，电话录入座机号码，以此类推。");
        $("#laiyuanweb").focus();
        return false;
    }
	else if($("#phone_check_month").val() == "1")
	{
		//<!-- 电话检查 为0的时候正常提交，不为0的时候 需要检查当前号码是否已经存在，如果存在重复则需要判断是否在两个月以内，是则不能提交信息 -->
		alert("存在重复的并且在两个月之内的电话记录，不能提交！");
		return false;
	}else if($("#order_time_type").val() == 1){
		//仁爱妇科 不孕流入公海 预到时间+指定天数
		var renfi_fk_by_day_time = $("#renfi_fk_by_day_time").val();
		 var today=new Date();
			today.setHours(0);
			today.setMinutes(0);
			today.setSeconds(0);
			today.setMilliseconds(0);

		 var time_ok =0;
		 $("#order_time_type_msg").html("");
            //只有确定情况下，并且修改时间和默认时间不同的情况下 才进行比较
		    if($("#form_action").val()== 'add'){
				     if($("#order_time_type").val() == 1){
						  if($("#order_time").val() != '' && $("#order_time").val()  != null){
							 var DATE_FORMAT = /^[0-9]{4}-[0-1]?[0-9]{1}-[0-3]?[0-9]{1}$/;
							 if(!DATE_FORMAT.test($("#order_time").val())){
								  $("#order_time_type_msg").html("您输入的日期格式有误，正确格式应为\"<?php echo date("Y-m-d",time());?>\".");time_ok =1;
							 }
						 }else{
							 $("#order_time_type_msg").html("您输入的日期格式有误，正确格式应为\"<?php echo date("Y-m-d",time());?>\"."); time_ok =1;
						 }

						if(time_ok == 0){
							$("#order_time_type_msg").html("");
							if (Date.parse(today) > Date.parse($("#order_time").val())) {
								$("#order_time_type_msg").html("必须>=当前日期");
								time_ok = 1;
							}else if($("#keshi_id").val() == 32 || $("#keshi_id").val() == 1){
								 var time=$("#order_time").val();
								  var time1=new Date(time);
								  var time2=new Date();
								  var end_time=time2.getTime()+30*24*60*60*1000;
								  if(time1.getTime() > end_time){
									  $("#order_time_type_msg").html("不能超过30天哦！从今天开始计算。");
									  time_ok = 1;
								  }
								/**
								判断仁爱妇科 不孕 是否通过验证条件
								1.以系统第一次录入的预到时间为准  往后延期 renfi_fk_by_day_time 天为 等待期，超过 renfi_fk_by_day_time 天未到诊的 流入公海
								2. renfi_fk_by_day_time 天之内 只有三次修改预到时间的机会。 添加时间  <= 修改时间    <=  renfi_fk_by_day_time 天预到时间
								3.非当前数据 拥有者 可以在 当拥有者 3次机会使用完之后，去更改数据的 预到时间，条件是更改的时间必须大于 当前预到时间。同时将更改的时间 更新为 最大的预到限制时间
								4.变更咨询员会导致所有记时 限制的条件初始化
								5.以当天+30天为最大的保留时间范围
								**/
								/***
								today.setDate(today.getDate()+renfi_fk_by_day_time);
								$("#order_time_type_msg").html("");
								if (Date.parse(today) < Date.parse($("#order_time").val())) {
									$("#order_time_type_msg").html("当前预到大于"+renfi_fk_by_day_time+"天有效期限制");
									time_ok = 1;
								}
								**/
							} else  if($("#hos_id").val() == 3 || $("#hos_id").val() == 6){//只判断台州和东方
								  var time=$("#order_time").val();
								  var time1=new Date(time);
								  var time2=new Date();

								  var end_time=time2.getTime()+30*24*60*60*1000;
								  //属于台州东方 非自媒体 只能延期 10天，自媒体延期30天
								  if($("#keshi_id").val() == 87 || $("#keshi_id").val() == 88 || $("#keshi_id").val() == 89  || $("#keshi_id").val() == 85 || $("#keshi_id").val() == 86){
									  if(time1.getTime() > end_time){
										  $("#order_time_type_msg").html("不能超过30天哦！从今天开始计算。");
										  time_ok = 1;
									  }
								  }else{
									  if($("#from_id").val() == 200 || $("#from_id").val() == 201){
									  		end_time=time2.getTime()+15*24*60*60*1000;
									  		if(time1.getTime() > end_time){
											  $("#order_time_type_msg").html("不能超过15天哦！从今天开始计算。");
											  time_ok = 1;
										  	}
									  	}else{
									  		end_time=time2.getTime()+10*24*60*60*1000;
										  	if(time1.getTime()>end_time){
											  $("#order_time_type_msg").html("不能超过10天哦！从今天开始计算。");
											  time_ok = 1;
									  		}
									  	}
								  }
						 	}
						}
					}
			}else if($("#form_action").val()== 'update'){
				   //如何科室不相等  则判断修改预到时间
				   if($("#keshi_id").val() == $("#keshi_old_id_check").val()){
					   if($("#order_time_type").val() == 1 && $("#order_time").val() != $("#order_hidden_time").val()){
							 if($("#order_time").val() != '' && $("#order_time").val()  != null){
								 var DATE_FORMAT = /^[0-9]{4}-[0-1]?[0-9]{1}-[0-3]?[0-9]{1}$/;
								 if(!DATE_FORMAT.test($("#order_time").val())){
									  $("#order_time_type_msg").html("您输入的日期格式有误，正确格式应为\"<?php echo date("Y-m-d",time());?>\".");    $("#order_time").val($("#order_hidden_time").val()); time_ok = 1;
								 }
							 }else{
								 $("#order_time_type_msg").html("您输入的日期格式有误，正确格式应为\"<?php echo date("Y-m-d",time());?>\".");  $("#order_time").val($("#order_hidden_time").val()); time_ok = 1;
							 }
							 if(time_ok == 0){
								    var date = new Date($("#order_add_time_check").val());
								    if (Date.parse(date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate()) > Date.parse($("#order_time").val())) {
										$("#order_time").val($("#order_hidden_time").val());
										$("#order_time_type_msg").html("必须>=添加日期"+date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate());
										time_ok = 1;
									 }else if(($("#keshi_id").val() == 32 || $("#keshi_id").val() == 1)){
								    	 var time=$("#order_time").val();
										  var time1=new Date(time);
										  var time2=new Date();
										  var end_time=time2.getTime()+30*24*60*60*1000;
										  if(time1.getTime() > end_time){
											  $("#order_time_type_msg").html("不能超过30天哦！从今天开始计算。");time_ok = 1;
										  }
									 }
									 /**
									 else if(($("#keshi_id").val() == 32 || $("#keshi_id").val() == 1) && $("#yd_time_check").val() !=  0 ){//不存在预到时间统计的就不用再判断时间
										 if($("#yd_time_check").val() != '' && $("#yd_time_check").val() != null && $("#yd_time_check").val() != 0){

												判断仁爱妇科 不孕 是否通过验证条件
												1.以系统第一次录入的预到时间为准  往后延期7天为 等待期，超过 renfi_fk_by_day_time 天未到诊的 流入公海
												2. renfi_fk_by_day_time 天之内 只有三次修改预到时间的机会。 添加时间  <= 修改时间    <=  renfi_fk_by_day_time 天预到时间
												3.非当前数据 拥有者 可以在 当拥有者 3次机会使用完之后，去更改数据的 预到时间，条件是更改的时间必须大于 当前预到时间。同时将更改的时间 更新为 最大的预到限制时间
												4.变更咨询员会导致所有记时 限制的条件初始化

												 if($("#admin_id_check").val() == $("#admin_id").val()){
													 if($("#yd_admin_id_check").val() == $("#admin_id_check").val() ){//当前咨询员自己更改时间
														 if(Date.parse($("#yd_time_check").val()) < Date.parse($("#order_time").val())){
																$("#order_time").val($("#order_hidden_time").val());
																$("#order_time_type_msg").html("必须小于:"+$("#yd_time_check").val()+",剩余修改次数:"+(3-$("#yd_sum_check").val())+"次");
																time_ok = 1;
														 }
													 }else{//其他人 更改时间
															if($("#l_rank_id_check").val() == 21 || $("#l_rank_id_check").val() == 5 || $("#l_rank_id_check").val() == 1){
																var order_add_time_check = $("#order_add_time_check").val().split(' ');
																if (Date.parse(order_add_time_check[0]) > Date.parse($("#order_time").val())) {
																	$("#order_time_type_msg").html("预到时间必须大于:"+$("#order_add_time_check").val()+"");time_ok = 1;
																}
															}else{
																$("#order_time_type_msg").html("原咨询剩余修改次数:"+(3-$("#yd_sum_check").val())+"次,你不能改。");time_ok = 1;
															}
														}
												 }
										 }
									 } **/

									 else if($("#hos_id").val() == 3 || $("#hos_id").val() == 6){//只判断台州和东方
										  var time=$("#order_time").val();
										  var time1=new Date(time);
										  var time2=new Date();

										  var end_time=time2.getTime()+30*24*60*60*1000;
										  //属于台州东方 非自媒体 只能延期 10天，自媒体延期30天
										  if($("#keshi_id").val() == 87 || $("#keshi_id").val() == 88 || $("#keshi_id").val() == 89  || $("#keshi_id").val() == 85 || $("#keshi_id").val() == 86){
											  if(time1.getTime() > end_time){
												  $("#order_time_type_msg").html("不能超过30天哦！从今天开始计算。");
												  time_ok = 1;
											  }
										  }else{
											  if($("#from_id").val() == 200 || $("#from_id").val() == 201){
											  		end_time=time2.getTime()+15*24*60*60*1000;
											  		if(time1.getTime() > end_time){
													  $("#order_time_type_msg").html("不能超过15天哦！从今天开始计算。");
													  time_ok = 1;
												  	}
											  	}else{
											  		end_time=time2.getTime()+10*24*60*60*1000;
												  	if(time1.getTime()>end_time){
													  $("#order_time_type_msg").html("不能超过10天哦！从今天开始计算。");
													  time_ok = 1;
											  		}
											  	}
										  }
									 }
							 }
						}
				   }else{
					   if($("#order_time_type").val() == 1){
							 if($("#order_time").val() != '' && $("#order_time").val()  != null){
								 var DATE_FORMAT = /^[0-9]{4}-[0-1]?[0-9]{1}-[0-3]?[0-9]{1}$/;
								 if(!DATE_FORMAT.test($("#order_time").val())){
									  $("#order_time_type_msg").html("您输入的日期格式有误，正确格式应为\"<?php echo date("Y-m-d",time());?>\".");    $("#order_time").val($("#order_hidden_time").val()); time_ok = 1;
								 }
							 }else{
								 $("#order_time_type_msg").html("您输入的日期格式有误，正确格式应为\"<?php echo date("Y-m-d",time());?>\".");  $("#order_time").val($("#order_hidden_time").val()); time_ok = 1;
							 }
							 if(time_ok == 0){
								    var date = new Date($("#order_add_time_check").val());
								    if (Date.parse(date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate()) > Date.parse($("#order_time").val())) {
										$("#order_time").val($("#order_hidden_time").val());
										$("#order_time_type_msg").html("必须>=添加日期"+date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate());
										time_ok = 1;
									 }else if(($("#keshi_id").val() == 32 || $("#keshi_id").val() == 1)){
								    	 var time=$("#order_time").val();
										  var time1=new Date(time);
										  var time2=new Date();
										  var end_time=time2.getTime()+30*24*60*60*1000;
										  if(time1.getTime() > end_time){
											  $("#order_time_type_msg").html("不能超过30天哦！从今天开始计算。");time_ok = 1;
										  }
									 }else if($("#hos_id").val() == 3 || $("#hos_id").val() == 6){//只判断台州和东方
										  var time=$("#order_time").val();
										  var time1=new Date(time);
										  var time2=new Date();

										  var end_time=time2.getTime()+30*24*60*60*1000;
										  //属于台州东方 非自媒体 只能延期 10天，自媒体延期30天
										  if($("#keshi_id").val() == 87 || $("#keshi_id").val() == 88 || $("#keshi_id").val() == 89  || $("#keshi_id").val() == 85 || $("#keshi_id").val() == 86){
											  if(time1.getTime() > end_time){
												  $("#order_time_type_msg").html("不能超过30天哦！从今天开始计算。");
												  time_ok = 1;
											  }
										  }else{
											  if($("#from_id").val() == 200 || $("#from_id").val() == 201){
											  		end_time=time2.getTime()+15*24*60*60*1000;
											  		if(time1.getTime() > end_time){
													  $("#order_time_type_msg").html("不能超过15天哦！从今天开始计算。");
													  time_ok = 1;
												  	}
											  	}else{
											  		end_time=time2.getTime()+10*24*60*60*1000;
												  	if(time1.getTime()>end_time){
													  $("#order_time_type_msg").html("不能超过10天哦！从今天开始计算。");
													  time_ok = 1;
											  		}
											  	}
										  }
									 }
							 }
						}
				   }
			}

		  $("#order_time_duan_type_msg").html("");
		  if(time_ok == 0){
			  var order_time_duan_j = $("input[name='order_time_duan_j']").val();
	          var tt=/[0-2][0-9][:][0-5][0-9]/;
			  if($("#jibing_parent_id").val() != 281){
				 if(tt.test(order_time_duan_j)===false){
				  $("#order_time_duan_type_msg").html("请输入规范的自定义预约时段，如12:01,冒号为英文冒号！");
				  time_ok = 1;
				 }
			 }
		  }

		  if(time_ok == 1){
			  alert("预到时间错误,请检查");
			  return false;
		  }
	}

	//判断宝宝缸
	if($("#jibing_id").val() == 300 || $("#jibing_id").val() == 301)
	{
		if($("#duan_confirm").val() == 1)
		{
			alert("预约时段类型必须为精确");
		    return false;
		}else  if($("#order_time_duan_b_select").val() == null || $("#order_time_duan_b_select").val() == "")
		{
			alert("预约时段值必须选择");
		    return false;
		}
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

		var order_hidden_time = $("#order_hidden_time").val();
		var order_hidden_time_duan_j = $("#order_hidden_time_duan_j").val();
		if(order_time == order_hidden_time  && order_time_duan_j == order_hidden_time_duan_j){
			 return true;
		}

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
		data:'parent_id=' + parent_id + '&from_id=' + from_id+ '&hos_id=' + $("#hos_id").val() + '&keshi_id=' + $("#keshi_id").val(),
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


/**
2016 12 01  检查科室下的电话号码
返回错误字符信息，同时在页面会生成相应提示

**/
function ajax_checkphone(phone)
{
	//如果从留联转入
	<?php if(!isset($liulian_info)){?>
	var is_liulian = 1;
	<?php }else{ ?>
	var is_liulian = 0;
	<?php } ?>

	 var phone_msg = '';
		$("#patient_phone").val(phone);
		//<!-- 电话检查 为0的时候正常提交，不为0的时候 需要检查当前号码是否已经存在，如果存在重复则需要判断是否在两个月以内，是则不能提交信息 -->
		 $("#phone_check_month").val("0");
		 $(".remove_msg").remove();
		if(phone != "")
		{

			$("#patient_phone").after("<i class='icon-refresh icon-spin'></i>");
			$.ajax({
				type:'post',
				url:'?c=order&m=phone_hos_keshi_check_ajax',
				data:'phone=' + phone+'&order_id='+$("#order_id").val()+'&hos_id='+$("#hos_id").val()+'&keshi_id='+$("#keshi_id").val()+'&is_liulian='+is_liulian,
				async: false,
				success:function(data)
				{
					data = $.parseJSON(data);
					type = data['type'];
					if(type == 0)
					{
						$("#patient_phone").next("i").remove();
						$("#patient_phone").next("span").remove();
						$("#patient_phone").parent().parent().addClass("error");
						$("#patient_phone").after('<span class="help-inline  remove_msg">请输入号码</span>');

						 phone_msg ="请输入号码！";
					}
					else if(type == 1)
					{
						$("#patient_phone").next("i").remove();
						$("#patient_phone").next("span").remove();
						$("#patient_phone").parent().parent().addClass("error");
						$("#patient_phone").after('<span class="help-inline   remove_msg">号码格式错误</span>');

						phone_msg ="号码格式错误！";
					}
					else if(type == 2)
					{
//						var city_id = data['info']['region_id'];
//						var province_id = data['info']['parent_id'];
//
//						$("#province option").removeAttr("selected");
//						$("#province option[value='" + province_id + "']").prop("selected",true);
//
//						ajax_area('city', province_id, city_id, 2);
						$("#patient_phone").next("i").remove();
						$("#patient_phone").next("div").remove();
						$("#patient_phone").parent().parent().removeClass("error");

						if(data['over'] != "")
						{
							var html = '&nbsp;<div class="btn-group   remove_msg"><button data-toggle="dropdown" class="btn btn-danger dropdown-toggle">当前号码预约过 <span class="caret"></span></button><ul class="dropdown-menu">';
							$.each(data['over'], function(key, value){
								html += '<li><a href="#">患者姓名：<font color="red">' + value.pat_name + '</font>、医院：<font color="red">' + value.hos_name + '</font>、预约号：<font color="red">' + value.order_no + '</font>、咨询员：<font color="red">' + value.admin_name + '</font>、登记时间：<font color="red">' + value.addtime + '</font>、最后回访时间：<font color="red">' + value.mark_time + '</font></a></li>';
							});
							html += '</ul></div>';
							$("#patient_phone").after(html);
						}
					}else if(type == 5)
					{
						$("#patient_phone").next("i").remove();
						$("#patient_phone").next("div").remove();
						$("#patient_phone").parent().parent().removeClass("error");

						if(data['over'] != "")
						{
							var html = '&nbsp;<div class="btn-group   remove_msg"><button data-toggle="dropdown" class="btn btn-danger dropdown-toggle">当前号码在两个月之内已经被预约过 <span class="caret"></span></button><ul class="dropdown-menu">';
							$.each(data['over'], function(key, value){
								html += '<li><a href="#">患者姓名：<font color="red">' + value.pat_name + '</font>、医院：<font color="red">' + value.hos_name + '</font>、预约号：<font color="red">' + value.order_no + '</font>、咨询员：<font color="red">' + value.admin_name + '</font>、登记时间：<font color="red">' + value.addtime + '</font>、最后回访时间：<font color="red">' + value.mark_time + '</font></a></li>';
							});
							html += '</ul></div>';
							$("#patient_phone").after(html);
							//<!-- 电话检查 为0的时候正常提交，不为0的时候 需要检查当前号码是否已经存在，如果存在重复则需要判断是否在两个月以内，是则不能提交信息 -->
							$("#phone_check_month").val("1");


							 phone_msg ="当前号码在两个月之内已经被预约过！";
						}
					}else if(type== 4){
                        $("#patient_phone").next("i").remove();
						$("#patient_phone").next("span").remove();
						$("#patient_phone").parent().parent().addClass("success");
						$("#patient_phone").after('<span class="help-inline remove_msg">可以使用！</span>');

                   }else if(type== 6){
                        $("#patient_phone").next("i").remove();
						$("#patient_phone").next("span").remove();
						$("#patient_phone").parent().parent().addClass("success");
						$("#patient_phone").after('<span class="help-inline remove_msg ">请选择医院和科室！</span>');

                    }else if(type == 7){
						$("#patient_phone").next("i").remove();
						$("#patient_phone").next("div").remove();
						$("#patient_phone").parent().parent().removeClass("error");

						if(data['over'] != "")
						{
							var html = '&nbsp;<div class="btn-group   remove_msg"><button data-toggle="dropdown" class="btn btn-danger dropdown-toggle">当前号码在在留联中已经被登记过 <span class="caret"></span></button><ul class="dropdown-menu">';
							$.each(data['over'], function(key, value){
								html += '<li><a href="#" style="width:500px;white-space: normal;">患者姓名：<font color="red">' + value.pat_name + '</font>、医院：<font color="red">' + value.hos_name + '</font>、预约号：<font color="red">' + value.order_no + '</font>、咨询员：<font color="red">' + value.admin_name + '</font>、登记时间：<font color="red">' + value.addtime + '</font>、来源：<font color="red">' + value.from + '</font></a></li>';
							});
							html += '</ul></div>';
							$("#patient_phone").after(html);
							//<!-- 电话检查 为0的时候正常提交，不为0的时候 需要检查当前号码是否已经存在，如果存在重复则需要判断是否在两个月以内，是则不能提交信息 -->
							$("#phone_check_month").val("1");


							 phone_msg ="当前号码在在留联中已经被登记过！";
						}
					}
				},
				complete: function (XHR, TS)
				{
				   XHR = null;
				}
			});
		}
		return phone_msg;
}


function ajax_get_form(hos_id, keshi_id)
{
	$("#from_parent_id").after("<i class='icon-refresh icon-spin'></i>");
	$.ajax({
		type:'post',
		url:'?c=order&m=form_list_ajax',
		data:'hos_id=' + hos_id + '&keshi_id=' + keshi_id+"&check_id="+$("#from_parent_id").val(),
		success:function(data)
		{
			$("#from_parent_id").html(data);
		},
		complete: function (XHR, TS)
		{
		   XHR = null;
		   $("#from_parent_id").next(".icon-spin").remove();
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
				 //修改挂号的时候  自动判断识别当前可选择宝宝 缸的时间范围
				if($("#jibing_id").val() ==  300  || $("#jibing_id").val() ==  301 ){
					$.ajax({
						type:'post',
						url:'?c=system&m=ajax_baby_time_to_order_list',
						data:'jb_id=' + $("#jibing_id").val()+"&defualtval="+$("#order_time_duan_b_select").val()+"&days="+$('#order_time').val(),
						success:function(data)
						{
							$("#order_time_duan_b_select").html(data);

							$('#order_time_duan_b').css("display", "block");
							$('#order_time_duan_j').css("display", "none");
							$('#order_time_duan_d').css("display", "none");

							$("#duan_confirm").html('<option value="1">大概</option><option value="2" selected>精确</option>');

						},
						complete: function (XHR, TS)
						{
						   XHR = null;
						}
					});
				}else if($("#jibing_parent_id").val() == 149 ){
					if($("#duan_confirm").val() == 2){
						$('#order_time_duan_b').css("display", "none");
						$('#order_time_duan_j').css("display", "block");
						$('#order_time_duan_d').css("display", "none");
					 }else{
						$('#order_time_duan_b').css("display", "none");
						$('#order_time_duan_j').css("display", "none");
						$('#order_time_duan_d').css("display", "block");
					 }
				}
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


//判断台州 东方
var hos_id = $("#hos_id").val();
if(hos_id == 3 || hos_id == 6){
	$("#order_time").attr("type", "text");
	$("#order_null_time").attr("type", "hidden");
	$("#order_null_time").val($("#order_hidden_time").val());
	$("#order_time_type").html('<option value="1" selected="selected">确定</option>');
}else{
	$("#order_time").attr("type", "text");
	$("#order_null_time").attr("type", "hidden");
	$("#order_null_time").val($("#order_hidden_time").val());
	$("#order_time_type").html('<option value="1" selected="selected">确定</option><option value="2" >待定</option>');
}

</script>
</body>
</html>