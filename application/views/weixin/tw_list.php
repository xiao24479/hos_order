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
<style>
td{color:#666;}
.td1 td{background-color:#efefef;}
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
			    <div class="span8">
				  <div class="widget orange">
<!--                            <div class="widget-title">
                                <h4><i class="icon-reorder"></i> <?php echo $this->lang->line('content_table'); ?></h4>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <a href="javascript:;" class="icon-remove"></a>
                            </span>
                            </div>-->
                            <div class="widget-body">
						  <table width="100%" border="0" cellspacing="0" cellpadding="2" class="table table-advance">
							  <thead>
							  <tr>
								<th width="10">#</th>
								<th>标题</th>
								<th>作者</th>
								
								<th>关联文章</th>
								<th>操作</th>
							  </tr>
							  </thead>
							  <tbody>
							  <?php
							  $i = 1;
							  foreach($tw_list as $item):
							 
							  ?>
							  <tr<?php if($i % 2){ echo " class='td1'";}?>>
								<td><?php echo $i; ?></td>
								<td class="title"><a href="?c=weixin&m=test&tid=<?php echo $item['tid']?>"><?php echo $item['title']?></a></td>
								<td><?php echo $item['author']?></td>
								<td><a href="<?php if($item['url']){echo $item['url'];}else{echo '?c=show&m=view&pid='.$item['pid'];}?>" target="_blank">查看</a></td>
								<td><a href="javascript:;" onclick="select(this, <?php echo $item['tid'];?>)">选中</a></td>
								
							  </tr>

							  <?php
							  $i ++;
							  endforeach;
							  ?>
							  </tbody>
							  </table>
						</div>
				</div>
			  </div>
			  <div class="span4 well tw_box" style="min-height:300px;">
				 <button class="btn btn-info"><a href="?c=weixin&m=send_imgtxt">群发图文</a></button>
					<form class="tw_id">
						<input name="tid[]" type="hidden" value="">
						<?php if(isset($select)):?>
							<?php foreach($select as $val):?>
									<input name="tid[]" type="hidden" value="<?php echo $val['tid'];?>">
							<?php endforeach;?>
						<?php endif;?>
					</form>
					<?php if(isset($select)):?>
						<?php foreach($select as $val):?>
							<button class="btn btn-success" style="margin-bottom:6px;"><a href="?c=weixin&m=test&tid=<?php echo $val['tid']?>"><?php echo $val['title'];?></a>&nbsp;&nbsp;<a href="javascript:;" onclick="del(this, <?php echo $val['tid']?>)"><i class="icon-remove"></i></a></button>&nbsp;&nbsp;&nbsp;
						<?php endforeach;?>
					<?php endif;?>
					
			  </div>
			 
            </div>
            <!-- END PAGE CONTENT-->         
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
</div>
   <script src="static/js/jquery.js"></script>
   <script src="static/js/jquery.nicescroll.js" type="text/javascript"></script>
   <script type="text/javascript" src="static/js/jquery.slimscroll.min.js"></script>
   <script src="static/js/bootstrap.min.js"></script>
   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="static/js/excanvas.js"></script>
   <script src="static/js/respond.js"></script>
   <![endif]-->
   <script src="static/js/common-scripts.js"></script>
   <script>
		function select(obj,tid){
			//检测是否已经添加
			var tag = 2;
			$('input[name="tid[]"]').each(function(){   
				var data = $(this).val();   
				if(data == tid){
					alert('该条目已经选中');
					tag = 1;
				}
			});
			if(tag !== 1){
				$.ajax({
					type:'post',
					url:'?c=weixin&m=ajax_select',
					data:'tid=' + tid,
					success:function(data)
					{
						if(data==1){
							alert('该条目已经选中');
						}else{
							var title = $(obj).parents().parents().children(".title").text();
							$('.tw_box').append('<button class="btn btn-success" style="margin-bottom:6px;">'+title+'&nbsp;&nbsp;<a href="javascript:;" onclick="del(this, '+tid+')"><i class="icon-remove"></i></a></button>&nbsp;&nbsp;&nbsp;');
							$('.tw_id').append('<input name="tid[]" type="hidden" value="'+tid+'">');
						}
					},
					complete: function (XHR, TS)
					{
					   XHR = null;
					}
				});
				
			}
		}
		function del(obj,tid){
			$('input[name="tid[]"]').each(function(){   
				var data = $(this).val();   
				if(data == tid){
					
					$.ajax({
					type:'post',
					url:'?c=weixin&m=ajax_select_del',
					data:'tid=' + tid,
					success:function(data)
					{
						$(this).remove();
						$(obj).parent().remove();
					},
					complete: function (XHR, TS)
					{
					   XHR = null;
					}
				});
				}
			});
		}
   </script>
</body>
</html>