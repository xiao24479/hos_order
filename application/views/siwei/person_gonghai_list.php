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

<link rel="stylesheet" type="text/css" href="static/css/metro-gallery.css" media="screen" />

<link href="static/js/datepicker/css/datepicker.css" rel="stylesheet" />

<style type="text/css">

/*#main-content{margin-left:0px;}

#sidebar{margin-left:-180px; z-index:-1;}

.sidebar-scroll{z-index:-1;}隐藏左边导航栏css代码*/

.date_div{ position:absolute; top:55px; left:412px; z-index:999;}

.anniu{ display:none;}

.o_a a{ padding:0 10px;}

.from_value{ width:85px; overflow:hidden; display:block;}
.order_form{ height:160px}

.autocomplete{ 
border: 1px solid #9ACCFB; 
background-color: white; 
text-align: left; 
} 
.autocomplete li{ 
list-style-type: none; 
} 
.clickable { 
cursor: default; 
} 
.highlight { 
background-color: #9ACCFB; 
} 
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
left: 25%;
width: 50%;
height: 85%;
border: 16px solid lightblue;
background-color: white;
z-index:1002;
overflow: auto;
}
.modal{width:50%; left:50%;margin-left:-25%;}
</style>

<script src="static/js/jquery.js"></script>

<script language="javascript">

//if($(window).width() <= 1200)
//
//{
//
//	window.location.href = '?c=order&m=order_list&type=mi';
//
//}

</script>
<script type="text/javascript" src="static/js/js_window/zDrag.js"></script>
<script type="text/javascript" src="static/js/js_window/zDialog.js"></script>
<script>
function open2(order_id)
{
	var diag = new Dialog();
	diag.Width = 820;
	diag.Height = 400;
	diag.Title = "预约操作记录页";
	diag.URL = "?c=gonghai&m=order_detail&order_id="+order_id;
	diag.show();
}

function order_and_gonghaiorder_search()
{ 
	if(($("#p_p").val() != null && $("#p_p").val() != '' && $("#p_p").val() != 0) || $("#p_n").val() != null && $("#p_n").val() != '' && $("#p_n").val() != 0 ){
		var diag = new Dialog();
		diag.Width = 820;
		diag.Height = 800;
		diag.Title = "预约和公海同时查询";
		diag.URL = "?c=order&m=order_and_gonghaiorder_search&p_p="+$("#p_p").val()+"&p_n="+$("#p_n").val();
		diag.show();
	}else{
		alert("电话和名称必须存在一个");
	} 
}

function yycard(order_id,hos_id)
{
	if(order_id != 0 ){
		var diag = new Dialog();
    if (hos_id == 3 || hos_id == 6) {
      diag.Width = 700;
      diag.Height = 570;
    } else {
      diag.Width = 700;
      diag.Height = 400;
    }

	diag.Title = "查询预约卡";
	diag.URL = "?c=order&m=yycard&order_id="+order_id;
	diag.show();
	}else{
		alert("必须存在预约单ID");
	}
}

function ajax_get_fz(order_id){
	var diag = new Dialog();
	diag.Width = 700;
	diag.Height = 400;
	diag.Title = "添加复诊记录";
	diag.URL = "?c=order&m=get_fz&order_id="+order_id;
	diag.show();
}
</script>

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
<!--  操作者 -->
<input type="hidden" id="yd_admin_id_check" value="<?php echo $_COOKIE['l_admin_id'];?>"/>



<?php echo $top; ?>

<div id="container" class="row-fluid"> 

<?php echo $sider_menu; ?>



<div id="main-content"> 

         <!-- BEGIN PAGE CONTAINER-->

         <div class="container-fluid" style="position:relative; padding-top:10px;"> 
<form action="" method="get" class="date_form order_form1">
<input type="hidden" value="gonghai" name="c" />
<input type="hidden" value="person_gonghai_list" name="m" />
<!--<input type="hidden" value="mi" name="type" />-->
<div class="span5">
    <div class="row-form">
		<select name="t" style="width:110px;">
			<?php foreach($this->lang->line('order_type') as $key=>$val):?><option value="<?php echo $key; ?>" <?php if($key == $t){echo " selected";}?>><?php echo $val; ?></option><?php endforeach;?>
		</select>
		<input type="text" value="<?php echo $start; ?> - <?php echo $end; ?>" style="width:270px;" class="input-block-level" name="date" id="inputDate" />
    </div>
    <div class="date_div">
    <div class="divdate"></div>
    <div class="anniu"><a href="javascript:;" class="btn btn-inverse guanbi"> 关闭 </a><br /><a href="javascript:;" class="btn btn-inverse today"> 今天 </a><br /><a href="javascript:;" class="btn btn-inverse week"> 一周 </a><br /><a href="javascript:;" class="btn btn-inverse month"> 一月 </a><br /><a href="javascript:;" class="btn btn-inverse year"> 一年 </a></div>
    </div>
    

   
</div>
<div class="span3">
	<div class="row-form">
		<label class="select_label"><?php echo $this->lang->line('patient_name');?></label>
		<input type="text" value="<?php echo $p_n; ?>" class="input-medium" name="p_n" id="p_n" />
	</div>
    
</div>
<div class="span3">
	<div class="row-form">
		<label class="select_label"><?php echo $this->lang->line('order_no');?></label>
		<input type="text" value="<?php echo $o_o; ?>" class="input-medium" name="o_o"  />
	</div>
    

</div>
<div class="span3">
<div class="row-form">

		<label class="select_label"><?php echo $this->lang->line('patient_phone');?></label>

		<input type="text" value="<?php echo $p_p; ?>" class="input-medium" name="p_p" id="p_p" />

	</div>
</div>
<div class="order_btn1">
    <button type="submit" class="btn btn-success" onsubmit="_czc.push(['_trackEvent', '预约列表', '<?php echo $admin['name']; ?>', '搜索','','']);"> 搜索 </button> <!--<a href="javascript:show(this);" id="gaoji" class="btn btn-default"> 高级 </a>-->

     <span style="margin-left: 10px;"> <input type="button" class="input_search"  style="vertical-align:middle;height:30px; cursor:pointer;" value="查询公海和预约" onClick="order_and_gonghaiorder_search()"/>
     
    </div>
</form>

	  <div class="row-fluid">

		<div class="span12">

<table width="100%" border="0" cellspacing="0" cellpadding="2" class="list_table">

  <thead>
      <tr style="font-size: 20px;color:lightcoral;"><td colspan="12" style="text-align:center; background-color:#dcdcdc;">个人预约列表</td></tr>
  <tr>

	<th width="40">序号</th>

    <th width="50"><?php echo $this->lang->line('order_no'); ?></th>
    <th >预约到期时间</th>
	<th><?php echo $this->lang->line('patient_info'); ?></th>

	<th width="150"><?php echo $this->lang->line('time'); ?></th>

	<th width="80">途径/性质</th>

	<th width="70"><?php echo $this->lang->line('order_keshi'); ?></th>

	<th width="70"><?php echo $this->lang->line('patient_jibing'); ?></th>

    <th width="65">客服/医生</th>

	<th width="200"><?php echo $this->lang->line('visit'); ?></th>

    <th width="200"><?php echo $this->lang->line('notice'); ?></th>

	<th width="70" style="border-right:1px solid #eee;"><?php echo $this->lang->line('action'); ?></th>

  </tr>

  </thead>

  <tbody>

  <?php
