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
              <div class="row-fluid" >
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
			  <table width="100%" border="0" cellspacing="0" cellpadding="2" class="table table-hover">
  <thead>
  <tr>
    <th><?php echo $this->lang->line('hos_name'); ?></th>
	<th width="200"><?php echo $this->lang->line('hos_website'); ?></th>
	<th width="100"><?php echo $this->lang->line('action'); ?></th>
  </tr>
  </thead>
  <tbody>
  <?php foreach($hos_list as $item): ?>
  <tr>
	<td><a style="color:#808080;" href="javascript:;" onClick="get_keshi(this, <?php echo $item['hos_id'];?>)"><i class="icon-plus"></i> 
	<?php if(!empty($item['ireport_hos_id'])){?>
	<?php echo $item['hos_name']."____(已绑定数据中心)";?>
	<?php }elseif(empty($item['ireport_msg'])){?>
	<?php echo $item['hos_name']."____(未绑定数据中心)";?>
	<?php }else{?>
	<?php echo $item['hos_name']."____(未绑定数据中心,".$item['ireport_msg'].")";?>
	<?php }?>
	</td>
	<td><a  style="color:#808080;"href="<?php echo $item['hos_website']; ?>" target="_blank"><?php echo $item['hos_website']; ?></a></td>
	<td><button class="btn btn-primary" onClick="go_url('?c=system&m=hospital_info&hos_id=<?php echo $item['hos_id']?>')" style="background-color:#00a186;"><i class="icon-pencil"></i></button></td>
  </tr>
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
<script>
function get_keshi(obj, hos_id)
{
	if($(obj).children("i").attr("class") == "icon-minus")
	{
		$(obj).children("i").attr("class", "icon-refresh icon-spin");
		$(".keshi_" + hos_id).slideUp(100, function(){ $(obj).children("i").attr("class", "icon-plus"); });
	}
	else
	{
		if($(".keshi_" + hos_id).attr("class") == ".keshi_" + hos_id)
		{
			$(obj).children("i").attr("class", "icon-refresh icon-spin");
			$(".keshi_" + hos_id).slideDown(100, function(){ $(obj).children("i").attr("class", "icon-minus"); });
		}
		else
		{
			$(obj).children("i").attr("class", "icon-refresh icon-spin");
			$.ajax({
				type:'post',
				url:'?c=system&m=keshi_list_ajax',
				data:'hos_id=' + hos_id + '&type=2',
				success:function(data)
				{
					$(obj).parent().parent().after(data);
				},
				complete: function (XHR, TS)
				{
				   XHR = null;
				   $(obj).children("i").attr("class", "icon-minus");
				}
			});
		}
	}
}
</script>
</body>
</html>