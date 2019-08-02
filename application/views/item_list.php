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
      <div class="widget orange">
<!--        <div class="widget-title">
          <h4><i class="icon-reorder"></i> 列表框</h4>
          <span class="tools"> <a href="javascript:;" class="icon-chevron-down"></a> <a href="javascript:;" class="icon-remove"></a> </span> </div>-->
        <div class="widget-body">
<table width="100%" border="0" cellspacing="0" cellpadding="2" class="table table-hover">
  <tr>
    <th width="10"><?php echo $this->lang->line('swt_id');?></th>
    <th width="190"><?php echo $this->lang->line('item_title');?></th>
	<th width="150"><?php echo $this->lang->line('item_swt_id');?></th>
	<th><?php echo $this->lang->line('item_desc');?></th>
	<th width="150"><?php echo $this->lang->line('swt_action');?></th>
  </tr>
  <?php foreach($item_list as $v){ ?>
  <tr>
    <td><?php echo $v['item_id'];?></td>
    <td><?php echo $v['item_title'];?></td>
    <td><?php echo $v['item_swt_id'];?></td>
    <td><?php echo $v['item_desc'];?></td>
    <td><a href="/?c=swt&&m=item_edit&item_id=<?php echo $v['item_id'];?>" class="btn btn-primary"><?php echo $this->lang->line('swt_edit');?></a>&nbsp;<a href="/?c=swt&&m=item_del&item_id=<?php echo $v['item_id'];?>" class="btn btn-danger"><?php echo $this->lang->line('swt_del');?></a></td>
  </tr>
  <?php } ?>
  </table>
  </div>
  </div>
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
