
<script>
    //声明_czc对象:
var _czc = _czc || [];
//绑定siteid，请用您的siteid替换下方"XXXXXXXX"部分
_czc.push(["_setAccount", "1254115876"]);
_czc.push(["_setCustomVar","<?php echo $_SERVER["REMOTE_ADDR"]; ?>","<?php echo $admin['name']; ?>",1]);
</script>
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



</script>
<!-- End Google Tag Manager -->
<!--<script type="text/javascript">
var url = window.location.href;
                if (url.indexOf("https") < 0) {
                    url = url.replace("http:", "https:");
                    window.location.replace(url);
                }
</script> -->
<div id="header" class="navbar navbar-inverse navbar-fixed-top">
       <!-- BEGIN TOP NAVIGATION BAR -->
       <div class="navbar-inner">
           <div class="container-fluid">
               <!--BEGIN SIDEBAR TOGGLE-->
               <div class="sidebar-toggle-box hidden-phone">
                   <div class="icon-reorder"></div>
               </div>
               <!--END SIDEBAR TOGGLE-->
               <!-- BEGIN LOGO -->
               
               <a class="brand" href="/">
                   <img src="static/images/logo.png" alt="Metro Lab" />
               </a>
               <!-- END LOGO -->
               <!-- BEGIN RESPONSIVE MENU TOGGLER -->
               <a class="btn btn-navbar collapsed" id="main_menu_trigger" data-toggle="collapse" data-target=".nav-collapse">
                   <span class="icon-bar"></span>
                   <span class="icon-bar"></span>
                   <span class="icon-bar"></span>
                   <span class="arrow"></span>
               </a>
               <!-- END RESPONSIVE MENU TOGGLER -->
               <div id="top_menu" class="nav notify-row">
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
							  <a href="###" <?php if(!empty($item['act_url'])){ echo "onclick=\"go_url('" . $item['act_url'] . "')\""; }?> class="dropdown-toggle" data-toggle="dropdown"><i class="<?php echo "icon-" . $item['act_action'];?>"></i></a>
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
                   </ul>
               </div>
               <!-- END  NOTIFICATION -->
               <div class="top-nav ">
                   <ul class="nav pull-right top-menu" >
                       <!-- BEGIN SUPPORT -->
                       <li class="dropdown mtop5">
                           <a class="dropdown-toggle element" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="Chat" onclick="_czc.push(['_trackEvent', '右上菜单', '<?php echo $admin['name']; ?>', 'Chat','','']);">
                               <i class="icon-comments-alt"></i>
                           </a>
                       </li>
                       <li class="dropdown mtop5">
                           <a class="dropdown-toggle element" target="_blank" data-placement="bottom" data-toggle="tooltip" href="http://wpa.qq.com/msgrd?v=3&uin=67051228&site=qq&menu=yes" data-original-title="Help">
                               <i class="icon-headphones"></i>
                           </a>
                       </li>
                       <!-- END SUPPORT -->
                       <!-- BEGIN USER LOGIN DROPDOWN -->
                       <li class="dropdown">
                           <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                               <img src="<?php echo $admin['img']; ?>" width="29" height="29" alt="<?php echo $admin['name']; ?>" />
                               <span id="username" class="username"><?php echo $admin['name']; ?></span>
                               <b class="caret"></b>
                           </a>
                           <ul class="dropdown-menu extended logout">
                               <li><a href="?c=index&m=my_profile" onclick="_czc.push(['_trackEvent', '右上菜单', '<?php echo $admin['name']; ?>', '<?php echo $this->lang->line('my_profile'); ?>','','']);"><i class="icon-user"></i> <?php echo $this->lang->line('my_profile'); ?></a></li>
							   <li><a href="?c=index&m=my_setting" onclick="_czc.push(['_trackEvent', '右上菜单', '<?php echo $admin['name']; ?>', '<?php echo $this->lang->line('my_settings'); ?>','','']);"><i class="icon-cog"></i> <?php echo $this->lang->line('my_settings'); ?></a></li>
                               <li><a href="?c=index&m=logout" onclick="_czc.push(['_trackEvent', '右上菜单', '<?php echo $admin['name']; ?>', '<?php echo $this->lang->line('logout'); ?>','','']);"><i class="icon-key"></i> <?php echo $this->lang->line('logout'); ?></a></li>
                           </ul>
                       </li>
                       <!-- END USER LOGIN DROPDOWN -->
                   </ul>
                   <!-- END TOP NAVIGATION MENU -->
               </div>
           </div>
       </div>
       <!-- END TOP NAVIGATION BAR -->
   </div>