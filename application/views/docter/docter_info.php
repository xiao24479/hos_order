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
<link rel="stylesheet" type="text/css" href="static/css/datepicker.css" />
<style>
.set{width:100px;}
.li{margin-top:10px;}
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
		<div class="span12" style="padding-bottom:150px;">
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
  <form action="?c=system&m=docter_update" enctype="multipart/form-data" method="post" class="form-horizontal">
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('hos_name');?></label>
	<div class="controls">
		<select name="hos_id" id="hos_id" style="width:180px;">

			<option value="">请选择医院</option>

			<?php foreach($hospital as $val):?><option value="<?php echo $val['hos_id']; ?>" <?php if($val['hos_id'] == $info['hos_id']){ echo "selected";}?> ><?php echo $val['hos_name']; ?></option><?php endforeach;?>

		</select>

		<select name="keshi_id" id="keshi_id" style="width:130px;">

			<option value="">请选择科室</option>

		</select>
	</div>
</div>

<div class="control-group">
	<label class="control-label">医生姓名</label>
	<div class="controls">
		<input type="radio" value="0" <?php if(empty($info['is_show'])){?>checked="checked"<?php }?> class="input-xlarge" name="is_show" />有效
		<input type="radio" value="1" <?php if(!empty($info['is_show'])){?>checked="checked"<?php }?> class="input-xlarge" name="is_show" />失效
	</div>
</div>

<div class="control-group">
	<label class="control-label">医生姓名</label>
	<div class="controls">
		<input type="text" value="<?php echo $info['doc_name'];?>" class="input-xlarge" name="doc_name" />
	</div>
</div>
<div class="control-group">
	<label class="control-label">职称</label>
	<div class="controls">
		<input type="text" value="<?php echo $info['doc_zc'];?>" class="input-xlarge" name="doc_zc" />
	</div>
</div>

<div class="control-group">
	<label class="control-label">医生形象</label>
	<div class="controls">
		
		<div class="span4 well">
			<a class="thumbnail">
				<img onclick="doUpload_suo()" src="<?php if(empty($info['doc_img'])):?>/static/images/no_photo.jpg<?php else: echo '/static/upload/'.$info['doc_img']; endif;?>" alt="医生形象" width="260" height="180" id="img_suo"/>
			</a>		
		</div>
	</div>
</div>
<div class="control-group">
	<label class="control-label">形象图集</label>
	<div class="controls">
		<ul class="thumbnails">
		<?php $work_img = explode(',',$info['work_img'])?>
            <li class="span3">
                <a href="#" class="thumbnail">
                  <img onclick="doUpload_work(this)" data-src="holder.js/260x180" src="<?php if(!empty($work_img[0])){ echo '/static/upload/'.$work_img[0];}else{echo '/static/images/no_photo.jpg';}?>" style="width: 260px; height: 180px;">
				   <?php if(isset($work_img[0])){ echo '<input type="hidden" name="img_work[]" value="'.$work_img[0].'"/>';} ?>
				</a>
            </li>
			<li class="span3">
                <a href="#" class="thumbnail">
                  <img onclick="doUpload_work(this)" data-src="holder.js/260x180" src="<?php if(!empty($work_img[1])){ echo '/static/upload/'.$work_img[1];}else{echo '/static/images/no_photo.jpg';}?>" style="width: 260px; height: 180px;">
				   <?php if(isset($work_img[1])){ echo '<input type="hidden" name="img_work[]" value="'.$work_img[1].'"/>';} ?>
				</a>
            </li>
			<li class="span3">
                <a href="#" class="thumbnail">
                  <img onclick="doUpload_work(this)" data-src="holder.js/260x180" src="<?php if(!empty($work_img[2])){ echo '/static/upload/'.$work_img[2];}else{echo '/static/images/no_photo.jpg';}?>" style="width: 260px; height: 180px;">
				   <?php if(isset($work_img[2])){ echo '<input type="hidden" name="img_work[]" value="'.$work_img[2].'"/>';} ?>
				</a>
            </li>
			<li class="span3">
                <a href="#" class="thumbnail">
                  <img onclick="doUpload_work(this)" data-src="holder.js/260x180" src="<?php if(!empty($work_img[3])){ echo '/static/upload/'.$work_img[3];}else{echo '/static/images/no_photo.jpg';}?>" style="width: 260px; height: 180px;">
				   <?php if(isset($work_img[3])){ echo '<input type="hidden" name="img_work[]" value="'.$work_img[3].'"/>';} ?>
				</a>
            </li>
		</ul>
	</div>
