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
		<div class="span12">
		  <div class="widget green">
<!--					<div class="widget-title">
					<h4><i class="icon-reorder"></i> <?php echo $this->lang->line('content_form'); ?></h4>
					<span class="tools">
						<a href="javascript:;" class="icon-chevron-down"></a>
						<a href="javascript:;" class="icon-remove"></a>
					</span>
					</div>-->
					<div class="widget-body">
  <?php if(!empty($info)): ?>
  <form action="?c=weixin&m=menu_update" method="post" class="form-horizontal">
<div class="control-group">
	<label class="control-label">栏目名称</label>
	<div class="controls">
		<input type="text" value="<?php echo $info['name'] ?>" class="input-xlarge" name="menu_name" />
	</div>
</div>
<div class="control-group">
	<label class="control-label">上一级</label>
	<div class="controls">
		<select name="botton" class="sel">
	   <option value="0"><?php echo $this->lang->line('please_select');?></option>
	   <?php foreach($weixin_menu as $item):?>
	   <option value="<?php echo $item['menu_id'];?>" <?php if($item['menu_id']==$info['botton']){echo 'selected="selected"';}?>><?php echo $item['name'];?></option>
	   <?php endforeach;?>
	   </select>
	</div>
</div> 
<div class="control-group">
	<label class="control-label">动作</label>
	<div class="controls">
		<?php if($info['type'] == 'view') :?><a href="javascript:void();" class="btn btn-success"> 链接 </a><?php endif;?>
		<?php if($info['type'] == 'click') :?><a href="javascript:void();" class="btn btn-success"> 文本 </a><?php endif;?>
		<?php if($info['type'] == 'vclick') :?><a href="javascript:void();" class="btn btn-success"> 图文 </a><?php endif;?>
	</div>
</div>
<?php if($info['type'] == 'view') :?>
<div class="control-group">
	<label class="control-label">链接</label>
	<div class="controls">
		<input type="text" value="<?php echo $info['url'];?>" class="input-xlarge" name="url"/>
	</div>
</div>
<?php elseif($info['type'] == 'click') :?>
<div class="control-group">
	<label class="control-label">文本</label>
	<div class="controls">
		<textarea class="input-xlarge" name="wb" rows="5"><?php echo $wb[0]['wb'];?></textarea>
		<input type="hidden" value="click" class="input-xlarge" name="type"/>
	</div>
</div>
<?php elseif($info['type'] == 'vclick') :?>
<div class="control-group" id="tw">
	<label class="control-label">图文</label>
	<div class="controls">
		<div class="well tw_box" style="width:600px;height:120px;">
			<?php 
				if(isset($has_list)):
					foreach($has_list as $v){
			?>
				<span class="btn btn-success" style="margin-bottom:6px;">
					<?php echo $v['title'];?>&nbsp;&nbsp;<a href="javascript:void();" onclick="putout(this, '<?php echo $v['tid'];?>')"><i class="icon-remove"></i></a>
				</span>&nbsp;&nbsp;&nbsp;
				<input name="tid[]" type="hidden" value="<?php echo $v['tid'];?>">
			<?php 
					}
				endif;
			?>
		</div>
	</div>
</div>
<?php endif;?>
<div class="control-group">
	<div class="controls">
		<input type="hidden" name="form_action" value="update" /><input type="hidden" name="menu_id" value="<?php echo $info['menu_id']; ?>" /><input type="submit" name="submit" value="<?php echo $this->lang->line('submit'); ?>" class="btn btn-success"/>  <input name="reset" type="reset" value="<?php echo $this->lang->line('reset'); ?>" class="btn"/>
	</div>
</div>
  </form>
  <?php else:?>
  <form action="?c=weixin&m=menu_update" method="post" class="form-horizontal">
<div class="control-group">
	<label class="control-label">栏目名称</label>
	<div class="controls">
		<input type="text" value="" class="input-xlarge" name="menu_name" />
	</div>
</div>
<div class="control-group">
	<label class="control-label">上一级</label>
	<div class="controls">
		<select name="botton" class="sel">
	   <option value="0"><?php echo $this->lang->line('please_select');?></option>
	   <?php foreach($weixin_menu as $item):?>
	   <option value="<?php echo $item['menu_id'];?>"><?php echo $item['name'];?></option>
	   <?php endforeach;?>
	   </select>
	</div>
