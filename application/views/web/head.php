<header data-am-widget="header" class="am-header am-header-default">
  <div class="am-header-left am-header-nav">
    <a href="?c=show" class="">
      <i class="am-header-icon am-icon-home"></i>
    </a>
  </div>
  <h1 class="am-header-title">
    <a href="#title-link"><?php echo $hos_name;?></a>
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
  <nav data-am-widget="menu" class="am-menu  am-menu-dropdown1" data-am-menu-collapse> <a href="javascript: void(0)" class="am-menu-toggle"> <i class="am-menu-toggle-icon am-icon-list"></i> </a>
    <ul  id="wx_menu" class="am-menu-nav am-avg-sm-1 am-collapse">
      <li class="am-parent"> <a>医院</a>
        <ul class="am-menu-sub am-collapse  am-avg-sm-2 ">
          <li class=""> <a href="?c=show&m=cate&mid=56">医院概况</a> </li>
          <li class=""> <a href="?c=show&m=cate&mid=57">医院动态</a> </li>
          <li class=""> <a href="?c=show&m=cate&mid=141">就医指南</a> </li>
         <!-- <li class=""> <a href="?c=show&m=cate&mid=142">科室介绍</a> </li>-->
        </ul>
      </li>
      <li class="am-parent"> <a>微创妇科</a>
        <ul class="am-menu-sub am-collapse  am-avg-sm-3 ">
          <li><a href="?c=show&m=cate&mid=52" target="_parent">妇科炎症</a></li>
          <li><a href="?c=show&m=cate&mid=55" target="_parent">宫颈疾病</a></li>
          <li><a href="?c=show&m=cate&mid=54" target="_parent">子宫肌瘤</a></li>
          <li><a href="?c=show&m=cate&mid=53" target="_parent">月经疾病</a></li>
          <li><a href="?c=show&m=cate&mid=58" target="_parent">私密整形</a></li>
          <li><a href="?c=show&m=cate&mid=59" target="_parent">卵巢囊肿</a></li>
        </ul>
      </li>
      <li class="am-parent"> <a>计划生育</a>
        <ul class="am-menu-sub am-collapse  am-avg-sm-4 ">
          <li><a href="?c=show&m=cate&mid=86" target="_blank">无痛人流</a></li>
          <li><a href="?c=show&m=cate&mid=88" target="_blank">早孕检查</a></li>
          <li><a href="?c=show&m=cate&mid=89" target="_blank">宫外孕</a></li>
          <li><a href="?c=show&m=cate&mid=87" target="_blank">上环</a></li>
          <li><a href="?c=show&m=cate&mid=90" target="_blank">取环</a></li>
          <li><a href="?c=show&m=cate&mid=108" target="_blank">避孕常识</a></li>
        </ul>
      </li>
	  <li class="am-parent"> <a>不孕不育</a>
        <ul class="am-menu-sub am-collapse  am-avg-sm-5 ">
          <li><a href="?c=show&m=cate&mid=92" target="_blank">女性不孕</a></li>
          <li><a href="?c=show&m=cate&mid=93" target="_blank">不孕检查</a></li>
          <li><a href="?c=show&m=cate&mid=95" target="_blank">不孕治疗</a></li>
          <li><a href="?c=show&m=cate&mid=124" target="_blank">不孕案例</a></li>
          <li><a href="?c=show&m=cate&mid=126" target="_blank">不孕预防</a></li>
          <li><a href="?c=show&m=cate&mid=94" target="_blank">男性不育</a></li>
          <li><a href="?c=show&m=cate&mid=96" target="_blank">其他不孕</a></li>
        </ul>
      </li>
	  <li class="am-parent"> <a>精品产科</a>
        <ul class="am-menu-sub am-collapse  am-avg-sm-6 ">
          <li><a href="?c=show&m=cate&mid=129" target="_blank">产前</a></li>
          <li><a href="?c=show&m=cate&mid=130" target="_blank">产后</a></li>
          <li><a href="?c=show&m=cate&mid=131" target="_blank">分娩</a></li>
          <li><a href="?c=show&m=cate&mid=132" target="_blank">围产保健</a></li>
          <li><a href="?c=show&m=cate&mid=133" target="_blank">孕检产检</a></li>
          <li><a href="?c=show&m=cate&mid=134" target="_blank">特色技术</a></li>
        </ul>
      </li>
      <li class=""> <a href="##">活动中心</a> </li>
    </ul>
  </nav>
  <!--菜单结束--> 
</header>