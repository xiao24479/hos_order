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
					<?php echo (($this->session->flashdata('success')))?'<div class="well" id="flash_info">' . $this->session->flashdata('success') . '</div>':'';?>
					<?php echo (($this->session->flashdata('error')))?'<div class="well" id="flash_info">' . $this->session->flashdata('error') . '</div>':'';?>
					<?php if($type=='category'):?>
					<div class="row-fluid">
			
						<div class="span7">
							<ul class="nav nav-tabs">
							   <li class="active"><a href="#">分类</a></li>
							   <li><a href="?c=metas&m=manage&type=tag">标签</a></li>
							</ul>
							<div class="span12">
								操作: <span class="check_all opera">全选</span>, <span class="check_null opera">不选</span>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  选中项: <span class="opera_blod" onclick="metas_del()">删除</span>, <span class="opera">刷新</span>, <span class="opera">合并到</span>&nbsp;&nbsp;&nbsp;
								<select style="width:120px;">
									<option value="">请选择...</option>
								</select>
							</div>
							<?php if(empty($category)):?>
								<div style="clear:both;"></div>
								<div class="well">没有添加分类，请在右侧添加</div>
							<?php else:?>
							<table class="table table-striped">
								<thead>
									<tr>
										<th>序号</th>
										<th>分类名</th>
										<th>别名</th>
										<th>文章数</th>
										<th></th>
										
									</tr>
								</thead>
								<tbody>
									<?php $tag = 1;?>
									<?php foreach($category as $val):?>
									<tr>
										<td> <input name="mid[]" value="<?php echo $val['mid']?>" type="checkbox" style="margin-top: -3px;">&nbsp;&nbsp;<?php echo $tag;?></td>
										<td><a href="?c=metas&m=manage&type=category&hos_id=<?php echo $val['hos_id'];?>&mid=<?php echo $val['mid'];?>"><?php echo $val['name']?> </td>
										<td><?php echo $val['slug']?> </td>
										<td><?php echo $val['count']?></td>
										<td><a href="javascript:;" onclick="get_son(this, <?php echo $val['mid'];?>)"><i class="icon-plus"></i></a></td>
									</tr>
									<?php 
										$tag++;
										endforeach;
										
									?>
								</tbody>
							</table>
							<?php endif;?>
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
										<li><a href="?c=metas&m=manage&hos_id=<?php echo $val['hos_id'];?>"><?php echo $val['hos_name'];?></a></li>
									<?php endforeach;?>
								</ul>
							</div>
							<button type="button" class="btn btn-default btn-lg"><a href="?c=metas&m=manage">新增分类</a></button>
						</div>
						<div class="span5" style="background: #f9f9f9;">
							<form class="well" method="post">
								<label>分类名称</label>
								<?php echo form_error('name', '<p class="message error">', '</p>'); ?>
								<input name="name" class="span12" placeholder="Type something…" value="<?php if(isset($name)){echo $name;}?>">
								<label>分类缩略名</label>
								<?php echo form_error('slug', '<p class="message error">', '</p>'); ?>
								<input name="slug" class="span12" placeholder="Type something…" value="<?php if(isset($slug)){echo $slug;}?>">
								<p class="text-info">分类缩略名用于创建友好的链接形式,建议使用字母,数字,下划线和横杠.</p>
								<label>分类描述</label>
								<?php echo form_error('description', '<p class="message error">', '</p>'); ?>
								<textarea class="form-control span12" rows="3" name="description"><?php if(isset($description)){echo $description;}?></textarea>
								<p class="text-info">此文字用于描述分类,在有的主题中它会被显示.</p>
								<label>所属医院</label>
								<select style="width:200px;" name="hos_id">
									<option value="">请选择...</option>
									<?php foreach($hospital as $val):?>
									<option value='<?php echo $val['hos_id']?>' <?php if($hos_id==$val['hos_id']){echo 'selected="selected"';}?>><?php echo $val['hos_name']?> </option>
									<?php endforeach;?>
								</select><br/>
								<label>父级</label>
								<select style="width:200px;" name="fid">
									<option value="">顶级分类</option>
									<?php foreach($category as $val):?>
									<option value='<?php echo $val['mid']?>' <?php if($fid==$val['mid']){echo 'selected="selected"';}?>><?php echo $val['name']?> </option>
									<?php endforeach;?>
								</select><br/>
								<label>排序</label>
								<?php echo form_error('order', '<p class="message error">', '</p>'); ?>
								<input name="order" class="span12" placeholder="Type something…" value="<?php if(isset($order)){echo $order;}?>"><br/>
								<input type="hidden" name="type" value="category">
								<input type="hidden" name="do" value="<?php echo (isset($mid) && is_numeric($mid))?'update':'insert';?>">
								<button type="submit" class="btn btn-default btn-lg">提交</button>
							</form>
						</div>
					</div>
					<?php elseif($type=='tag'):?>
					<div class="row-fluid">
			
						<div class="span7">
							<ul class="nav nav-tabs">
							   <li ><a href="?c=metas&m=manage">分类</a></li>
							   <li class="active"><a href="#">标签</a></li>
							</ul>
							<div class="span12">
								操作: <span class="check_all opera">全选</span>, <span class="check_null opera">不选</span>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  选中项: <span class="opera_blod" onclick="metas_del()">删除</span>, <span class="opera">刷新</span>, <span class="opera">合并到</span>&nbsp;&nbsp;&nbsp;
								<select style="width:120px;">
									<option value="">请选择...</option>
								</select>
							</div>
							<?php if(empty($tag)):?>
								<div style="clear:both;"></div>
								<div class="well">没有标签数据，请在右侧添加</div>
							<?php else:?>
							<table class="table table-striped">
								<thead>
									<tr>
										<th>名称</th>
										<th>分类名</th>
										<th>别名</th>
										<th>文章数</th>
										
									</tr>
								</thead>
								<tbody>
									<?php $num = 1;?>
									<?php foreach($tag as $val):?>
									<tr>
										<td> <input type="checkbox" name="mid[]" value="<?php echo $val['mid']?>" style="margin-top: -3px;">&nbsp;&nbsp;<?php echo $num;?></td>
										<td><a href="?c=metas&m=manage&type=tag&hos_id=<?php echo $val['hos_id'];?>&mid=<?php echo $val['mid'];?>"><?php echo $val['name']?></td>
										<td><?php echo $val['slug']?> </td>
										<td><?php echo $val['count']?></td>
									</tr>
									<?php 
										endforeach;
										$num++;
									?>
								</tbody>
							</table>
							<?php endif;?>
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
										<li><a href="?c=metas&m=manage&type=tag&hos_id=<?php echo $val['hos_id'];?>"><?php echo $val['hos_name'];?></a></li>
									<?php endforeach;?>
								</ul>
							</div>
						</div>
						<div class="span5" style="background: #f9f9f9;">
							<form class="well" method="post">
								<label>标签名称</label>
								<?php echo form_error('name', '<p class="message error">', '</p>'); ?>
								<input name="name" class="span12" placeholder="Type something…" value="<?php if(isset($name)){echo $name;}?>">
								<label>标签缩略名</label>
								<?php echo form_error('slug', '<p class="message error">', '</p>'); ?>
								<input name="slug" class="span12" placeholder="Type something…" value="<?php if(isset($slug)){echo $slug;}?>">
								<p class="text-info">标签缩略名用于创建友好的链接形式,建议使用字母,数字,下划线和横杠.</p>
								<label>所属医院</label>
								<select style="width:200px;" name="hos_id">
									<option value="">请选择...</option>
									<?php foreach($hospital as $val):?>
									<option value='<?php echo $val['hos_id']?>' <?php if($hos_id==$val['hos_id']){echo 'selected="selected"';}?>><?php echo $val['hos_name']?> </option>
									<?php endforeach;?>
								</select><br/>
								<input type="hidden" name="type" value="tag">
								<input type="hidden" name="do" value="<?php echo (isset($mid) && is_numeric($mid))?'update':'insert';?>">
								<button type="submit" class="btn btn-default btn-lg">提交</button>
							</form>
						</div>
					</div>
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
$(document).ready(
    function()
    {
		var obj = $("#flash_info");
		if(obj){
			setTimeout('$("#flash_info").hide("slow")',3000);
		} 
    }
);
$(function(){
		$(".check_all").click(function() {    
			$("input[name='mid[]']").attr("checked", true);
		});  

		$(".check_null").click(function() {    
			$("input[name='mid[]']").attr("checked", false);
		});  

});

function metas_del(){
	
	var metas="";
    $("input[name='mid[]']:checkbox").each(function(){ 
        if($(this).is(':checked')){
        metas += $(this).val()+","
		}
    })
	if(metas == ''){
	
		alert('请选择要删除的项目');
		return false;
	}
			
	if(confirm("确定删除吗")){
          location.href="?c=metas&m=operate&do=delete&metas="+metas;
    }

}

function get_son(obj,mid){
	
	if($(obj).children("i").attr("class") == "icon-minus")
	{
		$(obj).children("i").attr("class", "icon-refresh icon-spin");
		$(".cate_" + mid).slideUp(100, function(){ $(obj).children("i").attr("class", "icon-plus"); });
	}
	else
	{
		$(obj).children("i").attr("class", "icon-refresh icon-spin");
		$.ajax({
				type:'post',
				url:'?c=metas&m=cate_list_ajax',
				data:'mid=' + mid,
				success:function(data)
				{
					$(obj).parent().parent().after(data);
				},
				complete: function (XHR, TS)
				{
				   XHR = null;
				   $(obj).children("i").attr("class", "icon-minus");
				}
			});
	}

}
</script>
</body>
</html>