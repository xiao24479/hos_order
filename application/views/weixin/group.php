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
<style>
.popover {
width: 300px;
position: absolute;
margin-top: 12px;
z-index: 999;
}
.popover .popover_inner {
border: 1px solid #d9dadc;
word-wrap: break-word;
word-break: break-all;
padding: 30px 25px;
background-color: #fff;
box-shadow: none;
-moz-box-shadow: none;
-webkit-box-shadow: none;
}
.popover .popover_title {
font-weight: 400;
font-style: normal;
padding-bottom: 5px;
}
.popover_bar .btn {
min-width: 60px;
}
</style>
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
				  <div class="widget orange">
<!--                            <div class="widget-title">
                                <h4><i class="icon-reorder"></i> <?php echo $this->lang->line('content_table'); ?></h4>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <a href="javascript:;" class="icon-remove"></a>
                            </span>
                            </div>-->
                            <div class="widget-body" style="relative">
							
							<div class="clearfix" style="margin-bottom: 15px;">
							 <div class="btn-group" style="position:relative;">
								 <button id="editable-sample_new" class="btn green">
									 <i class="icon-plus"></i>添加分类
								 </button>
								 <div class="popover pos_center" style="top:26px; left: 0px; display: none;">
									<div class="popover_inner">
										<div class="popover_content jsPopOverContent"><h4 class="popover_title">分组名称</h4>
									<span class="frm_input_box">
										<input type="text" class="form-control js_name" placeholder="" value="" data-gid="">
									</span></div>

										

										<div class="popover_bar"><a href="javascript:;" class="btn btn-success js_ins">确定</a>&nbsp;<a href="javascript:;" class="btn btn_primary js_hide_ins">取消</a>&nbsp;</div>
									</div>
						
								 </div>
							 </div>
							 </div>
	
			 
			  </div>
			  </div>
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
   <script>
		$('#editable-sample_new').click(function(){
		
			$(this).next().show();
		
		});
		function edit(id,name){
			var id = id;
			var name = name;
			$(".edit").find("input").eq(0).val(name);
			$(".edit").find("input").eq(1).val(id);
			$(".edit").show();
		}
		$('.js_hide_ins').click(function(){
			$(this).parent().parent().parent().hide();
		});
		$('.js_ins').click(function(){
			var name = $(this).parent().prev().find('input').val();
			if(name == ''){
			
				alert('分组名称不能为空');
				return;
			}
			$.ajax({

				type:'post',

				url:'?c=weixin&m=create_group',

				data:'name=' + name,

				success:function(data)

				{

					alert(data)
					
				},

				complete: function (XHR, TS)

				{

				   XHR = null;
				   $('.js_ins').parent().parent().parent().hide();
				}

			});
		});
		$('.js_up').click(function(){
			
			var name = $('.js_up').parent().prev().find('input').eq(0).val();
			var id = $('.js_up').parent().prev().find('input').eq(1).val();
			if(name == ''){
			
				alert('分组名称不能为空');
				return;
			}
			$.ajax({

				type:'post',

				url:'?c=weixin&m=update_group',

				data:'name=' + name + '&id=' + id,

				success:function(data)

				{

					alert(data)
					
				},

				complete: function (XHR, TS)

				{

				   XHR = null;
				   $('.js_up').parent().parent().parent().hide();
				}

			});
		});
   </script>
</body>
</html>