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
                        <div class="span9" style="margin-top:50px;">
                            <label><a href="?c=group&m=group_add">新增组</a></label>
                            <table width="100%">
                                <tr><td width="50">编号</td><td width="300">名称</td><td width="200">发布时间</td><td width="200">修改时间</td><td width="60">操作</td></tr> 
                                <?php 
                                $i=0;
                                foreach($group_list as $val){  $i++;  ?>
                                <tr <?php if(($i+1)%2==0){echo "style='background-color:#fff'";}?>><td width="50"><?=$i?></td><td width="300"><?=$val['name']?></td><td width="80"><?php echo date('Y-m-d h:i:s',$val['add_time'])?></td><td width="200"><?php echo  date('Y-m-d h:i:s',$val['add_time']);?></td><td><a href="?c=group&m=update_group&id=<?=$val['id']?>">编辑</a><a href="?c=group&m=del_group&id=<?=$val['id']?>" style="color:#00a186;margin-left:10px; cursor: pointer;" >删除</a>
                                </td></tr>
                                <?php }?>
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
   <script>
     function order_window(url){
		window.open (url, 'newwindow', 'height=600, width=1000, top=200, left=200, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=no, status=no');
	}
   </script>
</body>
</html>