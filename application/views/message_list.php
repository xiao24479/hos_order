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

<?php echo $top; ?>

<div id="container" class="row-fluid"> 

<?php echo $sider_menu; ?>



<div id="main-content"> 

    <!-- BEGIN PAGE CONTAINER-->

    <div class="container-fluid" style="position:relative; padding-top:10px;"> 



		<form action="" method="get" class="date_form order_form" id="order_list_form">

		<input type="hidden" value="order" name="c" />

		<input type="hidden" value="message_list" name="m" />

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

			<div class="row-form">

				<label class="select_label">医院</label>

				<select name="hos_id" id="hos_id" style="width:180px;">

					<option value=""><?php echo $this->lang->line('hospital_select'); ?></option>

					<?php foreach($hospital as $val):?><option value="<?php echo $val['hos_id']; ?>" <?php if($val['hos_id'] == $hos_id){echo " selected";}?>><?php echo $val['hos_name']; ?></option><?php endforeach;?>

				</select>
                 
                
			</div>
			<div class="row-form">
				<label class="select_label">选择地区</label>
				<select name="province" id="province" class="input-small m-wrap" style="width:130px;">
				   <option value="0"><?php echo $this->lang->line('please_select');?></option>
				   <?php foreach($province as $val){ ?>
				   <option value="<?php echo $val['region_id'];?>" <?php if($val['region_id'] == $pro){echo 'selected="selected"';}?> ><?php echo $val['region_name']; ?></option>
				   <?php } ?>
				</select>
				<select name="city" id="city" class="input-small m-wrap" style="width:88px;">
				   <option value="0"><?php echo $this->lang->line('please_select');?></option>
				</select>
				<select name="area" id="area" class="input-small m-wrap" style="width:88px;">
				   <option value="0"><?php echo $this->lang->line('please_select');?></option>
				</select>
			
			</div>

		</div>
        
        <div class="span3">

			<div class="row-form">

				<label class="select_label"><?php echo $this->lang->line('patient_name');?></label>

				<input type="text" value="<?php echo $p_n; ?>" class="input-medium" name="p_n"  />

			</div>
			<div class="row-form">
            
            <label class="select_label">科室</label>
 <select name="keshi_id" id="keshi_id" style="width:180px;">

					<option value=""><?php echo $this->lang->line('hospital_select'); ?></option>

					<?php foreach($keshi as $val):?><option value="<?php echo $val['keshi_id']; ?>" <?php if($val['keshi_id'] == $keshi_id){echo " selected";}?>><?php echo $val['keshi_name']; ?></option><?php endforeach;?>

				</select>

			</div>
			<div class="row-form">

				 

			</div>
		</div>



		<div class="span3">

			<div class="row-form">

				<label class="select_label"><?php echo $this->lang->line('patient_phone');?></label>

				<input type="text" value="<?php echo $p_p; ?>" class="input-medium" name="p_p"  />

			</div>
			<div class="row-form">
                     <label class="select_label">疾病</label>

				    <select name="jb_id" id="jb_id" style="width:180px;">

					<option value=""><?php echo $this->lang->line('hospital_select'); ?></option>

					<?php foreach($jibing as $val):?><option value="<?php echo $val['jb_id']; ?>" <?php if($val['jb_id'] == $jb_id){echo " selected";}?>><?php echo $val['jb_name']; ?></option><?php endforeach;?>

				</select>

			</div>
			<div class="row-form">
  
			</div>
		</div>


