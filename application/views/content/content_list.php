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

<link rel="stylesheet" type="text/css" href="static/css/metro-gallery.css" media="screen" />

<link href="static/js/datepicker/css/datepicker.css" rel="stylesheet" />

<script src="static/js/jquery.js"></script>
</head>

<body class="fixed-top">

<?php echo $top; ?>


<div id="container" class="row-fluid"> 

<?php echo $sider_menu; ?>



<div id="main-content"> 

    <!-- BEGIN PAGE CONTAINER-->

    <div class="container-fluid" style="position:relative; padding-top:10px;"> 
	
		<form action="" method="get" class="date_form order_form" id="order_list_form" style="height:auto;">

		<input type="hidden" value="posts" name="c" />

		<input type="hidden" value="get_content_list" name="m" />

		<div class="span3">

			<div class="row-form">

				<label class="select_label">选择医院</label>

				<select name="hos_id" id="hos_id" style="width:180px;">

					<option value="">请选择医院...</option>

					<?php foreach($hospital as $val):?><option value="<?php echo $val['hos_id']; ?>" <?php if($val['hos_id'] == $hos_id){echo " selected";}?>><?php echo $val['hos_name']; ?></option><?php endforeach;?>

				</select>
			</div>
			<div class="row-form">

				<label class="select_label">一级分类</label>

				<select name="cate" id="cate" style="width:180px;">

					<option value="">请选择分类...</option>

				</select>
			</div>

		</div>
		<div class="span3">
			<div class="row-form">

				<label class="select_label">选择网站</label>

				<select name="do_id" id="do_id" style="width:160px;">

					<option value="">请选择网站...</option>

				</select>
			</div>
			<div class="row-form">

				<label class="select_label">二级分类</label>

				<select name="cate_1" id="cate_1" style="width:160px;">

					<option value="">请选择分类...</option>

				</select>
			</div>
		</div>
		<div class="span3">
			<div class="row-form">

				<label class="select_label">选择站点</label>

				<select name="st_id" id="st_id" style="width:140px;">

					<option value="">请选择站点...</option>

				</select>
			</div>
			<div class="row-form">

				<label class="select_label">三级分类</label>

				<select name="cate_2" id="cate_2" style="width:140px;">

					<option value="">请选择分类...</option>

				</select>
			</div>
		</div>
		<div class="span3">
			<div class="row-form" style="height:40px;">
				<label class="select_label">关键词</label>
				<input type="text" value="<?php echo $search;?>" class="input-xlarge" name="title" style="width:140px;">
			</div>
			<div class="row-form">

				<label class="select_label">四级分类</label>

				<select name="cate_3" id="cate_3" style="width:155px;">

					<option value="">请选择分类...</option>

				</select>
			</div>
		</div>
		<div class="order_btn" style="left:1100px;">

		   <button type="submit" class="btn btn-success"> 搜索 </button> 

		</div>

		</form>
		<div class="row-fluid">

			<div class="span12">
				<table class="table table-striped">
					<thead>
						<tr>
							<th width="40">#</th>
							<th width="400">标题</th>
							<th width="80">作者</th>
							<th>发布日期</th>
							<th width="160">操作</th>
						</tr>
					</thead>
					<tbody>
					<?php $i = 1;?>
					<?php foreach($content_list as $val):?>
						<tr>
							<td><?php echo $i;?></td>
							<td><input name="catid[]" value="<?php echo $val->catid?>" type="checkbox" style="margin-top: -1px;">&nbsp;&nbsp;<a href="<?php echo $val->url;?>" target="_Blank"><?php echo $val->title;?></td>
							<td><?php echo $val->username;?></td>
								
							<td><?php echo date('Y-m-d H:i:s',$val->inputtime);?></td>
							<td><a href="?c=posts&m=content_info&hos_id=<?php echo $hos_id;?>&do_id=<?php echo $do_id;?>&st_id=<?php echo $st_id;?>&catid=<?php echo $catid;?>&id=<?php echo $val->id?>">制作图文素材</a>/<a href="<?php echo $val->url;?>" target="_Blank">查看</a></td>
						</tr>
						<?php $i++;?>
					<?php endforeach;?>
					</tbody>
				</table>
			</div>
			<?php echo $pages; ?>
		</div>
	</div>

</div>

</div>

</div>					
</div>

