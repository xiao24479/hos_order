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
<link rel="stylesheet" type="text/css" href="static/css/datepicker.css" />
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
		   <div class="widget green">
<!--					<div class="widget-title">
						<h4><i class="icon-reorder"></i> <?php echo $this->lang->line('content_table'); ?></h4>
					<span class="tools">
						<a href="javascript:;" class="icon-chevron-down"></a>
						<a href="javascript:;" class="icon-remove"></a>
					</span>
					</div>-->
					<div class="widget-body">
<div class="clearfix">
 <div class="btn-group">
	 <a href="#info" role="button" class="btn btn-primary" data-toggle="modal"><?php echo $this->lang->line('project_add'); ?></a>
 </div>
 <div id="info" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel1">添加搜索项目</h3>
	</div>
	<form action="?c=site_seo&m=search_update" method="post">
	<div class="modal-body">
		<div class="control-group">
			<label class="control-label"><?php echo $this->lang->line('project_name');?></label>
			<div class="controls">
				<input type="text" value="" class="input-xlarge" name="pro_name" id="pro_name" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label"><?php echo $this->lang->line('project_description');?></label>
			<div class="controls">
				<textarea class="input-xlarge " rows="3" id="pro_description" name="pro_description"></textarea>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<input type="hidden" name="form_action" id="form_action" value="add" />
		<input type="hidden" name="pro_id" id="pro_id" value="" />
		<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo $this->lang->line('close'); ?></button>
		<button class="btn btn-primary"><?php echo $this->lang->line('submit'); ?></button>
	</div>
	</form>
</div>
</div>
<div class="space15"></div>

<table width="100%" border="0" cellspacing="0" cellpadding="2" class="table table-striped table-bordered table-advance table-hover">
<thead>
  <tr>
    <th width="30">#</th>
  	<th width="200"><?php echo $this->lang->line('project_name');?></th>
	<th><?php echo $this->lang->line('project_description');?></th>
	<th width="400"><?php echo $this->lang->line('action');?></th>
  </tr>
 </thead>
  <tbody>
    <?php foreach($pro_list as $key => $val):?>
	<tr>
	  <td><?php echo $val['pro_id'];?></td>
	  <td><a href="?c=site_seo&m=search_keyword&pro_id=<?php echo $val['pro_id'];?>"><?php echo $val['pro_name'];?></a></td>
	  <td><?php echo $val['pro_description'];?></td>
	  <td><button class="btn btn-success" onClick="go_url('?c=site_seo&m=search_report&pro_id=<?php echo $val['pro_id']?>')"><?php echo $this->lang->line('pro_report');?></button>&nbsp;<button class="btn btn-success" onClick="go_url('?c=site_seo&m=search_excel&pro_id=<?php echo $val['pro_id']?>')"><?php echo $this->lang->line('project_excel');?></button>&nbsp;<button class="btn btn-success" onClick="go_url('?c=site_seo&m=search_category&pro_id=<?php echo $val['pro_id']?>')"><?php echo $this->lang->line('project_cat');?></button>&nbsp;<a href="#info" role="button" class="btn btn-primary" data-toggle="modal" onClick="ajax_project(<?php echo $val['pro_id']?>)"><i class="icon-pencil"></i></a>&nbsp;<button class="btn btn-danger" onClick="go_del('?c=site_seo&m=search_del&pro_id=<?php echo $val['pro_id']?>')"><i class="icon-trash"></i></button></td>
	</tr>
   <?php endforeach;?>
  </tbody> 
</table>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
   <script src="static/js/jquery.js"></script>
   <script src="static/js/jquery.nicescroll.js" type="text/javascript"></script>
   <script type="text/javascript" src="static/js/jquery.slimscroll.min.js"></script>
   <script src="static/js/bootstrap.min.js"></script>
   <script type="text/javascript" src="static/js/bootstrap-datepicker.js"></script>
   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="static/js/excanvas.js"></script>
   <script src="static/js/respond.js"></script>
   <![endif]-->
   <script src="static/js/common-scripts.js"></script>
<script language="javascript">
function ajax_project(pro_id)
{
	$("#info").children("btn").css("display", "none");
	$.ajax({
		type:'post',
		url:'?c=site_seo&m=project_ajax',
		data:'pro_id=' + pro_id,
		success:function(data)
		{
			if(data != '')
			{
				data = $.parseJSON(data);
				$("#myModalLabel1").html("编辑搜索项目");
				$("#info").children("modal-footer").css("display", "block");
				$("#form_action").val('update');
				$("#pro_name").val(data['pro_name']);
				$("#pro_description").text(data['pro_description']);
				$("#pro_id").val(data['pro_id']);
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