<div class="span3">

			<div class="row-form">

				
				<label class="select_label">处理状态<?php echo $handle?></label>

				  
				<select name="handle" style="width:130px;">
					<option value="">选择是否处理</option>
					<option value="1" <?php if(1 == $handle){echo " selected";}?>>未处理</option>
					<option value="2" <?php if(2 == $handle){echo " selected";}?>>已处理</option>

				</select>

			</div>
			<div class="row-form">

				

			</div>
			<div class="row-form">
 
			</div>
		</div>
        
        

		<div class="order_btn" style="left:1010px;top:102px;">

		   <button type="submit" class="btn btn-success"> 搜索 </button> 
           
          <input type="hidden" value="0" class="input-medium" name="excel_status" id="excel_status" />

		 <?php   if(isset($down_message)){?>
                   <button type="button" class="btn btn-success  btn_down_message">  下载EXCEL </button> 
           <?php }?>
           
		</div>

		</form>

	<div class="row-fluid">

		<div class="span12">

		<table width="100%" border="0" cellspacing="0" cellpadding="2" class="list_table">

		<thead>

		  <tr>

			<th width="30">序号</th>

			<th><?php echo $this->lang->line('patient_info'); ?></th>

			<th ><?php echo $this->lang->line('time'); ?></th>

			<th>医院科室信息</th>

			<th width="200">患者留言</th>
			<th width="200">咨询备注</th>
			
			<th>记录</th>
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
	    foreach($message_list as $item):
	
			if(!in_array('179',$l_admin_action)){
				$item['pat_phone']  =  $item['pat_phone'][0].$item['pat_phone'][1].$item['pat_phone'][2].'*****';
				$item['pat_phone1']  =  $item['pat_phone1'][0].$item['pat_phone1'][1].$item['pat_phone1'][2].'*****';
			} 
			
		  ?>

  <tr<?php if($i % 2){ echo " class='td1'";}?> style="height:90px;">

    <td><b><?php echo $now_page + $i + 1; ?></b></td>
	
	<td style="text-align:left;">

    <div id="pat_<?php echo $item['order_id']; ?>">
 
		姓名：<font color='#FF0000'><b id="pat_name_<?php echo $item['order_id']; ?>"><?php echo $item['pat_name']?></b></font>（<?php echo $item['pat_sex']; ?>、<?php echo $item['pat_age']?>岁）<br />

		<?php if($rank_type == 4 || $rank_type == 2 || $_COOKIE['l_admin_action'] == 'all'):?>

        电话：<font id="pat_phone_<?php echo $item['order_id']; ?>"><?php echo $item['pat_phone']; if(!empty($item['pat_phone1'])){echo "/" . $item['pat_phone1'];}?></font><br />

        <?php else:?>
        

        电话：<font id="pat_phone_<?php echo $item['order_id']; ?>"><?php echo substr($item['pat_phone'],0, -4) . "****"; if(!empty($item['pat_phone1'])){echo "/" . substr($item['pat_phone1'],0, -4) . "****";}?></font><br />

        <?php endif;?>


		地区：<?php

		  if($item['pat_province'] > 0){ echo $area[$item['pat_province']]['region_name'];}

		  if($item['pat_city'] > 0){ echo "、" . $area[$item['pat_city']]['region_name'];}

		  if($item['pat_area'] > 0){ echo "、" . $area[$item['pat_area']]['region_name'];}?><br />

		<?php

		  if(!empty($item['pat_qq'])){

			  echo $item['pat_qq'] . "(QQ)";

		  }
		?>

    </div>

    </td>

	<td style="text-align:left;">

		<?php echo $this->lang->line('order_addtime'); ?>：<font id="order_addtime_<?php echo $item['order_id']; ?>"><?php echo $item['order_addtime']; ?></font><br />

		<?php echo $this->lang->line('order_time'); ?>：<font id="order_time_<?php echo $item['order_id']; ?>"><?php echo $item['order_time']; ?></font> <font style="color:#F00; font-weight:bold;" id="order_time_duan_<?php echo $item['order_id']; ?>"></font><br />

		提交网址：<?php echo $item['from_value'];?>
	</td>

	<td style="text-align:left;"> 
	医院：<?php foreach($hospital as $val){ if(strcmp($item['hos_id'],$val['hos_id']) == 0){echo $val['hos_name'];break;} }?><br />
    科室：<?php foreach($keshi as $val){ if(strcmp($item['keshi_id'],$val['keshi_id']) == 0){echo $val['keshi_name'];;break;} }?><br />
	疾病:<?php $check_jb =0 ;foreach($jibing as $val){ if(strcmp($item['jb_parent_id'],$val['jb_id']) == 0){echo $val['jb_name'];$check_jb =1;break;} }if($check_jb ==0 ){echo '没有选择病种';}?> </td>

	<td style="position:relative;">
    <div class="remark" id="notice_<?php echo $item['order_id']; ?>">
    </div>
	</td>
	<td style="position:relative;">
    <div class="remark" id="visit_<?php echo $item['order_id']; ?>">
    </div>
	</td>
	<td id="action_<?php echo $item['order_id']; ?>">
		
		<?php if(empty($item['admin_name'])):?>
			<a href="#visit" role="button" class="btn btn-success" data-toggle="modal" onClick="ajax_remark(<?php echo $item['order_id']?>)"><?php echo $this->lang->line('visit'); ?></a>
		<?php else: echo $item['admin_name'].'已处理<br/>';?><?php endif;?>
		<button class="btn btn-primary" onclick="go_del('?c=show&m=message_del&order_id=<?php echo $item['order_id']; ?>')">删除</button>
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

