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

.date_div{ position:absolute; top:55px; left:412px; z-index:999;}

.anniu{ display:none;}

.o_a a{ padding:0 10px;}

.order_form{ height:130px}

.black_overlay{display: none;position: absolute;top: 0%;left: 0%;width: 100%;height: 100%;background-color: black;z-index:1001;-moz-opacity: 0.8;opacity:.80;filter: alpha(opacity=80);}

</style>

<script src="static/js/jquery.js"></script>

</head>



<body class="fixed-top">

	 <div class="row-fluid">

			<div class="span12">

				 <table width="100%" border="0" cellspacing="0" cellpadding="2" class="list_table">

						<thead>

						  <tr>

							<th width="30">序号</th>

							<th>编号</th>

							<th>医院科室</th>

							<th>患者信息</th>

							<th>咨询</th>

							<th>类型</th>

						  </tr>

						</thead>

						<tbody>

						 <?php

						 $keshi_check_ts = $this->config->item('keshi_check_ts');

						 $keshi_check_ts = explode(",",$keshi_check_ts);

						 $zixun_check_ts = $this->config->item('zixun_check_ts');

						 $zixun_check_ts = explode(",",$zixun_check_ts);

						 if(count($gonghai_order) > 0){

						  $i = 0;

						  foreach($gonghai_order as $item){

							?>

						  <tr  <?php if($i % 2){ echo " class='td1'";}?>>

						    <td><b><?php echo $now_page + $i + 1; ?></b></td>

						    <td><?php echo $item['order_no']?></td>

						    <td><?php echo $item['hos_name']?>&nbsp;&nbsp;<?php echo $item['keshi_name'];?></td>

						    <td><?php

						    foreach ($patient as $patient_temp){

								//咨询只能看自己的电话 其他电话不可见

								if(in_array($_COOKIE["l_rank_id"], $zixun_check_ts) && $rank_type == 2 && $item['hos_id'] == 3 && in_array($item['keshi_id'], $keshi_check_ts)){

									if($_COOKIE['l_admin_id'] != $item['admin_id']){

										$patient_temp['pat_phone']  =  $patient_temp['pat_phone'][0].$patient_temp['pat_phone'][1].$patient_temp['pat_phone'][2].'*****';

										$patient_temp['pat_phone1']  =  $patient_temp['pat_phone1'][0].$patient_temp['pat_phone1'][1].$patient_temp['pat_phone1'][2].'*****';

}

								}



								if($patient_temp['pat_id'] == $item['pat_id']){

									if(!empty($patient_temp['pat_phone1'])){$patient_temp['pat_phone'] =  "/" . $patient_temp['pat_phone1'];}

									echo '姓名：'.$patient_temp['pat_name'].'&nbsp;电话：'.$patient_temp['pat_phone'];

									break;

								}

						    }

						    ?></td>

						    <td><?php echo $item['admin_name']?></td>

						     <td>公海数据</td>

						  </tr>

						  <?php   $i ++;} ?>

						  <?php }?>



						   <?php if(count($order) > 0){?>

						   <?php

						  $i = 0;

						  foreach($order as $item){

							?>

						  <tr  <?php if($i % 2){ echo " class='td1'";}?>>

						    <td><b><?php echo $now_page + $i + 1; ?></b></td>

						    <td><?php echo $item['order_no']?></td>

						    <td><?php echo $item['hos_name']?>&nbsp;&nbsp;<?php echo $item['keshi_name']?></td>

						    <td><?php

						    foreach ($patient as $patient_temp){

									//咨询只能看自己的电话 其他电话不可见

									if($rank_type == 2 && $item['hos_id'] == 3 && in_array($item['keshi_id'], $keshi_check_ts)){

										if($_COOKIE['l_admin_id'] != $item['admin_id']){

											$patient_temp['pat_phone']  =  $patient_temp['pat_phone'][0].$patient_temp['pat_phone'][1].$patient_temp['pat_phone'][2].'*****';

										}

									}



								if($patient_temp['pat_id'] == $item['pat_id']){

									echo  $patient_temp['pat_name'].'&nbsp;'.$patient_temp['pat_phone'];break;

								}

						    }

						    ?></td>

						    <td><?php echo $item['admin_name']?></td>

						    <td>预约数据</td>

						  <?php }}?>

							<?php if(count($ll_order) > 0):?>
							<?php $i = 0;foreach($ll_order as $item):?>
							<tr  <?php if($i % 2){ echo " class='td1'";}?>>
								    <td><b><?php echo $now_page + $i + 1; ?></b></td>
								    <td><?php echo $item['order_no']?></td>
								    <td><?php echo $item['hos_name']?>&nbsp;&nbsp;<?php echo $item['keshi_name']?></td>
								    <td><?php
								    foreach ($patient as $patient_temp){
											//咨询只能看自己的电话 其他电话不可见
											if($rank_type == 2 && $item['hos_id'] == 3 && in_array($item['keshi_id'], $keshi_check_ts)){
												if($_COOKIE['l_admin_id'] != $item['admin_id']){
													$patient_temp['pat_phone']  =  $patient_temp['pat_phone'][0].$patient_temp['pat_phone'][1].$patient_temp['pat_phone'][2].'*****';
												}
											}

										if($patient_temp['pat_id'] == $item['pat_id']){
											echo  $patient_temp['pat_name'].'&nbsp;'.$patient_temp['pat_phone'];break;
										}
								    }
								    ?></td>
								    <td><?php echo $item['admin_name']?></td>
								    <td>留联数据</td>
								</tr>
							<?php endforeach;?>
							<?php endif;?>

							<?php if(count($ll_gh_order) > 0):?>
							<?php $i = 0;foreach($ll_gh_order as $item):?>
							<tr  <?php if($i % 2){ echo " class='td1'";}?>>
								    <td><b><?php echo $now_page + $i + 1; ?></b></td>
								    <td><?php echo $item['order_no']?></td>
								    <td><?php echo $item['hos_name']?>&nbsp;&nbsp;<?php echo $item['keshi_name']?></td>
								    <td><?php
								    foreach ($patient as $patient_temp){
											//咨询只能看自己的电话 其他电话不可见
											if($rank_type == 2 && $item['hos_id'] == 3 && in_array($item['keshi_id'], $keshi_check_ts)){
												if($_COOKIE['l_admin_id'] != $item['admin_id']){
													$patient_temp['pat_phone']  =  $patient_temp['pat_phone'][0].$patient_temp['pat_phone'][1].$patient_temp['pat_phone'][2].'*****';
												}
											}

										if($patient_temp['pat_id'] == $item['pat_id']){
											echo  $patient_temp['pat_name'].'&nbsp;'.$patient_temp['pat_phone'];break;
										}
								    }
								    ?></td>
								    <td><?php echo $item['admin_name']?></td>
								    <td>留联公海数据</td>
								</tr>
							<?php endforeach;?>
							<?php endif;?>

						 </tbody>

				</table>

		</div>

	</div>

</body>

</html>