</div> 
<div class="control-group">
	<label class="control-label">设置动作</label>
	<div class="controls">
		<a onclick="choose_lj()" href="javascript:void();" class="btn btn-success"> 链接 </a>
		&nbsp;<a onclick="choose_wb()" href="javascript:void();" class="btn btn-success"> 文本 </a>
		&nbsp;<a onclick="choose_tw()" href="javascript:void();" class="btn btn-success"> 图文 </a>
	</div>
</div>
<div class="control-group" id="lj">
	<label class="control-label">链接</label>
	<div class="controls">
		<input type="text" value="" class="input-xlarge" name="url"/>
	</div>
</div>
<div class="control-group" style="display:none;" id="wb">
	<label class="control-label">文本</label>
	<div class="controls"></div>
</div>
<div class="control-group" style="display:none;" id="tw">
	<label class="control-label">图文</label>
	<div class="controls">
		<div class="well tw_box" style="width:600px;height:120px;">
			
		</div>
	</div>
</div>
<div class="control-group">
	<div class="controls">
		<input type="hidden" value="" class="input-xlarge" name="type"/>
		<input type="hidden" name="form_action" value="add" /><input type="submit" name="submit" value="<?php echo $this->lang->line('submit'); ?>" class="btn btn-success"/>  <input name="reset" type="reset" value="<?php echo $this->lang->line('reset'); ?>" class="btn"/>
	</div>
</div>
  </form>
  <?php endif; ?>
  </div>
  
</div>
</div>
</div>
<div class="span12 well" id="tw_list" style="margin-left:0;display:none;">
								<table class="table table-striped">
									<thead>
										<tr>
											<th width="400">标题</th>
											<th width="200">操作</th>
											
										</tr>
									</thead>
									<tbody class="putin">
										
									</tbody>
								</table>
								<input type="hidden" value="0" class="page">
								<a href="javascript:void();" onclick="next()" class="btn btn-success"> 换一批</a>
</div>
</div>
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
	<?php if(isset($has_list)):?>
	$(function(){
		next(1);
		$('#tw_list').show();
	});
	<?php endif;?>
		function choose_wb(){
			$('#lj').hide();
			$('#tw').hide();
			$("input[name='type']").val('click');
			var html = $('#wb').children('.controls').html();
			if(html == ''){
				$('#wb').children('.controls').append('<textarea class="input-xlarge" name="wb" rows="5"></textarea>');
			}
			$('#wb').show();
			$('#tw_list').hide();
		}
		
		function choose_tw()
		{
			$('#lj').hide();
			$('#wb').hide();
			$('#tw').show();
			next(1);
			$('#tw_list').show();
		}
		
		function choose_lj(){
			
			$('#tw').hide();
			$('#wb').hide();
			$('#wb').children('.controls').html('');
			$('#lj').show();
			$("input[name='type']").val('');
			$('#tw_list').hide();
		}
		function next(type){
			if(type == 1){
				$("input[name='type']").val('vclick');
				var page = 0;
			}else{
				var page = parseInt($('.page').val());
			}
			var list_count = <?php echo $list_count;?>;
			$.ajax({

				type:'post',

				url:'?c=weixin&m=tw_list_ajax',

				data:'page=' + page,

				success:function(data)

				{
					$('.putin').html(data);
					if(page+1 == list_count){
						$('.page').val(0);
					}else{
						$('.page').val(page+1);
					}
				},

				complete: function (XHR, TS)

				{

					XHR = null;

				}

			});
		}
		function putin(title,tid){
			var tag = 2;
			$('input[name="tid[]"]').each(function(){   
				var data = $(this).val();   
				if(data == tid){
					alert('该条目已经选中');
					tag = 1;
				}
			});
			if(tag !== 1){
				$('.tw_box').append('<span class="btn btn-success" style="margin-bottom:6px;">'+title+'&nbsp;&nbsp;<a href="javascript:void();" onclick="putout(this, '+tid+')"><i class="icon-remove"></i></a></span>&nbsp;&nbsp;&nbsp;');
				$('.tw_box').append('<input name="tid[]" type="hidden" value="'+tid+'">');
			}
		}
		function putout(obj,tid){
			$('input[name="tid[]"]').each(function(){   
				var data = $(this).val();   
				if(data == tid){
					$(this).remove();
				}
			});
			$(obj).parent().remove();
		}
		
   </script>
</body>
</html>