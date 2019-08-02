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



.list_table .disabledlist td {
    background-color: #999;
    border-top: 1px solid #000;
}



.list_table .expiredlist td {
    background-color: #f3b3b3;
    border-top: 1px solid #860505;
}



</style>







<script src="static/js/jquery.js"></script>







<script language="javascript">







if($(window).width() <= 1200)







{







  window.location.href = '?c=order&m=order_list_liulian&type=mi';







}







</script>



<script type="text/javascript" src="static/js/js_window/zDrag.js"></script>



<script type="text/javascript" src="static/js/js_window/zDialog.js"></script>



<script>



function open7(order_id)



{



  var diag = new Dialog();



  diag.Width = 820;



  diag.Height = 400;



  diag.Title = "留联操作记录页";



  diag.URL = "?c=order&m=order_detail_liulian&order_id="+order_id;



  diag.show();



}















</script>







</head>















<body class="fixed-top" style="width:100%;margin:0 auto; ">



<?php echo $top; ?>







<div id="container" class="row-fluid" >







<?php echo $sider_menu; ?>















    <div id="main-content" style="margin-left:180px;">







         <!-- BEGIN PAGE CONTAINER-->







         <div class="container-fluid" style="position:relative; padding-top:0px;margin-left: 0px;background-color: #f7f7f7;">







             <div class="order_count" style="position:fixed;padding-top:5px;z-index:1000;margin-left: -20px;width:100%;">







<span><?php if($rank_type == 2 || $rank_type == 3 || $rank_type == 4 || $_COOKIE['l_admin_action'] == 'all'):?><a style="cursor:pointer" onclick="order_window('?c=order&m=order_info_liulian&type=mi');_czc.push(['_trackEvent', '顶部链接', '<?php echo $admin['name']; ?>', '新增留联','','']);" target="_blank"><i class="icon-plus"></i> 新增留联</a><?php endif ?></span>

<span>总留联人数：<font><?php echo $total_rows; ?></font></span><span>总预约人数：<font><?php echo $total_ok_rows; ?></font> <span>总预约到诊人数：<font><?php echo $dz_rows; ?></font></span>



 <a href="<?php echo $down_url;?>" target="_blank" onclick="_czc.push(['_trackEvent', '顶部链接', '<?php echo $admin['name']; ?>', '导出数据','','']);">导出数据</a>







</div>







             <div id="top" style="position:fixed; z-index: 999; background-color: #f7f7f7;height:100px;margin-top:-10px;margin-left:-20px;">



              <form action="" method="get" class="date_form order_form" id="order_list_form"style="width:100%;">







<input type="hidden" value="order" name="c" />







<input type="hidden" value="order_list_liulian" name="m" />



           <div id="dy_search" style=" position:fixed;width:100%;height:100px;;padding-top:10px;z-index: 3;">







               <div style="position:fixed;padding-top:22px;width:100%;height: 100px;line-height: 5px;font-family: '微软雅黑';color: #808080;font-size: 12px;background-color: #f7f7f7;">



                   <span style="margin-left: 30px;">



            <input type="hidden"  id="order_query_seven_data" value="<?php echo $order_query_seven_data ;?>">







              <!--  时间类型 -->



              <input type="hidden" name="t" value="1">







                       <input type="text" value="<?php echo $start; ?> - <?php echo $end; ?>" style="width:240px;margin-top:8px; vertical-align:middle;height:16px;font-size:12px; " class="input-block-level" name="date" id="inputDate" /></span>



                   <span style="margin-left: 10px;"> 留联/预约单号：<input type="text" class="input_search" value="<?php echo $o_o; ?>" name="o_o" style="margin-top: 7px;width:70px;height:16px;font-size:12px; outline:none;"/> </span>



                   <span style="margin-left: 10px;"> 姓 名：<input type="text" class="input_search" value="<?php echo $p_n; ?>"  name="p_n" style="margin-top: 7px;width:70px;height:16px;font-size:12px; outline:none;"/> </span>



                   <span style="margin-left: 10px;"> 电 话：<input type="text" class="input_search" value="<?php echo $p_p; ?>" name="p_p" style="margin-top: 7px;width:100px;height:16px;font-size:12px; outline:none;"/> </span>



                   <span style="margin-left: 10px;"> 微信：<input type="text" class="input_search" value="<?php echo $p_w; ?>" name="p_w" style="margin-top: 7px;width:100px;height:16px;font-size:12px; outline:none;"/> </span>



                   <span style="margin-left: 10px;">转为预约：

          <select name="order_no_yy_check" id="order_no_yy_check" style="width:130px;">

          <option value="0"><?php echo $this->lang->line('please_select');?></option>

          <option value="1" <?php if($order_no_yy_check == 1){echo " selected";}?>>未预约</option>

          <option value="2" <?php if($order_no_yy_check == 2){echo " selected";}?>>已预约</option>

          </select>

          </span>

                   <br/>

                   <span style="margin-left: 10px;"> QQ：<input type="text" class="input_search" value="<?php echo $p_q; ?>" name="p_q" style="margin-top: 7px;width:100px;height:16px;font-size:12px; outline:none;"/> </span>





                   <span style="margin-left: 10px;"> 咨询员：<input type="text" class="input_search" value="<?php echo $a_i; ?>" name="a_i" style="margin-top: 7px;width:80px;height:16px; font-size:12px;outline:none;"/></span>

                   <span style="margin-left: 10px;"> 回访员：<input type="text" class="input_search" value="<?php echo $a_h; ?>" name="a_h" style="margin-top: 7px;width:80px;height:16px; font-size:12px;outline:none;"/></span>



                   <span style="margin-left: 10px;"> <input type="image" class="input_search" src="static/img/dy_search.png" style="vertical-align:middle;height:30px; cursor:pointer;" onclick="this.form.submit();"/>







                           <input type="hidden" value="0" class="input-medium" name="excel_status" id="excel_status" />



                   </span>



               </div>



               <div class="date_div">







    <div class="divdate"></div>







    <div class="anniu"><a href="javascript:;" class="btn btn-inverse guanbi"> 关闭 </a><br /><a href="javascript:;" class="btn btn-inverse today"> 今天 </a><br /><a href="javascript:;" class="btn btn-inverse week"> 一周 </a><br /><a href="javascript:;" class="btn btn-inverse month"> 一月 </a><br /><a href="javascript:;" class="btn btn-inverse year"> 一年 </a></div>







    </div>



            </div>







          <span style="position:fixed;margin-top: 90px;z-index: 3;margin-left: 620px;"><span style="color:#00a186;cursor:pointer; " id="gaoji_search" >高级搜索 <img src='static/img/dy_lists.png'id="search_pic"/></span></span>



          <!--高级搜索表单开始-->



           </div>



          <div id="gaoji" style="position: fixed; margin-top:90px;margin-left:-30px; background-color:#f7f7f7;width:100%;z-index: 9;align:center;border-bottom: 1px solid #bbb;">







              <div style="margin-left:0px;">



              <div class="span5" style="margin-left:32px;width:400px; margin-top: 35px;">







    <div class="row-form">















    </div>























  <div class="row-form">







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







  </div>







    <div class="row-form">







    <label class="select_label"><?php echo $this->lang->line('order_keshi');?></label>







    <select name="hos_id" id="hos_id" style="width:180px;">







      <option value=""><?php echo $this->lang->line('hospital_select'); ?></option>







      <?php foreach($hospital as $val):?><option value="<?php echo $val['hos_id']; ?>" <?php if($val['hos_id'] == $hos_id){echo " selected";}?>><?php echo $val['hos_name']; ?></option><?php endforeach;?>







    </select>







    <select name="keshi_id" id="keshi_id" style="width:130px;">







      <option value=""><?php echo $this->lang->line('keshi_select'); ?></option>