if(empty($order_list)){
        echo "<tr><td colspan='12'>很抱歉，亲，没有相关数据哦！</td></tr>";
    }else{
  $i = 0;

  foreach($order_list as $item):
 ?>
 <!-- 系统添加时间 -->
<input type="hidden" id="order_add_time_check_<?php echo $item['order_id'];?>" value="<?php echo $item['order_addtime'];?>"/>
<!--  当前所属咨询员 -->
<input type="hidden" id="admin_id_check_<?php echo $item['order_id'];?>" value="<?php echo $item['admin_id'];?>"/> 
<input type="hidden" id="order_hidden_time_check_<?php echo $item['order_id'];?>" value="<?php echo $item['order_time'];?>"/> 
<input type="hidden" id="order_time_duan_check_<?php echo $item['order_id'];?>" value="<?php echo $item['order_time_duan'];?>"/> 

 <?
  $yd_time =  0;
  $yd_sum = 0;
  $order_yd_check =0 ;
  foreach ($info_yd_time as $info_yd_time_temp){
	if(strcmp($info_yd_time_temp['order_id'],$item['order_id']) == 0){
	    $order_yd_check =1 ;
		//第一次预到时间 
		if(!empty($info_yd_time_temp['order_time'])){
			$yd_time = date("Y-m-d H:i:s", $info_yd_time_temp['order_time']) ;
			$yd_sum = $info_yd_time_temp['sum'];
		} 
		break;
	} 
}
if(empty($yd_time)){
	$yd_time =  0;
	$yd_sum = 0;
}
if(!empty( $order_yd_check )){
	if($item['keshi_id'] == 32 || $item['keshi_id'] == 1){ $yd_sum =  3-$yd_sum;}
	if($_COOKIE['l_admin_id'] != $item['admin_id']){$yd_sum = 3;}
?> 
<input type="hidden" id="yd_time_check_<?php echo $item['order_id'];?>" value="<?php echo $yd_time;?>"/>
<input type="hidden" id="yd_sum_check_<?php echo $item['order_id'];?>" value="<?php echo $yd_sum;?>"/> 

<? } ?>

<?php 
$format_time=  strtotime(date("Y-m-d",time()));
$left=strtotime($item['order_time'])-$format_time;
  $start_time=time()-30*24*60*60;
  
    
    ?>
  <tr<?php if($i % 2){ echo " class='td1'";}?> style="height:90px;">

    <td><b><?php echo $now_page + $i + 1; ?></b></td>
    
    <td>
   
       <a onClick="open2(<?php echo $item['order_id']; ?>)" id="order_no_<?php echo $item['order_id']; ?>"><?php echo $item['order_no']; ?></a><br />

	   <?php if($item['is_first']){ echo "初诊";}else{ echo "<font color='#FF0000'>复诊</font>";}?><br />
<!--显示打×还是√的方法javascript:change_order_status($order_id)-->	    <!-- 权限判断  -->       <input type="hidden"  id="l_admin_action" value="<?php echo $_COOKIE['l_admin_action'];?>">	   	   
        <?php if(empty($item['is_to_gonghai'])){ ?><a href="<?php if(($rank_type == 3) || ($_COOKIE['l_admin_action'] == 'all')):?>javascript:change_order_status(<?php echo $item['order_id']; ?>);<?php else:?>javascript:;<?php endif;?>"><?php if($item['is_come'] > 0){ echo '<i id="status_' . $item['order_id'] . '" class="icon-ok" style="color:#F00; font-size: large;"></i>';}else{ echo '<i id="status_' . $item['order_id'] . '" class="icon-remove" style="color:#00F; font-size: large;"></i>'; }?></a> <?php } ?>

    </td>
     <td>离过期时间还剩下：<br/>
     <?php $left_time=strtotime($item['order_time'])+24*60*60-time();
            $day=intval($left_time/(24*60*60));
            $hours=intval(($left_time-$day*(24*60*60))/(60*60));
            $min=intval(($left_time-$day*(24*60*60)-$hours*3600)/60);
//            $second=intval($left_time-$day*(24*60*60)-$hours*3600-$min*60);
            if($day<1){
            echo "<span style='color:red;'>".$day." 天 ".$hours." 时 ".$min." 分 <br/></span>";
            }else{
                echo "<span style='color:#808080;'>".$day." 天 ".$hours." 时 ".$min." 分 <br/></span>";
            }
     ?>
     
     </td>

	<td style="text-align:left;">

    <div id="pat_<?php echo $item['order_id']; ?>">

		姓名：<?php if(!empty($item['zampo'])):?><del><?php endif;?><font color='#FF0000'><b id="pat_name_<?php echo $item['order_id']; ?>"><?php echo $item['pat_name']?></b></font>（<?php echo $item['pat_sex']; ?>、<?php echo $item['pat_age']?>岁）<a href="#sms_content" title="短信内容" role="button" data-toggle="modal" onClick="sms_content('<?php echo $item['order_id']?>');_czc.push(['_trackEvent', '预约相关', '<?php echo $admin['name']; ?>', '短信内容','','']);"><i class='icon-envelope'></i></a> <a href="#kefu_talk" title="对话记录" role="button" data-toggle="modal" onClick="kefu_talk('<?php echo $item['order_id']?>');_czc.push(['_trackEvent', '预约相关', '<?php echo $admin['name']; ?>', '对话记录','','']);"><i class='icon-comments'></i></a> <a href="#card_info" title="预约卡信息" role="button" data-toggle="modal" onClick="card_info('<?php echo $item['order_id']?>');_czc.push(['_trackEvent', '预约相关', '<?php echo $admin['name']; ?>', '预约卡信息','','']);"><i class='icon-camera'></i></a> <?php if($item['from_parent_id'] == 1):?><a href="#page_path" title="患者轨迹" role="button" data-toggle="modal" onClick="page_path('<?php echo $item['from_value']?>')"><i class='icon-hand-right'></i></a><?php endif;?> <?php if(!empty($item['pat_weixin'])):?><a href="#weixin" title="微信内容" role="button" data-toggle="modal" onClick="weixin_content('<?php echo $item['pat_weixin'];?>','<?php echo $item['order_id']?>');_czc.push(['_trackEvent', '预约相关', '<?php echo $admin['name']; ?>', '微信内容','','']);"><i class='icon-edit'></i></a><?php endif;?><?php if(!empty($item['zampo'])):?></del><?php endif;?><br />
		<?php if($_COOKIE['l_admin_id'] == '239'||$_COOKIE['l_admin_id'] == '215'):?>
		电话：<font id="pat_phone_<?php echo $item['order_id']; ?>"><?php echo $item['pat_phone']; if(!empty($item['pat_phone1'])){echo "/" . $item['pat_phone1'];}?></font><br />
		<?php else:?>
			<?php if($rank_type == 2 || $_COOKIE['l_admin_action'] == 'all' || $item['is_come'] > 0):?>
				<?php if(in_array($item['hos_id'],$hos_auth) && $rank_type == 2 && $item['admin_id'] != $_COOKIE['l_admin_id']):?>
				 电话：<font id="pat_phone_<?php echo $item['order_id']; ?>"><?php echo substr($item['pat_phone'],0, -4) . "****"; if(!empty($item['pat_phone1'])){echo "/" . substr($item['pat_phone1'],0, -4) . "****";}?></font><br />
				<?php else:?>
				电话：<font id="pat_phone_<?php echo $item['order_id']; ?>"><?php echo $item['pat_phone']; if(!empty($item['pat_phone1'])){echo "/" . $item['pat_phone1'];}?></font><br />
				<?php endif;?>
			<?php else:?>
			

			电话：<font id="pat_phone_<?php echo $item['order_id']; ?>"><?php echo substr($item['pat_phone'],0, -4) . "****"; if(!empty($item['pat_phone1'])){echo "/" . substr($item['pat_phone1'],0, -4) . "****";}?></font><br />

			<?php endif;?>
		<?php endif;?>
	
		地区：<?php

		  if($item['pat_province'] > 0){ echo $area[$item['pat_province']]['region_name'];}

		  if($item['pat_city'] > 0){ echo "、" . $area[$item['pat_city']]['region_name'];}

		  if($item['pat_area'] > 0){ echo "、" . $area[$item['pat_area']]['region_name'];}?><br />

		  <?php if(strcmp($_COOKIE['l_admin_action'],'all') == 0){?>数据中心状态: <?php if(empty($item['ireport_order_id'])){echo '<span style="color:red;">未推送</span>'; }
		 else if(!empty($item['ireport_order_id'])){echo '<span style="color:red;">已推送 </span> ';if(!empty($item['ireport_msg'])){echo '<span style="color:red;">'.$item['ireport_msg'].'</span>';}}?>
		 <?php echo '<br/>';}?>
		 
		<?php

		  if(!empty($item['pat_qq'])){

			  echo $item['pat_qq'] . "(QQ)";

		  }

		  elseif(isset($item['data_time']))

		  {

			  echo "【孕周】" . (intval((time() - $item['data_time']) / (86400 * 7)) + 1) . "周，第" . intval((time() - $item['data_time']) / (86400)) . "天，【预产期】" . date("Y-m-d", ($item['data_time'] + (86400 * 280)));

		  }

		?>

    </div>

    </td>

	<td style="text-align:left;">

		<?php echo $this->lang->line('order_addtime'); ?>：<font id="order_addtime_<?php echo $item['order_id']; ?>"><?php echo $item['order_addtime']; ?></font><br />

		<?php echo $this->lang->line('order_time'); ?>：<font id="order_time_<?php echo $item['order_id']; ?>"><?php echo $item['order_time']; ?></font> 
		<font style="color:#F00; font-weight:bold;" id="order_time_duan_<?php echo $item['order_id']; ?>"><?php if($item['order_time_duan']){ echo $item['order_time_duan'];}?></font><br />

		<?php echo $this->lang->line('come_time'); ?>：<span id="come_time_<?php echo $item['order_id']; ?>"><?php if($item['come_time'] > 0){ echo date("Y-m-d H:i:s", $item['come_time']);}?></span><br />

		<?php 
		//温州男科 外线科    hos_id= 37 AND keshi_id in(96,123) AND from_id not in(195,213)  AND is_come=0
		//东方医院   hos_id= 6 AND keshi_id in(28,33,34,35,90) AND from_parent_id not in(169,170) AND from_id not in(213)  AND is_come=0
		//台州医院   hos_id=3 AND keshi_id in(4,26,95,92) AND from_parent_id not in(169,170) AND from_id not in(213)  AND is_come=0
		//仁爱医院   hos_id= 1 and keshi_id in(1,32) AND is_come=0
		$keshi_goonghai = array(37,6,3,1);
		if(in_array($item['hos_id'],$keshi_goonghai) && $item['is_come'] == 0 && empty($item['zampo'])){
			$keshi_goonghai_wz = array(96,123);
			$keshi_goonghai_df = array(28,33,34,35,90);
			$keshi_goonghai_tz = array(4,26,95,92);
			$keshi_goonghai_ra = array(1,32); 
			
			$from_id_goonghai_wz = array(195,213);
			$from_id_goonghai_df = array(213);
			$from_id_goonghai_tz = array(213); 
			 
			$from_parent_id_goonghai_df = array(169,170);
			$from_parent_id_goonghai_tz = array(169,170); 
			 
			$start_goonghai_time=strtotime(date("Y-m-d",time())." 00:00:00");
			$order_goonghai_time= strtotime($item['order_time']." 00:00:00");
			$order_goonghai_addtime= strtotime(date("Y-m-d",strtotime($item['order_addtime'].":00"))." 00:00:00");
			//如果存在公海捞取时间  则将预约数据的添加时间更新为公海捞出的时间
			if(!empty($item['gonghai_time'])){
				$order_goonghai_addtime= strtotime(date("Y-m-d",$item['gonghai_time'])." 00:00:00");
			} 
			$shree_ten_time=strtotime(date("Y-m-d",time()-30*24*60*60)." 00:00:00"); 
			$end_ten_time=strtotime(date("Y-m-d",time()+30*24*60*60)." 00:00:00");
			//echo date("Y-m-d",strtotime($item['order_addtime'].":00"))." 00:00:00".'/'.date("Y-m-d",time()-30*24*60*60)." 00:00:00".'<br/>';
			if(in_array($item['keshi_id'],$keshi_goonghai_wz) && !in_array($item['from_id'],$from_id_goonghai_wz)){
				if(!empty($item['order_time']) && $order_goonghai_addtime >= $shree_ten_time){
					if($start_goonghai_time <= $order_goonghai_time   && $order_goonghai_time <= $end_ten_time ){//如果当前时间小于预约时间 则预约时间 - 当前时间 等于待流入公海时间
						echo "<span style='color:red;'>距流入公海还剩：".($order_goonghai_time-$start_goonghai_time)/(24*60*60).'天</span>';
					}else if($order_goonghai_addtime < $start_goonghai_time){//如果当前时间大于预约时间 则 预约时间 - 30天强制流入指定时间   等于待流入公海时间 
						echo "<span style='color:red;'>距强制流入公海还剩：".($order_goonghai_addtime+30*24*60*60-$start_goonghai_time)/(24*60*60).'天</span>';
					}
				}
			}else if(in_array($item['keshi_id'],$keshi_goonghai_df) && !in_array($item['from_id'],$from_id_goonghai_df) && !in_array($item['from_parent_id'],$from_parent_id_goonghai_df) ){
				  if(!empty($item['order_time']) && $order_goonghai_addtime >= $shree_ten_time){
					if($start_goonghai_time <= $order_goonghai_time   && $order_goonghai_time <= $end_ten_time ){//如果当前时间小于预约时间 则预约时间 - 当前时间 等于待流入公海时间
						echo "<span style='color:red;'>距流入公海还剩：".($order_goonghai_time-$start_goonghai_time)/(24*60*60).'天</span>';
					}else if($order_goonghai_addtime < $start_goonghai_time){//如果当前时间大于预约时间 则 预约时间 - 30天强制流入指定时间   等于待流入公海时间
						echo "<span style='color:red;'>距强制流入公海还剩：".($order_goonghai_addtime+30*24*60*60-$start_goonghai_time)/(24*60*60).'天</span>';
					}
				}
			}else if(in_array($item['keshi_id'],$keshi_goonghai_tz) && !in_array($item['from_id'],$from_id_goonghai_tz) && !in_array($item['from_parent_id'],$from_parent_id_goonghai_tz) ){
				 if(!empty($item['order_time']) && $order_goonghai_addtime >= $shree_ten_time ){
					if($start_goonghai_time <= $order_goonghai_time   && $order_goonghai_time <= $end_ten_time ){//如果当前时间小于预约时间 则预约时间 - 当前时间 等于待流入公海时间
						echo "<span style='color:red;'>距流入公海还剩：".($order_goonghai_time-$start_goonghai_time)/(24*60*60).'天</span>';
					}else if($order_goonghai_addtime < $start_goonghai_time){//如果当前时间大于预约时间 则 预约时间 - 30天强制流入指定时间   等于待流入公海时间
						echo "<span style='color:red;'>距强制流入公海还剩：".($order_goonghai_addtime+30*24*60*60-$start_goonghai_time)/(24*60*60).'天</span>';
					}
				}
			}
		} 
		?> 
		<br />
		
		<!--<?php echo $this->lang->line('doctor_time'); ?>：<span id="doctor_time_<?php echo $item['order_id']; ?>"><?php if($item['doctor_time'] > 0){ echo date("Y-m-d H:i:s", $item['doctor_time']);} ?></span>--></td>

	<td>

	<?php

    if(isset($from_list[$item['from_parent_id']])){  echo $from_list[$item['from_parent_id']]['from_name'] . "<br />"; }

    if(isset($from_arr[$item['from_id']])){  echo $from_arr[$item['from_id']]['from_name'] . "<br />"; }

    if(isset($type_list[$item['order_type']])){ echo $type_list[$item['order_type']]['type_name'];}

	?>

    </td>

	<td><?php

    if(isset($keshi[$item['keshi_id']])){echo $keshi[$item['keshi_id']]['keshi_name'];}

	?></td>

	<td>

	<?php

      if(isset($jibing[$item['jb_parent_id']])){echo $jibing[$item['jb_parent_id']]['jb_name'];}

	  if(isset($jibing[$item['jb_id']])){echo "<br />" . $jibing[$item['jb_id']]['jb_name'];}

	?></td>

    <td><?php echo $item['admin_name']?><br /><br />
	<input type="hidden" name="hos_id_<?php echo $item['order_id'];?>" id="hos_id_<?php echo $item['order_id'];?>" value="<?php echo $item['hos_id'];?>" />
	<input type="hidden" name="keshi_id_<?php echo $item['order_id'];?>" id="keshi_id_<?php echo $item['order_id'];?>" value="<?php echo $item['keshi_id'];?>" />
	<span id="doctor_<?php echo $item['order_id'];?>" style="color:#00F"><?php echo $item['doctor_name']?></span>
	</td>

    <td style="position:relative;">

    <div class="remark" id="visit_<?php echo $item['order_id']; ?>">
	
		<?php 
		if(!empty($item['zampo'])){
		
			$str_out = '<blockquote><p><font color=#FF0000>（该预约用户确认不来院）</font></p><small><a>' . $item['z_name'] . '</a> <cite>' . date("m-d H:i",$item['zampo']) . '</cite></small></blockquote>';
			echo $str_out;
		}
		

		if(isset($item['mark'][3])):

		$s = count($item['mark'][3]);

		foreach($item['mark'][3] as $val):

		?>

		<blockquote>

        <p><?php echo $val['mark_content']; if($val['type_id']){ echo "<font color='#FF0000'>（未到诊原因：" . $dao_false_arr[$val['type_id']] . "）</font>";} ?></p>

		<small><a href="###"><?php echo $val['r_admin_name'];?></a> <cite><?php echo date("m-d H:i", $val['mark_time']);?></cite><i>【<?php echo $s;?>】</i></small>

        </blockquote>

		<?php 

		$s --;

		endforeach;

		endif; 
		
		

		?>

    </div>

    </td>

	<td style="position:relative;">

    <div class="remark" id="notice_<?php echo $item['order_id']; ?>">

		<?php 

		if(isset($item['mark'])):

		foreach($item['mark'] as $key=>$val):

			if($key != 3):

			foreach($val as $v):

			if($v['mark_content'] != "1"):

		?>

		<blockquote <?php if($key == 2){ echo ' class="d"';}elseif($key == 1){ echo ' class="g"';}elseif($key == 5){ echo ' class="doc"';}else{ echo ' class="r"';}?>>

        <p><?php echo $v['mark_content']; ?></p>

		<small><a href="###"><?php if($key == 4){ echo '短信回复';} else{ echo $v['r_admin_name'];}?></a> <cite><?php echo date("m-d H:i", $v['mark_time']);?></cite></small>

        </blockquote>

		<?php 

			endif;

			endforeach;

			endif; 

		endforeach;

		endif; 

		?>

    </div>

	</td>

	<td>
		

		<?php 
		 if(empty($item['is_to_gonghai'])):

		if(empty($item['zampo'])):
		if($rank_type == 2 || $rank_type == 3 || $_COOKIE['l_admin_action'] == 'all'):
		?>

		<?php 
		if((in_array(66, $admin_action)) || ($_COOKIE['l_admin_action'] == 'all') || ($rank_type == 2)):
		?>
			<?php if(in_array($item['hos_id'],$hos_auth)):?>
				<?php if($item['admin_id'] == $_COOKIE['l_admin_id'] || $_COOKIE['l_admin_id']== '239' || $_COOKIE['l_admin_id']== '71' || $_COOKIE['l_admin_action'] == 'all'):?>
					<a href="javascript:order_window('?c=gonghai&m=order_info&type=mi&order_id=<?php
					echo $item['order_id'];
					if(!in_array(66, $admin_action) && ($_COOKIE['l_admin_action'] != 'all'))
					{
						echo "&p=2";
					}

					?>');" class="btn btn-info" onClick="_czc.push(['_trackEvent', '预约列表', '<?php echo $admin['name']; ?>', '编辑','','']);"><?php echo $this->lang->line('edit'); ?></a>
				<?php endif;?>
			<?php else:?>
				<a href="javascript:order_window('?c=gonghai&m=order_info&type=mi&order_id=<?php

				echo $item['order_id'];

				if(!in_array(66, $admin_action) && ($_COOKIE['l_admin_action'] != 'all'))

				{

					if($item['admin_id'] != $_COOKIE['l_admin_id'])

					{

						echo "&p=1";

					}

					else

					{

						echo "&p=2";

					}

				}

				?>');" class="btn btn-info" onClick="_czc.push(['_trackEvent', '预约列表', '<?php echo $admin['name']; ?>', '<?php echo $this->lang->line('edit'); ?>','','']);"><?php echo $this->lang->line('edit'); ?></a>
			<?php endif;?>
		<?php endif;?>

		<?php if(empty($item['doctor_time'])):?><a href="#visit" role="button" class="btn btn-success" data-toggle="modal" onClick="ajax_remark(<?php echo $item['order_id']?>);_czc.push(['_trackEvent', '预约列表', '<?php echo $admin['name']; ?>', '<?php echo $this->lang->line('visit'); ?>','','']);"><?php echo $this->lang->line('visit'); ?></a><?php endif;?>

		<?php 

		endif;

		if($rank_type == 3 || $_COOKIE['l_admin_action'] == 'all'):

		?>

		<?php if(empty($item['come_time'])):?><a href="#dao" role="button" class="btn btn-danger" data-toggle="modal" onClick="ajax_dao(<?php echo $item['order_id']?>);_czc.push(['_trackEvent', '预约列表', '<?php echo $admin['name']; ?>', '<?php echo $this->lang->line('come'); ?>','','']);"><?php echo $this->lang->line('come'); ?></a><?php endif;?>
		<?php

        endif;

		if($rank_type == 1 || $_COOKIE['l_admin_action'] == 'all'):

		?>

        <!--<?php if(empty($item['doctor_time'])):?><a href="#doctor" role="button" class="btn btn-success" data-toggle="modal" onClick="ajax_doctor(<?php echo $item['order_id']?>)"><?php echo $this->lang->line('doctor'); ?></a><?php endif;?>-->

        <?php

        endif;
		endif;
		?>
		<?php
		if($rank_type == 2 || $_COOKIE['l_admin_action'] == 'all'):

		?>

        <?php if(empty($item['zampo'])):?><a href="#doctor" role="button" class="btn"  onClick="ajax_out(<?php echo $item['order_id']?>);_czc.push(['_trackEvent', '预约列表', '<?php echo $admin['name']; ?>', '取消','','']);">取消</a><?php else:?><a href="#doctor" role="button" class="btn"  onClick="ajax_out(<?php echo $item['order_id']?>);_czc.push(['_trackEvent', '预约列表', '<?php echo $admin['name']; ?>', '恢复','','']);">恢复</a><?php endif;?>

        <?php

        endif;

		?>

   <?php  else:?>

        <font style="color:#ff6060;font-size:14px;font-weight:normal;">流入公海</font>
	 <?php endif; ?>

	  <?php $hos_id_in_array= array(6,37,42,3,39);
 if(in_array($item['hos_id'],$hos_id_in_array)){?> 
    	 <a href="javaScript:void(0);"   class="btn"  onClick="yycard(<?php echo $item['order_id']?>,<?php echo $item['hos_id']?>);_czc.push(['_trackEvent', '预约列表', '<?php echo $admin['name']; ?>', '查看预约卡','','']);">查看预约卡</a>
    <?php  }?>
    
      <a href="javaScript:void(0);"   class="btn"  onClick="ajax_get_fz(<?php echo $item['order_id']?>);_czc.push(['_trackEvent', '预约列表', '<?php echo $admin['name']; ?>', '复诊备注','','']);">复诊备注</a>
   
	</td>

  </tr>

  <?php 

  $i ++;
    
  
endforeach;
}
 ?>

  </tbody>

  </table>

