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
td{color:#666;}
.td1 td{background-color:#efefef;}
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
			    <div class="span12">
				  <div class="widget orange">
<!--                            <div class="widget-title">
                                <h4><i class="icon-reorder"></i> <?php echo $this->lang->line('content_table'); ?></h4>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <a href="javascript:;" class="icon-remove"></a>
                            </span>
                            </div>-->
                            <div class="widget-body">
								<div class="clearfix" style="margin-bottom: 15px;">
									<div class="btn-group">
									 <button id="editable-sample_new" class="btn green" onclick="go_url('?c=system&m=site_info')">
										 添加网站
									 </button>
									</div>
								</div>
			  <table width="100%" border="0" cellspacing="0" cellpadding="2" class="table table-advance">
  <thead>
  <tr>
    <th width="10">#</th>
	<th>短信名称</th>
    <th>接口</th>
	<th width="200"><?php echo $this->lang->line('action'); ?></th>
  </tr>
  </thead>
  <tbody>
  <?php
  $i = 1;
  foreach($sms_list as $item):
 
  ?>
  <tr<?php if($i % 2){ echo " class='td1'";}?>>
    <td><?php echo $i; ?></td>
    <td><?php echo $item['sms_name']?></td>
    <td><?php echo $item['sms_int']?></td>
	<td>
	<button class="btn btn-primary" onClick="go_url('?c=system&m=sms_info&sms_id=<?php echo $item['sms_id']?>')"><i class="icon-pencil"></i></button>&nbsp;<button class="btn btn-danger" onClick="go_del('?c=system&m=sms_delete&sms_id=<?php echo $item['sms_id']?>')"><i class="icon-trash"></i></button>
	</td>
  </tr>

  <?php
  $i ++;
  endforeach;
  ?>
  </tbody>
  </table>
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
</body>
</html>