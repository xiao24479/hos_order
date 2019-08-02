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

.date_div{ position:absolute; top:60px; left:0; z-index:1000;}

.anniu{ display:none;}

.o_a a{ padding:0 10px;}

.from_value{ width:85px; overflow:hidden; display:block;}
.order_form{ height:150px;border:none;}

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

    background-color:#00a186;
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

          <div class="order_count" style="position:fixed;padding-top:5px;z-index:1000;margin-left: -20px;width:100%;">

            <span>登记人数：<font><?php echo $count['reg_count']?$count['reg_count']:'0'; ?></font></span><span>预约人数：<font><?php echo $count['order_count']?$count['order_count']:'0'; ?></font></span><span>到诊人数：<font><?php echo $count['come_count']?$count['come_count']:'0'; ?></font></span><span>咨询量：<font><?php echo $count['dialog_count']?$count['dialog_count']:'0'; ?></font></span><span>到诊率：<font><?php echo number_format((($count['come_count']/$count['order_count']) * 100) , 2, '.', '') . '%'; ?></font></span><span>转化率：<font><?php echo number_format((($count['come_count']/$count['dialog_count']) * 100) , 2, '.', '') . '%'; ?></font></span>
          </div>



             <div id="top">

<form action="" method="get" class="date_form order_form" id="report_list_form" style="width:100%;">
            <input type="hidden" value="report" name="c" />
            <input type="hidden" value="rindex" name="m" />
             <div id="dy_search" style=" position:fixed;width:100%;padding:20px 0 10px 0;z-index: 3;top:100px;background-color:#f7f7f7;">


                      <div class="row-form" style="display:inline-block;">
                        <label style="float:left;margin-right:10px;height:30px;line-height:30px;margin-top:10px;font-size:12px;" for="date">选择时间</label>
                            <input type="text" value="<?php  if(empty($date)){echo date('Y年m月d日',time()); ?> - <?php echo date('Y年m月d日',time());}else{echo $date;}  ?>" style="width:240px;margin-top:8px; vertical-align:middle;height:16px;font-size:12px;float:left; " class="input-block-level" name="date" id="inputDate" readonly/>
                      </div>
                          <div class="row-form"  style="display:inline-block;">

                            <label class="select_label">选择医院</label>

                            <select name="hos_id" id="hos_id" style="width:180px;">

                              <option value="">请选择</option>

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
                       </div>
                      <div class="row-form" style="display:inline-block;">
                        <label class="select_label" for="group">选择小组</label>
                            <select name="group" id="group"  style="width:180px;float:left;">

                              <option value="">请选择</option>

                              <?php foreach($groups as $key => $group){?>

                               <optgroup label="<?php echo $key; ?>"><?php echo $key; ?></optgroup>
                                 <?php foreach ($group as $value) {?>
                                    <OPTION value="<?php echo $value['id']; ?>" <?php if($group_id == $value['id']){?>selected<?php } ?> ><?php echo $value['name']; ?></OPTION>

                              <?php }} ?>


                              </select>
                              <label class="select_label" for="admin_name">咨询</label>
                              <input type="text" name="admin_name" id="admin_name" value="<?php echo $admin_name; ?>" style="float: left;width: 100px;">
                         <input type="image" src="static/img/dy_search.png" style="vertical-align:middle;height:30px; float:left;margin-left:30px;cursor:pointer;" onclick="this.form.submit();"/>

                      </div>
                      <div class="row-form">
                            <a href="?c=report&m=export<?php echo $parse; ?>" class="btn btn-success" role="button"><i class="icon-download-alt"></i> 导出</a>
                            <a href="?c=report&m=allocate<?php echo $parse; ?>" class="btn btn-success" role="button" style="margin-left:100px;"><i class="icon-download-alt"></i> 目标分解</a>
                      </div>
                      <div class="date_div">

                        <div class="divdate"></div>

                        <div class="anniu"><a href="javascript:;" class="btn btn-inverse guanbi"> 关闭 </a><br /><a href="javascript:;" class="btn btn-inverse today"> 今天 </a><br /><a href="javascript:;" class="btn btn-inverse week"> 一周 </a><br /><a href="javascript:;" class="btn btn-inverse month"> 一月 </a><br /><a href="javascript:;" class="btn btn-inverse year"> 一年 </a></div>

                      </div>
              </div>


            </form>
