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
.pix_25{height:25px;clear:both;}
.opera{cursor: pointer;color: #BD6800;}
.opera_blod{color: #C00;font-weight: bold;}
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
				<?php if($status=='post'):?>
				<?php if($type=='publish'):?>
					<div class="row-fluid">
						<div class="span9">
							<div class="span12">
								<ul class="nav nav-tabs">
									   <li class="active"><a href="#">已发布</a></li>
									   <li><a href="?c=posts&m=manage&status=draft&hos_id=<?php echo $hos_id;?>">草稿</a></li>
									   <li><a href="?c=posts&m=manage&status=waiting&hos_id=<?php echo $hos_id;?>">待审核</a></li>
								</ul>
							</div>
							<div class="span12">
								操作: <span class="check_all opera">全选</span>, <span class="check_null opera">不选</span>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  选中项: <span class="opera_blod" onclick="metas_del()">删除</span>&nbsp;&nbsp;&nbsp;&nbsp;<a href="?c=posts&m=write"><span class="opera_blod">添加文章</span> </a>
					
							</div>
							<div class="span12 well" style="margin-left:0;">
								<table class="table table-striped">
									<thead>
										<tr>
											<th width="280">标题</th>
											<th width="80">作者</th>
											<th width="90">分类</th>
											<th>发布日期</th>
											<th width="160">操作</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach($posts as $val):?>
										<tr>
											<td><input name="pid[]" value="<?php echo $val['pid']?>" type="checkbox" style="margin-top: -1px;">&nbsp;&nbsp;<a href="?c=posts&m=write&pid=<?php echo $val['pid'];?>" target="_Blank"><?php echo $val['title'];?></td>
											<td><?php echo $val['admin_name']?></td>
											<td>
												<?php foreach($val['categories'] as $key=>$v):
													echo $v['name'].'<br/>';
												 endforeach;?>
											</td>
											<td><?php echo date('Y-m-d H:i:s',$val['created']);?></td>
											<td><a href="?c=weixin&m=test&pid=<?php echo $val['pid'];?>">制作微信图文</a>/<a href="?c=show&m=view&pid=<?php echo $val['pid'];?>" target="_Blank">预览</a></td>
										</tr>
										<?php endforeach;?>
									</tbody>
								</table>
							</div>
							<?php echo $page;?>
						</div>
						<div class="span3">
							<div class="span12">
								<ul class="nav nav-tabs">
									   <li class="active"><a href="#">我的</a></li>
									   <li><a href="#">所有</a></li>
								</ul>
							</div>
							<div class="pix_25"></div>
							<div class="well">
								<label>切换医院</label>
								<div class="controls">	
									<div class="btn-group">
										<button type="button" class="btn btn-default dropdown-toggle" 
											data-toggle="dropdown">
											<?php foreach($hospital as $val):?>
											<?php if($val['hos_id']==$hos_id){echo $val['hos_name'];}?>
												
											<?php endforeach;?> <span class="caret"></span>
										</button>
										<ul class="dropdown-menu" role="menu">
											<?php foreach($hospital as $val):?>
											<?php if($val['hos_id']==$hos_id){continue;}?>
												<li><a href="?c=posts&m=manage&hos_id=<?php echo $val['hos_id'];?>"><?php echo $val['hos_name'];?></a></li>
											<?php endforeach;?>
										</ul>
									</div>
								</div>
								<form method="post">
								<div class="pix_25"></div>
								<div class="controls">	
									<select name="fid" id="fid">
										<option value="">请选择分类筛选</option>
										<?php foreach($category as $val):?>
											<option value="<?php echo $val['mid'];?>"><?php echo $val['name'];?></option>
										<?php endforeach;?>
									</select>
								</div>
								<div class="controls">
									<select name="mid" id="cid">
									   <option value="0">请选择子分类</option>
									</select>
								</div>
								<div class="controls">
									<input name="keywords" class="span12" placeholder="填写关键字筛选" value="<?php if(isset($keywords)){echo $keywords;}?>">
								</div>
								<div class="pix_25"></div>
								<button type="submit" class="btn btn-default btn-lg">筛选>></button>
								</form>
							</div>
						</div>
					</div>
					<?php elseif($type=='draft'):?>
					<div class="row-fluid">
						<div class="span9">
							<div class="span12">
								<ul class="nav nav-tabs">
									   <li><a href="?c=posts&m=manage&status=publish&hos_id=<?php echo $hos_id;?>">已发布</a></li>
									   <li class="active"><a href="#">草稿</a></li>
									   <li><a href="?c=posts&m=manage&status=waiting&hos_id=<?php echo $hos_id;?>">待审核</a></li>
								</ul>
							</div>
							<div class="span12">
								操作: <span class="check_all opera">全选</span>, <span class="check_null opera">不选</span>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  选中项: <span class="opera_blod" onclick="metas_del()">删除</span>
					
							</div>
							<div class="span12 well" style="margin-left:0;">
								<table class="table table-striped">
									<thead>
										<tr>
											<th width="400">标题</th>
											<th>作者</th>
											<th>分类</th>
											<th>发布日期</th>
											
										</tr>
									</thead>
									<tbody>
										<?php foreach($posts as $val):?>
										<tr>
											<td><input name="pid[]" value="<?php echo $val['pid']?>" type="checkbox" style="margin-top: -1px;">&nbsp;&nbsp;<a href="?c=posts&m=write&pid=<?php echo $val['pid'];?>" target="_Blank"><?php echo $val['title'];?></td>
											<td><?php echo $val['admin_name']?></td>
											<td>
												<?php foreach($val['categories'] as $key=>$v):
													echo $v['name'];
													if($key>0){
														echo ',';
													}
												 endforeach;?>
											</td>
											<td><?php echo date('Y-m-d H:i:s',$val['created']);?></td>
										</tr>
										<?php endforeach;?>
									</tbody>
								</table>
							</div>
							<?php echo $page;?>
						</div>
						<div class="span3">
							<div class="span12">
								<ul class="nav nav-tabs">
									   <li class="active"><a href="#">我的</a></li>
									   <li><a href="#">所有</a></li>
								</ul>
							</div>
							<div class="pix_25"></div>
							<div class="well">
								<label>切换医院</label>
								<div class="controls">	
									<div class="btn-group">
										<button type="button" class="btn btn-default dropdown-toggle" 
											data-toggle="dropdown">
											<?php foreach($hospital as $val):?>
											<?php if($val['hos_id']==$hos_id){echo $val['hos_name'];}?>
												
											<?php endforeach;?> <span class="caret"></span>
										</button>
										<ul class="dropdown-menu" role="menu">
											<?php foreach($hospital as $val):?>
											<?php if($val['hos_id']==$hos_id){continue;}?>
												<li><a href="?c=posts&m=manage&hos_id=<?php echo $val['hos_id'];?>"><?php echo $val['hos_name'];?></a></li>
											<?php endforeach;?>
										</ul>
									</div>
								</div>
								<form method="post">
								<div class="pix_25"></div>
								<div class="controls">	
									<select name="fid" id="fid">
										<option value="">请选择分类筛选</option>
										<?php foreach($category as $val):?>
											<option value="<?php echo $val['mid'];?>"><?php echo $val['name'];?></option>
										<?php endforeach;?>
									</select>
								</div>
								<div class="controls">
									<select name="mid" id="cid">
									   <option value="0">请选择子分类</option>
									</select>
								</div>
								<div class="controls">
									<input name="keywords" class="span12" placeholder="填写关键字筛选" value="<?php if(isset($keywords)){echo $keywords;}?>">
								</div>
								<div class="pix_25"></div>
								<button type="submit" class="btn btn-default btn-lg">筛选>></button>
								</form>
							</div>
						</div>
					</div>
					<?php elseif($type=='waiting'):?>
					<div class="row-fluid">
						<div class="span9">
							<div class="span12">
								<ul class="nav nav-tabs">
									   <li><a href="?c=posts&m=manage&status=publish&hos_id=<?php echo $hos_id;?>">已发布</a></li>
									   <li><a href="?c=posts&m=manage&status=draft&hos_id=<?php echo $hos_id;?>">草稿</a></li>
									   <li class="active"><a href="#">待审核</a></li>
								</ul>
							</div>
							<div class="span12">
								操作: <span class="check_all opera">全选</span>, <span class="check_null opera">不选</span>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  选中项: <span class="opera_blod" onclick="metas_del()">删除</span>
					
							</div>
							<div class="span12 well" style="margin-left:0;">
								<table class="table table-striped">
									<thead>
										<tr>
											<th width="400">标题</th>
											<th>作者</th>
											<th>分类</th>
											<th>发布日期</th>
											
										</tr>
									</thead>
									<tbody>
										<?php foreach($posts as $val):?>
										<tr>
											<td><input name="pid[]" value="<?php echo $val['pid']?>" type="checkbox" style="margin-top: -1px;">&nbsp;&nbsp;<a href="?c=posts&m=write&pid=<?php echo $val['pid'];?>" target="_Blank"><?php echo $val['title'];?></td>
											<td><?php echo $val['admin_name']?></td>
											<td>
												<?php foreach($val['categories'] as $key=>$v):
													echo $v['name'];
													if($key>0){
														echo ',';
													}
												 endforeach;?>
											</td>
											<td><?php echo date('Y-m-d H:i:s',$val['created']);?></td>
										</tr>
										<?php endforeach;?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="span3">
							<div class="span12">
								<ul class="nav nav-tabs">
									   <li class="active"><a href="#">我的</a></li>
									   <li><a href="#">所有</a></li>
								</ul>
							</div>
							<div class="pix_25"></div>
							<div class="well">
								<label>切换医院</label>
								<div class="btn-group">
									<button type="button" class="btn btn-default dropdown-toggle" 
										data-toggle="dropdown">
										<?php foreach($hospital as $val):?>
										<?php if($val['hos_id']==$hos_id){echo $val['hos_name'];}?>
											
										<?php endforeach;?> <span class="caret"></span>
									</button>
									<ul class="dropdown-menu" role="menu">
										<?php foreach($hospital as $val):?>
										<?php if($val['hos_id']==$hos_id){continue;}?>
											<li><a href="?c=posts&m=manage&hos_id=<?php echo $val['hos_id'];?>"><?php echo $val['hos_name'];?></a></li>
										<?php endforeach;?>
									</ul>
								</div>
								<div class="pix_25"></div>
			
								<select name="category">
									<option value="">请选择分类筛选</option>
									<?php foreach($category as $val):?>
										<option value="<?php echo $val['mid'];?>"><?php echo $val['name'];?></option>
									<?php endforeach;?>
								</select>
								<div class="pix_25"></div>
								<input name="keywords" class="span12" placeholder="填写关键字筛选" value="<?php if(isset($keywords)){echo $keywords;}?>">
								<div class="pix_25"></div>
								<button type="submit" class="btn btn-default btn-lg">筛选>></button>
							</div>
						</div>
					</div>
					<?php endif;?>
					<?php elseif($status=='page'):?>
					<?php if($type=='publish'):?>
					<div class="row-fluid">
						<div class="span9">
							<div class="span12">
								<ul class="nav nav-tabs">
									   <li class="active"><a href="#">已发布</a></li>
									   <li><a href="?c=pages&m=manage&status=draft&hos_id=<?php echo $hos_id;?>">草稿</a></li>
								</ul>
							</div>
							<div class="span12">
								操作: <span class="check_all opera">全选</span>, <span class="check_null opera">不选</span>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  选中项: <span class="opera_blod" onclick="metas_del()">删除</span>
					
							</div>
							<div class="span12 well" style="margin-left:0;">
								<table class="table table-striped">
									<thead>
										<tr>
											<th width="400">标题</th>
											<th>作者</th>
											<th>发布日期</th>
											<th>操作</th>
											
										</tr>
									</thead>
									<tbody>
										<?php foreach($posts as $val):?>
										<tr>
											<td><input name="pid[]" value="<?php echo $val['pid']?>" type="checkbox" style="margin-top: -1px;">&nbsp;&nbsp;<a href="?c=posts&m=write&pid=<?php echo $val['pid'];?>"><?php echo $val['title'];?></td>
											<td></td>
											<td><?php echo date('Y-m-d H:i:s',$val['created']);?></td>
											<td><a href="?c=weixin&m=test&pid=<?php echo $val['pid'];?>">制作微信图文</a></td>
										</tr>
										<?php endforeach;?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="span3">
							<div class="span12">
								<ul class="nav nav-tabs">
									   <li class="active"><a href="#">我的</a></li>
									   <li><a href="#">所有</a></li>
								</ul>
							</div>
							<div class="pix_25"></div>
							<div class="well">
								<label>切换医院</label>
								<div class="btn-group">
									<button type="button" class="btn btn-default dropdown-toggle" 
										data-toggle="dropdown">
										<?php foreach($hospital as $val):?>
										<?php if($val['hos_id']==$hos_id){echo $val['hos_name'];}?>
											
										<?php endforeach;?> <span class="caret"></span>
									</button>
									<ul class="dropdown-menu" role="menu">
										<?php foreach($hospital as $val):?>
										<?php if($val['hos_id']==$hos_id){continue;}?>
											<li><a href="?c=pages&m=manage&hos_id=<?php echo $val['hos_id'];?>"><?php echo $val['hos_name'];?></a></li>
										<?php endforeach;?>
									</ul>
								</div>
							
								<div class="pix_25"></div>
								<input name="keywords" class="span12" placeholder="填写关键字筛选" value="<?php if(isset($keywords)){echo $keywords;}?>">
								<div class="pix_25"></div>
								<button type="submit" class="btn btn-default btn-lg">筛选>></button>
							</div>
						</div>
					</div>
					<?php elseif($type=='draft'):?>
					<div class="row-fluid">
						<div class="span9">
							<div class="span12">
								<ul class="nav nav-tabs">
									   <li><a href="?c=pages&m=manage&status=publish&hos_id=<?php echo $hos_id;?>">已发布</a></li>
									   <li class="active"><a href="#">草稿</a></li>
								</ul>
							</div>
							<div class="span12">
								操作: <span class="check_all opera">全选</span>, <span class="check_null opera">不选</span>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  选中项: <span class="opera_blod" onclick="metas_del()">删除</span>
					
							</div>
							<div class="span12 well" style="margin-left:0;">
								<table class="table table-striped">
									<thead>
										<tr>
											<th width="400">标题</th>
											<th>作者</th>
											<th>发布日期</th>
											<th>操作</th>
											
											
										</tr>
									</thead>
									<tbody>
										<?php foreach($posts as $val):?>
										<tr>
											<td><input name="pid[]" value="<?php echo $val['pid']?>" type="checkbox" style="margin-top: -1px;">&nbsp;&nbsp;<a href="?c=posts&m=write&pid=<?php echo $val['pid'];?>"><?php echo $val['title'];?></td>
											<td></td>
											<td><?php echo date('Y-m-d H:i:s',$val['created']);?></td>
											<td>预览</td>
										</tr>
										<?php endforeach;?>
									</tbody>
								</table>
							</div>
							<?php echo $page;?>
						</div>
						<div class="span3">
							<div class="span12">
								<ul class="nav nav-tabs">
									   <li class="active"><a href="#">我的</a></li>
									   <li><a href="#">所有</a></li>
								</ul>
							</div>
							<div class="pix_25"></div>
							<div class="well">
								<label>切换医院</label>
								<div class="btn-group">
									<button type="button" class="btn btn-default dropdown-toggle" 
										data-toggle="dropdown">
										<?php foreach($hospital as $val):?>
										<?php if($val['hos_id']==$hos_id){echo $val['hos_name'];}?>
											
										<?php endforeach;?> <span class="caret"></span>
									</button>
									<ul class="dropdown-menu" role="menu">
										<?php foreach($hospital as $val):?>
										<?php if($val['hos_id']==$hos_id){continue;}?>
											<li><a href="?c=pages&m=manage&hos_id=<?php echo $val['hos_id'];?>"><?php echo $val['hos_name'];?></a></li>
										<?php endforeach;?>
									</ul>
								</div>
								<div class="pix_25"></div>
			
								
								<input name="keywords" class="span12" placeholder="填写关键字筛选" value="<?php if(isset($keywords)){echo $keywords;}?>">
								<div class="pix_25"></div>
								<button type="submit" class="btn btn-default btn-lg">筛选>></button>
							</div>
						</div>
					</div>
					<?php endif;?>
					<?php endif;?>
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
	$(document).ready(function(e) {
		$("#fid").change(function(){
			var fid = $(this).val();
			ajax_get_cid(fid, 0);
		});
		<?php if(isset($mid)):?>
			ajax_get_cid(<?php echo $fid;?>, <?php echo $mid;?>);
		<?php endif;?>
	});
		function metas_del(){
	
			var metas="";
			$("input[name='pid[]']").each(function(){ 
				if($(this).is(':checked')){
				metas += $(this).val()+","
				}
				
			})
			if(metas == ''){
			
				alert('请选择要删除的项目');
				return false;
			}
					
			if(confirm("确定删除吗")){
				  location.href="?c=posts&m=operate&do=delete&posts="+metas;
			}

		}
	function ajax_get_cid(fid, check_id)
	{
		$("#keshi_id").after("<i class='icon-refresh icon-spin'></i>");
		$.ajax({
			type:'post',
			url:'?c=posts&m=cate_list_ajax',
			data:'fid=' + fid + '&check_id=' + check_id,
			success:function(data)
			{
				$("#cid").html(data);
			},
			complete: function (XHR, TS)
			{
			   XHR = null;
			   $("#fid").next(".icon-spin").remove();
			}
		});
	}
	$(".check_all").click(function() {    
			$("input[name='pid[]']").attr("checked", true);
		});  
    $(".check_null").click(function() {   
     
		$("input[name='pid[]']").removeAttr("checked");//取消全选
  
    })
	</script>
</body>
</html>