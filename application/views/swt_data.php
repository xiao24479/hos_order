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
</head>
<body class="fixed-top">
   <?php echo $top; ?>
   <!-- END HEADER -->
   <!-- BEGIN CONTAINER -->
   <div id="container" class="row-fluid">
      <!-- BEGIN SIDEBAR -->
      <?php echo $sider_menu; ?>
      <!-- END SIDEBAR -->
      <!-- BEGIN PAGE -->
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->
            <?php echo $themes_color_select; ?>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
<table width="100%" border="0" cellspacing="0" cellpadding="2" class="table table-hover">
  <tr>
    <th width="80"><?php echo $this->lang->line('swt_id');?></th>
	<th><?php echo $this->lang->line('swt_title');?></th>
	<th><?php echo $this->lang->line('item_title');?></th>
	<th><?php echo $this->lang->line('swt_width');?></th>
  <th width="300"><?php echo $this->lang->line('swt_action');?></th>
  </tr>
  <?php foreach($swt_list as $v){ ?>
  <tr>
    <td><?php echo $v['swt_id'];?></td>
    <td><?php echo $v['swt_title'];?></td>
    <td><?php foreach($item_list as $r){ if($r['item_id']==$v['item_id']) echo $r['item_title'];} ?></td>
    <td><?php echo $v['swt_width'];?></td>
    <td><a href="/?c=swt&m=swt_edit&swt_id=<?php echo $v['swt_id'];?>" class="btn btn-primary"><?php echo $this->lang->line('swt_edit');?></a>&nbsp;<a href="/?c=swt&m=swt_del&swt_id=<?php echo $v['swt_id'];?>" class="btn btn-danger"><?php echo $this->lang->line('swt_del');?></a></td>
  </tr>
  <?php } ?>
  </table>
            <!-- END PAGE CONTENT-->
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
      <!-- END PAGE -->
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