</div>
<div class="control-group">
	<label class="control-label">医生简介</label>
	<div class="controls">
		<textarea class="input-xxlarge" name="doc_des" rows="5"><?php if(isset($info['doc_des'])){echo $info['doc_des'];}?></textarea>
	</div>
</div>
<div class="control-group">
	<label class="control-label">医生详情</label>
	<div class="controls">
		<textarea class="input-xxlarge span9" id="con_content" name="doc_long" rows="15"><?php if(isset($info['doc_long'])){echo $info['doc_long'];}?></textarea>
	</div>
</div>
<div class="control-group">
	<label class="control-label">排序</label>
	<div class="controls">
		<input type="text" value="<?php echo $info['doc_order']; ?>" class="input-xlarge" name="doc_order" />
		<span>&nbsp;&nbsp;&nbsp;&nbsp;*数字越大越靠前</span>
	</div>
</div>
<div class="control-group">
	<label class="control-label">自定义</label>
	<?php if(!empty($setting)): foreach($setting as $key => $val):?>
	<div class="controls">
		<span>英文：</span>
		<input type="text" value="<?php echo $key;?>" class="input-xlarge" name="doc_tag[]" style="width:80px;"/>
		<span>中文：</span>
		<input type="text" value="<?php echo $val;?>" class="input-xlarge" name="doc_val[]" />
		<a class="btn btn-danger btn_id" onClick="select_add(this);"><i class="icon-minus"></i></a>
	</div>
	<?php endforeach; endif;?>
	<div class="controls">
		<span>英文：</span>
		<input type="text" value="" class="input-xlarge" name="doc_tag[]" style="width:80px;"/>
		<span>中文：</span>
		<input type="text" value="" class="input-xlarge" name="doc_val[]" />
		<a class="btn btn-danger btn_id" onClick="select_add(this);"><i class="icon-plus"></i></a>
	</div>
</div>
<div class="control-group">
	<div class="controls">
		<input type="hidden" name="form_action" value="update" />
		<input type="hidden" name="img_suo" value="<?php echo $info['doc_img'] ?>"/>
		<input type="hidden" name="doc_id" value="<?php echo $info['doc_id'] ?>"/>
		<button type="submit" id="submit" class="btn btn-success"><i class="icon-ok"></i> <?php echo $this->lang->line('submit'); ?> </button>
		<button type="reset" class="btn"><i class="icon-remove"></i> <?php echo $this->lang->line('reset'); ?> </button>
	</div>
</div>
  </form>
  <?php else:?>
  <form action="?c=system&m=docter_update" enctype="multipart/form-data" method="post" class="form-horizontal">
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('hos_name');?></label>
	<div class="controls">
		<select name="hos_id" id="hos_id" style="width:180px;">

			<option value="">请选择医院</option>

			<?php foreach($hospital as $val):?><option value="<?php echo $val['hos_id']; ?>" <?php if(isset($hos_id)){if($val['hos_id'] == $hos_id){ echo "selected";}}?>><?php echo $val['hos_name']; ?></option><?php endforeach;?>

		</select>

		<select name="keshi_id" id="keshi_id" style="width:130px;">

			<option value="">请选择科室</option>

		</select>
	</div>
</div>
<div class="control-group">
	<label class="control-label">医生姓名</label>
	<div class="controls">
		<input type="text" value="" class="input-xlarge" name="doc_name" />
	</div>
