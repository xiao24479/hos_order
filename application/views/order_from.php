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
			 <button id="editable-sample_new" class="btn green" onClick="go_url('?c=order&m=from_info')">
				 Add New <i class="icon-plus"></i>
			 </button>
		 </div>
	 </div>
	 <div class="space15"></div>
<table class="table table-hover" id="editable-sample">
 <thead>
 <tr>
	 <th width="40">序号</th>
	 <th><?php echo $this->lang->line('from_name'); ?></th>
	 <th><?php echo $this->lang->line('hos_id'); ?></th>
	 <th><?php echo $this->lang->line('keshi_id'); ?></th>
	 <th><?php echo $this->lang->line('is_show'); ?></th>
	 <th width="50"><?php echo $this->lang->line('order'); ?></th>
	  <?php if(strcmp($_COOKIE['l_admin_action'],'all') == 0){?><th width="100">绑定数据中心状态</th><?php }?>
	 <th width="100"><?php echo $this->lang->line('action'); ?></th>
 </tr>
 </thead>
 <tbody>
<?php
if(!empty($from_list)):
	foreach($from_list as $list):
?>
 <tr style="background-color:#00a186;">
 	 <td><?php echo $list['from_id'];?></td>
	 <td><?php echo $list['from_name'];?></td>
	 <td><?php if(empty($list['hos_name'])){ echo "-"; }else{echo $list['hos_name'];}?></td>
	 <td><?php if(empty($list['keshi_name'])){ echo "-"; }else{echo $list['keshi_name'];}?></td>
	 <td><?php if($list['is_show'] == 0):?>显示<?php elseif($list['is_show'] == 1):?>隐藏<?php endif;?></td>
	 <td><?php echo $list['from_order'];?></td>
	 <?php if(strcmp($_COOKIE['l_admin_action'],'all') == 0){?><td> <?php if(empty($list['ireport_order_from_id'])){echo '未绑定 ';if(!empty($list['ireport_msg'])){echo $list['ireport_msg'];}}else if(!empty($list['ireport_order_from_id'])){echo '已绑定';}?></td><?php }?>
	
	 <td><button class="btn btn-primary" onClick="go_url('?c=order&m=from_info&from_id=<?php echo $list['from_id']?>')"><i class="icon-pencil"></i></button>&nbsp;<button class="btn btn-danger" onClick="go_del('?c=order&m=from_del&from_id=<?php echo $list['from_id']?>')"><i class="icon-trash"></i></button></td>
 </tr>
<?php
if(!empty($list['child'])):
    $i=1;
	foreach($list['child'] as $val):
            $i++;
?>
<tr  <?php if(($i)%2==0){echo "style='background-color:#fff'";}?>>
 <td>&nbsp;</td>
 <td><span class="td_child"></span><?php echo $val['from_name'];?></td>
 <td><?php if(empty($val['hos_name'])){ echo "-"; }else{echo $val['hos_name'];}?></td>
 <td><?php if(empty($val['keshi_name'])){ echo "-"; }else{echo $val['keshi_name'];}?></td>
 <td><?php if($val['is_show'] == 0):?>显示<?php elseif($val['is_show'] == 1):?>隐藏<?php endif;?></td>
 <td><?php echo $val['from_order'];?></td>
 <td> <?php if(empty($list['ireport_order_from_id'])){echo '未绑定 ';if(!empty($list['ireport_msg'])){echo $list['ireport_msg'];}}else if(!empty($list['ireport_order_from_id'])){echo '已绑定';}?></td>
	
 <td><button class="btn btn-primary" onClick="go_url('?c=order&m=from_info&from_id=<?php echo $val['from_id']?>')"><i class="icon-pencil"></i></button>&nbsp;<button class="btn btn-danger" onClick="go_del('?c=order&m=from_del&from_id=<?php echo $val['from_id']?>')"><i class="icon-trash"></i></button></td>
</tr>
<?php
	endforeach;
endif;
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