<?php foreach($keshi as $val): if($val['hos_id'] == $hos_id){?><option value="<?php echo $val['keshi_id']; ?>" <?php if($val['keshi_id'] == $keshi_id){echo " selected";}?>><?php echo $val['keshi_name']; ?></option><?php } endforeach;?>







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



    <div class="row-form">



    <label class="select_label">关联微信</label>







    <select name="wx" style="width:165px;">







      <option value="">请选择...</option>







      <option value="1" <?php if($wx == 1){echo " selected";}?>>未关联</option><option value="2" <?php if($wx == 2){echo " selected";}?>>已关联</option>



    </select>



  </div>


<?php $display_ks = array(1,32); ?>
<?php $is_exist = array_intersect($display_ks, explode(',', $_COOKIE['l_keshi_id'])); ?>
<?php if (!empty($is_exist) || $_COOKIE['l_admin_action'] == 'all'): ?>
      <div class="row-form">
        <label class="select_label">分组</label>
        <select name="group" style="width:165px;">
          <option value="">请选择...</option>
          <option value="1" <?php if($is_group == 1){echo " selected";}?>>A组</option>
          <option value="2" <?php if($is_group == 2){echo " selected";}?>>B组</option>
          <option value="3" <?php if($is_group == 3){echo " selected";}?>>C组</option>
          <option value="4" <?php if($is_group == 4){echo " selected";}?>>D组</option>
        </select>
      </div>

      <div class="row-form">
        <label class="select_label">是否过期</label>
        <select name="expired" style="width:165px;">
          <option value="">请选择...</option>
          <option value="1" <?php if($is_expired == 1){echo " selected";}?>>已过期</option>
          <option value="2" <?php if($is_expired == 2){echo " selected";}?>>未过期</option>
        </select>
      </div>
<?php endif; ?>



    <div class="row-form">







    <label class="select_label"><?php echo $this->lang->line('pager');?></label>







    <select name="p" style="width:165px;">







      <option value=""><?php echo $this->lang->line('please_select'); ?></option>







      <?php foreach($this->lang->line('page_no') as $key=>$val):?><option value="<?php echo $key; ?>" <?php if($key == $per_page){ echo "selected";}?>><?php echo $val; ?></option><?php endforeach;?>







    </select>







  </div>







</div>







              <div class="span3" style="margin-left:10px; width:250px; margin-top: 35px;">







<!--  <div class="row-form">







    <label class="select_label"><?php echo $this->lang->line('patient_name');?></label>







    <input type="text" value="<?php echo $p_n; ?>" class="input-medium" name="p_n"  />







  </div>-->











<div class="row-form">







    <label class="select_label"><?php echo $this->lang->line('type_name');?></label>







    <select name="o_t" style="width:165px;">







      <option value="" selected ><?php echo $this->lang->line('please_select'); ?></option>







      <?php foreach($type_list as $val):?><option value="<?php echo $val['type_id']; ?>" <?php if($val['type_id'] == $p_jb){echo " selected";}?>><?php echo $val['type_name']; ?></option><?php endforeach;?>







    </select>







  </div>







  <div class="row-form">







    <label class="select_label">大病种</label>







    <select name="p_jb" id="p_jb" style="width:165px;">







      <option value=""><?php echo $this->lang->line('jb_parent_select'); ?></option>







      <?php foreach($jibing_parent as $key=>$val):?><option value="<?php echo $val['jb_id']; ?>" <?php if($val['jb_id'] == $p_jb){ echo "selected";}?>><?php echo $val['jb_name']; ?></option><?php endforeach;?>







    </select>







  </div>























</div>







<div class="span3" style="margin-left:5px; margin-top: 35px;width:270px;">







<!--  <div class="row-form">







    <label class="select_label"><?php echo $this->lang->line('patient_phone');?></label>







    <input type="text" value="<?php echo $p_p; ?>" class="input-medium" name="p_p"  />







  </div>-->







<div class="row-form" style="">







    <label class="select_label"><?php echo $this->lang->line('status');?></label>







    <select name="s" id="status" style="width:165px;">







      <option value=""><?php echo $this->lang->line('please_select'); ?></option>







      <?php foreach($this->lang->line('order_status') as $key=>$val):?><option value="<?php echo $key; ?>" <?php if($key == $s){echo " selected";}?>><?php echo $val; ?></option><?php endforeach;?>







    </select>







  </div>















  <div class="row-form">







    <label class="select_label">小病种</label>







    <select name="jb" id="jb" style="width:165px;">







      <option value=""><?php echo $this->lang->line('jb_child_select'); ?></option>







    </select>







  </div>











</div>



















              </div>







 </div>