</div>
    <div class="row-fluid"  style="border:0px;" id="tab1">

              <div class="span12" style="border:0px;">

                    <table width="100%" border="0px" cellspacing="0" cellpadding="2" class="list_table table-hover table-striped" style="font-size:12px;">

  <thead>

  <tr>

  <th width="10">序号</th>

  <th width="50">咨询员</th>

  <th width="50">咨询量</th>

  <th width="50">预约量</th>

  <th width="50">到诊量</th>

  <th width="50">预约率</th>

  <th width="50">到诊率</th>

  <th width="50">转化率</th>

  <th width="50">本月目标</th>

  <th width="50">完成率</th>

  <th width="50">本月任务量</th>

  <th width="50">本月对话量</th>




  </tr>

  </thead>

   <tbody>

  <?php
  if(!empty($data_list)){
        $i = 0;
    foreach($data_list as $key => $item){

        ?>
      <tr style="height:90px;" class="<?php if($i % 2){ echo 'td1';}?>">

    <td><?php echo $i + 1; ?></td>

    <td><?php echo $item['admin_name']; ?></td>
    <td><span class="dialog_num" id="dialog_num_<?php echo $item['admin_id']; ?>" aid="<?php echo $item['admin_id']; ?>"><?php echo $item['dialog_num']?$item['dialog_num']:'0'; ?></span></td>
    <td><?php echo $item['reg_num']?$item['reg_num']:'0'; ?></td>
    <td><?php echo $item['come_num']?$item['come_num']:'0'; ?></td>
    <td><?php echo number_format((($item['reg_num']/$item['dialog_num']) * 100) , 2, '.', '') . '%'; ?></td>
    <td><?php echo number_format((($item['come_num']/$item['order_num']) * 100) , 2, '.', '') . '%'; ?></td>
    <td><?php echo number_format((($item['come_num']/$item['dialog_num']) * 100) , 2, '.', '') . '%'; ?></td>
    <td><span class="target_num" id="target_num_<?php echo $item['admin_id']; ?>" aid="<?php echo $item['admin_id']; ?>"><?php echo $item['target_num']?$item['target_num']:'0'; ?></span></td>
    <td><?php echo number_format((($item['come_num']/$item['target_num']) * 100) , 2, '.', '') . '%'; ?></td>
    <td><span class="task_come_num" id="task_come_num_<?php echo $item['admin_id']; ?>" aid="<?php echo $item['admin_id']; ?>"><?php echo $item['task_come_num']?$item['task_come_num']:'0'; ?></span></td>
    <td><span class="task_dialog_num" id="task_dialog_num_<?php echo $item['admin_id']; ?>" aid="<?php echo $item['admin_id']; ?>"><?php echo $item['task_dialog_num']?$item['task_dialog_num']:'0'; ?></span></td>
  </tr>





        <?php

   $i ++;
    }

  }else{
      echo "<tr><td colspan='12'>很抱歉，亲，查找不到相关数据哦！</td></tr>";
  }


  ?>



  </tbody>

  </table>




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



  $(".anniu").css("display", "block");

  $('.divdate').DatePicker({

    flat: true,

    date: ['<?php echo $start; ?>','<?php echo $end; ?>'],

    current: '<?php echo $end; ?>',

    format: 'Y年m月d日',

    calendars: 2,

    mode: 'range',

    starts: 1,

    onChange: function(formated) {

      $('#inputDate').val(formated.join(' - '));

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

    $('#inputDate').val(get_day(-7) + " - " + get_day(-1));

    $('.date_div').css("display", "none");

  });

  $(".anniu .month").click(function(){

    $('#inputDate').val(get_day(-30) + " - " + get_day(-1));

    $('.date_div').css("display", "none");

  });

  $(".anniu .year").click(function(){

    $('#inputDate').val(get_day(-365) + " - " + get_day(-1));

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

//获取某年某月多少天
function getDaysInMonth(yd){
  var ta = yd.split('-');
  var temp = new Date(ta[0],ta[1],0);
  return temp.getDate();
}


function stringtotime(stringTime){
  // 获取某个时间格式的时间戳
  //var stringTime = "2014-07-10 10:21:12";
  var timestamp = Date.parse(new Date(stringTime));
  return timestamp / 1000;
  //2014-07-10 10:21:12的时间戳为：1404958872 
  //console.log(stringTime + "的时间戳为：" + timestamp2);
}

$(function(){
    $('.dialog_num').click(function(){
      var aid = $(this).attr('aid');
        var td=$(this); //为后面文本框变成文本铺垫
        var text=parseInt($(this).text());
        var input_dialog=$('<input type="text" class="edit_dialog" name="dialog_num" value="'+text+'">');
        $(this).html( input_dialog );

        $('.edit_dialog').click(function(){
            return false;
        }); //阻止表单默认点击行为

        $('.edit_dialog').select(); //点击自动全选中表单的内容


        $('.edit_dialog').blur(function(){
            var nextxt=parseInt($(this).val());
            var patrn=/^[0-9]+$/;
            if (!patrn.exec(nextxt)) {
              layer.msg('只能是整数', {icon: 5,time: 1000});
              $('.edit_dialog').select();
              return false;
            }

            if (text === nextxt) {
              td.html(nextxt);
              return false;
            }

            var inputText = $('#inputDate').val();
            var date_array = inputText.split(' - ');
            var start_time = date_array[0].replace(/[\u4e00-\u9fa5]/g, "-").substring(0,date_array[0].length-1);
            var end_time = date_array[1].replace(/[\u4e00-\u9fa5]/g, "-").substring(0,date_array[1].length-1);

            if (start_time !== end_time) {
              layer.msg('只能按天数添加对话数！', {icon: 5,time: 2000});
              td.html(text);
              return false;
            }


            td.html(nextxt);
            var come_val = parseInt(td.parent().siblings('td').eq(3).text());
            var come_percentage = (Math.round(come_val / nextxt * 10000) / 100.00 + "%");
            td.parent().siblings('td').eq(6).text(come_percentage);
            //console.log(come_val+'---'+nextxt+'=='+come_percentage)
            $.ajax({
              type:'post',
              url:'?c=report&m=ajax_dialog_num',
              data:{aid:aid,num:$(this).val(),date:inputText},
              dataType:'html',
              success:function(data){
                if (data == 1) {
                  layer.msg('修改成功！', {icon: 1,time: 1000});
                } else {
                  layer.msg('修改失败！', {icon: 5,time: 1000});
                }
              }
            })
        }); //表单失去焦点文本框变成文本

    });

    $('.target_num').click(function(){
      var aid = $(this).attr('aid');
        var td=$(this); //为后面文本框变成文本铺垫
        var text=parseInt($(this).text());
        var input_dialog=$('<input type="text" class="edit_target" name="target_num" value="'+text+'">');
        $(this).html( input_dialog );

        $('.edit_target').click(function(){
            return false;
        }); //阻止表单默认点击行为

        $('.edit_target').select(); //点击自动全选中表单的内容


        $('.edit_target').blur(function(){
            var nextxt=parseInt($(this).val());
            var patrn=/^[0-9]+$/;
            if (!patrn.exec(nextxt)) {
              layer.msg('只能是整数', {icon: 5,time: 1000});
              $('.edit_target').select();
              return false;
            }

            if (text === nextxt) {
              td.html(nextxt);
              return false;
            }

            var inputText = $('#inputDate').val();
            var date_array = inputText.split(' - ');
            var start_time = date_array[0].replace(/[\u4e00-\u9fa5]/g, "-").substring(0,date_array[0].length-1);
            var end_time = date_array[1].replace(/[\u4e00-\u9fa5]/g, "-").substring(0,date_array[1].length-1);

            var start_time_pre = start_time.substring(0,date_array[0].length-4);
            var end_time_pre = end_time.substring(0,date_array[1].length-4);


            console.log(start_time_pre+'-----'+end_time_pre)
            var days = getDaysInMonth(end_time_pre);


            var new_start_time = start_time + " 00:00:00";
            var new_end_time = end_time + " 23:59:59";

            var cha = (stringtotime(new_end_time)-stringtotime(new_start_time)+1)/(60*60*24);


            if (start_time_pre === end_time_pre && days === cha) {
              td.html(nextxt);
              var tar_val = parseInt(td.parent().siblings('td').eq(4).text());
              var tar_percentage = (Math.round(tar_val / nextxt * 10000) / 100.00 + "%");
              td.parent().siblings('td').eq(8).text(tar_percentage);

              $.ajax({
                type:'post',
                url:'?c=report&m=ajax_target_num',
                data:{aid:aid,num:$(this).val(),date:inputText},
                dataType:'html',
                success:function(data){
                  if (data == 1) {
                    layer.msg('修改成功！', {icon: 1,time: 1000});
                  } else {
                    layer.msg('修改失败！', {icon: 5,time: 1000});
                  }
                }
              })
            } else {
              layer.msg('只能按自然月添加目标到诊！', {icon: 5,time: 2000});
              td.html(text);
              return false;
            }

        }); //表单失去焦点文本框变成文本

    });


    $('.task_come_num').click(function(){
      var aid = $(this).attr('aid');
        var td=$(this); //为后面文本框变成文本铺垫
        var text=parseInt($(this).text());
        var input_dialog=$('<input type="text" class="edit_task_come" name="task_come_num" value="'+text+'">');
        $(this).html( input_dialog );

        $('.edit_task_come').click(function(){
            return false;
        }); //阻止表单默认点击行为

        $('.edit_task_come').select(); //点击自动全选中表单的内容


        $('.edit_task_come').blur(function(){
            var nextxt=parseInt($(this).val());
            var patrn=/^[0-9]+$/;
            if (!patrn.exec(nextxt)) {
              layer.msg('只能是整数', {icon: 5,time: 1000});
              $('.edit_task_come').select();
              return false;
            }

            if (text === nextxt) {
              td.html(nextxt);
              return false;
            }

            var inputText = $('#inputDate').val();
            var date_array = inputText.split(' - ');
            var start_time = date_array[0].replace(/[\u4e00-\u9fa5]/g, "-").substring(0,date_array[0].length-1);
            var end_time = date_array[1].replace(/[\u4e00-\u9fa5]/g, "-").substring(0,date_array[1].length-1);

            var start_time_pre = start_time.substring(0,date_array[0].length-4);
            var end_time_pre = end_time.substring(0,date_array[1].length-4);


            console.log(start_time_pre+'-----'+end_time_pre)
            var days = getDaysInMonth(end_time_pre);


            var new_start_time = start_time + " 00:00:00";
            var new_end_time = end_time + " 23:59:59";

            var cha = (stringtotime(new_end_time)-stringtotime(new_start_time)+1)/(60*60*24);


            if (start_time_pre === end_time_pre && days === cha) {
              td.html(nextxt);

              $.ajax({
                type:'post',
                url:'?c=report&m=ajax_task_come_num',
                data:{aid:aid,num:$(this).val(),date:inputText},
                dataType:'html',
                success:function(data){
                  if (data == 1) {
                    layer.msg('修改成功！', {icon: 1,time: 1000});
                  } else {
                    layer.msg('修改失败！', {icon: 5,time: 1000});
                  }
                }
              })
            } else {
              layer.msg('只能按自然月添加当月任务到诊量！', {icon: 5,time: 2000});
              td.html(text);
              return false;
            }

        }); //表单失去焦点文本框变成文本

    });



    $('.task_dialog_num').click(function(){
      var aid = $(this).attr('aid');
        var td=$(this); //为后面文本框变成文本铺垫
        var text=parseInt($(this).text());
        var input_dialog=$('<input type="text" class="edit_task_dialog" name="task_dialog_num" value="'+text+'">');
        $(this).html( input_dialog );

        $('.edit_task_dialog').click(function(){
            return false;
        }); //阻止表单默认点击行为

        $('.edit_task_dialog').select(); //点击自动全选中表单的内容


        $('.edit_task_dialog').blur(function(){
            var nextxt=parseInt($(this).val());
            var patrn=/^[0-9]+$/;
            if (!patrn.exec(nextxt)) {
              layer.msg('只能是整数', {icon: 5,time: 1000});
              $('.edit_task_dialog').select();
              return false;
            }

            if (text === nextxt) {
              td.html(nextxt);
              return false;
            }

            var inputText = $('#inputDate').val();
            var date_array = inputText.split(' - ');
            var start_time = date_array[0].replace(/[\u4e00-\u9fa5]/g, "-").substring(0,date_array[0].length-1);
            var end_time = date_array[1].replace(/[\u4e00-\u9fa5]/g, "-").substring(0,date_array[1].length-1);

            var start_time_pre = start_time.substring(0,date_array[0].length-4);
            var end_time_pre = end_time.substring(0,date_array[1].length-4);


            console.log(start_time_pre+'-----'+end_time_pre)
            var days = getDaysInMonth(end_time_pre);


            var new_start_time = start_time + " 00:00:00";
            var new_end_time = end_time + " 23:59:59";

            var cha = (stringtotime(new_end_time)-stringtotime(new_start_time)+1)/(60*60*24);


            if (start_time_pre === end_time_pre && days === cha) {
              td.html(nextxt);

              $.ajax({
                type:'post',
                url:'?c=report&m=ajax_task_dialog_num',
                data:{aid:aid,num:$(this).val(),date:inputText},
                dataType:'html',
                success:function(data){
                  if (data == 1) {
                    layer.msg('修改成功！', {icon: 1,time: 1000});
                  } else {
                    layer.msg('修改失败！', {icon: 5,time: 1000});
                  }
                }
              })
            } else {
              layer.msg('只能按自然月添加任务对话量！', {icon: 5,time: 2000});
              td.html(text);
              return false;
            }

        }); //表单失去焦点文本框变成文本

    });



})










function ajax_get_group(hos_id, check_id)

{

  $.ajax({

    type:'post',

    url:'?c=report&m=ajax_get_group',

    data:'hos_id=' + hos_id + '&check_id=' + check_id,

    success:function(data)

    {

      $("#group").html(data);

    },

    complete: function (XHR, TS)

    {

       XHR = null;

    }

  });

}
<?php if($hos_id > 0):?>

ajax_get_group(<?php echo $hos_id?>, <?php echo !empty($group_id)?$group_id:'0';?>);

<?php endif;?>

$("#hos_id").change(function(){

  var hos_id = $(this).val();

  ajax_get_group(hos_id, 0);


});
    </script>
    <style>
      .dialog_num,.target_num,.task_come_num,.task_dialog_num{padding:20px 30px;}
      .edit_dialog,.edit_target,.edit_task_come,.edit_task_dialog{width:100px;height:30px;}
    </style>
    </body>
</html>