<?php echo $page; ?>
                    
           
<div style="margin-bottom:30px;"></div>

	<div id="visit" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"   style="top:5%;">

		<div class="modal-header">

			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

			<h3 id="myModalLabel1">回访记录</h3>

		</div>

		<div class="modal-body" style="max-height:440px;">

			<div class="control-group">

				<div class="controls" id="patient_info"></div>

			</div>
			<div class="control-group">
				<div class="span6">
				<label class="control-label">回访主题</label>

				<div class="controls">
					<select name="zt_id" id="zt_id">

                    <option value="0"><?php echo $this->lang->line('please_select'); ?></option>

                    <?php foreach($huifang['zt'] as $key=>$val):?>

                    <option value="<?php echo $key; ?>"><?php echo $val; ?></option>

                    <?php endforeach;?>

                    </select>
					
				</div>
				</div>
				
				<div class="span6">
				<label class="control-label">回访类型</label>

				<div class="controls">
					<select name="lx_id" id="lx_id">

                    <option value="0"><?php echo $this->lang->line('please_select'); ?></option>

                    <?php foreach($huifang['lx'] as $key=>$val):?>

                    <option value="<?php echo $key; ?>"><?php echo $val; ?></option>

                    <?php endforeach;?>

                    </select>
					
				</div>
				</div>
			</div>
			<div class="control-group">
				<div class="span6">
				<label class="control-label">回访状态</label>

				<div class="controls">
					<select name="jg_id" id="jg_id">

                    <option value="0"><?php echo $this->lang->line('please_select'); ?></option>

                    <?php foreach($huifang['jg'] as $key=>$val):?>

                    <option value="<?php echo $key; ?>"><?php echo $val; ?></option>

                    <?php endforeach;?>

                    </select>
					
				</div>
				</div>
				
				<div class="span6">
				<label class="control-label">客户流向</label>

				<div class="controls">
					<select name="ls_id" id="ls_id">

                    <option value="0"><?php echo $this->lang->line('please_select'); ?></option>

                    <?php foreach($huifang['ls'] as $key=>$val):?>

                    <option value="<?php echo $key; ?>"><?php echo $val; ?></option>

                    <?php endforeach;?>

                    </select>
					
				</div>
				</div>
			</div>

			<div class="control-group">
				
				<div class="span6">
				<label class="control-label"><?php echo $this->lang->line('false_name');?></label>

				<div class="controls">
					<select name="false_id" id="false_id">

                    <option value="0"><?php echo $this->lang->line('please_select'); ?></option>

                    <?php foreach($dao_false as $val):?>

                    <option value="<?php echo $val['false_id']; ?>"><?php echo $val['false_name']; ?></option>

                    <?php endforeach;?>

                    </select>
					
				</div>
				</div>
				<div class="span6">
				<label class="control-label">下次预到<color id="order_time_type_msg" style="color:red;"></color></label>

				<div class="controls">
                                    <input type="text" value="<?php echo date("Y-m-d",time())?>"  name="nextdate" id="nextdate" style="width:80px;float:left;" />
					<div class="input-append" style="margin-left:5px; float:left;">
						 <input type="text" name="datehour" id="datehour" style="width:80px;"  value="10:00"/>
					</div> 
					
				</div>
				<div class="date_lx" style="display:none;position:absolute;"><div class="lxdate"></div></div>
				</div>
			</div>

            <div class="control-group">

				<label class="control-label"><?php echo $this->lang->line('notice'); ?></label>

				<div class="controls">

					<textarea class="input-xxlarge " rows="5" name="visit_remark" id="visit_remark" style="width:520px;"></textarea>

				</div>

			</div>

		</div>

		<div class="modal-footer">

			<input type="hidden" name="order_id" id="visit_order_id" value="" />

			<button class="btn" data-dismiss="modal" aria-hidden="true"> 关闭 </button>

			<button class="btn btn-primary"  onClick="visit_add();"> 提交 </button>

		</div>

	</div>
	<div id="weixin" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">

		<div class="modal-header">

			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

			<h3>发送微信消息</h3>

		</div>

		<div class="modal-body">
			
			<div class="control-group">

			<label class="control-label">微信模版</label>

			<div class="controls">

				<select name="weixin_themes" id="weixin_themes" class="input-small m-wrap" style="width:150px;">

                   <option value="0"><?php echo $this->lang->line('please_select');?></option>

                </select>

			</div>

			</div>

			

            <div class="control-group">

				<label class="control-label">填写要发送的信息</label>

				<div class="controls">

					<textarea class="input-xxlarge " rows="5" name="weixin_remark" id="weixin_remark" style="width:520px;"></textarea>

				</div>

			</div>

		</div>

		<div class="modal-footer">

			<input type="hidden" name="weixin_id" id="weixin_user_id" value="" />
			<input type="hidden" name="order_id" id="weixin_order_id" value="" />

			<button class="btn" data-dismiss="modal" aria-hidden="true"> 关闭 </button>

			<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onClick="weixin_send();"> 提交 </button>

		</div>

	</div>
    

    <div id="dao" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">

		<div class="modal-header">

			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

			<h3 id="myModalLabel1">患者来院</h3>

		</div>

		<div class="modal-body">

			<div class="control-group">

				<div class="controls" id="dao_patient_info"></div>

			</div>

            <div class="control-group">

				<label class="control-label"><?php echo $this->lang->line('doctor_name'); ?></label>

				<div class="controls" id="likeBd" style="position:relative;">

					<input type="text" class="input-xxlarge" name="dao_doctor_name" id="dao_doctor_name" value="" style="width:520px;" />

				</div>

			</div>

            <div class="control-group">

				<label class="control-label"><?php echo $this->lang->line('notice'); ?></label>

				<div class="controls">

					<textarea class="input-xxlarge " rows="5" name="dao_remark" id="dao_remark" style="width:520px;"></textarea>

				</div>

			</div>

		</div>

		<div class="modal-footer">

			<input type="hidden" name="order_id" id="dao_order_id" value="" />

			<button class="btn" data-dismiss="modal" aria-hidden="true"> 关闭 </button>

			<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onClick="dao_add();"> 来院 </button>

		</div>

	</div>

    

    <div id="doctor" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">

		<div class="modal-header">

			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

			<h3 id="myModalLabel1">患者就诊</h3>

		</div>

		<div class="modal-body">

			<div class="control-group">

				<div class="controls" id="doctor_patient_info"></div>

			</div>

            <div class="control-group">

				<label class="control-label"><?php echo $this->lang->line('doctor_name'); ?></label>

				<div class="controls">

					<input type="text" class="input-xxlarge" name="doctor_name" id="doctor_name" value="" style="width:520px;" />

				</div>

			</div>

            <div class="control-group">

				<label class="control-label"><?php echo $this->lang->line('notice'); ?></label>

				<div class="controls">

					<textarea class="input-xxlarge" rows="5" name="doctor_remark" id="doctor_remark" style="width:520px;"></textarea>

				</div>

			</div>

		</div>

		<div class="modal-footer">

			<input type="hidden" name="order_id" id="doctor_order_id" value="" />

			<button class="btn" data-dismiss="modal" aria-hidden="true"> 关闭 </button>

			<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onClick="doctor_add();"> 到诊 </button>

		</div>

	</div>

    

    

    <div id="page_path" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true" style="width:800px; left:40%;">

		<div class="modal-header">

			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

			<h3 id="myModalLabel1">患者访问轨迹（ID：<span id="swt_id"></span>）</h3>

		</div>

		<div class="modal-body" id="page_path_body">

			

		</div>

		<div class="modal-footer">

			<button class="btn" data-dismiss="modal" aria-hidden="true"> 关闭 </button>

		</div>

	</div>

    <div id="kefu_talk" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true" style="width:80%; left:30%;">

		<div class="modal-header">

			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

			<h3 id="myModalLabel1">对话记录</h3>

		</div>

		<div class="modal-body" id="con_content">

		</div>
		


		<div class="modal-footer">

			<button class="btn" data-dismiss="modal" aria-hidden="true"> 关闭 </button>

		</div>

	</div>
	
	<div id="card_info" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true" style="width:80%; left:30%;">

		<div class="modal-header">

			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

			<h3 id="myModalLabel1">预约卡信息</h3>

		</div>

		<div class="modal-body" id="card_content">

		</div>
		


		<div class="modal-footer">
			<input type="hidden" id="card_order_id" value="" />
			<span class="btn btn-success" onClick="reset_card();"> 重新生成</span>
			<button class="btn" data-dismiss="modal" aria-hidden="true"> 关闭 </button>

		</div>

	</div>

    <div id="sms_content" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true" style="width:800px; left:40%;">

		<div class="modal-header">

			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

			<h3 id="myModalLabel1">短信内容</h3>

		</div>

		<div class="modal-body sms_content" id="sms_content_body">

		</div>

		<div class="modal-footer">

			<button class="btn" data-dismiss="modal" aria-hidden="true"> 关闭 </button>

            <a class="btn btn-primary" href="#sms_send" onClick="sms_send()" role="button" data-toggle="modal" data-dismiss="modal" aria-hidden="true"> 发送短信 </a>

            <input type="hidden" value="" id="sms_order_id" />

		</div>

	</div>

    <div id="sms_reply" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true" style="width:800px; left:40%;">

		<div class="modal-header">

			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

			<h3 id="myModalLabel1">获取回复短信内容</h3>

		</div>

		<div class="modal-body" id="sms_reply_body">

		</div>

		<div class="modal-footer">

			<button class="btn" data-dismiss="modal" aria-hidden="true" onClick="window.location.reload();"> 关闭 </button>

		</div>

	</div>

    <div id="sms_send" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true" style="width:800px; left:40%;">

		<div class="modal-header">

			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

			<h3 id="myModalLabel1">发送短信</h3>

		</div>

		<div class="modal-body">

          <div class="control-group">

			<div class="controls">

              预约号码：<font id="sms_order_no"></font><br />

              患者姓名：<font id="sms_pat_name"></font><br />

              联系电话：<font id="sms_pat_phone"></font><br />

              登记时间：<font id="sms_order_addtime"></font><br />

              预约时间：<font id="sms_order_time"></font>&nbsp;<font id="sms_order_time_duan"></font>

            </div>

		  </div>

          <div class="control-group">

			<label class="control-label"><?php echo $this->lang->line('sms_themes'); ?></label>

			<div class="controls">

				<select name="sms_themes" id="sms_themes" class="input-small m-wrap" style="width:150px;">

                   <option value="0"><?php echo $this->lang->line('please_select');?></option>

                </select>
				&nbsp;&nbsp;&nbsp;<span>短信端口：</span>
				<select name="sms_id" id="sms_id" class="input-small m-wrap" style="width:150px;">

                   <option value="0"><?php echo $this->lang->line('please_select');?></option>

                </select>

			</div>

		  </div>

          <div class="control-group">

            <label class="control-label"><?php echo $this->lang->line('sms_content');?></label>

            <div class="controls">

                <textarea class="input-xxlarge " rows="5" name="sms_content_area" id="sms_content_area" style="width:600px;"></textarea><br />请确认短信结尾有签名，否则发送失败！

            </div>

        </div>

		</div>

		<div class="modal-footer">

			<button class="btn" data-dismiss="modal" aria-hidden="true"> 关闭 </button>

            <button class="btn btn-primary" onClick="sms_send_ok();"> 立即发送 </button>

		</div>

	</div>

