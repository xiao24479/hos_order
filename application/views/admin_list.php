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
			  <table width="100%" border="0" cellspacing="0" cellpadding="2" class="table table-striped table-bordered" id="sample_1">
  <thead>
  <tr>
    <th width="50">序号</th>
    <th width="150"><?php echo $this->lang->line('name'); ?></th>
	<th width="120"><?php echo $this->lang->line('rank'); ?></th>
	<th width="120"><i class="icon-mobile-phone"></i> <?php echo $this->lang->line('tel'); ?></th>
    <th width="100"><i class="icon-comment"></i> <?php echo $this->lang->line('admin_tel_duan'); ?></th>
	<th width="120"><i class="icon-comments"></i> <?php echo $this->lang->line('qq'); ?></th>
	<th width="160"><i class="icon-envelope"></i> <?php echo $this->lang->line('email'); ?></th>
        <th width="200"><i class="icon-time"></i> <?php echo $this->lang->line('log_time'); ?></th>
     <?php if(strcmp($_COOKIE['l_admin_action'],'all') == 0){?><th width="200"> </i> 数据中心绑定状态</th><?php }?>
	<th width="200"><?php if($rank_id_arr[0] == 'all'):?><?php echo $this->lang->line('action'); ?><?php endif;?></th>
  </tr>
  </thead>
  <tbody>
  <?php
  $i = 1;
  foreach($admin_list as $item):
  ?>
  <tr<?php if($item['is_pass'] == 0){ echo " class='list_del'"; }?> >
    <td><?php echo $i; ?></td>
    <td><b><?php echo $item['admin_name']?></b> <i class="<?php if($item['admin_sex'] == 1){ echo "icon-male"; }else{ echo "icon-female"; }?>"></i>(
        <?php echo $item['admin_username'];
    ?>)-【<?php echo $item['admin_logintimes']?>】</td>
	<td><?php echo $item['rank_name']?></td>
	<td><i class="icon-mobile-phone"></i> <?php if(empty($item['admin_tel'])){ echo "-"; }else{echo $item['admin_tel']; }?></td>
    <td><i class="icon-comment"></i> <?php if(empty($item['admin_tel_duan'])){ echo "-"; }else{echo $item['admin_tel_duan']; }?></td>
	<td><i class="icon-comments"></i> <?php if(empty($item['admin_qq'])){ echo "-"; }else{echo $item['admin_qq']; }?></td>
	<td><i class="icon-envelope"></i> <?php if(empty($item['admin_email'])){ echo "-"; }else{echo $item['admin_email']; }?></td>
        <td style="line-height:16px;">上次：<?php if(empty($item['admin_lasttime'])){ echo "-"; }else{echo date("Y-m-d H:i", $item['admin_lasttime']); }?><br />现在：<?php if(empty($item['admin_nowtime'])){ echo "-"; }else{echo date("Y-m-d H:i", $item['admin_nowtime']); }?></td>
	<?php if(strcmp($_COOKIE['l_admin_action'],'all') == 0){?><td> <?php if(empty($item['ireport_admin_id'])){echo '未绑定 ';if(!empty($item['ireport_msg'])){echo $item['ireport_msg'];}}else if(!empty($item['ireport_admin_id'])){echo '已绑定';}?></td><?php }?>
	
	<td>
	    <!--<button class="btn btn-primary" onClick="go_url('?c=index&m=admin_mail&admin_id=<?php echo $item['admin_id']?>')"><i class="icon-comments-alt"></i></button>
	    <?php if(in_array($item['rank_id'], $rank_id_arr) || ($rank_id_arr[0] == 'all')):?>&nbsp;<button class="btn btn-danger" onClick="go_url('?c=index&m=admin_pro_info&admin_id=<?php echo $item['admin_id']?>')"><i class="icon-user"></i></button><?php endif;?>-->
		<!--<?php if((in_array($item['rank_id'], $rank_id_arr) && ($item['rank_id'] != $rank_id)) || ($rank_id_arr[0] == 'all') || ($item['admin_id'] == $admin_id)):?>&nbsp;<button class="btn btn-primary" onClick="go_url('?c=index&m=admin_info&admin_id=<?php echo $item['admin_id']?>')"><i class="icon-pencil"></i></button><?php endif;?>-->
        <?php $action= explode(',',$_COOKIE['l_admin_action']);if($rank_id_arr[0] == 'all' || in_array(20,$action)):?>&nbsp;<button class="btn btn-primary" onClick="go_url('?c=index&m=admin_info&admin_id=<?php echo $item['admin_id']?>')"><i class="icon-pencil"></i></button><?php endif;?></td>
  </tr>
  <?php 
  $i ++;
  endforeach; ?>
  </tbody>
  </table>
<?php echo $page; ?>  
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
   <!--动态表格JS-->
   <script type="text/javascript" src="static/js/jquery.dataTables.js"></script>
   <script type="text/javascript" src="static/js/DT_bootstrap.js"></script>
   <script src="static/js/dynamic-table.js"></script>
   <!--动态表格JS-->
   <script src="static/js/common-scripts.js"></script>
</body>
</html>