</form>











           <!-- 高级搜索表单结束-->











    <div class="row-fluid"  style="border:0px;" id="tab1">







              <div class="span12" style="border:0px;">







                    <table width="100%" border="0px" cellspacing="0" cellpadding="2" class="list_table" style="margin-top:120px;font-size:12px;">







  <thead>







  <tr>







  <th width="30">序号</th>







    <th width="50"><?php echo $this->lang->line('order_no'); ?></th>







  <th><?php echo $this->lang->line('patient_info'); ?></th>







  <th width="200">











    <input type="hidden" id="select_type_value" value="<?php echo $this->lang->line('time'); ?>">







    <ul class="nav_title">







        <li><a class="fly" id="select_type_class" href="javaScript:void(0);" style="text-decoration:none;"><?php echo $this->lang->line('time'); ?></a>



            <ul class="dd">



                 <li  class="select_type" title="0~1"><a href="javaScript:void(0);"  >0~1</a></li>



                 <li  class="select_type" title="1~2"><a href="javaScript:void(0);"  >1~2</a></li>



                 <li  class="select_type" title="2~3"><a href="javaScript:void(0);"  >2~3</a></li>



                 <li  class="select_type" title="3~4"><a href="javaScript:void(0);"  >3~4</a></li>



                 <li  class="select_type" title="4~5"><a href="javaScript:void(0);"  >4~5</a></li>



                 <li  class="select_type" title="5~6"><a href="javaScript:void(0);"  >5~6</a></li>



                 <li  class="select_type" title="6~7"><a href="javaScript:void(0);"  >6~7</a></li>



                 <li  class="select_type" title="7~8"><a href="javaScript:void(0);"  >7~8</a></li>



                 <li  class="select_type" title="8~9"><a href="javaScript:void(0);"  >8~9</a></li>



                 <li  class="select_type" title="9~10"><a href="javaScript:void(0);"  >9~10</a></li>



                 <li  class="select_type" title="10~11"><a href="javaScript:void(0);"  >10~11</a></li>



                 <li  class="select_type" title="11~12"><a href="javaScript:void(0);"  >11~12</a></li>



          <li  class="select_type" title="12~13"><a href="javaScript:void(0);"  >12~13</a></li>



           <li  class="select_type" title="13~14"><a href="javaScript:void(0);"  >13~14</a></li>



            <li  class="select_type" title="14~15"><a href="javaScript:void(0);"  >14~15</a></li>



           <li  class="select_type" title="15~16"><a href="javaScript:void(0);"  >15~16</a></li>



            <li  class="select_type" title="16~17"><a href="javaScript:void(0);"  >16~17</a></li>



             <li  class="select_type" title="17~18"><a href="javaScript:void(0);"  >17~18</a></li>



              <li  class="select_type" title="18~19"><a href="javaScript:void(0);"  >18~19</a></li>



             <li  class="select_type" title="19~20"><a href="javaScript:void(0);"  >19~20</a></li>



              <li  class="select_type" title="20~21"><a href="javaScript:void(0);"  >20~21</a></li>



               <li  class="select_type" title="21~22"><a href="javaScript:void(0);"  >21~22</a></li>



                <li  class="select_type" title="22~23"><a href="javaScript:void(0);"  >22~23</a></li>



               <li  class="select_type" title="23~24"><a href="javaScript:void(0);"  >23~24</a></li>



                             <li  class="select_type" title="0~24"><a href="javaScript:void(0);"  >时间</a></li>



            </ul>



        </li>







    </ul>







    </th>







  <th width="80">



     <input type="hidden" id="appointment_route_value" value="途径/性质">







     <ul class="nav_title">







        <li><a class="fly" id="appointment_route_class" href="javaScript:void(0);"  style="text-decoration:none;">途径/性质</a>



            <ul class="dd">



                <?php   foreach($appointment_route as $appointment_route_item){?>



           <li  class="select_type"  title="<?php echo $appointment_route_item['order_id']?>"><a href="javaScript:void(0);"  ><?php echo $appointment_route_item['from_name'].'('.$appointment_route_item['order_count'].')'?></a></li>



         <?php }?>



                 <li  class="select_type"  title=""><a href="javaScript:void(0);"  >途径/性质</a></li>



            </ul>



        </li>







    </ul>







    </th>







  <th width="70">



     <input type="hidden" id="appointment_section_value" value="<?php echo $this->lang->line('order_keshi'); ?>">







     <ul class="nav_title">







        <li><a class="fly" id="appointment_section_class" href="javaScript:void(0);"  style="text-decoration:none;"><?php echo $this->lang->line('order_keshi'); ?></a>



            <ul class="dd">



                 <?php   foreach($appointment_section as $appointment_section_item){?>



           <li  class="select_type" title="<?php echo $appointment_section_item['order_id']?>"  ><a href="javaScript:void(0);"><?php echo $appointment_section_item['keshi_name'].'('.$appointment_section_item['order_count'].')'?></a></li>



         <?php }?>



                 <li  class="select_type"  title=""><a href="javaScript:void(0);"  ><?php echo $this->lang->line('order_keshi'); ?></a></li>



            </ul>



        </li>







    </ul>







    </th>







  <th width="70">



    <input type="hidden" id="appointment_disease_value" value="<?php echo $this->lang->line('patient_jibing'); ?>">







      <ul class="nav_title">







        <li><a class="fly" id="appointment_disease_class" href="javaScript:void(0);"  style="text-decoration:none;"><?php echo $this->lang->line('patient_jibing'); ?></a>



            <ul class="dd">



                <?php   foreach($appointment_disease as $appointment_disease_item){?>



           <li  class="select_type"  title="<?php echo $appointment_disease_item['order_id']?>"><a href="javaScript:void(0);" ><?php echo $appointment_disease_item['jb_name'].'('.$appointment_disease_item['order_count'].')'?></a></li>



         <?php }?>



                 <li  class="select_type"  title=""><a href="javaScript:void(0);"  ><?php echo $this->lang->line('patient_jibing'); ?></a></li>



            </ul>



        </li>







    </ul>







    </th>







    <th width="65">



    <input type="hidden" id="consult_a_doctor_value" value="客服/医生">







     <ul class="nav_title">







        <li><a class="fly" id="consult_a_doctor_class" href="javaScript:void(0);"  style="text-decoration:none;">客服/医生</a>



            <ul class="dd">



                <?php   foreach($consult_a_doctor as $consult_a_doctor_item){?>



           <li class="select_type" title="<?php echo $consult_a_doctor_item['order_id']?>"><a href="javaScript:void(0);"  ><?php echo $consult_a_doctor_item['admin_name'].'('.$consult_a_doctor_item['order_count'].')'?></a></li>



         <?php }?>



                  <li  class="select_type"  title=""><a href="javaScript:void(0);"  >客服/医生</a></li>



            </ul>



        </li>







    </ul>







    </th>

   <th width="200">回访</th>

    <th width="200"><?php echo $this->lang->line('notice'); ?></th>





  <th width="180" style="border-right:1px solid #9D4A9C;"><?php echo $this->lang->line('action'); ?></th>







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

  foreach($order_list as $item){

    if(!in_array('179',$l_admin_action)){

      $item['pat_phone']  =  $item['pat_phone'][0].$item['pat_phone'][1].$item['pat_phone'][2].'*****';

      $item['pat_phone1']  =  $item['pat_phone1'][0].$item['pat_phone1'][1].$item['pat_phone1'][2].'*****';

    }





        ?>



        <tr
        <?php if($item['is_expired'] == 1):?>
          <?php if($i % 2):?>
            class='td1 expiredlist'
          <?php else: ?>
            class='expiredlist'
          <?php endif;?>
        <?php else: ?>
          <?php if($i % 2):?>
            class='td1'
          <?php endif;?>
        <?php endif;?>

        style="height:90px;"  id="<?php echo $item['order_id']; ?>"  title="<?php echo date('H',strtotime($item['order_addtime'])); ?>">







    <td><b style="color:#808080;font-size:16px;"><?php echo $now_page + $i + 1; ?></b></td>







    <td>

    <span id="ll_group_<?php echo $item['order_id'] ?>"><?php  if($item['is_group'] == 1 ): ?>A组<?php elseif($item['is_group'] == 2 ):?>B组<?php elseif($item['is_group'] == 3 ):?>C组<?php elseif($item['is_group'] == 4 ):?>D组<?php endif;?></span>

    <?php  if($item['keshi_id'] == 7 && $item['from_parent_id'] == 23 && ($admin['rank_id'] == 21 || $admin['rank_id'] == 28)): ?>

        <?php echo $item['order_no']; ?>

   <?php else:?>

     <a style="cursor:pointer;color:#333333;"   id="order_no_<?php echo $item['order_id']; ?>" <?php if(in_array(143, $admin_action)||$_COOKIE['l_admin_action'] == 'all'||$item['hos_id']==1){echo " onclick='open7(".$item['order_id'].");'"; }else{echo "";}?>><?php echo $item['order_no']; ?></a>

     <?php endif;?>



<br />







     <?php if($item['is_first']){ echo "初诊";}else{ echo "<font color='#FF0000'>复诊</font>";}?><br />



     <?php if(!empty($item['order_no_yy'])){?>

      <a href="javascript:void(0)"><i id="status_401892" class="icon-ok" style="color: rgb(251, 153, 0); font-size: large;"></i></a>

       <?php  }?>
<br />
       <?php if($item['is_expired'] == 1):?>
         <p><?php echo $item['is_expired_msg']; ?></p>
       <?php endif;?>

    </td>



  <td style="text-align:left;">







    <div id="pat_<?php echo $item['order_id']; ?>">



预约单号：<a href="?c=order&m=order_list&t=1&o_o=<?php echo $item['order_no_yy'];?>"><span style="color:blue;"><?php echo $item['order_no_yy'];?></span></a>&nbsp;<?php  if(!empty($item['order_is_come'])){echo '<span style="color:red;">已到诊</span>'; }?><br/>转单时间:<?php if($item['order_no_yy_time']){echo date("Y-m-d H:i:s",$item['order_no_yy_time']);}?><br/>



        姓名：<?php if(!empty($item['zampo'])):?><del><?php endif;?><font style="color:#ff6060;font-size:14px;font-weight:normal;" id="pat_name_<?php echo $item['order_id']; ?>"><?php echo $item['pat_name']?></font>（<?php echo $item['pat_sex']; ?>、<?php echo $item['pat_age']?>岁）

          <a href="#kefu_talk" title="对话记录" id="talk_status_<?php echo $item['order_id'];?>" role="button" data-toggle="modal" onClick="kefu_talk('<?php echo $item['order_id']?>');_czc.push(['_trackEvent', '留联相关', '<?php echo $admin['name']; ?>', '对话记录','','']);"><img src="static/img/talk<?php if(empty($item['con_content'])){echo '_g';} ?>.png" width="16px;"/></a><?php if(!empty($item['zampo'])):?></del><?php endif;?><br />





         <?php

         $order_id_check= '';

      foreach($order_remark_list as $order_remark_list_temp){

        if(strcmp($order_remark_list_temp['order_id'],$item['order_id']) == 0){

          $order_id_check =$order_remark_list_temp['mark_id'];break;

        }

      }

      ?>

          <a href="#kefu_hf" title="回访记录" id="hf_status_<?php echo $item['order_id'];?>" role="button" data-toggle="modal" onClick="kefu_hf('<?php echo $item['order_id']?>');_czc.push(['_trackEvent', '留联相关', '<?php echo $admin['name']; ?>', '回访记录','','']);"><img src="static/img/talk<?php if(empty($order_id_check)){echo '_g';} ?>.png" width="16px;"/></a><?php if(!empty($item['zampo'])):?></del><?php endif;?><br />





          <br />



    <?php if($_COOKIE['l_admin_id'] == '239'||$_COOKIE['l_admin_id'] == '215'):?>



    电话：<font id="pat_phone_<?php echo $item['order_id']; ?>"><?php echo $item['pat_phone']; if(!empty($item['pat_phone1'])){echo "/" . $item['pat_phone1'];}?></font><br />



    <?php else:?>



      <?php if($rank_type == 2|| $rank_type == 1|| $_COOKIE['l_admin_action'] == 'all' || $item['is_come'] > 0):?>



        <?php if(in_array($item['hos_id'],$hos_auth) && $rank_type == 2 && $item['admin_id'] != $_COOKIE['l_admin_id']):?>



         电话：<font id="pat_phone_<?php echo $item['order_id']; ?>"><?php echo $item['pat_phone']; if(!empty($item['pat_phone1'])){echo "/" . $item['pat_phone1'];}?></font><br />



        <?php else:?>



        电话：<font id="pat_phone_<?php echo $item['order_id']; ?>"><?php echo $item['pat_phone']; if(!empty($item['pat_phone1'])){echo "/" . $item['pat_phone1'];}?></font><br />



        <?php endif;?>



      <?php else:?>











      电话：<font id="pat_phone_<?php echo $item['order_id']; ?>"><?php echo $item['pat_phone']; if(!empty($item['pat_phone1'])){echo "/" . $item['pat_phone1'];}?></font><br />







      <?php endif;?>



    <?php endif;?>

        QQ：<font  ><?php echo $item['pat_qq']; ?></font><br />

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







        echo "【孕周】" . (intval((time() - $item['data_time']) / (86400 * 7)) + 1) . "周，第" . intval((time() - $item['data_time']) / (86400)) . "天，【预产期】" . date("Y-m-d", ($item['data_time'] + (86400 * 280)));







      }







    ?>







    </div>







    </td>







  <td style="text-align:left;">







    <?php echo $this->lang->line('order_addtime'); ?>：<font id="order_addtime_<?php echo $item['order_id']; ?>"><?php echo date("Y-m-d H:i:s",$item['order_addtime']) ; ?></font><br />







    <div style="display: none;"><?php echo $this->lang->line('order_time'); ?>：<font id="order_time_<?php echo $item['order_id']; ?>"><?php echo date("Y-m-d H:i:s",$item['order_time']); ?></font> <font style="color:#ff6060; font-weight:bold;" id="order_time_duan_<?php echo $item['order_id']; ?>"><?php if($item['order_time_duan']){ echo $item['order_time_duan'];}?></font><br />







    <?php echo $this->lang->line('come_time'); ?>：<span  id="come_time_<?php echo $item['order_id']; ?>"><?php if($item['come_time'] > 0){ echo date("Y-m-d H:i:s", $item['come_time']);}?></span><br /></div>







    <!--<?php echo $this->lang->line('doctor_time'); ?>：<span id="doctor_time_<?php echo $item['order_id']; ?>"><?php if($item['doctor_time'] > 0){ echo date("Y-m-d H:i:s", $item['doctor_time']);} ?></span>--></td>







  <td>



            <p style="color:red;" id="gonghai_status_<?php echo $item['order_id'];?>"></p>



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



            <a href="#kefu_hf" title="回访记录" id="hf_status_<?php echo $item['order_id'];?>" class="btn btn-info" role="button" data-toggle="modal" onClick="kefu_hf('<?php echo $item['order_id']?>');_czc.push(['_trackEvent', '留联相关', '<?php echo $admin['name']; ?>', '回访记录','','']);">添加回访记录</a><?php if(!empty($item['zampo'])):?></del><?php endif;?>



           <a href="javascript:order_window('?c=order&m=order_info_liulian&type=mi&p=2&order_id=<?php echo $item['order_id'];?>');" class="btn btn-info" onclick="_czc.push(['_trackEvent', '留联列表', '<?php echo $admin['name']; ?>', '<?php echo $this->lang->line('edit'); ?>','','']);"><?php echo $this->lang->line('edit'); ?></a>



           <?php if(empty($item['order_no_yy'])){?>

             <a href="?c=order&m=order_info&order_liulian_id=<?php echo $item['order_id'];?>" class="btn btn-info" onclick="_czc.push(['_trackEvent', '预约列表', '<?php echo $admin['name']; ?>', '添加预约','','']);">添加预约</a>

          <?php  }?>

          <?php $disableAllowList = array(8,12,947,948,2453,1035,2096,224,1882,2963,2537,2930,2945,14,2978,2983,2995,3002,3001);//不孕能设置优先级留联名单 ?>
          <?php $allowRankLists = array(5,21,28,545,549);//能设置优先级留联岗位ID ?>

          <?php if(($item['hos_id'] == 1 && $item['keshi_id'] == 1 && in_array($_COOKIE['l_rank_id'], $allowRankLists)) || ($item['hos_id'] == 1 && $item['keshi_id'] == 32 && in_array($_COOKIE['l_admin_id'], $disableAllowList)) || $_COOKIE['l_admin_action'] == 'all'):?>

            <a href="javascript:;" class="btn set_priority" order_id="<?php echo $item['order_id']?>" order_no="<?php echo $item['order_no']?>" group="<?php echo $item['is_group']?>">设置优先级</a>

          <?php endif; ?>

  </td>







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



