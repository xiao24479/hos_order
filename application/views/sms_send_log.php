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
                                    
<div class="widget-body">
<table class="table table-hover" id="editable-sample">
 <thead>
 <tr>
	 <th width="40">序号</th>
	 <th><?php echo $this->lang->line('send_content'); ?></th>
	 <th width="80"><?php echo $this->lang->line('send_phone'); ?></th>
	 <th width="120"><?php echo $this->lang->line('send_time'); ?></th>
	 <th width="70"><?php echo $this->lang->line('send_type'); ?></th>
	 <th width="100"><?php echo $this->lang->line('hos_name'); ?></th>
	 <th width="80"><?php echo $this->lang->line('keshi_name'); ?></th>
	 <th width="60"><?php echo $this->lang->line('status'); ?></th>
	 <th width="60"><?php echo $this->lang->line('action'); ?></th>
 </tr>
 </thead>
 <tbody>
<?php
if(!empty($sms_log_list)):
    $i=0;
	foreach($sms_log_list as $list):
            $i++;
?>
 <tr  <?php if(($i+1)%2==0){echo "style='background-color:#fff'";}?>>
 	 <td><?php echo $list['send_id'];?></td>
	 <td><p style=" line-height:20px; margin:5px 0; padding:0;"><?php echo $list['send_content'];?></p></td>
	 <td><?php echo $list['send_phone'];?></td>
	 <td><?php echo date("Y-m-d H:i:s", $list['send_time']);?></td>
	 <td><?php if($list['send_type'] == 1){ echo '<a href="?c=order&m=order_info&order_id=' . $list['type_value'] . '" target="_blank">预约</a>';}elseif($list['send_type'] == 2){ echo "手动发送";}elseif($list['send_type'] == 3){ echo "回复短信";}elseif($list['send_type'] == 4){ echo "群发短信";}else{ echo "未知";}?></td>
	 <td><?php if(empty($list['hos_name'])){ echo "-"; }else{echo $list['hos_name'];}?></td>
	 <td><?php if(empty($list['keshi_name'])){ echo "-"; }else{echo $list['keshi_name'];}?></td>
	 <td><?php $status = $this->lang->line('sms_send_status'); if($list['send_status'] == 0){ echo "发送成功";}else{ echo $status[$list['send_status']];} ?></td>
	 <td><?php if($list['send_status'] != 0):?><button class="btn btn-primary" onClick="go_url('?c=system&m=sms_send&send_id=<?php echo $list['send_id']?>')">发送</button><?php endif;?></td>
 </tr>
<?php
	endforeach;
endif;
?>
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