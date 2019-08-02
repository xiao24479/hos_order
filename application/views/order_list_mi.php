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
#main-content{margin-left:0px;}
#sidebar{margin-left:-180px; z-index:-1;}
.sidebar-scroll{z-index:-1;}
.date_div{ position:absolute; top:55px; left:412px; z-index:999;}
.anniu{ display:none;}
.remark {width:auto;}
.order_form1 {
margin-bottom: 10px;
width: 100%;
border: 1px solid #e3e3e3;
padding: 10px 0 0 0;
}
.order_btn1 {
left: 1210px;
}
.o_a a{ padding:0 10px;}

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
.list_table .blacklist td{
    background-color:#999;
}

 .mask {       
		position: absolute; top: 0px; filter: alpha(opacity=60); background-color: #777;     
		z-index: 1002; left: 0px;     
		opacity:0.5; -moz-opacity:0.5;     
	} 
	
</style>
<script src="static/js/jquery.js"></script>
<script language="javascript">
if($(window).width() >= 1200)
{
//	window.location.href = '?c=order&m=order_list';
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


function yycard(order_id)
{
	if(order_id != 0 ){
		var diag = new Dialog();
		diag.Width = 700;
		diag.Height = 400;
		diag.Title = "查询预约卡";
		diag.URL = "?c=order&m=yycard&order_id="+order_id;
		diag.show();
	}else{
		alert("必须存在预约单ID");
	} 
}


function ajax_get_dagou(order_id){
	var diag = new Dialog();
	diag.Width = 700;
	diag.Height = 400;
	diag.Title = "获取打勾记录";
	diag.URL = "?c=order&m=get_dagou&order_id="+order_id;
	diag.show();
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
 <!--遮罩层使用 div -->
 <div id="mask" class="mask"></div> 
 
 
<?php echo $top; ?>
<div id="container" class="row-fluid"> 
<?php echo $sider_menu; ?>

<div id="main-content"> 
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid" style="position:relative; padding-top:10px;"> 
<div class="order_count">
    <span><?php if($rank_type == 2 || $rank_type == 3 || $_COOKIE['l_admin_action'] == 'all'):?><a href="javascript:order_window('?c=order&m=order_info&type=mi');" target="_blank" onClick="_czc.push(['_trackEvent', '顶部链接', '<?php echo $admin['name']; ?>', '新增预约','','']);"><i class="icon-plus"></i> 新增预约</a><?php endif ?></span><span>总预约人数：<font><?php echo $order_count['count']; ?></font></span><span>来院人数：<font><?php echo $order_count['come']; //$order_count['dao'] . " / " . $order_count['come']; ?></font></span><span>未来院人数：<font><?php echo $order_count['wei']; ?></font></span>
<span class="o_a">
<a href="javascript:jsearch('s=1');" target="_blank" onClick="_czc.push(['_trackEvent', '顶部链接', '<?php echo $admin['name']; ?>', '未来院','','']);">未来院</a>
<a href="javascript:jsearch('null');" target="_blank" onClick="_czc.push(['_trackEvent', '顶部链接', '<?php echo $admin['name']; ?>', '刷新','','']);">刷新</a>
<a href="javascript:jsearch('wu=1');" target="_blank" onClick="_czc.push(['_trackEvent', '顶部链接', '<?php echo $admin['name']; ?>', '五点数据','','']);">五点数据</a>
<a href="<?php echo $down_url;?>" target="_blank" onClick="_czc.push(['_trackEvent', '顶部链接', '<?php echo $admin['name']; ?>', '导出数据','','']);">导出数据</a>
<a href="javascript:jsearch('p_jb=149');" target="_blank" onClick="_czc.push(['_trackEvent', '顶部链接', '<?php echo $admin['name']; ?>', '四维','','']);">四维</a>
</span>
</div>
             
             
             
                <!--导医快捷查询开始-->
             <div style="height:150px;background-color: lightblue;<?php if((in_array('3',  explode(',', isset($_COOKIE['l_hos_id'])?$_COOKIE['l_hos_id']:''))||in_array('15',  explode(',', isset($_COOKIE['l_hos_id'])?$_COOKIE['l_hos_id']:'')))&&$rank_type==3){
echo '';}else{ echo 'display: none';}?>">
        <span style="width:180px;color:red;font-size:18px;padding-left:45%;">导医快捷检索</span><br/>
        <span style="font-size:16px; padding-left:20%;">预约号：</span><input type="text" style="height:16px;width: 120px;" id="dy_order_no"/>
<span style="font-size:16px;padding-left: 30px;">患者姓名：<input type="text" style="height:16px;width: 120px;"id="dy_order_name"/></span>
<span style="font-size:16px;padding-left: 30px;">患者手机号：<input type="text" style="height:16px;width: 120px;"id="dy_order_phone"/></span>
<button type="button" class="btn btn-success" style="font-size:16px;margin-left: 30px;margin-top: -8px;" id="dy_fast">快捷检索</button>
    
                 
             </div>     
             
             
             
             
             <!--导医快捷查询结束-->
             
             
             
             <!--导医快捷查询结束-->
<form action="" method="get" class="date_form order_form1">
<input type="hidden" value="order" name="c" />
<input type="hidden" value="order_list" name="m" />
<input type="hidden" value="mi" name="type" />
<div class="span5">
    <div class="row-form">
	    <input type="hidden"  id="order_query_seven_data" value="<?php echo $order_query_seven_data ;?>"> 
		<select name="t" style="width:110px;">
			 <?php if($order_query_seven_data == 0){
					  foreach($this->lang->line('order_type') as $key=>$val):
						 if($key == 2){?>
							 <option value="<?php echo $key; ?>" <?php  echo 'selected="selected"'; ?>><?php echo $val?></option>
							<?php break; }?>
							
					 <?php endforeach;
				
				}else{
					foreach($this->lang->line('order_type') as $key=>$val):?>
						<option value="<?php echo $key; ?>" <?php if($key == $t){echo 'selected="selected"';}?>><?php echo $val; ?></option>
					 <?php endforeach;
					
			    }?> 
		</select>
		<input type="text" value="<?php echo $start; ?> - <?php echo $end; ?>" style="width:270px;" class="input-block-level" name="date" id="inputDate" />
    </div>
    <div class="date_div">
    <div class="divdate"></div>
    <div class="anniu"><a href="javascript:;" class="btn btn-inverse guanbi"> 关闭 </a><br /><a href="javascript:;" class="btn btn-inverse today"> 今天 </a><br /><a href="javascript:;" class="btn btn-inverse week"> 一周 </a><br /><a href="javascript:;" class="btn btn-inverse month"> 一月 </a><br /><a href="javascript:;" class="btn btn-inverse year"> 一年 </a></div>
    </div>
    
<!--	<div class="row-form">
		<label class="select_label"><?php echo $this->lang->line('from_name');?></label>
		<select name="f_p_i" id="from_parent_id" style="width:130px;">
		   <option value="0"><?php echo $this->lang->line('please_select');?></option>
		   <?php foreach($from_list as $val){ ?>
		   <option value="<?php echo $val['from_id'];?>" <?php if($val['from_id'] == $f_p_i){echo " selected";}?>><?php echo $val['from_name'];?></option>
		   <?php } ?>
	   </select>
	   
	   <select name="f_i" id="from_id" style="width:180px;">
	   	<option value="0"><?php echo $this->lang->line('please_select');?></option>
	   </select>
	</div>-->
    <div class="row-form">
		<label class="select_label"><?php echo $this->lang->line('order_keshi');?></label>
		<select name="hos_id" id="hos_id" style="width:180px;">
			<option value=""><?php echo $this->lang->line('hospital_select'); ?></option>
			 
            <?php  
			  $hospital_sort = array();
			  foreach($hospital as $v) {
			   $hospital_sort[] = $v['hos_name'];
			  }
			   
			  foreach($hospital_sort as $k=>$v) {
			   $hospital_sort[$k] = iconv('UTF-8', 'GBK//IGNORE',$v);
			  }
			  foreach($hospital_sort as $k=>$v) {
			   $hospital_sort[$k] = iconv('GBK', 'UTF-8//IGNORE', $v);
			  }
			  rsort($hospital_sort);
			  
		   foreach($hospital_sort as $hospital_sort_temp){
			foreach($hospital as $item){
				if(strcmp($hospital_sort_temp,$item['hos_name']) == 0){  ?>
		<OPTION value="<?php echo $item['hos_id']; ?>" <?php if($hos_id == $item['hos_id']){?>selected<?php } ?> ><?php echo $item['hos_name']; ?></OPTION> 
		<?php }}} ?> 
         
		</select>
		<select name="keshi_id" id="keshi_id" style="width:130px;">
			<option value=""><?php echo $this->lang->line('keshi_select'); ?></option>
		</select>
	</div>
</div>
<div class="span3">
	<div class="row-form">
		<label class="select_label"><?php echo $this->lang->line('patient_name');?></label>
		<input type="text" value="<?php echo $p_n; ?>" class="input-medium" name="p_n" id="p_n"  />
	</div>
<!--	<div class="row-form">
		<label class="select_label">咨询员</label>
		<input type="text" value="<?php echo $a_i; ?>" class="input-medium" name="a_i"  />
	</div>
	<div class="row-form">
		<label class="select_label">大病种</label>
		<select name="p_jb" id="p_jb" style="width:165px;">
			<option value=""><?php echo $this->lang->line('jb_parent_select'); ?></option>
			<?php foreach($jibing_parent as $key=>$val):?><option value="<?php echo $val['jb_id']; ?>" <?php if($val['jb_id'] == $p_jb){ echo "selected";}?>><?php echo $val['jb_name']; ?></option><?php endforeach;?>
		</select>
	</div>
--></div>	
<div class="span3">
	<div class="row-form">
		<label class="select_label"><?php echo $this->lang->line('patient_phone');?></label>
		<input type="text" value="<?php echo $p_p; ?>" class="input-medium" name="p_p"  id="p_p" />
	</div>
	
<!--	<div class="row-form">
		<label class="select_label"><?php echo $this->lang->line('type_name');?></label>
		<select name="o_t" style="width:165px;">
			<option value="" selected ><?php echo $this->lang->line('please_select'); ?></option>
			<?php foreach($type_list as $val):?><option value="<?php echo $val['type_id']; ?>" <?php if($val['type_id'] == $o_t){echo " selected";}?>><?php echo $val['type_name']; ?></option><?php endforeach;?>
		</select>
	</div>
	<div class="row-form">
		<label class="select_label">小病种</label>
		<select name="jb" id="jb" style="width:165px;">
			<option value=""><?php echo $this->lang->line('jb_child_select'); ?></option>
		</select>
	</div>
--></div>
<div class="span3">
	<div class="row-form">
		<label class="select_label"><?php echo $this->lang->line('order_no');?></label>
		<input type="text" value="<?php echo $o_o; ?>" class="input-medium" name="o_o"  />
	</div>
<!--    <div class="row-form">
		<label class="select_label"><?php echo $this->lang->line('status');?></label>
		<select name="s" id="status" style="width:165px;">
			<option value=""><?php echo $this->lang->line('please_select'); ?></option>
			<?php foreach($this->lang->line('order_status') as $key=>$val):?><option value="<?php echo $key; ?>" <?php if($key == $s){echo " selected";}?>><?php echo $val; ?></option><?php endforeach;?>
		</select>
	</div>
    <div class="row-form">
		<label class="select_label"><?php echo $this->lang->line('pager');?></label>
		<select name="p" style="width:165px;">
			<option value=""><?php echo $this->lang->line('please_select'); ?></option>
			<?php foreach($this->lang->line('page_no') as $key=>$val):?><option value="<?php echo $key; ?>" <?php if($key == $per_page){ echo "selected";}?>><?php echo $val; ?></option><?php endforeach;?>
		</select>
	</div>
--></div>
<div class="order_btn1">
    <button type="submit" class="btn btn-success" onsubmit="_czc.push(['_trackEvent', '预约列表', '<?php echo $admin['name']; ?>', '搜索','','']);"> 搜索 </button> <!--<a href="javascript:show(this);" id="gaoji" class="btn btn-default"> 高级 </a>-->

    <span style="margin-left: 10px;"> <input type="button" class="input_search"  style="vertical-align:middle;height:30px; cursor:pointer;" value="查询公海和预约" onClick="order_and_gonghaiorder_search()"/>
     
     
    </div>
</form>
	  <div class="row-fluid">
		<div class="span12">
<table width="100%" border="0" cellspacing="0" cellpadding="2" class="list_table">
  <thead>
  <tr>
	<th width="50"><?php echo $this->lang->line('order_no'); ?></th>
	<th><?php echo $this->lang->line('patient_info'); ?></th>
	<th width="200"><?php echo $this->lang->line('time'); ?></th>
	<th width="80">途径/性质</th>
	<th width="70"><?php echo $this->lang->line('order_keshi'); ?></th>
	<th width="70"><?php echo $this->lang->line('patient_jibing'); ?></th>
    <th width="65">客服/医生</th>
	<th width="200"><?php echo $this->lang->line('visit'); ?></th>
    <th width="200"><?php echo $this->lang->line('notice'); ?></th>
	<th width="70" style="border-right:1px solid #9D4A9C;"><?php echo $this->lang->line('action'); ?></th>
  </tr>
  </thead>
  <tbody>
  <?php
  $i = 0;
  
  	if(strcmp($_COOKIE['l_admin_action'],'all') == 0){
		$l_admin_action  = array("179");
	}else{
		$l_admin_action  = explode(',',$_COOKIE['l_admin_action']);
	} 
	$keshi_check_ts = $this->config->item('keshi_check_ts');
	$keshi_check_ts = explode(",",$keshi_check_ts);
	$zixun_check_ts = $this->config->item('zixun_check_ts');
	$zixun_check_ts = explode(",",$zixun_check_ts);
	foreach($order_list as $item):
		if(!in_array('179',$l_admin_action)){
			$item['pat_phone']  =  $item['pat_phone'][0].$item['pat_phone'][1].$item['pat_phone'][2].'*****';
			$item['pat_phone1']  =  $item['pat_phone1'][0].$item['pat_phone1'][1].$item['pat_phone1'][2].'*****';
		} 
		//咨询只能看自己的电话 其他电话不可见
		if(in_array($_COOKIE["l_rank_id"], $zixun_check_ts) && $rank_type == 2 && $item['hos_id'] == 3 && in_array($item['keshi_id'], $keshi_check_ts)){		
		   if($_COOKIE['l_admin_id'] != $item['admin_id']){
		      	//$item['pat_phone']  =  $item['pat_phone'][0].$item['pat_phone'][1].$item['pat_phone'][2].'*****';
		   }
		} 
  ?>
  <tr class="<?php if($i % 2){ echo 'td1';}?> <?php if($item['pat_blacklist']==1){echo 'blacklist';}?>" style="height:90px;">
    <td>
       <a href="?c=order&m=order_info&order_id=<?php echo $item['order_id']; ?>&p=1" target="_blank"><?php echo $item['order_no']; ?></a><br />
	   <?php if($item['is_first']){ echo "初诊";}else{ echo "<font color='#FF0000'>复诊</font>";}?><br />
       <a id="<?php echo $item['order_id']; ?>_check_order_status" href="<?php if(($rank_type == 3) || ($_COOKIE['l_admin_action'] == 'all')):?>javascript:change_order_status(<?php echo $item['order_id']; ?>);<?php else:?>javascript:;<?php endif;?>"><?php if($item['is_come'] > 0){ echo '<i id="status_' . $item['order_id'] . '" class="icon-ok" style="color:#F00; font-size: large;"></i>';}else{ echo '<i id="status_' . $item['order_id'] . '" class="icon-remove" style="color:#00F; font-size: large;"></i>'; }?></a>
    </td>
	<td style="text-align:left;">
    <div id="pat_<?php echo $item['order_id']; ?>">
		姓名：<font color='#FF0000'><b><?php echo $item['pat_name']?></b></font>（<?php echo $item['pat_sex']; ?>、<?php echo $item['pat_age']?>岁）<br />
		<?php if($_COOKIE['l_admin_id'] == '239'):?>
		电话：<font id="pat_phone_<?php echo $item['order_id']; ?>"><?php echo $item['pat_phone']; if(!empty($item['pat_phone1'])){echo "/" . $item['pat_phone1'];}?></font><br />
		<?php else:?>
			<?php if($rank_type == 1 || $rank_type == 2 || $rank_type == 3 || $_COOKIE['l_admin_action'] == 'all' || $item['is_come'] > 0):?>
				<?php if(in_array($item['hos_id'],$hos_auth) && $rank_type == 2 && $item['admin_id'] != $_COOKIE['l_admin_id']):?>
					电话：<font id="pat_phone_<?php echo $item['order_id']; ?>"><?php echo $item['pat_phone']; if(!empty($item['pat_phone1'])){echo "/" . $item['pat_phone1'];}?></font><br />
				<?php else:?>	
					电话：<font id="pat_phone_<?php echo $item['order_id']; ?>"><?php echo $item['pat_phone']; if(!empty($item['pat_phone1'])){echo "/" . $item['pat_phone1'];}?></font><br />
				<?php endif;?>
			<?php else:?>
				电话：<font id="pat_phone_<?php echo $item['order_id']; ?>"><?php echo $item['pat_phone']; if(!empty($item['pat_phone1'])){echo "/" . $item['pat_phone1'];}?></font><br />
			<?php endif;?>
		<?php endif;?>
		地区：<?php
		  if($item['pat_province'] > 0){ echo $area[$item['pat_province']]['region_name'];}
		  if($item['pat_city'] > 0){ echo "、" . $area[$item['pat_city']]['region_name'];}
		  if($item['pat_area'] > 0){ echo "、" . $area[$item['pat_area']]['region_name'];}?><br />
		
		<?php if(strcmp($_COOKIE['l_admin_action'],'all') == 0){?>  数据中心状态: <?php if(empty($item['ireport_order_id'])){echo '<span style="color:red;">未推送</span>'; }
		 else if(!empty($item['ireport_order_id'])){echo '<span style="color:red;">已推送 </span> ';if(!empty($item['ireport_msg'])){echo '<span style="color:red;">'.$item['ireport_msg'].'</span>';}}?>
		 <?php echo '<br/>';}?>
		<?php
		  if(!empty($item['pat_qq'])){
			  echo $item['pat_qq'] . "(QQ)";
		  }
		  elseif(isset($item['data_time']))
		  {
			  echo "【孕周】" . (intval((time() - $item['data_time']) / (86400 * 7)) + 1) . "周、【预产期】" . date("Y-m-d", ($item['data_time'] + (86400 * 280)));
		  }
		?>
        
        来源网址：
        <?php 
		    $ly_check =0 ;
		    foreach ($order_laiyuanweb as $order_laiyuanweb_temp){
		    	if(strcmp($order_laiyuanweb_temp['order_id'],$item['order_id']) == 0 && !empty($order_laiyuanweb_temp['form'])){
		    		$ly_check =1;
		    		echo '<a href="'.$order_laiyuanweb_temp['form'].'"  target="_blank">'.mb_substr($order_laiyuanweb_temp['form'],0,30,'utf-8').'...</a>';
		    		break;
		    	}
		    }
		    if(empty($ly_check)){
		    	foreach($order_content as $order_content_temp){
		    		if(strcmp($order_content_temp['order_id'],$item['order_id']) == 0){
		    			$con_content = explode('进入:<a href="',$order_content_temp['con_content']);
		    			if(count($con_content) > 1){
		    				$con_content = explode('">',$con_content[1]);
		    				if(count($con_content) > 1){
		    					$ly_check =1;
		    					echo '<a href="'.$con_content[0].'"  target="_blank">'.mb_substr($con_content[0],0,30,'utf-8').'...</a>';
		    				}
		    			}break;
		    		}
		    	}
		    }
			if($ly_check == 0){?>
				<div id="<?php echo $item['order_id'];?>_lyweb"><a href="javaScript:void(0);" class="lya" title="<?php echo $item['order_id'];?>">编辑来源网址</a> </div>
		  <?php } ?> 
            
    </div>
    </td>
	<td style="text-align:left;">
		<?php echo $this->lang->line('order_addtime'); ?>：<?php echo $item['order_addtime']; ?><br />
		<?php echo $this->lang->line('order_time'); ?>：<?php echo $item['order_time']; ?> <font style="color:#F00; font-weight:bold;"><?php if($item['order_time_duan']){ echo $item['order_time_duan'];}?></font><br />
		<?php echo $this->lang->line('come_time'); ?>：<span id="come_time_<?php echo $item['order_id']; ?>"><?php if($item['come_time'] > 0){ echo date("Y-m-d H:i", $item['come_time']);}?></span><br />
		
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
		<!--<?php echo $this->lang->line('doctor_time'); ?>：<span id="doctor_time_<?php echo $item['order_id']; ?>"><?php if($item['doctor_time'] > 0){ echo date("Y-m-d H:i", $item['doctor_time']);} ?></span>--></td>
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
	 <?php  if(empty($item['is_to_gonghai'])):  ?> 
		<?php if($rank_type == 2 || $rank_type == 3 || $_COOKIE['l_admin_action'] == 'all'):?>
		 <?php  if($item['keshi_id'] == 7 && $item['from_parent_id'] == 23 && ($admin['rank_id'] == 21 || $admin['rank_id'] == 28)): ?> 
		<?php elseif((in_array(66, $admin_action)) || ($_COOKIE['l_admin_action'] == 'all') || ($rank_type == 2)):?><a href="?c=order&m=order_info&order_id=<?php
        echo $item['order_id'];
		if($_COOKIE['l_admin_action'] != 'all')

				{

					if($item['admin_id'] == $_COOKIE['l_admin_id']||in_array(143,$admin_action)||$_COOKIE['l_admin_action'] == 'all'){
                                            if($rank_type==4){
                                                echo "";
                                            }else{
                                                echo "&p=2";
                                            } 
                                            }else{
                                            echo "&p=1";
                                            }

				}
		?>" target="_blank" class="btn btn-info" onClick="_czc.push(['_trackEvent', '预约列表', '<?php echo $admin['name']; ?>', '编辑','','']);"><?php echo $this->lang->line('edit'); ?></a><?php endif;?>
		<?php if(empty($item['doctor_time'])):?><a href="#visit" role="button" class="btn btn-success" data-toggle="modal" onClick="ajax_remark(<?php echo $item['order_id']?>);_czc.push(['_trackEvent', '预约列表', '<?php echo $admin['name']; ?>', '<?php echo $this->lang->line('visit'); ?>','','']);"><?php echo $this->lang->line('visit'); ?></a><?php endif;?>
		
		 <?php if($_COOKIE['l_admin_action'] == 'all'){?>
            <a class="btn btn-success"   onClick="ajax_get_dagou(<?php echo $item['order_id']?>);_czc.push(['_trackEvent', '预约列表', '<?php echo $admin['name']; ?>', '<?php echo '查看打勾记录'; ?>','','']);"   href="javaScript:void(0);" >查看打勾记录</a> 
            <?php }?>
            
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
		?>
		<?php
		if($rank_type == 2 || $_COOKIE['l_admin_action'] == 'all'):

		?>

        <?php if(empty($item['zampo'])):?><a href="#doctor" role="button" class="btn"  onClick="ajax_out(<?php echo $item['order_id']?>);_czc.push(['_trackEvent', '预约列表', '<?php echo $admin['name']; ?>', '取消','','']);">取消</a><?php else:?><a href="#doctor" role="button" class="btn"  onClick="ajax_out(<?php echo $item['order_id']?>);_czc.push(['_trackEvent', '预约列表', '<?php echo $admin['name']; ?>', '恢复','','']);">恢复</a><?php endif;?>

        <?php endif; ?>
        
        <?php  else:?> 
        <font style="color:#ff6060;font-size:14px;font-weight:normal;">流入公海</font> 
	    <?php endif; ?> 
	    
	     <?php $hos_id_in_array= array(6,37,42,3,39);
 if(in_array($item['hos_id'],$hos_id_in_array)){?> 
    	 <a href="javaScript:void(0);"   class="btn"  onClick="yycard(<?php echo $item['order_id']?>);_czc.push(['_trackEvent', '预约列表', '<?php echo $admin['name']; ?>', '查看预约卡','','']);">查看预约卡</a>
    <?php  }?>
    
    
      <a href="javaScript:void(0);"   class="btn"  onClick="ajax_get_fz(<?php echo $item['order_id']?>);_czc.push(['_trackEvent', '预约列表', '<?php echo $admin['name']; ?>', '复诊备注','','']);">复诊备注</a>

      <?php  if($rank_type == 2 || $rank_type == 6 || $_COOKIE['l_admin_action'] == 'all'):?>
		    <?php if(in_array(185,$admin_action)||$_COOKIE['l_admin_action'] == 'all'):?>
                 <span id="black_<?php echo $item['order_id'];?>"><a href="#set_black" role="button" class="btn" data-toggle="modal" onClick="ajax_set_black(<?php echo $item['order_id']?>,'<?php echo $admin['name']?>',<?php echo $item['pat_id']?>,<?php echo $item['pat_blacklist'];?>);_czc.push(['_trackEvent', '预约列表', '<?php echo $admin['name']; ?>', '<?php echo $this->lang->line('black'); ?>','','']);"><?php echo $this->lang->line('black'); ?></a></span>
		    <?php endif;?>
	  <?php endif;?>
   
	</td>
  </tr>
  <?php 
  $i ++;
  endforeach; ?>
  </tbody>
  </table>
<?php echo $page; ?>
<div style="margin-bottom:30px;"></div>
	<div id="visit" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabel1">回访记录</h3>
		</div>
		<div class="modal-body">
			<div class="control-group">
				<div class="controls" id="patient_info"></div>
			</div>
			<div class="control-group">
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
<!--					<input type="text" class="input-xxlarge" name="dao_doctor_name" id="dao_doctor_name" value="" style="width:520px;" />-->
				<!-- 获取医生名单的列表开始-->   
                                    <select name="dao_doctor_name" id="dao_doctor_name">
                                            <option selected="selected"><?php echo $this->lang->line('please_select');?></option>
                                             <?php 
											$doctor_sort_list = array();
											foreach($doctor_list as $va){
												$doctor_sort_list[] = $va['admin_name'];
											} 
											foreach($doctor_sort_list as $k=>$v) {
												$doctor_sort_list[$k] = iconv('UTF-8', 'GBK//IGNORE',$v);
											}
											asort($doctor_sort_list);
											foreach($doctor_sort_list as $k=>$v) {
												$doctor_sort_list[$k] = iconv('GBK', 'UTF-8//IGNORE', $v);
											}
											foreach($doctor_sort_list as $doctor_sort_va){
												foreach($doctor_list as $va){
													if(strcmp($doctor_sort_va,$va['admin_name']) == 0){?>
													 <option value="<?php echo $va['admin_name'];?>"><?php echo $va['admin_name'];?></option> 
                                            <?php }}}?>
                                        </select>
                                    <!-- 获取医生名单的列表结束-->  
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

	<div id="set_black" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">

		<div class="modal-header">

			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

			<h3 id="myModalLabel1"><?php echo $this->lang->line('black'); ?></h3>

		</div>

		<div class="modal-body">

			<div class="control-group">

				<div class="controls" id="black_patient_info"></div>

			</div>

            <div class="control-group">

				<label class="control-label">是否将该患者加入黑名单：</label>

				<div class="controls" style="position:relative;">
                        <label class="radio1">
							<input type="radio" name="black_pat_blacklist" id="black_pat_blacklist_1" value="1" checked="checked"><font color="red">是</font>
						</label>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<label class="radio1">
							<input type="radio" name="black_pat_blacklist" id="black_pat_blacklist_2" value="0"><font color="#00a195">否</font>
						</label>
				</div>
		
			</div>

		</div>

		<div class="modal-footer">
		
		    <input type="hidden" name="order_id" id="black_order_id" value="" />
		    
		    <input type="hidden" name="admin_name" id="black_admin_name" value="" />

			<input type="hidden" name="pat_id" id="black_pat_id" value="" />
			
			<button class="btn" data-dismiss="modal" aria-hidden="true"> 关闭 </button>

			<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onClick="black_add();"> 确定 </button>

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
</div>
</div>
</div>
</div>
</div>
</div>
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
	$(".lya").click(function(){
		 $("#"+$(this).attr("title")+"_lyweb").html('<form id="lywebaddform" method="post" action=""><input type="text" name="weburl" id="'+$(this).attr("title")+'_weburl" class="input_search" value="" placeholder="请在此处填入来源网址" style="width:120px;height:16px;font-size:12px;outline:none;"/><input type="hidden" name="order_id"  value="'+$(this).attr("title")+'"/><br/><a href="javascript:lywebadd('+$(this).attr("title")+');" class="btn btn-info">添加</a></form>'); 
	 });
	 
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
	
	$('#sidebar > ul').hide();
	$("#container").addClass("sidebar-closed");
	
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
		ajax_get_form(hos_id, 0);
	});
	
	$("#keshi_id").change(function(){
		var keshi_id = $(this).val();

		var order_query_seven_data = $("#order_query_seven_data").val();
		if(order_query_seven_data == 0){
			 var hos_id = $("#hos_id").val(); 
			if(hos_id  == 3  || hos_id  == 6){
				if(keshi_id  == 4  || keshi_id  == 85  || keshi_id  == 28  || keshi_id  == 88){
					//如果当前用户属于指定男科科室的  则默认 搜索时间为 前后3天 
					var time_str = '';//2016年09月01日 - 2016年10月21日
					var now = new Date();
					var date = new Date(now.getTime() - 3 * 24 * 3600 * 1000);
					var year = date.getFullYear();
					var month = date.getMonth() + 1;
					var day = date.getDate(); 
					time_str =year + '年' + month + '月' + day + '日';
					date = new Date(now.getTime() + 3 * 24 * 3600 * 1000);
					year = date.getFullYear();
					month = date.getMonth() + 1;
					day = date.getDate(); 
					time_str =time_str+" - "+year + '年' + month + '月' + day + '日'; 
					$("#inputDate").val(time_str);
				}
			}
		}

	});
	
	$("#from_parent_id").change(function(){
		var parent_id = $(this).val();
		ajax_from(parent_id, 0);
	});
	
	$("#p_jb").change(function(){
		var parent_id = $(this).val();
		ajax_get_jibing(0, parent_id, 0);
	});
	//快捷查询ajax开始
       $("#dy_fast").click(function(){
           var dy_order_no=$("#dy_order_no").val();
           var dy_order_name=$("#dy_order_name").val();
           var dy_order_phone=$("#dy_order_phone").val();
          
           $.ajax({
                type :'post',
                url :'?c=gonghai&m=daoyi_query',
                data :'dy_order_no='+dy_order_no+'&dy_order_name='+dy_order_name+'&dy_order_phone='+dy_order_phone,
                  success:function(data){
                      data = $.parseJSON(data);
		      type = data['type'];
                     
                      if(type==1){
                          $("#dy_fast").next("i").remove();
                        	$("#dy_fast").next("div").remove();
//						$("#dy_fast").parent().parent().removeClass("error");
                          $("#dy_fast").next("span").remove();
						$("#dy_fast").after("<i></i>");
						if(data['order'] != "")
						{
                                                       
							var html = '&nbsp;<div class="btn-group" style="width:1000px;padding-left:20%;"><button data-toggle="dropdown" class="btn btn-danger dropdown-toggle">当前患者已预约 <span class="caret"></span></button><ul class="dropdown-menu" style="width:900px;background-color:#eee;">';
							$.each(data['order'], function(key, value){
                                                                 if(value.is_come==0){
								html += '<li style="margin-top:10px;"><span style="width:860px;"><font color="green">(正常预约)</font>  患者姓名：<font color="red" >' + value.pat_name + '</font>、医院：<font color="red">' + value.hos_name + '</font>、预约号：<font color="red">' + value.order_no + '</font>、咨询员：<font color="red">' + value.admin_name + '</font>、登记时间：<font color="red">' + value.addtime + '</font></span><span style="width:40px;margin-left:30px;"><a href="#dao" role="button" class="btn btn-danger" data-toggle="modal" onClick="ajax_dao('+value.order_id+');">来院</a></span></li>';
                                                            }else{
                                                                html += '<li style="margin-top:10px;"><span style="width:860px;"><font color="green">(正常预约)</font>  患者姓名：<font color="red" >' + value.pat_name + '</font>、医院：<font color="red">' + value.hos_name + '</font>、预约号：<font color="red">' + value.order_no + '</font>、咨询员：<font color="red">' + value.admin_name + '</font>、登记时间：<font color="red">' + value.addtime + '</font></span><font color="green" style="margin-left:30px;">已来院</font></li>';
                                                            }
							});
                                                        if(data['gonghai']){
                                                        $.each(data['gonghai'], function(key, value){
                                                           if(value.is_come==0){
								html += '<li style="margin-top:10px;"><span style="width:860px;"><font color="blue">(公海患者)</font>  患者姓名：<font color="red">' + value.pat_name + '</font>、医院：<font color="red">' + value.hos_name + '</font>、预约号：<font color="red">' + value.order_no + '</font>、咨询员：<font color="red">' + value.admin_name + '</font>、登记时间：<font color="red">' + value.addtime + '</font></span><span style="width:40px;margin-left:30px;"><a href="#dao" role="button" class="btn btn-danger" data-toggle="modal" onClick="ajax_dao('+value.order_id+');">来院</a></span></li>';
							    }else{
                                                                html += '<li style="margin-top:10px;"><span style="width:860px;"><font color="blue">(公海患者)</font>  患者姓名：<font color="red">' + value.pat_name + '</font>、医院：<font color="red">' + value.hos_name + '</font>、预约号：<font color="red">' + value.order_no + '</font>、咨询员：<font color="red">' + value.admin_name + '</font>、登记时间：<font color="red">' + value.addtime + '</font></span> <font color="green" style="margin-left:30px;">已来院</font></li>';
                                                            }
                                                        });
                                                        }
							html += '</ul></div>';
							$("#dy_fast").after(html);
						}
                          
                      }else if(type==2){
                          
//                          $("#dy_fast").next("i").remove();
						$("#dy_fast").next("span").remove();
//						$("#dy_fast").parent().parent().removeClass("error");
						$("#dy_fast").after("<span style='color:red;margin-left:30px;'>查找不到相关数据</span>");
                          
                      }else if(type==3){
                          
//                          $("#dy_fast").next("i").remove();
						$("#dy_fast").next("span").remove();
//						$("#dy_fast").parent().parent().removeClass("error");
						$("#dy_fast").after("<span style='color:red;margin-left:30px;'>请输入最少一项查询条件</span>");
                          
                      }
                      
                      
                  },
                  complete: function (XHR, TS)
				{
				   XHR = null;
				}
        });
          
           
       });
       
       //快捷查询ajax结束
       
        
        
        
        
        
        
        
        
	/* ajax 获取回访备注内容 */
	var order_id = "<?php if(!empty($order_list)){foreach($order_list as $item){ echo $item['order_id'] . ",";}}?>";
	ajax_remark_list(order_id);