<?php echo $page; ?>







<div style="margin-bottom:30px;"></div>







  <div id="visit" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true" style="top:5%;">







    <div class="modal-header">







      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>







      <h3 id="myModalLabel1">回访记录</h3>







    </div>







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



        <label class="control-label">下次联系</label>







        <div class="controls">



          <input type="text" value=""  name="nextdate" id="nextdate" style="width:80px;float:left;"/>



          <div class="input-append" style="margin-left:5px; float:left;">



             <input type="text" name="datehour" id="datehour" style="width:80px;"  value="">



             <div class="btn-group">



              <button data-toggle="dropdown" class="btn dropdown-toggle">



                <span class="caret"></span>



              </button>



              <ul class="dropdown-menu pull-right">



                <li onClick="time_sel(this)"><a href="javaScript:void(0);">08:00</a></li>



                <li onClick="time_sel(this)"><a href="javaScript:void(0);">09:00</a></li>



                <li onClick="time_sel(this)"><a href="javaScript:void(0);">10:00</a></li>



                <li onClick="time_sel(this)"><a href="javaScript:void(0);">11:00</a></li>



                <li onClick="time_sel(this)"><a href="javaScript:void(0);">12:00</a></li>



                <li onClick="time_sel(this)"><a href="javaScript:void(0);">13:00</a></li>



                <li onClick="time_sel(this)"><a href="javaScript:void(0);">14:00</a></li>



                <li onClick="time_sel(this)"><a href="javaScript:void(0);">15:00</a></li>



                <li onClick="time_sel(this)"><a href="javaScript:void(0);">16:00</a></li>



                <li onClick="time_sel(this)"><a href="javaScript:void(0);">17:00</a></li>



                <li class="divider"></li>



                <li onClick="time_clean(this)"><a href="javaScript:void(0);">自定义</a></li>



              </ul>



            </div>



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







    <div class="modal-footer">







      <input type="hidden" name="order_id" id="visit_order_id" value="" />







      <button class="btn" data-dismiss="modal" aria-hidden="true"> 关闭 </button>







      <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onClick="visit_add();"> 提交 </button>







    </div>







  </div>



  <div id="weixin" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">







    <div class="modal-header">







      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>







      <h3>发送微信消息</h3>







    </div>







    <div class="modal-body">







      <div class="control-group">







      <label class="control-label">微信模版</label>







      <div class="controls">







        <select name="weixin_themes" id="weixin_themes" class="input-small m-wrap" style="width:150px;">







                   <option value="0"><?php echo $this->lang->line('please_select');?></option>







                </select>







      </div>







      </div>















            <div class="control-group">







        <label class="control-label">填写要发送的信息</label>







        <div class="controls">







          <textarea class="input-xxlarge " rows="5" name="weixin_remark" id="weixin_remark" style="width:520px;"></textarea>







        </div>







      </div>







    </div>







    <div class="modal-footer">







      <input type="hidden" name="weixin_id" id="weixin_user_id" value="" />



      <input type="hidden" name="order_id" id="weixin_order_id" value="" />







      <button class="btn" data-dismiss="modal" aria-hidden="true"> 关闭 </button>







      <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onClick="weixin_send();"> 提交 </button>







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