</div>

</div>

</div>

</div>

</div>
<div id="fade" class="black_overlay">
</div>

					<div class="widget green white_content" id="MyDiv">
							<div class="widget-title">
							<h4><i class="icon-reorder"></i> 生成预约卡</h4>
							<span class="tools">


								<span style="font-size: 14px;line-height:12px;color:#fff;cursor: pointer;" onClick="CloseDiv('MyDiv','fade')">关闭</span>
							</span>
							</div>
							<div class="widget-body" id="wx_order">
								
							</div>
					</div>

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

<script language="javascript">

var dao_false_arr = new Array();

<?php 

foreach($dao_false_arr as $key=>$val)

{

	echo "dao_false_arr[$key] = \"$val\";\r\n";

}

?>

$(document).ready(function(e) {
	
	//获取div层
	var $likeBd = $('#likeBd');
	//获取要操作的输入框
	var $dao_doctor_name = $likeBd.find('#dao_doctor_name');
	//关闭浏览器的记忆功能
	$dao_doctor_name.attr('autocomplete','off'); 
	//创建自动完成的下拉列表，用于显示服务器返回的数据,插入在输入框的后面，等显示的时候再调整位置 
	var $autocomplete = $('<div class="autocomplete"></div>') 
	.hide() 
	.insertAfter('#dao_doctor_name'); 
	//清空下拉列表的内容并且隐藏下拉列表区 
	var clear = function(){ 
	$autocomplete.empty().hide(); 
	}; 
	//注册事件，当输入框失去焦点的时候清空下拉列表并隐藏 
	$dao_doctor_name.blur(function(){ 
	setTimeout(clear,500); 
	});
	//下拉列表中高亮的项目的索引，当显示下拉列表项的时候，移动鼠标或者键盘的上下键就会移动高亮的项目，像百度搜索那样 
	var selectedItem = null; 
	//timeout的ID 
	var timeoutid = null; 
	//设置下拉项的高亮背景 
	var setSelectedItem = function(item){ 
	//更新索引变量 
	selectedItem = item ; 
	//按上下键是循环显示的，小于0就置成最大的值，大于最大值就置成0 
	if(selectedItem < 0){ 
	selectedItem = $autocomplete.find('li').length - 1; 
	} 
	else if(selectedItem > $autocomplete.find('li').length-1 ) { 
	selectedItem = 0; 
	} 
	//首先移除其他列表项的高亮背景，然后再高亮当前索引的背景 
	$autocomplete.find('li').removeClass('highlight') 
	.eq(selectedItem).addClass('highlight'); 
	}; 
	
	var ajax_request = function(){ 
	//ajax服务端通信 
	$.ajax({ 
	'url':'?c=order&m=search_ajax', //服务器的地址 
	'data':{'search_text':$dao_doctor_name.val()}, //参数 
	'dataType':'json', //返回数据类型 
	'type':'POST', //请求类型 
	'success':function(data){ 
	if(data.length) { 
	//遍历data，添加到自动完成区 
	$.each(data, function(index,term) { 
	//创建li标签,添加到下拉列表中 
	$('<li></li>').text(term).appendTo($autocomplete) 
	.addClass('clickable') 
	.hover(function(){ 
	//下拉列表每一项的事件，鼠标移进去的操作 
	$(this).siblings().removeClass('highlight'); 
	$(this).addClass('highlight'); 
	selectedItem = index; 
	},function(){ 
	//下拉列表每一项的事件，鼠标离开的操作 
	$(this).removeClass('highlight'); 
	//当鼠标离开时索引置-1，当作标记 
	selectedItem = -1; 
	}) 
	.click(function(){ 
	//鼠标单击下拉列表的这一项的话，就将这一项的值添加到输入框中 
	$dao_doctor_name.val(term); 
	//清空并隐藏下拉列表 
	$autocomplete.empty().hide(); 
	}); 
	});//事件注册完毕 
	//设置下拉列表的位置，然后显示下拉列表 
	var ypos = $dao_doctor_name.position().top; 
	var xpos = $dao_doctor_name.position().left; 
	$autocomplete.css('width',$dao_doctor_name.css('width')); 
	$autocomplete.css({'position':'relative','left':xpos + "px",'top':ypos +"px"}); 
	setSelectedItem(0); 
	//显示下拉列表 
	$autocomplete.show(); 
	} 
	} 
	}); 
	}; 
	
	//对输入框进行事件注册 
	$dao_doctor_name.keyup(function(event) { 
	//字母数字，退格，空格 
	if(event.keyCode > 40 || event.keyCode == 8 || event.keyCode ==32) { 
	//首先删除下拉列表中的信息 
	$autocomplete.empty().hide(); 
	clearTimeout(timeoutid); 
	timeoutid = setTimeout(ajax_request,100); 
	} 
	else if(event.keyCode == 38){ 
	//上 
	//selectedItem = -1 代表鼠标离开 
	if(selectedItem == -1){ 
	setSelectedItem($autocomplete.find('li').length-1); 
	} 
	else { 
	//索引减1 
	setSelectedItem(selectedItem - 1); 
	} 
	event.preventDefault(); 
	} 
	else if(event.keyCode == 40) { 
	//下 
	//selectedItem = -1 代表鼠标离开 
	if(selectedItem == -1){ 
	setSelectedItem(0); 
	} 
	else { 
	//索引加1 
	setSelectedItem(selectedItem + 1); 
	} 
	event.preventDefault(); 
	} 
	}) 
	.keypress(function(event){ 
	//enter键 
	if(event.keyCode == 13) { 
	//列表为空或者鼠标离开导致当前没有索引值 
	if($autocomplete.find('li').length == 0 || selectedItem == -1) { 
	return; 
	} 
	$dao_doctor_name.val($autocomplete.find('li').eq(selectedItem).text()); 
	$autocomplete.empty().hide(); 
	event.preventDefault(); 
	} 
	}) 
	.keydown(function(event){ 
	//esc键 
	if(event.keyCode == 27 ) { 
	$autocomplete.empty().hide(); 
	event.preventDefault(); 
	} 
	}); 
	//注册窗口大小改变的事件，重新调整下拉列表的位置 
	$(window).resize(function() { 
	var ypos = $dao_doctor_name.position().top; 
	var xpos = $dao_doctor_name.position().left; 
	$autocomplete.css('width',$dao_doctor_name.css('width')); 
	$autocomplete.css({'position':'relative','left':xpos + "px",'top':ypos +"px"}); 
	}); 


	$(".list_table tr").hover(

	  function(){

	    $(this).addClass("over_list");

	  },

	  function(){

	    $(this).removeClass("over_list");

	  }

	);
	
	

	$(".remark").hover(

	  function(){

	      $(this).css({height:"auto", background:"#CCFFCC", "z-index":"999", "padding-bottom":"50px"});

	  },

	  function(){

	     $(this).css({height:"70px", background:"none", "z-index":"1", "padding-bottom":"10px"});

	  }

	);
	
	
	$("#province").change(function(){
		var province_id = $(this).val();
		ajax_area('city', province_id, 0, 2);
	});
	$("#city").change(function(){
		var city_id = $(this).val();
		ajax_area('area', city_id, 0, 3);
	});
//隐藏左边导航栏代码
//	$('#sidebar > ul').hide();
//
//	$("#container").addClass("sidebar-closed");

	

	$(".anniu").css("display", "block");

	$('.divdate').DatePicker({

		flat: true,

		date: ['<?php echo $start_date; ?>','<?php echo $end_date; ?>'],

		current: '<?php echo $end_date; ?>',

		format: 'Y年m月d日',

		calendars: 2,

		mode: 'range',

		starts: 1,

		onChange: function(formated) {

			$('#inputDate').val(formated.join(' - '));

		}

	});
	$('.lxdate').DatePicker({

		flat: true,

		date: [''],

		current: '',

		format: 'Y-m-d',

		calendars: 1,

		starts: 1,

		onChange: function(formated) {
			$('#nextdate').val(formated);
			$('.date_lx').hide();

		}

	});
	$("#nextdate").focus(function(){

		$('.date_lx').css("display", "block");

		$('.date_lx .datepicker').css({"width":"210px",'height':'160px','background':'black'});

	});

    $('.date_div').css("display", "none");

	$(".anniu .guanbi").click(function(){

		$('.date_div').css("display", "none");

	});

	$("#inputDate").focus(function(){

		$('.date_div').css("display", "block");

	});

	$(".anniu .today").click(function(){

		$('#inputDate').val(get_day(0) + " - " + get_day(0));

		$('.date_div').css("display", "none");

	});

	$(".anniu .week").click(function(){

		$('#inputDate').val(get_day(-6) + " - " + get_day(0));

		$('.date_div').css("display", "none");

	});

	$(".anniu .month").click(function(){

		$('#inputDate').val(get_day(-29) + " - " + get_day(0));

		$('.date_div').css("display", "none");

	});

	$(".anniu .year").click(function(){

		$('#inputDate').val(get_day(-364) + " - " + get_day(0));

		$('.date_div').css("display", "none");

	});

	$("#hos_id").change(function(){

		var hos_id = $(this).val();

		ajax_get_keshi(hos_id, 0);

	});

	

	$("#keshi_id").change(function(){

		var keshi_id = $(this).val();

		ajax_get_jibing(keshi_id, 0, 0);

	});

	

	$("#from_parent_id").change(function(){

		var parent_id = $(this).val();

		ajax_from(parent_id, 0);

	});

	

	$("#p_jb").change(function(){

		var parent_id = $(this).val();

		ajax_get_jibing(0, parent_id, 0);

	});

	

	/* 获取短信发送内容 */

	$("#sms_themes").change(function(){

		var themes_id = $(this).val();

		if(themes_id > 0)

		{

			var pat_name = $("#sms_pat_name").html();

			var pat_phone = $("#sms_pat_phone").html();

			var order_addtime = $("#sms_order_addtime").html();

			var order_time = $("#sms_order_time").html();

			var order_time_duan = $("#sms_order_time_duan").html();

			var order_no = $("#sms_order_no").html();

			var order_id = $("#sms_order_id").html();



			$.ajax({

				type:'post',

				url:'?c=order&m=sms_ajax',

				data:'pat_name=' + pat_name + '&order_addtime=' + order_addtime + '&pat_phone=' + pat_phone + '&type=list&order_time=' + order_time + '&order_no=' + order_no + '&order_id=' + order_id + '&themes_id=' + themes_id + '&order_time_duan=' + order_time_duan,

				success:function(data)

				{

					$("#sms_content_area").val(data);

				},

				complete: function (XHR, TS)

				{

				   XHR = null;

				}

			});

		}


	});
$("#weixin_themes").change(function(){

		var themes_id = $(this).val();

		if(themes_id > 0)

		{
			var order_id = $("#weixin_order_id").val();

			var hos_id = $("#hos_id_" + order_id).val();

			var keshi_id = $("#keshi_id_" + order_id).val();

			var order_no = $("#order_no_" + order_id).html();

			var pat_name = $("#pat_name_" + order_id).html();

			var pat_phone = $("#pat_phone_" + order_id).html();

			var order_addtime = $("#order_addtime_" + order_id).html();

			var order_time = $("#order_time_" + order_id).html();

			var order_time_duan = $("#order_time_duan_" + order_id).html();



			$.ajax({

				type:'post',

				url:'?c=order&m=sms_ajax',

				data:'pat_name=' + pat_name + '&order_addtime=' + order_addtime + '&pat_phone=' + pat_phone + '&type=list&order_time=' + order_time + '&order_no=' + order_no + '&order_id=' + order_id + '&themes_id=' + themes_id + '&order_time_duan=' + order_time_duan,

				success:function(data)

				{

					$("#weixin_remark").val(data);

				},

				complete: function (XHR, TS)

				{

				   XHR = null;

				}

			});

		}


	});
	

	

	/* ajax 获取回访备注内容 */

	var order_id = "<?php if(!empty($order_list)){foreach($order_list as $item){ echo $item['order_id'] . ",";}}?>";

	ajax_remark_list(order_id);



<?php if($hos_id > 0):?>

ajax_get_keshi(<?php echo $hos_id?>, <?php echo $keshi_id?>);

<?php endif;?>

<?php if($f_p_i > 0):?>

ajax_from(<?php echo $f_p_i?>, <?php echo $f_i?>);

<?php endif;?>

<?php if($pro > 0):?>
ajax_area('city', <?php echo $pro; ?>, <?php if(isset($city)){echo $city;}else{echo 0;}?>, 3);
<?php endif;?>

<?php if(isset($city)):?>
ajax_area('area', <?php echo $city; ?>, <?php if(isset($are)){echo $are;}else{echo 0;}?>, 3);
<?php endif;?>
});



