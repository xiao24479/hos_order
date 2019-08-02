<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8" />
<title><?php echo $title; ?></title>
<meta content="width=device-width, initial-scale=1.0" name="viewport" />
<meta content="" name="description" />
<meta content="" name="author" /> 
 <link href="static/css/bootstrap.min.css" rel="stylesheet" />

<link href="static/css/bootstrap-responsive.min.css" rel="stylesheet" />

<link href="static/css/font-awesome.css" rel="stylesheet" />

<link href="static/css/style.css" rel="stylesheet" />

<link href="static/css/style-responsive.css" rel="stylesheet" />

<link href="static/css/style-default.css" rel="stylesheet" id="style_color" />

<link rel="stylesheet" type="text/css" href="static/css/metro-gallery.css" media="screen" />

</head>
  
<body  >
	<div class="container-fluid">
					<div class="row-fluid" style="margin-top: 10px;">
				  
    				 <div class="row-fluid">
    				 <div class="span12">
    				 <table class="list_table" width="100%" cellspacing="0" cellpadding="2" border="0">
    				 <thead>
    				 <tr class=""> 
                                  <th style="text-align:center;" width="10%">操作人</th>
                                  <th style="text-align:center;" width="10%">时间</th>
                                  <th style="text-align:center;" width="10%">操作情况</th>
                                  
    				 </tr>
    				 </thead>
    				 <tbody>
    				 <?php foreach ($order as $order_temp){?>
			 			<tr class="">
				 			<td style="text-align:center;"><?php echo $order_temp['admin_name'];?></td>
				 			<td style="text-align:center;"><?php echo date("Y-m-d H:i:s", $order_temp['time']);?></td>
				 			<td style="text-align:center;"><?php if($order_temp['turn_on'] == 1){echo '到诊';}else{echo '未到诊';}?></td> 
				 		</tr>
			 		<?php }?> 
                          </tbody>
    				 </table>
    				 </div>
    				 </div>
 
					</div>
					  
				<!-- END PAGE CONTENT-->
			 </div>
</body>
</html>