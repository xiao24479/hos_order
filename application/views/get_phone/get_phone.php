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
<style>
   
    .mark_phone{
        position:absolute;
        height:60px;
        overflow:hidden;
        margin-bottom:10px;
    }
    .mark_phone blockquote{
        border-left:1px solid #00a186;
        
    }
   .date_div{ position:absolute; top:80px; left:30px; z-index:1000;}

   .anniu{ display:none;}
    
</style>
</head>

<body class="fixed-top">
   <?php echo $top; ?>
   <div id="container" class="row-fluid">
   <?php echo $sider_menu; ?>
   <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <?php echo $themes_color_select; ?>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
              <div class="row-fluid">
			    <div class="span12">
				  <div class="widget purple">
<!--                            <div class="widget-title">
                                <h4><i class="icon-reorder"></i> <?php echo $this->lang->line('content_table'); ?></h4>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <a href="javascript:;" class="icon-remove"></a>
                            </span>
                            </div>-->
<div class="widget-body">
	<div class="clearfix">
<!--		 <div class="btn-group">
			 <button id="editable-sample_new" class="btn green" onClick="go_url('?c=phone&m=get_phone')">
				 刷 新 
			 </button>
                      
                     
		 </div>-->

            <div style="display:none;" >距离下次刷新还剩下：&nbsp;<span id="time_show" style="color:red;font-size:16px;"></span>&nbsp;&nbsp;秒</div>
	 </div>
    <div class="space15">
        <form class="form-search" action="?c=phone&m=index" method="post">
  日  期：<input type="text" value="<?php echo $start; ?> - <?php echo $end; ?>" style="width:250px; " class="input-medium search-query" name="date" id="inputDate" />
  手机号：<input type="text" class="input-medium search-query" value="<?php echo isset($phone)?$phone:'';?>" name="phone">
  
  备  注：<input type="text" class="input-medium search-query" name="mark_content">
  咨询员：<input type="text" class="input-medium search-query" name="zx_name">
  <button type="submit" class="btn btn-danger">搜 索</button>
</form>
</div>
     <div class="date_div">

    <div class="divdate"></div>

    <div class="anniu"><a href="javascript:;" class="btn btn-inverse guanbi"> 关闭 </a><br /><a href="javascript:;" class="btn btn-inverse today"> 今天 </a><br /><a href="javascript:;" class="btn btn-inverse week"> 一周 </a><br /><a href="javascript:;" class="btn btn-inverse month"> 一月 </a><br /><a href="javascript:;" class="btn btn-inverse year"> 一年 </a></div>

    </div>   
        
    </div>
<table class="table table-hover">
 <thead>
 <tr>
	 <th width="60">编号</th>
	 <th width="120">时间</th>
         <th width="120">手机号/型号</th>
	 <th width="100">号码归属地</th>
	 <th width="100">IP地址</th>
         <th >网址来源</th>
         <th width='200'>备注</th>
         <th width='60'>操作</th>
         <th width='100'>是否预约</th>
 </tr>
 </thead>
 <tbody>
       <?php 
       $i=$per_page+1;
       foreach($phone_list as $val){
           if($val['status']==1){
               $checked=" checked";
           }else{
               $checked=" ";
           }
        echo "<tr><td>".$i."</td><td>".date("Y-m-d H:i:s",$val['time']+900)."</td>"
                . "<td id='phone_".$val['phone_id']."'>电话：".substr($val['phone'], 0,11)."</td><td>".$val['ss']."</td><td>".$val['ip_addr']."</td>"
                . "<td>".$val['keywords']."</td><td class='mark_phone' id='mark_".$val['phone_id']."'></td><td><a href='#mark' data-toggle='modal' onClick='phone_mark(".$val['phone_id'].")' class='btn btn-info'>备注</a></td><td><input type='checkbox' value='1' ".$checked." onclick='change_status(".$val['phone_id'].")' id='status_".$val['phone_id']."' >已预约<font color='red'>(已预约)</font></td></tr>";   
        $i++;
       }
       
       
       
       echo "<tr ><td colspan='8' style='text-align:center;'>".$page."</td></tr>";
       ?>
     

     
 </tbody>
