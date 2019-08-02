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



</style>



<script src="static/js/jquery.js"></script>



<script language="javascript">



if($(window).width() >= 1200)



{



//	window.location.href = '?c=order&m=order_list';



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



    <span><?php if($rank_type == 2 || $rank_type == 3 || $_COOKIE['l_admin_action'] == 'all'):?><a href="javascript:order_window('?c=order&m=order_info_liulian&type=mi');" target="_blank" onclick="_czc.push(['_trackEvent', '顶部链接', '<?php echo $admin['name']; ?>', '新增留联','','']);"><i class="icon-plus"></i> 新增留联</a><?php endif ?></span>

    <span>总留联人数：<font><?php echo $total_rows; ?></font></span> <span>总预约人数：<font><?php echo $total_ok_rows; ?></font></span>



 



</div>



             



             



             



                <!--导医快捷查询开始-->



             <div style="height:150px;background-color: lightblue;<?php if((in_array('3',  explode(',', isset($_COOKIE['l_hos_id'])?$_COOKIE['l_hos_id']:''))||in_array('15',  explode(',', isset($_COOKIE['l_hos_id'])?$_COOKIE['l_hos_id']:'')))&&$rank_type==3){



echo '';}else{ echo 'display: none';}?>">



        <span style="width:180px;color:red;font-size:18px;padding-left:45%;">导医快捷检索</span><br/>



        <span style="font-size:16px; padding-left:20%;">留联/预约单号：</span><input type="text" style="height:16px;width: 120px;" id="dy_order_no"/>



<span style="font-size:16px;padding-left: 30px;">患者姓名：<input type="text" style="height:16px;width: 120px;"id="dy_order_name"/></span>



<span style="font-size:16px;padding-left: 30px;">患者手机号：<input type="text" style="height:16px;width: 120px;"id="dy_order_phone"/></span>





					

<button type="button" class="btn btn-success" style="font-size:16px;margin-left: 30px;margin-top: -8px;" id="dy_fast">快捷检索</button>



    



                 



             </div>     



             



             



             



             



             <!--导医快捷查询结束-->



             



             



             



             <!--导医快捷查询结束-->



<form action="" method="get" class="date_form order_form1">



<input type="hidden" value="order" name="c" />



<input type="hidden" value="order_list_liulian" name="m" />



<input type="hidden" value="mi" name="type" />



<div class="span5">



    <div class="row-form">



	    <input type="hidden"  id="order_query_seven_data" value="<?php echo $order_query_seven_data ;?>"> 



		      <!--  时间类型 -->



		          <input type="hidden" name="t" value="1">



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



			<?php foreach($hospital as $val):?><option value="<?php echo $val['hos_id']; ?>" <?php if($val['hos_id'] == $hos_id){echo " selected";}?>><?php echo $val['hos_name']; ?></option><?php endforeach;?>



		</select>



		<select name="keshi_id" id="keshi_id" style="width:130px;">



			<option value=""><?php echo $this->lang->line('keshi_select'); ?></option>

<?php foreach($keshi as $val):if($val['hos_id'] == $hos_id){?><option value="<?php echo $val['keshi_id']; ?>" <?php if($val['keshi_id'] == $keshi_id){echo " selected";}?>><?php echo $val['keshi_name']; ?></option><?php } endforeach;?>





		</select>



	</div>



</div>



<div class="span3">



	<div class="row-form">



		<label class="select_label"><?php echo $this->lang->line('patient_name');?></label>



		<input type="text" value="<?php echo $p_n; ?>" class="input-medium" name="p_n"  />



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



		<input type="text" value="<?php echo $p_p; ?>" class="input-medium" name="p_p"  />



	</div>

</div>

<div class="span3">	

	<div class="row-form">

		<label class="select_label">患者微信</label>

		<input type="text" value="<?php echo $p_w; ?>" class="input-medium" name="p_w"  />

	</div>

</div>	





<div class="span3">	

	<div class="row-form">

		<label class="select_label">转为预约：</label>

			<select name="order_no_yy_check" id="order_no_yy_check" style="width:130px;">

					<option value="0"><?php echo $this->lang->line('please_select');?></option>

					<option value="1" <?php if($order_no_yy_check == 1){echo " selected";}?>>未预约</option>

					<option value="2" <?php if($order_no_yy_check == 1){echo " selected";}?>>已预约</option>

					</select>

	</div>

</div>

 

