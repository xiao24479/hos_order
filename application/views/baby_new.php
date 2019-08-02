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

.modal {width:80%; left:50%;margin-left:-40%;}

.modal-body {



max-height: 500px;



min-height: 380px;}

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

					<form action="" method="get" class="date_form order_form" id="order_list_form" style="height:auto;">

						<input type="hidden" value="system" name="c" />

						<input type="hidden" value="baby" name="m" />

						<div class="span4">

							<div class="row-form">

								<label class="select_label">选择疾病</label>

								<select name="jb_id" id="jb_id" style="width:180px;"> 
									<option value="">请选择疾病...</option> 
									<?php foreach($jb_list as $val):?> 
									<option <?php if($jb_id==$val['jb_id']){echo 'selected';}?> value="<?php echo $val['jb_id'];?>" ><?php echo $val['jb_name']; ?></option>
									<?php endforeach;?>
								</select> 
							    <i class=""></i> 
							</div>

						</div>

						 

						<div class="span4">

                            <button type="submit" class="btn btn-success" style="background-color:#00a186;"> 搜索 </button> 
							<a href="?c=system&m=baby_info" class="btn"> 添加 </a> 

						</div>

					</form>

				</div>
				
				<div id="page-wraper">   
				    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-hover table-bordered">

						<tr><th>排序</th><th>疾病名称</th><th>时间</th><th>可预约数量</th><th>操作</th></tr>
                       <?php if(!empty($baby)){  ?>
						<?php foreach($baby as $key=>$val):?> 
						<tr>

						    <td><?php echo $key+1?></td>
						    <td><?php echo $val['jb_name'];?></td> 
						    
							<td><span><?php echo  $val['time_start'].' ~ '.$val['time_end'];?></span></td>

							<td><?php echo $val['sum'];?></td>
  
							<td>
								<button class="btn btn-primary" onclick="go_url('?c=system&m=baby_info&id=<?php echo $val['id'];?>')" style="background-color:#00a186;"><i class="icon-pencil"></i>修改</button>
								<?php if($_COOKIE['l_rank_id'] == 1):?>
								<button class="btn" onclick="go_del('?c=system&m=baby_del&id=<?php echo $val['id'];?>')">删除</button>
								<?php endif;?>
							</td>

						</tr>

						<?php endforeach;
                                            }else{ ?>
                                                
                                                <tr width="100%"><td colspan="6" style="text-align:center;color:red;">很抱歉！还未收录相关数据！</td></tr>
                                            <?php
                                            
                                            }
                                                
                                                
                                                ?>

					</table> 
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
$(".jb_id").change(function(){
	 
});
</script>
</body>
</html>