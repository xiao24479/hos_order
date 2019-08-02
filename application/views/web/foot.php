<div style="height:49px;width:100%;"></div>
<div data-am-widget="navbar" class="am-navbar am-cf am-navbar-default " id="" >
  <ul class="am-navbar-nav am-cf am-avg-sm-4">
    <li> <a href="tel:0755-88308188"> <span class="am-icon-phone"></span> <span class="am-navbar-label">呼叫</span> </a> </li>
    <li> <a href="<?php echo $code_url;?>"> <span class="am-icon-heart"></span> <span class="am-navbar-label">预约挂号</span> </a> </li>
    <li> <a href="?c=show&m=get_all_docter"> <span class="am-icon-cloud"></span> <span class="am-navbar-label">在线医生</span> </a> </li>
    <li> <a href="<?php if(isset($_COOKIE['u_openid'])):?>?c=show&m=get_ditu<?php else:?>?c=show&m=view&slug=<?php echo $ditu; endif;?>"> <span class="am-icon-location-arrow"></span> <span class="am-navbar-label">医院地址</span> </a> </li>
	<?php if(!empty($user_url)):?><li> <a href="<?php echo $user_url;?>"> <span class="am-icon-cloud"></span> <span class="am-navbar-label">个人中心</span> </a> </li><?php endif;?>
 </ul>
</div>
<!--商务通链接-->
<a id="QIAO_ICON_CONTAINER" href="<?php echo $swt_url; ?>">在线咨询</a>
<style>
#QIAO_ICON_CONTAINER {filter:alpha(opacity=40);-moz-opacity:0.4;opacity:0.4;position: fixed;text-decoration: none;display: block !important;z-index: 2147483647;background-color:#0E90D2;;color: #fff;top: 65.5%; font-size: 14px; left: auto; right: 5px;line-height: 1.3;width: 2em; padding: 0.5em;cursor: pointer; border-radius: 0.3em;
}
</style>
<?php require_once 'cs.php';echo '<img src="'._cnzzTrackPageView(1254115876).'" width="0" height="0"/>';?>