<div class="span3">	

	<div class="row-form">

		<label class="select_label">患者QQ</label>

		<input type="text" value="<?php echo $p_q; ?>" class="input-medium" name="p_q"  />

	</div>

 



<!--	<div class="row-form">



		<label class="select_label"><?php echo $this->lang->line('type_name');?></label>



		<select name="o_t" style="width:165px;">



			<option value="" selected ><?php echo $this->lang->line('please_select'); ?></option>



			<?php foreach($type_list as $val):?><option value="<?php echo $val['type_id']; ?>" <?php if($val['type_id'] == $p_jb){echo " selected";}?>><?php echo $val['type_name']; ?></option><?php endforeach;?>



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



    <button type="submit" class="btn btn-success" onsubmit="_czc.push(['_trackEvent', '留联列表', '<?php echo $admin['name']; ?>', '搜索','','']);"> 搜索 </button> <!--<a href="javascript:show(this);" id="gaoji" class="btn btn-default"> 高级 </a>-->



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

	foreach($order_list as $item):

		if(!in_array('179',$l_admin_action)){

			$item['pat_phone']  =  $item['pat_phone'][0].$item['pat_phone'][1].$item['pat_phone'][2].'*****';

			$item['pat_phone1']  =  $item['pat_phone1'][0].$item['pat_phone1'][1].$item['pat_phone1'][2].'*****';

		} 

		

  ?>



  <tr<?php if($i % 2){ echo " class='td1'";}?> style="height:90px;">



    <td>



       <a href="?c=order&m=order_info_liulian&order_id=<?php echo $item['order_id']; ?>&p=1" target="_blank"><?php echo $item['order_no']; ?></a><br />



	   <?php if($item['is_first']){ echo "初诊";}else{ echo "<font color='#FF0000'>复诊</font>";}?><br />



	    <?php if(!empty($item['order_no_yy'])){?>

	   	<a href="javascript:void(0)"><i id="status_401892" class="icon-ok" style="color: rgb(251, 153, 0); font-size: large;"></i></a>

       <?php  }?> 

    </td>



	<td style="text-align:left;">



    <div id="pat_<?php echo $item['order_id']; ?>">





        预约单号：<a href="?c=order&m=order_list&t=1&o_o=<?php echo $item['order_no_yy'];?>"><span style="color:blue;"><?php echo $item['order_no_yy'];?></span></a>&nbsp;<?php  if(!empty($item['order_is_come'])){echo '<span style="color:red;">已到诊</span>'; }?><br/>转单时间:<?php if($item['order_no_yy_time']){echo date("Y-m-d H:i:s",$item['order_no_yy_time']);}?><br/>

   

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

      QQ ：<font  ><?php echo $item['pat_qq']; ?></font><br />

		 微信：<font  ><?php echo $item['pat_weixin'];?></font><br />

		地区：<?php



		  if($item['pat_province'] > 0){ echo $area[$item['pat_province']]['region_name'];}



		  if($item['pat_city'] > 0){ echo "、" . $area[$item['pat_city']]['region_name'];}



		  if($item['pat_area'] > 0){ echo "、" . $area[$item['pat_area']]['region_name'];}?><br />



		<?php



		  if(!empty($item['pat_qq'])){



			  echo $item['pat_qq'] . "(QQ)";



		  }



		  elseif(isset($item['data_time']))



		  {



			  echo "【孕周】" . (intval((time() - $item['data_time']) / (86400 * 7)) + 1) . "周、【预产期】" . date("Y-m-d", ($item['data_time'] + (86400 * 280)));



		  }



		?>



    </div>



    </td>



	<td style="text-align:left;">



		<?php echo $this->lang->line('order_addtime'); ?>：<?php echo date("Y-m-d H:i:s",$item['order_addtime']) ;  ?><br />



		<div style="display: none;"><?php echo $this->lang->line('order_time'); ?>：<?php echo date("Y-m-d H:i:s",$item['order_time']) ;  ?> <font style="color:#F00; font-weight:bold;"><?php if($item['order_time_duan']){ echo $item['order_time_duan'];}?></font><br />



		<?php echo $this->lang->line('come_time'); ?>：<span   id="come_time_<?php echo $item['order_id']; ?>"><?php if($item['come_time'] > 0){ echo date("Y-m-d H:i:s", $item['come_time']);}?></span></div><br />



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



       <td  style="position:relative;">  

	<div class="remark" style="height: 70px; background: none; z-index: 1; padding-bottom: 10px;">

	<?php foreach($order_remark_list as $order_remark_list_t){ 

		if($order_remark_list_t['order_id'] == $item['order_id'] && $order_remark_list_t['mark_type'] == 3){?> 

		 <blockquote><p><?php echo $order_remark_list_t['mark_content'];?></p>

		 <small><a href="###"><?php echo  $order_remark_list_t['admin_name'];?></a> <cite><?php echo date("Y-m-d H:i:s", $order_remark_list_t['mark_time']);?></cite></small>

		 </blockquote> 

	  <?php  }

	}?>

		</div>

    </td>



   <td  style="position:relative;"> 

   <div class="remark"   style="height: 70px; background: none; z-index: 1; padding-bottom: 10px;">

	

   <?php foreach($order_remark_list as $order_remark_list_t){

		if($order_remark_list_t['order_id'] == $item['order_id'] && $order_remark_list_t['mark_type'] != 3){

			?> 

		<blockquote class="g">

		<p style="font-size:12px;"><?php echo $order_remark_list_t['mark_content'];?></p>

		<small><a href="###"><?php echo  $order_remark_list_t['admin_name'];?></a> <cite><?php echo date("Y-m-d H:i:s", $order_remark_list_t['mark_time']);?></cite></small>

		</blockquote>

	  <?php 

	  

		}

	}?></div>

    </td>



	<td>

	<a href="#kefu_hf" title="回访记录" id="hf_status_<?php echo $item['order_id'];?>" class="btn btn-info" role="button" data-toggle="modal" onClick="kefu_hf('<?php echo $item['order_id']?>');_czc.push(['_trackEvent', '留联相关', '<?php echo $admin['name']; ?>', '回访记录','','']);">添加回访记录</a><?php if(!empty($item['zampo'])):?></del><?php endif;?><br /><br /> 

	 

	 <a href="javascript:order_window('?c=order&m=order_info_liulian&type=mi&p=2&order_id=<?php echo $item['order_id'];?>');" class="btn btn-info" onclick="_czc.push(['_trackEvent', '留联列表', '<?php echo $admin['name']; ?>', '<?php echo $this->lang->line('edit'); ?>','','']);"><?php echo $this->lang->line('edit'); ?></a>

	   <?php if(empty($item['order_no_yy'])){?>

	         	 <br/><br/><a href="?c=order&m=order_info&order_liulian_id=<?php echo $item['order_id'];?>" class="btn btn-info" onclick="_czc.push(['_trackEvent', '预约列表', '<?php echo $admin['name']; ?>', '添加预约','','']);">添加预约</a>

	        <?php  }?>

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



			<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onClick="visit_add();"> 提交 </button>



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



                                            <?php foreach($doctor_list as $va){?>



                                            <option value="<?php echo $va['admin_name'];?>"><?php echo $va['admin_name'];?></option>



                                            <?php }?>



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



     <div id="kefu_hf" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true" style="width:80%; left:30%;">

		<div class="modal-header">

			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

			<h3 id="myModalLabel1">回访记录</h3>

		</div>

		<div class="modal-body" >

		  <form action="?c=order&m=ajax_hf_add_liulian" method="post"  id="hf_form"style="width:100%;">

		    <div class="control-group order_from">

				<label class="control-label">记录详情</label>

					<textarea class="input-xxlarge " rows="2" name="msg_hf" id="msg_hf"   style="width:500px;"></textarea>

					<input type="hidden" name="hf_order_id" id="hf_order_id"><!-- 隐藏留联单ID -->

				    <button type="button" id="hf_sumbit" class="btn btn-success" style="background-color:#00a186;"><i class="icon-ok"></i> <?php echo $this->lang->line('submit'); ?> </button>

				    <span style="color:red" id="hf_span"></span>

				    <span  id="hf_span_ok"></span>

			</div>

	     </form>

		</div>

		

		<div class="modal-body" id="hf">

		<div class="left hf_left" style="width:100%; height:300px; float:center; overflow-y: auto; overflow-x: hidden;">

		</div>

		</div>

		<div class="modal-footer">

			<button class="btn" data-dismiss="modal" aria-hidden="true"> 关闭 </button>

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



