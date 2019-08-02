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
		   <div class="widget orange">
					<div class="widget-title" style="background-color:#00a186;">
					<h4 class="site_seo_h4"><i class="icon-reorder"></i><?php echo $this->lang->line('key_data_list');?></h4>
					<span class="tools">
						<a href="javascript:;" class="icon-chevron-down"></a>
						<a href="javascript:;" class="icon-remove"></a>
					</span>
					</div>
					<div class="widget-body">
<div class="widget-body-log">
<form action="?c=site_seo&m=search_keyword_update&per_page=<?php echo $per_page;?>" method="post" class="form-horizontal">
<table width="100%" border="0" cellspacing="0" cellpadding="2" class="table table-striped table-bordered table-advance table-hover">
<thead>
  <tr>
    <th><?php echo $this->lang->line('key_name');?></th>
	<th><?php echo $this->lang->line('key_search');?></th>
	<th><?php echo $this->lang->line('key_bd_site');?></th>
	<th><?php echo $this->lang->line('key_google_site');?></th>
	<th><?php echo $this->lang->line('key_cpc');?></th>
	<th><?php echo $this->lang->line('category');?></th>
	<th><?php echo $this->lang->line('key_area');?></th>
	<th><?php echo $this->lang->line('action');?></th>
  </tr>
 </thead>
  <tbody>
    <?php foreach($keyword_list as $key => $val):?>
	 <tr id="<?php echo 'key_' . $val['pro_id'] . "_" . $val['key_id'];?>">
	  <td><?php echo $val['key_name'];?><input type="hidden" name="key_id[<?php echo $val['key_id'];?>]" value="<?php echo $val['key_id'];?>"></td>
	  <td><?php echo $val['key_search'];?></td>
	  <td><?php echo $val['bd_site'];?></td>
	  <td><?php echo $val['google_site'];?></td>
	  <td><?php echo $val['bd_cpc'];?></td>
	  <td>
	    <select name="cat_id[<?php echo $val['key_id'];?>]">
	     <option value="0"><?php echo $this->lang->line('please_select');?></option>
	     <?php foreach($cat as $key => $value):?>
		  <option value="<?php echo $value['cat_id']; ?>" <?php if($value['cat_id'] == $val['cat_id']){ echo "selected"; }?>><?php echo $value['cat_name'];?></option>
		 <?php endforeach;?>
	    </select>
	  </td>
	  <td>
	   <input type="text" name="area_name[<?php echo $val['key_id'];?>]" value="<?php echo $val['area_name'];?>"/>
	  </td>
	   <td><a class="btn btn-danger" onClick="keyword_del(<?php echo $val['pro_id'];?>, <?php echo $val['key_id'];?>)"><i class="icon-trash"></i></a></td>
	  </tr>
	<?php endforeach;?>
	<tr class="seo_keywords"><td colspan="8"><input type="submit" name="submit" class="btn btn-success" value="<?php echo $this->lang->line('pro_add');?>" class="btn" /></td></tr>
  </tbody>
</table>
</form>
<?php echo $page; ?>
</div>
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
function keyword_del(pro_id, key_id)
{
	if(confirm("确定删除吗？"))
	{
		$.ajax({
			type:'post',
			url:'?c=site_seo&m=search_key_delete',
			data:'pro_id=' + pro_id + '&key_id=' + key_id,
			success:function(data)
			{
				if(data == 1)
				{
					$("#key_" + pro_id + "_" + key_id).remove();
				}
			},
			complete: function (XHR, TS)
			{
			   XHR = null;
			}
		});
	}
}
</script>
</body>
</html>