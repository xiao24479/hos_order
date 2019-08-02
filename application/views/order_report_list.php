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
/*.order_form{ height:160px}*/
.order_form{ height:80px}
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
    
    background-color:#00a195;
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


</style>

<script src="static/js/jquery.js"></script>
 
<script type="text/javascript" src="static/js/js_window/zDrag.js"></script>
<script type="text/javascript" src="static/js/js_window/zDialog.js"></script>
 

</head>



<body class="fixed-top" style="width:100%;margin:0 auto; ">
<?php echo $top; ?>

<div id="container" class="row-fluid" > 

<?php echo $sider_menu; ?>



    <div id="main-content" style="margin-left:180px;"> 

         <!-- BEGIN PAGE CONTAINER-->

         <div class="container-fluid" style="position:relative; padding-top:0px;margin-left: 0px;background-color: #f7f7f7;"> 
     
             
             <div style=" background-color: #f7f7f7;height:100px;margin-top:-10px;margin-left:-20px;">
              <form action="" method="get" class="date_form order_form" id="order_list_form"style="width:100%;">

                <input type="hidden" value="order" name="c" />
                
                <input type="hidden" value="order_report_list" name="m" />


                   <div id="dy_search" style=" position:fixed;width:100%;height:100px;;padding-top:10px;z-index: 3;"> 
                       
                       <div style="position:fixed;padding-top:30px;width:100%;height: 50px;line-height: 30px;font-family: '微软雅黑';color: #808080;font-size: 12px;background-color: #f7f7f7;">
                         年份:
                         <select name="year" id="year" style="width:90px;">
                         <?php 
						 $year_current =1997;
						
						 while($year_current <= date('Y',time())){?>
                         <option  
                         <?php 
							  if($year_current == $year){
								   echo " selected ";
							  }?>
							  value="<?php echo $year_current;?>"><?php echo $year_current;?>年</option>
						 <?php $year_current++; }?>
                          </select>
                        月份:
                          <select name="moth" id="moth" style="width:70px;">
                               <?php 
								 $moth_current =1;
								 while($moth_current <= 12 ){?>
									  <option <?php 
									      if($moth_current == $moth) {
										     echo 'selected';
										  }?> value="<?php echo $moth_current;?>"><?php echo $moth_current;?>月</option>
								 <?php $moth_current++; }?>
                                 
                          </select>
                        
                           
                           <span style="margin-left: 10px;"> 咨询员：<input type="text" class="input_search" value="<?php echo $a_i; ?>" name="a_i" style="margin-top: 7px;width:80px;height:16px; font-size:12px;outline:none;"/></span>
                       
                            <?php echo $this->lang->line('order_keshi');?>
                            <select name="hos_id" id="hos_id" style="width:180px;">
                    
                                <option value=""><?php echo $this->lang->line('hospital_select'); ?></option>
                    
                                <?php foreach($hospital as $val):?><option value="<?php echo $val['hos_id']; ?>" <?php if($val['hos_id'] == $hos_id){echo " selected";}?>><?php echo $val['hos_name']; ?></option><?php endforeach;?>
                    
                            </select>
                    
                            <select name="keshi_id" id="keshi_id" style="width:180px;">
                    
                                <option value=""><?php echo $this->lang->line('keshi_select'); ?></option>
                    
                            </select>
         
            
                           <span style="margin-left: 10px;"> <input type="image" class="input_search" src="static/img/dy_search.png" style="vertical-align:middle;height:30px; cursor:pointer;" onclick="this.form.submit();"/>
                           
                                 <input type="hidden" value="0" class="input-medium" name="excel_status" id="excel_status" />
            
					 <?php   
					 if(isset($order_report_list)){?>
                                         <button type="button" class="btn btn-success  btn_order_report_list">  下载EXCEL </button> 
                       <?php }?>
                           </span>
 
                       </div>
         
 				</div>
 