<!--          <input type="text" class="input-xxlarge" name="dao_doctor_name" id="dao_doctor_name" value="" style="width:220px;" />-->



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







          <input type="text" class="input-xxlarge" name="doctor_name" id="doctor_name" value="" style="width:220px;" />







        </div>







      </div>







            <div class="control-group">







        <label class="control-label"><?php echo $this->lang->line('notice'); ?></label>







        <div class="controls">







          <textarea class="input-xxlarge" rows="5" name="doctor_remark" id="doctor_remark" style="width:220px;"></textarea>







        </div>







      </div>







    </div>







    <div class="modal-footer">







      <input type="hidden" name="order_id" id="doctor_order_id" value="" />







      <button class="btn" data-dismiss="modal" aria-hidden="true"> 关闭 </button>







      <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onClick="doctor_add();"> 到诊 </button>







    </div>







  </div>























    <div id="page_path" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true" style="width:800px; left:40%;">







    <div class="modal-header">







      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>







      <h3 id="myModalLabel1">患者访问轨迹（ID：<span id="swt_id"></span>）</h3>







    </div>







    <div class="modal-body" id="page_path_body">















    </div>







    <div class="modal-footer">







      <button class="btn" data-dismiss="modal" aria-hidden="true"> 关闭 </button>







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



    <div id="kefu_talk" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true" style="width:80%; left:30%;">

    <div class="modal-header">

      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

      <h3 id="myModalLabel1">对话记录</h3>

    </div>

    <div class="modal-body" id="con_content"></div>

    <div class="modal-footer">

      <button class="btn" data-dismiss="modal" aria-hidden="true"> 关闭 </button>

    </div>

  </div>







  <div id="card_info" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true" style="width:80%; left:30%;">







    <div class="modal-header">







      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>







      <h3 id="myModalLabel1">留联卡信息</h3>







    </div>







    <div class="modal-body" id="card_content">







    </div>















    <div class="modal-footer">



      <input type="hidden" id="card_order_id" value="" />



      <span class="btn btn-success" onclick="reset_card();"> 重新生成</span>



      <button class="btn" data-dismiss="modal" aria-hidden="true"> 关闭 </button>







    </div>







  </div>







    <div id="sms_content" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true" style="width:800px; left:40%;">







    <div class="modal-header">







      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>







      <h3 id="myModalLabel1">短信内容</h3>







    </div>







    <div class="modal-body sms_content" id="sms_content_body">







    </div>







    <div class="modal-footer">







      <button class="btn" data-dismiss="modal" aria-hidden="true"> 关闭 </button>







            <a class="btn btn-primary" href="#sms_send" onClick="sms_send()" role="button" data-toggle="modal" data-dismiss="modal" aria-hidden="true"> 发送短信 </a>







            <input type="hidden" value="" id="sms_order_id" />







    </div>







  </div>







    <div id="sms_reply" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true" style="width:800px; left:40%;">







    <div class="modal-header">







      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>







      <h3 id="myModalLabel1">获取回复短信内容</h3>







    </div>







    <div class="modal-body" id="sms_reply_body">







    </div>



                <div class="modal-body" id="sms_reply_select">



                    <select name="" id="ms_hos" style="width:180px;">







      <option value="1"><?php echo $this->lang->line('hospital_select'); ?></option>







      <?php foreach($hospital as $val):



                            $a=array(1,2,4,5,6,3,9,11,12,13,14,16,20,22,23,24,25,32,34,35);



                            if(in_array($val['hos_id'],$a)){?><option value="<?php echo $val['hos_id']; ?>"><?php echo $val['hos_name']; ?></option><?php }endforeach;?>







    </select>



                    <span><a onclick="sms_reply(2);">获取回复</a></span>



    </div>







    <div class="modal-footer">







      <button class="btn" data-dismiss="modal" aria-hidden="true" onClick="window.location.reload();"> 关闭 </button>







    </div>







  </div>







    <div id="sms_send" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true" style="width:800px; left:40%;">







    <div class="modal-header">







      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>







      <h3 id="myModalLabel1">发送短信</h3>







    </div>







    <div class="modal-body">







          <div class="control-group">







      <div class="controls">







              留联号码：<font id="sms_order_no"></font><br />







              患者姓名：<font id="sms_pat_name"></font><br />







              联系电话：<font id="sms_pat_phone"></font><br />







              登记时间：<font id="sms_order_addtime"></font><br />







              留联时间：<font id="sms_order_time"></font>&nbsp;<font id="sms_order_time_duan"></font>







            </div>







      </div>







          <div class="control-group">







      <label class="control-label"><?php echo $this->lang->line('sms_themes'); ?></label>







      <div class="controls">







        <select name="sms_themes" id="sms_themes" class="input-small m-wrap" style="width:150px;">







                   <option value="0"><?php echo $this->lang->line('please_select');?></option>







                </select>



        &nbsp;&nbsp;&nbsp;<span>短信端口：</span>



        <select name="sms_id" id="sms_id" class="input-small m-wrap" style="width:150px;">







                   <option value="0"><?php echo $this->lang->line('please_select');?></option>







                </select>







      </div>







      </div>







          <div class="control-group">







            <label class="control-label"><?php echo $this->lang->line('sms_content');?></label>







            <div class="controls">







                <textarea class="input-xxlarge " rows="5" name="sms_content_area" id="sms_content_area" style="width:600px;"></textarea><br />请确认短信结尾有签名，否则发送失败！







            </div>







        </div>







    </div>







    <div class="modal-footer">







      <button class="btn" data-dismiss="modal" aria-hidden="true"> 关闭 </button>







            <button class="btn btn-primary" onClick="sms_send_ok();"> 立即发送 </button>







    </div>







  </div>