$("#hf_sumbit").click(function(){

	$("#hf_span").html("");

	$("#hf_span_ok").html("");

   if( $("#msg_hf").val() == null  ||  $("#msg_hf").val() == ''){ 

	   $("#hf_span").html("请填入记录");

   }else if($("#msg_hf").val().length > 1800){ 

	   $("#hf_span").html("你填的太多了,只可以填写500个字。");

   }else{

	   // 通过 form 的 id 取得 form

		var $form = $('#hf_form');  

		$.ajax({

			type:'post',

			url:$form.attr("action"),

			data:'hf_order_id=' +$("#hf_order_id").val()+"&msg_hf="+$("#msg_hf").val(),

			success:function(data){

				$("#hf_span_ok").html("添加成功，请看下面的第一条记录。");

				$(".hf_left").html(data+$(".hf_left").html());

			},

			complete: function (XHR, TS){

			   XHR = null;

			}

		}); 

	}

   

});



function kefu_hf(order_id){

	 $("#msg_hf").val("");

	$("#hf_order_id").val(order_id); 

	if(order_id >= 1){

		$.ajax({

			type:'post',

			url:'?c=order&m=hf_info_liulian',

			data:'order_id=' + order_id,

			success:function(data){

				$(".hf_left").html(data);

			},

			complete: function (XHR, TS){

			   XHR = null;

			}

		});

	}

}







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



		ajax_get_form(hos_id,0);



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



                                                       



							var html = '&nbsp;<div class="btn-group" style="width:1000px;padding-left:20%;"><button data-toggle="dropdown" class="btn btn-danger dropdown-toggle">当前患者已留联 <span class="caret"></span></button><ul class="dropdown-menu" style="width:900px;background-color:#eee;">';



							$.each(data['order'], function(key, value){



                                                                 if(value.is_come==0){



								html += '<li style="margin-top:10px;"><span style="width:860px;"><font color="green">(正常留联)</font>  患者姓名：<font color="red" >' + value.pat_name + '</font>、医院：<font color="red">' + value.hos_name + '</font>、留联号：<font color="red">' + value.order_no + '</font>、咨询员：<font color="red">' + value.admin_name + '</font>、登记时间：<font color="red">' + value.addtime + '</font></span><span style="width:40px;margin-left:30px;"><a href="#dao" role="button" class="btn btn-danger" data-toggle="modal" onClick="ajax_dao('+value.order_id+');">来院</a></span></li>';



                                                            }else{



                                                                html += '<li style="margin-top:10px;"><span style="width:860px;"><font color="green">(正常留联)</font>  患者姓名：<font color="red" >' + value.pat_name + '</font>、医院：<font color="red">' + value.hos_name + '</font>、留联号：<font color="red">' + value.order_no + '</font>、咨询员：<font color="red">' + value.admin_name + '</font>、登记时间：<font color="red">' + value.addtime + '</font></span><font color="green" style="margin-left:30px;">已来院</font></li>';



                                                            }



							});



                                                        if(data['gonghai']){



                                                        $.each(data['gonghai'], function(key, value){



                                                           if(value.is_come==0){



								html += '<li style="margin-top:10px;"><span style="width:860px;"><font color="blue">(公海患者)</font>  患者姓名：<font color="red">' + value.pat_name + '</font>、医院：<font color="red">' + value.hos_name + '</font>、留联号：<font color="red">' + value.order_no + '</font>、咨询员：<font color="red">' + value.admin_name + '</font>、登记时间：<font color="red">' + value.addtime + '</font></span><span style="width:40px;margin-left:30px;"><a href="#dao" role="button" class="btn btn-danger" data-toggle="modal" onClick="ajax_dao('+value.order_id+');">来院</a></span></li>';



							    }else{



                                                                html += '<li style="margin-top:10px;"><span style="width:860px;"><font color="blue">(公海患者)</font>  患者姓名：<font color="red">' + value.pat_name + '</font>、医院：<font color="red">' + value.hos_name + '</font>、留联号：<font color="red">' + value.order_no + '</font>、咨询员：<font color="red">' + value.admin_name + '</font>、登记时间：<font color="red">' + value.addtime + '</font></span> <font color="green" style="margin-left:30px;">已来院</font></li>';



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



		window.location.href = "http://www.renaidata.com/?c=order&m=order_list_liulian";



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



				html += "留联时间：" + data['ordertime'] + "<br />";



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



				html += "留联时间：" + data['ordertime'] + "<br />";



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



				html += "留联时间：" + data['ordertime'] + "<br />";



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







		},







		complete: function (XHR, TS)







		{







		   XHR = null;







		}







	});







}







</script>



</body>



</html>