</div>
<div class="control-group">
	<label class="control-label">职称</label>
	<div class="controls">
		<input type="text" value="" class="input-xlarge" name="doc_zc" />
	</div>
</div>

<div class="control-group">
	<label class="control-label">医生形象</label>
	<div class="controls">
		
		<div class="span4 well">
			<a class="thumbnail">
				<img onclick="doUpload_suo()" src="<?php if(empty($thumb)):?>http://www.w3cschool.cc/try/bootstrap/php-thumb.png<?php else: echo $thumb; endif;?>" alt="医生形象" width="260" height="180" id="img_suo"/>
			</a>		
		</div>
	</div>
</div>
<div class="control-group">
	<label class="control-label">形象图集</label>
	<div class="controls">
		<ul class="thumbnails">
            <li class="span3">
                <a href="#" class="thumbnail">
                  <img onclick="doUpload_work(this)" data-src="holder.js/260x180" src="" style="width: 260px; height: 180px;">
                </a>
            </li>
			<li class="span3">
                <a href="#" class="thumbnail">
                  <img onclick="doUpload_work(this)" data-src="holder.js/260x180" src="" style="width: 260px; height: 180px;">
                </a>
            </li>
			<li class="span3">
                <a href="#" class="thumbnail">
                  <img onclick="doUpload_work(this)" data-src="holder.js/260x180" src="" style="width: 260px; height: 180px;">
                </a>
            </li>
			<li class="span3">
                <a href="#" class="thumbnail">
                  <img onclick="doUpload_work(this)" data-src="holder.js/260x180" src="" style="width: 260px; height: 180px;">
                </a>
            </li>
		</ul>
	</div>
</div>
<div class="control-group">
	<label class="control-label">医生简介</label>
	<div class="controls">
		<textarea class="input-xxlarge" name="doc_des" rows="5"></textarea>
	</div>
</div>
<div class="control-group">
	<label class="control-label">医生详情</label>
	<div class="controls">
		<textarea class="input-xxlarge span9" id="con_content" name="doc_long" rows="15"></textarea>
	</div>
</div>
<div class="control-group">
	<label class="control-label">排序</label>
	<div class="controls">
		<input type="text" value="" class="input-xlarge" name="doc_order" />
		<span>&nbsp;&nbsp;&nbsp;&nbsp;*数字越大越靠前</span>
	</div>
</div>
<div class="control-group">
	<label class="control-label">自定义</label>
	<div class="controls">
		<span>英文：</span>
		<input type="text" value="" class="input-xlarge" name="doc_tag[]" style="width:80px;"/>
		<span>中文：</span>
		<input type="text" value="" class="input-xlarge" name="doc_val[]" />
		<a class="btn btn-danger btn_id" onClick="select_add(this);"><i class="icon-plus"></i></a>
	</div>
</div>
<div class="control-group">
	<div class="controls">
		<input type="hidden" name="form_action" value="add" />
		<input type="hidden" name="tag" value="0" class="tag"/>
		<input type="hidden" name="img_suo" value=""/>
		<button type="submit" class="btn btn-success"><i class="icon-ok"></i> <?php echo $this->lang->line('submit'); ?> </button>
		<button type="reset" class="btn"><i class="icon-remove"></i> <?php echo $this->lang->line('reset'); ?> </button>
	</div>
</div>
  </form>
  <?php endif; ?>
  </div>
</div>
</div>
</div>
</div>
</div>
</div>
   <script src="static/js/jquery.js"></script>
   <script src="static/js/jquery.nicescroll.js" type="text/javascript"></script>
   <script type="text/javascript" src="static/js/jquery.slimscroll.min.js"></script>
   <script src="static/js/bootstrap.min.js"></script>
   <script type="text/javascript" src="static/js/bootstrap-datepicker.js"></script>
   <script charset="utf-8" src="static/editor/kindeditor.js"></script>
   <script charset="utf-8" src="static/editor/lang/zh_CN.js"></script>
   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="static/js/excanvas.js"></script>
   <script src="static/js/respond.js"></script>
   <![endif]-->
   <script src="static/js/common-scripts.js"></script>
   <script type="text/javascript" src="static/js/jquery.upload.js"></script>