</div>







</div>







</div>







</div>







</div>



<div id="fade" class="black_overlay">



</div>







          <div class="widget green white_content" id="MyDiv">



              <div class="widget-title">



              <h4><i class="icon-reorder"></i> 生成留联卡</h4>



              <span class="tools">











                <span style="font-size: 14px;line-height:12px;color:#fff;cursor: pointer;" onclick="CloseDiv('MyDiv','fade')">关闭</span>



              </span>



              </div>



              <div class="widget-body" id="wx_order">







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




<script language="javascript">


$(function(){

  $(".set_priority").live('click',function(){

    var _that = $(this);
    var order_id = _that.attr('order_id');
    var order_no = _that.attr('order_no');
    var group = _that.attr('group');
    var options,groupText;
    if (group == 1) {
      options = "<option value=\"1\" selected>A组（高意向，高质量患者）</option><option value=\"2\">B组（有意向，中等质量患者）</option><option value=\"3\">C组（意向低，低质量患者）</option><option value=\"4\">D组（无意向，无效患者）</option>";
    } else if (group == 2) {
      options = "<option value=\"1\">A组（高意向，高质量患者）</option><option value=\"2\" selected>B组（有意向，中等质量患者）</option><option value=\"3\">C组（意向低，低质量患者）</option><option value=\"4\">D组（无意向，无效患者）</option>";
    } else if (group == 3) {
      options = "<option value=\"1\">A组（高意向，高质量患者）</option><option value=\"2\">B组（有意向，中等质量患者）</option><option value=\"3\" selected>C组（意向低，低质量患者）</option><option value=\"4\">D组（无意向，无效患者）</option>";
    } else if (group == 4) {
      options = "<option value=\"1\">A组（高意向，高质量患者）</option><option value=\"2\">B组（有意向，中等质量患者）</option><option value=\"3\">C组（意向低，低质量患者）</option><option value=\"4\" selected>D组（无意向，无效患者）</option>";
    }

    var __HTML = "<div style=\"padding:20px;\"><select id=\"pat_group\" style=\"width:100%;\">"+options+"</select></div>";

    layer.open({
      type: 1,
      title:'<b>'+order_no+'</b> 优先级设置',
      skin: 'layui-layer-rim', //加上边框
      area: ['420px', '180px'], //宽高
      content: __HTML,
      btn: ['确定'],
      yes: function(index, layero){
        var pat_group = parseInt($('#pat_group').val());

        layer.close(layer.index);

        $.ajax({
          type:'post',
          url:'?c=order&m=ajax_set_priority',
          dataType:'json',
          data:{order_id:order_id,pat_group:pat_group},
          success:function(data){
            if (data.code == 1) {
              _that.attr('group',data.group);
              $('#ll_group_' + order_id).text(data.groupText);
              layer.msg(data.msg, {icon: 6,time:1000});
            } else if (data.code == 2) {
              layer.msg(data.msg, {icon: 5,time:1000});
            }
          }
        });

      }
    });

  });
})





