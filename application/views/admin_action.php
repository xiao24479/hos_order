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
			  <table width="100%" border="0" cellspacing="0" cellpadding="2" class="table table-striped table-bordered table-advance table-hover">
  <thead>
  <tr>
    <th width="60"><?php echo $this->lang->line('id'); ?></th>
	<th><?php echo $this->lang->line('act_name'); ?></th>
	<th width="200"><?php echo $this->lang->line('act_action'); ?></th>
	<th><?php echo $this->lang->line('act_url'); ?></th>
	<th width="60"><?php echo $this->lang->line('order'); ?></th>
	<th width="60"><?php echo $this->lang->line('is_show'); ?></th>
	<th width="120"><?php echo $this->lang->line('action'); ?></th>
  </tr>
  </thead>
  <tbody>
  <?php
  $i = 1;
  foreach($menu as $item):
  ?>
  <tr>
    <td><?php echo $i; ?></td>
	<td><b><?php echo $item['act_name']?></b></td>
	<td><?php echo $item['act_action']?></td>
	<td><?php if(empty($item['act_url'])){ echo "-"; }else{echo $item['act_url']; }?></td>
	<td><?php echo $item['act_order']?></td>
	<td align="center"><?php if($item['is_show']):?><i class="icon-ok blue"></i><?php else:?><i class="icon-no red"></i><?php endif;?></td>
	<td><button class="btn btn-primary" onClick="go_url('?c=index&m=admin_action_info&act_id=<?php echo $item['act_id']?>')"><i class="icon-pencil"></i></button>&nbsp;<button class="btn btn-danger" onClick="go_del('?c=index&m=admin_action_del&act_id=<?php echo $item['act_id']?>')"><i class="icon-trash"></i></button></td>
  </tr>
  <?php
  if(!empty($item['son'])):
  foreach($item['son'] as $list):
  $i ++;
  ?>
  <tr>
    <td><?php echo $i; ?></td>
	<td class="son"><b><?php echo $list['act_name']?></b></td>
	<td><?php echo $list['act_action']?></td>
	<td><?php if(empty($list['act_url'])){ echo "-"; }else{echo $list['act_url']; }?></td>
	<td><?php echo $list['act_order']?></td>
	<td align="center"><?php if($list['is_show']):?><i class="icon-ok blue"></i><?php else:?><i class="icon-remove red"></i><?php endif;?></td>
	<td><button class="btn btn-primary" onClick="go_url('?c=index&m=admin_action_info&act_id=<?php echo $list['act_id']?>')"><i class="icon-pencil"></i></button>&nbsp;<button class="btn btn-danger" onClick="go_del(<?php echo $list['act_id']?>)"><i class="icon-trash"></i></button></td>
  </tr>
  <?php
  endforeach;
  endif;
  $i ++;
  ?>
  <?php endforeach; ?>
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