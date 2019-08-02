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
                                           
                                                <div class="span9" style="margin-top:50px;">
                                                    
                                                    <table width="100%" style="color:#808080;">
                                                        <tr style="color:#00a186;font-size:16px;"><td width="50">编号</td><td width="300">标题</td><td width="80">发布时间</td><td width="200">发布人</td><td width="60">操作</td></tr> 
                                                        <?php 
                                                        $i=0;
                                                        foreach($mes_list as $val){
                                                            $i++;
                                                            if(!empty($no_read)){
                                                            
                                                           
                                                                if(in_array($val['message_id'], $no_read)){
                                                                    ?>
                                                                 <tr <?php if(($i+1)%2==0){echo "style='background-color:#fff;margin-top:3px;'";}?>>
                                                                  <td width="50" style="color:red;"><a href="" style="color:#808080; cursor: pointer;" onclick="order_window('?c=sys_message&m=message_content&id=<?=$val['message_id']?>')"><?=$i?></a></td>
                                                                  <td width="300"><a href="" style="color:red; cursor: pointer;" onclick="order_window('?c=sys_message&m=message_content&id=<?=$val['message_id']?>')"><?=$val['message_title']?></a></td>
                                                                  <td width="80"><a href="" style="color:red; cursor: pointer;" onclick="order_window('?c=sys_message&m=message_content&id=<?=$val['message_id']?>')"><?php echo date('Y-m-d',$val['message_time'])?></a></td>
                                                                  <td width="200"><a href="" style="color:red; cursor: pointer;" onclick="order_window('?c=sys_message&m=message_content&id=<?=$val['message_id']?>')"><?=$val['message_user']?></a></td>
                                                                  <td><a href="" style="color:red; cursor: pointer;" onclick="order_window('?c=sys_message&m=message_content&id=<?=$val['message_id']?>')">查看</a>  <span style="background-color: red;color:#fff;margin-left: 10px">&nbsp;未读&nbsp;</span></td></tr>
                                                                        <?php 
                                                                }else{?>
                                                      
                                                               <tr <?php if(($i+1)%2==0){echo "style='background-color:#fff;margin-top:3px;'";}?>>
                                                                  <td width="50" style="color:#808080;"><a href="" style="color:#808080; cursor: pointer;" onclick="order_window('?c=sys_message&m=message_content&id=<?=$val['message_id']?>')"><?=$i?></a></td>
                                                                  <td width="300"><a href="" style="color:#808080; cursor: pointer;" onclick="order_window('?c=sys_message&m=message_content&id=<?=$val['message_id']?>')"><?=$val['message_title']?></a></td>
                                                                  <td width="80"><a href="" style="color:#808080; cursor: pointer;" onclick="order_window('?c=sys_message&m=message_content&id=<?=$val['message_id']?>')"><?php echo date('Y-m-d',$val['message_time'])?></a></td>
                                                                  <td width="200"><a href="" style="color:#808080; cursor: pointer;" onclick="order_window('?c=sys_message&m=message_content&id=<?=$val['message_id']?>')"><?=$val['message_user']?></a></td>
                                                                  <td><a href="" style="color:#00a186; cursor: pointer;" onclick="order_window('?c=sys_message&m=message_content&id=<?=$val['message_id']?>')">查看</a></td></tr>   
                                                            <?php
                                                                }
                                                                                
                                                            }else{?>
                                                                
                                                              <tr <?php if(($i+1)%2==0){echo "style='background-color:#fff;margin-top:3px;'";}?>>
                                                                  <td width="50" style="color:#808080;"><a href="" style="color:#808080; cursor: pointer;" onclick="order_window('?c=sys_message&m=message_content&id=<?=$val['message_id']?>')"><?=$i?></a></td>
                                                                  <td width="300"><a href="" style="color:#808080; cursor: pointer;" onclick="order_window('?c=sys_message&m=message_content&id=<?=$val['message_id']?>')"><?=$val['message_title']?></a></td>
                                                                  <td width="80"><a href="" style="color:#808080; cursor: pointer;" onclick="order_window('?c=sys_message&m=message_content&id=<?=$val['message_id']?>')"><?php echo date('Y-m-d',$val['message_time'])?></a></td>
                                                                  <td width="200"><a href="" style="color:#808080; cursor: pointer;" onclick="order_window('?c=sys_message&m=message_content&id=<?=$val['message_id']?>')"><?=$val['message_user']?></a></td>
                                                                  <td><a href="" style="color:#00a186; cursor: pointer;" onclick="order_window('?c=sys_message&m=message_content&id=<?=$val['message_id']?>')">查看</a></td></tr>   
                                                           <?php }
                                                        }?>
                                                    </table>
							
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
	});
       function order_window(url)

{

	window.open (url, 'newwindow', 'height=600, width=1000, top=200, left=200, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=no, status=no');

}
   
   </script>
</body>
</html>
