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

<script src="static/js/jquery.js"></script>
</head>

<body class="fixed-top">

<?php echo $top; ?>

<div id="container" class="row-fluid"> 

<?php echo $sider_menu; ?>



<div id="main-content"> 

    <!-- BEGIN PAGE CONTAINER-->

    <div class="container-fluid"> 

		<?php echo $themes_color_select; ?>
		
		<div class="row-fluid">
		<form action="" method="get" class="date_form order_form" id="order_list_form">

		<input type="hidden" value="order" name="c" />

		<input type="hidden" value="dean_list" name="m" />

		<div class="span3">

			<div class="row-form">

				<label class="select_label"><?php echo $this->lang->line('patient_name');?></label>

				<input type="text" value="<?php echo $p_n; ?>" class="input-medium" name="p_n"  />

			</div>
		</div>
		<div class="span3">
			<div class="row-form">

				<label class="select_label"><?php echo $this->lang->line('patient_phone');?></label>

				<input type="text" value="<?php echo $p_p; ?>" class="input-medium" name="p_p"  />

			</div>
		</div>

		<div class="order_btn" style="left:710px;top:123px;">

		   <button type="submit" class="btn btn-success"> 搜索 </button> 

		</div>

		</form>

	<div class="row-fluid">

		<div class="span12">

		<table width="100%" border="0" cellspacing="0" cellpadding="2" class="list_table">

		<thead>

		  <tr>

			<th width="30">#</th>

			<th>姓名</th>

			<th >电话</th>

			<th>邮箱</th>
			<th width="200">主题</th>
			<th width="200">留言</th>
			<th>操作</th>
		  </tr>

		</thead>

		<tbody>

		  <?php

		  $i = 0;

		  foreach($message_list as $item):

		  ?>

  <tr<?php if($i % 2){ echo " class='td1'";}?> style="height:90px;">

    <td><b><?php echo $now_page + $i + 1; ?></b></td>
	
	<td style="text-align:left;">

    <div id="pat_<?php echo $item['ask_id']; ?>">

		姓名：<font color='#FF0000'><b id="pat_name_<?php echo $item['ask_id']; ?>"><?php echo $item['name']?></b></font>

    </div>

    </td>

	<td style="text-align:left;">
		<?php if($rank_type == 2 || $_COOKIE['l_admin_action'] == 'all'):?>
		
		 电话：<font id="pat_phone_<?php echo $item['ask_id']; ?>"><?php echo $item['phone']; ?></font><br />

        <?php else:?>
        

        电话：<font id="pat_phone_<?php echo $item['ask_id']; ?>"><?php echo substr($item['phone'],0, -4) . "****"; ?></font><br />

        <?php endif;?>
	</td>

	<td>

	<?php

      if(empty($item['email'])){echo '没有填写邮箱';}else{echo '<a href="mailto:'.$item['email'].'">'.$item['email'];}

	?></td>

	<td style="position:relative;">
    <div class="remark" id="notice_<?php echo $item['ask_id']; ?>">
		<?php echo $item['topic']; ?>
    </div>
	</td>
	<td style="position:relative;">
    <div class="remark" id="visit_<?php echo $item['ask_id']; ?>">
		<?php echo $item['content']; ?>
    </div>
	</td>
	<td id="action_<?php echo $item['ask_id']; ?>">
		<button class="btn btn-primary" onclick="go_del('?c=show&m=dean_del&ask_id=<?php echo $item['ask_id']; ?>')">删除</button>
	</td>
  </tr>

  <?php 

  $i ++;

  endforeach; ?>

  </tbody>

  </table>

<?php echo $page; ?>

<div style="margin-bottom:30px;"></div>
</div>

</div>

</div>

</div>

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