<script language="javascript">

$(document).ready(function(e) {

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



  
	$(".btn_down_message").click(function(){
		$('#excel_status').val(1);
		$("#order_list_form").submit();
		$('#excel_status').val(0);
	});
 
  
	$("#hos_id").click(function(){
		 ajax_get_keshi($(this).val());
	});
 	$("#keshi_id").click(function(){
		 ajax_get_jibing($(this).val(),0,0);
	});
  
    /**下载excel判断**/
	$(".btn_down_message").click(function(){
		$('#excel_status').val(1);
		$("#order_list_form").submit();
		$('#excel_status').val(0);
	});
 
	/* ajax 获取回访备注内容 */

	var order_id = "<?php if(!empty($message_list)){foreach($message_list as $item){ echo $item['order_id'] . ",";}}?>";

	ajax_remark_list(order_id);




<?php if($pro > 0):?>
ajax_area('city', <?php echo $pro; ?>, <?php if(isset($city)){echo $city;}else{echo 0;}?>, 3);
<?php endif;?>

<?php if(isset($city)):?>
ajax_area('area', <?php echo $city; ?>, <?php if(isset($are)){echo $are;}else{echo 0;}?>, 3);
<?php endif;?>
});

function ajax_remark_list(order_id)

{

	$.ajax({

		type:'post',

		url:'?c=order&m=ajax_message_list',

		data:'order_id=' + order_id,

		success:function(data)

		{

			data = $.parseJSON(data);

			$.each(data, function(i, item){

					if($("#notice_" + item.order_id).html() == "...")

					{

						$("#notice_" + item.order_id).html("");

					}

					var str = "";

					str += "<blockquote";
					str += ' class="g"';
					str += "><p>" + item.mark_content + "</p><small><a href=\"###\">";
					
					if(item.mark_type == 0){
						str += "病人留言";
						str += "</a> <cite>" + item.mark_time + "</cite></small></blockquote>";
						$("#notice_" + item.order_id).html(str + $("#notice_" + item.order_id).html());
					}else{
						str += "咨询备注";
						str += "</a> <cite>" + item.mark_time + "</cite></small></blockquote>";
						$("#visit_" + item.order_id).html(str + $("#visit_" + item.order_id).html());
					}
			});

		},

		complete: function (XHR, TS)

		{

		   XHR = null;

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

function ajax_remark(order_id)
{

	$("#visit").children("btn").css("display", "none");

	$("#visit_order_id").val(order_id);

	$("#visit_remark").val("");
}
function visit_add()
{
	var order_id = $("#visit_order_id").val();

	var remark = $("#visit_remark").val();

	$.ajax({

		type:'post',

		url:'?c=order&m=mes_content_ajax',

		data:'order_id=' + order_id + '&remark=' + remark ,

		success:function(data)

		{

			$("#visit_" + order_id).html(data + $("#visit_" + order_id).html());
			$("#action_" + order_id).html('处理成功');
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

				$("#jb_id").html(data);

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



</script>

</body>

</html>