<?php if($p_jb > 0):?>

ajax_get_jibing(0, <?php echo $p_jb?>, <?php echo $jb?>);

<?php endif;?>



function sms_send_ok()

{

	var order_id = $("#sms_order_id").val();

	var pat_phone = $("#sms_pat_phone").html();

	var sms_content = $("#sms_content_area").val();

	var hos_id = $("#hos_id_" + order_id).val();

	var keshi_id = $("#keshi_id_" + order_id).val();
	
	var sms_id = $("#sms_id").val();

	if(sms_content !== "")

	{

		$.ajax({

			type:'post',

			url:'?c=order&m=sms_send_ajax',

			data:'order_id=' + order_id + "&pat_phone=" + pat_phone + "&sms_content=" + encodeURIComponent(sms_content) + "&hos_id=" + hos_id + "&keshi_id=" + keshi_id + "&sms_id=" + sms_id,

			success:function(data)

			{

				window.location.reload();

			},

			complete: function (XHR, TS)

			{

			   XHR = null;

			}

		});

	}

}



function page_path(from_value)

{

	if(from_value == "")

	{

		$("#swt_id").html("请输入用户唯一身份ID");

		$("#page_path_body").html("请输入用户唯一身份ID");

	}

	else

	{

		$("#swt_id").html(String(from_value));

		$("#page_path_body").html("<i class='icon-refresh icon-spin'></i>");

		$.ajax({

			type:'post',

			url:'?c=order&m=page_path',

			data:'from_value=' + from_value,

			success:function(data)

			{

				$("#page_path_body").html(data);

			},

			complete: function (XHR, TS)

			{

			   XHR = null;

			}

		});

	}

}



