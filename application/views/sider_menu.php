<div class="sidebar-scroll" style="background-color:#2f3640;">
    <div id="sidebar" class="nav-collapse collapse"style="background-color:#2f3640;" >
	<ul class="sidebar-menu" style="background-color:#1a212b;" >
		<?php
		foreach($menu as $item):
		if($item['is_show'] == 1):
		?>
		<li class="sub-menu <?php if($item['act_action'] == $menu_here['parent']['act_action']){ echo " active";}?>">
			<a href="<?php if(empty($item['act_url'])){ echo "javascript:;"; } else { echo $item['act_url']; }?>" class="">
				<i class="<?php echo "icon-" . $item['act_action'];?>"></i>
				<span ><?php echo $item['act_name']?></span>
				<?php if(!empty($item['son'])):?><span class="arrow"></span><?php endif; ?>
			</a>
			<?php if(!empty($item['son'])):?>
			<ul class="sub">
				<?php
				foreach($item['son'] as $list):
				if($list['is_show'] == 1):
				?>
				<li<?php if($list['act_action'] == $menu_here['parent']['son']['act_action']){ echo " class='active'";}?>><a class="" href="<?php echo $list['act_url']; ?>" onclick="_czc.push(['_trackEvent', '左侧菜单', '<?php echo $admin['name']; ?>', '<?php echo $list['act_name']; ?>','','']);"><?php echo $list['act_name']; ?></a></li>
				<?php
				endif;
				endforeach;
				?>
			</ul>
			<?php endif; ?>
	   </li>
	  <?php
	  endif;
	  endforeach;
	  ?>
  </ul>
</div>
</div>
<!-- <div style="display: none;"><script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1254115876'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s11.cnzz.com/stat.php%3Fid%3D1254115876' type='text/javascript'%3E%3C/script%3E"));</script></div> -->