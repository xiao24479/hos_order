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
<div class="widget-body">
<?php if(!empty($info)){ ?>
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
<!--  当前所属咨询员 -->
<input type="hidden" id="admin_id_check" value="<?php echo $info['admin_id'];?>"/>

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
<div class="row-fluid order_from">
<div class="span6 order_from">
	<div class="control-group order_from">
		<label class="control-label"><?php echo $this->lang->line('order_time');?></label>
		<div class="controls">
			<div class="input-icon left">
				<i class="icon-time"></i>
			 <?php if($info['keshi_id'] == 32 || $info['keshi_id'] == 1){ $yd_sum =  3-$yd_sum; 
						if($_COOKIE['l_admin_id'] != $info['admin_id']){$yd_sum = 3;}
						//管理员 项目运营 项目咨询主管 自己 其他人不可以改预到时间
						if(in_array($_COOKIE['l_rank_id'],array("21","5","1")) || $_COOKIE['l_admin_id'] == $info['admin_id'] ){}else{$yd_sum = 0;}?>
						<?php if(!empty($info['order_time'])):?>
						<input <?php if($yd_sum == 0){?>disabled="disabled"<?}?> type="text" name="order_time" class="input-large" value="<?php echo date("Y-m-d", $info['order_time']); ?>" id="order_time" style="width:95px;" />
						<input <?php if($yd_sum == 0){?>disabled="disabled"<?}?> type="hidden" id="order_hidden_time" value="<?php echo date("Y-m-d", $info['order_time']);?>"/>
						<input <?php if($yd_sum == 0){?>disabled="disabled"<?}?> type="hidden" id="order_hidden_time_duan_j" value="<?php if($info['duan_confirm'] == 2){ echo $info['order_time_duan'];}else{ echo "12:00";}?>"/>
		  
						<input <?php if($yd_sum == 0){?>disabled="disabled"<?}?> type="hidden" name="order_null_time" class="input-large" value="" id="order_null_time" style="width:95px;" />
						<select <?php if($yd_sum == 0){?>disabled="disabled"<?}?> name="order_time_type" id="order_time_type" style="width:70px;">
						<option value="1" selected="selected"><?php echo $this->lang->line('time_define');?></option>
						</select>
						<?php else:?>
						<input <?php if($yd_sum == 0){?>disabled="disabled"<?}?> type="hidden" name="order_time" class="input-large" value="<?php echo date("Y-m-d", time()); ?>" id="order_time" style="width:95px;" />
						
						  <input <?php if($yd_sum == 0){?>disabled="disabled"<?}?> type="hidden" id="order_hidden_time" value="<?php echo date("Y-m-d",time());?>"/>
						  
		                   <input <?php if($yd_sum == 0){?>disabled="disabled"<?}?> type="hidden" id="order_hidden_time_duan_j" value="<?php if($info['duan_confirm'] == 2){ echo $info['order_time_duan'];}else{ echo "12:00";}?>"/>
		  
		  
						<input <?php if($yd_sum == 0){?>disabled="disabled"<?}?> type="text" name="order_null_time" class="input-large" value="<?php echo $info['order_null_time']; ?>" id="order_null_time" style="width:95px;" />
						<select <?php if($yd_sum == 0){?>disabled="disabled"<?}?> name="order_time_type" id="order_time_type" style="width:70px;">
						<option value="1"><?php echo $this->lang->line('time_define');?></option> 
						</select>
						<?php endif;?>
						 <color id="order_time_type_msg" style="color:red;"><?php if($yd_time != 0 ){?>修改次数剩余:<?php echo $yd_sum;?>次;最大预到时间:<?php   echo $yd_time;}?></color>
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
</div>
<?php }?>
 
   <script src="static/js/jquery.js"></script>
   <script src="static/js/jquery.nicescroll.js" type="text/javascript"></script>
   <script type="text/javascript" src="static/js/jquery.slimscroll.min.js"></script>
   <script src="static/js/bootstrap.min.js"></script>
   <script type="text/javascript" src="static/js/bootstrap-datepicker.js"></script> 
   <script charset="utf-8" src="static/js/clockface.js"></script> 
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
	
</body>
</html>