function sms_send()

{

	var order_id = $("#sms_order_id").val();

	var hos_id = $("#hos_id_" + order_id).val();

	var keshi_id = $("#keshi_id_" + order_id).val();

	var order_no = $("#order_no_" + order_id).html();

	var pat_name = $("#pat_name_" + order_id).html();

	var pat_phone = $("#pat_phone_" + order_id).html();

	var order_addtime = $("#order_addtime_" + order_id).html();

	var order_time = $("#order_time_" + order_id).html();

	var order_time_duan = $("#order_time_duan_" + order_id).html();



	$("#sms_order_no").html(order_no);

	$("#sms_pat_name").html(pat_name);

	$("#sms_pat_phone").html(pat_phone);

	$("#sms_order_addtime").html(order_addtime);

	$("#sms_order_time").html(order_time);

	$("#sms_order_time_duan").html(order_time_duan);

	$("#sms_content_area").val("");

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
	$.ajax({

		type:'post',

		url:'?c=system&m=sms_id_ajax',

		data:'hos_id=' + hos_id,

		success:function(data)

		{

			$("#sms_id").html(data);

		},

		complete: function (XHR, TS)

		{

		   XHR = null;

		}

	});

}

function weixin_content(user_id,order_id)
{
	$("#weixin_remark").val('');
	$("#weixin_user_id").val(user_id);
	$("#weixin_order_id").val(order_id);
	
	var hos_id = $("#hos_id_" + order_id).val();
	$.ajax({

		type:'post',

		url:'?c=order&m=sms_themes_ajax',

		data:'hos_id=' + hos_id,

		success:function(data)

		{

			$("#weixin_themes").html(data);

		},

		complete: function (XHR, TS)

		{

		   XHR = null;


		}

	});
	
}

function weixin_send()
{
	var user_id = $("#weixin_user_id").val();
	var order_id = $("#weixin_order_id").val();
	var remark = $("#weixin_remark").val();
	
	if(remark == ''){
	
		alert('发送内容不能为空');
		return;
	}
	$.ajax({

		type:'post',

		url:'?c=weixin&m=ajax_send',

		data:'order_id=' + order_id + '&user_id=' +　user_id + '&remark=' + remark,

		success:function(data)

		{

			alert(data);

		},

		complete: function (XHR, TS)

		{

		   XHR = null;

		}
	});
}

function sms_content(order_id)

{

	$("#sms_content_body").html("<i class='icon-refresh icon-spin'></i>");

	$.ajax({

		type:'post',

		url:'?c=order&m=sms_content',

		data:'order_id=' + order_id,

		success:function(data)

		{

			$("#sms_content_body").html("");

			data = $.parseJSON(data);

			$.each(data, function(i, item){

				var html = "<blockquote";

				if(item.send_type == 3)

				{

					html += ' class="d"';

				}

				html += "><p>" + item.send_content + "</p>";

				html += "<small><a href=\"###\">"

				if(item.send_type == 3)

				{

					html += '患者：';

				}

				html += item.admin_name + "</a>&nbsp;&nbsp;<cite>" + item.send_time + "</cite>&nbsp;&nbsp;<i>【" + item.status +"】</i></small>";

				html += "</blockquote>";

				$("#sms_content_body").html($("#sms_content_body").html() + html);

			});

			$("#sms_order_id").val(order_id);

		},

		complete: function (XHR, TS)

		{

		   XHR = null;

		}

	});

}



function sms_reply(type)

{

	if(type == 2)

	{

		$("#sms_reply_body").html($("#sms_reply_body").html() + "&nbsp;<i class='icon-refresh icon-spin'></i>");

	}

	$.ajax({

		type:'post',

		url:'?c=order&m=sms_reply',

		data:'type=' + type,

		success:function(data)

		{

			if(type == 1)

			{

				$("#sms_reply_body").html("最后获取回复时间是：" + data + "，重新获取点击这里：<a href='javascript:sms_reply(2);'>获取回复</a>");

			}

			else

			{

				$("#sms_reply_body").html("短信获取成功！");

			}

		},

		complete: function (XHR, TS)

		{

		   XHR = null;

		}

	});

}

function reset_card()
{
	var order_id = $("#card_order_id").val();
	if(order_id >= 1)

	{
		$("#card_order_id").val(order_id);
		$.ajax({

			type:'post',

			url:'?c=order&m=card_info',

			data:'type=reset&order_id=' + order_id,

			success:function(data)

			{
				if(data == 1){
					$("#card_content").html('该医院下没有模版不存在');
				}else if(data == 2){
					$("#card_content").html('图片生成失败');
				}else{
					$("#card_content").html(data);
				}

			},

			complete: function (XHR, TS)

			{

			   XHR = null;

			}

		});

	}
	
}

function card_info(order_id)
{
	if(order_id >= 1)

	{
		$("#card_order_id").val(order_id);
		$.ajax({

			type:'post',

			url:'?c=order&m=card_info',

			data:'order_id=' + order_id,

			success:function(data)

			{
				if(data == 1){
					$("#card_content").html('该医院下没有模版不存在');
				}else if(data == 2){
					$("#card_content").html('图片生成失败');
				}else{
					$("#card_content").html(data);
				}

			},

			complete: function (XHR, TS)

			{

			   XHR = null;

			}

		});

	}

}

function kefu_talk(order_id)

{

	if(order_id >= 1)

	{

		$.ajax({

			type:'post',

			url:'?c=order&m=talk_info',

			data:'order_id=' + order_id,

			success:function(data)

			{

				$("#con_content").html(data);

			},

			complete: function (XHR, TS)

			{

			   XHR = null;

			}

		});

	}

}

function insert_talk(order_id)

{

	var con = $('#textareaz').val();
	var length = con.length;
	if(length > 100)
	{
		alert('哥们，够了，就100字而已');
		return;
	}
	if(length < 2)
	{
		alert('哥们，太懒了，至少你得写两个字吧');
		return;
	}
	if(order_id >= 1)

	{
	
	

		$.ajax({

			type:'post',

			url:'?c=order&m=insert_info',

			data:'order_id=' + order_id + '&content='+con,

			success:function(data)

			{

				$(".contentz").prepend(data);
				$("#textareaz").val('');

			},

			complete: function (XHR, TS)

			{

			   XHR = null;

			}

		});
	
	}

}



function order_window(url)

{

	window.open (url, 'newwindow', 'height=650, width=1100, top=50, left=50, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=no, status=no');

}



function jsearch(name)

{

	if(name == "null")

	{

		window.location.href = "http://www.renaidata.com/?c=order&m=order_list";

	}

	else

	{

		window.location.href = window.location.href + '&' + name;

	}

}



function ajax_remark_list(order_id)

