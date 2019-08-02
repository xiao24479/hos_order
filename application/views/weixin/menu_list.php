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
								<div class="clearfix" style="margin-bottom: 15px;">
									<div class="btn-group">
									 <button id="editable-sample_new" class="btn green" onclick="go_url('?c=weixin&amp;m=menu_data')">
										 更新到公众号
									 </button>
									 <button style="margin-left: 10px;" id="editable-sample_new" class="btn green" onclick="go_url('?c=weixin&m=menu_info')">
										 添加菜单
									 </button>
									</div>
								</div>
			  <table width="100%" border="0" cellspacing="0" cellpadding="2" class="table table-striped table-bordered table-advance table-hover">
  <thead>
  <tr>
    <th width="60">编号</th>
	<th>栏目名称</th>
	<th>类型</th>
	<th width="600">链接地址</th>
	<th>key</th>
	<th width="60">排序</th>
	<th width="120">操作</th>
  </tr>
  </thead>
  <tbody>
  <?php
  $i = 1;
  foreach($list as $item):
  ?>
  <tr>
    <td><?php echo $i; ?></td>
	<td><b><?php echo $item['name']?></b></td>
	<td>——</td>
	<td>——</td>
	<td>——</td>
	<td>0</td>
	<td><button class="btn btn-primary" onClick="go_url('?c=weixin&m=menu_info&menu_id=<?php echo $item['menu_id']?>')"><i class="icon-pencil"></i></button>&nbsp;<button class="btn btn-danger" onClick="go_del('?c=weixin&m=menu_delete&menu_id=<?php echo $item['menu_id']?>')"><i class="icon-trash"></i></button></td>
  </tr>
  <?php
  if(!empty($item['son'])):
  foreach($item['son'] as $val):
  $i ++;
  ?>
  <tr>
    <td><?php echo $i; ?></td>
	<td class="son"><b><?php echo $val['name']?></b></td>
	<td><?php echo $val['type']?></td>
	<td><?php echo $val['url']?></td>
	<td><?php echo $val['key']?></td>
	<td>0</td>
	<td><button class="btn btn-primary" onClick="go_url('?c=weixin&m=menu_info&menu_id=<?php echo $val['menu_id']?>')"><i class="icon-pencil"></i></button>&nbsp;<button class="btn btn-danger" onClick="go_del('?c=weixin&m=menu_delete&menu_id=<?php echo $val['menu_id']?>')"><i class="icon-trash"></i></button></td>
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