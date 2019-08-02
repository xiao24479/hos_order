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
				
					<div class="widget green">
<!--						<div class="widget-title">
							<h4><i class="icon-reorder"></i> 编辑图文信息</h4>
							<span class="tools">
								<a href="javascript:;" class="icon-chevron-down"></a>
								<a href="javascript:;" class="icon-remove"></a>
							</span>
						</div>-->
						<div class="widget-body">
							<form action="?c=posts&m=tw_insert" method="post" class="form-horizontal">
								<div class="control-group">
									<label class="control-label">图文标题</label>
									<div class="controls">
		
										<input type="text" value="<?php if(isset($title)){echo $title;}?>" class="input-xlarge" name="img_title" />
									</div>
								</div>

								<div class="control-group">
									<label class="control-label">原始图标</label>
									<div class="controls span3" style="margin-left:22px;">
										<a class="thumbnail">
											<img onclick="doUpload()" src="<?php if(empty($img_url)):?>http://www.w3cschool.cc/try/bootstrap/php-thumb.png<?php else: echo $img_url; endif;?>" alt="文章缩略图" width="260" height="180" id="img_suo"/>
										</a>
			
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">作者</label>
									<div class="controls">
										<input type="text" value="<?php if(isset($username)){echo $username;}?>" class="input-xlarge" name="author" />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">描述</label>
									<div class="controls">
										<textarea class="input-xxlarge" rows="3" name="digest"><?php if(isset($description)){echo $description;}?></textarea>
									</div>
								</div>

								<div class="control-group">
									<label class="control-label">正文</label>
									<div class="controls">
										<textarea class="input-xxlarge" rows="10" name="content" id="con_content"><?php if(isset($content)){echo $content;}?></textarea>
									</div>
								</div>
						
								<div class="control-group">
									<div class="controls">
										<input type="hidden" value="<?php if(isset($thumb)){echo $thumb;}?>" class="input-xlarge" name="thumb" />
										<input type="hidden" value="<?php if(isset($url)){echo $url;}?>" class="input-xlarge" name="url" />
										<input type="hidden" value="<?php if(isset($hos_id)){echo $hos_id;}?>" class="input-xlarge" name="hos_id" />
										<button type="submit" class="btn btn-success"><i class="icon-ok"></i> <?php echo $this->lang->line('submit'); ?> </button>
										
									</div>
								</div>
							</form>
						</div>
					
				</div>
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
   <script charset="utf-8" src="static/editor/kindeditor.js"></script>
   <script charset="utf-8" src="static/editor/lang/zh_CN.js"></script>
   <script type="text/javascript" src="static/js/jquery.upload.js"></script>
<script>
function doUpload() {
	// 上传方法
	$.upload({
			// 上传地址
			url: '?c=weixin&m=upload', 
			// 文件域名字
			fileName: 'file', 
			// 其他表单数据
			params: {name: 'pxblog'},
			// 上传完成后, 返回json, text
			dataType: 'json',
			// 上传之前回调,return true表示可继续上传
			onSend: function() {
					return true;
			},
			// 上传之后回调
			onComplate: function(data) {
				$("#img_suo").attr("src", data.url); 	
				$("input[name='thumb']").val(data.name);
			}
	});
}

	KindEditor.ready(function(K) {
	window.editor = K.create('#con_content', {
					resizeType : 1,
					cssPath : '/static/editor/plugins/code/prettify.css',
					uploadJson : '/static/editor/php/upload_json.php',
					fileManagerJson : '/static/editor/php/file_manager_json.php',
					allowPreviewEmoticons : false,
					allowImageUpload : true,
					items : [
						'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
						'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
						'insertunorderedlist', '|', 'emoticons', 'image', 'link', '|', 'fullscreen']
				});
	});
</script>
</body>
</html>