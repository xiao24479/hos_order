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
<link rel="stylesheet" type="text/css" href="static/css/chosen.css" />
<script type="text/javascript">
function check(){
var re_pwd = document.getElementById("re_pwd").value;
var pwd= document.getElementById("pwd").value;
if(re_pwd !=pwd){
alert("确认密码不一致！");
}else if(pwd.length < 6){
    alert("密码不能低于6位");

    return false;
}

return true;
}


</script>
</head>
<body class="fixed-top">


<div class="widget red" >
					<div class="widget-title">
					<h4><i class="icon-reorder"></i> <?php echo $this->lang->line('re_pass'); ?></h4>
					<span class="tools">
						<a href="javascript:;" class="icon-chevron-down"></a>
						<a href="javascript:;" class="icon-remove"></a>
					</span>
					</div>
					<div class="widget-body">
<form action="?c=index&m=change_pass" method="post" class="form-horizontal" >
<div class="control-group">
	
	<div class="controls">
		请输入新修改的密码！
	</div>
</div>
    <div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('admin_password');?></label>
	<div class="controls">
		<input type="password" value="" class="input-xlarge" name="admin_password" />
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('re_password');?></label>
	<div class="controls">
		<input type="password" value="" class="input-xlarge" name="re_password" id="pwd"/>
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('confirm_password');?></label>
	<div class="controls">
		<input type="password" value="" class="input-xlarge" name="confirm_password" id="re_pwd"/>
	</div>
</div>
<div class="control-group">
	<div class="controls">
            <input type="hidden" name="admin_id" value="<?php echo $_COOKIE['l_admin_id'];?>"/><input type="submit" name="submit" value="<?php echo $this->lang->line('submit'); ?>" class="btn btn-danger" onclick="return check()"/> <input name="reset" type="reset" value="<?php echo $this->lang->line('reset'); ?>" class="btn"/>
	</div>
</div>
</form>
 </div>
</div>
</div>
</div>
</div>
</div>
</div>

   <script src="static/js/jquery.js"></script>
   <script src="static/js/jquery.min.js"></script>
   <script src="static/js/jquery.nicescroll.js" type="text/javascript"></script>
   <script src="static/js/bootstrap.min.js"></script>
   <script type="text/javascript" src="static/js/bootstrap-datepicker.js"></script>
   <script src="static/js/chosen.jquery.js"></script>
   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="static/js/excanvas.js"></script>
   <script src="static/js/respond.js"></script>
   <![endif]-->
   
</body>
</html>