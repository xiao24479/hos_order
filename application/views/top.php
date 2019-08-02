<!DOCTYPE html>

<!--

To change this license header, choose License Headers in Project Properties.

To change this template file, choose Tools | Templates

and open the template in the editor.

-->

<script type="text/javascript" src="static/js/js_window/zDrag.js"></script>

<script type="text/javascript" src="static/js/js_window/zDialog.js"></script>

<script>

function open3()

{

	var diag = new Dialog();

	diag.Width = 1200;

	diag.Height = 600;

	diag.Title = "四维排期表";

	diag.URL = "?c=order&m=siwei_show_window";

	diag.show();

}



//账户登录的需要弹出提示框

<?php if(isset($_COOKIE["l_login_weixin_check"]) && !empty($_COOKIE["l_login_weixin_check"])){?>



/***

var diag = new Dialog();

diag.Width = 600;

diag.Height = 380;

diag.Title = "微信登录提醒";

diag.URL = "?c=index&m=weixinlogin_msg";

diag.show();  

***/

<?php 

setcookie('l_login_weixin_check',0, $cookie_time, "/"); 

}?>



</script>

<div id="header" class="navbar navbar-inverse navbar-fixed-top" >

       <!-- BEGIN TOP NAVIGATION BAR -->

       <div class="navbar-inner">

           <div class="container-fluid">

               <!--BEGIN SIDEBAR TOGGLE-->

               <div style=" position: fixed;width:70px;height:60px;margin-left:-18px;cursor: pointer;background-color: " class="nav_menu">

                   &nbsp;

               </div>

               

               <div style=" position: fixed;width:360px;height:60px;margin-left:60px;cursor: pointer; " onclick="index();">

                   &nbsp;

               </div>

               <!--END SIDEBAR TOGGLE-->

               <!-- BEGIN LOGO -->

<!--               

               <a class="brand" href="/">

                   <img src="static/images/logo.png" alt="Metro Lab" />

               </a>-->

               <!-- END LOGO -->

               <!-- BEGIN RESPONSIVE MENU TOGGLER -->

<!--               <a class="btn btn-navbar collapsed" id="main_menu_trigger" data-toggle="collapse" data-target=".nav-collapse">

                   <span class="icon-bar"></span>

                   <span class="icon-bar"></span>

                   <span class="icon-bar"></span>

                   <span class="arrow"></span>

               </a>-->

               <!-- END RESPONSIVE MENU TOGGLER -->

               <div id="top_menu" class="nav notify-row" style="margin-left:600px;">

                   <!-- BEGIN NOTIFICATION -->

                   <ul class="nav top-menu">

                       <!-- BEGIN SETTINGS -->

					   <?php

                       foreach($menu as $item):

                       if($item['is_show'] == 1):

                       ?>

                       <li class="dropdown">

							<?php if($item['act_id']==41):?>

							<a href="###" <?php if(!empty($item['act_url'])){ echo "onclick=\"go_url('" . $item['act_url'] . "')\""; }?> class="dropdown-toggle" data-toggle="dropdown"><?php if(isset($mes_count)):?><span class="badge badge-important"><?php echo $mes_count;?></span><?php endif;?><i class="<?php echo "icon-" . $item['act_action'];?>"></i></a>

							<?php else:?>

							  <!-- <a href="###" <?php if(!empty($item['act_url'])){ echo "onclick=\"go_url('" . $item['act_url'] . "')\""; }?> class="dropdown-toggle" data-toggle="dropdown"><i class="<?php echo "icon-" . $item['act_action'];?>"></i></a> -->

							<?php endif;?>

						  <ul class="dropdown-menu extended tasks-bar">

                               <li><p><?php echo $item['act_name']?></p></li>

                               <?php

							   if(!empty($item['son'])):

							   foreach($item['son'] as $list):

							   if($list['is_show'] == 1):

									if($list['act_id']==123):

							   ?>

									<li class="child"><a href="<?php echo $list['act_url']; ?>" onclick="_czc.push(['_trackEvent', '顶部菜单', '<?php echo $admin['name']; ?>', '<?php echo $list['act_name']; ?>','','']);"><?php echo $list['act_name']; ?><span style="color:red;">(<?php echo $mes_count;?>)</span></a></li>

                               <?php

									else:

								?>

									<li class="child"><a href="<?php echo $list['act_url']; ?>" onclick="_czc.push(['_trackEvent', '顶部菜单', '<?php echo $admin['name']; ?>', '<?php echo $list['act_name']; ?>','','']);"><?php echo $list['act_name']; ?></a></li>

								<?php

									endif;

							   endif;

							   endforeach;

							   endif;

							   ?>

                           </ul>

                       </li>

                      <?php

					  endif;

					  endforeach;

					  ?>

                       <!-- END SETTINGS -->

                    <?php 

                    $aa=array(1,11,36,38);

                    $hos_id=isset($_COOKIE['l_hos_id'][0])?$_COOKIE['l_hos_id'][0]:1;

                   if(in_array($hos_id,$aa)){

                    

                    ?>

                       <li class="child"><a  onclick="open3()"style="cursor:pointer;">四维排期表</a></li>

                   <?php }else{

                       

                    echo '';   

                   }?>

                   

                    <?php 

				    $hos_id_check=1;

                    $ks_id_check=19;

                    $hos_id=isset($_COOKIE['l_hos_id'])?$_COOKIE['l_hos_id']:1;

					$keshi_id=isset($_COOKIE['l_keshi_id'])?$_COOKIE['l_keshi_id']:0;  

                   if(in_array($hos_id_check,explode(',',$hos_id)) && in_array($ks_id_check,explode(',',$keshi_id))){ ?>

                       <li class="child"><a  href="?c=order&m=baby_show_window" style="cursor:pointer;">宝宝游泳排期表</a></li>

                   <?php }else{

                       

                    echo '';   

                   }?>

                   

                   </ul>

               </div>

               <!-- END  NOTIFICATION -->

               <div class="top-nav ">

                  

                       <!-- BEGIN SUPPORT -->

