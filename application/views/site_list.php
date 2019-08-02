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
<style>
td{color:#666;}
.td1 td{background-color:#efefef;}
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
              <div class="row-fluid">
			    <div class="span12">
				  <div class="widget orange">
                            <div class="widget-title" style="background-color: #00a186;">
                                <h4><i class="icon-reorder"></i> <?php echo $this->lang->line('content_table'); ?></h4>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <a href="javascript:;" class="icon-remove"></a>
                            </span>
                            </div>
                            <div class="widget-body">
			  <table width="100%" border="0" cellspacing="0" cellpadding="2" class="table table-advance">
  <thead>
  <tr>
    <th width="10">#</th>
	<th><?php echo $this->lang->line('site_name'); ?></th>
    <th><?php echo $this->lang->line('site_domain'); ?></th>
	<th width="80"><?php echo $this->lang->line('site_ask'); ?></th>
	<th width="80"><?php echo $this->lang->line('site_order'); ?></th>
	<th width="80"><?php echo $this->lang->line('site_uv'); ?></th>
	<th width="80"><?php echo $this->lang->line('site_pv'); ?></th>
    <!--<th width="80">跳出率</th>-->
    <!--<th width="80">平均访问时长</th>-->
    <!--<th width="80">新访客比率</th>-->
    <!--<th width="80">平均访问量</th>-->
	<th><?php echo $this->lang->line('action'); ?></th>
  </tr>
  </thead>
  <tbody>
  <?php
  $i = 1;
  foreach($site_list as $item):
  if($item['data_count'] <= 1):
  ?>
  <tr<?php if($i % 2){ echo " class='td1'";}?>>
    <td><?php echo $i; ?></td>
    <td><a href="?c=site&m=data&site_id=<?php echo $item['site_id']?>&domain_id=0" title="<?php echo $item['site_name']?>"><?php echo $item['site_name']?></a></td>
	<td><?php echo $item['site_show_domain']?></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
    <!--<td><?php if(!empty($item['site_visitBounceRate'])){ echo $item['site_visitBounceRate'] . "%";}?></td>-->
    <!--<td><?php if(!empty($item['site_avgTimeOnSite'])){ echo $item['site_avgTimeOnSite'] . "(s)";}?></td>-->
    <!--<td><?php if(!empty($item['site_percentNewVisits'])){ echo $item['site_percentNewVisits'] . "%";}?></td>-->
    <!--<td><?php if(!empty($item['site_pageviewsPerVisit'])){ echo $item['site_pageviewsPerVisit'];}?></td>-->
	<td>
         <!--<?php if($item['site_bd']):?><div class="btn-group dropdown">
	<button class="btn btn-success" onClick="go_url('?c=site&m=cpc&site_id=<?php echo $item['site_id']?>')"><?php echo $this->lang->line('bd_cpc');?></button>
	<button class="btn btn-success dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
	<ul class="dropdown-menu">
		<li><a href="?c=site&m=cpc_account&site_id=<?php echo $item['site_id']?>"><?php echo $this->lang->line('cpc_account'); ?></a></li>
		<li><a href="?c=site&m=cpc_plan&site_id=<?php echo $item['site_id']?>"><?php echo $this->lang->line('cpc_plan'); ?></a></li>
	</ul>
</div><?php endif; ?><div class="btn-group dropdown">
	<button class="btn btn-inverse"><?php echo $this->lang->line('seo');?></button>
	<button class="btn btn-inverse dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
	<ul class="dropdown-menu">
	  <li><a href="?c=site_seo&m=seo_data&site_id=<?php echo $item['site_id']?>"><?php echo $this->lang->line('included-data');?></a></li>
	  <li><a href="?c=site_seo&m=seo_keywords_pm&site_id=<?php echo $item['site_id']?>"><?php echo $this->lang->line('keyword-pm');?></a></li>
	</ul>
</div>-->
<button class="btn btn-primary" onClick="go_url('?c=site&m=site_info&site_id=<?php echo $item['site_id']?>')"><i class="icon-pencil"></i></button>&nbsp;<button class="btn btn-danger" onClick="go_del('?c=site&m=site_delete&site_id=<?php echo $item['site_id']?>')"><i class="icon-trash"></i></button>
</td>
  </tr>
  <?php
  $i ++;
  else:
  ?>
  <tr<?php if($i % 2){ echo " class='td1'";}?>>
    <td rowspan="<?php echo $item['data_count']?>"><?php echo $i; ?></td>
    <td rowspan="<?php echo $item['data_count']?>"><a href="?c=site&m=data&site_id=<?php echo $item['site_id']?>&domain_id=0" title="<?php echo $item['site_name']?>"><?php echo $item['site_name']?></a></td>
    <td><a href="?c=site&m=data&site_id=<?php echo $item['site_id']?>&domain_id=0" title="<?php echo $item['site_name']?>">总计</a></td>
    <td><?php echo $item['data'][0]['site_ask']?></td>
	<td><?php echo $item['data'][0]['site_order']?></td>
	<td><?php echo $item['data'][0]['site_uv']?></td>
	<td><?php echo $item['data'][0]['site_pv']?></td>
    <!--<td></td>-->
    <!--<td></td>-->
    <!--<td></td>-->
    <!--<td></td>-->
	<td rowspan="<?php echo $item['data_count']?>"><?php if($item['site_bd']):?><div class="btn-group dropdown">
<!--	<button class="btn btn-success" onClick="go_url('?c=site&m=cpc&site_id=<?php echo $item['site_id']?>')"><?php echo $this->lang->line('bd_cpc');?></button>
	<button class="btn btn-success dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
	<ul class="dropdown-menu">
		<li><a href="?c=site&m=cpc_account&site_id=<?php echo $item['site_id']?>"><?php echo $this->lang->line('cpc_account'); ?></a></li>
		<li><a href="?c=site&m=cpc_plan&site_id=<?php echo $item['site_id']?>"><?php echo $this->lang->line('cpc_plan'); ?></a></li>
	</ul>
</div><?php endif; ?><div class="btn-group dropdown">
	<button class="btn btn-inverse"><?php echo $this->lang->line('seo');?></button>
	<button class="btn btn-inverse dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
	<ul class="dropdown-menu">
	  <li><a href="?c=site&m=seo_data&site_id=<?php echo $item['site_id']?>"><?php echo $this->lang->line('included-data');?></a></li>
	  <li><a href="?c=site&m=seo_keywords_pm&site_id=<?php echo $item['site_id']?>"><?php echo $this->lang->line('keyword-pm');?></a></li>
	</ul>
</div>--> 
<button class="btn btn-primary" onClick="go_url('?c=site&m=site_info&site_id=<?php echo $item['site_id']?>')"><i class="icon-pencil"></i></button>&nbsp;<button class="btn btn-danger" onClick="go_del('?c=site&m=site_delete&site_id=<?php echo $item['site_id']?>')"><i class="icon-trash"></i></button>
</td>
  </tr>
  <?php 
  if(isset($item['data'])):
  foreach($item['data'] as $k => $v):
  if($k > 0):
  ?>
  <tr<?php if($i % 2){ echo " class='td1'";}?>>
    <td><a href="?c=site&m=data&site_id=<?php echo $v['site_id']?>&domain_id=<?php echo $v['domain_id']?>"><?php echo $v['site_domain']?></a></td>
    <td><?php echo $v['site_ask']?></td>
	<td><?php echo $v['site_order']?></td>
	<td><?php echo $v['site_uv']?></td>
	<td><?php echo $v['site_pv']?></td>
    <!--<td><?php if(!empty($v['site_visitBounceRate'])){ echo $v['site_visitBounceRate'] . "%";}?></td>-->
    <!--<td><?php if(!empty($v['site_avgTimeOnSite'])){ echo $v['site_avgTimeOnSite'] . "(s)";}?></td>-->
    <!--<td><?php if(!empty($v['site_percentNewVisits'])){ echo $v['site_percentNewVisits'] . "%";}?></td>-->
    <!--<td><?php if(!empty($v['site_pageviewsPerVisit'])){ echo $v['site_pageviewsPerVisit'];}?></td>-->
  </tr>
  <?php
  endif;
  endforeach;
  endif;
  ?>
  <?php
  $i ++;
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
</body>
</html>