var dao_false_arr = new Array();







<?php







foreach($dao_false_arr as $key=>$val)







{







  echo "dao_false_arr[$key] = \"$val\";\r\n";







}







?>







$(document).ready(function(e) {



       /**2016 10 27 新增加表头 下拉框效果**/



        $(".select_type").click(function(){







        $("#appointment_disease_class").html($("#appointment_disease_value").val());



        $("#appointment_section_class").html($("#appointment_section_value").val());



        $("#appointment_route_class").html($("#appointment_route_value").val());



        $("#select_type_class").html($("#select_type_value").val());



        $("#consult_a_doctor_class").html($("#consult_a_doctor_value").val());







          var strs= new Array(); //定义一数组



        if($(this).attr("title") == ''){







          $(this).parent().parent().find("a").first().html($(this).text());



            $(".list_table tbody").find("tr").each(function(){



             $(this).show();



          });



          }else if($(this).attr("title").indexOf("~") > 0){



          $(this).parent().parent().find("a").first().html($(this).text());



          strs=$(this).attr("title").split("~"); //字符分割



          $(".list_table tbody").find("tr").each(function(){



             if(parseInt($(this).attr("title")) >= strs[0] && parseInt($(this).attr("title")) <= strs[1]){



               $(this).show();



             }else{



               $(this).hide();



               }



          });



          }else{



          $(this).parent().parent().find("a").first().html($(this).text());



          if($(this).attr("title").indexOf(",") > 0){



            strs=$(this).attr("title").split(","); //字符分割



          }else{



            strs[0] = $(this).attr("title");



          }



          $(".list_table tbody").find("tr").each(function(){



             var check_no =0 ;



            for (i=0;i<strs.length ;i++ )



            {



              if( $(this).attr("id") == strs[i]){



                 check_no =1;break;



              }



            }



            if(check_no == 0){



              $(this).hide();



            }else{



              $(this).show();



            }



          });



        }



          });















        $("#gaoji").hide();







          $("#gaoji_search").click(function(){



              $(".date_div").hide();



                 if($("#gaoji").css("display")=="block"){







                     $("#search_pic").attr("src","static/img/dy_lists.png");



                 }else{



                      $("#search_pic").attr("src","static/img/dy_f.png");



                 }



                 $("#gaoji").toggle(10);



             });



         //点击表单高级搜索隐藏



         $("#tab1").click(function(){



              $("#gaoji").hide();



              $("#search_pic").attr("src","static/img/dy_lists.png");



         });



         $(".input_search").click(function(){



              $(".date_div").hide();







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



//            $("#dy_fast").parent().parent().removeClass("error");



                          $("#dy_fast").next("span").remove();



            $("#dy_fast").after("<i></i>");



            if(data['order'] != "")



            {







              var html = '&nbsp;<div class="btn-group" style="width:1000px;padding-left:20%"><button data-toggle="dropdown" class="btn btn-danger dropdown-toggle">当前患者已留联 <span class="caret"></span></button><ul class="dropdown-menu" style="width:900px;background-color:#eee;">';



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



                                                        });}



              html += '</ul></div>';



              $("#dy_fast").after(html);



            }







                      }else if(type==2){







//                          $("#dy_fast").next("i").remove();



            $("#dy_fast").next("span").remove();



//            $("#dy_fast").parent().parent().removeClass("error");



            $("#dy_fast").after("<span style='color:red;margin-left:30px;'>查找不到相关数据</span>");







                      }else if(type==3){







//                          $("#dy_fast").next("i").remove();



            $("#dy_fast").next("span").remove();



//            $("#dy_fast").parent().parent().removeClass("error");



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







        $(this).css({height:"auto", background:"#f7f7f7", "z-index":"999", "padding-bottom":"50px"});







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







//  $('#sidebar > ul').hide();



//



//  $("#container").addClass("sidebar-closed");















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







  $("#hos_id").change(function(){







    var hos_id = $(this).val();







    ajax_get_keshi(hos_id, 0);





    ajax_get_form(hos_id,0);



  });















  $("#keshi_id").change(function(){







    var keshi_id = $(this).val();







    ajax_get_jibing(keshi_id, 0, 0);







     /**



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



    }**/



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







        /* ajax 判断短信内容是否为空 */



        //ajax_talk_isnull(order_id);



        /* ajax 获取对话数据是否为空 */







            //ajax_is_gonghai(order_id);



            ajax_sms_isnull(order_id);



            ajax_talk_isnull(order_id);



<?php if($hos_id > 0):?>







ajax_get_keshi(<?php echo $hos_id?>, <?php echo $keshi_id?>);







<?php endif;?>







<?php if($f_p_i > 0):?>







ajax_from(<?php echo $f_p_i?>, <?php echo $f_i?>);







<?php endif;?>




});


<?php if($pro > 0):?>

ajax_area('city', <?php echo $pro; ?>, <?php if(isset($city)){echo $city;}else{echo 0;}?>, 3);

<?php endif;?>







<?php if(isset($city)):?>

ajax_area('area', <?php echo $city; ?>, <?php if(isset($are)){echo $are;}else{echo 0;}?>, 3);

<?php endif;?>












<?php if($p_jb > 0):?>







ajax_get_jibing(0, <?php echo $p_jb?>, <?php echo $jb?>);







<?php endif;?>



    /*判断订单是否为公海的函数*/



function ajax_is_gonghais(order_id){















  if(order_id == ",")







  {







    $("p[id^='gonghai_status_']").html("(gonghai)");















  }















  $.ajax({







    type:'post',







    url:'?c=order&m=ajax_is_gonghai',







    data:'order_id=' + order_id,







    success:function(data)







    {







      data = $.parseJSON(data);



                        $.each(data,function(i,item){



                       //$("#gonghai_status_"+item.order_id).html("( 公海 )");



                   });







    },







    complete: function (XHR, TS)







    {







       XHR = null;







    }







  });







    }



/* 判断短信数据是否为空的ajax */



function ajax_sms_isnull(order_id){



















  if(order_id == ",")







  {







    $("a[id^='sms_status_']").html("<img src='static/img/xinxi_g.png' width='16px;'/>");















  }















  $.ajax({







    type:'post',







    url:'?c=order&m=ajax_sms_isnull',







    data:'order_id=' + order_id,







    success:function(data)







    {







      data = $.parseJSON(data);



                        $.each(data,function(i,item){



                       $("#sms_status_"+item.type_value).html("<img src='static/img/xinxi.png' width='16px;'/>");



                   });







    },







    complete: function (XHR, TS)







    {







       XHR = null;







    }







  });











}



//判断对话数据是否为空函数



function ajax_talk_isnull(order_id){



   if(order_id == ",")







  {













  }















  $.ajax({







    type:'post',







    url:'?c=order&m=ajax_talk_isnull',







    data:'order_id=' + order_id,







    success:function(data)







    {







      data = $.parseJSON(data);



                        $.each(data,function(i,item){



                       $("#talk_status_"+item.order_id).html("<img src='static/img/talk.png' width='16px;'/>");



                   });







    },







    complete: function (XHR, TS)







    {







       XHR = null;







    }







  });











}











/* 判断短信数据是否为空的ajax */







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



                                if(item.send_type == 4){



                                  html += item.admin_name + "</a>&nbsp;&nbsp;<cite>" + item.send_time + "</cite>&nbsp;&nbsp;群发短信<i>【" + item.status +"】</i></small>";



                                }else{



        html += item.admin_name + "</a>&nbsp;&nbsp;<cite>" + item.send_time + "</cite>&nbsp;&nbsp;<i>【" + item.status +"】</i></small>";



                            }



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



var hos_id=$("#ms_hos").val();



  if(type == 2)







  {







    $("#sms_reply_body").html($("#sms_reply_body").html() + "&nbsp;<i class='icon-refresh icon-spin'></i>");







  }







  $.ajax({







    type:'post',







    url:'?c=order&m=sms_reply',







    data:'type=' +type+"&hos_id="+hos_id,







    success:function(data)







    {







      if(type == 1)







      {







        $("#sms_reply_body").html("最后获取回复时间是：" + data);







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





function kefu_talk(order_id){

  if(order_id >= 1){

    $.ajax({

      type:'post',

      url:'?c=order&m=talk_info_liulian',

      data:'order_id=' + order_id,

      success:function(data){

        $("#con_content").html(data);

      },

      complete: function (XHR, TS){

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







    window.location.href = "http://www.renaidata.com/?c=order&m=order_list_liulian";







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







          str += "<blockquote><p style='font-size:12px;'>" + item.mark_content;







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







function ajax_weixin(order_id)



{







    $.ajax({







      type:'post',







      url:'?c=order&m=get_info_by_id',







      data:'order_id=' + order_id ,







      success:function(data)







      {



        var html = '<h4>留联卡地址：';



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