<?php if($hos_id > 0):?>
ajax_get_keshi(<?php echo $hos_id?>, <?php echo $keshi_id?>);
<?php endif;?>
<?php if($f_p_i > 0):?>
ajax_from(<?php echo $f_p_i?>, <?php echo $f_i?>);
<?php endif;?>
});

<?php if($p_jb > 0):?>
ajax_get_jibing(0, <?php echo $p_jb?>, <?php echo $jb?>);
<?php endif;?>

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
	$("div[id^='visit_']").html("...");
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
					if($("#visit_" + item.order_id).html() == "...")
					{
						$("#visit_" + item.order_id).html("");
						v_c = 1;
					}
					var str = "";
					//指定账户显示红色
					if(item.admin_id == 1195 || item.admin_id == 298){
						str += "<blockquote><p style='color:red;'>" + item.mark_content;
					}else{
					    str += "<blockquote><p>" + item.mark_content;
					}
					if(item.type_id > 0)
					{
						str += "<font color='#FF0000'>（未到诊原因：" + dao_false_arr[item.type_id] + "）</font>";
					}
					str += "</p><small><a href=\"###\">";
					str += item.admin_name + "</a> <cite>" + item.mark_time + "</cite><i>【" + v_c + "】</i></small></blockquote>";
					$("#visit_" + item.order_id).html(str + $("#visit_" + item.order_id).html());
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
						str += item.admin_name + "</a> <cite>" + item.mark_time + "</cite></small></blockquote>";
					}
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
	$("#p_jb").after("<i class='icon-refresh icon-spin'></i>");
	$.ajax({
		type:'post',
		url:'?c=order&m=jibing_ajax',
		data:'keshi_id=' + keshi_id + '&parent_id=' + parent_id + '&check_id=' + check_id,
		success:function(data)
		{
			if(parent_id == 0)
			{
				$("#jb").html(data);
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
		data:'parent_id=' + parent_id + '&from_id=' + from_id,
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
	$("#patient_info").html($("#pat_" + order_id).html());
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

function ajax_set_black(order_id,admin_name,pat_id,pat_blacklist){
	

	$("#set_black").children("btn").css("display", "none");

	$("#black_order_id").val(order_id);

	$("#black_admin_name").val(admin_name);

	$("#black_pat_id").val(pat_id);

    if(pat_blacklist==1){
      $('#black_pat_blacklist_1').attr("checked",true);  
    }else{
      $('#black_pat_blacklist_2').attr("checked",true);  
    }

	$("#black_patient_info").html($("#pat_" + order_id).html());

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

function visit_add()
{  
	var order_id = $("#visit_order_id").val();
	var false_id = $("#false_id").val();
	var remark = $("#visit_remark").val();
	$.ajax({
		type:'post',
		url:'?c=order&m=order_update_ajax',
		data:'order_id=' + order_id + '&false_id=' + false_id + '&remark=' + remark + '&type=visit',
		success:function(data)
		{
			$("#visit_" + order_id).html(data + $("#visit_" + order_id).html());
			//关闭框
			$('#visit').modal('hide');
		},
		complete: function (XHR, TS)
		{
		   XHR = null;
		}
	});
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
			}
		},
		complete: function (XHR, TS)
		{
		   XHR = null;
		}
	});
}

function black_add(){
	
    
    var order_id=$("#black_order_id").val();

    var admin_name=$("#black_admin_name").val();
	
	var pat_id = $("#black_pat_id").val();

	var pat_blacklist = $("input[name='black_pat_blacklist']:checked").val();

    $.ajax({

		type:'post',

		url:'?c=order&m=pat_update_ajax',

		data:'pat_id=' + pat_id + '&pat_blacklist=' + pat_blacklist,

		success:function(data)

		{

			if(data != '')

			{

				data = $.parseJSON(data);


				if(pat_blacklist==1){
					$('#black_'+order_id).parent().parent().addClass('blacklist');
				}else{
					$('#black_'+order_id).parent().parent().removeClass('blacklist');
				}

				$('#black_'+order_id).html('<a href="#set_black" role="button" class="btn" data-toggle="modal" onClick="ajax_set_black('+order_id+',\''+admin_name+'\','+pat_id+','+pat_blacklist+');_czc.push([\'_trackEvent\', \'预约列表\', \''+admin_name+'\', \'加入黑名单\',\'\',\'\']);">加入黑名单</a>');

		        
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


//兼容火狐、IE8   
//显示遮罩层    
function showMask(width,height,order_id){
	if($("#"+order_id+"mask").val() == null){
		$("#mask").html($("#mask").html()+'<div id="'+order_id+'mask" class="mask"></div> ');
	}
	  //margin-left:100px; margin-top:100px; 
	$("#"+order_id+"mask").css("height",25);
	$("#"+order_id+"mask").css("width",25);
	$("#"+order_id+"mask").css("margin-left",width-5);     
	$("#"+order_id+"mask").css("margin-top",height-5);
	    
	$("#"+order_id+"mask").show();  
}  
//隐藏遮罩层  
function hideMask(order_id){
	$("#"+order_id+"mask").hide();  
	$("#"+order_id+"mask").remove(); 
} 


function change_order_status(order_id)

{

	var classname = $("#status_" + order_id).attr("class");
showMask($("#"+order_id+"_check_order_status").offset().left,$("#"+order_id+"_check_order_status").offset().top,order_id);
	
	$.ajax({

		type:'post',

		url:'?c=order&m=change_order_status',

		data:'order_id=' + order_id,

		success:function(data)

		{
			if($("#l_admin_action").val() == 'all'  || $("#l_rank_type").val() == 4){

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
 hideMask(order_id);
}


   function lywebadd(order_id){
	     var  webtext = $("#"+order_id+"_weburl").val() ;
		  // 通过 form 的 id 取得 form
		 var $form = $('#lywebaddform');
		 var i = $.post("?c=order&m=setlyweb", $form.serialize(), function(data)
		{ 
		        if(data == 0){
					$("#"+order_id+"_weburl").val("");
					$("#"+order_id+"_weburl").attr("placeholder","添加失败");
				}else if(data == 1){
					$("#"+order_id+"_lyweb").html('<a href="'+webtext+'" target="_blank">'+webtext.substring(0,30)+'...</a>');
				} 
		}); 
	}

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