<script>
function select_add(obj)
{
	
	var i_value = $(obj).children("i").attr("class");
	if(i_value == 'icon-plus')
	{	
		
		var html = '<div class="controls">';
		html += '<span>英文：</span>&nbsp;';
		html += '<input type="text" value="" class="input-xlarge" name="doc_tag[]" style="width:80px;"/>&nbsp;';
		html += '<span>中文：</span>&nbsp;';
		html += '<input type="text" value="" class="input-xlarge" name="doc_val[]" />&nbsp;';
		html += '<a class="btn btn-danger btn_id" onClick="select_add(this);"><i class="icon-minus"></i></a></div>';
		$(obj).parent().after(html);
	}
	else
	{
		$(obj).parent().remove();
	}
}
$("#hos_id").change(function(){
		var hos_id = $(this).val();

		ajax_get_keshi(hos_id, 0);

	});
function ajax_get_keshi(hos_id, check_id)

{

	$.ajax({

		type:'post',

		url:'?c=order&m=keshi_list_ajax',

		data:'hos_id=' + hos_id + '&check_id=' + check_id,

		success:function(data)

		{

			$("#keshi_id").html(data);

		},

		complete: function (XHR, TS)

		{

		   XHR = null;

		}

	});

}

function static_del(obj){

	var key = $(obj).prev(":input").val();
	$.ajax({

		type:'post',

		url:'?c=system&m=static_del_ajax',

		data:'key=' + key ,

		success:function(data)

		{

			$(obj).parent().remove();

		},

		complete: function (XHR, TS)

		{

		   XHR = null;

		}
	});
}

function time_sel(obj){
		var shijian = $(obj).children("a").html();
		$(obj).parent().parent().prev().val(shijian);
		$(obj).parent().parent().prev().focus();
}
	
function time_clean(obj){
		$(obj).parent().parent().prev().val("");
		$(obj).parent().parent().prev().focus();
	};
	
function static_add(obj){
	var shijian = $(obj).parent().children("div:eq(0)").children(":input").val();
	var shang = $(obj).parent().children("div:eq(1)").children('div').children(":input").val();
	var xia = $(obj).parent().children("div:eq(2)").children('div').children(":input").val();
	var num = $(obj).parent().children("div:eq(3)").children(":input").val();
	$.ajax({

		type:'post',

		url:'?c=system&m=static_add_ajax',

		data:'shijian=' + shijian + '&shang=' + shang + '&xia=' + xia + '&num=' + num + '&doc_id= <?php if(!empty($doc_id)){echo $doc_id;}else{echo '';}?>',

		success:function(data)

		{

	
			$(obj).parent().parent().children('label:first').after(data);
			$(obj).parent().remove();

		},

		complete: function (XHR, TS)

		{

		   XHR = null;

		}

	});
};

<?php if(!empty($info)):?>

ajax_get_keshi(<?php echo $info['hos_id'];?>, <?php echo $info['keshi_id'];?>);
<?php endif;?>
<?php if(isset($hos_id)):?>

ajax_get_keshi(<?php echo $hos_id;?>, 0);
<?php endif;?>
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
  function doUpload_suo() {
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
					$("input[name='img_suo']").val(data.name);
				}
		});
	}
	  function doUpload_work(obj) {
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
					$(obj).attr("src", data.url); 	
					input = $(obj).next("input[name='img_work[]']"); 
					if(input){
						input.val(data.name);
					}else{
						$(obj).after('<input type="hidden" name="img_work[]" value="'+data.name+'"/>');
					}
				}
		});
	} 

</script>

</body>
</html>