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
					<div class="widget-title" style="background-color:#00a186;">
						<h4><i class="icon-reorder"></i><?php echo $pro_info['pro_name'] . '搜索项目 ' . $this->lang->line('project_cat');?></h4>
					<span class="tools">
						<a href="javascript:;" class="icon-chevron-down"></a>
						<a href="javascript:;" class="icon-remove"></a>
					</span>
					</div>
					<div class="widget-body">
<div class="clearfix">
 <div class="btn-group">
	 <a href="#info" role="button" class="btn btn-primary" data-toggle="modal"><?php echo $this->lang->line('project_cat_add'); ?></a>
 </div>
 <div id="info" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel1"><?php echo $this->lang->line('project_cat_add'); ?></h3>
	</div>
	<form action="?c=site_seo&m=search_cat_update" method="post">
	<div class="modal-body">
		<div class="control-group">
			<label class="control-label"><?php echo $this->lang->line('cat_name');?></label>
			<div class="controls">
				<input type="text" value="" class="input-xlarge" name="cat_name" id="cat_name" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label"><?php echo $this->lang->line('order');?></label>
			<div class="controls">
				<input type="text" value="50" class="input-xlarge" name="cat_order" id="cat_order" />
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<input type="hidden" name="form_action" id="form_action" value="add" />
		<input type="hidden" name="cat_id" id="cat_id" value="" />
		<input type="hidden" name="pro_id" id="pro_id" value="<?php echo $pro_info['pro_id']; ?>" />
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
  	<th><?php echo $this->lang->line('cat_name');?></th>
	<th width="200"><?php echo $this->lang->line('order');?></th>
	<th width="200"><?php echo $this->lang->line('action');?></th>
  </tr>
 </thead>
  <tbody>
    <?php foreach($cat_list as $val):?>
	<tr>
	  <td><?php echo $val['cat_id'];?></td>
	  <td><?php echo $val['cat_name'];?></td>
	  <td><?php echo $val['cat_order'];?></td>
	  <td><a href="#info" role="button" class="btn btn-primary" data-toggle="modal" onClick="ajax_search_cat(<?php echo $val['cat_id']?>)"><i class="icon-pencil"></i></a>&nbsp;<button class="btn btn-danger" onClick="go_del('?c=site_seo&m=search_cat_del&cat_id=<?php echo $val['cat_id']?>&pro_id=<?php echo $pro_info['pro_id']; ?>')"><i class="icon-trash"></i></button></td>
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
function ajax_search_cat(cat_id)
{
	$("#info").children("btn").css("display", "none");
	$.ajax({
		type:'post',
		url:'?c=site_seo&m=ajax_search_cat',
		data:'cat_id=' + cat_id,
		success:function(data)
		{
			if(data != '')
			{
				data = $.parseJSON(data);
				$("#myModalLabel1").html("编辑关键词类型");
				$("#info").children("modal-footer").css("display", "block");
				$("#form_action").val('update');
				$("#cat_name").val(data['cat_name']);
				$("#cat_order").val(data['cat_order']);
				$("#cat_id").val(data['cat_id']);
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