<!--                       <li class="dropdown mtop5">

                           <a class="dropdown-toggle element" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="Chat" onclick="_czc.push(['_trackEvent', '右上菜单', '<?php echo $admin['name']; ?>', 'Chat','','']);">

                               <i class="icon-comments-alt"></i>

                           </a>

                       </li>-->

<!--                       <li class="dropdown mtop5">

                           <a class="dropdown-toggle element" target="_blank" data-placement="bottom" data-toggle="tooltip" href="http://wpa.qq.com/msgrd?v=3&uin=67051228&site=qq&menu=yes" data-original-title="Help">

                               <i class="icon-headphones"></i>

                           </a>

                       </li>-->

                       <!-- END SUPPORT -->

                       <!-- BEGIN USER LOGIN DROPDOWN -->

                    

                       <label style="margin-left: 80%;margin-top:15px;">

                           

<!--                           <span id="message" style="position:fixed;top:40px;left:60%;z-index: 2000;width:400px;background-color:#f0ffff;display:none;border: 3px solid #D8BFD8;">

                               <span style="padding-left:10;width:390px;height:150px;"><p style="text-align:left;color:red;background-color:#8FBC8F;height:30px;line-height: 30px;">

                                       <span>最新消息</span>

                                       <span id="close" style="float:right;margin-top: 0px;"><img src="static/img/close.png" width="22" style="margin-top: -4px;"/></span></p>

                                   <p style="text-align:center;color:#FF6347;">

                                       <span id="sys_mes_title"><?=isset($no_read[0]['message_title'])?$no_read[0]['message_title']:'暂无未读消息'?></span></p>

                                      

                                   <div style="color:#FF6347;width: 390px;margin-top: 5px;margin-left: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="sys_mes_content" style="width:390px;margin-left: 5px;"><?=isset($no_read[0]['message_content'])?$no_read[0]['message_content']:''?></span></div></span>

                               <span style="color:#aaa; "><hr style="margin-bottom:3px;margin-top: 3px;"/>

                                   <span id="no_read" style="text-align:center;"><span style="width:400px;;">未读消息&nbsp;&nbsp;&nbsp;&nbsp;<span id="png" style="width:300px;"><img src="static/img/dy_lists.png" height="12px"/></span></span>

                                      

                                       <table style="width:400px;"><tr><td>编号</td><td>消息标题</td><td>发布时间</td><td>操作</td></tr>

                                           <?php 

                                           if(empty($no_read)){

                                               

                                               echo "<tr><td colspan='4'> 暂无未读消息！</td></tr>";

                                           }else{

                                           foreach($no_read as $val){?>

                                        

                                          

                                           <tr><td><?=$val['message_id']?></td><td><?=$val['message_title']?></td><td><?=date("Y年m月d日",$val['message_time'])?></td><td><a onclick="read_mes(<?=$val['message_id']?>);">阅读</a></td>

                                          <?php }

                                           }?>

                                       </table>

                                   </span><hr style="margin-top:3px;margin-bottom: 3px;"/>

                                       <span id="is_read" style="width:400px;text-align:center;"><span style="width:400px;;">已读消息&nbsp;&nbsp;&nbsp;&nbsp;<span id="png_r"><img src="static/img/dy_lists.png" height="12px"/></span></span>

                                     <table style="width:400px;"><tr><td>编号</td><td>消息标题</td><td>发布时间</td><td>操作</td></tr>

                                           <?php if(empty($is_read)){

                                               

                                               echo "<tr><td colspan='4'> 暂无已读消息！</td></tr>";

                                           }else{

                                           foreach($is_read as $val){?>

                                        

                                          

                                         <tr><td><?=$val['message_id']?></td><td><?=$val['message_title']?></td><td><?=date("Y年m月d日",$val['message_time'])?></td><td><a onclick="read_mes(<?=$val['message_id']?>);">阅读</a></td>

                                           <?php }}?>

                                       </table>

                                 

                                 

                                 </span>

                               </span>

                           </span>

                           -->

                           

                           

                           <span style="margin-right:30px;"><a href="?c=sys_message&m=mess_list" style="color:#fff;cursor: pointer;">系统消息<img src="static/img/news.png" width="18px"/></a><span class="badge badge-important" id="mes_num" style=" position: fixed;margin-top:-8px;margin-left:-15px;"><a href="?c=sys_message&m=mess_list" style="color:#fff;cursor: pointer;"><?=$no_read_count?></a></span></span><img src="static/img/people.png" width="30px;"/><a href="?c=index&m=my_profile" style="color:#fff;padding-left: 8px;"><?php echo $_COOKIE['l_admin_name'];?></a><a href="?c=index&m=logout" style="color:red;padding-left: 8px;"><img src="static/img/logout.png"/></a></label> 

                       

                       <!-- END USER LOGIN DROPDOWN --> 

                  

                   <!-- END TOP NAVIGATION MENU -->

               </div>

           </div>

       </div>

       <!-- END TOP NAVIGATION BAR -->

   </div>

   <script src="static/js/jquery-1.8.3.min.js"></script>

                           <script type="text/javascript">

                               

                               function index(){

                                   

                                   window.location.href="?c=index&m=index";

                               }