</table>

         
         
         
         <div id="mark" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style='width:400px;'>
             <div class="modal-header">

			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

			<h3 id="myModalLabel">备注</h3>

		</div>
             <div class='modal-body'>
                 <div class="control-group">

				<label class="control-label">手机号码：</label>

				<div class="controls" id='phone_name'>

					

				</div>

			</div>
                  <div class="control-group">

				<label class="control-label">备注信息：</label>

				<div class="controls">

					<textarea class="input-xxlarge " rows="5" name="phone_mark" id="phone_mark" style="width:300px;"></textarea>

				</div>

			</div>
                 
             </div>
             <div class='modal-footer'>
                 <input type="hidden" name="mark_phone_id" id="mark_phone_id" value=""/> 
                 <a role='button' class='btn btn-primary ' data-dismiss="modal" aria-hidden="true" onClick="mark_add();">提交</a><button  class="btn btn-primary " data-dismiss="modal" aria-hidden="true">取消</button>
                 
             </div>
             
             
             
         </div>

</div>
			  </div>
			  </div>
            </div>
            <!-- END PAGE CONTENT-->         
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
</div>
   <script src="static/js/jquery-1.8.3.min.js"></script>
   <script src="static/js/jquery.nicescroll.js" type="text/javascript"></script>
   <script src="static/js/bootstrap.min.js"></script>
   <script type="text/javascript" src="static/js/jquery.slimscroll.min.js"></script>
   <script src="static/js/datepicker/js/datepicker.js"></script>
   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="static/js/excanvas.js"></script>
   <script src="static/js/respond.js"></script>
   <![endif]-->
   <script src="static/js/common-scripts.js"></script>
   <script>
       
      
       
       
      
       $(document).ready(function(){
           
           
           
           
           timer();
           
           
           $(".mark_phone").hover(

	  function(){

	      $(this).css({height:"auto", background:"#f7f7f7", "z-index":"999", "padding-bottom":"10px"});

	  },

	  function(){

	     $(this).css({height:"50px", background:"none", "z-index":"1", "padding-bottom":"10px"});

	  }

	);
           
           
           
          var phone_id="<?php if(!empty($phone_list)){foreach($phone_list as $val){echo $val['phone_id'].",";}}?>";
           
          mark_list_ajax(phone_id);
          
          
       });
       
       //日期js代码开始
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
                 $("#gaoji").hide();
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
        //日期js代码结束
      function timer(){
	window.setInterval(function(){
         var d=new Date();
         var init_time=parseInt(d.getMinutes());
	if(init_time%2==0){
            update_phone();
        }
	
	$('#time_show').html(60-init_time);
	
	}, 50000);
} 
    function update_phone(){
        
        window.location.href='?c=phone&m=get_phone';
        
    }   
       
   function phone_mark(phone_id){

      var a=$("#phone_"+phone_id).html().substr(3,11);
      var phone_html="<font style='color:red;'>"+a+"</font>"
       $("#phone_name").html(phone_html);
       $("#mark_phone_id").val(phone_id);
       
   }
   function mark_add(){
       var phone_id=$("#mark_phone_id").val();
       var mark_content=$("#phone_mark").val(); 
       $.ajax({
           type:'post',
           url:'?c=phone&m=mark_add_ajax',
           data:'phone_id='+phone_id+'&mark_content='+mark_content,
           success:function(data){
               $("#mark_"+phone_id).html(data+$("#mark_"+phone_id).html());
           },
           complete : function (XHR, TS){

		   XHR = null;

		}
       });
   }
   
   function mark_list_ajax(phone_id){
       
       $.ajax({
           type:'post',
           url:'?c=phone&m=mark_list_ajax',
           data:'phone_id='+phone_id,
           success:function(data){
               data = $.parseJSON(data);
               
                        $.each(data,function(i,item){
                            var str="<blockquote style=''><p style='color:red;font-size:14px;width:180px;'>"+item.mark_content+"</p><small>"+item.admin_name+"&nbsp;"+item.mark_time+"</small></blockquote>";
                            
                       $("#mark_"+item.phone_id).html(str+$("#mark_"+item.phone_id).html());
                  
    
    });
               
           }
           
           
       });
       
   }
   
   function change_status(phone_id){
   var status=0;
   if($("#status_"+phone_id).attr('checked')){
       status=1;
   }else{
       status=0;
   }
    $.ajax({
        method:'post',
        url:'?c=phone&m=update_status_ajax',
        data:'status='+status+'&phone_id='+phone_id,
        success:function(data){
            if(data=='yes'){
                alert('操作成功！');
            }
            
        }
        
    });
   
   }
   </script>
   
</body>
</html>