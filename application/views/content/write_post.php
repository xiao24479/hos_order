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
.pix_15{height:15px;clear:both;}
.error {
border: 1px solid #fbc2c4;
background: #fbe3e4;
color: #8A1F11;
}
.message {
padding: 5px 10px;
font-size: 14px;
color: #333;
margin-bottom: 10px;
overflow: hidden;
}
</style>
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
					$("input[name='img']").val(data.name);
				}
		});
	} 
</script>
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
					<div class="row-fluid">
						<form method="post">
						<div class="span9">
							<div class="well">
							
								<label>标题</label>
								<?php echo form_error('title', '<p class="message error">', '</p>'); ?>
								<input name="title" class="span12" placeholder="Type something…" value="<?php if(isset($title_post)){echo $title_post;}?>">
							
								<div class="pix_25"></div>
								<label>文章视图</label>
								<div class="pix_15"></div>
								<div class="span4 well">
									<a class="thumbnail">
										<img onclick="doUpload()" src="<?php if(empty($thumb)):?>http://www.w3cschool.cc/try/bootstrap/php-thumb.png<?php else: echo $thumb; endif;?>" alt="文章缩略图" width="260" height="180" id="img_suo"/>
									</a>
						
								</div>
								<div class="text-info span3">此图片用于生成微信图文,为了获得较好的效果,大图[做为封面]640*320，小图80*80</div>
								<input name="img" type="hidden" value="<?php if(isset($img)){echo $img;}?>">
								<div class="pix_15"></div>
								<label>描述</label>
								<?php echo form_error('description', '<p class="message error">', '</p>'); ?>
								<textarea class="input-xxlarge span12" rows="3" name="description" ><?php if(isset($description)){echo $description;}?></textarea>
								<div class="pix_15"></div>
								<label>内容</label>
								<?php echo form_error('text', '<p class="message error">', '</p>'); ?>
								<textarea class="input-xxlarge span12" rows="20" name="text" id="con_content"><?php if(isset($text)){echo $text;}?></textarea>
	

							</div>
							<input type="hidden" name="draft" value="0">
							
						</div>
						<div class="span3">
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
											<li><a href="?c=posts&m=write&hos_id=<?php echo $val['hos_id'];?>"><?php echo $val['hos_name'];?></a></li>
										<?php endforeach;?>
									</ul>
								</div>
								<div class="pix_25"></div>
								<label>分类</label>
								<?php echo form_error('category[]', '<p class="message error">', '</p>'); ?>
								<ul class="unstyled">
									<?php foreach($all_categories as $val):?>
									<li><a href="javascript:;" onclick="get_son(this, <?php echo $val['mid'];?>)"><?php if(in_array($val['mid'],$pop_fid)):?><i class="icon-minus"></i><?php else:?><i class="icon-plus"></i><?php endif;?></a>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $val['name'];?></li>
									<?php endforeach;?>
								</ul>
								<?php if(isset($str)){echo $str;}?>
								<div class="pix_25"></div>
								<label>缩略名</label>
								<?php echo form_error('slug', '<p class="message error">', '</p>'); ?>
								<input name="slug" class="span12" placeholder="Type something…" value="<?php if(isset($slug)){echo $slug;}?>">
								<p class="text-info">分类缩略名用于创建友好的链接形式,建议使用字母,数字,下划线和横杠.</p>
								<div class="pix_25"></div>
								<label>标签</label>
								<input name="tags" class="span12" id="tag" placeholder="Type something…" value="<?php if(isset($tags)){echo $tags;}?>">
								<div class="span12" id="tag_show" style="background-color: azure;margin-left:0px;display:none;"></div>
								<div class="pix_25"></div>
								<label>排序</label>
								<?php echo form_error('order', '<p class="message error">', '</p>'); ?>
								<input name="order" class="span12" placeholder="Type something…" value="<?php if(isset($order)){echo $order;}?>">
								<div class="pix_25"></div>
								<button  id="btn-save" class="btn btn-default">  保存并继续编辑 </button>
								<button  id="btn-submit" class="btn btn-default"> 更新这篇文章 »</button>
							</div>
						</div>
								<input name="hos_id" type="hidden" value="<?php echo $hos_id;?>">
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
	$(document).ready(
		function()
		{
			var obj = $("#flash_info");
			if(obj){
				setTimeout('$("#flash_info").hide("slow")',3000);
			} 
		}
	);
	KindEditor.ready(function(K) {
	window.editor = K.create('#con_content', {
					resizeType : 1,
					cssPath : '/static/editor/plugins/code/prettify.css',
					uploadJson : '/static/editor/php/upload_json.php',
					fileManagerJson : '/static/editor/php/file_manager_json.php',
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
	});
	
	$('#btn-save').click(function () {
        $('input[name=draft]').val(1);
               
    });
              
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
				data:'type=1<?php if(isset($pid)){echo '&pid='.$pid;}?>&mid=' + mid,
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
            
	/*
	var tags = [<?php echo $tag_str;?>];
	
	$('#tag').bind('input propertychange', function() {
		var tag = $('#tag').val();
		
		$.each(tags,function(key,val){
			re = '/'+val+'/i'
			str = val.search(re);
			alert(str);
		});
	});
	*/
</script>
</body>
</html>