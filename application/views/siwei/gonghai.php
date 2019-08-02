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
.sidebar-scroll{z-index:-1;}*/
.date_div{ position:absolute; top:55px; left:412px; z-index:999;}
.anniu{ display:none;}
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
.list_table .td2 td{
    
    background-color: linen;
}
.list_table .td3 td{
    
    background-color:lightpink;
}
.list_table .blacklist td{
    background-color:#999;
}
.list_table .exceed_15_hf td{
	background-color:#ec8a8a;
	border-top: 1px solid #999;
}
</style>
<script src="static/js/jquery.js"></script>
<script language="javascript">
//if($(window).width() >= 1200)
//{
//	window.location.href = '?c=order&m=order_list';
//}

function exprot_gonghai()
{ 
	  $("#m").val("gonghai_order_list_down");
	  $("#c").val("order");
	  $(".order_form1").submit();
	  $("#m").val("gonghai");
	  $("#c").val("gonghai");
	 
}

</script>
</head>

<body class="fixed-top">
 
<?php echo $top; ?>
<div id="container" class="row-fluid"> 
<?php echo $sider_menu; ?>

<div id="main-content"> 
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid" style="position:relative; padding-top:10px;"> 
<div class="order_count">
    
</div>
<form action="" method="get" class="date_form order_form1">
<input type="hidden" value="gonghai" name="c" id="c" />
<input type="hidden" value="gonghai" name="m" id="m"/>
<!--<input type="hidden" value="mi" name="type" />-->
<div class="span5">
    <div class="row-form">
		<select name="t" style="width:110px;">
			<?php foreach($this->lang->line('order_type') as $key=>$val):?><option value="<?php echo $key; ?>" <?php if($key == $t){echo " selected";}?>><?php echo $val; ?></option><?php endforeach;?>
			<option value="5" <?php if($t == 5){echo " selected";}?>>公海时间</option>
		</select>
		<input type="text" value="<?php echo $start; ?> - <?php echo $end; ?>" style="width:270px;" class="input-block-level" name="date" id="inputDate" />
    </div>
    <div class="date_div">
    <div class="divdate"></div>
    <div class="anniu"><a href="javascript:;" class="btn btn-inverse guanbi"> 关闭 </a><br /><a href="javascript:;" class="btn btn-inverse today"> 今天 </a><br /><a href="javascript:;" class="btn btn-inverse week"> 一周 </a><br /><a href="javascript:;" class="btn btn-inverse month"> 一月 </a><br /><a href="javascript:;" class="btn btn-inverse year"> 一年 </a></div>
    </div>
    

    <div class="row-form">
		<label class="select_label"><?php echo $this->lang->line('order_keshi');?></label>
		<select name="hos_id" id="hos_id" style="width:180px;">
			<option value=""><?php echo $this->lang->line('hospital_select'); ?></option>
			<?php foreach($hospital as $val):?><option value="<?php echo $val['hos_id']; ?>" <?php if($val['hos_id'] == $hos_id){echo " selected";}?>><?php echo $val['hos_name']; ?></option><?php endforeach;?>
		</select>
		<select name="keshi_id" id="keshi_id" style="width:130px;">
			<option value=""><?php echo $this->lang->line('keshi_select'); ?></option>
                </select>
	</div>
</div>
<div class="span3">
	<div class="row-form">
		<label class="select_label"><?php echo $this->lang->line('patient_name');?></label>
		<input type="text" value="<?php echo $p_n; ?>" class="input-medium" name="p_n"  />
	</div>
       总记录数：<font style="font-size:18px;" color="red"><?=$all_count?></font>
	   <?php if(strcmp($_COOKIE['l_admin_action'],'all') == 0){?><span>数据中心未推送：<font style="color:red;"><?php echo $ireport_order_ount; ?></font></span><?php }?>
</div>
<div class="span3">
	<div class="row-form">
		<label class="select_label"><?php echo $this->lang->line('order_no');?></label>
		<input type="text" value="<?php echo $o_o; ?>" class="input-medium" name="o_o"  />
	</div>
    <div class="row-form">

		<label class="select_label">咨询员</label>

		<input type="text" value="<?php echo $a_i; ?>" class="input-medium" name="a_i"  />

	</div>