</form>
 
	  <div  style="border:0px;"  >

              <div   style="border:0px;">
                    
                    <table width="100%" border="0px" cellspacing="0" cellpadding="2" class="list_table" style="margin-top:1px;font-size:10px !important;">
                       <tr>
                       <th colspan="33" ><?php echo $field_name;?></th>
                        </tr>
                
                     <tbody>
                      <tr>
                        <td class="red" ></td>
                       <?php foreach($fields as $key=>$val){?>
                        <td colspan="2"><?php echo  $key;?></td>
                      <?php } ?>
                        <td colspan="2">汇总</td>
                        <td>到诊率</td>
                      </tr>
                      
                      <tr>
                        <td class="red" style="width:80px">咨询员</td>
                       <?php foreach($fields as $key=>$val){?>
                        <td>约</td>
                        <td>到</td>
                      <?php } ?>
                        <td>约</td>
                        <td>到</td>
                        <td></td>
                      </tr>
                      
             
                  <?php
                  if(!empty($order_list)){
					  $zixun_arr_day_order_addtime = array();// 每天的预约总计
					   $zixun_arr_day_come_time = array();// 每天的到诊总计
				      $zixun_arr_total_count_all_order_addtime =0 ;// 一个月所有人预约的总和
						$zixun_arr_total_count_all_come_time =0 ;// 一个月所有人到诊的总和
	
                      foreach($order_list as $item){?>
		
                         <tr><td><span style="font-weight:bold"><?php echo  $zixun_arr_name[$item['admin_id']];?></span></td>
						<?php  
								    $one_person_order_addtime =0 ;//一个人一个月 预约的总和
								    $one_person_come_time =0 ;//一个人一个月 到诊 的总和
									$one_person_order_time =0 ;//一个人一个月 预到 的总和
									foreach($item['order_addtime'] as $item_key=>$item_val)
									{			
										if(empty( $item_val )){
											  $item_val  = 0; 
										}
										$one_person_order_addtime  =$one_person_order_addtime+$item_val;//一个人一个月的总和
										if(!isset($item['order_time'][$item_key])){
											$item['order_time'][$item_key] = 0;
										}
										 $one_person_order_time =  $one_person_order_time+$item['order_time'][$item_key];
										 
										 
										if(isset($zixun_arr_day_order_addtime[$item_key])){//每人每天的统计
										   $zixun_arr_day_order_addtime[$item_key]	 = $zixun_arr_day_order_addtime[$item_key] + $item_val;
										}else{
										   $zixun_arr_day_order_addtime[$item_key]	 = $item_val;
										} ?>
										<td>
										<?php 
										$date_bol = 0;
										if($year <= date('Y',time()) && $moth <= date('m',time()) && ($item_key+1) <= date('d',time()) ){
											$date_bol = 1;
										}
										if(empty($item_val)  && $date_bol  == 1){?><div style="color:#F00"><?php } ?> 
										<?php echo $item_val;?>
                                        <?php if(empty($item_val)  && $date_bol  == 1){?></div><?php } ?> 
                                        </td>
                                        <td>
										<?php if(empty($item['come_time'][$item_key])   && $date_bol  == 1){?><div style="color:#F00"><?php } ?> 
										<?php echo $item['come_time'][$item_key];?>
                                            <?php $one_person_come_time  =$one_person_come_time+$item['come_time'][$item_key];//一个人一个月的总和
											
										//每人每天预约的统计
										if(isset($zixun_arr_day_order_time[$item_key])){
										   $zixun_arr_day_order_time[$item_key]	 = $zixun_arr_day_come_time[$item_key] +$item['come_time'][$item_key];
										}else{
										   $zixun_arr_day_order_time[$item_key]	 =$item['come_time'][$item_key];
										} 
										?>
                                         <?php if(empty($item['come_time'][$item_key])  && $date_bol  == 1){?></div><?php } ?> 
                                </td>
						  
							 <?php  }
							  $zixun_arr_total_count_all_order_addtime  = $zixun_arr_total_count_all_order_addtime+$one_person_order_addtime;
							  $zixun_arr_total_count_all_come_time  = $zixun_arr_total_count_all_come_time+$one_person_come_time;
							  ?>
								 <td  ><?php echo $one_person_order_addtime;?></td>
                                  <td  ><?php echo $one_person_come_time;?></td>
                                   <td  ><?php 
								   if($one_person_order_time > 0){
									   echo round(($one_person_come_time/$one_person_order_time)*100).'%';
								   }else{
									   echo '0%';   
								   }
								   ?></td>
		 	
					 </tr>     
					 <?php  } ?>
                     <tr>
                                      <td>总计</td> 
                     <?php    
							 foreach($zixun_arr_day_order_addtime as $zixun_arr_day_order_addtime_key=>$zixun_arr_day_order_addtime_val)
							{   
								 ?>
									 <td><?php echo $zixun_arr_day_order_addtime_val;?></td>
                                      <td><?php echo $zixun_arr_day_come_time[$zixun_arr_day_order_addtime_key];?></td>
								 <?php }
							 ?>
							
							  <td> <?php echo   $zixun_arr_total_count_all_order_addtime;?></td> 
                               <td> <?php echo   $zixun_arr_total_count_all_come_time;?></td> 
                                 <td> <?php echo   $zixun_arr_total_count_all_order_addtime+$zixun_arr_total_count_all_come_time;?></td> 
                            </tr>
								   
         <?php    }else{
                      echo "<tr><td colspan='33'>很抱歉，亲，查找不到相关数据哦！</td></tr>";
                  }
                 ?>
                  </tbody>
                
                  </table>


<div style="margin-bottom:30px;"></div>
 
</div>

</div>

</div>

</div>
<div id="fade" class="black_overlay">
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
 
 
<script language="javascript">
   /**下载excel判断**/
	$(".btn_order_report_list").click(function(){
		$('#excel_status').val(1);
		$("#order_list_form").submit();
		$('#excel_status').val(0);
	});
	
	
		$("#hos_id").change(function(){

		var hos_id = $(this).val();

		ajax_get_keshi(hos_id, 0);

	});
 

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

</script>
    </body>
</html>