</div>

   <script src="static/js/jquery-1.8.3.min.js"></script>

   <script src="static/js/jquery.nicescroll.js" type="text/javascript"></script>

   <script src="static/js/bootstrap.min.js"></script>

   <script type="text/javascript" src="static/js/jquery.slimscroll.min.js"></script>

   <!-- ie8 fixes -->

   <!--[if lt IE 9]>

   <script src="static/js/excanvas.js"></script>

   <script src="static/js/respond.js"></script>

   <![endif]-->

   <script src="static/js/common-scripts.js"></script>

   <script type="text/javascript" src="static/js/date.js"></script>

   <script type="text/javascript" src="static/js/daterangepicker.js"></script>

   <script src="static/js/datepicker/js/datepicker.js"></script>

<script language="javascript">

	<?php if($hos_id > 0):?>

	ajax_get_keshi(<?php echo $hos_id?>, <?php echo $do_id?>);

	<?php endif;?>
	<?php if($do_id > 0):?>

	ajax_get_site(<?php echo $do_id?>, <?php echo $st_id?>);

	<?php endif;?>
	<?php if($st_id > 0):?>

	ajax_get_cate(<?php echo $do_id?>, <?php echo $st_id?>,0,<?php echo $cate?>,0);

	<?php endif;?>
	<?php if($cate > 0):?>

	ajax_get_cate(<?php echo $do_id?>, <?php echo $st_id?>,<?php echo $cate?>,<?php echo $cate_1?>,1);

	<?php endif;?>
	<?php if($cate_1 > 0):?>

	ajax_get_cate(<?php echo $do_id?>, <?php echo $st_id?>,<?php echo $cate_1?>,<?php echo $cate_2?>,2);

	<?php endif;?>
	<?php if($cate_2 > 0):?>

	ajax_get_cate(<?php echo $do_id?>, <?php echo $st_id?>,<?php echo $cate_2?>,<?php echo $cate_3?>,3);

	<?php endif;?>
	$("#hos_id").change(function(){
			
			var hos_id = $(this).val();
			ajax_get_keshi(hos_id, 0);

		});
	$("#do_id").change(function(){
			
			var do_id = $(this).val();
			ajax_get_site(do_id, 0);

		});
	$("#st_id").change(function(){
			
			var do_id = $("#do_id").val();
			var st_id = $(this).val();
			ajax_get_cate(do_id,st_id,0, 0, 0);

		});
	$("#cate").change(function(){
			
			var do_id = $("#do_id").val();
			var st_id = $("#st_id").val();
			var cate = $(this).val();
			if(cate == 0){
				return false;
			}
			ajax_get_cate(do_id,st_id,cate, 0 ,1);

		});
	$("#cate_1").change(function(){
			
			var do_id = $("#do_id").val();
			var st_id = $("#st_id").val();
			var cate = $(this).val();
			if(cate == 0){
				return false;
			}
			ajax_get_cate(do_id,st_id,cate, 0 ,2);

		});	
	$("#cate_2").change(function(){
			
			var do_id = $("#do_id").val();
			var st_id = $("#st_id").val();
			var cate = $(this).val();
			if(cate == 0){
				return false;
			}
			ajax_get_cate(do_id,st_id,cate, 0 ,3);

		});	
function ajax_get_keshi(hos_id, check_id)
{
	$.ajax({

		type:'post',

		url:'?c=posts&m=get_domain_ajax',

		data:'hos_id=' + hos_id + '&check_id=' + check_id,

		success:function(data)

		{

			$("#do_id").html(data);

		},

		complete: function (XHR, TS)

		{

		   XHR = null;

		}

	});

}
function ajax_get_site(do_id, check_id)
{
	$.ajax({

		type:'post',

		url:'?c=posts&m=get_site_ajax',

		data:'do_id=' + do_id + '&check_id=' + check_id,

		success:function(data)

		{

			$("#st_id").html(data);

		},

		complete: function (XHR, TS)

		{

		   XHR = null;

		}

	});

}
function ajax_get_cate(do_id, st_id, fid, check_id,tag)
{
	$.ajax({

		type:'post',

		url:'?c=posts&m=get_cate_ajax',

		data:'do_id=' + do_id + '&st_id=' + st_id + '&fid=' + fid + '&check_id=' + check_id,

		success:function(data)

		{
			if(tag==0){
				$("#cate").html(data);
			}else if(tag==1){
				$("#cate_1").html(data);
			}else if(tag==2){
				$("#cate_2").html(data);
			}else if(tag==3){
				$("#cate_3").html(data);
			}
			
		},

		complete: function (XHR, TS)

		{

		   XHR = null;

		}

	});

}

</script>

</body>

</html>