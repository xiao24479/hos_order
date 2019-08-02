<header data-am-widget="header" class="am-header am-header-default">
  <div class="am-header-left am-header-nav">
    <a href="?c=show" class="" data-am-offcanvas="{target: '#doc-oc-demo2', effect: 'push'}">
      
      切换医院
    </a>
  </div>
  <h1 class="am-header-title">
    <a href="#title-link"><?php echo $hos_data[$hos_id];?></a>
  </h1>
  <script>
	var flag = true;
	
	$(document).on('click',function(e){
		
		$('#wx_menu > li').on('click',function(e){
			flag = false;
		});
		
		if(flag && ($("#wx_menu").hasClass("am-in"))){
			$("#wx_menu").removeClass('am-in');
		}
		flag = true;
	});
  </script>
  
  
  <!--菜单开始-->
  <nav data-am-widget="menu" class="am-menu  am-menu-dropdown1" data-am-menu-collapse> <a href="javascript: void(0)" class="am-menu-toggle"> <i class="am-menu-toggle-icon am-icon-list"></i> 菜单</a>
      
    <ul  id="wx_menu" class="am-menu-nav am-avg-sm-1 am-collapse">
        <li><a href="?c=show&m=order_data&hos_id=<?php echo $hos_id;?>">每日概览</a></li>
  <li><a href="?c=show&m=order_data_type&hos_id=<?php echo $hos_id;?>">日数据对比</a></li>
  <li><a href="?c=show&m=order_data_type&type=zhou&hos_id=<?php echo $hos_id;?>">周数据对比</a></li>
  <li><a href="?c=show&m=order_data_type&type=yue&hos_id=<?php echo $hos_id;?>">月数据对比</a></li>
      
    </ul>
  </nav>
  <!--菜单结束--> 
</header>