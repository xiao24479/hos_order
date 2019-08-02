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
					
					<form method="post" action="?c=group&m=set_group">
                        <div class="span9" style="margin-top:50px;">
							<div class="content"> 
                                 <label>组</label>
                                 <select name="id">
                                    <option value="0">请选择</option>
                                   <?php  foreach($parnt_list as $val){?>
                                    <option value="<?php echo $val['id'];?>"  <?php if($admin_group == $val['id']){?>selected="selected"<?php }?>><?php echo $val['name'];?></option>
                                    <?php  foreach($parnt_two_list as $val_two){ 
                                        if($val_two['parent_id'] == $val['id']){?>
                                           <option value="<?php echo $val['id'];?>"  <?php if($admin_group == $val_two['id']){?>selected="selected"<?php }?>>------<?php echo $val['name'];?></option>
                                        <?php } ?>                             
                                    <?php  }
									 }?>
                                 </select> 
							    <div class="pix_25"></div>  <p><input type="submit" value="提交"/></p> </div> 
						</div>
						<!-- 岗位ID -->
						<input type="hidden" name="rank_id" value="<?php echo $rank_id;?>">
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
</body>
</html>