//                               $("#message").hide();

//                               $("#no_read table").hide();

//                               $("#is_read table").hide();

//                           $("#no_read").toggle(

//                                   function(){

//                              $("#no_read table").show();

//                              $("#png img").remove();

//                             $("#png").append("<img src='static/img/dy_f.png' height='12px'/>");

//                               

//                           },function(){

//                                    

//                               $("#no_read table").hide();

//                               $("#png img").remove();

//                               $("#png").append("<img src='static/img/dy_lists.png' height='12px'/>");

//                           });

//                            $("#is_read").toggle(

//                                   function(){

//                              $("#is_read table").show();

//                              $("#png_r img").remove();

//                             $("#png_r").append("<img src='static/img/dy_f.png' height='12px'/>");

//                               

//                           },function(){

//                                    

//                               $("#is_read table").hide();

//                               $("#png_r img").remove();

//                               $("#png_r").append("<img src='static/img/dy_lists.png' height='12px'/>");

//                           });

//                           $("#close").click(function(){

//                               

//                               $("#message").hide();

//                               

//                           });

//                           $("#mes_num").click(function(){

//                               

//                               $("#message").show();

//                               

//                           });

//                           

//                           function read_mes(message_id){

//                             var mes_id=message_id;  

//                               $.ajax({

//                                   type:'post',

//                                   url:'?c=sys_message&m=read_mes_ajax',

//                                   data:'message_id='+mes_id,

//                                   success:function(data){

//                                       data = $.parseJSON(data);

//                                    $("#sys_mes_title").html(data.title);

//                                    $("#sys_mes_content").html(data.content);

//                                       

//                                   }

//                                       

//                

//                

//                

//                               });

//                               

//                               

//                               

//                           }

//                           

                           

                           </script>