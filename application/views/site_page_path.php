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
<link rel="stylesheet" type="text/css" href="static/css/metro-gallery.css" media="screen" />
<link href="static/js/datepicker/css/datepicker.css" rel="stylesheet" />
<style>
table tr td font{ color:#F00;}
.table tr td{ line-height:20px; padding:10px; margin:0;}
</style>
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
            <form action="" method="get" class="date_form">
			<input type="hidden" value="site" name="c" />
			<input type="hidden" value="site_page_path" name="m" />
			<input type="hidden" value="<?php echo $site_info['site_id']; ?>" name="site_id" />
            <input type="hidden" value="<?php echo $domain_id; ?>" name="domain_id" />
			  <div class="row-fluid">
				<div class="filter_wrapper">
					<a href="?c=site&m=data&site_id=<?php echo $site_info['site_id']; ?>&domain_id=<?php echo $domain_id; ?>" class="filter"><?php echo $this->lang->line('chart_data'); ?></a>
					<a href="?c=site&m=area_data&site_id=<?php echo $site_info['site_id']; ?>&domain_id=<?php echo $domain_id; ?>" class="filter"><?php echo $this->lang->line('area_data'); ?></a>
					<a href="?c=site&m=order_area_data&site_id=<?php echo $site_info['site_id']; ?>&domain_id=<?php echo $domain_id; ?>" class="filter"><?php echo $this->lang->line('order_area_data'); ?></a>
					<a href="?c=site&m=site_from_data&site_id=<?php echo $site_info['site_id']; ?>&domain_id=<?php echo $domain_id; ?>" class="filter"><?php echo $this->lang->line('site_from_data'); ?></a>
					<a href="?c=site&m=site_page_views&site_id=<?php echo $site_info['site_id']; ?>&domain_id=<?php echo $domain_id; ?>" class="filter"><?php echo $this->lang->line('site_page_views'); ?></a>
                    <div class="filter selected"><?php echo $this->lang->line('site_page_path'); ?></div>
                    <div class="span3" style="float:right;"><input type="text" value="<?php echo $start; ?> - <?php echo $end; ?>" class="input-block-level" name="date" id="inputDate" /></div>
					<div class="clear"></div>
				</div>
                <div class="date_div">
                <div class="data_option">
				<div class="control-group" style="float:left; padding-left:20px;">
                    <label class="control-label">用户ID</label>
					<div class="controls">
                    <input type="text" value="<?php echo $cid; ?>" class="input-large" name="cid" />
					</div>
				</div>
                <div class="control-group" style="float:left; padding-left:20px;">
					<label class="control-label">来源方式</label>
					<div class="controls">
					<select name="from_site">
					   <option value="0"><?php echo $this->lang->line('please_select');?></option>
                       <?php foreach($from_site_list as $key=>$val):?>
					   <option value="<?php echo $val['site_id']; ?>"<?php if($from_site == $val['site_id']){ echo " selected";}?>><?php echo $val['site_domain'];?></option>
                       <?php endforeach;?>
					</select>
					</div>
					<div class="controls">
					<select name="from_type">
					   <option value="0"><?php echo $this->lang->line('please_select');?></option>
					   <?php foreach($from_type_list as $key=>$val):?>
					   <option value="<?php echo $val['type_id']; ?>"<?php if($from_type == $val['type_id']){ echo " selected";}?>><?php echo $val['type_name'];?></option>
                       <?php endforeach;?>
					</select>
					</div>
                    <label class="control-label">是否对话</label>
					<div class="controls">
                    <select name="is_ask">
					   <option value="0"><?php echo $this->lang->line('please_select');?></option>
                       <option value="1"<?php if($is_ask == 1):?> selected<?php endif; ?>><?php echo $this->lang->line('yes');?></option>
                       <option value="2"<?php if($is_ask == 2):?> selected<?php endif; ?>><?php echo $this->lang->line('no');?></option>
					</select>
					</div>
				</div>
				</div>
				<div class="divdate"></div>
				<div class="anniu"><button type="submit" class="btn btn-success"> 确定 </button><br /><button id="reset" type="reset" class="btn"> 取消 </button></div>
				</div>
			  </div>
              </form>
              <div class="row-fluid">
			    <div class="span12">
				  <div class="widget purple">
                            <div class="widget-title">
                                <h4><i class="icon-reorder"></i> <?php
                                echo $site_info['site_name'] . " ("; 
								if($site_domain == 'www'){ echo "总数据";} else {echo $site_domain;}
								echo  ")";
								?> <?php echo $this->lang->line('chart_data'); ?></h4>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <a href="javascript:;" class="icon-remove"></a>
                            </span>
                            </div>
				<div class="widget-body">
<table width="100%" border="0" cellspacing="0" cellpadding="2" class="table table-striped table-bordered table-hover">
<thead>
    <tr>
        <th width="200">用户信息</th>
        <th width="200">来源信息</th>
        <th width="40">对话</th>
        <th>页面轨迹</th>
    </tr>
</thead>
<tbody>
    <?php
    foreach($page_path['visitor'] as $key => $val):
	if(!empty($key)):
	?>
    <tr>
        <td>
          <font><?php echo $key; ?></font> [<?php $area = $this->lang->line('gg_area'); echo isset($area[$val['vis_city']])? $area[$val['vis_city']]:$val['vis_city']; ?>]<br /><?php echo date("Y-m-d H:i", $val['load_time']); ?><br />
          <?php echo $google_device[$val['dev_id']]['dev_name']?>、<?php echo $google_screen[$val['scr_id']]['scr_name']?><br /><?php echo $google_browser[$val['bro_id']]['bro_name']?>、<?php echo $google_system[$val['sys_id']]['sys_name']?>
        </td>
        <td>
		  <?php
		  if(!empty($val['key_name']))
		  {
			  echo "<font title=\"" . $val['key_name'] . "\">" . sub_str($val['key_name'], 20) . "</font><br />";
		  }
          if($val['from_site_id'])
          {
              echo "来源渠道：" . $from_site_list[$val['from_site_id']]['site_name'] . "<br />";
              echo "来路方式：" . $from_type_list[$val['from_type_id']]['type_name'];
          }
          else
          {
              echo "<a href=\"" . $val['from_url'] . "\" target=\"_blank\" title=\"" . $val['from_url'] . "\">" . sub_str($val['from_url'], 30) . "</a>";
          }
          ?>
        </td>
        <td>
          <?php 
		  if($val['is_ask'])
		  {
			  echo "<i class=\"icon-ok\" style=\"color:#ff0000\"></i>";
		  }
		  else
		  {
			  echo "<i class=\"icon-remove\"></i>";
		  }
		  ?>
        </td>
        <td>
        <?php
		if(isset($page_path['path'][$key]))
		{
			$i = 1;
			foreach($page_path['path'][$key] as $v)
			{
				//echo date("Y-m-d H:i", $v['path_time']) . " <font>从</font> <a href=\"" . $v['path_pre'] . "\" title=\"" . $v['path_pre'] . "\" target=\"_blank\">" . sub_str($v['path_pre'], 30) . "</a> <font>到</font> <a href=\"http://" . $v['path_url'] . "\" title=\"" . $v['path_title'] . "\" target=\"_blank\">" . sub_str($v['path_title'], 30) . "</a><br />";
				echo $i . "、" . $v['path_title'];
				if($v['path_url_reload'] > 1)
				{
					echo "&nbsp;&nbsp;&nbsp;&nbsp;<font>[" . $v['path_url_reload'] . "]</font>";
				}
				echo "<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"http://" . $v['path_url'] . "\" title=\"" . $v['path_title'] . "\" target=\"_blank\">" . sub_str($v['path_url'], 80) . "</a><br />";
				$i ++;
			}
		}
		?>
        </td>
    </tr>
    <?php
    endif;
	endforeach;
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
   <script src="static/js/datepicker/js/datepicker.js"></script> 
   <script language="javascript">
$(function() {
	$('.date_div').slideUp(1);
    $('.divdate').DatePicker({
		flat: true,
		date: ['<?php echo $start; ?>','<?php echo $end; ?>'],
		current: '<?php echo $current; ?>',
		format: 'Y年m月d日',
		calendars: 3,
		mode: 'range',
		starts: 1,
		onChange: function(formated) {
			$('#inputDate').val(formated.join(' - '));
		}
	});
	
	$('#inputDate').click(function(){
		$('.date_div').slideDown(200);
	});
	
	$("#reset").click(function(){
		$('.date_div').slideUp(200);
	});
});
   </script>
</body>
</html>