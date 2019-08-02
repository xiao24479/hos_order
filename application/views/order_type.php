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
				  <div class="widget purple">
<!--                            <div class="widget-title">
                                <h4><i class="icon-reorder"></i> <?php echo $this->lang->line('content_table'); ?></h4>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <a href="javascript:;" class="icon-remove"></a>
                            </span>
                            </div>-->
<div class="widget-body">
	 <div class="clearfix">
		 <div class="btn-group">
			 <button id="editable-sample_new" class="btn green" onClick="go_url('?c=order&m=type_info')">
				 Add New <i class="icon-plus"></i>
			 </button>
		 </div>
	 </div>
	 <div class="space15"></div>
<table class="table table-hover" id="editable-sample">
 <thead>
 <tr>
	 <th width="40">序号</th>
	 <th><?php echo $this->lang->line('type_name'); ?></th>
	 <th width="500"><?php echo $this->lang->line('type_desc'); ?></th>
	 <th width="50"><?php echo $this->lang->line('order'); ?></th>
	  <?php if(strcmp($_COOKIE['l_admin_action'],'all') == 0){?><th width="100">绑定数据中心状态</th><?php }?>
	 <th width="100"><?php echo $this->lang->line('action'); ?></th>
 </tr>
 </thead>
 <tbody>
<?php
if(!empty($type_list)):
    $i=0;
	foreach($type_list as $list):
            $i++;
?>
 <tr  <?php if(($i+1)%2==0){echo "style='background-color:#fff'";}?>>
 	 <td><?php echo $list['type_id'];?></td>
	 <td><?php echo $list['type_name'];?></td>
	 <td><?php echo $list['type_desc'];?></td>
	 <td><?php echo $list['type_order'];?></td>
	 <?php if(strcmp($_COOKIE['l_admin_action'],'all') == 0){?> <td> <?php if(empty($list['ireport_order_type_id'])){echo '未绑定 ';if(!empty($list['ireport_msg'])){echo $list['ireport_msg'];}}else if(!empty($list['ireport_order_type_id'])){echo '已绑定';}?></td><?php }?>
	
	 <td><button class="btn btn-primary" onClick="go_url('?c=order&m=type_info&type_id=<?php echo $list['type_id']?>')"><i class="icon-pencil"></i></button>&nbsp;<button class="btn btn-danger" onClick="go_del('?c=order&m=type_del&type_id=<?php echo $list['type_id']?>')"><i class="icon-trash"></i></button></td>
 </tr>
<?php
	endforeach;
endif;
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
   <script src="static/js/jquery-1.8.3.min.js"></script>
   <script src="static/js/jquery.nicescroll.js" type="text/javascript"></script>
   <script src="static/js/bootstrap.min.js"></script>
   <script type="text/javascript" src="static/js/jquery.slimscroll.min.js"></script>
   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="static/js/excanvas.js"></script>
   <script src="static/js/respond.js"></script>
   <![endif]-->
   <script src="static/js/common-scripts.js"></script>
</body>
</html>