{

	$("div[id^='visit_']").append("<i id='tag_visit'>...</i>");

	$("div[id^='notice_']").html("...");

	if(order_id == ",")

	{

		$("div[id^='visit_']").html("");

		$("div[id^='notice_']").html("");

	}

	var v_c = 1;

	$.ajax({

		type:'post',

		url:'?c=order&m=ajax_remark_list',

		data:'order_id=' + order_id,

		success:function(data)

		{

			data = $.parseJSON(data);

			$.each(data, function(i, item){

				if(item.mark_type == 3)

				{
					if($("#visit_" + item.order_id).children('#tag_visit').html() == "...")

					{

						$("#visit_" + item.order_id).children('#tag_visit').remove();
						v_c = 1;

					}

					var str = "";

					str += "<blockquote><p>" + item.mark_content;

					if(item.type_id > 0)

					{

						str += "<font color='#FF0000'>（未到诊原因：" + dao_false_arr[item.type_id] + "）</font>";

					}

					str += "</p><small><a href=\"###\">";

					str += item.admin_name + "</a> <cite>" + item.mark_time + "</cite><i>【" + v_c + "】</i></small></blockquote>";

					$("#visit_" + item.order_id).prepend(str + '...');

					v_c ++;

				}

				else

				{

					if($("#notice_" + item.order_id).html() == "...")

					{

						$("#notice_" + item.order_id).html("");

					}

					var str = "";

					str += "<blockquote";

					if(item.mark_type == 2)

					{

						str += ' class="d"';

					}

					else if(item.mark_type == 1)

					{

						str += ' class="g"';

					}

					else if(item.mark_type == 5)

					{

						str += ' class="doc"';

					}
					else if(item.mark_type == 6)
					{
						str += ' style="background-color: red;"';
					
					}

					else

					{

						str += ' class="r"';

					}

					str += "><p>" + item.mark_content + "</p><small><a href=\"###\">";

					if(item.mark_type == 4)

					{

						str += "短信回复";

					}

					else

					{

						str += item.admin_name;

					}

					str += "</a> <cite>" + item.mark_time + "</cite></small></blockquote>";

					$("#notice_" + item.order_id).html(str + $("#notice_" + item.order_id).html());

				}

			});

		},

		complete: function (XHR, TS)

		{

		   XHR = null;

		}

	});

}



function ajax_get_jibing(keshi_id, parent_id, check_id)

{

	$("#p_jb").after("<i class='icon-refresh icon-spin'></i>");

	$.ajax({

		type:'post',

		url:'?c=order&m=jibing_ajax',

		data:'keshi_id=' + keshi_id + '&parent_id=' + parent_id + '&check_id=' + check_id,

		success:function(data)

		{

			if(parent_id == 0)

			{

				$("#p_jb").html(data);

			}

			else

			{

				$("#jb").html(data);

			}

			

		},

		complete: function (XHR, TS)

		{

		   XHR = null;

		   $("#p_jb").next(".icon-spin").remove();

		}

	});

}





function get_day(day){  

       var today = new Date();  

       var targetday_milliseconds=today.getTime() + 1000*60*60*24*day;

       today.setTime(targetday_milliseconds); /* 注意，这行是关键代码 */   

       var tYear = today.getFullYear();  

       var tMonth = today.getMonth();  

       var tDate = today.getDate();  

       tMonth = doHandleMonth(tMonth + 1);  

       tDate = doHandleMonth(tDate);  

       return tYear + "年" + tMonth + "月" + tDate + "日";  

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



function doHandleMonth(month){  

       var m = month;  

       if(month.toString().length == 1){  

          m = "0" + month;  

       }  

       return m;  

}

function ajax_from(parent_id, from_id)

{

	$.ajax({

		type:'post',

		url:'?c=order&m=from_order_ajax',

		data:'parent_id=' + parent_id + '&from_id=' + from_id + '&tag=-1',

		success:function(data)

		{

		   $("#from_id").html(data);

		},

		complete: function (XHR, TS)

		{

		   XHR = null;

		}

	});	

}



function ajax_get_keshi(hos_id, check_id)

{

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

		}

	});

}



function ajax_remark(order_id)

{

	$("#visit").children("btn").css("display", "none");

	$("#visit_order_id").val(order_id);

	$("#false_id").val(0);

	$("#visit_remark").val("");
	var zt_id = $("#zt_id").val(0);
	
	var lx_id = $("#lx_id").val(0);
	
	var jg_id = $("#jg_id").val(0);
	
	var ls_id = $("#ls_id").val(0);
	
	var date_lx = $("#nextdate").val('<?php echo date("Y-m-d",time());?>');
	var datehour = $("#datehour").val('10:00');

	$("#patient_info").html($("#pat_" + order_id).html());
	$("#nextdate").val($("#order_hidden_time_check_"+order_id).val());
	$("#datehour").val($("#order_time_duan_check_"+order_id).val()); 
	/**
	if($("#keshi_id_"+order_id).val() == 32 || $("#keshi_id_"+order_id).val() == 1){
		if($("#yd_sum_check_"+order_id).val() == 0){
			$("#nextdate").attr("disabled","disabled");
			$("#datehour").attr("disabled","disabled");
			
			$("#order_time_type_msg").html("修改次数剩余:0次;");
		}else if($("#yd_time_check_"+order_id).val() != '' && $("#yd_time_check_"+order_id).val() != null && $("#yd_time_check_"+order_id).val() != 0){
			$("#nextdate").removeAttr("disabled");
			$("#datehour").removeAttr("disabled"); 
			$("#order_time_type_msg").html("修改次数剩余:"+$("#yd_sum_check_"+order_id).val()+"次;最大预到时间:"+$("#yd_time_check_"+order_id).val());
		} 
	}
	**/
	
	/*$.ajax({

		type:'post',

		url:'?c=order&m=order_info_ajax',

		data:'order_id=' + order_id,

		success:function(data)

		{

			if(data != '')

			{

				data = $.parseJSON(data);

				$("#visit").children("modal-footer").css("display", "block");

				$("#visit_order_id").val(data['order_id']);

				var html = "患者姓名：" + data['pat_name'] + " （" + data['sex'] + "、" + data['pat_age'] + "岁）" +"<br />";

				html += "联系电话：" + data['pat_phone'] + "<br />";

				html += "登记时间：" + data['addtime'] + "<br />";

				html += "预约时间：" + data['ordertime'] + "<br />";

				$("#patient_info").html(html);

			}

		},

		complete: function (XHR, TS)

		{

		   XHR = null;

		}

	});*/

}



function ajax_dao(order_id)

{

	$("#dao").children("btn").css("display", "none");

	$("#dao_order_id").val(order_id);

	$("#dao_remark").val("");

	$("#dao_patient_info").html($("#pat_" + order_id).html());

	/*$.ajax({

		type:'post',

		url:'?c=order&m=order_info_ajax',

		data:'order_id=' + order_id,

		success:function(data)

		{

			if(data != '')

			{

				data = $.parseJSON(data);

				$("#dao").children("modal-footer").css("display", "block");

				$("#dao_order_id").val(data['order_id']);

				var html = "患者姓名：" + data['pat_name'] + " （" + data['sex'] + "、" + data['pat_age'] + "岁）" +"<br />";

				html += "联系电话：" + data['pat_phone'] + "<br />";

				html += "登记时间：" + data['addtime'] + "<br />";

				html += "预约时间：" + data['ordertime'] + "<br />";

				$("#dao_patient_info").html(html);

			}

		},

		complete: function (XHR, TS)

		{

		   XHR = null;

		}

	});*/

}



function ajax_doctor(order_id)

{

	$("#doctor").children("btn").css("display", "none");

	$("#doctor_order_id").val(order_id);

	$("#doctor_remark").val("");

	$("#doctor_patient_info").html($("#pat_" + order_id).html());

	/*$.ajax({

		type:'post',

		url:'?c=order&m=order_info_ajax',

		data:'order_id=' + order_id,

		success:function(data)

		{

			if(data != '')

			{

				data = $.parseJSON(data);

				$("#doctor").children("modal-footer").css("display", "block");

				$("#doctor_order_id").val(data['order_id']);

				var html = "患者姓名：" + data['pat_name'] + " （" + data['sex'] + "、" + data['pat_age'] + "岁）" +"<br />";

				html += "联系电话：" + data['pat_phone'] + "<br />";

				html += "登记时间：" + data['addtime'] + "<br />";

				html += "预约时间：" + data['ordertime'] + "<br />";

				$("#doctor_patient_info").html(html);

			}

		},

		complete: function (XHR, TS)

		{

		   XHR = null;

		}

	});*/

}

function ajax_weixin(order_id)
{

		$.ajax({

			type:'post',

			url:'?c=order&m=get_info_by_id',

			data:'order_id=' + order_id ,

			success:function(data)

			{
				var html = '<h4>预约卡地址：';
				html += data;
				html += '</h4><h4>预览:</h4><img src="';
				html += data;
				html += '">';
				$("#wx_order").html(html);

			},

			complete: function (XHR, TS)

			{

			   XHR = null;

			}

		});
		
		$('#MyDiv').css("display", "block");

		$('#fade').css("display", "block");
		var height = $(document).scrollTop()+70; 
		$('#MyDiv').css('top',height+'px');
		$("#fade").height($(document).height());
}

function CloseDiv(show_div,bg_div)
{
		$('#MyDiv').css("display", "none");

		$('#fade').css("display", "none");
}

