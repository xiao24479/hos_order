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
			 <a href="#info" role="button" class="btn btn-primary" data-toggle="modal"><?php echo $this->lang->line('add'); ?></a>
		 </div>
		 <div id="info" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel1">添加未到诊原因</h3>
			</div>
			<form action="?c=order&m=dao_false_update" method="post">
			<div class="modal-body">
				<div class="control-group">
					<label class="control-label"><?php echo $this->lang->line('false_name');?></label>
					<div class="controls">
						<input type="text" value="" class="input-xlarge" name="false_name" id="false_name" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo $this->lang->line('order');?></label>
					<div class="controls">
						<input type="text" value="50" class="input-xlarge" name="false_order" id="false_order" />
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<input type="hidden" name="form_action" id="form_action" value="add" />
				<input type="hidden" name="false_id" id="false_id" value="" />
				<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo $this->lang->line('close'); ?></button>
				<button class="btn btn-primary"><?php echo $this->lang->line('submit'); ?></button>
			</div>
			</form>
		</div>
	 </div>
	 <div class="space15"></div>
<table class="table table-hover" id="editable-sample">
 <thead>
 <tr>
	 <th width="40">序号</th>
	 <th><?php echo $this->lang->line('false_name'); ?></th>
	 <th><?php echo $this->lang->line('order'); ?></th>
	 <th width="100"><?php echo $this->lang->line('action'); ?></th>
 </tr>
 </thead>
 <tbody>
 <?php
 $i=0;
 foreach($false as $list):
     $i++;?>
 <tr class=""  <?php if(($i+1)%2==0){echo "style='background-color:#fff'";}?>>
 	 <td><?php echo $list['false_id'];?></td>
	 <td><?php echo $list['false_name'];?></td>
	 <td><?php echo $list['false_order'];?></td>
	 <td><a href="#info" role="button" class="btn btn-primary" data-toggle="modal" onClick="ajax_false(<?php echo $list['false_id']?>)"><i class="icon-pencil"></i></a>&nbsp;<button class="btn btn-danger" onClick="go_del('?c=order&m=dao_false_del&false_id=<?php echo $list['false_id']?>')"><i class="icon-trash"></i></button></td>
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
 <script language="javascript">
function ajax_false(false_id)
{
	$("#info").children("btn").css("display", "none");
	$.ajax({
		type:'post',
		url:'?c=order&m=dao_false_ajax',
		data:'false_id=' + false_id,
		success:function(data)
		{
			if(data != '')
			{
				data = $.parseJSON(data);
				$("#myModalLabel1").html("编辑未到诊原因");
				$("#info").children("modal-footer").css("display", "block");
				$("#form_action").val('update');
				$("#false_name").val(data['false_name']);
				$("#false_order").val(data['false_order']);
				$("#false_id").val(data['false_id']);
			}
		},
		complete: function (XHR, TS)
		{
		   XHR = null;
		}
	});
}
 </script>
</body>
</html>