<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8" />
<title><?php echo $msg_detail; ?></title>
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
.msg {
width: 286px;
border: 2px solid #4A8BC2;
margin: 80px auto;
padding: 10px;
zoom: 1;
overflow: hidden;
}
.msg .img {
float: left;
width: 32px;
padding-right: 20px;
padding-top: 0px;
}
.msg .info {
float: left;
width: 224px;
padding-top: 5px;
}
</style>
</head>

<body class="fixed-top" style="background-color:#FFFFFF;">
<div class="msg">
  <div class="img">
    <img src="static/images/information.gif" width="32" height="32" border="0" alt="information" />
  </div>
  <div class="info">
  <?php echo $msg_detail; ?>
  </div>
  <div style="float: right;
margin-top: 20px;"><input class="btn btn-info" type="button" value="关闭" onclick="window.close();" /></div>
</div>
</body>
</html>