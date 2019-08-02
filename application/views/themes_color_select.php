<div class="row-fluid" style="width:100%;">
               <div class="span12" style="width:100%;">
                   <!-- BEGIN THEME CUSTOMIZER-->
<!--                   <div id="theme-change" class="hidden-phone">
                       <i class="icon-cogs"></i>
                        <span class="settings">
                            <span class="text">Theme Color:</span>
                            <span class="colors">
                                <span class="color-default" data-style="default"></span>
                                <span class="color-green" data-style="green"></span>
                                <span class="color-gray" data-style="gray"></span>
                                <span class="color-purple" data-style="purple"></span>
                                <span class="color-red" data-style="red"></span>
                            </span>
                        </span>
                   </div>-->
                   <!-- END THEME CUSTOMIZER-->
                   <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                   <div style="position: relative;z-index: 1000;width:100%;background-color: #fff;margin-left:-20px;margin-top:0px;height: 43px;line-height: 43px;font-size: 16px"><?php  
                 foreach($menu as $item){
                     if($act_name=='系统首页'){
                         echo "<i style='margin-left:20px;' class='icon-" .$item['act_action']."'></i>&nbsp;&nbsp;&nbsp;&nbsp;".$item['act_name'];
                         break;
                     }else{
                         if($item['act_action']==$menu_here['parent']['act_action']){
                         echo "<i style='margin-left:20px;' class='icon-" .$item['act_action']."'></i>&nbsp;&nbsp;&nbsp;&nbsp;<a style='color:#00a186;' href='".$item['act_url']."'>".$item['act_name']."</a>>>$act_name";
                     }
                     }
                     
                 }
                   
                 ?></div>
                   <!-- END PAGE TITLE & BREADCRUMB-->
				   <?php if($menu_here['parent']['act_action']=='weixin'&&isset($weixin_list)):?>
						<div class="btn-group" style=" position: fixed;top: 72px;right: 40px;height: 100px;">
							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
								<?php foreach($weixin_list as $val):?>
									<?php if($val['wx_id']==$w_wx_id){echo $val['wxname'];}?>
								<?php endforeach;?>
								<span class="caret"></span>
							</button>
							<ul class="dropdown-menu" role="menu" style="top: 30px;">
								<?php foreach($weixin_list as $val):?>
									<?php if($val['wx_id']==$w_wx_id){continue;}?>
									<li><a href="javascript:void(0)" onclick="set_weixin(<?php echo $val['wx_id']?>)"><?php echo $val['wxname'];?></a></li>
								<?php endforeach;?>
							</ul>
						</div>
				   <?php endif;?>
               </div>
            </div>
			<script>
				function set_weixin(wx_id)
				{
					$.ajax({

						type:'post',

						url:'?c=weixin&m=set_weixin',

						data:'wx_id=' + wx_id,

						success:function(data)

						{

							location.reload();

						},

						complete: function (XHR, TS)

						{

						   XHR = null;

						}

					});
				}
			</script>