function visit_add(){

  	var os=getOs();
  	var  br_html = '\r\n';//IE系列用这个  
  	if(os=='FF' || os=='SF'){//FireFox、谷歌浏览器用这个  
 	 br_html = '\n';} 
		
	var order_id = $("#visit_order_id").val(); 
	var false_id = $("#false_id").val(); 
	var remark = $("#visit_remark").val(); 
	var zt_id = $("#zt_id").val(); 
	var lx_id = $("#lx_id").val(); 
	var jg_id = $("#jg_id").val(); 
	var ls_id = $("#ls_id").val(); 
	var date_lx = $("#nextdate").val();
	var datehour = $("#datehour").val();
	var msg = ''; 
	if(false_id==0){
		msg = br_html+'未就诊原因不能为空！';
		$("#false_id").focus();
	}
	if(zt_id==0){
		msg = msg+br_html+'回访主题不能为空！';
		$("#zt_id").focus();
	}
	if(lx_id==0){
		msg = msg+br_html+'回访类型不能为空！';
		$("#lx_id").focus();
	}
	if(jg_id==0){
		msg = msg+br_html+'回访状态不能为空！';
		$("#jg_id").focus();
	}
	if(ls_id==0){
		msg = msg+br_html+'客户流向不能为空！';
		$("#ls_id").focus();
	}

	if(remark==''){
		msg = msg+br_html+'备注不能为空！';
		$("#visit_remark").focus();
	}
	 
	//仁爱妇科 不孕流入公海 预到时间+指定天数 
	var renfi_fk_by_day_time = $("#renfi_fk_by_day_time").val();
	var today=new Date();
	today.setHours(0);
	today.setMinutes(0);
	today.setSeconds(0);
	today.setMilliseconds(0);
	
	var time_ok =0;
	//只有确定情况下，并且修改时间和默认时间不同的情况下 才进行比较
	if(date_lx != $("#order_hidden_time_check_"+order_id).val()){
		 if(date_lx != '' && date_lx  != null){ 
			 var DATE_FORMAT = /^[0-9]{4}-[0-1]?[0-9]{1}-[0-3]?[0-9]{1}$/;  
			 if(!DATE_FORMAT.test(date_lx)){
				 var time_html = '您输入的日期格式有误，正确格式应为'+(date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate()); 
				 $("#order_time_type_msg").html(time_html);
				 msg = msg+br_html+time_html; time_ok = 1;
			 }
		 }else{
		 	var time_html =  '您输入的日期格式有误，正确格式应为'+(date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate()); 
			 $("#order_time_type_msg").html(time_html);
			 msg = msg+br_html+time_html; time_ok = 1;
		 }
		 if(time_ok == 0 ){
			 var date = new Date($("#order_add_time_check_"+order_id).val());  
			 if (Date.parse(date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate()) > Date.parse(date_lx)) {
			  var time_html = '预到时间必须>=添加日期'+date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate(); 
				 $("#order_time_type_msg").html(time_html);
				time_ok = 1;
			 }
			 else if(($("#keshi_id_"+order_id).val() == 32 || $("#keshi_id_"+order_id).val() == 1)){
				  var time1=new Date(date_lx);
				  var time2=new Date();
				  var end_time=time2.getTime()+30*24*60*60*1000;
				  if(time1.getTime() > end_time){ 
					  $("#order_time_type_msg").html("不能超过30天哦！从今天开始计算。");time_ok = 1;
				  }
			 } 
			 /**
			 else if(($("#keshi_id_"+order_id).val() == 32 || $("#keshi_id_"+order_id).val() == 1) && $("#yd_time_check_"+order_id).val() !=  0 ){//不存在预到时间统计的就不用再判断时间 
				if($("#yd_time_check_"+order_id).val() != '' && $("#yd_time_check_"+order_id).val() != null && $("#yd_time_check_"+order_id).val() != 0){
				 
					判断仁爱妇科 不孕 是否通过验证条件
					1.以系统第一次录入的预到时间为准  往后延期renfi_fk_by_day_time 天为 等待期，超过 renfi_fk_by_day_time 天未到诊的 流入公海
					2. renfi_fk_by_day_time 天之内 只有三次修改预到时间的机会。 添加时间  <= 修改时间    <=  renfi_fk_by_day_time 天预到时间
					3.非当前数据 拥有者 可以在 当拥有者 3次机会使用完之后，去更改数据的 预到时间，条件是更改的时间必须大于 当前预到时间。同时将更改的时间 更新为 最大的预到限制时间
					4.变更咨询员会导致所有记时 限制的条件初始化
					 
					 if(Date.parse($("#yd_time_check_"+order_id).val()) < Date.parse(date_lx+" 23:59:59")){
						 var time_html = '预到时间必须小于:'+$("#yd_time_check_"+order_id).val()+",剩余修改次数:"+$("#yd_sum_check_"+order_id).val()+"次"; 
		 				$("#order_time_type_msg").html(time_html);
						msg = msg+br_html+time_html;
						time_ok = 1;
				 	}
				}
			 } **/

	        else if($("#hos_id_"+order_id).val() == 3 || $("#hos_id_"+order_id).val() == 6){//只判断台州和东方
				  var time=date_lx;
				  var time1=new Date(time);
				  var time2=new Date();
				  var end_time=time2.getTime()+30*24*60*60*1000; 

				  //属于台州东方 非自媒体 只能延期 7天，自媒体延期30天
				  if($("#keshi_id").val() == 87 || $("#keshi_id").val() == 88 || $("#keshi_id").val() == 89  || $("#keshi_id").val() == 85 || $("#keshi_id").val() == 86){
					  if(time1.getTime() > end_time){
						  var time_html = "不能超过30天哦！从今天开始计算。";  
				 		  $("#order_time_type_msg").html(time_html);
						  time_ok = 1;
					  }
				  }else{
					  end_time=time2.getTime()+7*24*60*60*1000;  
					  if(time1.getTime()>end_time){
						  var time_html = "不能超过7天哦！从今天开始计算。"; 
				 		  $("#order_time_type_msg").html(time_html);
						  time_ok = 1;
					  } 
				  } 
			 }    
		 } 
	}

    if(time_ok == 1){
	  msg = msg+br_html+'预到时间错误,请检查'; 
	  $("#nextdate").focus();
    } 
    msg= '';//默认提交
	if(msg != ''){
		alert(msg);
	    $('#visit').modal('show');
	}else{
		//以下语句是获取相关的编号，根据编号显示相关的div
		var add_id=$("#visit_order_id").val();
		var add_per='#add_person_'+add_id;
		$(add_per).css('display','block');
		$.ajax({
			type:'post',
			url:'?c=gonghai&m=gonghai_update_ajax',
			data:'order_id=' + order_id + '&false_id=' + false_id + '&zt_id=' + zt_id + '&lx_id=' + lx_id + '&jg_id=' + jg_id + '&ls_id=' + ls_id + '&date_lx=' + date_lx + '&remark=' + remark + '&datehour=' + datehour + '&type=visit',
			success:function(data){
					if(date_lx != $("#order_hidden_time_check_"+order_id).val()){
						/**
						//减少次数
						if($("#keshi_id_"+order_id).val() == 32 || $("#keshi_id_"+order_id).val() == 1){
							$("#yd_sum_check_"+order_id).val($("#yd_sum_check_"+order_id).val()-1);
							if($("#yd_sum_check_"+order_id).val() == 0){
								$("#nextdate").attr("disabled","disabled");
								$("#datehour").attr("disabled","disabled");
								
								$("#order_time_type_msg").html("修改次数剩余:0次;");
							}else{
								$("#nextdate").removeAttr("disabled");
								$("#datehour").removeAttr("disabled"); 
								$("#order_time_type_msg").html("修改次数剩余:"+$("#yd_sum_check_"+order_id).val()+"次;");
							} 
						}
						**/
					} 
				
					$("#visit_" + order_id).html(data + $("#visit_" + order_id).html()); 
					//变更 预到数据日期
					$("#order_time_"+order_id).html(date_lx);
					//变更 预到数据时间
					$("#order_time_duan_"+order_id).html(datehour); 
					//关闭框
					$('#visit').modal('hide');

					$("#order_hidden_time_check_"+order_id).val(date_lx);
					$("#order_time_duan_check_"+order_id).val(datehour); 
			},
			complete: function (XHR, TS){
			   XHR = null;
			}
		}); 
	} 
}

function dao_add()

{

	var order_id = $("#dao_order_id").val();

	var remark = $("#dao_remark").val();

	var doctor_name = $("#dao_doctor_name").val();

	$.ajax({

		type:'post',

		url:'?c=order&m=order_update_ajax',

		data:'order_id=' + order_id + '&remark=' + remark + '&doctor_name=' + doctor_name + '&type=dao',

		success:function(data)

		{

			if(data != '')

			{

				data = $.parseJSON(data);

				$("#come_time_" + order_id).html(data['come_time']);

				$("#doctor_" + order_id).html(doctor_name);

				$("#notice_" + order_id).html(data['remark'] + $("#notice_" + order_id).html());

				$("#status_" + order_id).removeClass("icon-remove");

				$("#status_" + order_id).addClass("icon-ok");

				$("#status_" + order_id).css("color", "#F00");

			}

		},

		complete: function (XHR, TS)

		{

		   XHR = null;

		}

	});

}
function ajax_out(order_id)

{
	$.ajax({

		type:'post',

		url:'?c=order&m=order_out_ajax',

		data:'order_id=' + order_id ,

		success:function(data)

		{
			var data = JSON.parse(data);

			$("#visit_" + order_id).html(data.str + $("#visit_" + order_id).html());
			if(data.tag == 1){
			
				$('#pat_name_'+order_id).parent().unwarp();
			}else if(data.tag == 2){
			
				$('#pat_name_'+order_id).parent().warp('<del></del>');
			}
			
		},

		complete: function (XHR, TS)

		{

		   XHR = null;

		}

	});


}


function doctor_add()

{

	var order_id = $("#doctor_order_id").val();

	var remark = $("#doctor_remark").val();

	var doctor_name = $("#doctor_name").val();

	$.ajax({

		type:'post',

		url:'?c=order&m=order_update_ajax',

		data:'order_id=' + order_id + '&remark=' + remark + '&doctor_name=' + doctor_name + '&type=doctor',

		success:function(data)

		{

			if(data != '')

			{

				data = $.parseJSON(data);

				$("#come_time_" + order_id).html(data['come_time']);

				$("#doctor_time_" + order_id).html(data['doctor_time']);

				$("#doctor_" + order_id).html(doctor_name);

				$("#notice_" + order_id).html(data['remark'] + $("#notice_" + order_id).html());

			}

		},

		complete: function (XHR, TS)

		{

		   XHR = null;

		}

	});

}



function change_order_status(order_id)

{

	var classname = $("#status_" + order_id).attr("class");

	$.ajax({

		type:'post',

		url:'?c=order&m=change_order_status',

		data:'order_id=' + order_id,

		success:function(data)

		{
			if($("#l_admin_action").val() == 'all'){

			if(classname == 'icon-ok')

			{
				
				$("#status_" + order_id).removeClass("icon-ok");

				$("#status_" + order_id).addClass("icon-remove");

				$("#status_" + order_id).css("color", "#00F");

			}

			else

			{

				$("#status_" + order_id).addClass("icon-ok");

				$("#status_" + order_id).removeClass("icon-remove");

				$("#status_" + order_id).css("color", "#F00");

				$("#come_time_" + order_id).html(data);

			}
			}

		},

		complete: function (XHR, TS)

		{

		   XHR = null;

		}

	});

}



function show(obj)

{

	var h = $(".order_form").height();

	if(h == 120)

	{

		$(".order_form").height(38);

		$("#gaoji").html(" 高级 ");

	}

	else

	{

		$(".order_form").height(120);

		$("#gaoji").html(" 简单 ");

	}

}

function bigTxt(obj)
{
	var con = $('#textareaz').val();
	if(con == '我也说一句'){
		$('#textareaz').val('');
	}
	$('#textareaz').css('height','100px');
	$('#textareaz').css('line-height','20px');
	
	$('#textareaz').blur(function(){
	
	$('#textareaz').css('height','30px');
	
	}
	
	);
	
}
function time_sel(obj){
		var shijian = $(obj).children("a").html();
		$(obj).parent().parent().prev().val(shijian);
		$(obj).parent().parent().prev().focus();
}
function time_clean(obj){
		$(obj).parent().parent().prev().val("");
		$(obj).parent().parent().prev().focus();
	};

 
 
 function getOs()   
 {    
    if(navigator.userAgent.indexOf("MSIE")>0) {   
         return "IE"; //InternetExplor  
    }   
    else if(isFirefox=navigator.userAgent.indexOf("Firefox")>0){   
         return "FF"; //firefox  
    }   
    else if(isSafari=navigator.userAgent.indexOf("Safari")>0) {   
         return "SF"; //Safari  
    }    
    else if(isCamino=navigator.userAgent.indexOf("Camino")>0){   
         return "C"; //Camino  
    }   
    else if(isMozilla=navigator.userAgent.indexOf("Gecko/")>0){   
         return "G"; //Gecko  
    }   
    else if(isMozilla=navigator.userAgent.indexOf("Opera")>=0){  
         return "O"; //opera  
    }else{  
        return 'Other';  
    }  
      
 }   
 
</script>

</body>

</html>