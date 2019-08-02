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
  <form action="?c=weixin&m=con_update" method="post" class="form-horizontal">
<div class="control-group">
	<label class="control-label">关键词</label>
	<div class="controls">
		<input type="text" value="<?php echo $info['con_name'] ?>" class="input-xlarge" name="con_name" />
	</div>
</div>
<div class="control-group">
	<label class="control-label">动作</label>
	<div class="controls">
		<?php if($info['type'] == 'click') :?><a href="javascript:void();" class="btn btn-success"> 文本 </a><?php endif;?>
		<?php if($info['type'] == 'vclick') :?><a href="javascript:void();" class="btn btn-success"> 图文 </a><?php endif;?>
	</div>
</div>
<?php if($info['type'] == 'click') :?>
<div class="control-group">
	<label class="control-label">文本</label>
	<div class="controls">
		<textarea class="input-xlarge" name="wb" rows="5"><?php echo $info['wb'];?></textarea>
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
		<input type="hidden" value="click" class="input-xlarge" name="type"/>
		<input type="hidden" name="form_action" value="update" /><input type="hidden" name="con_id" value="<?php echo $info['con_id']; ?>" /><input type="submit" name="submit" value="<?php echo $this->lang->line('submit'); ?>" class="btn btn-success"/>  <input name="reset" type="reset" value="<?php echo $this->lang->line('reset'); ?>" class="btn"/>
	</div>
</div>
  </form>
  <?php else:?>
  <form action="?c=weixin&m=con_update" method="post" class="form-horizontal">
<div class="control-group">
	<label class="control-label">关键词</label>
	<div class="controls">
		<input type="text" value="" class="input-xlarge" name="con_name" />
	</div>
</div>
<div class="control-group">
	<label class="control-label">设置动作</label>
	<div class="controls">
		<a onclick="choose_wb()" href="javascript:void();" class="btn btn-success"> 文本 </a>
		&nbsp;<a onclick="choose_tw()" href="javascript:void();" class="btn btn-success"> 图文 </a>
	</div>
</div>
<div class="control-group" id="wb">
	<label class="control-label">文本</label>
	<div class="controls"><textarea class="input-xlarge" name="wb" rows="5"></textarea></div>
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
		<input type="hidden" value="click" class="input-xlarge" name="type"/>
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
								<label><input id="con_keyword" style="margin-top: 9px;" value='' type="text" aria-controls="sample_1" class="input-medium"><a href="javascript:void();" onclick="next()" class="btn btn-success"> 换一批</a></label>
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
			$('#wb').hide();
			$('#tw').show();
			next(1);
			$('#tw_list').show();
		}
		function next(type){
			if(type == 1){
				$("input[name='type']").val('vclick');
				var con_keyword = '';
				var page = 0;
			}else{
				var page = parseInt($('.page').val());
				var con_keyword = $('#con_keyword').val();
			}
			var list_count = <?php echo $list_count;?>;
			$.ajax({

				type:'post',

				url:'?c=weixin&m=tw_list_ajax',

				data:'page=' + page + '&con_keyword='+ con_keyword,

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