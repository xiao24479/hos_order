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
					
					<div class="row-fluid">
                                            <form method="post" action="?c=sys_message&m=update_mes">
                                                <div class="span9" style="margin-top:50px;">
                                                    <?php foreach($mes_list as $val){?>
							<div class="content">
							
								<label>标题</label>
								
                                                                <input name="message_title" class="span12" placeholder="公告标题" value="<?=stripslashes($val['message_title'])?>">
							
								<div class="pix_25"></div>
								
								
								
								
								<label>内容</label>
								
								<textarea class="input-xxlarge span12" rows="20" name="message_content" id="mes_content"><?=stripslashes($val['message_content'])?></textarea>
                                                                <p style="height:30px;">是否通过：  是&nbsp;&nbsp;<input type="radio" <?php if($val['is_pass']==1){echo "checked='checked'";}?> name="is_pass"value="1" style="margin-top: 0px;"/>&nbsp;&nbsp;&nbsp;&nbsp;否&nbsp;&nbsp;<input type="radio" <?php if($val['is_pass']==0){echo "checked='checked'";}?> name="is_pass" value="0"/></p>
                                                                <input type="hidden" name="message_id" value="<?=$val['message_id']?>"/>
                                                                <p><input type="submit" value="提交"/></p>
							</div> 
							
                                                    <?php }?>
						</div>
						
						<
						</form>
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
   <script charset="utf-8" src="static/editor/kindeditor.js"></script>
   <script charset="utf-8" src="static/editor/lang/zh_CN.js"></script>
   <script type="text/javascript" src="static/js/jquery.upload.js"></script>
   <script>
       KindEditor.ready(function(K) {
	window.editor = K.create('#mes_content', {
					resizeType : 1,
					cssPath : 'static/editor/plugins/code/prettify.css',
					uploadJson : 'static/editor/php/upload_json.php',
					fileManagerJson : 'static/editor/php/file_manager_json.php',
					allowPreviewEmoticons : false,
					allowImageUpload : true,
                                        
					items : [
						'source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy', 'paste',
        'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
        'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
        'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
        'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
        'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'multiimage',
        'flash', 'media', 'insertfile', 'table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
        'anchor', 'link', 'unlink', '|', 'about']
				});
	});</script>
</body>
</html>