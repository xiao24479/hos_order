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

#main-content{margin-left:180px;}

/*#sidebar{margin-left:0px; z-index:-1;}

.sidebar-scroll{z-index:-1;}*/

.date_div{ position:absolute; top:80px; left:30px; z-index:1000;}

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
z-index:999;
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
z-index:999;
overflow: auto;
}
.modal{width:50%; left:50%;margin-left:-25%;}
.list_table{
   background-color: #00a186;
    border-bottom: 1px solid #dadada ;
    border-left: 0px solid #9d4a9c;


}
.list_table th{

    background-color:#000;
}
.list_table td{

   border-right: 1px solid #dadada ;
}
.list_table .over_list td {
    background: #ffffff none repeat scroll 0 0;
}
.list_table .td1 td {
    background-color: #ebebeb;
}
.list_table .blacklist td{
    background-color:#999;
}
.remark blockquote, .sms_content blockquote {
    border-left: 0px solid #ffffff;
    margin: 0 0 5px;
    padding-left: 5px;
    text-align: left;
}
.remark blockquote.d, .sms_content blockquote.d {
    border-left: 0px solid #ffffff;
    font-size: 12px;
}
.remark blockquote.g, .sms_content blockquote {
    border-left: 0px solid #ffffff;
     font-size: 12px;
}
.remark blockquote.r {
    border-left: 0px solid #000000;
     font-size: 12px;
}
.remark blockquote.doc {
    border-left: 0px solid #00f;
     font-size: 12px;
}
.icon-ok::before {
    content: "";
}
.icon-remove::before {
    content: "";
}
.btn-info {
    background-color: #287895;
    background-image: linear-gradient(to bottom, #5bc0de, #2f96b4);
    background-repeat: repeat-x;
    border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
    color: #fff;
    text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
}
.btn-success {
    background-color: #00a186;
    background-image: linear-gradient(to bottom, #62c462, #51a351);
    background-repeat: repeat-x;
    border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
    color: #fff;
    text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
}
.btn-danger {
    background-color: #fb9900;
    background-image: linear-gradient(to bottom, #ee5f5b, #bd362f);
    background-repeat: repeat-x;
    border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
    color: #fff;
    text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
}
.select_label{
    font-size:12px;
}

/**2016 10 27  新增加 表头的 下拉框 效果**/
/* main menu styles */
.nav_title,.nav_title ul {
    list-style:none;
    margin:0;
    padding:0;
}
.nav_title {
    height:41px;
    padding-left:5px;
    padding-top:5px;
    position:relative;
    z-index:2;
}
.nav_title ul {
    left:-9999px;
    position:absolute;
    top:37px;
    width:auto;
}
.nav_title ul ul {
    left:-9999px;
    position:absolute;
    top:0;
    width:auto;
}
.nav_title li {

    position:relative;
}
.nav_title .fly {

    color:#fff;
    display:block;
    text-decoration:underline; line-height:36px
}

.nav_title .dd li a { background:#00a186;
    text-decoration:none;  /*超链接无下划线*/
    color:#fff;
    display:block;
    float:left;
    border-bottom:1px dashed #CCC;
    padding:4px 0px;
    text-decoration:none;
}

.nav_title > li > a {
	text-decoration:none;  /*超链接无下划线*/
    -moz-border-radius:6px;
    -webkit-border-radius:6px;
    -o-border-radius:6px;
    border-radius:6px;

    overflow:hidden;
}

.nav_title ul li {
    margin:0;
}
.nav_title ul li a {
	text-decoration:none;  /*超链接无下划线*/
    width:120px;
}
.nav_title ul li a.fly {
	text-decoration:none;  /*超链接无下划线*/
    padding-right:10px;
}

/*hover styles*/
.nav_title .dd li:hover > a {
	text-decoration:none;  /*超链接无下划线*/
    background-color:#399;
    color:#fff;
}

/*focus styles*/
.nav_title li a:focus {
	text-decoration:none;  /*超链接无下划线*/
    outline-width:0;
}

/*popups*/
.nav_title li a:active + ul.dd,.nav_title li a:focus + ul.dd,.nav_title li ul.dd:hover {
	text-decoration:none;  /*超链接无下划线*/
    left:0;
}
.nav_title ul.dd li a:active + ul,.nav_title ul.dd li a:focus + ul,.nav_title ul.dd li ul:hover {
	text-decoration:none;  /*超链接无下划线*/
    left:140px;
}

 .mask {
		position: absolute; top: 0px; filter: alpha(opacity=60); background-color: #777;
		z-index: 1002; left: 0px;
		opacity:0.5; -moz-opacity:0.5;
	}
</style>

<script src="static/js/jquery.js"></script>



</head>



<body class="fixed-top" style="width:100%;margin:0 auto; ">

	   <!--遮罩层使用 div -->
 <div id="mask" class="mask"></div>


<?php echo $top; ?>

<div id="container" class="row-fluid" >

<?php echo $sider_menu; ?>



    <div id="main-content" style="margin-left:180px;">

         <!-- BEGIN PAGE CONTAINER-->

         <div class="container-fluid" style="position:relative; padding-top:0px;margin-left: 0px;background-color: #f7f7f7;">


             <div id="top">



	  <div class="row-fluid"  style="border:0px;" id="tab1">

              <div class="span12" style="border:0px;">

                    <table width="100%" border="0px" cellspacing="0" cellpadding="2" class="list_table" style="margin-top:20px;font-size:12px;">

  <thead>

  <tr>

	<th width="30">序号</th>

    <th width="50"><?php echo $this->lang->line('order_no'); ?></th>

	<th><?php echo $this->lang->line('patient_info'); ?></th>

	<th width="200">


    <input type="hidden" id="select_type_value" value="<?php echo $this->lang->line('time'); ?>">

    <ul class="nav_title">

        <li><a class="fly" id="select_type_class" href="javaScript:void(0);" style="text-decoration:none;"><?php echo $this->lang->line('time'); ?></a>
        </li>

    </ul>

    </th>

	<th width="80">
     <input type="hidden" id="appointment_route_value" value="途径/性质">

     <ul class="nav_title">

        <li><a class="fly" id="appointment_route_class" href="javaScript:void(0);"  style="text-decoration:none;">途径/性质</a>
        </li>

    </ul>

    </th>

	<th width="70">
     <input type="hidden" id="appointment_section_value" value="<?php echo $this->lang->line('order_keshi'); ?>">

     <ul class="nav_title">

        <li><a class="fly" id="appointment_section_class" href="javaScript:void(0);"  style="text-decoration:none;"><?php echo $this->lang->line('order_keshi'); ?></a>
        </li>

    </ul>

    </th>

	<th width="70">
    <input type="hidden" id="appointment_disease_value" value="<?php echo $this->lang->line('patient_jibing'); ?>">

      <ul class="nav_title">

        <li><a class="fly" id="appointment_disease_class" href="javaScript:void(0);"  style="text-decoration:none;"><?php echo $this->lang->line('patient_jibing'); ?></a>
        </li>

    </ul>

    </th>

    <th width="65">
    <input type="hidden" id="consult_a_doctor_value" value="咨询/医生">

     <ul class="nav_title">

        <li><a class="fly" id="consult_a_doctor_class" href="javaScript:void(0);"  style="text-decoration:none;">咨询/医生</a>
        </li>

    </ul>

    </th>

	<th width="200"><?php echo $this->lang->line('visit'); ?></th>

    <th width="200"><?php echo $this->lang->line('notice'); ?></th>

	<th width="180" style="border-right:1px solid #9D4A9C;"><?php echo $this->lang->line('action'); echo $_COOKIE["l_rank_id"];?></th>

  </tr>

  </thead>

   <tbody>

  <?php
  if(!empty($order_list)){
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
	foreach($order_list as $item){
		if(!in_array('179',$l_admin_action)){
			$item['pat_phone']  =  $item['pat_phone'][0].$item['pat_phone'][1].$item['pat_phone'][2].'*****';
			$item['pat_phone1']  =  $item['pat_phone1'][0].$item['pat_phone1'][1].$item['pat_phone1'][2].'*****';
		}
		//咨询只能看自己的电话 其他电话不可见
		if(in_array($_COOKIE["l_rank_id"], $zixun_check_ts) &&  $rank_type == 2 && $item['hos_id'] == 3 && in_array($item['keshi_id'], $keshi_check_ts)){
			if($_COOKIE['l_admin_id'] != $item['admin_id']){
				//$item['pat_phone']  =  $item['pat_phone'][0].$item['pat_phone'][1].$item['pat_phone'][2].'*****';
			}
		}

        ?>
      <tr class="<?php if($i % 2){ echo 'td1';}?> <?php if($item['pat_blacklist']==1){echo 'blacklist';}?>" style="height:90px;"  id="<?php echo $item['order_id']; ?>"  title="<?php echo date('H',strtotime($item['order_addtime'])); ?>">

    <td><b style="color:#808080;font-size:16px;"><?php echo $now_page + $i + 1; ?></b></td>

    <td>
  	<?php  if($item['keshi_id'] == 7 && $item['from_parent_id'] == 23 && ($admin['rank_id'] == 21 || $admin['rank_id'] == 28)): ?>
           <?php echo $item['order_no']; ?>
	 <?php else:?>
     <a style="cursor:pointer;color:#333333;"   id="order_no_<?php echo $item['order_id']; ?>" <?php if(in_array(143, $admin_action)||$_COOKIE['l_admin_action'] == 'all'||$item['hos_id']==1){echo " onclick='open7(".$item['order_id'].");'"; }else{echo "";}?>><?php echo $item['order_no']; ?></a>

	 <?php endif;?>
<br />

	   <?php if($item['is_first']){ echo "初诊";}else{ echo "<font color='#FF0000'>复诊</font>";}?><br />

	   <!-- 权限判断  -->
       <input type="hidden"  id="l_admin_action" value="<?php echo $_COOKIE['l_admin_action'];?>">
	    <!-- 岗位角色  -->
       <input type="hidden"  id="l_rank_type" value="<?php echo $rank_type;?>">



    </td>

	<td style="text-align:left;">

    <div id="pat_<?php echo $item['order_id']; ?>">

        姓名：<?php if(!empty($item['zampo'])):?><del><?php endif;?><font style="color:#ff6060;font-size:14px;font-weight:normal;" id="pat_name_<?php echo $item['order_id']; ?>"><?php echo $item['pat_name']?></font>（<?php echo $item['pat_sex']; ?>、<?php echo $item['pat_age']?>岁）<?php if(!empty($item['zampo'])):?></del><?php endif;?><br />
		<?php if($_COOKIE['l_admin_id'] == '239'||$_COOKIE['l_admin_id'] == '215'):?>
		电话：<font id="pat_phone_<?php echo $item['order_id']; ?>"><?php echo $item['pat_phone']; if(!empty($item['pat_phone1'])){echo "/" . $item['pat_phone1'];}?></font><br />
		QQ：<font id="pat_qq_<?php echo $item['order_id']; ?>"><?php echo $item['pat_qq']; ?></font><br />
		微信：<font id="pat_weixin_<?php echo $item['order_id']; ?>"><?php echo $item['pat_weixin'];?></font><br />
		<?php else:?>
			<?php if($rank_type == 2|| $rank_type == 1|| $_COOKIE['l_admin_action'] == 'all' || $item['is_come'] > 0):?>
				<?php if(in_array($item['hos_id'],$hos_auth) && $rank_type == 2 && $item['admin_id'] != $_COOKIE['l_admin_id']):?>
				 电话：<font id="pat_phone_<?php echo $item['order_id']; ?>"><?php echo $item['pat_phone']; if(!empty($item['pat_phone1'])){echo "/" . $item['pat_phone1'];}?></font><br />
				QQ：<font id="pat_qq_<?php echo $item['order_id']; ?>"><?php echo $item['pat_qq']; ?></font><br />
				微信：<font id="pat_weixin_<?php echo $item['order_id']; ?>"><?php echo $item['pat_weixin'];?></font><br />
				<?php else:?>
				电话：<font id="pat_phone_<?php echo $item['order_id']; ?>"><?php echo $item['pat_phone']; if(!empty($item['pat_phone1'])){echo "/" . $item['pat_phone1'];}?></font><br />
				QQ：<font id="pat_qq_<?php echo $item['order_id']; ?>"><?php echo $item['pat_qq']; ?></font><br />
				微信：<font id="pat_weixin_<?php echo $item['order_id']; ?>"><?php echo $item['pat_weixin'];?></font><br />
				<?php endif;?>
			<?php else:?>


			电话：<font id="pat_phone_<?php echo $item['order_id']; ?>"><?php echo $item['pat_phone']; if(!empty($item['pat_phone1'])){echo "/" . $item['pat_phone1'];}?></font><br />
			QQ：<font id="pat_qq_<?php echo $item['order_id']; ?>"><?php echo $item['pat_qq']; ?></font><br />
			微信：<font id="pat_weixin_<?php echo $item['order_id']; ?>"><?php echo $item['pat_weixin'];?></font><br />

			<?php endif;?>
		<?php endif;?>

		地区：<?php

		  if($item['pat_province'] > 0){ echo $area[$item['pat_province']]['region_name'];}

		  if($item['pat_city'] > 0){ echo "、" . $area[$item['pat_city']]['region_name'];}

		  if($item['pat_area'] > 0){ echo "、" . $area[$item['pat_area']]['region_name'];}?><br />

		  <?php if(strcmp($_COOKIE['l_admin_action'],'all') == 0){?> 数据中心状态: <?php if(empty($item['ireport_order_id'])){echo '<span style="color:red;">未推送</span>'; }
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


来源网址：
        <?php
		    $ly_check =0 ;
		    foreach ($order_laiyuanweb as $order_laiyuanweb_temp){
		    	if(strcmp($order_laiyuanweb_temp['order_id'],$item['order_id']) == 0  && !empty($order_laiyuanweb_temp['form'])){
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

		<?php echo $this->lang->line('order_addtime'); ?>：<font id="order_addtime_<?php echo $item['order_id']; ?>"><?php echo $item['order_addtime']; ?></font><br />

		<?php echo $this->lang->line('order_time'); ?>：<font id="order_time_<?php echo $item['order_id']; ?>"><?php echo $item['order_time']; ?></font> <font style="color:#ff6060; font-weight:bold;" id="order_time_duan_<?php echo $item['order_id']; ?>"><?php if($item['order_time_duan']){ echo $item['order_time_duan'];}?></font><br />

		<?php echo $this->lang->line('come_time'); ?>：<span id="come_time_<?php echo $item['order_id']; ?>"><?php if($item['come_time'] > 0){ echo date("Y-m-d H:i", $item['come_time']);}?></span><br />

  </td>

	<td>
            <p style="color:red;" id="gonghai_status_<?php echo $item['order_id'];?>"></p>

            <p style="color:red;" id="liulian_status_<?php echo $item['order_id'];?>"></p>

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

    <td style="color:#00a186"><?php echo $item['admin_name']?><br /><br />
	<input type="hidden" name="hos_id_<?php echo $item['order_id'];?>" id="hos_id_<?php echo $item['order_id'];?>" value="<?php echo $item['hos_id'];?>" />
	<input type="hidden" name="keshi_id_<?php echo $item['order_id'];?>" id="keshi_id_<?php echo $item['order_id'];?>" value="<?php echo $item['keshi_id'];?>" />
	<span id="doctor_<?php echo $item['order_id'];?>" style="color:#ff6060;"><?php echo $item['doctor_name']?></span>
	</td>

    <td style="position:relative;">

        <div class="remark" id="visit_<?php echo $item['order_id']; ?>" >

		<?php
		if(!empty($item['zampo'])){

			$str_out = '<blockquote ><p><font color=#FF0000 >（该预约用户确认不来院）</font></p><small><a>' . $item['z_name'] . '</a> <cite>' . date("m-d H:i",$item['zampo']) . '</cite></small></blockquote>';
			echo $str_out;
		}


		if(isset($item['mark'][3])):

		$s = count($item['mark'][3]);

		foreach($item['mark'][3] as $val):

		?>

		<blockquote>

                    <font style="font-size:12px;"><?php echo $val['mark_content']; if($val['type_id']){ echo "<font color='#FF0000'>（未到诊原因：" . $dao_false_arr[$val['type_id']] . "）</font>";} ?></font>

		<small><a href="###"><?php echo $val['r_admin_name'];?></a> <cite><?php echo date("m-d H:i", $val['mark_time']);?></cite><i>【<?php echo $s;?>】</i></small>

        </blockquote>

		<?php

		$s --;

		endforeach;

		endif;



		?>

    </div>

    </td>

	<td style="position:relative;font-size:12px;">

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
    <div class="coop" style="text-align:left;">
      <span isid="<?php echo $item['b_admin_id'];?>">操作人：<?php echo $item['black_admin'];?></span><br>
      <span>操作时间：<?php echo date('Y-m-d H:i',$item['b_add_time']);?></span>
    </div>
    <?php if(in_array(185,$admin_action)||$_COOKIE['l_admin_action'] == 'all'):?>
             <span id="black_<?php echo $item['order_id'];?>"><a class="btn btn-default black_back" pat_id="<?php echo $item['pat_id']?>" order_id="<?php echo $item['order_id']?>" >恢复</a></span>
    <?php endif;?>

	</td>

  </tr>





        <?php

   $i ++;
    }

  }else{
      echo "<tr><td colspan='12'>暂时没有相关数据哦！</td></tr>";
  }





 ?>

  </tbody>

  </table>

<?php echo $page; ?>



</div>

</div>

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

   <script src="static/vendor/layer/layer.js"></script>

    <script>
      /* ajax 获取回访备注内容 */

      var order_id = "<?php if(!empty($order_list)){foreach($order_list as $item){ echo $item['order_id'] . ",";}}?>";

      ajax_remark_list(order_id);

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

                //指定账户显示红色
                if(item.admin_id == 1195 || item.admin_id == 298){
                  str += "<blockquote><p style='font-size:12px;color:red;'>" + item.mark_content;
                }else{
                   str += "<blockquote><p style='font-size:12px;'>" + item.mark_content;
                }

                if(item.type_id > 0)

                {

                  str += "<font color='#FF0000' style='font-size:12px;'>（未到诊原因：" + dao_false_arr[item.type_id] + "）</font>";

                }

                str += "</p><small style='font-size:12px;'><a href=\"###\">";

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

                str += "><p style='font-size:12px;'>" + item.mark_content + "</p><small><a href=\"###\">";

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

      $(".remark").hover(

        function(){

            $(this).css({height:"auto", background:"#f7f7f7", "z-index":"999", "padding-bottom":"50px"});

        },

        function(){

           $(this).css({height:"70px", background:"none", "z-index":"1", "padding-bottom":"10px"});

        }

      );

      $(function(){

        $(".black_back").click(function(){

          var order_id=$(this).attr('order_id');
          var pat_id = $(this).attr('pat_id');

          layer.confirm('确定要恢复该黑名单到预约表吗？', {
            btn: ['确定','取消'] //按钮
          }, function(){

            $.ajax({
              type:'post',
              url:'?c=order&m=ajax_backBlack',
              data:'pat_id=' + pat_id + '&order_id=' + order_id,
              success:function(data){
                data = $.parseJSON(data);
                if (data.code == 1) {
                  layer.msg(data.msg, {icon: 6});
                  $('#black_'+order_id).parent().parent().remove();
                } else if (data.code == 2) {
                  layer.msg(data.msg, {icon: 5});
                } else if (data.code == 3) {
                  layer.msg(data.msg, {icon: 5});
                }
              },
              complete: function (XHR, TS)
              {
                XHR = null;
              }
            });
          });
        });
      })

      function open7(order_id)
      {
        var diag = new Dialog();
        diag.Width = 820;
        diag.Height = 400;
        diag.Title = "预约操作记录页";
        diag.URL = "?c=gonghai&m=order_detail&order_id="+order_id;
        diag.show();
      }

    </script>
    </body>
</html>
