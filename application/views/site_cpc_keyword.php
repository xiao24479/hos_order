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
			<input type="hidden" value="cpc_keyword" name="m" />
			<input type="hidden" value="<?php echo $type; ?>" name="type" />
			<input type="hidden" value="<?php echo $site_info['site_id']; ?>" name="site_id" />
              <div class="row-fluid">
				<div class="filter_wrapper">
					<a href="?c=site&m=cpc&site_id=<?php echo $site_info['site_id']; ?>" class="filter"><?php echo $this->lang->line('cpc_account_data'); ?></a>
					<div class="filter selected"><?php echo $this->lang->line('cpc_keyword_data'); ?></div>
                    <a href="?c=site&m=cpc_plan_data&site_id=<?php echo $site_info['site_id']; ?>" class="filter"><?php echo $this->lang->line('cpc_plan_data'); ?></a>
                    <a href="?c=site&m=cpc_hour&site_id=<?php echo $site_info['site_id']; ?>" class="filter"><?php echo $this->lang->line('cpc_hour'); ?></a>
					<div class="span3" style="float:right;"><input type="text" value="<?php echo $start; ?> - <?php echo $end; ?>" class="input-block-level" name="date" id="inputDate" /></div>
					<div class="clear"></div>
				</div>
				<div class="date_div"><div class="data_option">
				<div class="control-group">
					<label class="control-label">排序</label>
					<div class="controls">
					<select name="by">
					   <option value="0"><?php echo $this->lang->line('please_select');?></option>
					   <option value="1"<?php if($by == 1){ echo " selected";}?>>展现量</option>
					   <option value="2"<?php if($by == 2){ echo " selected";}?>>点击量</option>
					   <option value="3"<?php if($by == 3){ echo " selected";}?>>点击率</option>
					   <option value="4"<?php if($by == 4){ echo " selected";}?>>均价</option>
					   <option value="5"<?php if($by == 5){ echo " selected";}?>>消费</option>
					   <option value="6"<?php if($by == 6){ echo " selected";}?>>千次展现费用</option>
					   <option value="7"<?php if($by == 7){ echo " selected";}?>>对话次数</option>
					   <option value="8"<?php if($by == 8){ echo " selected";}?>>对话成本</option>
					   <option value="9"<?php if($by == 9){ echo " selected";}?>>对话率</option>
					   <option value="10"<?php if($by == 10){ echo " selected";}?>>预约数</option>
					   <option value="11"<?php if($by == 11){ echo " selected";}?>>预约率</option>
					   <option value="12"<?php if($by == 12){ echo " selected";}?>>到诊数</option>
					   <option value="13"<?php if($by == 13){ echo " selected";}?>>到诊率</option>
					</select>
					</div>
					<div class="controls">
					<select name="order">
					   <option value="0"><?php echo $this->lang->line('please_select');?></option>
					   <option value="1"<?php if($order == 1){ echo " selected";}?>>升序</option>
					   <option value="2"<?php if($order == 2){ echo " selected";}?>>降序</option>
					</select>
					</div>
					<div class="controls">
					
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
                            <div class="widget-title" style="position:relative;">
                                <h4><i class="icon-reorder"></i> <?php echo $site_info['site_name'] . " (" . $site_info['site_domain'] . ") " . $this->lang->line('cpc_keyword_data'); ?><select name="type" class="title_select"><option value=""<?php if(empty($type)){ echo " selected "; }?>><?php echo $this->lang->line('all_type'); ?></option><option value="pc"<?php if($type == 'pc'){ echo " selected "; }?>><?php echo $this->lang->line('pc_type'); ?></option><option value="mobile"<?php if($type == 'mobile'){ echo " selected "; }?>><?php echo $this->lang->line('mobile_type'); ?></option></select></h4>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <a href="javascript:;" class="icon-remove"></a>
                            </span>
                            </div>
				<div class="widget-body">
				    <?php if(empty($keyword_data)):?>暂无数据<?php else:?>
<table width="100%" border="0" cellspacing="0" cellpadding="2" class="table table-hover">
  <thead>
  <tr>
	<th>关键词</th>
	<th width="70">展示次数</th>
	<th width="70">点击次数</th>
	<th width="50">点击率</th>
	<th width="50">均价</th>
    <th width="50">消费</th>
	<th width="85">千次展示费用</th>
    <th width="60">对话次数</th>
	<th width="60">对话成本</th>
	<th width="45">对话率</th>
    <th width="45">预约数</th>
	<th width="45">预约率</th>
    <th width="45">到诊数</th>
	<th width="45">到诊率</th>
  </tr>
  </thead>
<tbody>
<?php foreach($keyword_data as $val):?>
<tr>
	<td><?php echo $val['keyword'];?></td>
	<td><?php echo $val['shows'];?></td>
	<td><?php echo $val['clicks'];?></td>
	<td><?php echo $val['click_lv'];?>%</td>
	<td><?php echo $val['price'];?></td>
	<td><?php echo $val['cost'];?></td>
	<td><?php echo $val['qian_show_cost'];?></td>
    <td><?php echo empty($val['ask_count'])? '':$val['ask_count'];?></td>
    <td><?php echo ($val['ask_cb'] == 0.00)? '':$val['ask_cb'];?></td>
    <td><?php echo ($val['ask_lv'] == 0.00)? '':$val['ask_lv'] . '%';?></td>
	<td><?php echo empty($val['order_count'])? '':$val['order_count'];?></td>
    <td><?php echo ($val['order_lv'] == 0.00)? '':$val['order_lv'] . '%';?></td>
    <td><?php echo empty($val['dao_count'])? '':$val['dao_count'];?></td>
	<td><?php echo ($val['dao_lv'] == 0.00)? '':$val['dao_lv'] . '%';?></td>
</tr>
<?php endforeach;?>
</tbody>
</table>
                    <?php echo $page; ?><?php endif;?>
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
   <script src="static/js/c/esl.js"></script>
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
	
	$(".title_select").change(function(){
		var value = $(this).val();
		var url = "?c=site&m=cpc_keyword&site_id=<?php echo $site_info['site_id'];?>&date=<?php echo $start; ?> - <?php echo $end; ?>&per_page=<?php echo $page_no; ?>";
		window.location.href = url + "&type=" + value;
	});
});
   </script>
</body>
</html>