</div>
<div class="span3">
    
    <div class="row-form">

		<label class="select_label"><?php echo $this->lang->line('patient_phone');?></label>

		<input type="text" value="<?php echo $p_p; ?>" class="input-medium" name="p_p"  />

	</div>
	<div class="row-form">
            <label class="select_label">类型选择</label>
            <!--给出3个不同的类型选项，分别赋予不同的类型is_gonghai,is_out,is_come-->
		<select name="gonghai_type" id="gonghai_type" style="width:150px; ">
                    
                    <option value="is_gonghai" <?php if($gonghai_type=="is_gonghai"){echo "selected='selected'";}else{echo "";}?>>公海患者 </option> 
                    <option value="is_out" <?php if($gonghai_type=="is_out"){echo "selected='selected'";}else{echo "";}?>>已被捞出患者 </option> 
                    <option value="is_come" <?php if($gonghai_type=="is_come"){echo "selected='selected'";}else{echo "";}?>>从公海捞出已到院</option> 
                </select>
	</div>
<div class="row-form"> 
		<label class="select_label">回访员</label> 
		<input type="text" value="<?php echo $a_h; ?>" class="input-medium" name="a_h"  /> 
	</div> 
</div>
<div class="order_btn1">
    <button type="submit" class="btn btn-success" onsubmit="_czc.push(['_trackEvent', '预约列表', '<?php echo $admin['name']; ?>', '搜索','','']);"> 搜索 </button> <!--<a href="javascript:show(this);" id="gaoji" class="btn btn-default"> 高级 </a>-->

      <span style="margin-left: 10px;"> <input type="button" class="input_search"  style="vertical-align:middle;height:30px; cursor:pointer;" value="导出" onClick="exprot_gonghai()"/>
   
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
  if(empty($order_list)){
      echo "<tr style='color:red;'><td colspan='10'>抱歉！当前暂时没有相关公海患者数据！</td></tr>";
  }else{
  $i = 0;
  
  	if(strcmp($_COOKIE['l_admin_action'],'all') == 0){
		$l_admin_action  = array("179");
	}else{
		$l_admin_action  = explode(',',$_COOKIE['l_admin_action']);
	} 
	foreach($order_list as $item):
	
	?>
	 <!-- 系统添加时间 -->
	<input type="hidden" id="order_add_time_check_<?php echo $item['order_id'];?>" value="<?php echo $item['order_addtime'];?>"/>
	<!--  当前所属咨询员 -->
	<input type="hidden" id="admin_id_check_<?php echo $item['order_id'];?>" value="<?php echo $item['admin_id'];?>"/> 
	<input type="hidden" id="order_hidden_time_check_<?php echo $item['order_id'];?>" value="<?php echo $item['order_time'];?>"/> 
	<input type="hidden" id="order_time_duan_check_<?php echo $item['order_id'];?>" value="<?php echo $item['order_time_duan'];?>"/> 
	
	 <? 
		if(!in_array('179',$l_admin_action)){
			$item['pat_phone']  =  $item['pat_phone'][0].$item['pat_phone'][1].$item['pat_phone'][2].'*****';
			$item['pat_phone1']  =  $item['pat_phone1'][0].$item['pat_phone1'][1].$item['pat_phone1'][2].'*****';
		} 
		 
      if($item['gonghai_type']=='gonghai'&&$item['is_come']==0){
  ?>
      
      <tr class="<?php if($i % 2){ echo 'td1';}?> <?php if($item['pat_blacklist']==1){echo 'blacklist';}?>  <?php if($item['exceed_15_hf'] == 1): ?> exceed_15_hf <?php endif; ?>" style="height:90px;">
          <?php } if($item['gonghai_type']!='gonghai'&&$item['is_come']==0){?>
          
      <tr style="height:90px;" class="td2 <?php if($item['pat_blacklist']==1){echo 'blacklist';}?>" >
              <?php } if($item['is_come']==1){?>
      <tr style="height:90px;" class="td3 <?php if($item['pat_blacklist']==1){echo 'blacklist';}?>" ><?php }?>
          
    <td>
<!--       <a href="?c=order&m=order_info&order_id= echo $item['order_id']; ?>&p=1" target="_blank"> echo $item['order_no']; ?></a><br />-->
       
         <a style="cursor:pointer;color:#333333;"   id="order_no_<?php echo $item['order_id']; ?>" <?php if(in_array(143, $admin_action)||$_COOKIE['l_admin_action'] == 'all'||$item['hos_id']==1){echo " onclick='open7(".$item['order_id'].");'"; }else{echo "";}?>><?php echo $item['order_no']; ?></a>
    
        <br />
	   <?php if($item['is_first']){ echo "初诊";}else{ echo "<font color='#FF0000'>复诊</font>";}?><br />
      
      <!-- 权限判断  -->       
	   <input type="hidden"  id="l_admin_action" value="<?php echo $_COOKIE['l_admin_action'];?>">	   	   
       <!-- 岗位角色  -->       
       <input type="hidden"  id="l_rank_type" value="<?php echo $rank_type;?>">	  
     
      <?php if(empty($item['is_to_order']) && $item['gonghai_type']=='gonghai'){ ?> 
    <a href="<?php if(($rank_type == 3) || ($rank_type == 4) || ($_COOKIE['l_admin_action'] == 'all')):?>javascript:change_order_status(<?php echo $item['order_id']; ?>);<?php else:?>javascript:;<?php endif;?>"><?php if($item['is_come'] > 0){ echo '<i id="status_' . $item['order_id'] . '" class="icon-ok" style="color:#F00; font-size: large;"></i>';}else{ echo '<i id="status_' . $item['order_id'] . '" class="icon-remove" style="color:#00F; font-size: large;"></i>'; }?></a>
    <?php }?> 
    </td>
	<td style="text-align:left;">
    <div id="pat_<?php echo $item['order_id']; ?>">
		姓名：<font color='#FF0000'><b><?php echo $item['pat_name']?></b></font>（<?php echo $item['pat_sex']; ?>、<?php echo $item['pat_age']?>岁）
		 <a href="javaScript:void(0);" title="详情"  onClick="detail_info('<?php echo $item['order_id']?>');"><img src="static/img/talk.png" width="16px;"/></a>
		 
		<br />
		
		
		<?php if($_COOKIE['l_admin_id'] == '239'):?>
		电话：<font id="pat_phone_<?php echo $item['order_id']; ?>"><?php echo $item['pat_phone']; if(!empty($item['pat_phone1'])){echo "/" . $item['pat_phone1'];}?></font><br />
		<?php else:?>
			<?php if($rank_type == 1 || $rank_type == 2 || $rank_type == 3 || $_COOKIE['l_admin_action'] == 'all' || $item['is_come'] > 0):?>
				<?php if(in_array($item['hos_id'],$hos_auth) && $rank_type == 2 && $item['admin_id'] != $_COOKIE['l_admin_id']):?>
					电话：<font id="pat_phone_<?php echo $item['order_id']; ?>"><?php echo $item['pat_phone']; if(!empty($item['pat_phone1'])){echo "/" . substr($item['pat_phone1'],0, -4) . "****";}?></font><br />
				<?php else:?>	
					电话：<font id="pat_phone_<?php echo $item['order_id']; ?>"><?php echo $item['pat_phone']; if(!empty($item['pat_phone1'])){echo "/" . $item['pat_phone1'];}?></font><br />
				<?php endif;?>
			<?php else:?>
				电话：<font id="pat_phone_<?php echo $item['order_id']; ?>"><?php echo $item['pat_phone']; if(!empty($item['pat_phone1'])){echo "/" . substr($item['pat_phone1'],0, -4) . "****";}?></font><br />
			<?php endif;?>
		<?php endif;?>
		地区：<?php
		  if(@$item['pat_province'] > 0){ echo $area[$item['pat_province']]['region_name'];}
		  if(@$item['pat_city'] > 0){ echo "、" . $area[$item['pat_city']]['region_name'];}
		  if(@$item['pat_area'] > 0){ echo "、" . $area[$item['pat_area']]['region_name'];}
              
                ?><br />

		<?php
                
		  if(!empty($item['pat_qq'])){
			  echo $item['pat_qq'] . "(QQ)";
                  }
		  elseif(isset($item['data_time']))
		  {
			  echo "【孕周】" . (intval((time() - $item['data_time']) / (86400 * 7)) + 1) . "周、【预产期】" . date("Y-m-d", ($item['data_time'] + (86400 * 280)));
		  }
                  if(!empty($item['pat_weixin'])){
                      echo "      ".$item['pat_weixin']."(微信) ";
                  }
		?>
    </div>
    </td>
	<td style="text-align:left;">
		<?php echo $this->lang->line('order_addtime'); ?>：<?php echo $item['order_addtime']; ?><br />
		<?php echo $this->lang->line('order_time'); ?>：<font id="order_time_<?php echo $item['order_id']; ?>"><?php echo $item['order_time']; ?></font> <font style="color:#F00; font-weight:bold;" id="order_time_duan_<?php echo $item['order_id']; ?>" ><?php if($item['order_time_duan']){ echo $item['order_time_duan'];}?></font><br />
		<?php echo $this->lang->line('come_time'); ?>：<span id="come_time_<?php echo $item['order_id']; ?>"><?php if($item['come_time'] > 0){ echo date("Y-m-d H:i", $item['come_time']);}?></span><br />
		<!--<?php echo $this->lang->line('doctor_time'); ?>：<span id="doctor_time_<?php echo $item['order_id']; ?>"><?php if($item['doctor_time'] > 0){ echo date("Y-m-d H:i", $item['doctor_time']);} ?></span>--></td>
	<td>
	<?php
    if(isset($from_list[$item['from_parent_id']])){  echo $from_list[$item['from_parent_id']]['from_name'] . "<br />"; }
    if(isset($from_arr[$item['from_id']])){  echo $from_arr[$item['from_id']]['from_name'] . "<br />"; }
    if(isset($type_list[$item['order_type']])){ echo $type_list[$item['order_type']]['type_name'];}
	?>
    </td>
	<td><?php
    if(isset($keshi[$item['keshi_id']])){echo $keshi[$item['keshi_id']]['keshi_name'];?>
	<input type="hidden" id="<?php echo $item['order_id']; ?>_keshi_id" value="<?php echo $item['keshi_id'];?>">
	<?php }
	?>
    
    </td>
	<td>
	<?php
      if(isset($jibing[$item['jb_parent_id']])){echo $jibing[$item['jb_parent_id']]['jb_name'];}
	  if(isset($jibing[$item['jb_id']])){echo "<br />" . $jibing[$item['jb_id']]['jb_name'];}
	?></td>
    <td>
        <?=$item['admin_name']?>
       <br /><br />
        <input type="hidden" name="hos_id_<?php echo $item['order_id'];?>" id="hos_id_<?php echo $item['order_id'];?>" value="<?php echo $item['hos_id'];?>" />
	<input type="hidden" name="keshi_id_<?php echo $item['order_id'];?>" id="keshi_id_<?php echo $item['order_id'];?>" value="<?php echo $item['keshi_id'];?>" />
	
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
        <?php 
	            $check_add_show = 0;
				$l_admin_action_s = $_COOKIE['l_admin_action'];
				if($_COOKIE['l_admin_action'] == 'all'){
					$check_add_show = 1;
				}else{
					$l_admin_action_s  =  "0'".str_replace(",","','",$l_admin_action_s)."'";
					//属于自己的数据 需要判断自己是否有捞取的权限
					if($item['admin_id'] != $_COOKIE['l_admin_id']){
						if(strpos($l_admin_action_s,"'184'")){
							$check_add_show = 1;
						}
					}else if(strpos($l_admin_action_s,"'180'")){//属于别人的数据 需要判断是否有捞取别人数据的权限
						$check_add_show = 1;
					}
				}?> 
	           <input type="hidden"  id="check_add_show_<?=$item['order_id']?>" value="<?php echo $check_add_show;?>"> 
	 
         <?php if(empty($item['is_to_order'])){ ?> 
            <div id="add_person_<?=$item['order_id']?>" style="display: none;"> <a href="?c=gonghai&m=update_name&order_id=<?=$item['order_id']?>" class=" btn btn-info">添加</a></div>
            <a href="#visit" role="button" class="btn btn-success" data-toggle="modal" onClick="ajax_remark(<?php echo $item['order_id']?>);_czc.push(['_trackEvent', '预约列表', '<?php echo $admin['name']; ?>', '<?php echo $this->lang->line('visit'); ?>','','']);"><?php echo $this->lang->line('visit'); ?></a>
	    <?php }else{?> 
			<font style="color:#ff6060;font-size:14px;font-weight:normal;">转为预约</font>
		 <?php }?> 
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
<!--	<div id="visit" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
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
                                  回访表中添加预约时间的选择
                                <div>
                                    <label class="control-label">预到时间</label>
                                    <input type="text" value="<?php echo date("Y-m-d",time())?>"  name="nextdate" id="nextdate" style="width:80px;"/><br/></div>
					
		<div class="date_lx" style="display:none;position:absolute;"><div class="lxdate"></div></div>
                  
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
			<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onClick="visit_add();"> 提交 </button>
		</div>
	</div>-->
	<div id="visit" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"  style="top:5%;">

		<div class="modal-header">

			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

			<h3 id="myModalLabel1">回访记录</h3>

		</div>


		<?php if ($is_nk_show == 1) { ?>


		<div class="modal-body" style="max-height:440px;">

			<div class="control-group">

				<div class="controls" id="patient_info"></div>

			</div>

			<div class="control-group">

				<div class="span12">
				<label class="control-label">下次预到<color id="order_time_type_msg" style="color:red;"></color></label>
				<?php
				       $gonghai_get =0;
				       if(strcmp($_COOKIE["l_admin_action"],'all') !=0){
							$l_admin_action = explode(",",$_COOKIE["l_admin_action"]);
							if (!in_array(184, $l_admin_action)){
								$gonghai_get =1;
							}
						} ?>
				<div class="controls">
                     <input type="text" value="<?php echo date("Y-m-d",time())?>"  name="nextdate" id="nextdate" <?php if($gonghai_get == 1){?> disabled="disabled"<?php }?> style="width:80px;float:left;" />
					<div class="input-append" style="margin-left:5px; float:left;">
						 <input type="text" name="datehour" id="datehour" style="width:80px;"  <?php if($gonghai_get == 1){?> disabled="disabled"<?php }?> value="10:00"/>

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




		<?php } else { ?>




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
				<?php
				       $gonghai_get =0;
				       if(strcmp($_COOKIE["l_admin_action"],'all') !=0){
							$l_admin_action = explode(",",$_COOKIE["l_admin_action"]);
							if (!in_array(184, $l_admin_action)){
								$gonghai_get =1;
							}
						} ?>
				<div class="controls">
                     <input type="text" value="<?php echo date("Y-m-d",time())?>"  name="nextdate" id="nextdate" <?php if($gonghai_get == 1){?> disabled="disabled"<?php }?> style="width:80px;float:left;" />
					<div class="input-append" style="margin-left:5px; float:left;">
						 <input type="text" name="datehour" id="datehour" style="width:80px;"  <?php if($gonghai_get == 1){?> disabled="disabled"<?php }?> value="10:00"/>

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




		<?php } ?>




		<div class="modal-footer">

			<input type="hidden" name="order_id" id="visit_order_id" value="" />

			<button class="btn" data-dismiss="modal" aria-hidden="true"> 关闭 </button>

			<button class="btn btn-primary" onClick="visit_add();"> 提交 </button>

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
   
   
    <!-- 百度編輯器  -->
    <script type="text/javascript" charset="utf-8" src="static/js/ueditor/ueditor.config.js"></script>
	<script type="text/javascript" charset="utf-8" src="static/js/ueditor/ueditor.all.min.js"> </script>
	<!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
	<!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
	<script type="text/javascript" charset="utf-8" src="static/js/ueditor/lang/zh-cn/zh-cn.js"></script>
     <script type="text/javascript"> 
	 //实例化编辑器
	 //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
	 var editor_info = UE.getEditor('editor_info',{toolbars:[[
	                                           'toggletool','fullscreen', 'source', '|', 'undo', 'redo', '|',
	                                           'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist','|',
	                                           'simpleupload', 'insertimage','snapscreen','|', 'selectall', 'cleardoc'
	                                       ]],initialFrameWidth:830,initialFrameHeight:400});
	                                       
	 </script>
	 <style type="text/css">
	.description{width:80%;}
	</style>
	
	
<script language="javascript">
var dao_false_arr = new Array();
<?php 
foreach($dao_false_arr as $key=>$val)
{
	echo "dao_false_arr[$key] = \"$val\";\r\n";
}
?>
$(document).ready(function(e) {
        ajax_timeout_all()
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
//	
//	$('#sidebar > ul').hide();
//	$("#container").addClass("sidebar-closed");
//	
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
	});
	
	$("#keshi_id").change(function(){
		var keshi_id = $(this).val();
	});
	
	$("#from_parent_id").change(function(){
		var parent_id = $(this).val();
		ajax_from(parent_id, 0);
	});
	
	$("#p_jb").change(function(){
		var parent_id = $(this).val();
		ajax_get_jibing(0, parent_id, 0);
	});
        //添加时间的选择
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
					str += "<blockquote><p>" + item.mark_content;
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
function ajax_timeout_all()
{
	$.ajax({
		type:'post',
		url:'?c=gonghai&m=update_gonghai_all',
		
		success:function(data)
		{
			
			
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
        
	var zt_id = $("#zt_id").val(0);
	
	var lx_id = $("#lx_id").val(0);
	
	var jg_id = $("#jg_id").val(0);
	
	var ls_id = $("#ls_id").val(0);
	
	var date_lx = $("#nextdate").val('<?php echo date("Y-m-d",time());?>');
	var datehour = $("#datehour").val();
	if(datehour == null || datehour == ''){
		datehour = '10:00';
	} 
	$("#patient_info").html($("#pat_" + order_id).html());
 
	$("#nextdate").val($("#order_hidden_time_check_"+order_id).val());
	$("#datehour").val($("#order_time_duan_check_"+order_id).val()); 
 
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
//
//function visit_add()
//{
//    //判断时间是否超过30天
//        var time=$("#nextdate").val();
//                          var time1=new Date(time);
//                          var time2=new Date();
//                          var end_time=time2.getTime()+30*24*60*60*1000;
//                          var dd=new Date(end_time);
//                          if(time1.getTime()>end_time){
//                              
//                              alert("对不起！亲，您所输入的预到时间不能超过30天哦！");
//                              return false;
//                          }else{
//	var order_id = $("#visit_order_id").val();
//	var false_id = $("#false_id").val();
//	var remark = $("#visit_remark").val();
//        //以下语句是获取相关的编号，根据编号显示相关的div
//        var add_id=$("#visit_order_id").val();
//        var add_per='#add_person_'+add_id;
//        $(add_per).css('display','block');
//	$.ajax({
//		type:'post',
//		url:'?c=order&m=order_update_ajax',
//		data:'order_id=' + order_id + '&false_id=' + false_id + '&remark=' + remark +'&nextdate='+ time + '&type=visit',
//		success:function(data)
//		{
//			$("#visit_" + order_id).html(data + $("#visit_" + order_id).html());
//		},
//		complete: function (XHR, TS)
//		{
//		   XHR = null;
//		}
//	});
//    }
//}
function visit_add()

{

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
		 if(time_ok == 0){
			 var date = new Date($("#order_add_time_check_"+order_id).val()); 
			 if (Date.parse(date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate()) > Date.parse(date_lx)) {
			 	 var time_html = '预到时间必须>=添加日期'+date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate(); 
				 $("#order_time_type_msg").html(time_html);
				msg = msg+br_html+time_html;
				time_ok = 1;
			 } else if($("#hos_id_"+order_id).val() == 3 || $("#hos_id_"+order_id).val() == 6){//只判断台州和东方
				  var time=date_lx;
				  var time1=new Date(time);
				  var time2=new Date();
				  var end_time=time2.getTime()+30*24*60*60*1000;
				  var start_time=time2.getTime()-24*60*60*1000;
				  var dd=new Date(end_time);
				  //属于台州  男科和 ，东方 男科的预到时间只能延期 7天，其他科室延期30天
				 if($("#keshi_id_"+order_id).val() == 4 || $("#keshi_id_"+order_id).val() == 28){
					  end_time=time2.getTime()+7*24*60*60*1000;  
					  if(time1.getTime()>end_time){ 
					  		var time_html = '预到时间不能超过7天哦！'; 
				 		    $("#order_time_type_msg").html(time_html);
						  msg = msg+br_html+time_html;
						  time_ok = 1;
					  } 
				  }else if(time1.getTime() > end_time && ($("#keshi_id_"+order_id).val() == 87 || $("#keshi_id_"+order_id).val() == 88 || $("#keshi_id_"+order_id).val() == 85 || $("#keshi_id_"+order_id).val() == 86)){ 
				      var time_html = '预到时间不能超过30天哦！'; 
				 	  $("#order_time_type_msg").html(time_html);
					  msg = msg+br_html+time_html;
					  time_ok = 1;
				  }
			 }
		 } 
	}

    if(time_ok == 1){
	  msg = msg+br_html+'预到时间错误,请检查'; 
	  $("#nextdate").focus();
    } 
    if(msg != ''){
		alert(msg);
	    $('#visit').modal('show');
	}else{
		//检查数据是否还在公海
		$.ajax({
			type:'post',
			url:'?c=gonghai&m=gonghai_check_exits_ajax',
			data:'order_id=' + order_id,
			success:function(data){
				 if(data == 1){
						//以下语句是获取相关的编号，根据编号显示相关的div
						var add_id=$("#visit_order_id").val();
						var add_per='#add_person_'+add_id;

						//以下语句是获取相关的编号，根据编号显示相关的div
						var add_id=$("#visit_order_id").val();
						var add_per='#add_person_'+add_id; 
						if($("#check_add_show_"+add_id).val() == 1){
							$(add_per).css('display','block');
						} 
						 
						$.ajax({
							type:'post',
							url:'?c=gonghai&m=gonghai_update_ajax',
							data:'order_id=' + order_id + '&false_id=' + false_id + '&zt_id=' + zt_id + '&lx_id=' + lx_id + '&jg_id=' + jg_id + '&ls_id=' + ls_id + '&date_lx=' + date_lx + '&remark=' + remark + '&datehour=' + datehour + '&type=visit&gonghai=1',
							success:function(data){ 
								$("#visit_" + order_id).html(data + $("#visit_" + order_id).html());
								//关闭框
								$('#visit').modal('hide');
								//变更 预到数据日期
								$("#order_time_"+order_id).html(date_lx);
								//变更 预到数据时间
								$("#order_time_duan_"+order_id).html(datehour);
				 
								$("#order_hidden_time_check_"+order_id).val(date_lx);
								$("#order_time_duan_check_"+order_id).val(datehour); 
							},
							complete: function (XHR, TS){
							   XHR = null;
							}
						});
				 }else{
					 alert("数据已经不在公海了");
			     }
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

function change_order_status(order_id)

{

	var classname = $("#status_" + order_id).attr("class");

	$.ajax({

		type:'post',

		url:'?c=order&m=change_order_status',

		data:'order_id=' + order_id,

		success:function(data)

		{

			if(classname == 'icon-ok')

			{
				if($("#l_admin_action").val() == 'all'  || $("#l_rank_type").val() == 4){
				$("#status_" + order_id).removeClass("icon-ok");

				$("#status_" + order_id).addClass("icon-remove");

				$("#status_" + order_id).css("color", "#00F");
				}
			}

			else

			{

				$("#status_" + order_id).addClass("icon-ok");

				$("#status_" + order_id).removeClass("icon-remove");

				$("#status_" + order_id).css("color", "#F00");

				$("#come_time_" + order_id).html(data);

			}

		},

		complete: function (XHR, TS)

		{

		   XHR = null;

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


function open7(order_id)
{
	var diag = new Dialog();
	diag.Width = 820;
	diag.Height = 800;
	diag.Title = "预约操作记录页";
	diag.URL = "?c=gonghai&m=order_detail&order_id="+order_id;
	diag.show();
}

 
function detail_info(order_id){
	var diag = new Dialog();
	diag.Width = 1200;
	diag.Height = 800;
	diag.Title = "回访记录 备注查看页";
	diag.URL = "?c=gonghai&m=get_remark_info&order_id="+order_id;
	diag.show();
}
</script>
</body>
</html>