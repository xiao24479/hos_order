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
<link href="static/css/bootstrap-fullcalendar.css" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="static/css/metro-gallery.css" media="screen" />
<link href="static/js/datepicker/css/datepicker.css" rel="stylesheet" />

<style type="text/css">
.date_div{ position:absolute; top:25%; left:13%; z-index:1000;}
.anniu{ display:none;}


.list_table .td2 td{background-color: linen;}
.list_table .td3 td{background-color:lightpink;}
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
					<div class="row-fluid" style="margin-top: 10px;">
				 
    				 <div class="row-fluid">
    				 <div class="span12">
    				 <table width="100%" border="0" cellspacing="0" cellpadding="2" class="list_table">
    				 <thead>
    				 <tr>
    				   			  <th width="5%" style="text-align:center;">编号</th> 
                                  <th width="60%" style="text-align:center;">内容</th>
                                  <th width="10%" style="text-align:center;">时间</th> 
    				 </tr>
    				 </thead>
    				 <tbody>
    				 <?php foreach($list_arr as $val){?>
                              <tr>
                                <td style="text-align:center;"><?php echo $val['id'];?></td>
                                <td style="text-align:center;"><?php echo $val['remark'];?></td>
                                <td style="text-align:center;"><?php echo $val['add_time'];?></td> 
                              </tr>
                              <?php }?> 
                              <tr><td colspan="9"><?php echo $page;?></td></tr>
    				 </tbody>
    				 </table>
    				 </div>
    				 </div>
 
					</div>
					  
				<!-- END PAGE CONTENT-->
			 </div>
			 <!-- END PAGE CONTAINER-->
		</div>
	</div>
 
    
</body>
</html>