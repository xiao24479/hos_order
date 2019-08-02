<!DOCTYPE html>

<!--

To change this license header, choose License Headers in Project Properties.

To change this template file, choose Tools | Templates

and open the template in the editor.

-->

<html>

    <head>

        <meta charset="UTF-8">

        <title></title>

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



#sidebar{ z-index:999;margin-top: 60px;}



.sidebar-scroll{z-index:999;}



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

z-index:1;

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

    height:30px;

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

}

.remark blockquote.g, .sms_content blockquote {

    border-left: 0px solid #ffffff;

}

.remark blockquote.r {

    border-left: 0px solid #000000;

}

.remark blockquote.doc {

    border-left: 0px solid #00f;

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

</style>

<script type="text/javascript" src="static/js/js_window/zDrag.js"></script>

<script type="text/javascript" src="static/js/js_window/zDialog.js"></script>

<script>

function open14()

{

	var diag = new Dialog();

	diag.Width = 1200;

	diag.Height = 600;

	diag.Title = "确认发送";

	diag.URL = "";

	diag.show();

}







</script>

    </head>

    

    <body style="margin:0 auto;background-color: #f7f7f7;">

        <?php echo $top;?>

        <?php echo $sider_menu;?>

       <div id="content" style="position:absolute; width:100%; height:100%; margin-top:60px;padding-left:190px;"> 

         

         

           <form action="?c=order&m=sms_send_all" method="post">

 <!--第一列选项开始-->

              <div class="span5" style="margin-left:20px;width:350px; margin-top: 15px;">



    <div class="row-form">



		<label class="select_label">预到时间</label>



		<input type="text" value="<?php echo $start; ?> - <?php echo $end; ?>" style="width:270px;" class="input-block-level" name="date" id="inputDate" />



    </div>



    <div class="date_div">



    <div class="divdate"></div>



    <div class="anniu"><a href="javascript:;" class="btn btn-inverse guanbi"> 关闭 </a><br /><a href="javascript:;" class="btn btn-inverse today"> 今天 </a><br /><a href="javascript:;" class="btn btn-inverse week"> 一周 </a><br /><a href="javascript:;" class="btn btn-inverse month"> 一月 </a><br /><a href="javascript:;" class="btn btn-inverse year"> 一年 </a></div>



    </div>

                  

                  <div class="row-form">



		<label class="select_label"><?php echo $this->lang->line('type_name');?></label>



		<select name="o_t" style="width:165px;">



			<option value="" selected ><?php echo $this->lang->line('please_select'); ?></option>



			<?php foreach($type_list as $val):?><option value="<?php echo $val['type_id']; ?>" <?php if($val['type_id'] == $p_jb){echo " selected";}?>><?php echo $val['type_name']; ?></option><?php endforeach;?>



		</select>



	</div>

                  

</div>

           <!--第一列选项结束-->

           <!--第二列选项开始-->

           <div class="span5" style="margin-left:10px; margin-top: 15px;">

               

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

	<div class="row-form">



		<label class="select_label">大病种</label>



		<select name="p_jb" id="p_jb" style="width:165px;">



			<option value=""><?php echo $this->lang->line('jb_parent_select'); ?></option>



			<?php foreach($jibing_parent as $key=>$val):?><option value="<?php echo $val['jb_id']; ?>" <?php if($val['jb_id'] == $p_jb){ echo "selected";}?>><?php echo $val['jb_name']; ?></option><?php endforeach;?>



		</select>

                <select name="jb" id="jb" style="width:165px;">



			<option value=""><?php echo $this->lang->line('jb_child_select'); ?></option>



		</select>



	</div>

              

           </div>







<div class="span3" style="margin-left:10px; margin-top: 15px;">



<div class="row-form" style="padding-top:0px;">



		<label class="select_label"><?php echo $this->lang->line('status');?></label>



		<select name="s" id="status" style="width:165px;">



			<option value=""><?php echo $this->lang->line('please_select'); ?></option>



			<?php foreach($this->lang->line('order_status') as $key=>$val):?><option value="<?php echo $key; ?>" <?php if($key == $s){echo " selected";}?>><?php echo $val; ?></option><?php endforeach;?>



		</select>



	</div>



	<div class="row-form" style="padding-top:0px;">

   <input type="image" src="static/img/gaoji.jpg" style="vertical-align:middle;height:30px; cursor:pointer;" onclick="this.form.submit();"/>

	 

        </div>

    



</div>	

           <div class="span12" style="margin-bottom:10px;"> 

               <hr style="width:1180px;border:1px solid #bbb;margin-left: -30px;">

               <span id="send_sms" style="font-size:16px; color:#00a186;cursor:pointer;margin-bottom:10px;"><button type="button" style="background-color:#00a186;border:0px;color:#fff;">群发短信</button><br/><font style="color:red;font-size:12px;">(本功能为短信群发功能，短信模板中请不要带{username}等字符，短信内容应为纯文字的通知内容！短信结尾必须有类似【深圳仁爱医院】的签名！点击全选，可以勾选全部。)</font></span></div>      

	









       

    



           



 



</form>

               

         

           <!-- 高级搜索表单结束-->

           

           <!-- 预约列表开始-->

           

               

           <div id="dy_table" style="margin-top:50px;">         

               

               

         

        

        

          <div class="row-fluid" >



		<div style="width:1200px;">



                    <table width="98%" border="0" cellspacing="0" cellpadding="2" class="list_table">



                        <thead >



  <tr>

      <th width="30"><span id="choose" style="cursor:pointer;">全选</span></th>

	<th width="30">序号</th>



    <th width="70"><?php echo $this->lang->line('order_no'); ?></th>



	<th width="70"><?php echo $this->lang->line('patient_info'); ?></th>



	<th width="200"><?php echo $this->lang->line('time'); ?></th>



	<th width="60">性质</th>



	<th width="70"><?php echo $this->lang->line('order_keshi'); ?></th>



	<th width="80"><?php echo $this->lang->line('patient_jibing'); ?></th>



   

    <th width="160"><?php echo $this->lang->line('notice'); ?></th>



	



  </tr>



  </thead>



  <tbody>



  <?php

  if(!empty($order_list)){

        $i = 0;

    foreach($order_list as $item){

        

        ?>

      <tr<?php if($i % 2){ echo " class='td1'";}?> style="height:30px;">

          <td><input name="phone[]" type="checkbox" value="<?=$item['pat_phone']?>" class="check_input" /></td>

    <td><b style="color:#808080;font-size:16px;"><?php echo $now_page + $i + 1; ?></b></td>



    <td>



       <a href="#" style="color:#333333;" id="order_no_<?php echo $item['order_id']; ?>"><?php echo $item['order_no']; ?></a>&nbsp;



	   <?php if($item['is_first']){ echo "初诊";}else{ echo "<font color='#FF0000'>复诊</font>";}?>&nbsp;



       <a <?php if($item['is_come'] > 0){ echo '<i id="status_' . $item['order_id'] . '" class="icon-ok" style="color:#fb9900; font-size: large;"></i>';}else{ echo '<i id="status_' . $item['order_id'] . '" class="icon-remove" style="color:#1f708b; font-size: large;"></i>'; }?></a>

       

    </td>



	<td style="text-align:left;">



    <div id="pat_<?php echo $item['order_id']; ?>">



        姓名：<?php if(!empty($item['zampo'])):?><del><?php endif;?><font style="color:#ff6060;font-size:14px;" id="pat_name_<?php echo $item['order_id']; ?>"><?php echo $item['pat_name']?></font>&nbsp;

		

            <br/>电话：<font id="pat_phone_<?php echo $item['order_id']; ?>"><?php echo $item['pat_phone']; if(!empty($item['pat_phone1'])){echo "/" . $item['pat_phone1'];}?></font><br />

		

    </div>



    </td>



	<td style="text-align:left;">



            <font style="color:#00a186;">登记：</font><font id="order_addtime_<?php echo $item['order_id']; ?>"><?php echo $item['order_addtime']; ?></font>



		<font style="color:#00a186;">预到：</font><font id="order_time_<?php echo $item['order_id']; ?>"><?php echo $item['order_time']; ?></font> <font style="color:#ff6060; font-weight:bold;" id="order_time_duan_<?php echo $item['order_id']; ?>"><?php if($item['order_time_duan']){ echo $item['order_time_duan'];}?></font>



                <br/><font style="color:#00a186;">实到：</font><span id="come_time_<?php echo $item['order_id']; ?>"><?php if($item['come_time'] > 0){ echo date("Y-m-d H:i", $item['come_time']);}?></span>

</td>



	<td>



	<?php



   

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

        

	<td style="position:relative;">



    <div class="remark" id="notice_<?php echo $item['order_id']; ?>">



		

    </div>



	</td>



	

  </tr>

  

  



  

        

        <?php

   

   $i ++;

       

    }

  }else{ 

      echo "<tr><td colspan='9'>很抱歉，亲，查找不到相关数据哦！</td></tr>";

  }

?>







	



    

    



  <div id="sms_send"  style="position: fixed;z-index:99;left:30%;top:10%;background-color:#bbb;height:400px;width:500px;display:none;">

      <div style="width:100%;height:40px;background-color: #eee;"><span style="font-size:18px;line-height:40px;color:#00a186;margin-left: 10px;">短信群发</span><img src="static/img/close.png" style="float:right;line-height:40px;height: 30px;margin-top: 5px;margin-right:5px;" id="sms_close"/></div>

      <div style="width:300px;height:358px;background-color:#bbb;float:left;padding-left: 10px;">

          <form action="?c=order&m=ms_all_sent" method="post">

          <p style="color:#00a186;"> 选择短信模板：</p>

       

		<select name="hos_id" id="mb_hos" style="width:180px;">



			<option value=""><?php echo $this->lang->line('hospital_select'); ?></option>



			<?php foreach($hospital as $val):

                            $a=array(1,2,4,5,6,3,9,11,12,13,14,16,20,22,23,24,25,32,34,35,52);

                            if(in_array($val['hos_id'],$a)){?><option value="<?php echo $val['hos_id']; ?>"><?php echo $val['hos_name']; ?></option><?php }endforeach;?>



		</select>



		<select name="keshi_id" id="mb_keshi" style="width:130px;">



			<option value=""><?php echo $this->lang->line('keshi_select'); ?></option>



		</select>

          <select name="sms_mb" id="mb_sms" style="width:130px;">



			<option value="">请选择模板</option>



          </select><br/>

          <p style="color:#00a186;">短信内容（<span style="color:red;">可手动填写,注意短信末尾必须填写自己所属医院的签名。如：【深圳仁爱医院】否则无法成功发送</span>）</p>

          <textarea  rows="6" style="width:90%;" value="" id="ms_content" name="ms_content">

         

          </textarea>

          <input type="submit" value="提交" id="ms_submit" style="background-color:#00a186;border:0px;color:#fff;"/>

           

      </div>

      <div style="width:150px;height:358px;background-color:#bbb;float:left;margin-left: 20px;">

          <p style="color:#00a186;">群发号码：</p>

           

          <textarea size="10" style="width:90%;height:300px;" id="ms_list"></textarea>

          

          <input type="hidden" id="sms_list" value="" name="ms_list"/>

          

      </div>	

        

	</div>



    



    </form>



    

        



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

      

    //以下是短信群发的相关ajax方法

    //验证相关信息是否为空

    $("#ms_submit").click(function(){

        var mb_hos=$("#mb_hos").val();

        var mb_keshi=$("#mb_keshi").val();

         var ms_list=$("#sms_list").val(); 

          var ms_content=$("#ms_content").val();

          var hos_id=$("#mb_hos").val();

          var keshi_id=$("#mb_keshi").val();

        

        if(mb_hos==""){

           alert('医院不能为空！');

           return false;

        }else if(mb_keshi==""||mb_keshi==0){

           alert('科室不能为空！'); 

           return false;

        }else if(ms_content==""||ms_content==0){

           alert('短信内容不能为空！');

           return false;

        }else if(ms_list==""||ms_list==null){

            

             alert('接收号码不能为空！');

             return false; 

        }

        $("#ms_submit").attr('disabled',true);

         $("#ms_submit").after("<font style='color:red;font-size:18px;margin-left:20px;' class='sms_tip'>短信发送中，请耐心等候···</font>");

          $.ajax({

              type:'post',

              url:'?c=order&m=ms_all_sent',

              data:'ms_list='+ms_list+'&ms_content='+ms_content+'&hos_id='+hos_id+'&keshi_id='+keshi_id,

              success:function(data){

                  $(".sms_tip").remove();

                  alert(data);

                  

                 $("#sms_send").hide();

                 window.location.reload();

              },

              complete: function (XHR, TS)



				{



				   XHR = null;



				}

          });

          

        

    });

    

    

      $("#mb_keshi").click(function(){

        sms_themes_ajax(); 

          

      });

 

      $("#mb_sms").change(function(){

         

          var themes_id=$(this).val();

          $.ajax({

             type:'post',

             url :'?c=order&m=ms_content_ajax',

             data:'themes_id='+themes_id,

             success:function(data)



				{

                                   



					$("#ms_content").val(data);



				},



				complete: function (XHR, TS)



				{



				   XHR = null;



				}

              

              

              

          });

          

          

      });

    $("#send_sms").click(function(){

        var str='';

        $("input[name='phone[]']:checkbox").each(function(){

        if($(this).attr("checked")){

            var va=$(this).val();

             if(va != null && va != ''){

				if($("#ms_list").val() == '' ){

					$("#ms_list").append(va);

				}else{

					 $("#ms_list").append("\r\n"+va);

				}

				str+=$(this).val()+";";

			}

         }

  });

        

        

        $("#sms_send").show();

        $("#sms_list").val(str);

      

     });  

           

		   

	 $('#ms_list').bind('input propertychange', function() {

	    $("#sms_list").val(ReplaceSeperator($("#ms_list").val())+";"); 

     });

     function ReplaceSeperator(mobiles) {

		var i;

		var result = "";

		var c;

		for (i = 0; i < mobiles.length; i++) {

			c = mobiles.substr(i, 1);

			if (c == "," || c == "，" || c == "\n")

				result = result + ";";

			else if (c != "\r")

				result = result + c;

		}

		return result;

	}

	

	

    $("#sms_close").click(function(){

        $("#ms_list option").remove();

        $("#sms_send").hide();

        

    });

    

    $("#choose").click(function(){

            

           

            if($(".check_input").attr("checked")){

                $(".check_input").removeAttr("checked");

                $("#choose").html("全选");

            }else{

                 $(".check_input").attr("checked","true");

                 $("#choose").html("取消");

            }

     

            

       });

        





	$(".list_table tr").hover(



	  function(){



	    $(this).addClass("over_list");



	  },



	  function(){



	    $(this).removeClass("over_list");



	  }



	);

	

	

$(".remark").css({height:"30px", background:"none", "z-index":"1", "padding-top":"2px"});

	$(".remark").hover(



	  function(){



	      $(this).css({height:"auto", background:"#ccc", "z-index":"999", "padding-bottom":"10px"});



	  },



	  function(){



	     $(this).css({height:"30px", background:"none", "z-index":"1", "padding-bottom":"10px"});



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

                  $('.date_div .datepicker').css({"width":"420px",'height':'160px','background':'black'});

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

        $("#mb_hos").change(function(){



		var hos_id = $(this).val();



		ajax_mb_keshi(hos_id, 0);



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



function sms_themes_ajax()

{

	var hos_id = $("#mb_hos").val();

	var keshi_id = $("#mb_keshi").val();

	

	if(hos_id > 0 || keshi_id > 0)

	{

	        

		$.ajax({

			type:'post',

			url:'?c=order&m=sms_themes_ajax',

			data:'hos_id=' + hos_id + '&keshi_id=' + keshi_id,

			success:function(data)

			{

				$("#mb_sms").html(data);

			},

			complete: function (XHR, TS)

			{

			   XHR = null;

			   

			}

		});

	}

}



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



						str += "<font color='#FF0000' style='font-size:12px;'>（未到诊原因：" + dao_false_arr[item.type_id] + "）</font>";



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



function ajax_get_docter(hos_id,keshi_id){

    	

        

        

        $.ajax({



		type:'post',



		url:'?c=order&m=ajax_get_docter',



		data:'keshi_id=' + keshi_id + '&hos_id=' + hos_id ,



		success:function(data)



		{



		$("#dao_doctor_name").html(data);	



			



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

function ajax_mb_keshi(hos_id, check_id)



{



	$.ajax({



		type:'post',



		url:'?c=order&m=keshi_list_ajax',



		data:'hos_id=' + hos_id + '&check_id=' + check_id,



		success:function(data)



		{



			$("#mb_keshi").html(data);



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

	

	var date_lx = $("#nextdate").val('');

	var datehour = $("#datehour").val('');



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



function visit_add()



{



	var order_id = $("#visit_order_id").val();



	var false_id = $("#false_id").val();



	var remark = $("#visit_remark").val();

	

	var zt_id = $("#zt_id").val();

	

	var lx_id = $("#lx_id").val();

	

	var jg_id = $("#jg_id").val();

	

	var ls_id = $("#ls_id").val();

	

	var date_lx = $("#nextdate").val();

	var datehour = $("#datehour").val();

	$.ajax({



		type:'post',



		url:'?c=order&m=order_update_ajax',



		data:'order_id=' + order_id + '&false_id=' + false_id + '&zt_id=' + zt_id + '&lx_id=' + lx_id + '&jg_id=' + jg_id + '&ls_id=' + ls_id + '&date_lx=' + date_lx + '&remark=' + remark + '&datehour=' + datehour + '&type=visit',



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



				$("#status_" + order_id).removeClass("icon-remove");



				$("#status_" + order_id).addClass("icon-ok");



				$("#status_" + order_id).css("color", "#fb9900");



			}



		},



		complete: function (XHR, TS)



		{



		   XHR = null;



		}



	});



}

//导医来院添加



function daoyi_add()



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



				$("#status_" + order_id).css("color", "#Fb9900");



			}



		},



		complete: function (XHR, TS)



		{



		   XHR = null;



		}



	});



}

//导医来院结束

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



			if(classname == 'icon-ok')



			{



				$("#status_" + order_id).removeClass("icon-ok");



				$("#status_" + order_id).addClass("icon-remove");



				$("#status_" + order_id).css("color", "#1f708b");



			}



			else



			{



				$("#status_" + order_id).addClass("icon-ok");



				$("#status_" + order_id).removeClass("icon-remove");



				$("#status_" + order_id).css("color", "#Fb9900");



				$("#come_time_" + order_id).html(data);



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

</script>

    </body>

</html>

